CINETIX — WEBSITE PEMESANAN TIKET BIOSKOP
==========================================

TEKNOLOGI
- PHP 8.0+
- MySQL / MariaDB
- XAMPP (Apache dan MySQL)
- PDO untuk koneksi database
- MVC: Model, Controller, View

PENTING: DATABASE DIBUAT MANUAL OLEH PENGGUNA
Website ini TIDAK mengimpor atau membuat database secara otomatis. Database `cinetix` dan keenam tabel harus sudah dibuat di phpMyAdmin.

STRUKTUR DATABASE YANG DIHARAPKAN

1. Tabel `user`
   - id_user INT (PK, AUTO_INCREMENT)
   - nama VARCHAR(100)
   - email VARCHAR(100)
   - no_hp VARCHAR(15)
   - alamat TEXT

2. Tabel `film`
   - id_film INT (PK, AUTO_INCREMENT)
   - sampul VARCHAR(255)
   - judul VARCHAR(150)
   - genre VARCHAR(50)
   - sutradara VARCHAR(100)
   - sinopsis TEXT

3. Tabel `jadwal_tayang`
   - id_jadwal INT (PK, AUTO_INCREMENT)
   - id_film INT (FK ke film.id_film)
   - studio VARCHAR(50)
   - tanggal_tayang DATE
   - jam_tayang TIME
   - harga_tiket DECIMAL(10,2)

4. Tabel `pemesanan`
   - id_pemesanan INT (PK, AUTO_INCREMENT)
   - id_user INT (FK ke user.id_user)
   - id_film INT (FK ke film.id_film)
   - id_jadwal INT (FK ke jadwal_tayang.id_jadwal)
   - tanggal_pemesanan DATETIME
   - jumlah_tiket INT
   - total_harga DECIMAL(10,2)

5. Tabel `akun`
   - id_akun INT (PK, AUTO_INCREMENT)
   - id_user INT (FK ke user.id_user)
   - username VARCHAR(50)
   - password VARCHAR(255)
   - tanggal_dibuat DATETIME DEFAULT CURRENT_TIMESTAMP

6. Tabel `admin`
   - id_admin INT (PK, AUTO_INCREMENT)
   - nama_admin VARCHAR(100)
   - username VARCHAR(50)
   - password VARCHAR(255)
   - tanggal_dibuat DATETIME DEFAULT CURRENT_TIMESTAMP

CATATAN DATABASE
- Gunakan InnoDB untuk semua tabel.
- Kolom FK harus memiliki tipe data yang sama dengan kolom PK tujuan.
- `akun.username` dan `admin.username` sebaiknya diberi UNIQUE.
- `user.email` sebaiknya diberi UNIQUE.
- `akun.id_user` sebaiknya diberi UNIQUE agar satu user memiliki satu akun.
- `tanggal_dibuat` pada akun dan admin diisi otomatis dengan DEFAULT CURRENT_TIMESTAMP.
- `pemesanan.id_film` harus cocok dengan `jadwal_tayang.id_film` untuk jadwal yang dipilih.
- Password di tabel akun dan admin harus VARCHAR(255), karena aplikasi menyimpan password hash.

CARA MENJALANKAN
1. Ekstrak folder CineTix_Web ke C:\xampp\htdocs.
2. Jalankan Apache dan MySQL di XAMPP.
3. Pastikan database `cinetix` dan tabel-tabelnya sudah dibuat di phpMyAdmin.
4. Buka config/database.php. Sesuaikan host, username, dan password MySQL jika diperlukan.
5. Buka http://localhost/CineTix_Web/buat_admin_awal.php satu kali.
6. Jika muncul pesan berhasil, HAPUS file buat_admin_awal.php.
7. Buka http://localhost/CineTix_Web/.

AKUN ADMIN AWAL
Username: admin
Password: admin123
Login pilih role Administrator.

REGISTRASI USER
- User mengisi nama, email, nomor HP, alamat, username, password, dan confirm password.
- Data identitas disimpan di tabel `user`.
- Username dan password hash disimpan di tabel `akun`.
- tanggal_dibuat otomatis oleh MySQL.

FITUR
- Dashboard katalog film.
- Pencarian judul film dan filter genre, bisa digunakan bersamaan.
- Detail film, poster, sutradara, sinopsis, dan jadwal.
- Upload poster film JPG, PNG, WEBP maksimal 4 MB.
- Login terpisah untuk admin dan user.
- Registrasi user dengan konfirmasi password.
- Pemesanan tiket oleh user yang login.
- Admin dashboard dengan ringkasan data.
- CRUD keenam tabel: user, film, jadwal_tayang, pemesanan, akun, admin.
- User tidak mendapatkan menu pengelolaan admin.
- Password di-hash menggunakan password_hash dan diverifikasi dengan password_verify.

CATATAN
- Proyek ini disusun untuk tugas kuliah. Pengujian langsung dengan struktur database milik pengguna tetap perlu dilakukan.
- Penghapusan data yang masih direferensikan oleh tabel lain dapat ditolak oleh aturan Foreign Key.
- Fitur pembayaran online, pemilihan kursi, dan pencetakan e-ticket belum termasuk dalam lingkup fitur ini.
- Untuk penggunaan publik, tambahkan verifikasi email, pembatasan percobaan login, dan pengamanan produksi lainnya.
