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
?>

<style>
.mini-alert {
    background: #ff4d4d;          /* merah */
    color: white;
    padding: 10px 14px;
    border-radius: 6px;
    font-size: 14px;
    margin-bottom: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-left: 5px solid #d80000;
    animation: fadeIn 0.3s ease;
    position: relative;
    z-index: 10;
}

.close-mini {
    cursor: pointer;
    font-size: 18px;
    margin-left: 12px;
    opacity: 0.8;
}

.close-mini:hover {
    opacity: 1;
}

/* animasi */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-6px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<?php include '../alert.php'; showAlert(); ?>
<div class="main-content">
    <h2>Data Pengguna</h2>

    <div class="table-container">
        <h3>List Pengguna</h3>

        <!-- Tombol Add User -->
        <button id="addUserBtn" class="btn-yellow">Tambah User</button>

        <div id="addUserPopup" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add User</h2>
                    <span id="closeAddUser" class="close">&times;</span>
                </div>

                 

                <form id="addUserForm" method="POST"  action="../controllers/admin/DataUserController.php">
                    <input type="hidden" name="action" value="add_user">
                    <div class="modal-body grid-form">

                        <?php if (isset($_GET['error']) && $_GET['error']=='email_exists'): ?>
                            <div class="mini-alert" id="miniAlert">
                            Email sudah digunakan
                                <span class="close-mini" onclick="closeMiniAlert()">✕</span>
                            </div>
                        <?php endif; ?>

                        <label>Nama:</label>
                        <input type="text" name="nama" required>

                        <label>Email:</label>
                        <input type="email" name="email" required>

                        <label>Password:</label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="passwordInput" required>
                            <i  id="togglePassword"></i>
                        </div>


                        <label>Role:</label>
                        <select name="role" id="roleSelect" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="mahasiswa">Mahasiswa</option>
                            <option value="dosen">Dosen</option>
                            <option value="staff">Pegawai</option>
                            <option value="admin">Admin</option>
                        </select>

                        <!-- Kolom tambahan, default hidden -->
                        <div class="conditional mahasiswa">
                            <label>NIM:</label>
                            <input type="text" name="nim">
                            <label>Prodi:</label>
                            <input type="text" name="prodi">
                        </div>

                        <div class="conditional dosen">
                            <label>NIDN:</label>
                            <input type="text" name="nidn">
                        </div>

                        <div class="conditional staff">
                            <label>NIP:</label>
                            <input type="text" name="nip">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn-yellow">Tambah</button>
                        <button type="button" id="cancelAddUser" class="btn-red">Batal</button>
                    </div>
                </form>
            </div>
        </div>


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

const addUserBtn = document.getElementById("addUserBtn");
const addUserPopup = document.getElementById("addUserPopup");
const closeAddUser = document.getElementById("closeAddUser");
const cancelAddUser = document.getElementById("cancelAddUser");
const roleSelect = document.getElementById("roleSelect");

// buka modal
addUserBtn.addEventListener("click", () => addUserPopup.style.display = "block");

// tutup modal
closeAddUser.addEventListener("click", () => addUserPopup.style.display = "none");
cancelAddUser.addEventListener("click", () => addUserPopup.style.display = "none");
window.addEventListener("click", (e) => { if(e.target === addUserPopup) addUserPopup.style.display = "none"; });

// tampilkan kolom sesuai role
roleSelect.addEventListener("change", () => {
    document.querySelectorAll(".conditional").forEach(div => div.style.display = "none");
    const role = roleSelect.value;
    if(role === "mahasiswa") {
        document.querySelector(".mahasiswa").style.display = "grid";
    } else if(role === "dosen") {
        document.querySelector(".dosen").style.display = "block";
    } else if(role === "pegawai") {
        document.querySelector(".pegawai").style.display = "block";
    }
});

const togglePassword = document.getElementById("togglePassword");
const passwordInput = document.getElementById("passwordInput");

togglePassword.addEventListener("click", () => {
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        togglePassword.classList.remove("fi-br-eye");
        togglePassword.classList.add("fi-br-eye-crossed");
    } else {
        passwordInput.type = "password";
        togglePassword.classList.remove("fi-br-eye-crossed");
        togglePassword.classList.add("fi-br-eye");
    }
});

function closeMiniAlert() {
        let box = document.getElementById("miniAlert");
        if (box) {
            box.style.display = "none";
        }
    }

</script>
<?php if (isset($_GET['error']) && $_GET['error'] === 'email_exists'): ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("addUserPopup");
    if (modal) {
        modal.style.display = "block";
    }
});
</script>
<?php endif; ?>
