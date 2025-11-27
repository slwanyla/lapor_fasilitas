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

   public function updateLaporan($id, $judul, $lokasi, $desk, $foto = null)
    {
        if ($foto) {
            $sql = "UPDATE laporan 
                    SET judul_laporan = ?, lokasi = ?, deskripsi = ?, foto = ?, tanggal_update = NOW()
                    WHERE id = ?";
            $params = [$judul, $lokasi, $desk, $foto, $id];
        } else {
            $sql = "UPDATE laporan 
                    SET judul_laporan = ?, lokasi = ?, deskripsi = ?, tanggal_update = NOW()
                    WHERE id = ?";
            $params = [$judul, $lokasi, $desk, $id];
        }

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

}
