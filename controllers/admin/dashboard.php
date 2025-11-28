<?php

require_once __DIR__ . "/../../koneksi.php";
require_once __DIR__ . "/../../models/dashboard.php";

class AdminDashboardController {

    private $model;

    public function __construct($db)
    {
        $this->model = new DashboardModel($db);
    }

    public function getSummary($user_id)
    {
        return $this->model->getSummary($user_id);
    }
    
    public function getFilterAdmin($start='', $end='', $status='', $filter='') {
        return $this->model->getFilterAdmin($start, $end, $status, $filter);
    }


    public function getAllLaporan() {
        return $this->model->getAllLaporan();
    }

    public function getTotalLaporanAdmin() {
        return $this->model->getTotalLaporanAdmin();
    }

    public function getDashboardStats() {
    return [
        'baru'       => $this->model->getCountByStatus('baru'),
        'diproses'   => $this->model->getCountByStatus('diproses'),
        'selesai'    => $this->model->getCountByStatus('selesai'),
        'tidak_valid'=> $this->model->getCountByStatus('tidak_valid'),
        'total'      => $this->model->getTotalLaporanAdmin()
    ];
}



}

?>
