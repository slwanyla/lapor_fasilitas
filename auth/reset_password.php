<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password</title>
<link rel="stylesheet" href="../css/auth.css">
</head>
<body>

<?php
require_once "../koneksi.php";
require_once "../controllers/authcontroller.php";

$auth = new AuthController($db);

$token = $_GET['token'] ?? null;
$check = $auth->validateResetToken($token);
?>

<?php if ($check === "invalid"): ?>
    <p>Token tidak valid.</p>

<?php elseif ($check === "expired"): ?>
    <p>Link reset password sudah kadaluarsa.</p>

<?php else: ?>

<div class="container">
    <h2 class="auth-title">Reset Password</h2>
    <div class="line"></div>

    <!-- FORM RESET PASSWORD -->
    <form method="POST" action="../controllers/authcontroller.php">
        
        <input type="hidden" name="action" value="reset_password">
        <input type="hidden" name="token" value="<?= $token ?>">

        <div class="form-group password-wrapper">
            <label>New Password</label>
            <input type="password" name="password" id="pass1" required>
            <i onclick="togglePassword('pass1')"></i>
        </div>

        <div class="form-group password-wrapper">
            <label>Confirm Password</label>
            <input type="password" id="pass2" required>
            <i onclick="togglePassword('pass2')"></i>
        </div>

        <button style="margin-top: 50px;">Submit</button>
    </form>
</div>

<script>
function togglePassword(id) {
    const pass = document.getElementById(id);
    pass.type = (pass.type === "password") ? "text" : "password";
}
</script>

<?php endif; ?>
</body>
</html>
