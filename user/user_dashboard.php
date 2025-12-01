<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php?error=not_logged_in");
    exit;
}

require_once "../koneksi.php";
require_once "../controllers/user/dashboard.php";
require_once "../controllers/nontifikasi/nontifikasi.php";

$user_id = $_SESSION['user_id'];
$controller = new DashboardController($db);

if (isset($_GET['status']) && $_GET['status'] === 'selesai') {
    header("Location: riwayat.php?status=selesai");
    exit;
}

$totalLaporan = $controller->getTotalLaporan($user_id);

// Ambil ringkasan laporan untuk user
$summary = $controller->getSummary($user_id);

// Ambil parameter dari URL
$filterStatus = $_GET['filter'] ?? ''; // dari dropdown filter (New, Dalam Proses, dll)
$searchStatus = $_GET['status'] ?? ''; // dari search popup
$startDate = $_GET['start'] ?? '';
$endDate = $_GET['end'] ?? '';
$query = $_GET['query'] ?? '';

$recent = $controller->getRecentActivity(
    $user_id,
    $startDate,
    $endDate,
    $searchStatus, // status dari search popup
    $filterStatus,
    $query         // keyword search
);


$notifController = new NotificationController($db);

$userId = $_SESSION['user_id'];
$unread = $notifController->getUnreadCount($userId, 'user');
$listNotif = $notifController->getNotifications($userId, 'user');

// Ambil aktivitas terbaru sesuai filter/search


?>

<?php include 'sidebar.php'; ?>
<?php include 'header.php'; ?>

<?php include '../alert.php'; showAlert(); ?>

<link rel="stylesheet" href="../css/user.css">

<div class="main-content">

    <h2 class="section-title">Aktivitas Terbaru</h2>

    <div class="recent-container" id="resultContainer">
       


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
                        <?php if (!empty($row['tanggal_update'])): ?>
                            <div class="recent-update" style="font-size:12px; color:#777;">
                                Updated: <?= date("d M Y • H:i", strtotime($row['tanggal_update'])) ?>
                            </div>
                        <?php endif; ?>
                        
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

                            <?php if ($row['status'] == 'baru'): ?>
                            <div class="dropdown">
                                <button class="dropdown-btn" type="button">
                                    <i class="fi fi-rr-menu-dots-vertical"></i>
                                </button>

                                <div class="dropdown-menu">
                                    <a href="#"
                                        onclick='openEdit(
                                            <?= json_encode($row["id_laporan"]) ?>,
                                            <?= json_encode($row["judul_laporan"]) ?>,
                                            <?= json_encode($row["lokasi"]) ?>,
                                            <?= json_encode($row["deskripsi"]) ?>,
                                            <?= json_encode($row["foto"]) ?>
                                        ); return false;'>
                                            Edit
                                    </a>
                                    <a href="#" onclick="confirmDelete(<?= $row['id_laporan'] ?>)">Hapus</a>
                                </div>
                            </div>
                        <?php endif; ?>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php else: ?>

            <?php if ($totalLaporan == 0): ?>
                <div class="no-data">
                    Anda belum membuat laporan.
                </div>
            <?php else: ?>
                <div class="no-data">
                    Tidak ada laporan untuk filter/search ini.
                </div>
            <?php endif; ?>

        <?php endif; ?>
        <!-- ====== END LOOP ====== -->
    </div>

</div>

<div id="deleteModal" class="modal">
    <div class="modal-content">
        <i class="fi fi-sr-info"></i>
        <p>Apakah anda yakin untuk dihapus?</p>

        <div class="btn-group">
            <button id="btnYes" class="btn-yes">YA</button>
            <button id="btnNo" class="btn-no">TIDAK</button>
        </div>
    </div>
</div>


<div id="imageModal" class="img-modal">
    <span class="close-btn" onclick="closeImage()">×</span>
    <img id="modalImg" class="modal-content">
</div>
<?php include 'edit.php'; ?>

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

    // === MANUAL SEARCH ===
function manualSearch() {
    let q = document.getElementById("manualSearch").value.trim();
    let start = document.getElementById("dateStart")?.value;
    let end = document.getElementById("dateEnd")?.value;
    let status = document.getElementById("statusSelect")?.value;

    // jika input kosong → kembali ke asli
    if (!q && !start && !end && !status) {
        window.location = "user_dashboard.php";
        return;
    }

    let url = "user_dashboard.php?";

    if (q) url += "query=" + encodeURIComponent(q) + "&";
    if (start) url += "start=" + start + "&";
    if (end) url += "end=" + end + "&";
    if (status) url += "status=" + status + "&";

    url = url.slice(0, -1);
    window.location = url;
}

// === ENTER KEY: jangan tutup popup, jangan hilang X ===
document.getElementById("manualSearch").addEventListener("keypress", function(e){
    if (e.key === "Enter") {
        e.preventDefault();  // stop submit form atau reload
        manualSearch();      // tetap lakukan search
    }
});

// === X: kembalikan halaman seperti semula ===
closeSearch.addEventListener("click", function(e) {
    e.stopPropagation();
    window.location = "user_dashboard.php";  // reset semua filter
});

// === buka popup normal ===
searchBox.addEventListener("click", function(e) {
    popup.style.display = "block";
    closeSearch.style.display = "block";
    searchBox.classList.add("input-expanded");
    e.stopPropagation();
});

// klik di luar → tutup popup tapi jangan reload
document.addEventListener("click", function(e) {
    if (!searchBox.contains(e.target) && !popup.contains(e.target)) {
        popup.style.display = "none";
        closeSearch.style.display = "none";
        searchBox.classList.remove("input-expanded");
    }
});

document.addEventListener("DOMContentLoaded", function() {
    let urlParams = new URLSearchParams(window.location.search);

    if (
        urlParams.get("query") ||
        urlParams.get("start") ||
        urlParams.get("end") ||
        urlParams.get("status")
    ) {
        // buka popup otomatis kalau ada filter
        document.getElementById("searchPopup").style.display = "block";
        document.getElementById("closeSearch").style.display = "block";
        document.getElementById("searchBox").classList.add("input-expanded");
    }
});

document.addEventListener("DOMContentLoaded", function() {
    let urlParams = new URLSearchParams(window.location.search);

    // isi ulang input jika ada di URL
    if (urlParams.get("query")) {
        document.getElementById("manualSearch").value = urlParams.get("query");
    }
    if (urlParams.get("start")) {
        document.getElementById("dateStart").value = urlParams.get("start");
    }
    if (urlParams.get("end")) {
        document.getElementById("dateEnd").value = urlParams.get("end");
    }
    if (urlParams.get("status")) {
        document.getElementById("statusSelect").value = urlParams.get("status");
    }
});

let deleteId = null;

function confirmDelete(id) {
    deleteId = id;
    document.getElementById("deleteModal").style.display = "flex";
}

document.getElementById("btnNo").addEventListener("click", () => {
    document.getElementById("deleteModal").style.display = "none";
});

document.getElementById("btnYes").addEventListener("click", () => {
    window.location.href = "../controllers/user/delete.php?id=" + deleteId;
});

</script>
