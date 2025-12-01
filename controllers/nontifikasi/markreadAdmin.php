<?php
require_once "../../koneksi.php";
require_once "../../models/nontifikasi.php";
require_once "../nontifikasi/nontifikasi.php";

session_start();

$id_user = $_SESSION['user_id'];
$role    = "admin"; // ← khusus admin

$controller = new NotificationController($db);
$controller->markAllRead($id_user, $role);

header("Location: ../../admin/admin_dashboard.php");
exit;
