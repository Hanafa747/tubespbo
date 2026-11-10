<?php
declare(strict_types=1);

class BookingController {
    private PDO $db;
    public function __construct(PDO $db) { $this->db = $db; }

    public function create(int $idUser, int $idJadwal, int $jumlah): void {
        if ($jumlah < 1 || $jumlah > 10) throw new RuntimeException('Jumlah tiket harus antara 1 sampai 10.');
        $stmt = $this->db->prepare("SELECT id_film, harga_tiket, tanggal_tayang FROM jadwal_tayang WHERE id_jadwal = ?");
        $stmt->execute([$idJadwal]);
        $schedule = $stmt->fetch();
        if (!$schedule) throw new RuntimeException('Jadwal tidak ditemukan.');
        if ($schedule['tanggal_tayang'] < date('Y-m-d')) throw new RuntimeException('Jadwal tersebut sudah lewat.');
        $total = (float)$schedule['harga_tiket'] * $jumlah;
        $stmt = $this->db->prepare("INSERT INTO pemesanan (id_user,id_film,id_jadwal,tanggal_pemesanan,jumlah_tiket,total_harga) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$idUser, $schedule['id_film'], $idJadwal, date('Y-m-d H:i:s'), $jumlah, $total]);
    }
}
