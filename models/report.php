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

    // Update status laporan
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE laporan SET status = ?, tanggal_update = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$status, $id]);
    }

    public function getUserIdByReport($id)
    {
        $sql = "SELECT id_user FROM laporan WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchColumn();
    }

    public function softDelete($id)
    {
        $query = "UPDATE laporan SET is_deleted = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function getFilteredReports($search, $status, $from, $to)
    {
        $query = "SELECT * FROM laporan WHERE is_deleted = 0";
        $params = [];

        if (!empty($search)) {
            $query .= " AND (judul_laporan LIKE ? OR lokasi LIKE ? OR deskripsi LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if (!empty($status)) {
            $query .= " AND status = ?";
            $params[] = $status;
        }

        if (!empty($from)) {
            $query .= " AND DATE(tanggal_lapor) >= ?";
            $params[] = $from;
        }

        if (!empty($to)) {
            $query .= " AND DATE(tanggal_lapor) <= ?";
            $params[] = $to;
        }

        $query .= " ORDER BY tanggal_lapor DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
