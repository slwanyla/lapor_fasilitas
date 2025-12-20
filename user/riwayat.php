<?php
session_start();
$user_id = $_SESSION['user_id'];

require_once "../koneksi.php";
require_once "../controllers/user/riwayatcontroller.php";
require_once "../controllers/nontifikasi/nontifikasi.php";

$notifController = new NotificationController($db);

$userId = $_SESSION['user_id'];
$unread = $notifController->getUnreadCount($userId, 'user');
$listNotif = $notifController->getNotifications($userId, 'user');

$controller = new RiwayatController($db);

// Ambil data
$riwayat = $controller->getRiwayatSelesai($user_id);
?>

<?php include 'sidebar.php'; ?>
<?php include 'header.php'; ?>
<?php include '../alert.php'; showAlert(); ?>

<div class="content-riwayat">

    <h2 class="riwayat-title">Riwayat Laporan</h2>

    <div class="riwayat-container">

    <?php if ($riwayat === null || empty($riwayat)): ?>

        <div class="no-riwayat">Tidak ada riwayat selesai.</div>

    <?php else: ?>

        <?php foreach ($riwayat as $row): ?>

            <div class="riwayat-item">

                <div class="riwayat-header" onclick="toggleDetail(<?= $row['id'] ?>)">
                    
                    <div class="left">
                        <div class="title"><?= htmlspecialchars($row['judul_laporan']) ?></div>
                        <div class="desc-small"><?= htmlspecialchars($row['deskripsi']) ?></div>
                        <div class="date">
                            <?= date("d M Y • H:i", strtotime($row['tanggal_lapor'])) ?>
                            <?php if ($row['tanggal_update']): ?>
                                <span style="color:#888;"> • Updated: <?= date("d M Y • H:i", strtotime($row['tanggal_update'])) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="right">
                        <span class="badge status-<?= $row['status'] ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                        <i class="fi fi-ss-angle-small-down expand-icon" id="icon-<?= $row['id'] ?>"></i>
                    </div>
                </div>

                <div class="riwayat-detail" id="detail-<?= $row['id'] ?>">
                    <div class="detail-box">
                        <p><b>Lokasi:</b> <?= htmlspecialchars($row['lokasi']) ?></p>
                        <p><?= htmlspecialchars($row['deskripsi']) ?></p>

                        <?php if (!empty($row['foto'])): ?>
                            <img src="../uploads/<?= $row['foto'] ?>" class="detail-img">
                        <?php endif; ?>
                    </div>
                </div>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

    </div>
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

 function openLogoutModal() {
            document.getElementById("logoutModal").style.display = "flex";
        }

        function closeLogoutModal() {
            document.getElementById("logoutModal").style.display = "none";
        }

        // Klik di luar modal untuk menutup
        window.onclick = function(event) {
            const modal = document.getElementById("logoutModal");
            if (event.target === modal) {
                modal.style.display = "none";
            }
        }

</script>
