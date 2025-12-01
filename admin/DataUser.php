<?php
session_start();

require_once "../koneksi.php";
require_once "../controllers/admin/DataUserController.php";
require_once "../controllers/nontifikasi/nontifikasi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Ambil user id
$user_id = $_SESSION['user_id'];

// Notifikasi
$notifController = new NotificationController($db);
$unread = $notifController->getUnreadCount($user_id, 'admin');
$listNotif = $notifController->getNotifications($user_id, 'admin');

// Search keyword pengguna
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : "";


// Ambil data pengguna
$controller = new AdminPenggunaController($db);
$dataUser = $controller->getPengguna($keyword);

include "sidebar.php";
include "header.php";
include '../alert.php'; 
showAlert(); 
?>


<div class="main-content">
    <h2>Data Pengguna</h2>

    <div class="table-container">
        <h3>List Pengguna</h3>

        <div class="table-wrapper">
            <table class="laporan-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>NIM</th>
                        <th>NIDN</th>
                        <th>NIP</th>
                        <th>Prodi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($dataUser as $u): 
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $u['nama'] ?></td>
                            <td><?= $u['email'] ?></td>
                            <td><?= ucfirst($u['role']) ?></td>
                            <td><?= $u['nim'] ?: '-' ?></td>
                            <td><?= $u['nidn'] ?: '-' ?></td>
                            <td><?= $u['nip'] ?: '-' ?></td>
                            <td><?= $u['prodi'] ?: '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let urlParams = new URLSearchParams(window.location.search);
    let keyword = urlParams.get("keyword");

    // === isi ulang search kalau ada keyword ===
    if (keyword) {
        document.getElementById("manualSearch").value = keyword;

        // otomatis buka popup
        popup.style.display = "block";
        closeSearch.style.display = "block";
        searchBox.classList.add("input-expanded");
    }
});

// === ENTER: search tapi jangan hilang popup atau text ===
document.getElementById("manualSearch").addEventListener("keypress", function(e){
    if (e.key === "Enter") {
        e.preventDefault(); // supaya popup tidak tertutup
        manualSearch();
    }
});

// === fungsi manual search ===
function manualSearch() {
    let q = document.getElementById("manualSearch").value.trim();

    if (!q) {
        window.location = "DataUser.php"; // kosong → reset
        return;
    }

    window.location = "DataUser.php?keyword=" + encodeURIComponent(q);
}

// === tombol X → reset semua ===
closeSearch.addEventListener("click", function(e) {
    e.stopPropagation();
    window.location = "DataUser.php"; // balik ke awal
});

// === klik di luar → tutup popup tapi text tetap ada ===
document.addEventListener("click", function(e) {
    if (!searchBox.contains(e.target) && !popup.contains(e.target)) {
        popup.style.display = "none";
        closeSearch.style.display = "none";
        searchBox.classList.remove("input-expanded");
    }
});

// === klik search box → buka popup ===
searchBox.addEventListener("click", function(e) {
    popup.style.display = "block";
    closeSearch.style.display = "block";
    searchBox.classList.add("input-expanded");
    e.stopPropagation();
});
</script>
