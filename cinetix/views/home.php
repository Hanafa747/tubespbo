<?php showFlash(); ?>
<section class="hero-banner">
    <div class="hero-content">
        <div class="eyebrow">SELAMAT DATANG DI CINETIX</div>
        <h1>Cerita hebat dimulai<br><em>dari sebuah tiket.</em></h1>
        <p>Temukan film favoritmu dan nikmati pengalaman menonton di layar lebar.</p>
        <a href="#daftar-film" class="button-primary">Jelajahi film <span>→</span></a>
    </div>
    <div class="hero-art" aria-hidden="true"><div class="reel">CINE<br><b>TIX</b><small>THE MOVIE EXPERIENCE</small></div></div>
</section>

<section class="movie-section" id="daftar-film">
    <div class="section-heading">
        <div><div class="eyebrow">PILIHAN UNTUKMU</div><h2>Film yang tersedia</h2></div>
        <span class="result-count"><?= count($movies) ?> film ditemukan</span>
    </div>
    <form class="filter-bar" action="index.php" method="get">
        <input type="hidden" name="page" value="home">
        <label class="filter-search"><span>⌕</span><input type="search" name="q" value="<?= e($keyword) ?>" placeholder="Cari judul film..."></label>
        <label class="genre-select"><span>Genre</span>
            <select name="genre">
                <option value="">Semua genre</option>
                <?php foreach ($genres as $item): ?><option value="<?= e($item) ?>" <?= $genre === $item ? 'selected' : '' ?>><?= e($item) ?></option><?php endforeach; ?>
            </select>
        </label>
        <button class="button-filter" type="submit">Terapkan</button>
        <?php if ($keyword !== '' || $genre !== ''): ?><a class="clear-filter" href="index.php">Reset</a><?php endif; ?>
    </form>

    <div class="movie-grid">
        <?php foreach ($movies as $movie): ?>
        <a class="movie-card" href="index.php?page=detail&id=<?= (int)$movie['id_film'] ?>">
            <div class="movie-poster">
                <?php if (!empty($movie['sampul'])): ?>
                    <img src="<?= e($movie['sampul']) ?>" alt="Poster <?= e($movie['judul']) ?>" onerror="this.style.display='none';this.nextElementSibling.style.display='grid'">
                <?php endif; ?>
                <div class="poster-placeholder"><span>NOW SHOWING</span><strong><?= e($movie['judul']) ?></strong></div>
                <span class="genre-chip"><?= e($movie['genre']) ?></span>
            </div>
            <div class="movie-card-info"><h3><?= e($movie['judul']) ?></h3><p><?= e($movie['sutradara']) ?></p></div>
        </a>
        <?php endforeach; ?>
        <?php if (!$movies): ?><div class="empty-state"><div class="empty-icon">⌕</div><h3>Film tidak ditemukan</h3><p>Coba kata kunci atau genre yang berbeda.</p><a href="index.php">Tampilkan semua film</a></div><?php endif; ?>
    </div>
</section>
