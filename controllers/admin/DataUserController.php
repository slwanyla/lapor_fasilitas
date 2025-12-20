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

    public function addUser()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: ../../auth/login.php");
            exit;
        }

        $nama     = $_POST['nama'];
        $email    = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role     = $_POST['role'];

        $nim   = $_POST['nim']   ?? null;
        $nidn  = $_POST['nidn']  ?? null;
        $nip   = $_POST['nip']   ?? null;
        $prodi = $_POST['prodi'] ?? null;

        if ($this->model->emailExists($email)) {
        header("Location:  ../../admin/DataUser.php?error=email_exists");
        exit;
        }


        $insert = $this->model->addUser(
            $nama,
            $email,
            $password,
            $role,
            $nim,
            $nidn,
            $nip,
            $prodi
        );

        if ($insert) {
            header("Location:  ../../admin/DataUser.php?success=added");
            exit;
        } else {
            header("Location:  ../../admin/DataUser.php?error=add_failed");
            exit;
        }
    }


    
}

$addUser = new AdminPenggunaController($db);

if (isset($_POST['action']) && $_POST['action'] == "add_user") {
    $addUser->addUser();
}