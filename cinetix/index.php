<?php
declare(strict_types=1);
session_start();

require __DIR__ . '/config/database.php';
require __DIR__ . '/models/CrudModel.php';
require __DIR__ . '/models/AuthModel.php';
require __DIR__ . '/models/FilmModel.php';
require __DIR__ . '/controllers/AuthController.php';
require __DIR__ . '/controllers/DashboardController.php';
require __DIR__ . '/controllers/CrudController.php';
require __DIR__ . '/controllers/BookingController.php';

$entities = require __DIR__ . '/config/entities.php';
$auth = new AuthController(new AuthModel($pdo));
$filmModel = new FilmModel($pdo);
$dashboard = new DashboardController($pdo, $filmModel);

function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function currentUser(): ?array { return $_SESSION['auth'] ?? null; }
function isAdmin(): bool { return (currentUser()['role'] ?? '') === 'admin'; }
function isUser(): bool { return (currentUser()['role'] ?? '') === 'user'; }
function requireAdmin(): void { if (!isAdmin()) { header('Location: index.php?page=login'); exit; } }
function redirect(string $url = 'index.php'): never { header('Location: ' . $url); exit; }
function flash(string $message, string $type = 'success'): void { $_SESSION['flash'] = ['message'=>$message, 'type'=>$type]; }
function showFlash(): void {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        echo '<div class="alert ' . e($f['type']) . '">' . e($f['message']) . '</div>';
        unset($_SESSION['flash']);
    }
}
function csrfToken(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function verifyCsrf(): void {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(419);
        exit('Permintaan tidak valid. Muat ulang halaman lalu coba kembali.');
    }
}

$page = $_GET['page'] ?? 'home';
$entity = $_GET['entity'] ?? '';

if ($page === 'logout') {
    $auth->logout();
    redirect();
}

if ($page === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $role = ($_POST['role'] ?? 'user') === 'admin' ? 'admin' : 'user';
    if ($auth->login($role, trim($_POST['username'] ?? ''), $_POST['password'] ?? '')) {
        flash('Berhasil masuk. Selamat datang di CineTix.');
        redirect();
    }
    $error = 'Username atau password tidak sesuai.';
}

if ($page === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    try {
        $auth->register($_POST);
        flash('Registrasi berhasil. Silakan masuk menggunakan akun yang dibuat.');
        redirect('index.php?page=login');
    } catch (Throwable $ex) {
        $error = $ex instanceof PDOException ? 'Email atau username sudah digunakan. Periksa kembali data registrasi.' : $ex->getMessage();
    }
}

if ($page === 'book' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isUser()) redirect('index.php?page=login');
    verifyCsrf();
    try {
        (new BookingController($pdo))->create((int)currentUser()['id'], (int)($_POST['id_jadwal'] ?? 0), (int)($_POST['jumlah_tiket'] ?? 1));
        flash('Pemesanan berhasil dicatat. Silakan simpan informasi pemesanan untuk konfirmasi selanjutnya.');
    } catch (Throwable $ex) {
        flash($ex->getMessage(), 'error');
    }
    redirect('index.php?page=detail&id=' . (int)($_POST['id_film'] ?? 0));
}

if (in_array($page, ['manage','save','delete'], true)) requireAdmin();

if ($page === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($entities[$entity])) {
    verifyCsrf();
    $controller = new CrudController($pdo, $entities[$entity]);
    try {
        $id = (int)($_POST['id'] ?? 0);
        $controller->save($id > 0 ? $id : null, $_POST, $_FILES);
        flash('Data berhasil disimpan.');
    } catch (Throwable $ex) {
        flash($ex instanceof PDOException ? 'Data gagal disimpan. Periksa relasi dan kemungkinan data duplikat.' : $ex->getMessage(), 'error');
    }
    redirect('index.php?page=manage&entity=' . urlencode($entity) . ((int)($_POST['id'] ?? 0) ? '&edit=' . (int)$_POST['id'] : ''));
}

if ($page === 'delete' && isset($entities[$entity])) {
    $controller = new CrudController($pdo, $entities[$entity]);
    try {
        $controller->delete((int)($_GET['id'] ?? 0));
        flash('Data berhasil dihapus.');
    } catch (Throwable $ex) {
        flash('Data tidak dapat dihapus karena masih digunakan oleh data lain atau memiliki relasi.');
    }
    redirect('index.php?page=manage&entity=' . urlencode($entity));
}

$keyword = trim($_GET['q'] ?? '');
$genre = trim($_GET['genre'] ?? '');
$movies = $filmModel->search($keyword, $genre);
$genres = $filmModel->genres();
$detail = null;
$schedules = [];

if ($page === 'detail') {
    $detail = $filmModel->find((int)($_GET['id'] ?? 0));
    if ($detail) $schedules = $filmModel->schedules((int)$detail['id_film']);
}

if ($page === 'manage' && isset($entities[$entity])) {
    $cfg = $entities[$entity];
    $crud = new CrudController($pdo, $cfg);
    $rows = $crud->all();
    $editRow = isset($_GET['edit']) ? $crud->find((int)$_GET['edit']) : null;
    $selectOptions = [];
    foreach ($cfg['fields'] as $field) {
        if (($field['type'] ?? '') === 'select') $selectOptions[$field['table']] = $crud->optionsFor($field);
    }
}

if ($page === 'home' || $page === 'detail') {
    require __DIR__ . '/views/partials/header.php';
    if ($page === 'detail') require __DIR__ . '/views/film-detail.php';
    else require __DIR__ . '/views/home.php';
    require __DIR__ . '/views/partials/footer.php';
    exit;
}

if ($page === 'login' || $page === 'register') {
    require __DIR__ . '/views/auth.php';
    exit;
}

if ($page === 'admin') {
    requireAdmin();
    $stats = $dashboard->stats();
    $recent = $dashboard->recentBookings();
    require __DIR__ . '/views/partials/header.php';
    require __DIR__ . '/views/admin-dashboard.php';
    require __DIR__ . '/views/partials/footer.php';
    exit;
}

if ($page === 'manage' && isset($entities[$entity])) {
    requireAdmin();
    require __DIR__ . '/views/partials/header.php';
    require __DIR__ . '/views/manage.php';
    require __DIR__ . '/views/partials/footer.php';
    exit;
}

redirect();
