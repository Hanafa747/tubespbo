<?php
// Jalankan satu kali melalui browser: http://localhost/cinetix/buat_admin_awal.php
// Setelah selesai, HAPUS file ini dari folder proyek.
require __DIR__ . '/config/database.php';

$stmt = $pdo->prepare("SELECT id_admin, password FROM `admin` WHERE username = ? LIMIT 1");
$stmt->execute(['admin']);
$existing = $stmt->fetch();
$hash = password_hash('admin123', PASSWORD_DEFAULT);

if ($existing) {
    if (password_verify('admin123', $existing['password'])) {
        exit('Akun admin sudah siap digunakan. Username: admin. Password: admin123. Hapus file buat_admin_awal.php sekarang.');
    }
    $update = $pdo->prepare("UPDATE `admin` SET nama_admin = ?, password = ? WHERE id_admin = ?");
    $update->execute(['Administrator', $hash, $existing['id_admin']]);
    echo 'Akun admin yang sudah ada diperbarui. Username: admin. Password: admin123. Hapus file buat_admin_awal.php sekarang.';
} else {
    $insert = $pdo->prepare("INSERT INTO `admin` (nama_admin, username, password) VALUES (?, ?, ?)");
    $insert->execute(['Administrator', 'admin', $hash]);
    echo 'Akun administrator berhasil dibuat. Username: admin. Password: admin123. Hapus file buat_admin_awal.php sekarang.';
}
