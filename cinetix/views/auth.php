<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= $page === 'login' ? 'Masuk' : 'Registrasi' ?> — CineTix</title><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet"><link rel="stylesheet" href="assets/css/style.css"></head>
<body class="auth-page">
<a class="brand auth-brand" href="index.php"><span class="brand-symbol">C</span><span>Cine<strong>Tix</strong></span></a>
<div class="auth-card">
    <div class="eyebrow"><?= $page === 'login' ? 'SELAMAT DATANG KEMBALI' : 'JADI BAGIAN DARI CINETIX' ?></div>
    <h1><?= $page === 'login' ? 'Masuk ke CineTix' : 'Buat akun baru' ?></h1>
    <p class="auth-intro"><?= $page === 'login' ? 'Masuk untuk menemukan film dan jadwal favoritmu.' : 'Isi data berikut untuk mulai memesan tiket bioskop.' ?></p>
    <?php showFlash(); ?>
    <?php if (!empty($error)): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
    <?php if ($page === 'login'): ?>
    <form method="post" action="index.php?page=login">
        <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
        <label class="form-field"><span>Masuk sebagai</span><select name="role"><option value="user">User</option><option value="admin">Administrator</option></select></label>
        <label class="form-field"><span>Username</span><input type="text" name="username" placeholder="Masukkan username" required autocomplete="username"></label>
        <label class="form-field"><span>Password</span><input type="password" name="password" placeholder="Masukkan password" required autocomplete="current-password"></label>
        <button class="button-primary full-width" type="submit">Masuk</button>
    </form>
    <div class="auth-switch">Belum punya akun? <a href="index.php?page=register">Daftar sekarang</a></div>
    <?php else: ?>
    <form method="post" action="index.php?page=register">
        <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
        <div class="form-grid">
            <label class="form-field"><span>Nama lengkap</span><input name="nama" required value="<?= e($_POST['nama'] ?? '') ?>" placeholder="Nama sesuai identitas"></label>
            <label class="form-field"><span>Email</span><input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>" placeholder="nama@email.com"></label>
            <label class="form-field"><span>Nomor HP</span><input name="no_hp" required value="<?= e($_POST['no_hp'] ?? '') ?>" placeholder="08xxxxxxxxxx"></label>
            <label class="form-field"><span>Username</span><input name="username" required value="<?= e($_POST['username'] ?? '') ?>" placeholder="Buat username"></label>
            <label class="form-field span-two"><span>Alamat</span><textarea name="alamat" required placeholder="Masukkan alamat lengkap"><?= e($_POST['alamat'] ?? '') ?></textarea></label>
            <label class="form-field"><span>Password</span><input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter"></label>
            <label class="form-field"><span>Confirm password</span><input type="password" name="confirm_password" required minlength="6" placeholder="Ulangi password"></label>
        </div>
        <button class="button-primary full-width" type="submit">Buat akun</button>
    </form>
    <div class="auth-switch">Sudah punya akun? <a href="index.php?page=login">Masuk</a></div>
    <?php endif; ?>
    <a class="back-home" href="index.php">← Kembali ke beranda</a>
</div>
</body></html>
