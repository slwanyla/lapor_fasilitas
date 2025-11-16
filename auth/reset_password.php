<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password</title>
<link rel="stylesheet" href="../css/auth.css">


</head>

<body>

<div class="container">
    <h2 class="auth-title">Reset Password</h2>
    <div class="line"></div>

     <div class="form-group password-wrapper">
        <label>New Password</label>
        <input type="password" id="password">
        <i onclick="togglePassword()"></i>
    </div>
   

    <div class="form-group password-wrapper" >
        <label>Confirm Password</label>
        <input type="password" id="password">
        <i onclick="togglePassword()"></i>
    </div>
   

    <button style="margin-top: 50px;" >submit</button>
</div>

<script> 
function togglePassword() {
    const pass = document.getElementById("password");
    pass.type = (pass.type === "password") ? "text" : "password";
}

</script>

</body>
</html>
