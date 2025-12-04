<?php 

session_start();

require_once "../koneksi.php";
require_once "../controllers/admin/dashboard.php";
require_once "../controllers/nontifikasi/nontifikasi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$controller = new AdminDashboardController($db);

// Ambil parameter filter
$keyword = $_GET['keyword'] ?? '';
$filterStatus = $_GET['filter'] ?? '';
$searchStatus = $_GET['status'] ?? '';
$startDate = $_GET['start'] ?? '';
$endDate = $_GET['end'] ?? '';

// Ambil data
$data = $controller->getFilterAdmin($startDate, $endDate, $searchStatus, $filterStatus, $keyword);
$total = $controller->getTotalLaporanAdmin();

$stats = $controller->getDashboardStats();

$notifController = new NotificationController($db);


$unread = $notifController->getUnreadCount($user_id, 'admin');
$listNotif = $notifController->getNotifications($user_id, 'admin');


include "sidebar.php";
include "header.php";
include "edit.php";
include '../alert.php'; showAlert(); 
?>

<div class="main-content">
    <h2>Dashboard</h2>

        <div class="stats-container">

            <div class="stat-box">
                <div class="stat-icon">
                    <i class="fi fi-rr-upload"></i>
                </div>
                <div class="stat-info">
                    <p class="title">New reports</p>
                    <p class="number"><?= $stats['baru'] ?></p>
                </div>
            </div>

            <div class="stat-box">
                <div class="stat-icon">
                    <i class="fi fi-br-rotate-right"></i>
                </div>
                <div class="stat-info">
                    <p class="title">In Progress</p>
                    <p class="number"><?= $stats['diproses'] ?></p>
                </div>
            </div>

            <div class="stat-box">
                <div class="stat-icon">
                    <i class="fi fi-ss-check-circle"></i>
                </div>
                <div class="stat-info">
                    <p class="title">Completed</p>
                    <p class="number"><?= $stats['selesai'] ?></p>
                </div>
            </div>

            <div class="stat-box">
                <div class="stat-icon">
                    <i class="fi fi-rr-folder"></i>
                </div>
                <div class="stat-info">
                    <p class="title">Total Reports</p>
                    <p class="number"><?= $stats['total'] ?></p>
                </div>
            </div>

        </div>



    <div class="table-container"  id="resultContainer">
        <h3>Data Laporan</h3>

         <div class="filter-wrapper">
            <button class="filter-btn">
                <i class="fi fi-sr-filter"></i>
                Filter
                <i class="fi fi-br-angle-small-down dropdown-icon"></i>
            </button>

           <div class="filter-menu">
                <a href="admin_dashboard.php" class="<?= ($filterStatus=='all')?'active':'' ?>">All</a>
                <hr>
                <a href="admin_dashboard.php?filter=baru" class="<?= ($filterStatus=='baru')?'active':'' ?>">Baru</a>
                <a href="admin_dashboard.php?filter=diproses" class="<?= ($filterStatus=='diproses')?'active':'' ?>">Dalam Proses</a>
                <a href="admin_dashboard.php?filter=selesai" class="<?= ($filterStatus=='selesai')?'active':'' ?>">Selesai</a>
                <a href="admin_dashboard.php?filter=tidak_valid" class="<?= ($filterStatus=='tidak_valid')?'active':'' ?>">Tidak Valid</a>
            </div>
        </div>


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
                        <tr id="row-<?= $row['id_laporan']; ?>">
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
                                <img src="../uploads/<?= $row['foto']; ?>" 
                                    class="img-thumbnail"
                                    onclick="openImage('../uploads/<?= $row['foto']; ?>')">
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
                            <a href="#" 
                                class="btn-edit"
                                data-id="<?= $row['id_laporan']; ?>"
                                data-status="<?= $row['status']; ?>"
                                data-judul="<?= htmlspecialchars($row['judul_laporan']); ?>"
                                data-lokasi="<?= htmlspecialchars($row['lokasi']); ?>"
                                data-deskripsi="<?= htmlspecialchars($row['deskripsi']); ?>"
                                data-foto="<?= $row['foto']; ?>"
                                >
                                    <i class="fi fi-rr-edit"></i>
                            </a>
                            <a href="#" class="btn-delete" data-id="<?= $row['id_laporan']; ?>">
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

