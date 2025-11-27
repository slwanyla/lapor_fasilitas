<?php

require_once __DIR__ . "/../../koneksi.php";
require_once __DIR__ . "/../../models/dashboard.php";

class AdminDashboardController {

    private $model;

    public function __construct($db)
    {
        $this->model = new DashboardModel($db);
    }

    public function index($user_id)
    {
        // Ambil summary: new, in progress, completed
        $summary = $this->model->getSummary($user_id);

        // Ambil data laporan terbaru untuk tabel
        $data = $this->model->getRecentActivity($user_id);

        // Ambil total laporan
        $total = $this->model->getTotalLaporan($user_id);

        return [
            'summary' => $summary,
            'data' => $data,
            'total' => $total
        ];
    }

    public function getAllLaporan() {
        return $this->model->getAllLaporan();
    }

    public function getTotalLaporan() {
        return $this->model->getTotalLaporanAdmin();
    }

}

?>
