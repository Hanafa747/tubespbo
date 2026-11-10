<?php
declare(strict_types=1);

class DashboardController {
    private PDO $db;
    private FilmModel $films;
    public function __construct(PDO $db, FilmModel $films) { $this->db = $db; $this->films = $films; }

    public function stats(): array {
        return [
            'film' => (int)$this->db->query("SELECT COUNT(*) FROM film")->fetchColumn(),
            'user' => (int)$this->db->query("SELECT COUNT(*) FROM `user`")->fetchColumn(),
            'jadwal' => (int)$this->db->query("SELECT COUNT(*) FROM jadwal_tayang")->fetchColumn(),
            'pemesanan' => (int)$this->db->query("SELECT COUNT(*) FROM pemesanan")->fetchColumn()
        ];
    }

    public function recentBookings(): array {
        $sql = "SELECT p.*, u.nama, f.judul, j.tanggal_tayang, j.jam_tayang
                FROM pemesanan p
                LEFT JOIN `user` u ON u.id_user=p.id_user
                LEFT JOIN film f ON f.id_film=p.id_film
                LEFT JOIN jadwal_tayang j ON j.id_jadwal=p.id_jadwal
                ORDER BY p.id_pemesanan DESC LIMIT 6";
        return $this->db->query($sql)->fetchAll();
    }
}
