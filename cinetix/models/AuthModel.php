<?php
declare(strict_types=1);

class AuthModel {
    private PDO $db;
    public function __construct(PDO $db) { $this->db = $db; }

    public function loginAdmin(string $username, string $password): ?array {
        $stmt = $this->db->prepare("SELECT * FROM `admin` WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $row = $stmt->fetch();
        if (!$row || !password_verify($password, $row['password'])) return null;
        return ['id' => (int)$row['id_admin'], 'nama' => $row['nama_admin'], 'username' => $row['username'], 'role' => 'admin'];
    }

    public function loginUser(string $username, string $password): ?array {
        $sql = "SELECT a.id_akun, a.username, a.password, u.id_user, u.nama
                FROM akun a INNER JOIN `user` u ON u.id_user = a.id_user
                WHERE a.username = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        $row = $stmt->fetch();
        if (!$row || !password_verify($password, $row['password'])) return null;
        return ['id' => (int)$row['id_user'], 'id_akun' => (int)$row['id_akun'], 'nama' => $row['nama'], 'username' => $row['username'], 'role' => 'user'];
    }

    public function register(array $user, string $username, string $password): void {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("INSERT INTO `user` (nama,email,no_hp,alamat) VALUES (?,?,?,?)");
            $stmt->execute([$user['nama'], $user['email'], $user['no_hp'], $user['alamat']]);
            $idUser = (int)$this->db->lastInsertId();
            $stmt = $this->db->prepare("INSERT INTO akun (id_user,username,password) VALUES (?,?,?)");
            $stmt->execute([$idUser, $username, password_hash($password, PASSWORD_DEFAULT)]);
            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function usernameExists(string $username): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM akun WHERE username = ? UNION ALL SELECT COUNT(*) FROM `admin` WHERE username = ?");
        $stmt->execute([$username, $username]);
        foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $count) if ((int)$count > 0) return true;
        return false;
    }

    public function emailExists(string $email): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM `user` WHERE email = ?");
        $stmt->execute([$email]);
        return (int)$stmt->fetchColumn() > 0;
    }
}
