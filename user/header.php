<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/user.css">
    <!-- UIcons Regular Straight (buat fi-rs-cross-small) -->
<link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-regular-straight/css/uicons-regular-straight.css">
    

       
    <title>User Dashboard</title>
</head>

<body>

    <header class="header">

        <div class="left-area">
            <div class="toggle-btn" onclick="toggleSidebar()">
                <i class="fi fi-br-align-justify"></i>
            </div>

            <div class="user-profile">
                <img src="../pfp/icon.jpg">
                <div class="user-text">
                        <strong><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></strong><br>
                        <?php if(isset($_SESSION['user_nim'])): ?>
                            <small><?= htmlspecialchars($_SESSION['user_nim']) ?></small>
                        <?php elseif(isset($_SESSION['user_nidn'])): ?>
                            <small><?= htmlspecialchars($_SESSION['user_nidn']) ?></small>
                        <?php elseif(isset($_SESSION['user_nip'])): ?>
                            <small><?= htmlspecialchars($_SESSION['user_nip']) ?></small>
                        <?php endif; ?>
                </div>
            </div>
        </div>

        <h2 class="header-title">Lapor Kampus</h2>

        <div class="header-right">
           <div class="notif-wrapper">
                <div class="notif-icon" onclick="toggleNotif()">
                    <i class="fi fi-rr-bell"></i>
                    <?php if($unread > 0): ?>
                        <span class="notif-badge"><?= $unread ?></span>
                    <?php endif; ?>
                </div>

                <div id="notifPopup" class="notif-popup">
                
                <div class="notif-header">
                    <h3>Notifications</h3>
                </div>

                <div class="notif-list">
                    <?php foreach($listNotif as $n): ?>
                        <div class="notif-item <?= $n['status_baca'] == 0 ? 'unread' : '' ?>">
    
                            <div class="notif-icon-left">
                                <i class="fi fi-sr-megaphone"></i>
                            </div>

                            <div class="notif-text">
                                <p><?= $n['isi_notifikasi'] ?></p>
                            </div>

                            <div class="notif-time-right">
                                <?= $n['tanggal_notifikasi'] ?>
                            </div>

                        </div>

                    <?php endforeach; ?>
                </div>

                <form method="POST" action="../controllers/nontifikasi/markread.php" class="mark-bottom">
                    <button class="mark-read-btn">Mark all read</button>
                </form>
            </div>

    
        </div>

            <div class="search-box" id="searchBox">
                <i class="fi fi-rs-cross-small close-search" id="closeSearch"></i>
                <input type="text" id="manualSearch" placeholder="Search...">
                <button onclick="manualSearch()"> <i class="fi fi-rr-search"></i> </button>

            </div>

            <div class="search-popup" id="searchPopup">
                
                <div class="popup-content">
                    <div class="row">
                        <div class="col">
                            <label>Dari tanggal</label>
                            <input type="date" id="dateStart">
                        </div>

                        <div class="col">
                            <label>Sampai tanggal</label>
                            <input type="date" id="dateEnd">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <label>Status</label>
                            <select id="statusSelect">
                                <option value="">Semua</option>
                                <option value="baru">Baru</option>
                                <option value="diproses">Diproses</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>

                        <div class="col">
                            <button class="btn-search" onclick="applySearch()">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</header>

<script>

    function manualSearch() {
    let q = document.getElementById("manualSearch").value;
    let start = document.getElementById("dateStart")?.value;
    let end = document.getElementById("dateEnd")?.value;
    let status = document.getElementById("statusSelect")?.value;

    let url = 'user_dashboard.php?';

    if(q) url += 'query=' + encodeURIComponent(q) + '&';
    if(start) url += 'start=' + start + '&';
    if(end) url += 'end=' + end + '&';
    if(status) url += 'status=' + status + '&';

    url = url.slice(0,-1); // hapus &
    window.location = url;
}

// Enter key di search input
document.getElementById("manualSearch").addEventListener("keypress", function(e){
    if(e.key === "Enter") manualSearch();
});
function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("closed");
}

const searchBox = document.getElementById("searchBox");
const popup = document.getElementById("searchPopup");
const closeSearch = document.getElementById("closeSearch");

// buka popup & munculkan X
searchBox.addEventListener("click", function(e) {
    popup.style.display = "block";
    closeSearch.style.display = "block";

    // geser input dikit
    searchBox.classList.add("input-expanded");

    e.stopPropagation();
});

// Klik X
closeSearch.addEventListener("click", function(e) {
    popup.style.display = "none";
    closeSearch.style.display = "none";

    // kembalikan input ke posisi awal
    searchBox.classList.remove("input-expanded");

    e.stopPropagation();
});

// Klik luar → popup tutup + X hilang
document.addEventListener("click", function(e) {
    if (!searchBox.contains(e.target) && !popup.contains(e.target)) {
        popup.style.display = "none";
        closeSearch.style.display = "none";

        searchBox.classList.remove("input-expanded");
    }
});


function applySearch() {
    let start = document.getElementById("dateStart").value;
    let end = document.getElementById("dateEnd").value;
    let status = document.getElementById("statusSelect").value;

    // bikin URL query
    let url = 'user_dashboard.php?';
    if(start) url += 'start=' + start + '&';
    if(end) url += 'end=' + end + '&';
    if(status) url += 'status=' + status + '&';

    // hapus & terakhir
    url = url.slice(0, -1);

    window.location = url;
}

function toggleNotif() {
    const popup = document.getElementById("notifPopup");
    popup.classList.toggle("show");
}

// Tutup popup kalo klik luar
document.addEventListener("click", function(e){
    const popup = document.getElementById("notifPopup");
    const icon = document.querySelector(".notif-icon");

    if (!popup.contains(e.target) && !icon.contains(e.target)) {
        popup.classList.remove("show");
    }
});

function manualSearch() {
    let q = document.getElementById("manualSearch").value;
    window.location = "user_dashboard.php?query=" + encodeURIComponent(q);
}


</script>