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

// Ambil parameter filter
$filterStatus = $_GET['filter'] ?? '';
$searchStatus = $_GET['status'] ?? '';
$startDate = $_GET['start'] ?? '';
$endDate = $_GET['end'] ?? '';

// Ambil data
$data = $controller->getFilterAdmin($startDate, $endDate, $searchStatus, $filterStatus);
$total = $controller->getTotalLaporanAdmin();

$stats = $controller->getDashboardStats();

// Ambil parameter dari URL
$filterStatus = $_GET['filter'] ?? ''; // dari dropdown filter (New, Dalam Proses, dll)
$searchStatus = $_GET['status'] ?? ''; // dari search popup
$startDate = $_GET['start'] ?? '';
$endDate = $_GET['end'] ?? '';

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



    <div class="table-container">
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
                <a href="admin_dashboard.php?filter=baru" class="<?= ($filterStatus=='baru')?'active':'' ?>">New</a>
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
                            <a href="#" class="btn-delete" data-rowid="row-<?= $row['id_laporan']; ?>">
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

    // DELETE HANYA TAMPILAN (tidak hapus DB)
    document.querySelectorAll(".btn-delete").forEach(btn => {
        btn.addEventListener("click", function(e) {
            e.preventDefault();
            let rowId = this.dataset.rowid;
            let row = document.getElementById(rowId);
            if (row) {
                row.style.display = "none"; // hilang dari tampilan
            }
        });
    });

</script>


