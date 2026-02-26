<?php
require_once __DIR__ . '/../includes/config.php';

$meta_title    = $location['meta_title']       ?: 'Toko Bunga ' . $location['name'] . ' - Florist Tangerang Terpercaya';
$meta_desc     = $location['meta_description'] ?: '';
$meta_keywords = 'toko bunga ' . strtolower($location['name']) . ', florist ' . strtolower($location['name']) . ', bunga Tangerang';

$all_cats_raw = db()->query("SELECT * FROM categories WHERE status='active' ORDER BY urutan ASC, id ASC")->fetchAll();
$all_cats = []; $all_cats_subs = [];
foreach ($all_cats_raw as $ac) {
    $pid = $ac['parent_id'] ?? null;
    if ($pid === null || $pid == 0) $all_cats[] = $ac;
    else $all_cats_subs[$pid][] = $ac;
}

$products  = db()->query("SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.status='active' ORDER BY RAND() LIMIT 8")->fetchAll();
$locations = db()->query("SELECT * FROM locations WHERE status='active' ORDER BY id")->fetchAll();
$faqs      = db()->query("SELECT * FROM faqs WHERE status='active' ORDER BY urutan LIMIT 6")->fetchAll();
$wa_url    = setting('whatsapp_url');

$min_price = !empty($products) ? min(array_column($products, 'price')) : 300000;

require __DIR__ . '/../includes/header.php';
?>

<style>
@keyframes ticker { from{transform:translateX(0)} to{transform:translateX(-50%)} }
.loc-ticker-inner { animation:ticker 20s linear infinite; display:flex; white-space:nowrap; }

