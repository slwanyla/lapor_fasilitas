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
                    SUM(status = 'diproses') AS proses,
                    SUM(status = 'selesai') AS selesai
                FROM laporan
                WHERE id_user = ?
                AND is_deleted = 0";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id]); // PDO pakai array, bukan bind_param

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRecentActivity($user_id, $start='', $end='', $status='', $filter='', $query='') {
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
                WHERE id_user = :user_id AND is_deleted = 0";

        $params = ['user_id' => $user_id];

        if($start){
            $sql .= " AND tanggal_lapor >= :start";
            $params['start'] = $start . ' 00:00:00';
        }

        if($end){
            $sql .= " AND tanggal_lapor <= :end";
            $params['end'] = $end . ' 23:59:59';
        }

        $finalStatus = $filter ?: $status;

        if($finalStatus){
            $allowedStatus = ['baru', 'diproses', 'selesai', 'tidak_valid'];
            if(in_array($finalStatus, $allowedStatus)){
                $sql .= " AND status = :status";
                $params['status'] = $finalStatus;
            }
        }

        if (!empty($query)) {
            $sql .= " AND (judul_laporan LIKE :q OR lokasi LIKE :q OR deskripsi LIKE :q)";
            $params['q'] = "%$query%";
        }

        // ⬇⬇ TAMBAHKAN INI (inti logikanya)
        $sql .= " AND (
                    status != 'selesai'
                    OR (
                        status = 'selesai'
                        AND DATE(tanggal_update) >= DATE_SUB(CURDATE(), INTERVAL 1 DAY)
                    )
                )";

        $sql .= " ORDER BY COALESCE(tanggal_update, tanggal_lapor) DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getTotalLaporan()
    {
        $sql = "SELECT COUNT(*) AS total FROM laporan";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }


    public function getAllLaporan() {
        $sql = "
            SELECT 
                l.id AS id_laporan,
                l.judul_laporan,
                l.deskripsi,
                l.lokasi,
                l.foto,
                l.status,
                l.tanggal_lapor,
                l.tanggal_update,

                u.id AS user_id,
                u.nama AS user_nama,
                u.nim,
                u.nidn,
                u.nip,
                u.role,
                u.prodi

            FROM laporan l
            LEFT JOIN user u ON l.id_user = u.id
            WHERE l.is_deleted = 0
            ORDER BY l.tanggal_update DESC, l.tanggal_lapor DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalLaporanAdmin() 
    {
        $sql = "SELECT COUNT(*) AS total FROM laporan WHERE is_deleted = 0"; 
        $stmt = $this->conn->prepare($sql); 
        $stmt->execute(); return 
        $stmt->fetch(PDO::FETCH_ASSOC)['total']; 
    }

    public function getFilterAdmin($start='', $end='', $status='', $filter='', $keyword='')
    {
        $sql = "SELECT 
                    l.id AS id_laporan,
                    l.judul_laporan,
                    l.lokasi,
                    l.deskripsi,
                    l.foto,
                    l.status,
                    l.tanggal_lapor,
                    l.tanggal_update,

                    u.nama AS user_nama,
                    u.role,
                    u.nim,
                    u.nidn,
                    u.nip,
                    u.prodi
                FROM laporan l
                LEFT JOIN user u ON l.id_user = u.id
                WHERE l.is_deleted = 0";

        $params = [];

        // ========= FILTER TANGGAL =========
        if ($start) {
            $sql .= " AND l.tanggal_lapor >= :start";
            $params['start'] = $start . " 00:00:00";
        }

        if ($end) {
            $sql .= " AND l.tanggal_lapor <= :end";
            $params['end'] = $end . " 23:59:59";
        }

        // ========= SEARCH STATUS / FILTER =========
        $finalStatus = $filter ?: $status;
        if ($finalStatus) {
            $allowed = ['baru','diproses','selesai','tidak_valid'];
            if (in_array($finalStatus, $allowed)) {
                $sql .= " AND l.status = :status";
                $params['status'] = $finalStatus;
            }
        }

        // ========= 🔥 SEARCH MANUAL KEYWORD =========  
        if ($keyword) {
            $sql .= " AND (
                    l.judul_laporan LIKE :kw OR
                    l.lokasi LIKE :kw OR
                    l.deskripsi LIKE :kw OR
                    l.status LIKE :kw OR

                    u.nama LIKE :kw OR
                    u.role LIKE :kw OR
                    u.nim LIKE :kw OR
                    u.nidn LIKE :kw OR
                    u.nip LIKE :kw OR
                    u.prodi LIKE :kw
            )";

            $params["kw"] = "%$keyword%";
        }

        $sql .= " ORDER BY COALESCE(l.tanggal_update, l.tanggal_lapor) DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


     public function getCountByStatus($status) {
        $sql = "SELECT COUNT(*) AS total FROM laporan WHERE status = :status AND is_deleted = 0";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":status", $status);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // total laporan (semua status)
    public function getTotalReports() {
        $sql = "SELECT COUNT(*) AS total FROM laporan WHERE is_deleted = 0";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

}

?>
