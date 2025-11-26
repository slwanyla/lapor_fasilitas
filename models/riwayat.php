<?php

class RiwayatModel {
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db; // ini sudah PDO
    }

     public function getRiwayatSelesai($user_id)
    {
        $sql = "SELECT * FROM laporan WHERE id_user = ? AND status = 'selesai' ORDER BY tanggal_lapor DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRiwayatByUser($user_id)
    {
        $query = $this->conn->prepare("SELECT * FROM laporan WHERE id_user = ?");
        $query->execute([$user_id]);

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result ?: []; // ← aman, gak akan null
    }

}