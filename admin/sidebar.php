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
            <a href="#" onclick="openLogoutModal()">
                <i class="fi fi-rr-exit"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
</div> <!-- PENUTUP SIDEBAR -->


<!-- MODAL LOGOUT -->
<div id="logoutModal" class="modal">
    <div class="modal-content">
        <div class="modal-icon">
            <i class="fi fi-rr-info"></i>
        </div>

        <p>Apakah anda yakin untuk Logout?</p>

        <div class="modal-buttons">
            <a href="../auth/logout.php" class="btn btn-ya">YA</a>
            <button class="btn btn-tidak" onclick="closeLogoutModal()">TIDAK</button>
        </div>
    </div>
</div>