<?php
require_once __DIR__ . "/../../koneksi.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php?error=not_logged_in");
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: ../../user/user_dashboard.php?error=no_id");
    exit;
}

// Ambil data laporan
$query = $db->prepare("SELECT foto, status FROM laporan WHERE id = ?");
$query->execute([$id]);
$data = $query->fetch();

if (!$data) {
    header("Location: ../../user/user_dashboard.php?error=not_found");
    exit;
}

// Cuma boleh hapus kalau status masih 'baru'
if ($data['status'] !== 'baru') {
    header("Location: ../../user/user_dashboard.php?error=not_allowed");
    exit;
}

// Hapus file foto jika ada
if (!empty($data['foto']) && file_exists("../../uploads/" . $data['foto'])) {
    unlink("../../uploads/" . $data['foto']);
}

// Hapus dari database
$delete = $db->prepare("DELETE FROM laporan WHERE id = ?");
$delete->execute([$id]);

header("Location: ../../user/user_dashboard.php?success=deleted");
exit;
