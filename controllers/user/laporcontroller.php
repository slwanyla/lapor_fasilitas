<?php

require_once "../../koneksi.php";
require_once "../../models/report.php";
require_once "../nontifikasi/nontifikasi.php";

class ReportController {

    private $model;
    private $notif;

    public function __construct($db)
    {
        $this->model  = new ReportModel($db);
        $this->notif  = new NotificationController($db); 
    }

    public function create()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: ../auth/login.php?error=not_logged_in");
            exit;
        }

        $id_user   = $_SESSION['user_id'];
        $judul     = $_POST['judul_laporan'];
        $lokasi    = $_POST['lokasi'];
        $deskripsi = $_POST['deskripsi'];

        // ===== Upload File ===== //
        $fotoName = null;

        if (!empty($_FILES['foto']['name'])) {

            $folder = "../../uploads/";
            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }

            $namaFile = $_FILES['foto']['name'];
            $tmpName  = $_FILES['foto']['tmp_name'];

            $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png'];

            if (!in_array($ext, $allowed)) {
                header("Location: ../../user/lapor.php?error=invalid_file");
                exit;
            }

            $fotoName = time() . "_" . uniqid() . "." . $ext;
            move_uploaded_file($tmpName, $folder . $fotoName);
        }

        // ===== Insert ke DB ===== //
        $insert = $this->model->createReport($id_user, $judul, $deskripsi, $lokasi, $fotoName);

        if ($insert) {

            $nama_user = $_SESSION['user_name'];

            $this->notif->send(
                null,
                "admin",
                "Laporan baru dibuat oleh $nama_user"
            );


            header("Location: ../../user/lapor.php?success=report_created");
        } else {
            header("Location: ../../user/lapor.php?error=insert_failed");
        }

        exit;
    }

    public function update()
    {
        session_start(); 
        
        $id     = $_POST['id'];
        $judul  = $_POST['judul_laporan'];
        $lokasi = $_POST['lokasi'];
        $desk   = $_POST['deskripsi'];

        $fotoBaru = null;

        if (!empty($_FILES['foto']['name'])) {

            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png'];

            if (!in_array($ext, $allowed)) {
                header("Location: ../../user/user_dashboard.php?error=file_type");
                exit;
            }

            $fileName = time() . "_" . uniqid() . "." . $ext;
            $temp = $_FILES['foto']['tmp_name'];

            move_uploaded_file($temp, "../../uploads/" . $fileName);

            $fotoBaru = $fileName;
        }

        $this->model->updateLaporan($id, $judul, $lokasi, $desk, $fotoBaru);

        // 🔔 Notifikasi untuk admin
        $nama_user = $_SESSION['user_name'];

        $this->notif->send(
            null,
            "admin",
            "User $nama_user melakukan update pada laporan ID $id"
        );


        header("Location: ../../user/user_dashboard.php?success=update_report");
        exit;
    }

}


// === ROUTER === //
$report = new ReportController($db);

if (isset($_POST['action'])) {

    if ($_POST['action'] == "create_report") {
        $report->create();
    }

    if ($_POST['action'] == "update_report") {
        $report->update();
    }
}
