<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php?error=not_logged_in");
    exit;
}

require_once "../koneksi.php";
require_once "../controllers/user/dashboard.php";

$user_id = $_SESSION['user_id'];
$controller = new DashboardController($db);

// Ambil ringkasan laporan untuk user
$summary = $controller->getSummary($user_id);

// Ambil aktivitas terbaru user
$recent = $controller->getRecentActivity($user_id);

$user_id = $_SESSION['user_id'];
$controller = new DashboardController($db);

// Ambil parameter dari URL
$filterStatus = $_GET['filter'] ?? ''; // dari dropdown filter (New, Dalam Proses, dll)
$searchStatus = $_GET['status'] ?? ''; // dari search popup
$startDate = $_GET['start'] ?? '';
$endDate = $_GET['end'] ?? '';

// Ambil aktivitas terbaru sesuai filter/search
$recent = $controller->getRecentActivity($user_id, $startDate, $endDate, $searchStatus, $filterStatus);

?>

<?php include 'sidebar.php'; ?>
<?php include 'header.php'; ?>
<?php include 'edit.php'; ?>
<?php include '../alert.php'; showAlert(); ?>

<link rel="stylesheet" href="../assets/css/style.css">

<div class="main-content">

    <h2 class="section-title">Aktivitas Terbaru</h2>

    <div class="recent-container">

        <div class="filter-wrapper">
            <button class="filter-btn">
                <i class="fi fi-sr-filter"></i>
                Filter
                <i class="fi fi-br-angle-small-down dropdown-icon"></i>
            </button>

           <div class="filter-menu">
                <a href="user_dashboard.php" class="<?= ($filterStatus=='all')?'active':'' ?>">All</a>
                <hr>
                <a href="user_dashboard.php?filter=baru" class="<?= ($filterStatus=='baru')?'active':'' ?>">New</a>
                <a href="user_dashboard.php?filter=diproses" class="<?= ($filterStatus=='diproses')?'active':'' ?>">Dalam Proses</a>
                <a href="user_dashboard.php?filter=selesai" class="<?= ($filterStatus=='selesai')?'active':'' ?>">Selesai</a>
                <a href="user_dashboard.php?filter=tidak_valid" class="<?= ($filterStatus=='tidak_valid')?'active':'' ?>">Tidak Valid</a>
            </div>
        </div>

        <!-- ====== START LOOP ====== -->
        <?php if(count($recent) > 0): ?>
            <?php foreach ($recent as $row): ?>
                <div class="recent-item">
                    <div class="recent-left">
                        <img class="recent-thumb" src="../uploads/<?= $row['foto'] ?: 'noimg.png' ?>"
                            onclick="openImage('../uploads/<?= $row['foto'] ?>')">
                        <div class="recent-info">
                            <div class="recent-title"><?= $row['judul_laporan'] ?></div>
                            <div class="recent-location"><?= $row['lokasi'] ?></div>
                            <div class="recent-desc"><?= $row['deskripsi'] ?></div>
                        </div>
                    </div>
                    <div class="recent-right">
                        <div class="recent-date">
                            <?= date("d M Y • H:i", strtotime($row['tanggal_lapor'])) ?>
                        </div>
                        <div class="status-wrapper">
                            <?php
                                $statusClass = [
                                    'baru' => 'status-baru',
                                    'diproses' => 'status-diproses',
                                    'selesai' => 'status-selesai',
                                    'tidak_valid' => 'status-tidakvalid'
                                ];
                                $class = $statusClass[$row['status']] ?? 'status-default';
                            ?>
                            <span class="badge <?= $class ?>">
                                <?= ucwords(str_replace('_', ' ', $row['status'])) ?>
                            </span>

                            <div class="dropdown">
                                <button class="dropdown-btn">
                                    <i class="fi fi-rr-menu-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a href="#" onclick="openEdit(<?= $row['id_laporan'] ?>)">Edit</a>
                                    <a href="../controllers/report/delete.php?id=<?= $row['id_laporan'] ?>">Hapus</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-data">
                Tidak ada laporan untuk filter/search ini.
            </div>
        <?php endif; ?>

        <!-- ====== END LOOP ====== -->

    </div>

</div>

<div id="imageModal" class="img-modal">
    <span class="close-btn" onclick="closeImage()">×</span>
    <img id="modalImg" class="modal-content">
</div>

<script>
    document.querySelector('.filter-btn').addEventListener('click', function () {
        const menu = document.querySelector('.filter-menu');
        menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    });

    document.querySelectorAll('.dropdown').forEach(drop => {
        const btn = drop.querySelector('.dropdown-btn');
        const menu = drop.querySelector('.dropdown-menu');

        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            document.querySelectorAll('.dropdown-menu').forEach(m => {
                if (m !== menu) m.style.display = 'none';
            });

            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        });

    });

    document.addEventListener('click', function () {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.style.display = 'none';
        });
    });

    function openImage(src) {
        document.getElementById("imageModal").style.display = "block";
        document.getElementById("modalImg").src = src;
    }

    function closeImage() {
        document.getElementById("imageModal").style.display = "none";
    }

    // Klik area luar gambar → close
    document.getElementById("imageModal").addEventListener("click", function(e) {
        if (e.target === this) {
            closeImage();
        }
    });

    document.querySelectorAll('.filter-menu a').forEach(link => {
        link.addEventListener('click', function(e){
            // jika href kosong atau all, kembalikan halaman tanpa query filter
            if(this.getAttribute('href') === 'user_dashboard.php'){
                window.location = 'user_dashboard.php';
            } else {
                window.location = this.getAttribute('href');
            }
        });
    });


</script>
