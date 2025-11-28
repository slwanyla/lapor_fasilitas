<div class="sidebar" id="sidebar">
    <div class="sidebar-top">
        <div class="logo">Administrator</div>
    </div>

    <div class="sidebar-menu">
        <ul class="menu">
            
            <li class="<?= basename($_SERVER['PHP_SELF']) == 'user_dashboard.php' ? 'active' : '' ?>">
                <a href="admin_dashboard.php">
                    <i class="fi fi-rr-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="<?= basename($_SERVER['PHP_SELF']) == 'DataUser.php' ? 'active' : '' ?>">
                <a href="DataUser.php">
                    <i class="fi fi-br-file-edit"></i>
                    <span>Data Pengguna</span>
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
