<?php
function showAlert() {

     // load css iconnya
    echo '
        <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css">
        <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-bold-rounded/css/uicons-bold-rounded.css">
        <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-brands/css/uicons-brands.css">
        <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css">
        <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-thin-rounded/css/uicons-thin-rounded.css">
        <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-bold-rounded/css/uicons-bold-rounded.css">
        <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css">
        <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css">



    ';


    if (!isset($_GET['success']) && !isset($_GET['error'])) return;

    $type = "";
    $title = "";
    $message = "";

    // SUCCESS
    if (isset($_GET['success'])) {
        switch ($_GET['success']) {
            case 'registered':
                $type = 'success';
                $title = 'Berhasil membuat akun,';
                $message = 'silahkan login';
                break;

            case 'updated':
                $type = 'success';
                $title = 'Berhasil diperbarui';
                $message = 'Data berhasil disimpan';
                break;

            case 'email_sent':
                $type = 'success';
                $title = 'Berhasil dikirim';
                $message = 'Link berhasil melalui email';
                break;
            
            case 'password_reset_success':
                $type = 'success';
                $title = 'Berhasil diubah';
                $message = 'Password berhasil diubah';
                break;
        }
    }

    // ERROR
    if (isset($_GET['error'])) {

        if ($_GET['error'] == 'wrong_password') {
            return; 
        }

         if ($_GET['error'] == 'pw_not_match') {
            return; 
        }

        switch ($_GET['error']) {
            case 'email_not_found':
                $type = 'error';
                $title = 'Anda belum mempunyai akun,';
                $message = 'silahkan daftar terlebih dahulu';
                break;

            case 'forbidden':
                $type = 'error';
                $title = 'Akses ditolak';
                $message = 'Anda tidak memiliki izin';
                break;
        }
    }
?>
    <div id="sideAlert" class="side-alert <?= $type ?>">
        <?php if ($type == 'success'): ?>
            <i class="fi fi-br-check-circle" style="font-size:40px;color:#32C86D;"></i>
        <?php else: ?>
            <i class="fi fi-br-cross-circle" style="font-size:40px;color:#FF3B3B;"></i>
        <?php endif; ?>

        <div>
            <b><?php echo $title; ?></b><br>
            <?php echo $message; ?>
        </div>  

    </div>

    <style>
        .side-alert {
            position: fixed;
            right: -400px;
            top: 20px;
            width: 320px;
            padding: 15px 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0px 4px 20px rgba(0,0,0,0.15);
            display: flex;
            gap: 15px;
            align-items: center;
            transition: 0.4s ease-in-out;
            z-index: 9999;
        }

        .side-alert.success { border-left: 6px solid #32C86D; }
        .side-alert.error { border-left: 6px solid #FF3B3B; }

        .side-alert.show { right: 20px; }
    </style>

    <script>
        const alertBox = document.getElementById("sideAlert");

        if (alertBox) {
            setTimeout(() => alertBox.classList.add("show"), 50);

            // auto-close
            setTimeout(() => alertBox.classList.remove("show"), 3000);
        }
    </script>

<?php } ?>