@keyframes shimmer-x { 0%{background-position:-200% center} 100%{background-position:200% center} }
.gold-line { height:1px; background:linear-gradient(90deg,transparent,#F5C518,#FFE066,#F5C518,transparent); background-size:200% auto; animation:shimmer-x 3s linear infinite; }

@keyframes fadeUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
.reveal{animation:fadeUp .6s ease both} .reveal-1{animation-delay:.1s} .reveal-2{animation-delay:.2s} .reveal-3{animation-delay:.3s} .reveal-4{animation-delay:.45s}

.stat-num { font-family:'Playfair Display',serif; background:linear-gradient(135deg,#F5C518 0%,#FFE066 50%,#F5C518 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.diagonal-cut-r { clip-path:polygon(0 0,100% 0,100% 100%,0 85%); }

/* Layanan cards */
.loc-layanan-card { transition:box-shadow .25s ease,border-color .25s ease; }
.loc-layanan-card:hover { box-shadow:0 16px 48px rgba(0,0,0,.4); border-color:rgba(245,197,24,.4) !important; }
.loc-layanan-icon { transition:transform .3s ease; }
.loc-layanan-card:hover .loc-layanan-icon { transform:scale(1.2) rotate(-5deg); }
.loc-sub-body { display:grid; grid-template-rows:0fr; transition:grid-template-rows .3s cubic-bezier(.4,0,.2,1); }
.loc-sub-body.open { grid-template-rows:1fr; }
.loc-sub-inner { overflow:hidden; }
.loc-sub-arrow { transition:transform .25s ease; flex-shrink:0; }
.loc-sub-arrow.open { transform:rotate(180deg); }

/* Product cards */
.cat-prod-overlay { transform:translateY(100%); transition:transform .38s cubic-bezier(.4,0,.2,1); }
.cat-prod-card:hover .cat-prod-overlay { transform:translateY(0); }
.cat-prod-img { transition:transform .6s cubic-bezier(.4,0,.2,1); }
.cat-prod-card:hover .cat-prod-img { transform:scale(1.08); }
.cat-prod-card { transition:box-shadow .3s ease; }
.cat-prod-card:hover { box-shadow:0 0 0 1.5px rgba(245,197,24,.5),0 24px 64px rgba(0,0,0,.5); }

/* FAQ */
.faq-new-card { position:relative; border-radius:1rem; padding:1.25rem 1.25rem 1.25rem 1.5rem; background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.07); transition:border-color .25s ease,background .25s ease; cursor:pointer; }
.faq-new-card::before { content:''; position:absolute; left:0; top:16px; bottom:16px; width:3px; border-radius:0 4px 4px 0; background:rgba(245,197,24,.2); transition:background .25s ease,top .25s ease,bottom .25s ease; }
.faq-new-card.open::before { background:#F5C518; top:0; bottom:0; }
.faq-new-card:hover { background:rgba(255,255,255,.05); border-color:rgba(245,197,24,.2); }
.faq-new-card.open { background:rgba(245,197,24,.05); border-color:rgba(245,197,24,.25); }
.faq-new-card.open .faq-new-icon { background:#F5C518; color:#0B1F4A; }
.faq-new-body { max-height:0; overflow:hidden; opacity:0; transition:max-height .4s cubic-bezier(.4,0,.2,1),opacity .3s ease; }
.faq-new-body.open { max-height:300px; opacity:1; }
.faq-new-icon { width:28px; height:28px; border-radius:8px; background:rgba(245,197,24,.1); color:#F5C518; font-size:11px; font-weight:900; font-family:'Playfair Display',serif; display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:background .2s ease,color .2s ease; }
.faq-new-chevron { transition:transform .3s cubic-bezier(.4,0,.2,1); flex-shrink:0; }
.faq-new-card.open .faq-new-chevron { transform:rotate(180deg); }

/* Area pills */
.area-pill { transition:all .2s ease; }
.area-pill:hover,.area-pill.active { background:rgba(245,197,24,.15) !important; border-color:rgba(245,197,24,.4) !important; color:#F5C518 !important; }

/* Sidebar accordion */
.sidebar-acc-content { max-height:0; overflow:hidden; transition:max-height .3s ease; }
.sidebar-acc-content.open { max-height:600px; }
.sidebar-acc-btn.open .acc-chevron { transform:rotate(180deg); }

/* Pin pulse */
@keyframes pin-pulse { 0%,100%{box-shadow:0 0 0 0 rgba(245,197,24,.4)} 50%{box-shadow:0 0 0 8px rgba(245,197,24,0)} }
.pin-dot { animation:pin-pulse 2s ease infinite; }

/* Masonry */
.layanan-masonry { columns:2; column-gap:16px; }
@media(min-width:768px) { .layanan-masonry{columns:3} }
@media(min-width:1024px) { .layanan-masonry{columns:4} }
.layanan-masonry-item { break-inside:avoid; margin-bottom:16px; display:block; }
.lm-card { position:relative; border-radius:20px; overflow:hidden; cursor:pointer; display:block; text-decoration:none; }
.lm-card.h-sm{height:200px} .lm-card.h-md{height:260px} .lm-card.h-lg{height:320px} .lm-card.h-xl{height:380px}
.lm-bg { position:absolute; inset:0; background-size:cover; background-position:center; transition:transform .7s cubic-bezier(.4,0,.2,1); }
.lm-card:hover .lm-bg { transform:scale(1.08); }
.lm-bg-fallback { position:absolute; inset:0; transition:transform .7s cubic-bezier(.4,0,.2,1); }
.lm-card:hover .lm-bg-fallback { transform:scale(1.05); }
.lm-overlay-default { position:absolute; inset:0; background:linear-gradient(to top,rgba(5,14,38,.9) 0%,rgba(5,14,38,.4) 40%,rgba(5,14,38,.1) 70%,transparent 100%); }
.lm-overlay-hover { position:absolute; inset:0; opacity:0; background:linear-gradient(160deg,rgba(245,197,24,.18) 0%,rgba(8,23,41,.85) 55%,rgba(5,14,38,.97) 100%); transition:opacity .4s ease; }
.lm-card:hover .lm-overlay-hover { opacity:1; }
.lm-bottom { position:absolute; bottom:0; left:0; right:0; padding:20px 18px 18px; z-index:10; }
.lm-detail { overflow:hidden; max-height:0; opacity:0; transform:translateY(8px); transition:max-height .45s cubic-bezier(.4,0,.2,1),opacity .35s ease .05s,transform .35s ease .05s; }
.lm-card:hover .lm-detail { max-height:200px; opacity:1; transform:translateY(0); }
.lm-name { font-family:'Playfair Display',Georgia,serif; font-weight:800; color:#fff; line-height:1.2; transition:color .25s ease; }
.lm-card:hover .lm-name { color:#F5C518; }
.lm-badge { display:inline-flex; align-items:center; gap:5px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:rgba(245,197,24,.55); margin-bottom:6px; }
.lm-gold-line { height:2px; width:0; background:linear-gradient(90deg,#F5C518,#FFE066); border-radius:2px; margin-bottom:12px; transition:width .45s cubic-bezier(.4,0,.2,1) .05s; }
.lm-card:hover .lm-gold-line { width:36px; }
.lm-sub-link { display:flex; align-items:center; gap:7px; font-size:11px; font-weight:600; color:rgba(255,255,255,.6); text-decoration:none; padding:4px 0; transition:color .15s ease; }
.lm-sub-link:hover { color:#F5C518; }
.lm-sub-dot { width:4px; height:4px; border-radius:50%; background:rgba(245,197,24,.4); flex-shrink:0; transition:background .15s ease; }
.lm-sub-link:hover .lm-sub-dot { background:#F5C518; }
.lm-chip { position:absolute; top:14px; right:14px; z-index:10; display:flex; align-items:center; gap:5px; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.07em; color:#0B1F4A; background:#F5C518; padding:4px 10px; border-radius:999px; opacity:0; transform:translateY(-4px); transition:opacity .3s ease .1s,transform .3s ease .1s; pointer-events:none; }
.lm-card:hover .lm-chip { opacity:1; transform:translateY(0); }
.lm-icon { position:absolute; top:14px; left:14px; z-index:10; font-size:22px; filter:drop-shadow(0 2px 8px rgba(0,0,0,.5)); transition:transform .3s ease; }
.lm-card:hover .lm-icon { transform:scale(1.2) rotate(-5deg); }
.lm-card::after { content:''; position:absolute; inset:0; border-radius:20px; border:1.5px solid transparent; transition:border-color .35s ease; pointer-events:none; z-index:20; }
.lm-card:hover::after { border-color:rgba(245,197,24,.35); }
.lm-grad-0{background:linear-gradient(135deg,#0d2154 0%,#162d6b 100%)} .lm-grad-1{background:linear-gradient(135deg,#071833 0%,#0e2550 100%)} .lm-grad-2{background:linear-gradient(135deg,#0c1e4a 0%,#1a3070 100%)} .lm-grad-3{background:linear-gradient(135deg,#060f28 0%,#0d1f4c 100%)}
.lm-dots { position:absolute; inset:0; opacity:.06; background-image:radial-gradient(circle,#F5C518 1px,transparent 1px); background-size:24px 24px; }
.lm-num { font-family:'Playfair Display',Georgia,serif; font-size:64px; font-weight:900; color:rgba(245,197,24,.06); line-height:1; position:absolute; bottom:-10px; right:12px; pointer-events:none; transition:color .3s ease; user-select:none; }
.lm-card:hover .lm-num { color:rgba(245,197,24,.1); }
</style>

<!-- ════════════════════════════════════════════════
     HERO
════════════════════════════════════════════════ -->
<section class="relative overflow-hidden diagonal-cut-r" style="min-height:540px; padding-top:100px; background:#081729;">
  <div class="absolute inset-0 opacity-[0.035]" style="background-image:radial-gradient(circle,#F5C518 1px,transparent 1px); background-size:36px 36px;"></div>
  <div class="absolute top-0 right-0 w-[500px] h-[500px] pointer-events-none" style="background:radial-gradient(circle,rgba(245,197,24,.08) 0%,transparent 65%); filter:blur(40px);"></div>
  <div class="absolute bottom-0 left-0 right-0 gold-line" style="z-index:5;"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-4 pt-4 mb-10 reveal reveal-1">
    <nav class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest">
      <a href="<?= BASE_URL ?>/" class="text-white/35 hover:text-[#F5C518] transition">Beranda</a>
      <span class="text-white/20">—</span>
      <a href="<?= BASE_URL ?>/#area" class="text-white/35 hover:text-[#F5C518] transition">Area Kirim</a>
      <span class="text-white/20">—</span>
      <span class="text-[#F5C518]/70"><?= e($location['name']) ?></span>
    </nav>
  </div>

  <div class="relative z-10 max-w-7xl mx-auto px-4 pb-32">
    <div class="grid md:grid-cols-2 gap-10 items-center">
      <div>
        <div class="reveal reveal-1 inline-flex items-center gap-2 bg-[#F5C518]/10 border border-[#F5C518]/25 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase text-[#F5C518] mb-6">
          <span class="pin-dot w-2 h-2 rounded-full bg-[#F5C518] inline-block flex-shrink-0"></span>
          📍 <?= e($location['name']) ?>, Tangerang
        </div>
        <h1 class="reveal reveal-2 font-serif text-4xl md:text-5xl lg:text-[56px] font-black text-white leading-tight mb-5">
          Toko Bunga<br><span style="color:#F5C518;"><?= e($location['name']) ?></span>
        </h1>
        <p class="reveal reveal-3 text-white/50 text-base md:text-lg leading-relaxed mb-8 max-w-md">
          <?= !empty($location['meta_description']) ? e($location['meta_description']) : 'Florist ' . e($location['name']) . ' terpercaya — karangan bunga papan, hand bouquet, wedding, duka cita. Pengiriman cepat 2–4 jam ke seluruh ' . e($location['name']) . '.' ?>
        </p>
        <div class="reveal reveal-3 flex flex-wrap items-center gap-6 mb-8">
          <div><div class="stat-num text-3xl font-black">10+</div><div class="text-[10px] font-bold uppercase tracking-widest text-white/35 mt-0.5">Tahun Berpengalaman</div></div>
          <div class="w-px h-10" style="background:rgba(255,255,255,.1);"></div>
          <div><div class="stat-num text-3xl font-black">2–4<span class="text-lg">Jam</span></div><div class="text-[10px] font-bold uppercase tracking-widest text-white/35 mt-0.5">Pengiriman</div></div>
          <div class="w-px h-10" style="background:rgba(255,255,255,.1);"></div>
          <div><div class="stat-num text-2xl font-black"><?= 'Rp ' . number_format($min_price/1000,0,',','.') . 'rb' ?></div><div class="text-[10px] font-bold uppercase tracking-widest text-white/35 mt-0.5">Mulai dari</div></div>
        </div>
        <div class="reveal reveal-4 flex flex-wrap gap-3">
          <a href="<?= e($wa_url) ?>?text=<?= urlencode('Halo, saya ingin memesan bunga di ' . $location['name'] . ', Tangerang.') ?>" target="_blank"
             class="inline-flex items-center gap-2.5 font-bold text-[#0B1F4A] px-7 py-3.5 rounded-full no-underline transition hover:-translate-y-1 hover:shadow-[0_12px_32px_rgba(245,197,24,.45)]"
             style="background:#F5C518;">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/></svg>
            Pesan Sekarang
          </a>
          <a href="tel:<?= e(setting('whatsapp_number')) ?>"
             class="inline-flex items-center gap-2 font-semibold text-white px-7 py-3.5 rounded-full no-underline transition hover:bg-white/10"
             style="border:1.5px solid rgba(255,255,255,.2);">
            📞 <?= e(setting('phone_display')) ?>
          </a>
        </div>
      </div>

      <div class="reveal reveal-4 hidden md:block">
        <div class="rounded-3xl p-6 relative overflow-hidden" style="background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.1);">
          <div class="absolute top-0 right-0 w-24 h-24" style="background:linear-gradient(225deg,rgba(245,197,24,.15) 0%,transparent 60%);"></div>
          <p class="text-[11px] font-bold uppercase tracking-widest text-[#F5C518]/60 mb-4">Info Pengiriman</p>
          <div class="space-y-3 mb-5">
            <div class="flex items-center gap-3 py-2.5 border-b" style="border-color:rgba(255,255,255,.07);"><span class="text-lg flex-shrink-0">📍</span><div><p class="text-[11px] text-white/35 uppercase tracking-wider">Lokasi</p><p class="text-white/80 text-sm font-semibold"><?= e($location['name']) ?>, Tangerang</p></div></div>
            <div class="flex items-center gap-3 py-2.5 border-b" style="border-color:rgba(255,255,255,.07);"><span class="text-lg flex-shrink-0">⚡</span><div><p class="text-[11px] text-white/35 uppercase tracking-wider">Estimasi Pengiriman</p><p class="text-white/80 text-sm font-semibold">2–4 Jam</p></div></div>
            <div class="flex items-center gap-3 py-2.5 border-b" style="border-color:rgba(255,255,255,.07);"><span class="text-lg flex-shrink-0">⏰</span><div><p class="text-[11px] text-white/35 uppercase tracking-wider">Jam Operasional</p><p class="text-white/80 text-sm font-semibold"><?= e(setting('jam_buka')) ?></p></div></div>
            <div class="flex items-center gap-3 py-2.5"><span class="text-lg flex-shrink-0">💐</span><div><p class="text-[11px] text-white/35 uppercase tracking-wider">Harga Mulai</p><p class="text-[#F5C518] text-sm font-black font-serif"><?= rupiah($min_price) ?></p></div></div>
          </div>
          <a href="<?= e($wa_url) ?>" target="_blank" class="flex items-center justify-center gap-2 font-bold text-[#0B1F4A] py-3 rounded-2xl no-underline transition hover:brightness-110" style="background:#F5C518;">Chat WhatsApp</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════
     TICKER
════════════════════════════════════════════════ -->
<div class="overflow-hidden py-3" style="background:#F5C518;">
  <div class="loc-ticker-inner">
    <?php for ($r = 0; $r < 2; $r++): foreach ($locations as $l): ?>
    <a href="<?= BASE_URL ?>/<?= e($l['slug']) ?>/"
       class="inline-flex items-center gap-3 mx-6 text-[#0B1F4A] font-bold text-[11px] uppercase tracking-widest no-underline hover:opacity-70 transition flex-shrink-0 <?= $l['id'] == $location['id'] ? 'opacity-100' : 'opacity-75' ?>">
      <span class="text-[#0B1F4A]/40">📍</span><?= e($l['name']) ?>
    </a>
    <?php endforeach; endfor; ?>
  </div>
</div>

<!-- ════════════════════════════════════════════════
     LAYANAN MASONRY
════════════════════════════════════════════════ -->
<section class="py-20 relative overflow-hidden" style="background:#0B1F4A;">
  <div class="absolute top-0 left-0 w-full h-px gold-line"></div>
  <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[400px] pointer-events-none" style="background:radial-gradient(ellipse,rgba(245,197,24,.05) 0%,transparent 65%); filter:blur(60px);"></div>
  <div class="absolute inset-0 opacity-[0.025]" style="background-image:radial-gradient(circle,#F5C518 1px,transparent 1px); background-size:40px 40px;"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-4">
    <div class="text-center mb-12">
      <div class="inline-flex items-center gap-2 bg-[#F5C518]/10 border border-[#F5C518]/25 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase text-[#F5C518] mb-5">
        <span class="w-1.5 h-1.5 rounded-full bg-[#F5C518] inline-block"></span>Tersedia di <?= e($location['name']) ?>
      </div>
      <h2 class="font-serif text-3xl md:text-4xl font-black text-white">Layanan Bunga di<br><span style="color:#F5C518;">Toko Bunga Tangerang <?= e($location['name']) ?></span></h2>
      <p class="text-white/40 mt-3 max-w-lg mx-auto text-[15px]">Semua kebutuhan bunga Anda tersedia dan siap dikirim ke <?= e($location['name']) ?></p>
    </div>

    <div class="layanan-masonry">
      <?php
      $heights = ['h-md','h-xl','h-lg','h-sm','h-xl','h-md','h-sm','h-lg','h-md','h-xl','h-sm','h-md'];
      $grads   = ['lm-grad-0','lm-grad-1','lm-grad-2','lm-grad-3'];
      foreach ($all_cats as $i => $cat):
        $has_img = !empty($cat['image']); $subs = $all_cats_subs[$cat['id']] ?? []; $has_sub = !empty($subs);
        $height = $heights[$i % count($heights)]; $grad = $grads[$i % count($grads)];
        $num = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
        $tag = $has_sub ? 'div' : 'a';
        $href = $has_sub ? '' : 'href="' . BASE_URL . '/' . e($cat['slug']) . '/"';
        $extra = $has_sub ? 'onclick="toggleLocSub(\'lc-' . $cat['id'] . '\', this)"' : '';
      ?>
      <div class="layanan-masonry-item">
        <<?= $tag ?> <?= $href ?> <?= $extra ?> class="lm-card <?= $height ?>">
          <?php if ($has_img): ?>
          <div class="lm-bg" style="background-image:url('<?= e(imgUrl($cat['image'], 'category')) ?>');"></div>
          <?php else: ?>
          <div class="lm-bg-fallback <?= $grad ?>">
            <div class="lm-dots"></div>
            <div style="position:absolute;top:-30px;right:-30px;width:120px;height:120px;border-radius:50%;background:radial-gradient(circle,rgba(245,197,24,.12) 0%,transparent 70%);"></div>
          </div>
          <?php endif; ?>
          <div class="lm-overlay-default"></div>
          <div class="lm-overlay-hover"></div>
          <div class="lm-num"><?= $num ?></div>
          <?php if (!empty($cat['icon'])): ?><div class="lm-icon"><?= e($cat['icon']) ?></div><?php endif; ?>
          <div class="lm-chip"><?= $has_sub ? 'Lihat Pilihan' : 'Lihat →' ?></div>
          <div class="lm-bottom">
            <?php if ($has_sub): ?>
            <div class="lm-badge"><span class="w-1 h-1 rounded-full bg-[#F5C518]/50 inline-block"></span><?= count($subs) ?> layanan tersedia</div>
            <?php endif; ?>
            <div class="lm-name <?= in_array($height, ['h-lg','h-xl']) ? 'text-xl' : 'text-base' ?>"><?= e($cat['name']) ?></div>
            <div class="lm-gold-line"></div>
            <div class="lm-detail">
              <?php if ($has_sub): ?>
              <div class="space-y-0.5 mb-3">
                <a href="<?= BASE_URL ?>/<?= e($cat['slug']) ?>/" class="lm-sub-link" onclick="event.stopPropagation()"><span class="lm-sub-dot"></span>Lihat semua <?= e($cat['name']) ?></a>
                <?php foreach (array_slice($subs, 0, 4) as $sub): ?>
                <a href="<?= BASE_URL ?>/<?= e($sub['slug']) ?>/" class="lm-sub-link" onclick="event.stopPropagation()"><span class="lm-sub-dot"></span><?= e($sub['name']) ?></a>
                <?php endforeach; ?>
                <?php if (count($subs) > 4): ?><span class="lm-sub-link" style="color:rgba(245,197,24,.4);"><span class="lm-sub-dot"></span>+<?= count($subs) - 4 ?> lainnya...</span><?php endif; ?>
              </div>
              <?php elseif (!empty($cat['description'])): ?>
              <p class="text-white/50 text-[12px] leading-relaxed mb-3 line-clamp-2"><?= e($cat['description']) ?></p>
              <?php endif; ?>
            </div>
          </div>
        </<?= $tag ?>>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════
     PRODUK
════════════════════════════════════════════════ -->
<section id="produk" class="py-20 relative overflow-hidden" style="background:#081729;">
  <div class="absolute top-0 left-0 w-full h-px gold-line"></div>
  <div class="absolute bottom-0 left-0 w-96 h-96 rounded-full pointer-events-none" style="background:radial-gradient(circle,rgba(245,197,24,.05) 0%,transparent 70%); filter:blur(60px);"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-4">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-12">
      <div>
        <div class="inline-flex items-center gap-2 bg-[#F5C518]/10 border border-[#F5C518]/25 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase text-[#F5C518] mb-4">
          <span class="w-1.5 h-1.5 rounded-full bg-[#F5C518] inline-block"></span>Produk Populer di <?= e($location['name']) ?>
        </div>
        <h2 class="font-serif text-3xl md:text-4xl font-black text-white">Bunga Favorit Pelanggan</h2>
      </div>
      <a href="<?= e($wa_url) ?>?text=<?= urlencode('Halo, saya ingin melihat katalog bunga untuk ' . $location['name'] . '.') ?>" target="_blank"
         class="inline-flex items-center gap-2 font-bold text-[#0B1F4A] px-5 py-2.5 rounded-full no-underline transition hover:brightness-110 flex-shrink-0"
         style="background:#F5C518; font-size:13px;">Lihat Semua via WA →</a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
      <?php foreach ($products as $prod):
        $img = imgUrl($prod['image'], 'product');
        $wa_prod = urlencode("Halo, saya tertarik memesan *{$prod['name']}* untuk dikirim ke {$location['name']}. Apakah tersedia?");
      ?>
      <div class="cat-prod-card group relative rounded-2xl overflow-hidden cursor-pointer" style="background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.07);">
        <div class="relative overflow-hidden aspect-[3/4]">
          <img src="<?= e($img) ?>" alt="<?= e($prod['name']) ?> <?= e($location['name']) ?>" class="cat-prod-img w-full h-full object-cover" loading="lazy">
          <div class="absolute inset-0" style="background:linear-gradient(to top,rgba(8,23,41,.95) 0%,rgba(8,23,41,.15) 55%,transparent 100%);"></div>
          <?php if (!empty($prod['cat_name'])): ?>
          <span class="absolute top-3 left-3 text-[10px] font-bold tracking-wider uppercase px-2.5 py-1 rounded-full" style="background:rgba(245,197,24,.15); border:1px solid rgba(245,197,24,.3); color:#F5C518; backdrop-filter:blur(8px);"><?= e($prod['cat_name']) ?></span>
          <?php endif; ?>
          <div class="absolute bottom-0 left-0 right-0 p-4 z-10">
            <h3 class="font-serif font-bold text-white text-sm leading-tight line-clamp-2 mb-1"><?= e($prod['name']) ?></h3>
            <span class="font-bold text-[#F5C518] text-sm"><?= rupiah($prod['price']) ?></span>
          </div>
          <div class="cat-prod-overlay absolute inset-0 z-20 flex flex-col justify-end p-4" style="background:linear-gradient(to top,rgba(8,23,41,1) 0%,rgba(8,23,41,.92) 55%,rgba(8,23,41,.5) 100%);">
            <h3 class="font-serif font-bold text-white text-sm leading-tight line-clamp-2 mb-1"><?= e($prod['name']) ?></h3>
            <?php if (!empty($prod['description'])): ?>
            <p class="text-white/50 text-[11px] leading-relaxed line-clamp-2 mb-3"><?= e($prod['description']) ?></p>
            <?php endif; ?>
            <div class="flex items-center justify-between gap-2">
              <span class="font-black text-[#F5C518] text-base font-serif"><?= rupiah($prod['price']) ?></span>
              <a href="<?= e($wa_url) ?>?text=<?= $wa_prod ?>" target="_blank"
                 class="inline-flex items-center gap-1.5 text-[#0B1F4A] font-bold text-[11px] px-3.5 py-2 rounded-full no-underline transition hover:brightness-110"
                 style="background:#F5C518;" onclick="event.stopPropagation()">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/></svg>Pesan
              </a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════
     SEO + FAQ + SIDEBAR
     KIRI : Tentang + Area Lainnya + WA Card
     KANAN: FAQ + Layanan Sidebar
════════════════════════════════════════════════ -->
<section class="py-20 relative overflow-hidden" style="background:#0B1F4A;">
  <div class="absolute top-0 left-0 w-full h-px gold-line"></div>
  <div class="absolute inset-0 opacity-[0.025]" style="background-image:radial-gradient(circle,#F5C518 1px,transparent 1px); background-size:48px 48px;"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-4">
    <div class="grid md:grid-cols-2 gap-16 items-start">

      <!-- ══════════════════════════════
           KIRI: Teks + Area Lainnya + WA Card
      ══════════════════════════════ -->
      <div class="space-y-6">

        <!-- Tentang -->
        <div>
          <div class="inline-flex items-center gap-2 bg-[#F5C518]/10 border border-[#F5C518]/25 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase text-[#F5C518] mb-6">
            <span class="w-1.5 h-1.5 rounded-full bg-[#F5C518] inline-block"></span>Tentang Kami
          </div>
          <h2 class="font-serif text-2xl md:text-3xl font-black text-white mb-5 leading-tight">
            Toko Bunga <?= e($location['name']) ?><br><span style="color:#F5C518;">Terpercaya & Berpengalaman</span>
          </h2>
          <?php if (!empty($location['content'])): ?>
          <div class="text-white/50 leading-relaxed text-[15px] mb-5"><?= $location['content'] ?></div>
          <?php endif; ?>
          <p class="text-white/45 text-[15px] leading-relaxed mb-6">
            Sebagai <strong class="text-white/80">toko bunga <?= e(strtolower($location['name'])) ?></strong> yang telah melayani pelanggan lebih dari 10 tahun, kami memahami setiap momen memerlukan rangkaian bunga yang tepat. Tim florist profesional siap membantu 24 jam.
          </p>
          <a href="<?= e($wa_url) ?>?text=<?= urlencode('Halo, saya ingin memesan bunga di ' . $location['name'] . '. Mohon info harga dan ketersediaannya.') ?>" target="_blank"
             class="inline-flex items-center gap-2.5 font-bold text-[#0B1F4A] px-7 py-3.5 rounded-full no-underline transition hover:-translate-y-1 hover:shadow-[0_12px_32px_rgba(245,197,24,.4)]"
             style="background:#F5C518;">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/></svg>
            Pesan via WhatsApp
          </a>
        </div>

        <!-- 📍 Area Lainnya -->
        <div class="rounded-2xl p-5" style="background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.07);">
          <div class="flex items-center gap-2 mb-4">
            <span class="text-[#F5C518]">📍</span>
            <h3 class="font-serif font-black text-white">Area Lainnya</h3>
          </div>
          <div class="flex flex-wrap gap-2">
            <?php foreach ($locations as $l): ?>
            <a href="<?= BASE_URL ?>/<?= e($l['slug']) ?>/"
               class="area-pill inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[12px] font-semibold no-underline transition <?= $l['id'] == $location['id'] ? 'active' : 'text-white/55' ?>"
               style="background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08); <?= $l['id'] == $location['id'] ? 'background:rgba(245,197,24,.15); border-color:rgba(245,197,24,.4); color:#F5C518;' : '' ?>">
              <span class="w-1 h-1 rounded-full <?= $l['id'] == $location['id'] ? 'bg-[#F5C518]' : 'bg-white/25' ?> flex-shrink-0 inline-block"></span>
              <?= e($l['name']) ?>
            </a>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- 💬 Siap Pesan? WA Card -->
        <div class="rounded-2xl p-6 text-center" style="background:linear-gradient(135deg,rgba(245,197,24,.12) 0%,rgba(245,197,24,.04) 100%); border:1px solid rgba(245,197,24,.2);">
          <div class="text-4xl mb-3">💬</div>
          <p class="font-serif font-bold text-white text-lg mb-1">Siap Pesan?</p>
          <p class="text-white/40 text-sm mb-5">Respon dalam hitungan menit, 24 jam</p>
          <a href="<?= e($wa_url) ?>" target="_blank"
             class="inline-flex items-center justify-center gap-2 font-bold text-[#0B1F4A] px-6 py-3 rounded-full w-full no-underline transition hover:brightness-110"
             style="background:#F5C518;">Chat WhatsApp Sekarang</a>
        </div>

      </div><!-- /kiri -->

      <!-- ══════════════════════════════
           KANAN: FAQ + Layanan Sidebar
      ══════════════════════════════ -->
      <div class="space-y-6">

        <!-- FAQ -->
        <?php if (!empty($faqs)): ?>
        <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,.025); border:1px solid rgba(255,255,255,.07);">
          <div class="px-6 pt-6 pb-4 flex items-center justify-between">
            <div>
              <p class="text-[10px] font-bold uppercase tracking-widest text-[#F5C518]/50 mb-1">Pertanyaan Umum</p>
              <h3 class="font-serif font-black text-white text-xl leading-tight">FAQ — <span style="color:#F5C518;"><?= e($location['name']) ?></span></h3>
            </div>
            <span class="text-3xl opacity-40">❓</span>
          </div>
          <div class="h-px mx-6 mb-5" style="background:linear-gradient(90deg,rgba(245,197,24,.35),transparent);"></div>
          <div class="px-5 pb-5 space-y-2.5">
            <?php foreach ($faqs as $i => $faq): ?>
            <div class="faq-new-card <?= $i === 0 ? 'open' : '' ?>" onclick="toggleFaqNew(this)">
              <div class="flex items-start gap-3">
                <div class="faq-new-icon"><?= str_pad($i+1, 2, '0', STR_PAD_LEFT) ?></div>
                <div class="flex-1 min-w-0">
                  <div class="flex items-start justify-between gap-2">
                    <p class="text-white/80 text-[13px] font-semibold leading-snug pr-1"><?= e($faq['question']) ?></p>
                    <svg class="faq-new-chevron w-4 h-4 text-white/25 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                  </div>
                  <div class="faq-new-body <?= $i === 0 ? 'open' : '' ?>">
                    <p class="text-white/45 text-[12.5px] leading-relaxed mt-3 pt-3 border-t" style="border-color:rgba(255,255,255,.07);"><?= e($faq['answer']) ?></p>
                  </div>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Layanan Sidebar -->
        <div class="rounded-2xl p-5" style="background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.07);">
          <h3 class="font-serif font-black text-white mb-4">Layanan Kami</h3>
          <div class="space-y-1">
            <?php foreach ($all_cats as $c):
              $c_subs = $all_cats_subs[$c['id']] ?? []; $has_sub = !empty($c_subs);
            ?>
            <?php if ($has_sub): ?>
            <div>
              <button onclick="toggleSidebarAcc(this)" class="sidebar-acc-btn w-full flex items-center justify-between px-3 py-2.5 rounded-xl hover:bg-white/5 transition" style="background:transparent; border:none; cursor:pointer;">
                <span class="text-[13px] font-medium text-white/55 transition text-left"><?= e($c['name']) ?></span>
                <svg class="acc-chevron w-3.5 h-3.5 text-white/25 transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
              </button>
              <div class="sidebar-acc-content pl-3 border-l border-[#F5C518]/15 ml-4 mt-1">
                <a href="<?= BASE_URL ?>/<?= e($c['slug']) ?>/" class="block px-3 py-1.5 text-[11px] font-bold text-[#F5C518]/50 hover:text-[#F5C518] no-underline transition">Lihat semua <?= e($c['name']) ?> →</a>
                <?php foreach ($c_subs as $sub): ?>
                <a href="<?= BASE_URL ?>/<?= e($sub['slug']) ?>/" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[12px] no-underline text-white/45 hover:text-[#F5C518] transition">
                  <span class="w-1 h-1 rounded-full bg-white/20 flex-shrink-0 inline-block"></span><?= e($sub['name']) ?>
                </a>
                <?php endforeach; ?>
              </div>
            </div>
            <?php else: ?>
            <a href="<?= BASE_URL ?>/<?= e($c['slug']) ?>/" class="flex items-center justify-between px-3 py-2.5 rounded-xl no-underline hover:bg-white/5 transition group/cat">
              <span class="text-[13px] font-medium text-white/55 group-hover/cat:text-[#F5C518] transition"><?= e($c['name']) ?></span>
              <svg class="w-3.5 h-3.5 text-white/20 group-hover/cat:text-[#F5C518] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>

      </div><!-- /kanan -->
    </div>
  </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>

<script>
function toggleLocSub(uid, triggerEl) {
  const body = document.getElementById(uid);
  const arrow = document.getElementById('arrow-' + uid);
  if (!body) return;
  const isOpen = body.classList.contains('open');
  document.querySelectorAll('.loc-sub-body.open').forEach(el => el.classList.remove('open'));
  document.querySelectorAll('.loc-sub-arrow.open').forEach(el => el.classList.remove('open'));
  if (!isOpen) { body.classList.add('open'); if (arrow) arrow.classList.add('open'); }
}

function toggleFaqNew(card) {
  const body = card.querySelector('.faq-new-body');
  const isOpen = card.classList.contains('open');
  document.querySelectorAll('.faq-new-card.open').forEach(c => { c.classList.remove('open'); c.querySelector('.faq-new-body').classList.remove('open'); });
  if (!isOpen) { card.classList.add('open'); body.classList.add('open'); }
}

function toggleSidebarAcc(btn) {
  const content = btn.nextElementSibling;
  const isOpen = content.classList.contains('open');
  document.querySelectorAll('.sidebar-acc-content.open').forEach(el => el.classList.remove('open'));
  document.querySelectorAll('.sidebar-acc-btn.open').forEach(el => el.classList.remove('open'));
  if (!isOpen) { btn.classList.add('open'); content.classList.add('open'); }
}
</script>