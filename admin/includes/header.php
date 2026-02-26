<?php
$page_title = $page_title ?? 'Dashboard';
$admin_user = adminUser();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="<?= BASE_URL ?>/assets/images/icon.png">
<title><?= e($page_title) ?> — Admin Toko Bunga Grogol</title>
<meta name="robots" content="noindex, nofollow">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  theme: {
    extend: {
      colors: {
        cream: { DEFAULT: '#FDF8F0', dark: '#F5EDD8' },
        sage:  { DEFAULT: '#7A9E7E', dark: '#5C7C60', light: '#A8C5AC' },
        navy:  { DEFAULT: '#2C3E6B', dark: '#1E2D52' },
      },
      fontFamily: {
        sans:  ['"DM Sans"', 'sans-serif'],
        serif: ['"Playfair Display"', 'serif'],
      }
    }
  }
}
</script>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body class="font-sans bg-gray-50 text-gray-800">

<div class="flex min-h-screen">
  <!-- Sidebar -->
  <aside class="w-64 bg-navy text-white flex flex-col flex-shrink-0 shadow-xl" id="sidebar">
<!-- Logo -->
<div class="px-6 py-5 border-b border-white/10">
  <div class="flex items-center gap-3 group">
    <div class="w-9 h-9 bg-sage rounded-full flex items-center justify-center shadow overflow-hidden transition duration-300 group-hover:scale-110 group-hover:shadow-lg">
      <img src="<?= BASE_URL ?>/assets/images/icon.png"
           alt="Logo"
           class="w-full h-full object-cover transition duration-500 group-hover:rotate-6">
    </div>
    <div class="transition duration-300 group-hover:translate-x-0.5">
      <div class="font-serif font-semibold text-sm leading-tight">Admin Panel</div>
      <div class="text-xs text-gray-400">Toko Bunga Jkt Barat</div>
    </div>
  </div>
</div>
    <!-- Nav -->
    <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">
      <?php
      $nav_items = [
        ['url' => '',             'icon' => '📊', 'label' => 'Dashboard'],
        ['url' => 'products',     'icon' => '🌺', 'label' => 'Produk'],
        ['url' => 'categories',   'icon' => '📂', 'label' => 'Kategori'],
        ['url' => 'locations',    'icon' => '📍', 'label' => 'Area/Kecamatan'],
        ['url' => 'testimonials', 'icon' => '⭐', 'label' => 'Testimoni'],
        ['url' => 'faqs',         'icon' => '❓', 'label' => 'FAQ'],
        ['url' => 'settings',     'icon' => '⚙️', 'label' => 'Pengaturan'],
      ];
      $current_page = $current_page ?? '';
      foreach ($nav_items as $item):
        $isActive = ($current_page === $item['url']) ? 'bg-sage text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white';
      ?>
      <a href="<?= BASE_URL ?>/admin/<?= $item['url'] ? '?page=' . $item['url'] : '' ?>"
         class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition <?= $isActive ?>">
        <span class="text-base"><?= $item['icon'] ?></span>
        <?= $item['label'] ?>
      </a>
      <?php endforeach; ?>
    </nav>

    <!-- Footer sidebar -->
    <div class="px-6 py-4 border-t border-white/10">
      <div class="text-xs text-gray-400 mb-3">Login sebagai <span class="text-white font-semibold"><?= e($admin_user) ?></span></div>
      <a href="<?= BASE_URL ?>/admin/?logout=1" class="block w-full text-center bg-white/10 hover:bg-white/20 text-white text-xs font-semibold py-2 rounded-lg transition">
        🚪 Logout
      </a>
    </div>
  </aside>

  <!-- Main content area -->
  <main class="flex-1 flex flex-col min-w-0">
    <!-- Top bar -->
    <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between sticky top-0 z-10 shadow-sm">
      <h1 class="font-serif text-lg font-semibold text-navy"><?= e($page_title) ?></h1>
      <div class="flex items-center gap-4">
        <a href="<?= BASE_URL ?>/" target="_blank" class="text-sm text-sage hover:underline flex items-center gap-1">
          🌐 Lihat Website
        </a>
        <div class="w-8 h-8 bg-sage rounded-full flex items-center justify-center text-white text-sm font-bold">
          <?= strtoupper(substr($admin_user, 0, 1)) ?>
        </div>
      </div>
    </header>

    <!-- Page content injected here -->
    <div class="flex-1 p-6 overflow-y-auto">
