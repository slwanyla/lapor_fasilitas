<?php include 'sidebar.php'; ?>
<?php include 'header.php'; ?>
<?php include '../alert.php'; showAlert(); ?>


<link rel="stylesheet" href="../assets/css/style.css">

<div class="main-content">

    <h2 class="section-title">Aktivitas Terbaru</h2>

    <div class="recent-container">

        <div class="filter-wrapper">
            <button class="filter-btn">
                <i class="fi fi-sr-filter"></i>
                Filter
                <i class="fi fi-br-angle-small-down dropdown-icon"></i>
            </button>

            <div class="filter-menu">
                <a href="#">All</a>
                <hr>
                <a href="#">New</a>
                <a href="#">Dalam Proses</a>
                <a href="#">Selesai</a>
                <a href="#">Tidak Valid</a>
            </div>
        </div>

        
        <!-- ITEM 1 -->
         <div class="recent-item">

            <div class="recent-left">
                <img class="recent-thumb" src="uploads/foto.jpg">

                <div class="recent-info">
                    <div class="recent-title">Lampu Jalan Mati</div>
                    <div class="recent-location">Jl. Melati No. 21</div>
                    <div class="recent-desc">Lampu tidak menyala sejak malam kemarin thtbttthtbtbtbtbtb
                        btbtbtbtbtbtbtbtbtbtbtbtbtbt.</div>
                </div>
            </div>

            <div class="recent-right">
                <div class="recent-date">18 Nov 2024 • 16:45</div>

                <div class="status-wrapper">
                    <span class="badge status-new">New</span>

                    <div class="dropdown">
                        <button class="dropdown-btn">▼</button>
                        <div class="dropdown-menu">
                            <a href="#">Edit</a>
                            <a href="#">Hapus</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ITEM 2 -->
        <div class="recent-item">

            <div class="recent-left">
                <img class="recent-thumb" src="uploads/foto.jpg">

                <div class="recent-info">
                    <div class="recent-title">Lampu Jalan Mati</div>
                    <div class="recent-location">Jl. Melati No. 21</div>
                    <div class="recent-desc">Lampu tidak menyala sejak malam kemarin.</div>
                </div>
            </div>

            <div class="recent-right">
                <div class="recent-date">18 Nov 2024 • 16:45</div>

                <div class="status-wrapper">
                    <span class="badge status-new">New</span>

                    <div class="dropdown">
                        <button class="dropdown-btn">▼</button>
                        <div class="dropdown-menu">
                            <a href="#">Edit</a>
                            <a href="#">Hapus</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>




        <!-- ITEM 3 -->
        <div class="recent-item">

            <div class="recent-left">
                <img class="recent-thumb" src="uploads/foto.jpg">

                <div class="recent-info">
                    <div class="recent-title">Lampu Jalan Mati</div>
                    <div class="recent-location">Jl. Melati No. 21</div>
                    <div class="recent-desc">Lampu tidak menyala sejak malam kemarin.</div>
                </div>
            </div>

            <div class="recent-right">
                <div class="recent-date">18 Nov 2024 • 16:45</div>

                <div class="status-wrapper">
                    <span class="badge status-new">Proses</span>

                    
                </div>
            </div>
        </div>


        <div class="recent-item">

            <div class="recent-left">
                <img class="recent-thumb" src="uploads/foto.jpg">

                <div class="recent-info">
                    <div class="recent-title">Lampu Jalan Mati</div>
                    <div class="recent-location">Jl. Melati No. 21</div>
                    <div class="recent-desc">Lampu tidak menyala sejak malam kemarin thtbttthtbtbtbtbtb
                        btbtbtbtbtbtbtbtbtbtbtbtbtbt.</div>
                </div>
            </div>

            <div class="recent-right">
                <div class="recent-date">18 Nov 2024 • 16:45</div>

                <div class="status-wrapper">
                    <span class="badge status-new">New</span>

                    <div class="dropdown">
                        <button class="dropdown-btn">▼</button>
                        <div class="dropdown-menu">
                            <a href="#">Edit</a>
                            <a href="#">Hapus</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="recent-item">

            <div class="recent-left">
                <img class="recent-thumb" src="uploads/foto.jpg">

                <div class="recent-info">
                    <div class="recent-title">Lampu Jalan Mati</div>
                    <div class="recent-location">Jl. Melati No. 21</div>
                    <div class="recent-desc">Lampu tidak menyala sejak malam kemarin thtbttthtbtbtbtbtb
                        btbtbtbtbtbtbtbtbtbtbtbtbtbt.</div>
                </div>
            </div>

            <div class="recent-right">
                <div class="recent-date">18 Nov 2024 • 16:45</div>

                <div class="status-wrapper">
                    <span class="badge status-new">New</span>

                    <div class="dropdown">
                        <button class="dropdown-btn">▼</button>
                        <div class="dropdown-menu">
                            <a href="#">Edit</a>
                            <a href="#">Hapus</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<script>
document.querySelector('.filter-btn').addEventListener('click', function () {
    const menu = document.querySelector('.filter-menu');
    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
});
</script>

