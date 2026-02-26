<?php
require_once __DIR__ . '/../includes/config.php';

$meta_title    = $category['meta_title']    ?: 'Toko Bunga Tangerang - ' . $category['name'];
$meta_desc     = $category['meta_description'] ?: '';
$meta_keywords = $category['name'] . ', toko bunga Tangerang, florist Tangerang';

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
   CATEGORY PAGE — Elegan Ivory + Rose/Blush
═══════════════════════════════════════════ */
:root {
  --cp-blush:  #F2C4CE;
  --cp-rose:   #D4899A;
  --cp-dusty:  #C8778A;
  --cp-cream:  #FAF5EE;
  --cp-ivory:  #FDF9F4;
  --cp-soft:   #F7EEF0;
  --cp-muted:  #8C6B72;
  --cp-dark:   #2C1A1E;
  --cp-warm:   #7A4F55;
}

/* ── Ticker ── */
@keyframes ticker {
  from { transform: translateX(0); }
  to   { transform: translateX(-50%); }
}
.cat-ticker-inner {
  animation: ticker 22s linear infinite;
  display: flex; white-space: nowrap;
}

/* ── Shimmer rose line ── */
@keyframes shimmer-x {
  0%   { background-position: -200% center; }
  100% { background-position: 200% center; }
}
.rose-line {
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--cp-blush), var(--cp-rose), var(--cp-blush), transparent);
  background-size: 200% auto;
  animation: shimmer-x 3.5s linear infinite;
}

