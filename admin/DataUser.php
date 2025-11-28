<?php
session_start();

require_once "../koneksi.php";
require_once "../controllers/admin/DataUserController.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$controller = new AdminPenggunaController($db);
$dataUser = $controller->getPengguna();

include "sidebar.php";
include "header.php";
include '../alert.php'; showAlert(); 
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
