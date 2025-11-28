<?php
require_once "../../koneksi.php";
require_once "../../models/report.php";

class AdminReportController {

    private $model;

    public function __construct($db)
    {
        $this->model = new ReportModel($db);
    }

    public function updateStatus()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../../admin/admin_dashboard.php?error=forbidden");
            exit;
        }

        $id = $_POST['id_laporan'];
        $status = $_POST['status'];

        $updated = $this->model->updateStatus($id, $status);

        if ($updated) {
            header("Location: ../../admin/admin_dashboard.php?success=updated");
            exit;
        } else {
            header("Location: ../../admin/admin_dashboard.php?error=update_failed");
            exit;
        }


    }

}

// === ROUTER ===
$adminReport = new AdminReportController($db);

if (isset($_POST['action']) && $_POST['action'] == "update_status") {
    $adminReport->updateStatus();
}
