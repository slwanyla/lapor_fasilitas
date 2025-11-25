<?php
session_start();

require_once "../koneksi.php";
require_once "../models/user.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/phpmailer/src/Exception.php';
require '../phpmailer/phpmailer/src/PHPMailer.php';
require '../phpmailer/phpmailer/src/SMTP.php';

class AuthController {

    private $user;

    public function __construct($db)
    {
        $this->user = new User($db); // model
    }

    /* ============================
                REGISTER
    ============================ */
    public function register()
    {
        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $identifier = $_POST['identifier'];
        $jurusan = $_POST['jurusan'] ?? null;

        if (strlen($_POST['password']) < 6) {
            header("Location: ../auth/register.php?error=pw_too_short");
            exit;
        }

        // CHECK EMAIL VIA MODEL
        if ($this->user->checkEmail($email)) {
            header("Location: ../auth/register.php?error=email_exists");
            exit;
        }

        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // identifier
        $nim  = $role == "mahasiswa" ? $identifier : null;
        $nidn = $role == "dosen"     ? $identifier : null;
        $nip  = $role == "staff"     ? $identifier : null;

        // INSERT via model
        $this->user->insertUser([
            "nama" => $nama,
            "email" => $email,
            "password" => $password,
            "role" => $role,
            "nim" => $nim,
            "nidn" => $nidn,
            "nip" => $nip,
            "prodi" => $jurusan
        ]);

        header("Location: ../auth/login.php?success=registered");
        exit;
    }

    /* ============================
                 LOGIN
    ============================ */
    public function login()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->user->getByEmail($email);

        if (!$user) {
            header("Location: ../auth/login.php?error=email_not_found");
            exit;
        }

        if (!password_verify($password, $user['password'])) {
            header("Location: ../auth/login.php?error=wrong_password");
            exit;
        }

        session_start(); // pastikan session sudah dimulai
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['role']      = $user['role'];

        // Tambahkan ini untuk nama dan NIM
        $_SESSION['user_name'] = $user['nama'];

        // simpan identitas tambahan sesuai role
        if ($user['role'] == 'mahasiswa') {
            $_SESSION['user_nim'] = $user['nim'];
        } elseif ($user['role'] == 'dosen') {
            $_SESSION['user_nidn'] = $user['nidn'];
        } elseif ($user['role'] == 'staff') {
            $_SESSION['user_nip'] = $user['nip'];
        }


        if ($user['role'] == "admin") {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../user/user_dashboard.php");
        }

        exit;
    }


    /* ============================
           FORGOT PASSWORD
    ============================ */
    public function forgotPassword()
    {
        $email = $_POST['email'];

        // Cek email via model
        $user = $this->user->checkEmail($email);

        if (!$user) {
            header("Location: ../auth/forgot_password.php?error=email_not_found");
            exit;
        }

        // Token
        $token = bin2hex(random_bytes(32));
        $expired = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Simpan token
        $this->user->saveResetToken($user['id'], $token, $expired);

        $resetLink = "http://localhost/lapor_fasilitas/auth/reset_password.php?token=" . $token;

        // Kirim email
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "laporaja77@gmail.com";
            $mail->Password = "mnyv cnpn pxdj trqx";
            $mail->SMTPSecure = "ssl";
            $mail->Port = 465;

            $mail->setFrom("laporaja77@gmail.com", "Reset Password System");
            $mail->addAddress($email);

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

    /* ============================
            VALIDATE TOKEN
    ============================ */
    public function validateResetToken($token)
    {
        $data = $this->user->getTokenData($token);

        if (!$data) {
            return "invalid";
        }

        if (strtotime($data['expired_at']) < time()) {
            return "expired";
        }

        return $data;
    }

    /* ============================
             RESET PASSWORD
    ============================ */
    public function resetPassword()
    {
        $token = $_POST['token'];
        $password = $_POST['password'];
        $confirm = $_POST['confirm_password'];

        if ($password !== $confirm) {
            header("Location: ../auth/reset_password.php?token=$token&error=pw_not_match");
            exit;
        }

        if (strlen($password) < 6) {
            header("Location: ../auth/reset_password.php?token=$token&error=pw_short");
            exit;
        }

        // cek token via model
        $data = $this->user->getTokenData($token);

        if (!$data || strtotime($data['expired_at']) < time()) {
            header("Location: ../auth/reset_password.php?error=invalid_token");
            exit;
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // update password
        $this->user->updatePassword($data['id_user'], $hashed);

        // hapus token
        $this->user->deleteToken($token);

        header("Location: ../auth/login.php?success=password_reset_success");
        exit;
    }
}

/* ============================
      ROUTER SIMPLE
============================= */

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
