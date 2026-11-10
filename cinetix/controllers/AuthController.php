<?php
declare(strict_types=1);

class AuthController {
    private AuthModel $model;
    public function __construct(AuthModel $model) { $this->model = $model; }

    public function login(string $role, string $username, string $password): bool {
        $account = $role === 'admin'
            ? $this->model->loginAdmin($username, $password)
            : $this->model->loginUser($username, $password);
        if (!$account) return false;
        session_regenerate_id(true);
        $_SESSION['auth'] = $account;
        return true;
    }

    public function register(array $post): void {
        $required = ['nama','email','no_hp','alamat','username','password','confirm_password'];
        foreach ($required as $field) {
            if (trim((string)($post[$field] ?? '')) === '') throw new RuntimeException('Semua kolom registrasi wajib diisi.');
        }
        if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) throw new RuntimeException('Format email tidak valid.');
        if ($post['password'] !== $post['confirm_password']) throw new RuntimeException('Password dan konfirmasi password tidak sama.');
        if (strlen($post['password']) < 6) throw new RuntimeException('Password minimal terdiri dari 6 karakter.');
        if ($this->model->usernameExists(trim($post['username']))) throw new RuntimeException('Username sudah digunakan.');
        if ($this->model->emailExists(trim($post['email']))) throw new RuntimeException('Email sudah terdaftar.');
        $user = [
            'nama' => trim($post['nama']),
            'email' => trim($post['email']),
            'no_hp' => trim($post['no_hp']),
            'alamat' => trim($post['alamat'])
        ];
        $this->model->register($user, trim($post['username']), $post['password']);
    }

    public function logout(): void {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }
}
