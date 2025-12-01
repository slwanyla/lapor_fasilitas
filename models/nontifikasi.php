<?php

class NotificationModel {

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Tambah notifikasi
    public function createNotif($id_user, $penerima, $isi)
    {
        $sql = "INSERT INTO notifikasi (id_user, penerima, isi_notifikasi)
                VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id_user, $penerima, $isi]);
    }

    // Ambil notifikasi (belum dibaca)
   public function getUnread($id_user, $role)
    {
        if ($role === 'admin') {
            // ADMIN
            $sql = "SELECT * FROM notifikasi 
                    WHERE penerima = ? AND status_baca = 0
                    ORDER BY id_notifikasi DESC";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$role]);
        } else {
            // USER BIASA
            $sql = "SELECT * FROM notifikasi 
                    WHERE id_user = ? AND penerima = ? AND status_baca = 0
                    ORDER BY id_notifikasi DESC";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_user, $role]);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Ambil semua notifikasi
    public function getAll($id_user, $role)
    {
        if ($role === 'admin') {
            // ADMIN TIDAK PAKAI id_user
            $sql = "SELECT * FROM notifikasi 
                    WHERE penerima = ?
                    ORDER BY id_notifikasi DESC";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$role]);
        } else {
            // USER PAKAI id_user
            $sql = "SELECT * FROM notifikasi 
                    WHERE id_user = ? AND penerima = ?
                    ORDER BY id_notifikasi DESC";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_user, $role]);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Mark all as read
    public function markAllRead($id_user, $role)
    {
        if ($role === "admin") {
            // Admin tidak pakai id_user
            $sql = "UPDATE notifikasi 
                    SET status_baca = 1
                    WHERE penerima = 'admin'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

        } else {
            // User biasa pakai id_user
            $sql = "UPDATE notifikasi 
                    SET status_baca = 1
                    WHERE id_user = ? AND penerima = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_user, $role]);
        }
    }


}
