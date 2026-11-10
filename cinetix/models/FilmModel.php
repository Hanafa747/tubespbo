<?php
declare(strict_types=1);

class FilmModel {
    private PDO $db;
    public function __construct(PDO $db) { $this->db = $db; }

    public function search(string $keyword = '', string $genre = ''): array {
        $sql = "SELECT f.* FROM film f WHERE 1=1";
        $params = [];
        if ($keyword !== '') {
            $sql .= " AND f.judul LIKE ?";
            $params[] = "%{$keyword}%";
        }
        if ($genre !== '') {
            $sql .= " AND f.genre = ?";
            $params[] = $genre;
        }
        $sql .= " ORDER BY f.id_film DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM film WHERE id_film = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function genres(): array {
        return $this->db->query("SELECT DISTINCT genre FROM film WHERE genre <> '' ORDER BY genre")->fetchAll(PDO::FETCH_COLUMN);
    }

    public function schedules(int $idFilm): array {
        $stmt = $this->db->prepare("SELECT * FROM jadwal_tayang WHERE id_film = ? AND tanggal_tayang >= CURDATE() ORDER BY tanggal_tayang, jam_tayang");
        $stmt->execute([$idFilm]);
        return $stmt->fetchAll();
    }

    public function upcoming(): array {
        return $this->db->query("SELECT COUNT(*) FROM jadwal_tayang WHERE tanggal_tayang >= CURDATE()")->fetchColumn();
    }
}
