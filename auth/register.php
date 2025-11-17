<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up</title>
<link rel="stylesheet" href="../css/auth.css">

<style>
.mini-alert {
    background: #ff4d4d;          /* merah */
    color: white;
    padding: 10px 14px;
    border-radius: 6px;
    font-size: 14px;
    margin-bottom: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-left: 5px solid #d80000;
    animation: fadeIn 0.3s ease;
    position: relative;
    z-index: 10;
}

.close-mini {
    cursor: pointer;
    font-size: 18px;
    margin-left: 12px;
    opacity: 0.8;
}

.close-mini:hover {
    opacity: 1;
}

/* animasi */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-6px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

</head>

<body>
    
    <form action="../controllers/authcontroller.php" method="POST">
    <input type="hidden" name="action" value="register">
    <div class="container">
        <h2 class="auth-title">Sign Up</h2>
        <div class="line"></div>


   <?php if (isset($_GET['error']) && $_GET['error']=='pw_too_short'): ?>
        <div class="mini-alert" id="miniAlert">
            Password salah
            <span class="close-mini" onclick="closeMiniAlert()">✕</span>
        </div>
    <?php endif; ?>
    

    <!-- NAMA -->
    <div class="form-group">
        <label>Nama Lengkap</label>
        <input type="text" name="nama" required>
    </div>

    <!-- EMAIL -->
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
    </div>

    <!-- ROLE -->
    <div class="form-group">
        <label>Status</label>
        <select id="statusSelect" name="role" required>
            <option value="">Pilih Status</option>
            <option value="mahasiswa">Mahasiswa</option>
            <option value="staff">Staff</option>
            <option value="dosen">Dosen</option>
        </select>
    </div>

    <!-- IDENTIFIER -->
    <div class="form-group">
        <label>NIM / NIDN / NIP</label>
        <input type="text" name="identifier" required>
    </div>

    <!-- PRODI (KHUSUS MAHASISWA) -->
    <div class="form-group" id="prodi-group" style="display:none;">
        <label>Prodi</label>
        <input type="text" name="jurusan">
    </div>

    <!-- PASSWORD -->
    <div class="form-group password-wrapper">
        <label>Password</label>
        <input type="password" name="password" id="password" placeholder="Minimal 6 karakter" required>
        <i onclick="togglePassword()"></i>
        
    </div>

    <button type="submit">Create</button>

    <div class="login-link">
        Sudah Punya Akun? <a href="login.php">Signin Disini</a>
    </div>
</div>
</form>

    <script>
    function togglePassword() {
        const pass = document.getElementById("password");
        pass.type = (pass.type === "password") ? "text" : "password";
    }

    // Munculkan PRODI jika role = mahasiswa
    document.getElementById("statusSelect").addEventListener("change", function () {
        const prodi = document.getElementById("prodi-group");
        prodi.style.display = (this.value === "mahasiswa") ? "block" : "none";
    });

    </script>
    <script>
    function closeMiniAlert() {
        let box = document.getElementById("miniAlert");
        if (box) {
            box.style.display = "none";
        }
    }
    </script>


</body>
</html>
