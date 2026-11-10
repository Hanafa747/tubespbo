<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>CineTix — Bioskop dalam genggaman</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="topbar">
    <a class="brand" href="index.php"><span class="brand-symbol">C</span><span>Cine<strong>Tix</strong></span></a>
    <?php if (($page ?? '') === 'home'): ?>
    <form class="top-search" action="index.php" method="get">
        <input type="hidden" name="page" value="home">
        <span class="search-icon">⌕</span>
        <input type="search" name="q" value="<?= e($keyword ?? '') ?>" placeholder="Cari judul film...">
        <button type="submit">Cari</button>
    </form>
    <?php else: ?>
    <div class="topbar-caption">YOUR MOVIE, YOUR MOMENT</div>
    <?php endif; ?>
    <div class="top-actions">
        <?php if (currentUser()): ?>
            <span class="hello-user">Hai, <?= e(currentUser()['nama']) ?></span>
            <?php if (isAdmin()): ?><a class="top-login" href="index.php?page=admin">Panel Admin</a><?php endif; ?>
            <a class="top-login" href="index.php?page=logout">Keluar</a>
        <?php else: ?>
            <a class="top-login" href="index.php?page=login">Masuk</a>
            <a class="top-register" href="index.php?page=register">Daftar</a>
        <?php endif; ?>
    </div>
</header>
<div class="app-shell">
<?php if (isAdmin()): ?>
<aside class="sidebar">
    <div class="side-caption">MENU UTAMA</div>
    <a class="<?= ($page ?? '') === 'admin' ? 'selected' : '' ?>" href="index.php?page=admin"><span>⌂</span> Dashboard</a>
    <div class="side-caption space-top">PENGELOLAAN DATA</div>
    <?php foreach ($entities as $key => $cfg): ?>
        <a class="<?= (($page ?? '') === 'manage' && ($entity ?? '') === $key) ? 'selected' : '' ?>" href="index.php?page=manage&entity=<?= e($key) ?>"><span>▦</span> <?= e($cfg['title']) ?></a>
    <?php endforeach; ?>
    <div class="sidebar-bottom"><span class="admin-dot"></span> Administrator</div>
</aside>
<?php endif; ?>
<main class="main-area <?= isAdmin() ? 'with-sidebar' : '' ?>">
