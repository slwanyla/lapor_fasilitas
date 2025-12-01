<?php
require_once "../../koneksi.php";
require_once "../../models/nontifikasi.php";
require_once "../nontifikasi/nontifikasi.php";

session_start();

$id_user = $_SESSION['user_id'];
$role    = "user"; // ← khusus user

$controller = new NotificationController($db);
$controller->markAllRead($id_user, $role);

header("Location: ../../user/user_dashboard.php");
exit;
