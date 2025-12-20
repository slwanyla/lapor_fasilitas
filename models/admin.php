<?php

class AdminModel {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Ambil semua data user
    public function getAllUsers() {
        // ambil semua user KECUALI role admin, mengurutkan terbaru dulu
        $sql = "SELECT * FROM user ORDER BY id DESC";
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

    public function addUser(
        $nama,
        $email,
        $password,
        $role,
        $nim = null,
        $nidn = null,
        $nip = null,
        $prodi = null
    ) {
        $sql = "INSERT INTO user 
                (nama, email, password, role, nim, nidn, nip, prodi)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $nama,
            $email,
            $password,
            $role,
            $nim,
            $nidn,
            $nip,
            $prodi
        ]);
    }

    public function emailExists($email)
    {
        $sql = "SELECT id FROM user WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':email' => $email
        ]);

        return $stmt->rowCount() > 0;
    }


}
