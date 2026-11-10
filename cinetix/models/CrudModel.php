<?php
declare(strict_types=1);

class CrudModel {
    private PDO $db;
    private string $table;
    private string $pk;

    public function __construct(PDO $db, string $table, string $pk) {
        $this->db = $db;
        $this->table = $table;
        $this->pk = $pk;
    }

    public function all(): array {
        return $this->db->query("SELECT * FROM `{$this->table}` ORDER BY `{$this->pk}` DESC")->fetchAll();
    }

    public function find(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE `{$this->pk}` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function insert(array $data): bool {
        $columns = array_keys($data);
        $quoted = array_map(fn($column) => "`{$column}`", $columns);
        $marks = implode(',', array_fill(0, count($columns), '?'));
        $sql = "INSERT INTO `{$this->table}` (" . implode(',', $quoted) . ") VALUES ({$marks})";
        return $this->db->prepare($sql)->execute(array_values($data));
    }

    public function update(int $id, array $data): bool {
        if (!$data) return true;
        $sets = [];
        foreach (array_keys($data) as $column) $sets[] = "`{$column}` = ?";
        $values = array_values($data);
        $values[] = $id;
        $sql = "UPDATE `{$this->table}` SET " . implode(',', $sets) . " WHERE `{$this->pk}` = ?";
        return $this->db->prepare($sql)->execute($values);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM `{$this->table}` WHERE `{$this->pk}` = ?");
        return $stmt->execute([$id]);
    }

    public function count(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM `{$this->table}`")->fetchColumn();
    }

    public function options(string $table, string $key, string $display): array {
        $sql = "SELECT `{$key}`, `{$display}` FROM `{$table}` ORDER BY `{$display}`";
        return $this->db->query($sql)->fetchAll();
    }
}
