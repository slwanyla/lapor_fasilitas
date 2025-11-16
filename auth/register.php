<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up</title>
<link rel="stylesheet" href="../css/auth.css">


</head>

<body>

<div class="container">
    <h2 class="auth-title">Sign Up</h2>
    <div class="line"></div>

    <div class="form-group">
        <label>Nama Lengkap</label>
        <input type="text">
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email">
    </div>

    <div class="form-group">
        <label>Status</label>
        <select id="statusSelect">
            <option value="">Pilih Status</option>
            <option value="mahasiswa">Mahasiswa</option>
            <option value="staff">Staff</option>
            <option value="dosen">Dosen</option>
        </select>
    </div>

    <div class="form-group">
        <label>NIM/NIDN/NIK</label>
        <input type="text">
    </div>

    <div class="form-group" id="prodi-group">
        <label>Prodi</label>
        <input type="text">
    </div>

    <div class="form-group password-wrapper">
        <label>Password</label>
        <input type="password" id="password">
        <i onclick="togglePassword()"></i>
    </div>

    <button>Create</button>

    <div class="login-link">
        Sudah Punya Akun? <a href="login.php">Signin Disini</a>
    </div>
</div>

<script>
function togglePassword() {
    const pass = document.getElementById("password");
    pass.type = (pass.type === "password") ? "text" : "password";
}

// Munculkan PRODI jika status = mahasiswa
document.getElementById("statusSelect").addEventListener("change", function () {
    const prodi = document.getElementById("prodi-group");
    prodi.style.display = (this.value === "mahasiswa") ? "block" : "none";
});
</script>

</body>
</html>
