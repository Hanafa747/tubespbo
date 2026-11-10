<?php showFlash(); ?>
<div class="admin-welcome"><div><div class="eyebrow">OVERVIEW</div><h1>Dashboard Admin</h1><p>Ringkasan aktivitas dan pengelolaan CineTix.</p></div><div class="date-pill"><?= date('d F Y') ?></div></div>
<div class="stats-grid">
    <div class="stat-card"><span class="stat-icon">▣</span><small>Total Film</small><strong><?= $stats['film'] ?></strong><span class="stat-note">Film terdaftar</span></div>
    <div class="stat-card"><span class="stat-icon">♙</span><small>Total User</small><strong><?= $stats['user'] ?></strong><span class="stat-note">Pengguna terdaftar</span></div>
    <div class="stat-card"><span class="stat-icon">◷</span><small>Jadwal Tayang</small><strong><?= $stats['jadwal'] ?></strong><span class="stat-note">Seluruh jadwal</span></div>
    <div class="stat-card"><span class="stat-icon">▤</span><small>Pemesanan</small><strong><?= $stats['pemesanan'] ?></strong><span class="stat-note">Transaksi tercatat</span></div>
</div>
<section class="admin-panel">
    <div class="panel-heading"><div><div class="eyebrow">AKTIVITAS TERBARU</div><h2>Pemesanan terakhir</h2></div><a href="index.php?page=manage&entity=pemesanan">Kelola pemesanan →</a></div>
    <div class="table-scroll"><table><thead><tr><th>Pengguna</th><th>Film</th><th>Jadwal</th><th>Jumlah</th><th>Total harga</th></tr></thead><tbody>
    <?php foreach ($recent as $item): ?><tr><td><?= e($item['nama'] ?? '-') ?></td><td><?= e($item['judul'] ?? '-') ?></td><td><?= e($item['tanggal_tayang'] ?? '-') ?> <?= e($item['jam_tayang'] ?? '') ?></td><td><?= (int)$item['jumlah_tiket'] ?></td><td>Rp <?= number_format((float)$item['total_harga'],0,',','.') ?></td></tr><?php endforeach; ?>
    <?php if (!$recent): ?><tr><td colspan="5" class="table-empty">Belum ada pemesanan.</td></tr><?php endif; ?>
    </tbody></table></div>
</section>
