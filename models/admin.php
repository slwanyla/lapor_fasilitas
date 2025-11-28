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

   


}
