<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In</title>
<link rel="stylesheet" href="../css/auth.css">


</head>

<body>

<div class="container">
    <h2 class="auth-title">Sign In</h2>
    <div class="line"></div>

    <div class="form-group">
        <label>Email</label>
        <input type="email">
    </div>

    <div class="form-group password-wrapper">
        <label>Password</label>
        <input type="password" id="password">
        <i onclick="togglePassword()"></i>

         <div class="forgot-text">
            <a href="forgot_password.php">Forgot Password?</a>
        </div>
    </div>
   

    <button>signin</button>

    <div class="login-link">
        Belum Punya Akun? <a href="register.php">Daftar Disini</a>
    </div>
</div>

<script>
function togglePassword() {
    const pass = document.getElementById("password");
    pass.type = (pass.type === "password") ? "text" : "password";
}

</script>

</body>
</html>
