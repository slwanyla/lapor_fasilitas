<?php 
require_once __DIR__ . "/../../koneksi.php";
require_once __DIR__ . "/../../models/dashboard.php";


class DashboardController {
    private $model;

    public function __construct($db)
    {
        $this->model = new DashboardModel($db);
    }

    public function getSummary($user_id)
    {
        return $this->model->getSummary($user_id);
    }

    public function getRecentActivity($user_id, $start='', $end='', $status='', $filter='') 
    {
        return $this->model->getRecentActivity($user_id, $start, $end, $status, $filter);
    }

    public function getTotalLaporan($user_id)
    {
        return $this->model->getTotalLaporan($user_id);
    }

    

}
?>
