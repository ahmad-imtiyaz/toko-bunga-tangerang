<?php
require_once __DIR__ . '/includes/config.php';

// Parse URI
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Remove base folder from URI if running in subfolder
$base = trim(parse_url(BASE_URL, PHP_URL_PATH), '/');
if ($base && str_starts_with($uri, $base)) {
    $uri = trim(substr($uri, strlen($base)), '/');
}

$uri = $uri ?: 'home';

// ============================================================
// ROUTER
// ============================================================

// Homepage
if ($uri === '' || $uri === 'home') {
    require __DIR__ . '/pages/home.php';
    exit;
}

// Kategori produk — /karangan-bunga-papan-jakarta-utara/ etc
$stmt = db()->prepare("SELECT * FROM categories WHERE slug = ? AND status = 'active' LIMIT 1");
$stmt->execute([$uri]);
$category = $stmt->fetch();
if ($category) {
    require __DIR__ . '/pages/category.php';
    exit;
}

// Lokasi/kecamatan — /toko-bunga-koja/ etc
$stmt = db()->prepare("SELECT * FROM locations WHERE slug = ? AND status = 'active' LIMIT 1");
$stmt->execute([$uri]);
$location = $stmt->fetch();
if ($location) {
    require __DIR__ . '/pages/location.php';
    exit;
}

// Static pages
switch ($uri) {
    case 'layanan':
        require __DIR__ . '/pages/services.php';
        break;
    case 'tentang':
        require __DIR__ . '/pages/about.php';
        break;
    case 'faq':
        require __DIR__ . '/pages/faq.php';
        break;
    case 'kontak':
        require __DIR__ . '/pages/contact.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/pages/404.php';
        break;
}