/* ── Stat number ── */
.stat-num {
  font-family: 'Cormorant Garamond', serif;
  background: linear-gradient(135deg, var(--cp-rose) 0%, var(--cp-blush) 50%, var(--cp-dusty) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* ══════════════════════════════
   PRODUCT CARD — Tirai + Bunga
══════════════════════════════ */
.cat-prod-card {
  transition: box-shadow .35s ease, border-color .35s ease, transform .35s ease;
  position: relative;
  isolation: isolate;
}
.cat-prod-card:hover {
  box-shadow: 0 0 0 1.5px rgba(212,137,154,.5), 0 24px 56px rgba(44,26,30,.15);
  border-color: rgba(212,137,154,.4) !important;
  transform: translateY(-4px);
}
.cat-prod-img {
  transition: transform .7s cubic-bezier(.25,.46,.45,.94);
}
.cat-prod-card:hover .cat-prod-img { transform: scale(1.1); }

/* ── Tirai kiri-kanan ke tengah ── */
.cat-prod-overlay {
  pointer-events: none;
}
/* Dua panel tirai */
.curtain-left,
.curtain-right {
  position: absolute;
  top: 0; bottom: 0;
  width: 50%;
  z-index: 20;
  pointer-events: none;
  transition: transform .48s cubic-bezier(.77,0,.18,1);
}
.curtain-left  {
  left: 0;
  transform: translateX(-101%);
  background: linear-gradient(to right,
    rgba(44,26,30,.75) 0%,
    rgba(44,26,30,.65) 100%);
}
.curtain-right {
  right: 0;
  transform: translateX(101%);
  background: linear-gradient(to left,
    rgba(44,26,30,.75) 0%,
    rgba(44,26,30,.65) 100%);
}
.cat-prod-card:hover .curtain-left  { transform: translateX(0); }
.cat-prod-card:hover .curtain-right { transform: translateX(0); }

/* Konten overlay di atas tirai */
.curtain-content {
  position: absolute;
  inset: 0;
  z-index: 25;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  padding: 16px;
  pointer-events: none;
  opacity: 0;
  transform: translateY(8px);
  transition: opacity .3s ease .25s, transform .3s ease .25s;
}
.cat-prod-card:hover .curtain-content {
  opacity: 1;
  transform: translateY(0);
  pointer-events: auto;
}

/* ── Bunga melayang di sekitar card ── */
@keyframes floatUp {
  0%   { opacity:0; transform: translate(var(--fx,0), 0px) scale(.4) rotate(0deg); }
  20%  { opacity:1; }
  80%  { opacity:.7; }
  100% { opacity:0; transform: translate(var(--fx,0), -90px) scale(1) rotate(var(--fr, 30deg)); }
}
.card-petal {
  position: absolute;
  font-size: 18px;
  pointer-events: none;
  z-index: 40;
  animation: floatUp .9s ease forwards;
  will-change: transform, opacity;
}

/* ── Mobile: overlay selalu tampil ── */
@media (hover: none) {
  .curtain-left, .curtain-right {
    transform: translateX(0) !important;
    transition: none;
  }
  .curtain-content {
    opacity: 1 !important;
    transform: translateY(0) !important;
    transition: none;
  }
  .cat-prod-card { transform: none !important; }
}

/* ── Filter tabs ── */
.cat-tab {
  transition: all .25s ease;
  cursor: pointer; white-space: nowrap;
}
.cat-tab.active, .cat-tab:hover {
  background: linear-gradient(135deg, var(--cp-blush), var(--cp-dusty));
  color: #fff;
  border-color: transparent;
}

/* ── SEO prose ── */
.cat-prose h2, .cat-prose h3 {
  font-family: 'Cormorant Garamond', serif;
  color: var(--cp-dark);
}
.cat-prose p, .cat-prose li { color: var(--cp-muted); line-height: 1.85; }
.cat-prose a { color: var(--cp-dusty); }
.cat-prose strong { color: var(--cp-dark); }
.cat-prose ul { padding-left: 0; list-style: none; }
.cat-prose ul li::before { content: '✦ '; color: var(--cp-rose); font-size: 10px; }

/* ── Reveal animation ── */
@keyframes fadeUp {
  from { opacity:0; transform:translateY(22px); }
  to   { opacity:1; transform:translateY(0); }
}
.reveal   { animation: fadeUp .6s ease both; }
.reveal-1 { animation-delay: .08s; }
.reveal-2 { animation-delay: .18s; }
.reveal-3 { animation-delay: .30s; }
.reveal-4 { animation-delay: .44s; }

/* ── Area pill ── */
.area-pill { transition: all .2s ease; }
.area-pill:hover {
  background: rgba(242,196,206,.2);
  border-color: rgba(212,137,154,.4);
  color: var(--cp-dusty);
}

/* ── Diagonal cut ── */
.diagonal-cut { clip-path: polygon(0 0, 100% 0, 100% 88%, 0 100%); }

/* Sidebar accordion */
.sidebar-acc-content {
  max-height: 0; overflow: hidden;
  transition: max-height .35s ease;
}
.sidebar-acc-content.open { max-height: 600px; }
.sidebar-acc-btn.open .acc-chevron { transform: rotate(180deg); }
</style>

<!-- ════════════════════════════════════════
     HERO — Cinematic, tema Ivory/Rose
════════════════════════════════════════ -->
<section class="relative overflow-hidden diagonal-cut"
         style="min-height:520px; padding-top:100px; background: var(--cp-soft);">

  <!-- Foto background -->
  <?php if (!empty($category['image'])): ?>
  <div class="absolute inset-0">
    <div class="absolute inset-0 bg-cover bg-center"
         style="background-image:url('<?= e(imgUrl($category['image'], 'category')) ?>')"></div>
    <!-- Overlay: kiri pekat ivory, kanan transparan biar foto kelihatan -->
    <div class="absolute inset-0"
         style="background: linear-gradient(105deg,
           rgba(253,249,244,.97) 0%,
           rgba(253,249,244,.82) 40%,
           rgba(253,249,244,.45) 70%,
           rgba(253,249,244,.15) 100%);"></div>
  </div>
  <?php else: ?>
  <!-- Fallback: dot pattern cream -->
  <div class="absolute inset-0"
       style="background: radial-gradient(circle at 70% 50%, rgba(242,196,206,.25) 0%, transparent 65%);"></div>
  <div class="absolute inset-0 opacity-[0.06]"
       style="background-image: radial-gradient(circle, var(--cp-rose) 1px, transparent 1px);
              background-size: 36px 36px;"></div>
  <?php endif; ?>

  <!-- Blob dekorasi -->
  <div class="absolute top-0 right-0 w-96 h-96 rounded-full pointer-events-none"
       style="background:radial-gradient(circle,rgba(242,196,206,.3) 0%,transparent 70%); filter:blur(60px);"></div>

  <!-- Rose shimmer line bawah hero -->
  <div class="absolute bottom-0 left-0 right-0 rose-line" style="z-index:5;"></div>

  <!-- Breadcrumb -->
  <div class="relative z-10 max-w-7xl mx-auto px-4 pt-4 mb-10 reveal reveal-1">
    <nav class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest">
      <a href="<?= BASE_URL ?>/"
         class="transition" style="color:var(--cp-muted); opacity:.6;">
        Beranda
      </a>
      <span style="color:var(--cp-muted); opacity:.3;">—</span>
      <span style="color:var(--cp-dusty);"><?= e($category['name']) ?></span>
    </nav>
  </div>

  <!-- Konten hero -->
  <div class="relative z-10 max-w-7xl mx-auto px-4 pb-28">
    <div class="max-w-2xl">

      <!-- Overline badge -->
      <div class="reveal reveal-1 inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase mb-6"
           style="background:rgba(242,196,206,.25); border:1px solid rgba(212,137,154,.3); color:var(--cp-dusty);">
        <span class="w-1.5 h-1.5 rounded-full animate-pulse inline-block"
              style="background:var(--cp-rose);"></span>
        Florist Terpercaya Tangerang
      </div>

      <!-- Judul -->
      <h1 class="reveal reveal-2 leading-tight mb-5"
          style="font:300 clamp(2.4rem,5vw,3.8rem)/1.1 'Cormorant Garamond',serif; color:var(--cp-dark);">
        <?= e($category['name']) ?><br>
        <em style="font-style:italic; color:var(--cp-dusty);">di Tangerang</em>
      </h1>

      <!-- Deskripsi -->
      <p class="reveal reveal-3 leading-relaxed mb-8 max-w-lg"
         style="font:400 15px/1.8 'Jost',sans-serif; color:var(--cp-warm);">
        <?= !empty($category['meta_description'])
            ? e($category['meta_description'])
            : 'Toko bunga Tangerang menyediakan ' . e(strtolower($category['name'])) . ' berkualitas tinggi dengan bunga segar pilihan. Pesan sekarang, kirim cepat ke seluruh Tangerang.' ?>
      </p>

      <!-- Stats row -->
      <div class="reveal reveal-3 flex items-center gap-6 mb-8 flex-wrap">
        <div>
          <div class="stat-num text-3xl font-black"><?= $product_count ?>+</div>
          <div class="text-[10px] font-bold uppercase tracking-widest mt-0.5"
               style="color:var(--cp-muted); opacity:.7;">Produk</div>
        </div>
        <div class="w-px h-10" style="background:rgba(200,119,138,.2);"></div>
        <div>
          <div class="stat-num text-3xl font-black">2–4<span class="text-lg">Jam</span></div>
          <div class="text-[10px] font-bold uppercase tracking-widest mt-0.5"
               style="color:var(--cp-muted); opacity:.7;">Pengiriman</div>
        </div>
        <div class="w-px h-10" style="background:rgba(200,119,138,.2);"></div>
        <div>
          <div class="stat-num text-2xl font-black">
            <?= 'Rp ' . number_format($min_price/1000, 0, ',', '.') . 'rb' ?>
          </div>
          <div class="text-[10px] font-bold uppercase tracking-widest mt-0.5"
               style="color:var(--cp-muted); opacity:.7;">Mulai dari</div>
        </div>
      </div>

      <!-- CTA -->
      <div class="reveal reveal-4 flex flex-wrap gap-3">
        <a href="<?= e($wa_url) ?>?text=<?= urlencode('Halo, saya ingin memesan ' . $category['name'] . ' di Tangerang.') ?>"
           target="_blank"
           class="inline-flex items-center gap-2.5 font-bold px-7 py-3.5 rounded-full no-underline transition hover:-translate-y-1"
           style="background:linear-gradient(135deg,var(--cp-blush),var(--cp-dusty)); color:#fff;
                  box-shadow:0 6px 22px rgba(200,119,138,.35);
                  font:700 13px/1 'Jost',sans-serif; letter-spacing:.04em;">
          <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
            <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
          </svg>
          Pesan Sekarang
        </a>
        <a href="#produk"
           class="inline-flex items-center gap-2 font-semibold px-7 py-3.5 rounded-full no-underline transition hover:bg-opacity-80"
           style="border:1.5px solid rgba(200,119,138,.35); color:var(--cp-dusty);
                  font:600 13px/1 'Jost',sans-serif;">
          Lihat Produk ↓
        </a>
      </div>

    </div>
  </div>
</section>

<!-- ════════════════════════════════════════
     TICKER — kategori lain, warna rose
════════════════════════════════════════ -->
<div class="overflow-hidden py-3"
     style="background:linear-gradient(135deg,var(--cp-blush),var(--cp-dusty));">
  <div class="cat-ticker-inner">
    <?php for ($r = 0; $r < 2; $r++): ?>
    <?php foreach ($all_cats as $c): ?>
    <a href="<?= BASE_URL ?>/<?= e($c['slug']) ?>/"
       class="inline-flex items-center gap-3 mx-6 font-bold text-[11px] uppercase tracking-widest no-underline transition flex-shrink-0"
       style="color:rgba(255,255,255,.85);">
      <span style="color:rgba(255,255,255,.4);">✦</span>
      <?= e($c['name']) ?>
    </a>
    <?php endforeach; ?>
    <?php endfor; ?>
  </div>
</div>

<!-- ════════════════════════════════════════
     PRODUK GRID — light card tema ivory
════════════════════════════════════════ -->
<section id="produk" class="py-20 relative overflow-hidden"
         style="background:var(--cp-ivory);">

  <div class="absolute top-0 left-0 w-full rose-line"></div>
  <div class="absolute top-1/2 right-0 w-96 h-96 rounded-full pointer-events-none"
       style="background:radial-gradient(circle,rgba(242,196,206,.2) 0%,transparent 70%); filter:blur(60px);"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-4">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-12">
      <div>
        <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase mb-4"
             style="background:rgba(242,196,206,.3); border:1px solid rgba(212,137,154,.25); color:var(--cp-dusty);">
          <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:var(--cp-rose);"></span>
          Koleksi <?= e($category['name']) ?>
        </div>
        <h2 style="font:400 clamp(1.8rem,3vw,2.6rem)/1.2 'Cormorant Garamond',serif; color:var(--cp-dark);">
          <?= $product_count ?> Produk Tersedia
        </h2>
      </div>
      <a href="<?= e($wa_url) ?>?text=<?= urlencode('Halo, saya ingin melihat katalog ' . $category['name'] . ' lengkap.') ?>"
         target="_blank"
         class="inline-flex items-center gap-2 font-bold px-5 py-2.5 rounded-full no-underline transition hover:-translate-y-0.5 flex-shrink-0"
         style="background:linear-gradient(135deg,var(--cp-blush),var(--cp-dusty)); color:#fff;
                font:600 13px/1 'Jost',sans-serif;
                box-shadow:0 4px 16px rgba(200,119,138,.3);">
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
           style="background:#fff; border:1px solid rgba(212,137,154,.12);">
        <div class="relative overflow-hidden aspect-[3/4]">
          <img src="<?= e($img) ?>"
               alt="<?= e($prod['name']) ?> Tangerang"
               class="cat-prod-img w-full h-full object-cover" loading="lazy">

          <!-- Gradient bawah lembut selalu ada -->
          <div class="absolute inset-0 pointer-events-none"
               style="background:linear-gradient(to top,rgba(44,26,30,.7) 0%,rgba(44,26,30,.05) 45%,transparent 100%); z-index:5;"></div>

          <!-- Badge kategori -->
          <?php if (!empty($prod['cat_name'])): ?>
          <span class="absolute top-3 left-3 text-[10px] font-bold tracking-wider uppercase px-2.5 py-1 rounded-full"
                style="background:rgba(253,249,244,.92); border:1px solid rgba(212,137,154,.25);
                       color:var(--cp-dusty); backdrop-filter:blur(8px); z-index:10;">
            <?= e($prod['cat_name']) ?>
          </span>
          <?php endif; ?>

          <!-- Info nama + harga selalu terlihat di bawah -->
          <div class="absolute bottom-0 left-0 right-0 p-4 z-10">
            <h3 class="font-semibold text-white text-sm leading-tight line-clamp-2 mb-1"
                style="font-family:'Cormorant Garamond',serif;">
              <?= e($prod['name']) ?>
            </h3>
            <span class="font-bold text-sm" style="color:var(--cp-blush);">
              <?= rupiah($prod['price']) ?>
            </span>
          </div>

          <!-- Tirai kiri -->
          <div class="curtain-left rounded-l-none"></div>
          <!-- Tirai kanan -->
          <div class="curtain-right rounded-r-none"></div>

          <!-- Konten muncul setelah tirai menutup -->
          <div class="curtain-content">
            <h3 class="font-semibold text-white text-sm leading-tight line-clamp-2 mb-1"
                style="font-family:'Cormorant Garamond',serif;">
              <?= e($prod['name']) ?>
            </h3>
            <?php if (!empty($prod['description'])): ?>
            <p class="text-[11px] leading-relaxed line-clamp-2 mb-3" style="color:rgba(255,255,255,.5);">
              <?= e($prod['description']) ?>
            </p>
            <?php endif; ?>
            <div class="flex items-center justify-between gap-2">
              <span class="font-bold text-base" style="color:var(--cp-blush); font-family:'Cormorant Garamond',serif;">
                <?= rupiah($prod['price']) ?>
              </span>
              <a href="<?= e($wa_url) ?>?text=<?= $wa_prod ?>" target="_blank"
                 class="inline-flex items-center gap-1.5 font-bold text-[11px] px-3.5 py-2 rounded-full no-underline transition hover:-translate-y-0.5"
                 style="background:linear-gradient(135deg,var(--cp-blush),var(--cp-dusty)); color:#fff;
                        box-shadow:0 3px 10px rgba(200,119,138,.35);">
                <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
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
    <div class="text-center py-20">
      <div class="text-5xl mb-4">🌸</div>
      <p class="text-lg mb-6" style="color:var(--cp-muted);">Produk sedang dipersiapkan</p>
      <a href="<?= e($wa_url) ?>" target="_blank"
         class="inline-flex items-center gap-2 font-bold px-6 py-3 rounded-full no-underline"
         style="background:linear-gradient(135deg,var(--cp-blush),var(--cp-dusty)); color:#fff;">
        Tanya via WhatsApp
      </a>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- ════════════════════════════════════════
     SEO CONTENT — tema ivory/rose
════════════════════════════════════════ -->
<section class="py-20 relative overflow-hidden" style="background:var(--cp-soft);">

  <div class="absolute top-0 left-0 w-full rose-line"></div>
  <!-- Dot pattern lembut -->
  <div class="absolute inset-0 opacity-[0.04]"
       style="background-image:radial-gradient(circle,var(--cp-rose) 1px,transparent 1px);
              background-size:40px 40px;"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-4">
    <div class="grid md:grid-cols-2 gap-16 items-start">

      <!-- Kiri: konten SEO -->
      <div class="cat-prose">
        <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase mb-6"
             style="background:rgba(242,196,206,.3); border:1px solid rgba(212,137,154,.25); color:var(--cp-dusty);">
          <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:var(--cp-rose);"></span>
          Tentang Layanan
        </div>
        <h2 class="text-2xl md:text-3xl font-black mb-5 leading-tight">
          <?= e($category['name']) ?> Terbaik<br>di Tangerang
        </h2>

        <?php if (!empty($category['content'])): ?>
        <div class="leading-relaxed text-[15px] mb-6" style="color:var(--cp-muted);">
          <?= $category['content'] ?>
        </div>
        <?php endif; ?>

        <p class="text-[15px] leading-relaxed mb-8" style="color:var(--cp-muted);">
          Kami sebagai <strong>florist Tangerang</strong> terpercaya menyediakan
          <?= e(strtolower($category['name'])) ?> berkualitas tinggi dengan harga terjangkau.
          Setiap rangkaian bunga dibuat oleh tim florist profesional menggunakan bunga segar pilihan.
        </p>

        <h3 class="text-xl font-black mb-4">Mengapa Memilih Kami?</h3>
        <ul class="space-y-3 mb-8">
          <?php
          $keunggulan = [
            'Bunga 100% segar berkualitas premium',
            'Pengiriman cepat 2–4 jam ke seluruh Tangerang',
            'Harga transparan mulai ' . rupiah($min_price),
            'Desain custom sesuai keinginan Anda',
            'Melayani pesanan mendadak 24 jam',
          ];
          foreach ($keunggulan as $k): ?>
          <li class="flex items-start gap-3 text-[14px]">
            <span class="font-black mt-0.5 flex-shrink-0" style="color:var(--cp-rose);">✦</span>
            <span style="color:var(--cp-muted);"><?= $k ?></span>
          </li>
          <?php endforeach; ?>
        </ul>

        <h3 class="text-xl font-black mb-4">Cara Memesan</h3>
        <p class="text-[14px] leading-relaxed mb-6" style="color:var(--cp-muted);">
          Pemesanan sangat mudah! Hubungi kami via WhatsApp di
          <strong style="color:var(--cp-dark);"><?= e(setting('phone_display')) ?></strong> —
          informasikan jenis bunga, alamat, tanggal & jam pengiriman, dan pesan yang ingin dituliskan.
        </p>
      </div>

      <!-- Kanan: area + kategori lain + CTA -->
      <div class="space-y-5">

        <!-- Area pengiriman -->
        <div class="rounded-2xl p-6"
             style="background:#fff; border:1px solid rgba(212,137,154,.15);
                    box-shadow:0 4px 20px rgba(44,26,30,.05);">
          <div class="flex items-center gap-2 mb-4">
            <span class="text-lg">📍</span>
            <h3 style="font:700 18px/1 'Cormorant Garamond',serif; color:var(--cp-dark);">
              Area Pengiriman
            </h3>
          </div>
          <p class="text-[13px] mb-4 leading-relaxed" style="color:var(--cp-muted);">
            Kami melayani pengiriman <?= e(strtolower($category['name'])) ?>
            ke seluruh kecamatan di Tangerang:
          </p>
          <div class="flex flex-wrap gap-2">
            <?php foreach ($locations as $l): ?>
            <a href="<?= BASE_URL ?>/<?= e($l['slug']) ?>/"
               class="area-pill inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[12px] font-semibold no-underline"
               style="background:rgba(242,196,206,.15); border:1px solid rgba(212,137,154,.2); color:var(--cp-muted);">
              <span class="w-1 h-1 rounded-full flex-shrink-0 inline-block"
                    style="background:rgba(212,137,154,.5);"></span>
              <?= e($l['name']) ?>
            </a>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Layanan lain -->
        <div class="rounded-2xl p-6"
             style="background:#fff; border:1px solid rgba(212,137,154,.15);
                    box-shadow:0 4px 20px rgba(44,26,30,.05);">
          <h3 class="mb-4" style="font:700 18px/1 'Cormorant Garamond',serif; color:var(--cp-dark);">
            Layanan Lainnya
          </h3>
          <div class="space-y-1">
            <?php foreach ($all_cats as $c):
              $c_subs   = $all_cats_subs[$c['id']] ?? [];
              $has_subs = !empty($c_subs);
              $is_active = $c['id'] == $category['id']
                        || (isset($category['parent_id']) && $category['parent_id'] == $c['id']);
            ?>
            <?php if ($has_subs): ?>
            <div class="sidebar-acc-wrap">
              <button onclick="toggleSidebarAcc(this)"
                      class="sidebar-acc-btn w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition text-left"
                      style="background:<?= $is_active ? 'rgba(242,196,206,.2)' : 'transparent' ?>;
                             border:<?= $is_active ? '1px solid rgba(212,137,154,.3)' : 'none' ?>;
                             cursor:pointer;">
                <span class="text-[13px] font-medium transition"
                      style="color:<?= $is_active ? 'var(--cp-dusty)' : 'var(--cp-muted)' ?>;">
                  <?= e($c['name']) ?>
                </span>
                <svg class="acc-chevron w-3.5 h-3.5 transition-transform flex-shrink-0"
                     style="color:<?= $is_active ? 'var(--cp-dusty)' : 'rgba(140,107,114,.4)' ?>;"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>
              <div class="sidebar-acc-content <?= $is_active ? 'open' : '' ?> pl-3 ml-4 mt-1"
                   style="border-left:2px solid rgba(212,137,154,.2);">
                <a href="<?= BASE_URL ?>/<?= e($c['slug']) ?>/"
                   class="block px-3 py-1.5 text-[11px] font-bold no-underline transition"
                   style="color:var(--cp-rose); opacity:.7;">
                  Lihat semua <?= e($c['name']) ?> →
                </a>
                <?php foreach ($c_subs as $sub):
                  $is_sub = $sub['id'] == $category['id'];
                ?>
                <a href="<?= BASE_URL ?>/<?= e($sub['slug']) ?>/"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg text-[12px] no-underline transition"
                   style="color:<?= $is_sub ? 'var(--cp-dusty)' : 'var(--cp-muted)' ?>;
                          font-weight:<?= $is_sub ? '600' : '400' ?>;">
                  <span class="w-1 h-1 rounded-full flex-shrink-0 inline-block"
                        style="background:<?= $is_sub ? 'var(--cp-rose)' : 'rgba(140,107,114,.3)' ?>;"></span>
                  <?= e($sub['name']) ?>
                </a>
                <?php endforeach; ?>
              </div>
            </div>
            <?php else: ?>
            <a href="<?= BASE_URL ?>/<?= e($c['slug']) ?>/"
               class="flex items-center justify-between px-3 py-2.5 rounded-xl no-underline transition group/cat"
               style="background:<?= $is_active ? 'rgba(242,196,206,.2)' : 'transparent' ?>;
                      border:<?= $is_active ? '1px solid rgba(212,137,154,.25)' : '1px solid transparent' ?>;">
              <span class="text-[13px] font-medium transition"
                    style="color:<?= $is_active ? 'var(--cp-dusty)' : 'var(--cp-muted)' ?>;">
                <?= e($c['name']) ?>
              </span>
              <svg class="w-3.5 h-3.5 transition flex-shrink-0"
                   style="color:<?= $is_active ? 'var(--cp-dusty)' : 'rgba(140,107,114,.3)' ?>;"
                   fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </a>
            <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- CTA WA card -->
        <div class="rounded-2xl p-6 text-center relative overflow-hidden"
             style="background:linear-gradient(135deg,rgba(242,196,206,.3) 0%,rgba(212,137,154,.1) 100%);
                    border:1px solid rgba(212,137,154,.25);">
          <div class="text-4xl mb-3">💬</div>
          <p class="font-bold mb-1"
             style="font:700 18px/1 'Cormorant Garamond',serif; color:var(--cp-dark);">
            Butuh Konsultasi?
          </p>
          <p class="text-sm mb-5" style="color:var(--cp-muted);">
            Kami siap membantu 24 jam sehari
          </p>
          <a href="<?= e($wa_url) ?>" target="_blank"
             class="inline-flex items-center justify-center gap-2 font-bold px-6 py-3 rounded-full w-full no-underline transition hover:-translate-y-0.5"
             style="background:linear-gradient(135deg,var(--cp-blush),var(--cp-dusty)); color:#fff;
                    font:700 13px/1 'Jost',sans-serif;
                    box-shadow:0 4px 16px rgba(200,119,138,.3);">
            Chat WhatsApp Sekarang
          </a>
        </div>

      </div>
    </div>
  </div>
</section>

<script>
function toggleSidebarAcc(btn) {
  const content = btn.nextElementSibling;
  const isOpen  = content.classList.contains('open');
  document.querySelectorAll('.sidebar-acc-content.open').forEach(el => el.classList.remove('open'));
  document.querySelectorAll('.sidebar-acc-btn.open').forEach(el => el.classList.remove('open'));
  if (!isOpen) { btn.classList.add('open'); content.classList.add('open'); }
}

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.sidebar-acc-content.open').forEach(el => {
    el.previousElementSibling?.classList.add('open');
  });

  /* ══════════════════════════════════
     BUNGA MELAYANG SAAT HOVER / TAP
  ══════════════════════════════════ */
  const petals   = ['🌸','🌺','🌷','✿','❀','💮','🪷'];
  const isMobile = window.matchMedia('(hover: none)').matches;

  function spawnPetals(card, originX, originY) {
    const count = isMobile ? 5 : 7;
    for (let i = 0; i < count; i++) {
      const el = document.createElement('span');
      el.className  = 'card-petal';
      el.textContent = petals[Math.floor(Math.random() * petals.length)];

      /* posisi awal acak di sekitar titik sentuh / batas card */
      const spread = 60;
      const startX = originX + (Math.random() - .5) * spread;
      const startY = originY + (Math.random() - .5) * 20;
      const driftX = (Math.random() - .5) * 60;
      const rot    = (Math.random() - .5) * 60;

      el.style.cssText = `
        left: ${startX}px;
        top:  ${startY}px;
        --fx: ${driftX}px;
        --fr: ${rot}deg;
        animation-delay: ${i * 60}ms;
        animation-duration: ${750 + Math.random() * 400}ms;
        font-size: ${14 + Math.random() * 10}px;
      `;

      card.appendChild(el);
      el.addEventListener('animationend', () => el.remove());
    }
  }

  document.querySelectorAll('.cat-prod-card').forEach(card => {
    if (isMobile) {
      /* Mobile: spawn bunga saat tap di titik jari */
      card.addEventListener('touchstart', e => {
        const touch = e.touches[0];
        const rect  = card.getBoundingClientRect();
        spawnPetals(card,
          touch.clientX - rect.left,
          touch.clientY - rect.top
        );
      }, { passive: true });

    } else {
      /* Desktop: spawn bunga saat mouseenter dari tepi card */
      card.addEventListener('mouseenter', e => {
        const rect = card.getBoundingClientRect();
        spawnPetals(card,
          e.clientX - rect.left,
          e.clientY - rect.top
        );
      });
    }
  });
});
</script>

<?php require __DIR__ . '/../includes/footer.php'; ?>