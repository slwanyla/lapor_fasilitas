<?php
require_once "../koneksi.php";

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

        header("Location: ../auth/login.php?status=registered");
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
}
