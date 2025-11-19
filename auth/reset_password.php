<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password</title>
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

    .password-wrapper {
        position: relative;
    }

    .password-wrapper i {
        position: absolute;
        right: 12px;
        top: 70%;                 /* dari 50% → 60% biar lebih turun */
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 18px;
        color: black;
    }


</style>

</head>
<body>
    <?php include '../alert.php'; showAlert(); ?>

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

            <?php if (isset($_GET['error']) && $_GET['error']=='pw_short'): ?>
                <div class="mini-alert" id="miniAlert">
                    Minimal 6 karakter
                    <span class="close-mini" onclick="closeMiniAlert()">✕</span>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error']) && $_GET['error']=='pw_not_match'): ?>
                <div class="mini-alert" id="miniAlert">
                    Password tidak sama
                    <span class="close-mini" onclick="closeMiniAlert()">✕</span>
                </div>
            <?php endif; ?>

            <div class="form-group password-wrapper">
                <label>New Password</label>
                <input type="password" name="password" id="pass1" placeholder="Minimal 6 karakter" required>
            <i class="fi fi-br-eye" onclick="togglePassword('pass1', this)"></i>


            </div>

            <div class="form-group password-wrapper">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" id="pass2" required>
              <i class="fi fi-br-eye" onclick="togglePassword('pass2', this)"></i>

            </div>



            <button style="margin-top: 50px;">Submit</button>
        </form>
    </div>



<?php endif; ?>
    <script>
        function togglePassword(id, icon) {
            const field = document.getElementById(id);

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
