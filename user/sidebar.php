<div class="sidebar" id="sidebar">
    <div class="sidebar-top">
        <div class="logo">LaporAja</div>
    </div>

    <div class="sidebar-menu">
        <ul class="menu">
            
            <li class="<?= basename($_SERVER['PHP_SELF']) == 'user_dashboard.php' ? 'active' : '' ?>">
                <a href="user_dashboard.php">
                    <i class="fi fi-rr-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="<?= basename($_SERVER['PHP_SELF']) == 'lapor.php' ? 'active' : '' ?>">
                <a href="lapor.php">
                    <i class="fi fi-br-file-edit"></i>
                    <span>Buat Pengaduan</span>
                </a>
            </li>

            <li class="<?= basename($_SERVER['PHP_SELF']) == 'riwayat.php' ? 'active' : '' ?>">
                <a href="riwayat.php">
                    <i class="fi fi-rr-folder-open"></i>
                    <span>Riwayat Laporan</span>
                </a>
            </li>

        </ul>

        <div class="logout">
            <a href="../auth/login.php" onclick="return confirm('Yakin mau logout?')">
                <i class="fi fi-rr-exit"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
</div>
