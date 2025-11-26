<?php
session_start();
$user_id = $_SESSION['id_user'];

require_once "../koneksi.php";
require_once "../controllers/user/riwayatcontroller.php";

$controller = new RiwayatController($db);

// Ambil data
$riwayat = $controller->getRiwayatSelesai($user_id);
$laporan = $controller->getRiwayatLaporan($user_id);
?>

<?php include 'sidebar.php'; ?>
<?php include 'header.php'; ?>
<?php include '../alert.php'; showAlert(); ?>

<h2 class="riwayat-title">Riwayat Laporan</h2>

<div class="riwayat-container">


<?php
// Jika tidak ada data selesai ➝ tampilkan placeholder
if ($riwayat === null || empty($riwayat)): ?>

    <div class="no-riwayat">
        Tidak ada riwayat selesai.
    </div>


<?php else: ?>

    <?php foreach ($riwayat as $row): ?>

        <div class="riwayat-item">

            <div class="riwayat-header" onclick="toggleDetail(<?= $row['id_laporan'] ?>)">
                <div class="left">
                    <div class="title"><?= $row['judul_laporan'] ?></div>
                    <div class="date"><?= date("d M Y • H:i", strtotime($row['tanggal_lapor'])) ?></div>
                </div>

                <div class="right">
                    <span class="badge status-selesai">Selesai</span>
                    <i class="fi fi-ss-angle-small-down expand-icon" id="icon-<?= $row['id_laporan'] ?>"></i>
                </div>
            </div>

            <div class="riwayat-detail" id="detail-<?= $row['id_laporan'] ?>">
                <p><b>Lokasi:</b> <?= $row['lokasi'] ?></p>
                <p><?= $row['deskripsi'] ?></p>

                <?php if ($row['foto']): ?>
                    <img src="../uploads/<?= $row['foto'] ?>" class="detail-img">
                <?php endif; ?>
            </div>

        </div>

    <?php endforeach; ?>

<?php endif; ?>

</div>

<script>
function toggleDetail(id) {
    const detail = document.getElementById("detail-" + id);
    const icon = document.getElementById("icon-" + id);

    if (detail.style.display === "block") {
        detail.style.display = "none";
        icon.classList.remove("rotate");
    } else {
        detail.style.display = "block";
        icon.classList.add("rotate");
    }
}
</script>
