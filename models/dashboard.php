<?php

class DashboardModel {
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db; // ini sudah PDO
    }

    public function getSummary($user_id)
    {
        $sql = "SELECT
                    SUM(status = 'baru') AS baru,
                    SUM(status = 'proses') AS proses,
                    SUM(status = 'selesai') AS selesai
                FROM laporan
                WHERE id_user = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id]); // PDO pakai array, bukan bind_param

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRecentActivity($user_id, $start='', $end='', $status='', $filter='') {
        $sql = "SELECT 
                    id AS id_laporan,
                    judul_laporan,
                    lokasi,
                    deskripsi,
                    foto,
                    status,
                    tanggal_lapor,
                    tanggal_update
                FROM laporan
                WHERE id_user = :user_id";
        
        $params = ['user_id' => $user_id];

        // Jika ada tanggal mulai
        if($start){
            $sql .= " AND tanggal_lapor >= :start";
            $params['start'] = $start . ' 00:00:00'; // mulai awal hari
        }

        // Jika ada tanggal akhir
        if($end){
            $sql .= " AND tanggal_lapor <= :end";
            $params['end'] = $end . ' 23:59:59'; // sampai akhir hari
        }

        // Tentukan status dari filter atau search
        $finalStatus = $filter ?: $status;

        // Hanya pakai status jika tidak kosong
        if($finalStatus){
            // Pastikan status sama persis dengan enum di DB
            $allowedStatus = ['baru', 'diproses', 'selesai', 'tidak_valid'];
            if(in_array($finalStatus, $allowedStatus)){
                $sql .= " AND status = :status";
                $params['status'] = $finalStatus;
            }
        }

        $sql .= " ORDER BY tanggal_update DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>
