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
