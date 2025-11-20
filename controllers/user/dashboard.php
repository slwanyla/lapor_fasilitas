<?php

class DashboardController {

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db; // PDO
    }

    /* =============================
            GET RINGKASAN LAPORAN
    ============================== */
    public function getSummary($user_id)
    {
        $summary = [
            "total"   => 0,
            "baru"    => 0,
            "proses"  => 0,
            "selesai" => 0
        ];

        $sql = "SELECT 
                    COUNT(*) AS total,
                    SUM(CASE WHEN status = 'baru' THEN 1 ELSE 0 END) AS baru,
                    SUM(CASE WHEN status = 'proses' THEN 1 ELSE 0 END) AS proses,
                    SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) AS selesai
                FROM laporan
                WHERE id_user = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $summary = $data;
        }

        return $summary;
    }

    /* =============================
            GET 10 AKTIVITAS TERBARU
    ============================== */
    public function getRecentActivity($user_id)
    {
        $sql = "SELECT id_laporan, judul, lokasi, status, updated_at 
                FROM laporan
                WHERE id_user = ?
                ORDER BY GREATEST(
                    COALESCE(updated_at, created_at),
                    created_at
                ) DESC
                LIMIT 10";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
