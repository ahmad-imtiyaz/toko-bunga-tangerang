<?php
$wa_url  = setting('whatsapp_url');
$wa_msg  = urlencode('Halo, saya ingin memesan bunga dari Toko Bunga Grogol. Mohon info lebih lanjut.');
$wa_full = $wa_url . '?text=' . $wa_msg;
$cats    = db()->query("SELECT name, slug FROM categories WHERE status='active' ORDER BY id LIMIT 10")->fetchAll();
$locs    = db()->query("SELECT name, slug FROM locations WHERE status='active' ORDER BY id")->fetchAll();
?>

<!-- FOOTER -->
<footer class="bg-navy text-white pt-16 pb-8 mt-0">
  <div class="max-w-7xl mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">

   <!-- Brand -->
<div class="lg:col-span-1">
  <div class="flex items-center gap-3 mb-4 group">
    
    <!-- Logo -->
    <div class="w-10 h-10 rounded-full bg-sage flex items-center justify-center shadow overflow-hidden transition duration-300 group-hover:scale-110 group-hover:shadow-lg">
     <img src="<?= BASE_URL ?>/assets/images/icon.png"
     alt="Logo"
     class="w-full h-full object-cover transition duration-500 group-hover:rotate-6">
    </div>

    <!-- Site Name -->
    <div class="font-serif font-bold text-lg leading-tight transition duration-300 group-hover:scale-105">
      <?= e(setting('site_name')) ?>
    </div>

  </div>

  <p class="text-gray-300 text-sm leading-relaxed mb-4">
    <?= e(setting('footer_text')) ?>
  </p>

  <div class="flex gap-3">
    <a href="<?= e($wa_full) ?>" target="_blank"
       class="w-9 h-9 bg-green-500 rounded-full flex items-center justify-center hover:bg-green-400 transition text-sm">
      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
        <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
      </svg>
    </a>
  </div>
</div>

      <!-- Layanan -->
      <div>
        <h3 class="font-serif font-semibold text-base mb-4 text-sky-light border-b border-white/10 pb-2">Layanan Kami</h3>
        <ul class="space-y-2">
          <?php foreach ($cats as $cat): ?>
          <li>
            <a href="<?= BASE_URL ?>/<?= e($cat['slug']) ?>/" class="text-gray-300 hover:text-sky text-sm transition flex items-center gap-1.5">
              <span class="text-sage text-xs">›</span> <?= e($cat['name']) ?>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Area Layanan -->
      <div>
        <h3 class="font-serif font-semibold text-base mb-4 text-sky-light border-b border-white/10 pb-2">Area Pengiriman</h3>
        <ul class="space-y-2">
          <?php foreach ($locs as $loc): ?>
          <li>
            <a href="<?= BASE_URL ?>/<?= e($loc['slug']) ?>/" class="text-gray-300 hover:text-sky text-sm transition flex items-center gap-1.5">
              <span class="text-sage text-xs">›</span> <?= e($loc['name']) ?>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Kontak -->
      <div>
        <h3 class="font-serif font-semibold text-base mb-4 text-sky-light border-b border-white/10 pb-2">Hubungi Kami</h3>
        <ul class="space-y-3 text-sm text-gray-300">
          <li class="flex gap-2.5">
            <span class="text-sage mt-0.5">📍</span>
            <span><?= e(setting('address')) ?></span>
          </li>
          <li class="flex gap-2.5">
            <span class="text-sage">📞</span>
            <a href="tel:<?= e(setting('whatsapp_number')) ?>" class="hover:text-white transition"><?= e(setting('phone_display')) ?></a>
          </li>
          <li class="flex gap-2.5">
            <span class="text-sage">✉️</span>
            <a href="mailto:<?= e(setting('email')) ?>" class="hover:text-white transition"><?= e(setting('email')) ?></a>
          </li>
          <li class="flex gap-2.5">
            <span class="text-sage">⏰</span>
            <span><?= e(setting('jam_buka')) ?></span>
          </li>
        </ul>
        <a href="<?= e($wa_full) ?>" target="_blank"
           class="mt-5 inline-flex items-center gap-2 bg-green-500 hover:bg-green-400 text-white text-sm font-semibold px-5 py-2.5 rounded-full transition">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/></svg>
          Chat WhatsApp
        </a>
      </div>
    </div>

    <!-- Bottom bar -->
    <div class="border-t border-white/10 pt-6 flex flex-col md:flex-row justify-between items-center gap-3 text-xs text-gray-400">
      <p>© <?= date('Y') ?> <?= e(setting('site_name')) ?>. Hak cipta dilindungi.</p>
      <p>Website Florist Grogol Terpercaya | Pengiriman 24 Jam</p>
    </div>
  </div>
</footer>

<!-- STICKY WA BUTTON -->
<a href="<?= e($wa_full) ?>" target="_blank" rel="noopener"
   class="fixed bottom-5 right-5 z-50 flex items-center gap-2.5 bg-green-500 hover:bg-green-400 text-white font-semibold text-sm px-4 py-3 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 group"
   aria-label="Chat WhatsApp">
  <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
    <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
  </svg>
  <span class="whitespace-nowrap">Pesan Sekarang</span>
  <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-ping"></span>
  <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
</a>

<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
