<?php

require_once __DIR__ . "/../../koneksi.php";
require_once __DIR__ . "/../../models/admin.php";

class AdminPenggunaController {

    private $model;

    public function __construct($db) {
        $this->model = new AdminModel($db);
    }

    public function getPengguna() {
        return $this->model->getAllUsers();
    }
}
