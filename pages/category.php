<?php
require_once __DIR__ . '/../includes/config.php';

$meta_title    = $category['meta_title']    ?: 'Toko Bunga Grogol - ' . $category['name'];
$meta_desc     = $category['meta_description'] ?: '';
$meta_keywords = $category['name'] . ', toko bunga Grogol, florist Grogol';

$stmt = db()->prepare("SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.category_id = ? AND p.status='active' ORDER BY p.id");
$stmt->execute([$category['id']]);
$products = $stmt->fetchAll();

$all_cats_raw = db()->query("SELECT * FROM categories WHERE status='active' ORDER BY urutan ASC, id ASC")->fetchAll();
$all_cats = []; $all_cats_subs = [];
foreach ($all_cats_raw as $ac) {
    $pid = $ac['parent_id'] ?? null;
    if ($pid === null || $pid == 0) $all_cats[] = $ac;
    else $all_cats_subs[$pid][] = $ac;
}
$locations = db()->query("SELECT * FROM locations WHERE status='active' ORDER BY id")->fetchAll();
$wa_url    = setting('whatsapp_url');

$product_count = count($products);
$min_price     = !empty($products) ? min(array_column($products, 'price')) : 300000;

require __DIR__ . '/../includes/header.php';
?>

<style>
/* ═══════════════════════════════════════════
   CATEGORY PAGE — Imajinatif Navy + Gold
═══════════════════════════════════════════ */

/* ── Ticker text animasi ── */
@keyframes ticker {
  from { transform: translateX(0); }
  to   { transform: translateX(-50%); }
}
.cat-ticker-inner {
  animation: ticker 18s linear infinite;
  display: flex; white-space: nowrap;
}

/* ── Hero image parallax ── */
.cat-hero-img {
  transition: transform .1s linear;
  will-change: transform;
}

