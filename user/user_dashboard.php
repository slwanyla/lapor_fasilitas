<?php include 'header.php'; ?>   <!-- HEADER & SIDEBAR -->
<?php include 'sidebar.php'; ?>
<link rel="stylesheet" href="../css/user.css">

<!-- ====== DASHBOARD CONTENT ====== -->
<div class="main-content">

    <!-- === SECTION: RINGKASAN === -->
    <h2 class="section-title">Dashboard</h2>

    <div class="stats-container">

        <div class="stats-box">
            <div class="stats-number"><?= $total ?></div>
            <div class="stats-label">Total Laporan</div>
        </div>

        <div class="stats-box">
            <div class="stats-number"><?= $baru ?></div>
            <div class="stats-label">Menunggu Diproses</div>
        </div>

        <div class="stats-box">
            <div class="stats-number"><?= $proses ?></div>
            <div class="stats-label">Sedang Diproses</div>
        </div>

        <div class="stats-box">
            <div class="stats-number"><?= $selesai ?></div>
            <div class="stats-label">Selesai</div>
        </div>

    </div>



    <!-- === SECTION: 5 LAPORAN TERBARU === -->
    <h2 class="section-title">Aktivitas Terbaru</h2>

    <div class="recent-container">
        <?php if ($recent->num_rows == 0): ?>
            <div class="no-data">Belum ada laporan.</div>
        <?php else: ?>
            <?php while ($row = $recent->fetch_assoc()): ?>
                <div class="recent-item">

                    <div class="recent-left">
                        <div class="recent-title"><?= $row['judul']; ?></div>
                        <div class="recent-date">
                            Update: <?= date('d M Y H:i', strtotime($row['updated_at'])); ?>
                        </div>
                    </div>

                    <div class="recent-right">
                        <span class="badge status-<?= $row['status']; ?>">
                            <?= ucfirst($row['status']); ?>
                        </span>

                        <a href="detail_laporan.php?id=<?= $row['id_laporan']; ?>" class="detail-btn">
                            Detail
                        </a>
                    </div>

                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

</div>

<link rel="stylesheet" href="../css/user.css">
