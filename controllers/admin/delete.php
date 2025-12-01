<?php
require_once __DIR__ . "/../../koneksi.php";
require_once __DIR__ . "/../../models/report.php";

if (!isset($_GET['id'])) {
    header("Location: ../../admin/admin_dashboard.php?error=no_id");
    exit;
}

$id = $_GET['id'];

$model = new ReportModel($db);
$model->softDelete($id);

header("Location: ../../admin/admin_dashboard.php?success=deleted");
exit;
