<?php

class AdminModel {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Ambil semua data user
    public function getAllUsers() {
        // ambil semua user KECUALI role admin, urutkan terbaru dulu
        $sql = "SELECT * FROM user WHERE role != 'admin' ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchUsers($keyword) {
        $keyword = "%$keyword%";

        $sql = "SELECT *
                FROM user
                WHERE role != 'admin'
                AND (
                    nama LIKE :kw OR
                    email LIKE :kw OR
                    role LIKE :kw OR
                    nim LIKE :kw OR
                    nidn LIKE :kw OR
                    nip LIKE :kw OR
                    prodi LIKE :kw
                )
                ORDER BY id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':kw', $keyword);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
