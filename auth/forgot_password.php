<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password</title>
<link rel="stylesheet" href="../css/auth.css">


</head>

<body>

    <form method="POST" action="../controllers/authcontroller.php">
        <input type="hidden" name="action" value="forgot_password">

    <div class="container">
        <h2 class="auth-title">Forgot Password</h2>
        <div class="line"></div>

        <p  class="form-group" style="margin-bottom: 40px;"> enter your email address and we will send you
        a link to reset yout password</p>

        <div class="form-group">
            <label>Email</label>
            <input type="email">

            <div class="forgot-text" style="font-size: 12px; margin-top: 5px;">
                Link expired. <a href="forgot_password.php" style="font-size: 12px;">Send again</a>
            </div>

        </div>

    


        <button style="margin-top: 50px;">Submit</button>

        <div class="login-link">
            <a href="login.php">Kembali halaman signin</a>
        </div>
    </div>

</body>
</html>
