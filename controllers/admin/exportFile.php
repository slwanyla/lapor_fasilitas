<?php
require_once __DIR__ . "/../../koneksi.php";
require_once __DIR__ . "/../../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

// Ambil filter dari URL
$search = $_GET['search'] ?? "";
$status = $_GET['status'] ?? "";
$from   = $_GET['from'] ?? "";
$to     = $_GET['to'] ?? "";

// QUERY DATA + JOIN USER
$sql = "
    SELECT 
        laporan.*,
        user.nama,
        user.role,
        user.email,
        user.nim,
        user.nidn,
        user.nip,
        user.prodi
    FROM laporan
    LEFT JOIN user ON user.id = laporan.id_user
    WHERE laporan.is_deleted = 0
";

$params = [];

// Filter search
if (!empty($search)) {
    $sql .= " AND (laporan.judul_laporan LIKE ? OR laporan.deskripsi LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Filter status
if (!empty($status)) {
    $sql .= " AND laporan.status = ?";
    $params[] = $status;
}

// Filter tanggal
if (!empty($from) && !empty($to)) {
    $sql .= " AND DATE(laporan.tanggal_lapor) BETWEEN ? AND ?";
    $params[] = $from;
    $params[] = $to;
}

$sql .= " ORDER BY laporan.tanggal_lapor DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// HTML TABEL PDF
$html = '
<style>
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}
th {
    background: #4a76e8;
    padding: 8px;
    color: #fff;
    border: 1px solid #ddd;
}
td {
    padding: 6px;
    border: 1px solid #ddd;
}
.title {
    text-align: center;
    font-size: 20px;
    margin-bottom: 10px;
    font-weight: bold;
}
</style>

<div class="title">Laporan Fasilitas — Export PDF</div>
<table>
<tr>
    <th>No</th>
    <th>Judul</th>
    <th>Deskripsi</th>
    <th>Lokasi</th>

    <th>Nama User</th>
    <th>Role</th>
    <th>Email</th>
    <th>Identitas</th>

    <th>Status</th>
    <th>Tanggal Lapor</th>
</tr>
';

$no = 1;
foreach ($data as $row) {

    // IDENTITAS — SATU KOLOM
    $identitas = "-";

    if ($row['role'] === 'mahasiswa') {
        $identitas = "NIM: {$row['nim']} ({$row['prodi']})";
    } 
    elseif ($row['role'] === 'dosen') {
        $identitas = "NIDN: {$row['nidn']}";
    } 
    elseif ($row['role'] === 'staff') {
        $identitas = "NIP: {$row['nip']}";
    } 
    elseif ($row['role'] === 'admin') {
        $identitas = "Admin";
    }

    $html .= "
    <tr>
        <td>$no</td>
        <td>{$row['judul_laporan']}</td>
        <td>{$row['deskripsi']}</td>
        <td>{$row['lokasi']}</td>

        <td>{$row['nama']}</td>
        <td>{$row['role']}</td>
        <td>{$row['email']}</td>
        <td>$identitas</td>

        <td>{$row['status']}</td>
        <td>{$row['tanggal_lapor']}</td>
    </tr>
    ";

    $no++;
}

$html .= "</table>";

// DOMPDF CONFIG
$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape'); // bisa portrait
$dompdf->render();

// Download PDF
$dompdf->stream("laporan_fasilitas.pdf", ["Attachment" => true]);
exit;
?>
