<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In</title>
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

    <?php include '../alert.php'; showAlert(); ?>
    
    <form action="../controllers/authcontroller.php" method="POST">
    <input type="hidden" name="action" value="login">
    <div class="container">
        <h2 class="auth-title">Sign In</h2>
        <div class="line"></div>

    <?php if (isset($_GET['error']) && $_GET['error'] == "wrong_password"): ?>
        <div class="mini-alert" id="miniAlert">
            Email atau password salah
            <span class="close-mini" onclick="document.getElementById('miniAlert').style.display='none'">×</span>
        </div>
    <?php endif; ?>



    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
    </div>

    <div class="form-group password-wrapper">
        <label>Password</label>
        <input type="password" id="password" name="password" required>
        <i class="fi fi-br-eye" onclick="togglePassword(this)"></i>

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
    function togglePassword(icon) {
        const field = document.getElementById("password");

        if (field.type === "password") {
            field.type = "text";
            icon.classList.remove("fi-br-eye");
            icon.classList.add("fi-br-eye-crossed");
        } else {
            field.type = "password";
            icon.classList.remove("fi-br-eye-crossed");
            icon.classList.add("fi-br-eye");
        }
    }
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
