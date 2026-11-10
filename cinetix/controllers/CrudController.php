<?php
declare(strict_types=1);

class CrudController {
    private PDO $db;
    private array $config;
    private CrudModel $model;

    public function __construct(PDO $db, array $config) {
        $this->db = $db;
        $this->config = $config;
        $this->model = new CrudModel($db, $config['table'], $config['pk']);
    }

    public function all(): array { return $this->model->all(); }
    public function find(int $id): ?array { return $this->model->find($id); }

    public function optionsFor(array $field): array {
        return $this->model->options($field['table'], $field['key'], $field['display']);
    }

    public function save(?int $id, array $post, array $files): void {
        $data = [];
        foreach ($this->config['fields'] as $name => $field) {
            $type = $field['type'];
            if ($type === 'file') {
                if (!empty($files[$name]['name'])) {
                    $data[$name] = $this->uploadPoster($files[$name]);
                } elseif (!$id && !empty($field['required'])) {
                    throw new RuntimeException('Sampul film wajib dipilih.');
                }
                continue;
            }
            if ($type === 'password') {
                $value = (string)($post[$name] ?? '');
                if ($value === '' && $id) continue;
                if ($value === '') throw new RuntimeException('Password wajib diisi saat membuat data baru.');
                if (strlen($value) < 6) throw new RuntimeException('Password minimal terdiri dari 6 karakter.');
                $data[$name] = password_hash($value, PASSWORD_DEFAULT);
                continue;
            }
            $value = trim((string)($post[$name] ?? ''));
            if ($value === '' && !empty($field['required'])) throw new RuntimeException($field['label'] . ' wajib diisi.');
            if ($type === 'number' && $value !== '' && (float)$value < 0) throw new RuntimeException($field['label'] . ' tidak boleh negatif.');
            if ($type === 'email' && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) throw new RuntimeException('Format email tidak valid.');
            if ($type === 'datetime-local' && $value !== '') $value = str_replace('T', ' ', $value) . ':00';
            if ($value !== '') $data[$name] = $value;
        }

        if ($this->config['table'] === 'pemesanan') {
            $stmt = $this->db->prepare("SELECT id_film FROM jadwal_tayang WHERE id_jadwal = ?");
            $stmt->execute([(int)($data['id_jadwal'] ?? 0)]);
            $filmJadwal = $stmt->fetchColumn();
            if (!$filmJadwal || (int)$filmJadwal !== (int)($data['id_film'] ?? 0)) {
                throw new RuntimeException('Film yang dipilih harus sama dengan film pada jadwal tayang.');
            }
        }

        if ($id) $this->model->update($id, $data);
        else $this->model->insert($data);
    }

    private function uploadPoster(array $file): string {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) throw new RuntimeException('Upload sampul gagal.');
        if (($file['size'] ?? 0) > 4 * 1024 * 1024) throw new RuntimeException('Ukuran sampul maksimal 4 MB.');
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        $allowed = ['image/jpeg'=>'jpg', 'image/png'=>'png', 'image/webp'=>'webp'];
        if (!isset($allowed[$mime])) throw new RuntimeException('Format sampul harus JPG, PNG, atau WEBP.');
        $folder = dirname(__DIR__) . '/uploads/poster';
        if (!is_dir($folder)) mkdir($folder, 0755, true);
        $filename = bin2hex(random_bytes(12)) . '.' . $allowed[$mime];
        if (!move_uploaded_file($file['tmp_name'], $folder . '/' . $filename)) throw new RuntimeException('Gagal menyimpan file sampul.');
        return 'uploads/poster/' . $filename;
    }

    public function delete(int $id): void {
        $row = $this->model->find($id);
        $this->model->delete($id);
        if ($this->config['table'] === 'film' && $row && !empty($row['sampul'])) {
            $path = dirname(__DIR__) . '/' . $row['sampul'];
            if (is_file($path)) @unlink($path);
        }
    }
}
