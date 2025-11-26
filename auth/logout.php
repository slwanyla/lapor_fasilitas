<?php
session_start();

// Hapus semua session
session_unset();
session_destroy();

// Arahkan ke halaman login
header("Location: ../auth/login.php?success=logged_out");
exit;

