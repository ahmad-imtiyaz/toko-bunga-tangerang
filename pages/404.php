<?php
require_once __DIR__ . '/../includes/config.php';
$meta_title = 'Halaman Tidak Ditemukan — Toko Bunga Tangerang';
$meta_desc  = 'Halaman yang Anda cari tidak ditemukan.';
require __DIR__ . '/../includes/header.php';
?>
<section class="min-h-[60vh] flex items-center justify-center py-20">
  <div class="text-center px-4">
    <div class="text-8xl mb-6">🌸</div>
    <h1 class="font-serif text-5xl font-bold text-navy mb-4">404</h1>
    <p class="text-xl text-gray-500 mb-8">Oops! Halaman yang Anda cari tidak ditemukan.</p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="<?= BASE_URL ?>/" class="bg-sage hover:bg-sage-dark text-white font-bold px-8 py-3.5 rounded-full transition shadow">← Kembali ke Beranda</a>
      <a href="<?= e(setting('whatsapp_url')) ?>" target="_blank" class="bg-white border-2 border-sage text-sage font-bold px-8 py-3.5 rounded-full hover:bg-cream transition">💬 Hubungi Kami</a>
    </div>
  </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
