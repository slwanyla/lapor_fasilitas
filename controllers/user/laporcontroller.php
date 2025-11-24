<?php

require_once "../../koneksi.php";

class ReportController {

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db; // PDO
    }

    //CREATE REPORT
    public function create()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: ../auth/login.php?error=not_logged_in");
            exit;
        }

        $id_user = $_SESSION['user_id'];
        $judul = $_POST['judul_laporan'];
        $lokasi = $_POST['lokasi'];
        $deskripsi = $_POST['deskripsi'];

        //UPLOAD FOTO

        $fotoName = null;

        if (!empty($_FILES['foto']['name'])) {

            $folder = "../uploads/";
            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }

            $namaFile = $_FILES['foto']['name'];
            $tmpName  = $_FILES['foto']['tmp_name'];

            $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

            // validasi hanya foto
            $allowed = ['jpg', 'jpeg', 'png'];
            if (!in_array($ext, $allowed)) {
                header("Location: ../../user/create_report.php?error=invalid_file");
                exit;
            }

            // generate nama file baru
            $fotoName = time() . "_" . uniqid() . "." . $ext;

            move_uploaded_file($tmpName, $folder . $fotoName);
        }

        //INSERT DATABASE

        $sql = "INSERT INTO laporan (id_user, judul_laporan, deskripsi, lokasi, foto)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        if ($stmt->execute([$id_user, $judul, $deskripsi, $lokasi, $fotoName])) {
            header("Location: ../../user/lapor.php?success=report_created");
        } else {
           header("Location: ../../user/lapor.php?error=insert_failed");

        }

        exit;
    }
}


$report = new ReportController($db);

if (isset($_POST['action']) && $_POST['action'] === "create_report") {
    $report->create();
}

