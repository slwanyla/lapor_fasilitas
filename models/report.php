<?php

class ReportModel {

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;  // PDO connection
    }

    // Insert Report
    public function createReport($id_user, $judul, $deskripsi, $lokasi, $fotoName)
    {
        $sql = "INSERT INTO laporan (id_user, judul_laporan, deskripsi, lokasi, foto)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id_user, $judul, $deskripsi, $lokasi, $fotoName]);
    }
}
