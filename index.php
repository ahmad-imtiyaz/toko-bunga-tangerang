<?php
require_once __DIR__ . '/includes/config.php';

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$base = trim(parse_url(BASE_URL, PHP_URL_PATH), '/');
if ($base && str_starts_with($uri, $base)) {
    $uri = trim(substr($uri, strlen($base)), '/');
}
$uri = $uri ?: 'home';

// Homepage
if ($uri === '' || $uri === 'home') {
    require __DIR__ . '/pages/home.php';
    exit;
}

// Kategori produk
$stmt = db()->prepare("SELECT * FROM categories WHERE slug = ? AND status = 'active' LIMIT 1");
$stmt->execute([$uri]);
$category = $stmt->fetch();
if ($category) {
    require __DIR__ . '/pages/category.php';
    exit;
}

// Lokasi/kecamatan
$stmt = db()->prepare("SELECT * FROM locations WHERE slug = ? AND status = 'active' LIMIT 1");
$stmt->execute([$uri]);
$location = $stmt->fetch();
if ($location) {
    require __DIR__ . '/pages/location.php';
    exit;
}

// ============================================================
// BLOG ROUTES
// ============================================================

// Blog list → /blog
if ($uri === 'blog') {
    require __DIR__ . '/pages/blog.php';
    exit;
}

// Blog detail → /blog/{slug}
if (preg_match('#^blog/([a-z0-9-]+)/?$#', $uri, $m)) {
    $stmt = db()->prepare("
        SELECT b.*, bc.name AS cat_name, bc.slug AS cat_slug
        FROM blogs b
        LEFT JOIN blog_categories bc ON b.blog_category_id = bc.id
        WHERE b.slug = ? AND b.status = 'active'
        LIMIT 1
    ");
    $stmt->execute([$m[1]]);
    $blog = $stmt->fetch();

    if ($blog) {
        require __DIR__ . '/pages/blog-detail.php';
    } else {
        http_response_code(404);
        require __DIR__ . '/pages/404.php';
    }
    exit;
}

// ============================================================
// Static pages
// ============================================================
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