<?php
require_once __DIR__ . '/includes/auth.php';

// Logout
if (isset($_GET['logout'])) {
    logout();
}

// Login form handler
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if (login($username, $password)) {
        header('Location: ' . BASE_URL . '/admin/');
        exit;
    }
    $error = 'Username atau password salah.';
}

// If not logged in, show login page
if (!isLoggedIn()) {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= BASE_URL ?>/assets/images/icon.png">
    <title>Login — Admin Toko Bunga Grogol</title>
    <meta name="robots" content="noindex, nofollow">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            cream: '#FDF8F0', sage: '#7A9E7E', navy: '#2C3E6B'
          },
          fontFamily: {
            sans: ['"DM Sans"', 'sans-serif'],
            serif: ['"Playfair Display"', 'serif']
          }
        }
      }
    }
    </script>
    </head>
    <body class="min-h-screen flex items-center justify-center bg-cream font-sans relative overflow-hidden">
      <!-- Background blobs -->
      <div class="absolute top-0 left-0 w-64 h-64 bg-sage/10 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
      <div class="absolute bottom-0 right-0 w-80 h-80 bg-navy/5 rounded-full translate-x-1/3 translate-y-1/3"></div>

      <div class="w-full max-w-sm mx-4 relative">
    <!-- Logo area -->
<div class="text-center mb-8">
  <div class="w-16 h-16 bg-sage rounded-full flex items-center justify-center shadow-lg mx-auto mb-4 overflow-hidden">
    <img src="../assets/images/icon.png"
         alt="Logo"
         class="w-full h-full object-cover">
  </div>
  <h1 class="font-serif text-2xl font-bold text-navy">Admin Panel</h1>
  <p class="text-gray-500 text-sm mt-1">Toko Bunga Grogol</p>
</div>

        <!-- Login card -->
        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
          <h2 class="font-semibold text-navy text-base mb-6">Masuk ke Dashboard</h2>

          <?php if ($error): ?>
          <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-5">
            ⚠️ <?= e($error) ?>
          </div>
          <?php endif; ?>

          <form method="POST" action="" class="space-y-4">
            <div>
              <label class="form-label">Username</label>
              <input type="text" name="username" required autocomplete="username"
                     class="form-input" placeholder="Username admin">
            </div>
            <div>
              <label class="form-label">Password</label>
              <input type="password" name="password" required autocomplete="current-password"
                     class="form-input" placeholder="••••••••">
            </div>
            <button type="submit"
                    class="w-full bg-sage hover:bg-sage-dark text-white font-bold py-3 rounded-xl transition shadow mt-2">
              Masuk →
            </button>
          </form>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
          <a href="<?= BASE_URL ?>/" class="hover:text-sage transition">← Kembali ke Website</a>
        </p>
      </div>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    </body>
    </html>
    <?php
    exit;
}

// Dispatch admin pages
$page = $_GET['page'] ?? '';
$current_page = $page;

switch ($page) {
    case 'products':     require __DIR__ . '/pages/products.php';     break;
    case 'categories':   require __DIR__ . '/pages/categories.php';   break;
    case 'locations':    require __DIR__ . '/pages/locations.php';     break;
    case 'testimonials': require __DIR__ . '/pages/testimonials.php'; break;
    case 'faqs':         require __DIR__ . '/pages/faqs.php';         break;
    case 'settings':     require __DIR__ . '/pages/settings.php';     break;
    default:             require __DIR__ . '/pages/dashboard.php';    break;
}