<div class="download-container">
    <a href="../controllers/admin/exportFile.php?search=<?= $_GET['search'] ?? '' ?>&status=<?= $_GET['status'] ?? '' ?>&from=<?= $_GET['from'] ?? '' ?>&to=<?= $_GET['to'] ?? '' ?>" 
    class="btn-download">
        <i class="fi fi-br-download"></i> 
    </a>
</div>


<!-- Overlay -->
<div id="deleteOverlay" class="delete-overlay" style="display:none;">
    <div class="delete-popup">
        <div class="popup-icon">
            <i class="fi fi-rr-info"></i>
        </div>
        <p>Apakah anda yakin untuk dihapus?</p>

        <div class="popup-actions">
            <button id="confirmDelete" class="btn-yes">YA</button>
            <button id="cancelDelete" class="btn-no">TIDAK</button>
        </div>
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
    // ambil modal
    const modal = document.getElementById("editModal");
    const closeBtn = document.querySelector(".close-edit");

    // tombol edit
    document.querySelectorAll(".btn-edit").forEach(btn => {
        btn.addEventListener("click", e => {
            e.preventDefault();

            // ambil data dari tombol
            document.getElementById("editId").value = btn.dataset.id;
            document.getElementById("editStatus").value = btn.dataset.status;

            document.getElementById("editJudul").value = btn.dataset.judul;
            document.getElementById("editLokasi").value = btn.dataset.lokasi;
            document.getElementById("editDeskripsi").value = btn.dataset.deskripsi;

            document.getElementById("previewImg").src = "../uploads/" + btn.dataset.foto;
            document.getElementById("fileInfo").innerText = btn.dataset.foto || "Tidak ada file";

            modal.style.display = "block";
            modal.style.opacity = "1";
            modal.style.transform = "translate(-50%, -50%) scale(1)";
        });
    });

    // close modal
    function closeEdit() {
        modal.style.display = "none";
    }

    // klik luar modal
    window.addEventListener("click", e => {
        if (e.target == modal) closeEdit();
    });

    function openImage(src) {
        document.getElementById("imageModal").style.display = "flex";
        document.getElementById("modalImg").src = src;
        }

    function closeImage() {
            document.getElementById("imageModal").style.display = "none";
        }

        // Klik area luar -> close
        document.getElementById("imageModal").addEventListener("click", function(e) {
            if (e.target === this) {
                closeImage();
            }
    });

    function manualSearch() {
    let q = document.getElementById("manualSearch").value.trim();
    let start = document.getElementById("dateStart")?.value;
    let end = document.getElementById("dateEnd")?.value;
    let status = document.getElementById("statusSelect")?.value;

    // jika input kosong → kembali ke asli
    if (!q && !start && !end && !status) {
        window.location = "admin_dashboard.php";
        return;
    }

    let url = "admin_dashboard.php?";

    if (q) url += "keyword=" + encodeURIComponent(q) + "&";
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
    window.location = "admin_dashboard.php";  // reset semua filter
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
        urlParams.get("keyword") ||
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
    if (urlParams.get("keyword")) {
        document.getElementById("manualSearch").value = urlParams.get("keyword");
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

let deleteID = null;

document.querySelectorAll(".btn-delete").forEach(btn => {
    btn.addEventListener("click", function(e) {
        e.preventDefault();

        deleteID = this.dataset.id;  // ID yg benar

        document.getElementById("deleteOverlay").style.display = "flex";
    });
});

// Klik "TIDAK"
document.getElementById("cancelDelete").addEventListener("click", function() {
    deleteID = null;
    document.getElementById("deleteOverlay").style.display = "none";
});

// Klik "YA"
document.getElementById("confirmDelete").addEventListener("click", function() {
    if (deleteID) {
        window.location = "../controllers/admin/delete.php?id=" + deleteID;
    }
});

function openLogoutModal() {
    document.getElementById("logoutModal").style.display = "flex";
}

function closeLogoutModal() {
    document.getElementById("logoutModal").style.display = "none";
}

window.onclick = function(event) {
    const modal = document.getElementById("logoutModal");
    if (event.target === modal) {
        modal.style.display = "none";
    }
}
</script>


