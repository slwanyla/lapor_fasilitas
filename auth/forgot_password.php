<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password</title>
<link rel="stylesheet" href="../css/auth.css">

<style>
.mini-alert {
    background: #ff4d4d;         
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
    <form method="POST" action="../controllers/authcontroller.php">
        <input type="hidden" name="action" value="forgot_password">

    <div class="container">
        <h2 class="auth-title">Forgot Password</h2>
        <div class="line"></div>

        <p  class="form-group" style="margin-bottom: 40px;"> enter your email address and we will send you
        a link to reset yout password</p>

        <?php if (isset($_GET['error']) && $_GET['error']=='email_not_found'): ?>
        <div class="mini-alert" id="miniAlert">
            Email tidak ditemukan
            <span class="close-mini" onclick="closeMiniAlert()">✕</span>
            </div>
        <?php endif; ?>


        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>

            <div class="forgot-text" style="font-size: 12px; margin-top: 5px;">
                Link expired. <a href="forgot_password.php" style="font-size: 12px;">Send again</a>
            </div>

        </div>

    


        <button style="margin-top: 50px;">Submit</button>

        <div class="login-link">
            <a href="login.php">Kembali halaman signin</a>
        </div>
    </div>

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
