<?php
// ============================================================
// Config: Database & App Settings
// ============================================================
define('DB_HOST',   'localhost');
define('DB_USER',   'root');
define('DB_PASS',   '');
define('DB_NAME',   'tokobungatangerang');
define('DB_CHARSET','utf8mb4');

define('BASE_URL',  'http://localhost/TOKOBUNGATANGERANG');
define('SITE_NAME', 'Toko Bunga Tangerang');

// Upload path
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', BASE_URL . '/uploads/');

// Timezone
date_default_timezone_set('Asia/Jakarta');

// ============================================================
// Database Connection (PDO)
// ============================================================
function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die('<div style="font-family:sans-serif;padding:20px;background:#fee;border:1px solid #f00;border-radius:8px;margin:20px;">
                <strong>Database Error:</strong> ' . htmlspecialchars($e->getMessage()) . '
                </div>');
        }
    }
    return $pdo;
}

// ============================================================
// Helper: Get setting value
// ============================================================
function setting(string $key, string $default = ''): string {
    static $cache = [];
    if (!isset($cache[$key])) {
        $stmt = db()->prepare("SELECT `value` FROM settings WHERE `key` = ? LIMIT 1");
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        $cache[$key] = $row ? (string)$row['value'] : $default;
    }
    return $cache[$key];
}

// ============================================================
// Helper: Get all settings as array
// ============================================================
function allSettings(): array {
    $stmt = db()->query("SELECT `key`, `value` FROM settings");
    $result = [];
    foreach ($stmt->fetchAll() as $row) {
        $result[$row['key']] = $row['value'];
    }
    return $result;
}

// ============================================================
// Helper: Format harga Rupiah
// ============================================================
function rupiah(float $amount): string {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

// ============================================================
// Helper: Sanitize output
// ============================================================
function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// ============================================================
// Helper: WA link builder
// ============================================================
function waLink(string $message = ''): string {
    $wa = setting('whatsapp_number', '6281322991131');
    $msg = urlencode($message ?: 'Halo, saya ingin memesan bunga dari Toko Bunga Tangerang.');
    return "https://wa.me/{$wa}?text={$msg}";
}

// ============================================================
// Helper: Redirect
// ============================================================
function redirect(string $url): void {
    header("Location: {$url}");
    exit;
}

// ============================================================
// Helper: Active nav check
// ============================================================
function isActive(string $path): string {
    $current = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return str_contains($current, $path) ? 'active' : '';
}

// ============================================================
// Image helper — return upload URL or placeholder
// ============================================================
function imgUrl(string $filename, string $type = 'product'): string {
    if (!empty($filename) && file_exists(UPLOAD_DIR . $filename)) {
        return UPLOAD_URL . $filename;
    }
    // Unsplash placeholder based on type
    $placeholders = [
        'product'     => 'https://images.unsplash.com/photo-1487530811015-780780dde0e4?w=400&h=300&fit=crop',
        'category'    => 'https://images.unsplash.com/photo-1490750967868-88df5691cc69?w=400&h=300&fit=crop',
        'testimonial' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop',
        'hero'        => 'https://images.unsplash.com/photo-1487530811015-780780dde0e4?w=1200&h=600&fit=crop',
    ];
    return $placeholders[$type] ?? $placeholders['product'];
}
