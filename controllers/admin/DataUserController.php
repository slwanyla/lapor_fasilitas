<?php

require_once __DIR__ . "/../../koneksi.php";
require_once __DIR__ . "/../../models/admin.php";

class AdminPenggunaController {

    private $model;

    public function __construct($db) {
        $this->model = new AdminModel($db);
    }

    public function getPengguna($keyword = "") {
        if (!empty($keyword)) {
            return $this->model->searchUsers($keyword);
        }

        return $this->model->getAllUsers();
    }
}
