<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin.css">
    <!-- UIcons Regular Straight (buat fi-rs-cross-small) -->
<link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-regular-straight/css/uicons-regular-straight.css">
    

       
    <title>Admin Dashboard</title>
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
            <div class="notif-icon">
                <i class="fi fi-rr-bell"></i>
            </div>

            <div class="search-box" id="searchBox">
                 <i class="fi fi-rs-cross-small close-search" id="closeSearch"></i>
                <input type="text" placeholder="Search...">
                <button><i class="fi fi-rr-search"></i></button>
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



</script>