/* ── Number counter besar ── */
.stat-num {
  font-family: 'Playfair Display', serif;
  background: linear-gradient(135deg, #F5C518 0%, #FFE066 50%, #F5C518 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* ── Product card ── */
.cat-prod-overlay {
  transform: translateY(100%);
  transition: transform .38s cubic-bezier(.4,0,.2,1);
}
.cat-prod-card:hover .cat-prod-overlay { transform: translateY(0); }
.cat-prod-img {
  transition: transform .6s cubic-bezier(.4,0,.2,1);
}
.cat-prod-card:hover .cat-prod-img { transform: scale(1.08); }
.cat-prod-card {
  transition: box-shadow .3s ease;
}
.cat-prod-card:hover {
  box-shadow: 0 0 0 1.5px rgba(245,197,24,.5), 0 24px 64px rgba(0,0,0,.5);
}

/* ── Filter tabs ── */
.cat-tab {
  transition: all .25s ease;
  cursor: pointer;
  white-space: nowrap;
}
.cat-tab.active,
.cat-tab:hover {
  background: #F5C518;
  color: #0B1F4A;
  border-color: #F5C518;
}

/* ── SEO prose section ── */
.cat-prose h2, .cat-prose h3 {
  font-family: 'Playfair Display', serif;
  color: #fff;
}
.cat-prose p, .cat-prose li { color: rgba(255,255,255,.55); line-height: 1.85; }
.cat-prose a { color: #F5C518; }
.cat-prose strong { color: rgba(255,255,255,.85); }
.cat-prose ul { padding-left: 0; list-style: none; }
.cat-prose ul li::before { content: '✦ '; color: #F5C518; font-size: 10px; }

/* ── Diagonal divider ── */
.diagonal-cut {
  clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
}

/* ── Shimmer gold line ── */
@keyframes shimmer-x {
  0%   { background-position: -200% center; }
  100% { background-position: 200% center; }
}
.gold-line {
  height: 1px;
  background: linear-gradient(90deg, transparent, #F5C518, rgba(255,220,80,1), #F5C518, transparent);
  background-size: 200% auto;
  animation: shimmer-x 3s linear infinite;
}

/* ── Reveal animation ── */
@keyframes fadeUp {
  from { opacity:0; transform:translateY(24px); }
  to   { opacity:1; transform:translateY(0); }
}
.reveal { animation: fadeUp .6s ease both; }
.reveal-1 { animation-delay: .1s; }
.reveal-2 { animation-delay: .2s; }
.reveal-3 { animation-delay: .3s; }
.reveal-4 { animation-delay: .45s; }

/* Area strip */
.area-pill {
  transition: all .2s ease;
}
.area-pill:hover {
  background: rgba(245,197,24,.15);
  border-color: rgba(245,197,24,.4);
  color: #F5C518;
}
</style>

<!-- ════════════════════════════════════════════════
     HERO — Cinematic Full Height
════════════════════════════════════════════════ -->
<section class="relative overflow-hidden diagonal-cut"
         style="min-height: 520px; padding-top: 100px; background: #081729;">

  <!-- Foto background -->
  <?php if (!empty($category['image'])): ?>
  <div class="cat-hero-img absolute inset-0">
    <div class="absolute inset-0 bg-cover bg-center"
         style="background-image: url('<?= e(imgUrl($category['image'], 'category')) ?>')"></div>
    <!-- Overlay multi-layer dramatis -->
    <div class="absolute inset-0"
         style="background: linear-gradient(105deg, rgba(8,23,41,.97) 0%, rgba(8,23,41,.8) 45%, rgba(11,31,74,.6) 100%);"></div>
  </div>
  <?php else: ?>
  <!-- Fallback: dot pattern navy -->
  <div class="absolute inset-0"
       style="background: radial-gradient(circle at 70% 50%, rgba(245,197,24,.06) 0%, transparent 60%);"></div>
  <div class="absolute inset-0 opacity-[0.04]"
       style="background-image: radial-gradient(circle, #F5C518 1px, transparent 1px); background-size: 36px 36px;"></div>
  <?php endif; ?>

  <!-- Gold shimmer line bawah hero -->
  <div class="absolute bottom-0 left-0 right-0 gold-line" style="z-index:5;"></div>

  <!-- Breadcrumb -->
  <div class="relative z-10 max-w-7xl mx-auto px-4 pt-4 mb-10 reveal reveal-1">
    <nav class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest">
      <a href="<?= BASE_URL ?>/"
         class="text-white/35 hover:text-[#F5C518] transition">Beranda</a>
      <span class="text-white/20">—</span>
      <span class="text-[#F5C518]/70"><?= e($category['name']) ?></span>
    </nav>
  </div>

  <!-- Konten hero -->
  <div class="relative z-10 max-w-7xl mx-auto px-4 pb-28">
    <div class="max-w-2xl">

      <!-- Overline badge -->
      <div class="reveal reveal-1 inline-flex items-center gap-2 bg-[#F5C518]/10 border border-[#F5C518]/25 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase text-[#F5C518] mb-6">
        <span class="w-1.5 h-1.5 rounded-full bg-[#F5C518] animate-pulse inline-block"></span>
        Florist Terpercaya Grogol
      </div>

      <!-- Judul -->
      <h1 class="reveal reveal-2 font-serif text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight mb-5">
        <?= e($category['name']) ?><br>
        <span style="color:#F5C518;">di Grogol</span>
      </h1>

      <!-- Deskripsi -->
      <p class="reveal reveal-3 text-white/55 text-base md:text-lg leading-relaxed mb-8 max-w-lg">
        <?= !empty($category['meta_description']) ? e($category['meta_description']) : 'Toko bunga Grogol menyediakan ' . e(strtolower($category['name'])) . ' berkualitas tinggi dengan bunga segar pilihan. Pesan sekarang, kirim cepat ke seluruh Grogol.' ?>
      </p>

      <!-- Stats row -->
      <div class="reveal reveal-3 flex items-center gap-6 mb-8">
        <div>
          <div class="stat-num text-3xl font-black"><?= $product_count ?>+</div>
          <div class="text-[10px] font-bold uppercase tracking-widest text-white/35 mt-0.5">Produk</div>
        </div>
        <div class="w-px h-10" style="background: rgba(255,255,255,.1);"></div>
        <div>
          <div class="stat-num text-3xl font-black">2–4<span class="text-lg">Jam</span></div>
          <div class="text-[10px] font-bold uppercase tracking-widest text-white/35 mt-0.5">Pengiriman</div>
        </div>
        <div class="w-px h-10" style="background: rgba(255,255,255,.1);"></div>
        <div>
          <div class="stat-num text-2xl font-black"><?= 'Rp ' . number_format($min_price/1000, 0, ',', '.') . 'rb' ?></div>
          <div class="text-[10px] font-bold uppercase tracking-widest text-white/35 mt-0.5">Mulai dari</div>
        </div>
      </div>

      <!-- CTA -->
      <div class="reveal reveal-4 flex flex-wrap gap-3">
        <a href="<?= e($wa_url) ?>?text=<?= urlencode('Halo, saya ingin memesan ' . $category['name'] . ' di Grogol.') ?>"
           target="_blank"
           class="inline-flex items-center gap-2.5 font-bold text-[#0B1F4A] px-7 py-3.5 rounded-full no-underline transition hover:-translate-y-1 hover:shadow-[0_12px_32px_rgba(245,197,24,.45)]"
           style="background:#F5C518;">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
            <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
          </svg>
          Pesan Sekarang
        </a>
        <a href="#produk"
           class="inline-flex items-center gap-2 font-semibold text-white px-7 py-3.5 rounded-full no-underline transition hover:bg-white/10"
           style="border: 1.5px solid rgba(255,255,255,.2);">
          Lihat Produk ↓
        </a>
      </div>

    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════
     TICKER — Kategori lain bergerak
════════════════════════════════════════════════ -->
<div class="overflow-hidden py-3" style="background:#F5C518;">
  <div class="cat-ticker-inner">
    <?php for ($r = 0; $r < 2; $r++): ?>
    <?php foreach ($all_cats as $c): ?>
    <a href="<?= BASE_URL ?>/<?= e($c['slug']) ?>/"
       class="inline-flex items-center gap-3 mx-6 text-[#0B1F4A] font-bold text-[11px] uppercase tracking-widest no-underline hover:opacity-70 transition flex-shrink-0">
      <span class="text-[#0B1F4A]/40">✦</span>
      <?= e($c['name']) ?>
    </a>
    <?php endforeach; ?>
    <?php endfor; ?>
  </div>
</div>

<!-- ════════════════════════════════════════════════
     PRODUK GRID — Full width, dark cards
════════════════════════════════════════════════ -->
<section id="produk" class="py-20 relative overflow-hidden"
         style="background: #0B1F4A;">

  <!-- Dekorasi -->
  <div class="absolute top-0 left-0 w-full h-px gold-line"></div>
  <div class="absolute top-1/2 right-0 w-96 h-96 rounded-full pointer-events-none"
       style="background: radial-gradient(circle, rgba(245,197,24,.05) 0%, transparent 70%); filter: blur(60px);"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-4">

    <!-- Header section -->
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-12">
      <div>
        <div class="inline-flex items-center gap-2 bg-[#F5C518]/10 border border-[#F5C518]/25 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase text-[#F5C518] mb-4">
          <span class="w-1.5 h-1.5 rounded-full bg-[#F5C518] inline-block"></span>
          Koleksi <?= e($category['name']) ?>
        </div>
        <h2 class="font-serif text-3xl md:text-4xl font-black text-white">
          <?= $product_count ?> Produk Tersedia
        </h2>
      </div>
      <a href="<?= e($wa_url) ?>?text=<?= urlencode('Halo, saya ingin melihat katalog ' . $category['name'] . ' lengkap.') ?>"
         target="_blank"
         class="inline-flex items-center gap-2 font-bold text-[#0B1F4A] px-5 py-2.5 rounded-full no-underline transition hover:brightness-110 flex-shrink-0"
         style="background:#F5C518; font-size:13px;">
        Lihat Semua via WA →
      </a>
    </div>

    <!-- Grid produk -->
    <?php if (!empty($products)): ?>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
      <?php foreach ($products as $prod):
        $img     = imgUrl($prod['image'], 'product');
        $wa_prod = urlencode("Halo, saya tertarik memesan *{$prod['name']}* seharga " . rupiah($prod['price']) . ". Apakah masih tersedia?");
      ?>
      <div class="cat-prod-card group relative rounded-2xl overflow-hidden cursor-pointer"
           style="background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.07);">

        <div class="relative overflow-hidden aspect-[3/4]">
          <img src="<?= e($img) ?>"
               alt="<?= e($prod['name']) ?> Grogol"
               class="cat-prod-img w-full h-full object-cover"
               loading="lazy">

          <!-- Gradient gelap bawah -->
          <div class="absolute inset-0"
               style="background: linear-gradient(to top, rgba(8,23,41,.95) 0%, rgba(8,23,41,.15) 55%, transparent 100%);"></div>

          <!-- Badge kategori -->
          <?php if (!empty($prod['cat_name'])): ?>
          <span class="absolute top-3 left-3 text-[10px] font-bold tracking-wider uppercase px-2.5 py-1 rounded-full"
                style="background:rgba(245,197,24,.15); border:1px solid rgba(245,197,24,.3); color:#F5C518; backdrop-filter:blur(8px);">
            <?= e($prod['cat_name']) ?>
          </span>
          <?php endif; ?>

          <!-- Info nama + harga selalu terlihat -->
          <div class="absolute bottom-0 left-0 right-0 p-4 z-10">
            <h3 class="font-serif font-bold text-white text-sm leading-tight line-clamp-2 mb-1">
              <?= e($prod['name']) ?>
            </h3>
            <span class="font-bold text-[#F5C518] text-sm"><?= rupiah($prod['price']) ?></span>
          </div>

          <!-- Hover overlay slide up -->
          <div class="cat-prod-overlay absolute inset-0 z-20 flex flex-col justify-end p-4"
               style="background: linear-gradient(to top, rgba(8,23,41,1) 0%, rgba(8,23,41,.92) 55%, rgba(8,23,41,.5) 100%);">
            <h3 class="font-serif font-bold text-white text-sm leading-tight line-clamp-2 mb-1">
              <?= e($prod['name']) ?>
            </h3>
            <?php if (!empty($prod['description'])): ?>
            <p class="text-white/50 text-[11px] leading-relaxed line-clamp-2 mb-3">
              <?= e($prod['description']) ?>
            </p>
            <?php endif; ?>
            <div class="flex items-center justify-between gap-2">
              <span class="font-black text-[#F5C518] text-base font-serif"><?= rupiah($prod['price']) ?></span>
              <a href="<?= e($wa_url) ?>?text=<?= $wa_prod ?>" target="_blank"
                 class="inline-flex items-center gap-1.5 text-[#0B1F4A] font-bold text-[11px] px-3.5 py-2 rounded-full no-underline transition hover:brightness-110"
                 style="background:#F5C518;"
                 onclick="event.stopPropagation()">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                  <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
                </svg>
                Pesan
              </a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <?php else: ?>
    <!-- Empty state -->
    <div class="text-center py-20">
      <div class="text-5xl mb-4">🌸</div>
      <p class="text-white/40 text-lg mb-6">Produk sedang dipersiapkan</p>
      <a href="<?= e($wa_url) ?>" target="_blank"
         class="inline-flex items-center gap-2 font-bold text-[#0B1F4A] px-6 py-3 rounded-full"
         style="background:#F5C518;">
        Tanya via WhatsApp
      </a>
    </div>
    <?php endif; ?>

  </div>
</section>

<!-- ════════════════════════════════════════════════
     SEO CONTENT — Editorial style
════════════════════════════════════════════════ -->
<section class="py-20 relative overflow-hidden" style="background:#081729;">

  <div class="absolute top-0 left-0 w-full h-px gold-line"></div>
  <div class="absolute inset-0 opacity-[0.025]"
       style="background-image: radial-gradient(circle, #F5C518 1px, transparent 1px); background-size: 48px 48px;"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-4">
    <div class="grid md:grid-cols-2 gap-16 items-start">

      <!-- Kiri: Konten SEO -->
      <div class="cat-prose">
        <div class="inline-flex items-center gap-2 bg-[#F5C518]/10 border border-[#F5C518]/25 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase text-[#F5C518] mb-6">
          <span class="w-1.5 h-1.5 rounded-full bg-[#F5C518] inline-block"></span>
          Tentang Layanan
        </div>
        <h2 class="text-2xl md:text-3xl font-black mb-5 leading-tight">
          <?= e($category['name']) ?> Terbaik<br>di Grogol
        </h2>

        <?php if (!empty($category['content'])): ?>
        <div class="text-white/55 leading-relaxed text-[15px] mb-6">
          <?= $category['content'] ?>
        </div>
        <?php endif; ?>

        <p class="text-white/50 text-[15px] leading-relaxed mb-8">
          Kami sebagai <strong>florist Grogol</strong> terpercaya menyediakan <?= e(strtolower($category['name'])) ?> berkualitas tinggi dengan harga terjangkau. Setiap rangkaian bunga dibuat oleh tim florist profesional menggunakan bunga segar pilihan.
        </p>

        <h3 class="text-xl font-black mb-4">Mengapa Memilih Kami?</h3>
        <ul class="space-y-3 mb-8">
          <?php
          $keunggulan = [
            'Bunga 100% segar berkualitas premium',
            'Pengiriman cepat 2–4 jam ke seluruh Grogol',
            'Harga transparan mulai ' . rupiah($min_price),
            'Desain custom sesuai keinginan Anda',
            'Melayani pesanan mendadak 24 jam',
          ];
          foreach ($keunggulan as $k): ?>
          <li class="flex items-start gap-3 text-[14px]">
            <span class="text-[#F5C518] font-black mt-0.5 flex-shrink-0">✦</span>
            <span class="text-white/55"><?= $k ?></span>
          </li>
          <?php endforeach; ?>
        </ul>

        <h3 class="text-xl font-black mb-4">Cara Memesan</h3>
        <p class="text-white/50 text-[14px] leading-relaxed mb-6">
          Pemesanan sangat mudah! Hubungi kami via WhatsApp di <strong class="text-white/80"><?= e(setting('phone_display')) ?></strong> — informasikan jenis bunga, alamat, tanggal & jam pengiriman, dan pesan yang ingin dituliskan.
        </p>

   
      </div>

      <!-- Kanan: Area pengiriman + Kategori lain -->
      <div class="space-y-6">

        <!-- Area pengiriman -->
        <div class="rounded-2xl p-6" style="background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.07);">
          <div class="flex items-center gap-2 mb-5">
            <span class="text-[#F5C518] text-lg">📍</span>
            <h3 class="font-serif font-black text-white text-lg">Area Pengiriman</h3>
          </div>
          <p class="text-white/40 text-[13px] mb-4 leading-relaxed">
            Kami melayani pengiriman <?= e(strtolower($category['name'])) ?> ke seluruh kecamatan di Grogol:
          </p>
          <div class="flex flex-wrap gap-2">
            <?php foreach ($locations as $l): ?>
            <a href="<?= BASE_URL ?>/<?= e($l['slug']) ?>/"
               class="area-pill inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[12px] font-semibold text-white/60 no-underline"
               style="background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08);">
              <span class="w-1 h-1 rounded-full bg-[#F5C518]/50 flex-shrink-0 inline-block"></span>
              <?= e($l['name']) ?>
            </a>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Layanan lain -->
        <div class="rounded-2xl p-6" style="background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.07);">
          <h3 class="font-serif font-black text-white text-lg mb-5">Layanan Lainnya</h3>
          <div class="space-y-1" id="sidebar-cats">
            <?php foreach ($all_cats as $c):
              $c_subs   = $all_cats_subs[$c['id']] ?? [];
              $has_subs = !empty($c_subs);
              $is_active = $c['id'] == $category['id']
                        || isset($category['parent_id']) && $category['parent_id'] == $c['id'];
            ?>

            <?php if ($has_subs): ?>
            <!-- Parent dengan accordion -->
            <div class="sidebar-acc-wrap">
              <button onclick="toggleSidebarAcc(this)"
                      class="sidebar-acc-btn w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition <?= $is_active ? 'bg-[#F5C518]/10 border border-[#F5C518]/25' : '' ?>"
                      style="background: <?= $is_active ? '' : 'transparent' ?>; border: <?= $is_active ? '' : 'none' ?>; cursor:pointer;">
                <span class="text-[13px] font-medium <?= $is_active ? 'text-[#F5C518]' : 'text-white/55' ?> transition text-left">
                  <?= e($c['name']) ?>
                </span>
                <svg class="acc-chevron w-3.5 h-3.5 <?= $is_active ? 'text-[#F5C518]' : 'text-white/25' ?> transition-transform flex-shrink-0"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>

              <!-- Sub-kategori -->
              <div class="sidebar-acc-content <?= $is_active ? 'open' : '' ?> pl-3 border-l border-[#F5C518]/15 ml-4 mt-1">
                <a href="<?= BASE_URL ?>/<?= e($c['slug']) ?>/"
                   class="block px-3 py-1.5 text-[11px] font-bold text-[#F5C518]/50 hover:text-[#F5C518] no-underline transition">
                  Lihat semua <?= e($c['name']) ?> →
                </a>
                <?php foreach ($c_subs as $sub):
                  $is_sub_active = $sub['id'] == $category['id'];
                ?>
                <a href="<?= BASE_URL ?>/<?= e($sub['slug']) ?>/"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg text-[12px] no-underline transition
                          <?= $is_sub_active ? 'text-[#F5C518] font-semibold' : 'text-white/50 hover:text-[#F5C518]' ?>">
                  <span class="w-1 h-1 rounded-full <?= $is_sub_active ? 'bg-[#F5C518]' : 'bg-white/25' ?> flex-shrink-0 inline-block"></span>
                  <?= e($sub['name']) ?>
                </a>
                <?php endforeach; ?>
              </div>
            </div>

            <?php else: ?>
            <!-- Parent tanpa sub -->
            <a href="<?= BASE_URL ?>/<?= e($c['slug']) ?>/"
               class="flex items-center justify-between px-3 py-2.5 rounded-xl no-underline transition group/cat
                      <?= $is_active ? 'bg-[#F5C518]/10 border border-[#F5C518]/25' : 'hover:bg-white/5' ?>">
              <span class="text-[13px] font-medium <?= $is_active ? 'text-[#F5C518]' : 'text-white/55 group-hover/cat:text-[#F5C518]' ?> transition">
                <?= e($c['name']) ?>
              </span>
              <svg class="w-3.5 h-3.5 <?= $is_active ? 'text-[#F5C518]' : 'text-white/20 group-hover/cat:text-[#F5C518]' ?> transition"
                   fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </a>
            <?php endif; ?>

            <?php endforeach; ?>
          </div>

          <style>
            .sidebar-acc-content { max-height:0; overflow:hidden; transition: max-height .3s ease; }
            .sidebar-acc-content.open { max-height: 600px; }
            .sidebar-acc-btn.open .acc-chevron { transform: rotate(180deg); }
          </style>
          <script>
          function toggleSidebarAcc(btn) {
            const content = btn.nextElementSibling;
            const isOpen  = content.classList.contains('open');
            // Tutup semua
            document.querySelectorAll('.sidebar-acc-content.open').forEach(el => el.classList.remove('open'));
            document.querySelectorAll('.sidebar-acc-btn.open').forEach(el => el.classList.remove('open'));
            if (!isOpen) { btn.classList.add('open'); content.classList.add('open'); }
          }
          // Auto buka yang aktif
          document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.sidebar-acc-content.open').forEach(el => {
              el.previousElementSibling?.classList.add('open');
            });
          });
          </script>
        </div>

        <!-- CTA WA card -->
        <div class="rounded-2xl p-6 text-center relative overflow-hidden"
             style="background: linear-gradient(135deg, rgba(245,197,24,.12) 0%, rgba(245,197,24,.05) 100%); border:1px solid rgba(245,197,24,.2);">
          <div class="text-4xl mb-3">💬</div>
          <p class="font-serif font-bold text-white text-lg mb-1">Butuh Konsultasi?</p>
          <p class="text-white/40 text-sm mb-5">Kami siap membantu 24 jam sehari</p>
          <a href="<?= e($wa_url) ?>" target="_blank"
             class="inline-flex items-center justify-center gap-2 font-bold text-[#0B1F4A] px-6 py-3 rounded-full w-full no-underline transition hover:brightness-110"
             style="background:#F5C518;">
            Chat WhatsApp Sekarang
          </a>
        </div>

      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>