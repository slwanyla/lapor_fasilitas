<?php

class User
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /* ============================
           FUNGSI REGISTER
    ============================ */
    public function checkEmail($email)
    {
        $q = $this->db->prepare("SELECT * FROM user WHERE email = ?");
        $q->execute([$email]);
        return $q->fetch();
    }

    public function insertUser($data)
    {
        $sql = "INSERT INTO user (nama, email, password, role, nim, nidn, nip, prodi)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nama'],
            $data['email'],
            $data['password'],
            $data['role'],
            $data['nim'],
            $data['nidn'],
            $data['nip'],
            $data['prodi']
        ]);
    }

    /* ============================
               LOGIN
    ============================ */
    public function getByEmail($email)
    {
        $q = $this->db->prepare("SELECT * FROM user WHERE email = ? LIMIT 1");
        $q->execute([$email]);
        return $q->fetch(PDO::FETCH_ASSOC);
    }

    /* ============================
           PASSWORD RESET
    ============================ */
    public function saveResetToken($userId, $token, $expired)
    {
        $sql = "INSERT INTO password_reset (id_user, token, expired_at)
                VALUES (?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId, $token, $expired]);
    }

    public function getTokenData($token)
    {
        $q = $this->db->prepare("SELECT * FROM password_reset WHERE token = ? LIMIT 1");
        $q->execute([$token]);
        return $q->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePassword($user_id, $hashed)
    {
        $q = $this->db->prepare("UPDATE user SET password = ? WHERE id = ?");
        return $q->execute([$hashed, $user_id]);
    }

    public function deleteToken($token)
    {
        $q = $this->db->prepare("DELETE FROM password_reset WHERE token = ?");
        return $q->execute([$token]);
    }
}
