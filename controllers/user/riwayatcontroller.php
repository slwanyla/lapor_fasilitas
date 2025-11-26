<?php 
require_once __DIR__ . "/../../koneksi.php";
require_once __DIR__ . "/../../models/riwayat.php";


class RiwayatController {
    private $model;

    public function __construct($db)
    {
        $this->model = new RiwayatModel($db);
    }

    public function getRiwayatSelesai($user_id)
    {
        return $this->model->getRiwayatSelesai($user_id);
    }

    public function getRiwayatLaporan($user_id)
    {
        return $this->model->getRiwayatByUser($user_id);
    }


}