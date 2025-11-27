<?php 

session_start();

require_once "../koneksi.php";
require_once "../controllers/admin/dashboard.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$controller = new AdminDashboardController($db);
$allLaporan = $controller->getAllLaporan(); 
$total = $controller->getTotalLaporan();


$result = [];
$result['data'] = $controller->getAllLaporan();
$result['total'] = $controller->getTotalLaporan();
// Siapkan variabel ke view
$summary = $result['summary'];
$data = $result['data'];
$total = $result['total'];

include "sidebar.php";
include "header.php";
include '../alert.php'; showAlert(); 
?>

<div class="main-content">
    <h2>Dashboard</h2>

    <div class="stats-container">
        <div class="stat-box blue">New reports<br><span>7</span></div>
        <div class="stat-box yellow">In Progress<br><span>44</span></div>
        <div class="stat-box green">Completed<br><span>128</span></div>
        <div class="stat-box cyan">Total Reports<br><span>777</span></div>
    </div>

    <div class="table-container">
        <h3>Data Laporan</h3>


        <div class="table-wrapper">
            <table class="laporan-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NIM/NIDN/NIK</th>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Prodi</th>
                        <th>Judul</th>
                        <th>Lokasi</th>
                        <th>Deskripsi</th>
                        <th>Foto</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
    <?php 
    $no = 1; 
    foreach($data as $row):

    // identitas
    if (!empty($row['nim'])) {
        $identitas = $row['nim'];
    } elseif (!empty($row['nidn'])) {
        $identitas = $row['nidn'];
    } elseif (!empty($row['nip'])) {
        $identitas = $row['nip'];
    } else {
        $identitas = '-';
    }

    $namaUser = !empty($row['user_nama']) ? $row['user_nama'] : '-';
    ?>

    <tr>
        <td><?= $no++; ?></td> <!-- Nomor urut -->
        <td><?= $identitas; ?></td>
        <td><?= $namaUser; ?></td>
        <td><?= ucfirst($row['role']); ?></td>
        <td><?= ($row['role'] === 'mahasiswa') ? htmlspecialchars($row['prodi']) : '-' ?></td>
        <td><?= $row['judul_laporan']; ?></td>
        <td><?= $row['lokasi']; ?></td>
        <td><?= $row['deskripsi']; ?></td>

        <td>
            <?php if ($row['foto']): ?>
                <img src="../uploads/<?= $row['foto']; ?>" class="img-thumbnail">
            <?php else: ?>
                -
            <?php endif; ?>
        </td>

        <td>
            <span class="status-badge <?= $row['status']; ?>">
                <?= ucfirst(str_replace("_"," ",$row['status'])); ?>
            </span>
        </td>

        <td class="aksi">
            <a href="view.php?id=<?= $row['id_laporan']; ?>" class="btn-view">
                <i class="fi fi-sr-eye"></i>
            </a>
            <a href="edit.php?id=<?= $row['id_laporan']; ?>" class="btn-edit">
                <i class="fi fi-rr-edit"></i>
            </a>
            <a href="delete.php?id=<?= $row['id_laporan']; ?>" class="btn-delete">
                <i class="fi fi-br-trash"></i>
            </a>
        </td>
    </tr>

    <?php endforeach; ?>
</tbody>


            </table>
        </div>
    </div>

    </div>
</div>
