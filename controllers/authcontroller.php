<?php

require_once "../koneksi.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/phpmailer/src/Exception.php';
require '../phpmailer/phpmailer/src/PHPMailer.php';
require '../phpmailer/phpmailer/src/SMTP.php';


class AuthController {

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db; // PDO
    }

    /* =====================
          REGISTER
    ===================== */
    public function register()
    {
        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $identifier = $_POST['identifier'];
        $jurusan = $_POST['jurusan'] ?? null;

        // VALIDASI MINIMAL PASSWORD 6 KARAKTER
        if (strlen($_POST['password']) < 6) {
            header("Location: ../auth/register.php?error=pw_too_short");
            exit;
        }

        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // CEK EMAIL
        $cek = $this->conn->prepare("SELECT * FROM user WHERE email = ?");
        $cek->execute([$email]);

        if ($cek->rowCount() > 0) {
            header("Location: ../auth/register.php?error=email_exists");
            exit;
        }

        // identifier berdasarkan role
        $nim  = $role == "mahasiswa" ? $identifier : null;
        $nidn = $role == "dosen"     ? $identifier : null;
        $nip  = $role == "staff"     ? $identifier : null;

        // INSERT
        $sql = "INSERT INTO user (nama, email, password, role, nim, nidn, nip, prodi)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$nama, $email, $password, $role, $nim, $nidn, $nip, $jurusan]);

        header("Location: ../auth/login.php?success=registered");
        exit;
    }

    /* =====================
            LOGIN
    ===================== */
    public function login()
    {
        session_start();

        $email = $_POST['email'];
        $password = $_POST['password'];

        $query = $this->conn->prepare("SELECT * FROM user WHERE email = ? LIMIT 1");
        $query->execute([$email]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            header("Location: ../auth/login.php?error=email_not_found");
            exit;
        }

        if (!password_verify($password, $user['password'])) {
            header("Location: ../auth/login.php?error=wrong_password");
            exit;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == "admin") {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../user/dashboard.php");
        }
        exit;
    }

    public function forgotPassword()
    {
        $email = $_POST['email'];

        // Cek email
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

       
        if (!$user) {
            header("Location: ../auth/forgot_password.php?error=email_not_found");
            exit;
        }

        // Generate token
        $token = bin2hex(random_bytes(32));
        $expired = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Insert / Update token
        $sql = "INSERT INTO password_reset (id_user, token, expired_at)
                VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user['id'], $token, $expired]);

        // Kirim email pakai PHPMailer
        $resetLink = "http://localhost/lapor_fasilitas/auth/reset_password.php?token=".$token;

        $mail = new PHPMailer(true);

        try {
            // SMTP Gmail
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "laporaja77@gmail.com";
            $mail->Password = "mnyv cnpn pxdj trqx";
            $mail->SMTPSecure = "ssl";
            $mail->Port = 465;

            // Penerima
            $mail->setFrom("laporaja77@gmail.com", "Reset Password System");
            $mail->addAddress($email);

            // Isi email
            $mail->isHTML(true);
            $mail->Subject = "Reset Password Akun Anda";
            $mail->Body = "
                Klik link berikut untuk reset password Anda:<br><br>
                <a href='$resetLink'>Reset Password</a><br><br>
                Link berlaku 1 jam.
            ";

            $mail->send();
        } catch (Exception $e) {
            header("Location: ../auth/forgot_password.php?error=send_failed");
            exit;
        }

       header("Location: ../auth/forgot_password.php?success=email_sent");
        exit;
    }

    public function validateResetToken($token)
    {
        $stmt = $this->conn->prepare("SELECT * FROM password_reset WHERE token = ? LIMIT 1");
        $stmt->execute([$token]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return "invalid"; // token tidak ditemukan
        }

        // CEK EXPIRED
        if (strtotime($data['expired_at']) < time()) {
            return "expired"; // token kadaluarsa
        }

        return $data; // token valid → return data user
    }

    public function resetPassword()
    {
        $token = $_POST['token'];
        $password = $_POST['password'];

        // Minimal 6 karakter
        if (strlen($password) < 6) {
            header("Location: ../auth/reset_password.php?token=$token&error=pw_short");
            exit;
        }

        // Validasi token
        $stmt = $this->conn->prepare("SELECT * FROM password_reset WHERE token = ? LIMIT 1");
        $stmt->execute([$token]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data || strtotime($data['expired_at']) < time()) {
            header("Location: ../auth/reset_password.php?error=invalid_token");
            exit;
        }

        // Update password
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $u = $this->conn->prepare("UPDATE user SET password = ? WHERE id = ?");
        $u->execute([$hashed, $data['id_user']]);

        // Hapus token setelah dipakai
        $del = $this->conn->prepare("DELETE FROM password_reset WHERE token = ?");
        $del->execute([$token]);

        header("Location: ../auth/login.php?success=password_reset_success");
        exit;
    }

}

/* ============================================
   EKSEKUSI FORM TANPA ROUTER (SIMPLE MODE)
============================================ */

$auth = new AuthController($db);

if (isset($_POST['action'])) {

    

    if ($_POST['action'] == "register") {
        $auth->register();
    }

    if ($_POST['action'] == "login") {
        $auth->login();
    }

    if ($_POST['action'] == "forgot_password") {
        $auth->forgotPassword();
    }

    if ($_POST['action'] == "reset_password") {
        $auth->resetPassword();
    }

}
