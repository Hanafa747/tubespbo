<?php showFlash(); ?>
<a class="back-link" href="index.php">← Kembali ke daftar film</a>
<?php if (!$detail): ?>
<div class="empty-state"><h2>Film tidak ditemukan</h2><a href="index.php">Kembali ke dashboard</a></div>
<?php else: ?>
<section class="film-detail">
    <div class="detail-poster">
        <?php if (!empty($detail['sampul'])): ?><img src="<?= e($detail['sampul']) ?>" alt="Poster <?= e($detail['judul']) ?>"><?php else: ?><div class="poster-placeholder"><strong><?= e($detail['judul']) ?></strong></div><?php endif; ?>
    </div>
    <div class="detail-content">
        <div class="eyebrow"><?= e($detail['genre']) ?></div>
        <h1><?= e($detail['judul']) ?></h1>
        <div class="director-line">Sutradara: <strong><?= e($detail['sutradara']) ?></strong></div>
        <h3>Sinopsis</h3>
        <p class="synopsis"><?= nl2br(e($detail['sinopsis'])) ?></p>
        <div class="schedule-heading"><div class="eyebrow">JADWAL PENAYANGAN</div><h2>Pilih waktu menonton</h2></div>
        <?php if (!currentUser()): ?><div class="login-hint">Masuk sebagai user untuk melakukan pemesanan tiket. <a href="index.php?page=login">Masuk sekarang</a></div><?php endif; ?>
        <?php if (!$schedules): ?><p class="muted">Belum ada jadwal tayang yang tersedia untuk film ini.</p><?php endif; ?>
        <?php foreach ($schedules as $schedule): ?>
        <div class="schedule-item">
            <div><strong><?= date('d M Y', strtotime($schedule['tanggal_tayang'])) ?> · <?= substr($schedule['jam_tayang'],0,5) ?></strong><small><?= e($schedule['studio']) ?></small></div>
            <b class="price">Rp <?= number_format((float)$schedule['harga_tiket'],0,',','.') ?></b>
            <?php if (isUser()): ?>
            <form method="post" action="index.php?page=book" class="book-form">
                <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                <input type="hidden" name="id_film" value="<?= (int)$detail['id_film'] ?>">
                <input type="hidden" name="id_jadwal" value="<?= (int)$schedule['id_jadwal'] ?>">
                <input type="number" name="jumlah_tiket" min="1" max="10" value="1" aria-label="Jumlah tiket">
                <button class="button-primary compact" type="submit">Pesan</button>
            </form>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
