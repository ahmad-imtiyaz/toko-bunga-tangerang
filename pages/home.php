
<?php
require_once __DIR__ . '/../includes/config.php';

// SEO Meta
$meta_title    = setting('meta_title_home');
$meta_desc     = setting('meta_desc_home');
$meta_keywords = setting('meta_keywords_home');

// Data
$categories = db()->query("SELECT * FROM categories WHERE status='active' ORDER BY id")->fetchAll();
$products   = db()->query("SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.status='active' ORDER BY p.id LIMIT 8")->fetchAll();
$locations  = db()->query("SELECT * FROM locations WHERE status='active' ORDER BY id")->fetchAll();
$testimonials = db()->query("SELECT * FROM testimonials WHERE status='active' ORDER BY urutan LIMIT 6")->fetchAll();
$faqs = db()->query("SELECT * FROM faqs WHERE status = 'active' ORDER BY urutan ASC")->fetchAll();
$wa_url     = setting('whatsapp_url');
$wa_msg     = urlencode('Halo, saya ingin memesan bunga. Mohon info produk dan harga yang tersedia.');

// Category icons mapping
$cat_icons = ['🌸','🕊️','💍','💐','🌿','🎊','🎁','🌼'];

require __DIR__ . '/../includes/header.php';
?>
<!-- ============================================================
     HERO SECTION — Bento Grid
============================================================ -->
<?php
/* ================================================================
   HERO SECTION — Opsi B "Overlapping Float" (versi rectangular)
   Teks kiri & kanan, 2 gambar persegi panjang di tengah
================================================================ */
?>

<!-- ============================================================
     HERO SECTION
============================================================ -->
<?php
/* ================================================================
   HERO SECTION — Tailwind CSS Version
   Desktop: Teks kiri | Dua gambar kanan (portrait overlapping)
            Stats + Review di bawah (full width)
   Mobile : Stack vertikal semua
================================================================ */
?>

<!-- Minimal CSS tambahan: hanya untuk animasi & blob -->
<style>
  @keyframes hero-pulse {
    0%,100% { opacity:1; transform:scale(1); }
    50%      { opacity:.35; transform:scale(1.5); }
  }
  @keyframes hero-ticker {
    from { transform:translateX(0); }
    to   { transform:translateX(-33.333%); }
  }
  .hero-pulse     { animation: hero-pulse 2s infinite; }
  .ticker-track   { animation: hero-ticker 35s linear infinite; }

  .blob-gold {
    position:absolute; inset:auto; top:-160px; right:-80px;
    width:480px; height:480px; border-radius:50%; pointer-events:none;
    background:radial-gradient(circle, rgba(245,197,24,.13) 0%, transparent 70%);
    filter:blur(80px);
  }
  .blob-blue {
    position:absolute; inset:auto; bottom:-80px; left:-60px;
    width:360px; height:360px; border-radius:50%; pointer-events:none;
    background:radial-gradient(circle, rgba(30,58,138,.5) 0%, transparent 70%);
    filter:blur(80px);
  }

  .img-card       { transition: transform .4s ease; }
  .img-card:hover { transform: translateY(-6px); }
  .img-card:hover img { transform: scale(1.05); }
  .img-card img   { transition: transform .5s ease; }

  /* ── MOBILE: gambar stack, terpisah, badge pindah ke pojok atas sub ── */
  @media (max-width: 1023px) {
    .hero-images-wrap {
      position: static !important;
      height: auto !important;
      display: flex !important;
      flex-direction: column;
      gap: 16px;
      max-width: 100%;
    }
    .hero-img-main {
      position: static !important;
      width: 100% !important;
      height: 200px !important;
    }
    .hero-img-sub-wrap {
      position: relative;
      width: 72%;
      align-self: flex-end;
    }
    .hero-img-sub {
      position: static !important;
      width: 100% !important;
      height: 160px !important;
    }
    /* Badge: pojok kiri atas kotak kecil */
    .hero-badge {
      position: absolute !important;
      top: -22px !important;
      left: 14px !important;
      bottom: auto !important;
      right: auto !important;
      transform: none !important;
      width: 52px !important;
      height: 52px !important;
    }
  }
</style>

<section id="hero" class="relative overflow-hidden bg-[#0B1F4A] min-h-screen flex flex-col justify-center pt-24 pb-0">

  <!-- Ambient blobs -->
  <div class="blob-gold"></div>
  <div class="blob-blue"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-4 w-full">

    <!-- ════════════════════════════════════════════
         BARIS 1 — Teks Kiri | Gambar Kanan
    ════════════════════════════════════════════ -->
    <div class="flex flex-col lg:flex-row items-center gap-10 lg:gap-16 py-10 lg:py-16">

      <!-- ── KIRI: Headline + CTA ── -->
      <div class="flex-1 flex flex-col">

        <!-- Overline badge -->
        <div class="inline-flex items-center gap-2 bg-[#F5C518]/10 border border-[#F5C518]/25 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase text-[#F5C518] mb-6 w-fit">
          <span class="hero-pulse inline-block w-1.5 h-1.5 rounded-full bg-[#F5C518]"></span>
          Florist Terpercaya Tangerang
        </div>

        <!-- Headline -->
        <h1 class="font-serif text-4xl lg:text-5xl xl:text-6xl font-black leading-[1.1] text-white mb-5">
          <?= e(setting('hero_title')) ?>
        </h1>

        <!-- Subtitle -->
        <p class="text-[15px] leading-[1.8] text-white/60 mb-7 max-w-sm">
          <?= e(setting('hero_subtitle')) ?>
        </p>

        <!-- USP chips -->
        <div class="flex flex-wrap gap-2 mb-8">
          <span class="bg-[#F5C518]/10 border border-[#F5C518]/20 text-[#F5C518] text-[11px] font-semibold px-3.5 py-1.5 rounded-full">● Antar 2–4 Jam</span>
          <span class="bg-[#F5C518]/10 border border-[#F5C518]/20 text-[#F5C518] text-[11px] font-semibold px-3.5 py-1.5 rounded-full">● Bunga Segar</span>
          <span class="bg-[#F5C518]/10 border border-[#F5C518]/20 text-[#F5C518] text-[11px] font-semibold px-3.5 py-1.5 rounded-full">● Custom Design</span>
          <span class="bg-[#F5C518]/10 border border-[#F5C518]/20 text-[#F5C518] text-[11px] font-semibold px-3.5 py-1.5 rounded-full">● Buka 24 Jam</span>
        </div>

        <!-- CTA buttons -->
        <div class="flex flex-col sm:flex-row items-start gap-3">
          <a href="<?= e($wa_url) ?>?text=<?= $wa_msg ?>" target="_blank"
             class="inline-flex items-center gap-2.5 bg-[#F5C518] text-[#0B1F4A] font-bold text-[14px] px-6 py-3.5 rounded-full transition hover:-translate-y-1 hover:shadow-[0_12px_32px_rgba(245,197,24,.4)] no-underline">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
              <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
            </svg>
            Pesan via WhatsApp
          </a>
          <a href="#produk"
             class="inline-flex items-center gap-2 border border-white/20 text-white/70 font-semibold text-[13px] px-5 py-3.5 rounded-full transition hover:border-[#F5C518] hover:text-[#F5C518] no-underline">
            Lihat Produk ↓
          </a>
        </div>

      </div>

      <!-- ── KANAN: Dua Gambar ── -->
      <!-- Desktop: landscape atas + portrait bawah kanan overlapping -->
      <!-- Mobile : stack vertikal terpisah, badge di pojok atas kotak kecil -->
      <div class="hero-images-wrap relative shrink-0 w-full max-w-[480px] lg:max-w-[520px]"
           style="height: 420px;">

        <!-- Gambar 1 — bannersub.png: landscape, atas -->
        <div class="hero-img-main img-card absolute top-0 left-0 z-20 w-full overflow-hidden rounded-2xl border border-white/10"
             style="height: 260px; box-shadow: -4px 8px 40px rgba(0,0,0,.45);">
          <img src="<?= BASE_URL ?>/assets/images/bannersub.png" alt="Hand Bouquet Tangerang"
               class="w-full h-full object-cover block" loading="eager">
          <div class="absolute bottom-3 left-3 bg-[#0B1F4A]/90 backdrop-blur text-[#F5C518] text-[10px] font-bold tracking-wide px-3 py-1.5 rounded-full border border-[#F5C518]/25 whitespace-nowrap">
            Toko Bunga Tangerang
          </div>
        </div>

        <!-- Wrapper kotak kecil (relative di mobile agar badge bisa absolute di dalamnya) -->
        <div class="hero-img-sub-wrap lg:contents">

          <!-- Gambar 2 — bannersub.png: portrait, bawah kanan, menjorok -->
          <div class="hero-img-sub img-card overflow-hidden rounded-2xl border border-white/10 lg:absolute lg:z-30"
               style="bottom: 0; right: -20px; width: 44%; height: 210px; box-shadow: 6px 6px 40px rgba(0,0,0,.4);">
            <img src="<?= BASE_URL ?>/assets/images/1a.jpg" alt="Wedding Flower Tangerang"
                 class="w-full h-full object-cover block" loading="eager">
            <div class="absolute bottom-3 left-3 bg-[#0B1F4A]/90 backdrop-blur text-[#F5C518] text-[10px] font-bold tracking-wide px-3 py-1.5 rounded-full border border-[#F5C518]/25 whitespace-nowrap">
              Nikmati Pesona Indahnya
            </div>
          </div>

          <!-- Badge 10+ Tahun:
               Desktop → titik pertemuan kedua gambar (absolute dari .hero-images-wrap)
               Mobile  → pojok kiri atas kotak kecil (absolute dari .hero-img-sub-wrap via CSS) -->
          <div class="hero-badge absolute z-40 w-16 h-16 rounded-full bg-[#F5C518] border-4 border-[#0B1F4A] flex flex-col items-center justify-center text-center"
               style="bottom: 120px; right: calc(44% - 8px); box-shadow: 0 8px 28px rgba(245,197,24,.55);">
            <span class="font-serif text-base font-black text-[#0B1F4A] leading-none">10+</span>
            <span class="text-[8px] font-bold text-[#0B1F4A]/70 uppercase tracking-wide">Tahun</span>
          </div>

        </div>

      </div>
    </div>

    <!-- ════════════════════════════════════════════
         BARIS 2 — Stats + Review (di bawah, full width)
         Berlaku di mobile & desktop
    ════════════════════════════════════════════ -->
    <div class="border-t border-white/[0.08] pt-8 pb-10">
      <div class="flex flex-col sm:flex-row flex-wrap items-start sm:items-center gap-6 lg:gap-10">

        <!-- Mulai dari harga -->
        <div class="shrink-0">
          <p class="text-xs text-white/50 font-medium mb-0.5">Mulai dari</p>
          <p class="font-serif text-2xl lg:text-3xl font-black text-[#F5C518]">Rp 300.000</p>
        </div>

        <!-- Divider -->
        <div class="hidden sm:block w-px h-12 bg-white/10"></div>

        <!-- Stats 3 item -->
        <div class="flex items-center gap-6 lg:gap-10">
          <div class="flex flex-col">
            <span class="font-serif text-2xl lg:text-3xl font-black text-white leading-none">
              500<sup class="text-sm text-[#F5C518]">+</sup>
            </span>
            <span class="text-[10px] font-semibold uppercase tracking-widest text-white/40 mt-1">Pelanggan Puas</span>
          </div>
          <div class="w-px h-10 bg-white/10"></div>
          <div class="flex flex-col">
            <span class="font-serif text-2xl lg:text-3xl font-black text-white leading-none">
              24<sup class="text-sm text-[#F5C518]">H</sup>
            </span>
            <span class="text-[10px] font-semibold uppercase tracking-widest text-white/40 mt-1">Siap Antar</span>
          </div>
          <div class="w-px h-10 bg-white/10"></div>
          <div class="flex flex-col">
            <span class="font-serif text-2xl lg:text-3xl font-black text-white leading-none">12</span>
            <span class="text-[10px] font-semibold uppercase tracking-widest text-white/40 mt-1">Kecamatan</span>
          </div>
        </div>

        <!-- Divider -->
        <div class="hidden lg:block w-px h-12 bg-white/10"></div>
<!-- Mini Review -->
<div id="mini-review"
  class="flex items-start gap-3 bg-[#F5C518]/[0.07] border border-[#F5C518]/15 rounded-2xl px-5 py-4 flex-1 min-w-[240px] max-w-sm">

  <div>
    <div id="mini-stars" class="text-[#F5C518] text-xs mb-1.5"></div>

    <p id="mini-content"
       class="text-[12px] text-white/65 italic leading-relaxed mb-1.5">
    </p>

    <span id="mini-author"
          class="text-[11px] font-bold text-[#F5C518]"></span>
  </div>

</div>
<!-- slider script card mini -->

<script>
const testimonials = <?= json_encode($testimonials) ?>;
</script>

      </div>
    </div>

  </div>

  <!-- ── TICKER ── -->
  <div class="border-t border-[#F5C518]/[0.12] bg-[#F5C518]/[0.04] py-3 overflow-hidden shrink-0" aria-hidden="true">
    <div class="ticker-track flex w-max">
      <?php
      $tickers = ['🌸 Hand Bouquet Premium','📋 Bunga Papan Ucapan','💍 Wedding Decoration',
                  '🕊️ Duka Cita','🎓 Buket Wisuda','⚡ Pengiriman 2–4 Jam',
                  '✏️ Custom Design','💰 Mulai Rp 300.000'];
      for ($i = 0; $i < 3; $i++):
        foreach ($tickers as $t): ?>
        <span class="inline-flex items-center gap-3.5 px-8 whitespace-nowrap text-xs font-semibold text-white/40">
          <span class="text-[#F5C518] text-[10px]">✦</span>
          <?= $t ?>
        </span>
      <?php endforeach; endfor; ?>
    </div>
  </div>

</section>

<!-- ============================================================
     LAYANAN SECTION
============================================================ -->
<?php
/* ================================================================
   LAYANAN SECTION — Bento Grid Asimetris
   Konsisten dengan tema navy + gold hero section
   Desktop: grid asimetris (kartu besar + kecil)
   Mobile : 2 kolom seragam, rapi
================================================================ */

// Ambil hanya kategori INDUK
$parent_cats = array_filter($categories, fn($c) => empty($c['parent_id']) || $c['parent_id'] == 0);
$parent_cats = array_values($parent_cats);

// Ambil sub-kategori
$sub_cats = db()->query("
    SELECT * FROM categories
    WHERE parent_id IS NOT NULL AND parent_id != 0 AND status = 'active'
    ORDER BY urutan ASC, id ASC
")->fetchAll();

$subs_by_parent = [];
foreach ($sub_cats as $sc) {
    $subs_by_parent[$sc['parent_id']][] = $sc;
}

// Bento slot pattern (desktop): 0=tall-wide, 1=normal, 2=normal, 3=wide, 4=normal, 5=tall, dst
// Kita definisikan class per posisi (loop mod 6)
$bento_classes = [
    0 => 'md:col-span-2 md:row-span-2',   // BESAR: 2x2
    1 => 'md:col-span-1 md:row-span-1',   // normal
    2 => 'md:col-span-1 md:row-span-1',   // normal
    3 => 'md:col-span-1 md:row-span-1',   // normal
    4 => 'md:col-span-2 md:row-span-1',   // lebar
    5 => 'md:col-span-1 md:row-span-1',   // normal
];

$min_heights = [
    0 => 'min-h-[320px] md:min-h-[340px]',
    1 => 'min-h-[160px] md:min-h-[160px]',
    2 => 'min-h-[160px] md:min-h-[160px]',
    3 => 'min-h-[160px] md:min-h-[160px]',
    4 => 'min-h-[160px] md:min-h-[160px]',
    5 => 'min-h-[160px] md:min-h-[160px]',
];
?>

<style>
  section#produk {
  position: relative;
  z-index: 1; /* lebih rendah dari dropdown layanan */
}

section#layanan {
  position: relative;
  z-index: 2; /* lebih tinggi */
}
  /* Tambah di bagian <style> section layanan */
.bento-grid {
  overflow: visible !important;
}

.layanan-card {
  overflow: visible !important;
}
  /* Tambah di CSS layanan */
.layanan-card:has(.layanan-sub.show) {
  z-index: 300;
}
  /* Dropdown sub-kategori */
  .layanan-sub { display: none; }
  .layanan-sub.show {
    display: block;
    animation: subDropIn .18s ease;
  }
  @keyframes subDropIn {
    from { opacity:0; transform:translateY(-6px); }
    to   { opacity:1; transform:translateY(0); }
  }
  .sub-arrow { transition: transform .2s; }
  .sub-arrow.open { transform: rotate(180deg); }

  /* Pastikan grid overflow visible untuk dropdown */
  #layanan .bento-grid { overflow: visible; }
</style>

<!-- ============================================================
     LAYANAN SECTION
============================================================ -->
<section id="layanan" class="py-20 bg-[#0B1F4A] relative overflow-visible">

  <!-- Dekorasi background subtle -->
  <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#F5C518]/30 to-transparent"></div>
  <div class="absolute inset-0 opacity-[0.03]"
       style="background-image: radial-gradient(circle, #F5C518 1px, transparent 1px); background-size: 40px 40px;"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-4">

    <!-- Header -->
    <div class="text-center mb-14">
      <div class="inline-flex items-center gap-2 bg-[#F5C518]/10 border border-[#F5C518]/25 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase text-[#F5C518] mb-5">
        <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#F5C518]"></span>
        Apa yang Kami Tawarkan
      </div>
      <h2 class="font-serif text-3xl md:text-4xl font-black text-white mt-2 mb-4">Layanan Kami</h2>
      <p class="text-white/50 max-w-xl mx-auto text-[15px] leading-relaxed">
        Kami menyediakan berbagai jenis rangkaian bunga segar berkualitas tinggi untuk setiap momen spesial Anda di Tangerang.
      </p>
    </div>

    <!-- ── BENTO GRID ── -->
    <div class="bento-grid grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-5 auto-rows-auto">

      <?php foreach ($parent_cats as $i => $cat):
        $slot      = $i % 6;
        $has_img   = !empty($cat['image']);
        $img_url   = $has_img ? e(imgUrl($cat['image'], 'category')) : '';
        $children  = $subs_by_parent[$cat['id']] ?? [];
        $has_subs  = !empty($children);

        // Warna fallback kalau tidak ada gambar (gold-tinted)
        $fallback_bg = [
          'rgba(245,197,24,.08)',
          'rgba(255,255,255,.04)',
          'rgba(245,197,24,.06)',
          'rgba(255,255,255,.03)',
          'rgba(245,197,24,.10)',
          'rgba(255,255,255,.05)',
        ];
        $bg = $fallback_bg[$i % count($fallback_bg)];
      ?>

      <!-- Kartu <?= $i ?> — slot <?= $slot ?> -->
      <div class="layanan-card group relative rounded-2xl overflow-visible <?= $bento_classes[$slot] ?> <?= $min_heights[$slot] ?> transition-all duration-300 cursor-pointer"
           style="<?= $has_img ? '' : "background: $bg;" ?> border: 1px solid rgba(255,255,255,.08);"
           <?= $has_subs ? 'onclick="toggleLayananSub(this)"' : '' ?>>

        <!-- Background gambar -->
        <?php if ($has_img): ?>
        <div class="absolute inset-0 overflow-hidden rounded-2xl">
          <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110"
               style="background-image: url('<?= $img_url ?>')"></div>
          <!-- Overlay gradient gold-navy -->
          <div class="absolute inset-0 rounded-2xl transition-all duration-300"
               style="background: linear-gradient(160deg, rgba(11,31,74,.25) 0%, rgba(11,31,74,.75) 100%);"></div>
          <div class="absolute inset-0 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"
               style="background: linear-gradient(160deg, rgba(245,197,24,.15) 0%, rgba(11,31,74,.8) 100%);"></div>
        </div>
        <?php endif; ?>

        <!-- Aksen garis gold di sudut kiri atas -->
        <div class="absolute top-0 left-0 w-8 h-8 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-20">
          <div class="absolute top-3 left-3 w-4 h-[2px] bg-[#F5C518]"></div>
          <div class="absolute top-3 left-3 w-[2px] h-4 bg-[#F5C518]"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 p-5 md:p-6 flex flex-col justify-end h-full" style="min-height: inherit;">

          <!-- Icon -->
          <?php if (!empty($cat['icon'])): ?>
          <div class="text-2xl md:text-3xl mb-3 transition-transform duration-300 group-hover:-translate-y-1 w-fit">
            <?= e($cat['icon']) ?>
          </div>
          <?php endif; ?>

          <!-- Nama layanan -->
          <h3 class="font-serif font-bold text-base md:<?= $slot === 0 ? 'text-2xl' : 'text-lg' ?> leading-tight mb-2
                     <?= $has_img ? 'text-white' : 'text-white/90' ?>">
            <?= e($cat['name']) ?>
          </h3>

          <!-- Deskripsi singkat (hanya kartu besar / slot 0) -->
          <?php if ($slot === 0 && !empty($cat['description'])): ?>
          <p class="text-white/60 text-[13px] leading-relaxed mb-3 max-w-xs line-clamp-2">
            <?= e($cat['description']) ?>
          </p>
          <?php endif; ?>

          <!-- CTA -->
          <?php if ($has_subs): ?>
          <div class="inline-flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-[#F5C518] mt-1 transition-all duration-200 opacity-60 group-hover:opacity-100">
            Lihat kategori
            <svg class="sub-arrow w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
            </svg>
          </div>
          <?php else: ?>
          <a href="<?= BASE_URL ?>/<?= e($cat['slug']) ?>/"
             class="inline-flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-[#F5C518] mt-1 transition-all duration-200 opacity-60 group-hover:opacity-100 no-underline"
             onclick="event.stopPropagation()">
            Lihat selengkapnya
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
          </a>
          <?php endif; ?>
        </div>

        <!-- ── Sub-kategori dropdown ── -->
        <?php if ($has_subs): ?>
        <div class="layanan-sub absolute left-0 right-0 top-full mt-2 rounded-2xl z-[999] p-3 text-left"
             style="background: #0f2860; border: 1px solid rgba(245,197,24,.2); box-shadow: 0 20px 60px rgba(0,0,0,.5);"
             onclick="event.stopPropagation()">

          <p class="text-[10px] text-[#F5C518]/60 font-bold uppercase tracking-widest px-2 mb-2">
            Pilih kategori <?= e($cat['name']) ?>
          </p>

          <?php foreach ($children as $ch): ?>
          <a href="<?= BASE_URL ?>/<?= e($ch['slug']) ?>/"
             class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition text-sm font-medium text-white/75 hover:text-[#F5C518] hover:bg-[#F5C518]/08 no-underline"
             style="cursor:pointer;">
            <span class="w-1.5 h-1.5 rounded-full bg-[#F5C518]/40 flex-shrink-0"></span>
            <?= e($ch['name']) ?>
          </a>
          <?php endforeach; ?>

          <div class="border-t mt-2 pt-2" style="border-color: rgba(255,255,255,.06);">
            <a href="<?= BASE_URL ?>/<?= e($cat['slug']) ?>/"
               class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold text-[#F5C518]/50 hover:text-[#F5C518] transition no-underline">
              Lihat semua <?= e($cat['name']) ?> →
            </a>
          </div>
        </div>
        <?php endif; ?>

      </div><!-- /layanan-card -->
      <?php endforeach; ?>

    </div><!-- /bento-grid -->

  </div>
</section>

<script>
function toggleLayananSub(card) {
  const sub = card.querySelector('.layanan-sub');
  if (!sub) return;

  const isOpen = sub.classList.contains('show');

  // Tutup semua
  document.querySelectorAll('.layanan-sub.show').forEach(el => el.classList.remove('show'));
  document.querySelectorAll('.sub-arrow.open').forEach(el => el.classList.remove('open'));

  if (!isOpen) {
    sub.classList.add('show');
    card.querySelector('.sub-arrow')?.classList.add('open');
  }
}

// Tutup saat klik luar
document.addEventListener('click', function(e) {
  if (!e.target.closest('.layanan-card')) {
    document.querySelectorAll('.layanan-sub.show').forEach(el => el.classList.remove('show'));
    document.querySelectorAll('.sub-arrow.open').forEach(el => el.classList.remove('open'));
  }
});
</script>

<!-- ============================================================
     PRODUK SECTION
============================================================ -->
<?php
/* ================================================================
   PRODUK SECTION — Dark Card + Hover Reveal
   Tema: navy + gold, konsisten dengan hero & layanan
   Desktop: grid 4 kolom
   Mobile : grid 2 kolom
================================================================ */
?>

<style>
  /* Slide-up overlay saat hover */
  .prod-overlay {
    transform: translateY(100%);
    transition: transform .35s cubic-bezier(.4,0,.2,1);
  }
  .prod-card:hover .prod-overlay {
    transform: translateY(0);
  }

  /* Zoom gambar saat hover */
  .prod-img {
    transition: transform .6s cubic-bezier(.4,0,.2,1);
  }
  .prod-card:hover .prod-img {
    transform: scale(1.08);
  }

  /* Shimmer gold di border card saat hover */
  .prod-card {
    transition: box-shadow .3s ease, border-color .3s ease;
  }
  .prod-card:hover {
    box-shadow: 0 0 0 1.5px rgba(245,197,24,.45), 0 20px 60px rgba(0,0,0,.5);
  }
</style>

<!-- ============================================================
     PRODUK SECTION
============================================================ -->
<section id="produk" class="py-20 relative overflow-hidden"
         style="background: #081729;">

  <!-- Dekorasi top line -->
  <div class="absolute top-0 left-0 w-full h-px"
       style="background: linear-gradient(90deg, transparent, rgba(245,197,24,.3), transparent);"></div>

  <!-- Glow accent -->
  <div class="absolute bottom-0 right-0 w-96 h-96 rounded-full pointer-events-none"
       style="background: radial-gradient(circle, rgba(245,197,24,.06) 0%, transparent 70%); filter: blur(60px);"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-4">

    <!-- ── Header ── -->
    <div class="text-center mb-14">
      <div class="inline-flex items-center gap-2 bg-[#F5C518]/10 border border-[#F5C518]/25 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase text-[#F5C518] mb-5">
        <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#F5C518]"></span>
        Koleksi Terbaik Kami
      </div>
      <h2 class="font-serif text-3xl md:text-4xl font-black text-white mt-2 mb-4">
        Produk Unggulan
      </h2>
      <p class="text-white/45 max-w-xl mx-auto text-[15px] leading-relaxed">
        Setiap rangkaian bunga dibuat dengan penuh cinta menggunakan bunga segar pilihan terbaik.
      </p>
    </div>

    <!-- ── Grid Produk ── -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
      <?php foreach ($products as $prod):
        $img     = imgUrl($prod['image'], 'product');
        $wa_prod = urlencode("Halo, saya tertarik memesan *{$prod['name']}* seharga " . rupiah($prod['price']) . ". Apakah masih tersedia?");
      ?>

      <div class="prod-card group relative rounded-2xl overflow-hidden cursor-pointer"
           style="background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.07);">

        <!-- Gambar -->
        <div class="relative overflow-hidden aspect-[3/4]">
          <img src="<?= e($img) ?>"
               alt="<?= e($prod['name']) ?> Tangerang"
               class="prod-img w-full h-full object-cover"
               loading="lazy">

          <!-- Gradient gelap permanen di bawah gambar -->
          <div class="absolute inset-0"
               style="background: linear-gradient(to top, rgba(8,23,41,.95) 0%, rgba(8,23,41,.2) 50%, transparent 100%);"></div>

          <!-- Badge kategori -->
          <?php if (!empty($prod['cat_name'])): ?>
          <span class="absolute top-3 left-3 text-[10px] font-bold tracking-wider uppercase px-2.5 py-1 rounded-full"
                style="background: rgba(245,197,24,.15); border: 1px solid rgba(245,197,24,.3); color: #F5C518; backdrop-filter: blur(8px);">
            <?= e($prod['cat_name']) ?>
          </span>
          <?php endif; ?>

          <!-- Info nama + harga (selalu terlihat di bawah gambar) -->
          <div class="absolute bottom-0 left-0 right-0 p-4 z-10">
            <h3 class="font-serif font-bold text-white text-sm md:text-base leading-tight line-clamp-2 mb-1">
              <?= e($prod['name']) ?>
            </h3>
            <span class="font-bold text-[#F5C518] text-sm">
              <?= rupiah($prod['price']) ?>
            </span>
          </div>

          <!-- ── HOVER OVERLAY: slide up dari bawah ── -->
          <div class="prod-overlay absolute inset-0 z-20 flex flex-col justify-end p-4"
               style="background: linear-gradient(to top, rgba(8,23,41,1) 0%, rgba(8,23,41,.92) 60%, rgba(8,23,41,.5) 100%);">

            <!-- Nama -->
            <h3 class="font-serif font-bold text-white text-sm md:text-base leading-tight line-clamp-2 mb-1">
              <?= e($prod['name']) ?>
            </h3>

            <!-- Deskripsi singkat -->
            <?php if (!empty($prod['description'])): ?>
            <p class="text-white/55 text-[11px] leading-relaxed line-clamp-2 mb-3">
              <?= e($prod['description']) ?>
            </p>
            <?php endif; ?>

            <!-- Harga + Tombol -->
            <div class="flex items-center justify-between gap-2">
              <span class="font-black text-[#F5C518] text-base font-serif">
                <?= rupiah($prod['price']) ?>
              </span>
              <a href="<?= e($wa_url) ?>?text=<?= $wa_prod ?>" target="_blank"
                 class="inline-flex items-center gap-1.5 text-[#0B1F4A] font-bold text-[11px] px-3.5 py-2 rounded-full no-underline transition hover:brightness-110"
                 style="background: #F5C518;"
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

    <!-- ── CTA Bawah ── -->
    <div class="text-center mt-12">
      <a href="<?= e($wa_url) ?>?text=<?= urlencode('Halo, saya ingin melihat katalog bunga lengkap Toko Bunga Tangerang.') ?>"
         target="_blank"
         class="inline-flex items-center gap-2.5 font-bold text-[#0B1F4A] px-8 py-3.5 rounded-full no-underline transition hover:-translate-y-1 hover:shadow-[0_12px_32px_rgba(245,197,24,.4)]"
         style="background: #F5C518;">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
          <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
          <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
        </svg>
        Lihat Semua Produk via WhatsApp
      </a>
    </div>

  </div>
</section>
<?php
/* ================================================================
   KEUNGGULAN SECTION — Diagonal Slash Photo Grid
   Foto dipotong diagonal berselang-seling, tema navy + gold
   Desktop: gambar kiri | konten kanan
   Mobile : stack vertikal
================================================================ */
?>

<style>
  /* ── Diagonal clip-path untuk tiap foto ── */

  /* Foto 1: miring kanan atas ke kiri bawah */
  .slash-1 {
    clip-path: polygon(0 0, 88% 0, 100% 100%, 0 100%);
  }
  /* Foto 2: kebalikan — miring kiri atas ke kanan bawah */
  .slash-2 {
    clip-path: polygon(12% 0, 100% 0, 100% 100%, 0 100%);
  }
  /* Foto 3: sama dengan slash-1 */
  .slash-3 {
    clip-path: polygon(0 0, 88% 0, 100% 100%, 0 100%);
  }
  /* Foto 4: sama dengan slash-2 */
  .slash-4 {
    clip-path: polygon(12% 0, 100% 0, 100% 100%, 0 100%);
  }

  /* Hover: sedikit zoom + brightness */
  .slash-img {
    transition: transform .6s cubic-bezier(.4,0,.2,1), filter .4s ease;
  }
  .slash-wrap:hover .slash-img {
    transform: scale(1.07);
    filter: brightness(1.1);
  }

  /* Gold line aksen di tiap foto */
  .slash-wrap::after {
    content: '';
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity .3s ease;
    background: linear-gradient(135deg, rgba(245,197,24,.18) 0%, transparent 60%);
    pointer-events: none;
  }
  .slash-wrap:hover::after {
    opacity: 1;
  }
</style>

<!-- ============================================================
     KEUNGGULAN SECTION
============================================================ -->
<section id="tentang" class="py-20 relative overflow-hidden"
         style="background: #0B1F4A;">

  <!-- Dekorasi top line -->
  <div class="absolute top-0 left-0 w-full h-px"
       style="background: linear-gradient(90deg, transparent, rgba(245,197,24,.3), transparent);"></div>

  <!-- Glow kiri -->
  <div class="absolute top-1/2 left-0 -translate-y-1/2 w-80 h-80 rounded-full pointer-events-none"
       style="background: radial-gradient(circle, rgba(245,197,24,.07) 0%, transparent 70%); filter: blur(60px);"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-4">
    <div class="grid md:grid-cols-2 gap-12 lg:gap-20 items-center">

      <!-- ══════════════════════════════════
           KIRI — Diagonal Photo Grid
      ══════════════════════════════════ -->
      <div class="grid grid-cols-2 gap-3 md:gap-4">

        <!-- Foto 1 — slash kanan, offset atas -->
        <div class="slash-wrap relative overflow-hidden rounded-2xl shadow-2xl group"
             style="aspect-ratio: 4/5;">
          <img src="<?= BASE_URL ?>/assets/images/biru 1.jpg"
               class="slash-img slash-1 w-full h-full object-cover"
               alt="Buket bunga Tangerang" loading="lazy">
          <div class="slash-1 absolute inset-0"
               style="background: linear-gradient(to top, rgba(8,23,41,.6) 0%, transparent 50%);"></div>
        </div>

        <!-- Foto 2 — slash kiri, turun -->
        <div class="slash-wrap relative overflow-hidden rounded-2xl shadow-2xl mt-8 md:mt-10 group"
             style="aspect-ratio: 4/5;">
          <img src="<?= BASE_URL ?>/assets/images/biru 2.jpg"
               class="slash-img slash-2 w-full h-full object-cover"
               alt="Bunga pernikahan Tangerang" loading="lazy">
          <div class="slash-2 absolute inset-0"
               style="background: linear-gradient(to top, rgba(8,23,41,.6) 0%, transparent 50%);"></div>
        </div>

        <!-- Foto 3 — slash kanan, naik -->
        <div class="slash-wrap relative overflow-hidden rounded-2xl shadow-2xl -mt-8 md:-mt-10 group"
             style="aspect-ratio: 4/5;">
          <img src="<?= BASE_URL ?>/assets/images/biru 3.jpg"
               class="slash-img slash-3 w-full h-full object-cover"
               alt="Rangkaian bunga segar" loading="lazy">
          <div class="slash-3 absolute inset-0"
               style="background: linear-gradient(to top, rgba(8,23,41,.6) 0%, transparent 50%);"></div>
        </div>

        <!-- Foto 4 — slash kiri, normal -->
        <div class="slash-wrap relative overflow-hidden rounded-2xl shadow-2xl group"
             style="aspect-ratio: 4/5;">
          <img src="<?= BASE_URL ?>/assets/images/biru 4.jpg"
               class="slash-img slash-4 w-full h-full object-cover"
               alt="Toko bunga Tangerang" loading="lazy">
          <div class="slash-4 absolute inset-0"
               style="background: linear-gradient(to top, rgba(8,23,41,.6) 0%, transparent 50%);"></div>
        </div>

        <!-- Badge stats mengambang di antara foto -->
        <div class="absolute hidden md:flex flex-col items-center justify-center w-20 h-20 rounded-full z-20"
             style="
               left: 50%; top: 50%;
               transform: translate(-140%, -50%);
               background: #F5C518;
               border: 4px solid #0B1F4A;
               box-shadow: 0 8px 32px rgba(245,197,24,.5);
             ">
          <span class="font-serif text-lg font-black text-[#0B1F4A] leading-none">10+</span>
          <span class="text-[8px] font-bold text-[#0B1F4A]/70 uppercase tracking-wide">Tahun</span>
        </div>

      </div>

      <!-- ══════════════════════════════════
           KANAN — Konten Keunggulan
      ══════════════════════════════════ -->
      <div>

        <!-- Overline -->
        <div class="inline-flex items-center gap-2 bg-[#F5C518]/10 border border-[#F5C518]/25 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase text-[#F5C518] mb-6">
          <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#F5C518]"></span>
          Kenapa Pilih Kami?
        </div>

        <h2 class="font-serif text-3xl md:text-4xl font-black text-white mt-2 mb-5 leading-tight">
          Florist Terpercaya<br>di Tangerang
        </h2>

        <p class="text-white/55 leading-relaxed mb-10 text-[15px]">
          <?= e(setting('about_text')) ?>
        </p>

        <!-- Feature list -->
        <div class="space-y-5">
          <?php
          $features = [
            ['icon'=>'🌺','title'=>'Bunga 100% Segar',
             'desc'=>'Kami hanya menggunakan bunga segar yang dipilih setiap hari dari pasar bunga terbaik.'],
            ['icon'=>'⚡','title'=>'Pengiriman Cepat 2–4 Jam',
             'desc'=>'Armada pengiriman kami siap mengantar ke seluruh Tangerang dengan cepat dan aman.'],
            ['icon'=>'✏️','title'=>'Desain Custom',
             'desc'=>'Tim florist kami siap membuat rangkaian sesuai keinginan dan budget Anda.'],
            ['icon'=>'💰','title'=>'Harga Terjangkau',
             'desc'=>'Harga mulai Rp 300.000 dengan kualitas premium yang tidak mengecewakan.'],
            ['icon'=>'🕐','title'=>'Layanan 24/7',
             'desc'=>'Kami siap menerima pesanan kapan saja, termasuk malam hari dan hari libur.'],
          ];
          foreach ($features as $idx => $f): ?>

          <div class="flex gap-4 group/feat">
            <!-- Icon box -->
            <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0 transition-all duration-300 group-hover/feat:scale-110"
                 style="background: rgba(245,197,24,.1); border: 1px solid rgba(245,197,24,.2);">
              <?= $f['icon'] ?>
            </div>
            <!-- Teks -->
            <div class="pt-0.5">
              <div class="font-bold text-white text-sm mb-0.5 transition-colors duration-200 group-hover/feat:text-[#F5C518]">
                <?= $f['title'] ?>
              </div>
              <div class="text-white/45 text-[13px] leading-relaxed">
                <?= $f['desc'] ?>
              </div>
            </div>
          </div>

          <?php if ($idx < count($features) - 1): ?>
          <!-- Divider tipis -->
          <div style="height:1px; background: rgba(255,255,255,.06); margin-left: 56px;"></div>
          <?php endif; ?>

          <?php endforeach; ?>
        </div>

        <!-- CTA -->
        <div class="mt-10">
          <a href="<?= e($wa_url) ?>?text=<?= urlencode('Halo, saya ingin konsultasi tentang pesanan bunga.') ?>"
             target="_blank"
             class="inline-flex items-center gap-2.5 font-bold text-[#0B1F4A] px-7 py-3.5 rounded-full no-underline transition hover:-translate-y-1 hover:shadow-[0_12px_32px_rgba(245,197,24,.4)]"
             style="background: #F5C518;">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
              <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
            </svg>
            Konsultasi Gratis
          </a>
        </div>

      </div>
    </div>
  </div>
</section>

<!-- ============================================================
     AREA PENGIRIMAN SECTION
============================================================ -->
<?php
/* ================================================================
   AREA PENGIRIMAN SECTION — Abstract Pin Map + Card List
   Pendekatan 2: visual peta abstrak atas + kartu lokasi bawah
   Responsif mobile & desktop
================================================================ */
?>

<style>
  /* ── Pin pulse animation ── */
  @keyframes pin-pulse {
    0%   { transform: scale(1);   opacity: .8; }
    50%  { transform: scale(1.5); opacity: 0;  }
    100% { transform: scale(1);   opacity: 0;  }
  }
  @keyframes pin-float {
    0%,100% { transform: translateY(0); }
    50%      { transform: translateY(-5px); }
  }

  .pin-ring {
    animation: pin-pulse 2s ease-out infinite;
  }
  .pin-dot {
    animation: pin-float 3s ease-in-out infinite;
  }

  /* Delay tiap pin beda-beda biar ga seragam */
  .pin-wrap:nth-child(1) .pin-ring { animation-delay: 0s; }
  .pin-wrap:nth-child(2) .pin-ring { animation-delay: .4s; }
  .pin-wrap:nth-child(3) .pin-ring { animation-delay: .8s; }
  .pin-wrap:nth-child(4) .pin-ring { animation-delay: 1.2s; }
  .pin-wrap:nth-child(5) .pin-ring { animation-delay: 1.6s; }
  .pin-wrap:nth-child(6) .pin-ring { animation-delay: 0.2s; }
  .pin-wrap:nth-child(1) .pin-dot  { animation-delay: 0s; }
  .pin-wrap:nth-child(2) .pin-dot  { animation-delay: .5s; }
  .pin-wrap:nth-child(3) .pin-dot  { animation-delay: 1s; }
  .pin-wrap:nth-child(4) .pin-dot  { animation-delay: 1.5s; }
  .pin-wrap:nth-child(5) .pin-dot  { animation-delay: .3s; }
  .pin-wrap:nth-child(6) .pin-dot  { animation-delay: .7s; }

  /* ── Garis koneksi antar pin (SVG) ── */
  .map-line {
    stroke-dasharray: 6 4;
    animation: dash-move 2s linear infinite;
  }
  @keyframes dash-move {
    to { stroke-dashoffset: -20; }
  }

  /* ── Card area hover ── */
  .area-card {
    transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
  }
  .area-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 48px rgba(0,0,0,.4);
    border-color: rgba(245,197,24,.4) !important;
  }
  .area-card:hover .area-pin-icon {
    background: #F5C518 !important;
    color: #0B1F4A !important;
  }
</style>

<!-- ============================================================
     AREA PENGIRIMAN SECTION
============================================================ -->
<section id="area" class="py-20 relative overflow-hidden"
         style="background: #081729;">

  <!-- Dekorasi top line -->
  <div class="absolute top-0 left-0 w-full h-px"
       style="background: linear-gradient(90deg, transparent, rgba(245,197,24,.3), transparent);"></div>

  <!-- Background glow -->
  <div class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full pointer-events-none"
       style="background: radial-gradient(circle, rgba(245,197,24,.05) 0%, transparent 65%); filter: blur(40px);"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-4">

    <!-- ── Header ── -->
    <div class="text-center mb-12">
      <div class="inline-flex items-center gap-2 bg-[#F5C518]/10 border border-[#F5C518]/25 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase text-[#F5C518] mb-5">
        <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#F5C518]"></span>
        Jangkauan Layanan
      </div>
      <h2 class="font-serif text-3xl md:text-4xl font-black text-white mt-2 mb-4">
        Area Pengiriman Tangerang
      </h2>
      <p class="text-white/45 max-w-xl mx-auto text-[15px] leading-relaxed">
        Kami melayani pengiriman bunga ke seluruh kecamatan di Tangerang dengan cepat dan terpercaya.
      </p>
    </div>

    <!-- ════════════════════════════════════
         VISUAL PETA ABSTRAK
    ════════════════════════════════════ -->
    <div class="relative w-full rounded-3xl mb-12 overflow-hidden"
         style="height: 280px; background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.07);">

      <!-- Grid lines latar (efek peta) -->
      <svg class="absolute inset-0 w-full h-full opacity-10" xmlns="http://www.w3.org/2000/svg">
        <defs>
          <pattern id="map-grid" width="40" height="40" patternUnits="userSpaceOnUse">
            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(245,197,24,.4)" stroke-width=".5"/>
          </pattern>
        </defs>
        <rect width="100%" height="100%" fill="url(#map-grid)"/>
      </svg>

      <!-- Garis koneksi antar pin (SVG overlay) -->
      <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg" style="overflow:visible;">
        <!-- Garis-garis koneksi — posisi disesuaikan dengan pin di bawah -->
        <line class="map-line" x1="14%" y1="42%" x2="34%" y2="62%" stroke="rgba(245,197,24,.25)" stroke-width="1.5"/>
        <line class="map-line" x1="34%" y1="62%" x2="52%" y2="38%" stroke="rgba(245,197,24,.25)" stroke-width="1.5"/>
        <line class="map-line" x1="52%" y1="38%" x2="70%" y2="65%" stroke="rgba(245,197,24,.25)" stroke-width="1.5"/>
        <line class="map-line" x1="34%" y1="62%" x2="20%" y2="76%" stroke="rgba(245,197,24,.15)" stroke-width="1"/>
        <line class="map-line" x1="52%" y1="38%" x2="78%" y2="48%" stroke="rgba(245,197,24,.15)" stroke-width="1"/>
      </svg>

      <!-- Pin-pin lokasi -->
      <!-- Posisi pakai % agar responsif -->
      <?php
      // Posisi pin di peta abstrak (% dari kiri & atas)
      // Top mulai 40%+ agar tidak nabrak badge "Antar 2-4 Jam" di pojok kanan atas
      $pin_positions = [
        ['left'=>'14%',  'top'=>'42%'],
        ['left'=>'34%',  'top'=>'62%'],
        ['left'=>'52%',  'top'=>'38%'],
        ['left'=>'70%',  'top'=>'65%'],
        ['left'=>'20%',  'top'=>'76%'],
        ['left'=>'78%',  'top'=>'48%'],
      ];
      foreach ($locations as $idx => $loc):
        $pos = $pin_positions[$idx % count($pin_positions)];
      ?>
      <div class="pin-wrap absolute z-10 flex flex-col items-center"
           style="left: <?= $pos['left'] ?>; top: <?= $pos['top'] ?>; transform: translate(-50%,-100%);">

        <!-- Pin icon + float -->
        <div class="pin-dot relative flex flex-col items-center cursor-pointer group/pin">

          <!-- Tooltip nama lokasi -->
          <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 whitespace-nowrap
                      bg-[#0B1F4A] border border-[#F5C518]/30 text-white text-[10px] font-bold
                      px-2.5 py-1 rounded-full opacity-0 group-hover/pin:opacity-100 transition-opacity duration-200 z-20
                      pointer-events-none">
            <?= e($loc['name']) ?>
          </div>

          <!-- Icon pin -->
          <div class="w-8 h-8 rounded-full flex items-center justify-center shadow-lg relative z-10"
               style="background: #F5C518; border: 2px solid #0B1F4A;">
            <svg class="w-3.5 h-3.5" fill="#0B1F4A" viewBox="0 0 24 24">
              <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
            </svg>
          </div>

          <!-- Tangkai pin -->
          <div class="w-0.5 h-3" style="background: #F5C518; opacity: .6;"></div>
        </div>

        <!-- Pulse ring -->
        <div class="pin-ring absolute w-10 h-10 rounded-full border-2 border-[#F5C518]"
             style="top: -4px; left: 50%; transform: translateX(-50%);"></div>

      </div>
      <?php endforeach; ?>

      <!-- Label "Tangerang & Sekitarnya" di tengah bawah -->
      <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2">
        <div class="w-1.5 h-1.5 rounded-full bg-[#F5C518] animate-pulse"></div>
        <span class="text-[11px] font-bold uppercase tracking-widest text-white/30">
          Tangerang & Sekitarnya
        </span>
        <div class="w-1.5 h-1.5 rounded-full bg-[#F5C518] animate-pulse"></div>
      </div>

      <!-- Delivery badge pojok kanan atas -->
      <div class="absolute top-4 right-4 flex items-center gap-2 px-3 py-1.5 rounded-full"
           style="background: rgba(245,197,24,.1); border: 1px solid rgba(245,197,24,.25);">
        <span class="text-sm">🛵</span>
        <span class="text-[11px] font-bold text-[#F5C518]">Antar Kapanpun & Dimanapun</span>
      </div>

    </div><!-- /peta abstrak -->

    <!-- ════════════════════════════════════
         KARTU LOKASI
    ════════════════════════════════════ -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
      <?php foreach ($locations as $idx => $loc): ?>

      <a href="<?= BASE_URL ?>/<?= e($loc['slug']) ?>/"
         class="area-card group block rounded-2xl p-5 no-underline"
         style="background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08);">

        <div class="flex items-start gap-3 mb-3">
          <!-- Pin icon -->
          <div class="area-pin-icon w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 transition-all duration-300"
               style="background: rgba(245,197,24,.12); border: 1px solid rgba(245,197,24,.2);">
            <svg class="w-4 h-4 text-[#F5C518]" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
            </svg>
          </div>

          <div>
            <div class="font-serif font-bold text-white text-sm leading-tight">
              <?= e($loc['name']) ?>
            </div>
            <!-- Estimasi waktu -->
            <!-- <div class="text-[10px] font-semibold text-[#F5C518]/70 mt-0.5">
               2–4 Jam
            </div> -->
          </div>
        </div>

        <!-- Alamat -->
        <?php if (!empty($loc['address'])): ?>
        <p class="text-white/40 text-[12px] leading-relaxed line-clamp-2 mb-3">
          <?= e($loc['address']) ?>
        </p>
        <?php endif; ?>

        <!-- CTA -->
        <div class="flex items-center gap-1 text-[11px] font-bold text-[#F5C518]/60 group-hover:text-[#F5C518] transition-colors duration-200">
          Lihat layanan di sini
          <svg class="w-3 h-3 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
          </svg>
        </div>

      </a>

      <?php endforeach; ?>
    </div>

    <!-- ── Footer note ── -->
    <div class="text-center mt-10">
      <p class="text-white/35 text-sm">
        Tidak menemukan area Anda?
        <a href="<?= e($wa_url) ?>?text=<?= urlencode('Halo, apakah ada layanan pengiriman ke area saya?') ?>"
           target="_blank"
           class="text-[#F5C518] font-semibold hover:underline transition ml-1">
          Hubungi kami via WhatsApp →
        </a>
      </p>
    </div>

  </div>
</section>

<!-- ============================================================
     TESTIMONIAL SECTION
============================================================ -->
<?php
/* ================================================================
   TESTIMONI SECTION — Carousel
   Tema: navy + gold, konsisten dengan seluruh halaman
   Auto-play + drag/swipe support
================================================================ */
?>

<style>
  /* ── Carousel track ── */
  .testi-track {
    display: flex;
    transition: transform .5s cubic-bezier(.4,0,.2,1);
    will-change: transform;
  }

  .testi-slide {
    flex: 0 0 100%;
    padding: 0 8px;
  }

  @media (min-width: 768px) {
    .testi-slide { flex: 0 0 50%; }
  }
  @media (min-width: 1024px) {
    .testi-slide { flex: 0 0 33.333%; }
  }

  /* ── Card ── */
  .testi-card {
    background: rgba(255,255,255,.04);
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 20px;
    padding: 28px;
    height: 100%;
    position: relative;
    transition: border-color .3s ease, box-shadow .3s ease;
  }
  .testi-card:hover {
    border-color: rgba(245,197,24,.3);
    box-shadow: 0 16px 48px rgba(0,0,0,.3);
  }

  /* Quote mark dekoratif */
  .testi-quote {
    position: absolute;
    top: 16px;
    right: 20px;
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 72px;
    line-height: 1;
    color: rgba(245,197,24,.1);
    pointer-events: none;
    user-select: none;
  }

  /* ── Dot indicator ── */
  .testi-dot {
    width: 6px;
    height: 6px;
    border-radius: 100px;
    background: rgba(255,255,255,.2);
    transition: all .3s ease;
    cursor: pointer;
  }
  .testi-dot.active {
    width: 24px;
    background: #F5C518;
  }

  /* ── Nav button ── */
  .testi-nav {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: 1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.05);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all .25s ease;
    flex-shrink: 0;
  }
  .testi-nav:hover {
    background: #F5C518;
    border-color: #F5C518;
  }
  .testi-nav:hover svg {
    stroke: #0B1F4A;
  }
  .testi-nav svg {
    stroke: rgba(255,255,255,.7);
    transition: stroke .25s ease;
  }

  /* Avatar inisial */
  .testi-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(245,197,24,.15);
    border: 1.5px solid rgba(245,197,24,.3);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Playfair Display', Georgia, serif;
    font-weight: 700;
    font-size: 16px;
    color: #F5C518;
    flex-shrink: 0;
  }

  /* Fade edge kiri kanan (desktop) */
  .testi-fade-left {
    background: linear-gradient(to right, #0B1F4A, transparent);
  }
  .testi-fade-right {
    background: linear-gradient(to left, #0B1F4A, transparent);
  }
</style>

<!-- ============================================================
     TESTIMONI SECTION
============================================================ -->
<section id="testimoni" class="py-20 relative overflow-hidden"
         style="background: #0B1F4A;">

  <!-- Dekorasi top line -->
  <div class="absolute top-0 left-0 w-full h-px"
       style="background: linear-gradient(90deg, transparent, rgba(245,197,24,.3), transparent);"></div>

  <!-- Glow background -->
  <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[500px] h-64 pointer-events-none"
       style="background: radial-gradient(ellipse, rgba(245,197,24,.06) 0%, transparent 70%); filter: blur(40px);"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-4">

    <!-- ── Header ── -->
    <div class="mb-12">
      <div>
        <div class="inline-flex items-center gap-2 bg-[#F5C518]/10 border border-[#F5C518]/25 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase text-[#F5C518] mb-5">
          <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#F5C518]"></span>
          Apa Kata Mereka
        </div>
        <h2 class="font-serif text-3xl md:text-4xl font-black text-white mt-2 mb-3">
          Testimoni Pelanggan
        </h2>
        <p class="text-white/45 max-w-md text-[15px] leading-relaxed">
          Kepercayaan pelanggan adalah motivasi terbesar kami untuk terus memberikan yang terbaik.
        </p>
      </div>


    </div>

    <!-- ── Carousel Wrapper ── -->
    <div class="relative">

      <!-- Fade edges (desktop only) -->
      <div class="testi-fade-left absolute left-0 top-0 bottom-0 w-12 z-10 pointer-events-none hidden md:block"></div>
      <div class="testi-fade-right absolute right-0 top-0 bottom-0 w-12 z-10 pointer-events-none hidden md:block"></div>

      <!-- Overflow container -->
      <div class="overflow-hidden" id="testi-overflow">
        <div class="testi-track" id="testi-track">

          <?php foreach ($testimonials as $t): ?>
          <div class="testi-slide">
            <div class="testi-card">

              <!-- Quote dekoratif -->
              <div class="testi-quote">"</div>

              <!-- Bintang -->
              <div class="flex gap-0.5 mb-4">
                <?php for ($s = 0; $s < (int)$t['rating']; $s++): ?>
                <span class="text-[#F5C518] text-sm leading-none">★</span>
                <?php endfor; ?>
                <?php for ($s = (int)$t['rating']; $s < 5; $s++): ?>
                <span class="text-white/15 text-sm leading-none">★</span>
                <?php endfor; ?>
              </div>

              <!-- Isi testimoni -->
              <p class="text-white/65 text-[13px] leading-[1.8] mb-6 relative z-10">
                "<?= e($t['content']) ?>"
              </p>

              <!-- Author -->
              <div class="flex items-center gap-3 mt-auto">
                <div class="testi-avatar">
                  <?= strtoupper(substr($t['name'], 0, 1)) ?>
                </div>
                <div>
                  <div class="font-bold text-white text-sm">
                    <?= e($t['name']) ?>
                  </div>
                  <div class="text-white/35 text-[11px] mt-0.5">
                    <?= e($t['location']) ?>
                  </div>
                </div>
              </div>

            </div>
          </div>
          <?php endforeach; ?>

        </div><!-- /testi-track -->
      </div><!-- /overflow -->

    </div><!-- /carousel wrapper -->

    <!-- ── Dot indicators + nav mobile ── -->
    <div class="flex items-center justify-center gap-4 mt-8">

      <!-- Nav kiri -->
      <button class="testi-nav" id="testi-prev-m" aria-label="Sebelumnya">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
      </button>

      <!-- Dots -->
      <div class="flex items-center gap-2" id="testi-dots"></div>

      <!-- Nav kanan -->
      <button class="testi-nav" id="testi-next-m" aria-label="Berikutnya">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
      </button>

    </div>

  </div>
</section>

<script>
(function() {
  const track      = document.getElementById('testi-track');
  const overflow   = document.getElementById('testi-overflow');
  const dotsWrap   = document.getElementById('testi-dots');
  const slides     = track.querySelectorAll('.testi-slide');
  const total      = slides.length;

  let current   = 0;
  let autoTimer = null;
  let startX    = 0;
  let isDragging = false;

  // Berapa slide terlihat per breakpoint
  function visibleCount() {
    if (window.innerWidth >= 1024) return 3;
    if (window.innerWidth >= 768)  return 2;
    return 1;
  }

  // Max index yang bisa dicapai
  function maxIndex() {
    return Math.max(0, total - visibleCount());
  }

  // Pindah ke index tertentu
  function goTo(idx) {
    current = Math.max(0, Math.min(idx, maxIndex()));
    const slideW = slides[0].offsetWidth;
    track.style.transform = `translateX(-${current * slideW}px)`;
    updateDots();
  }

  // Buat dots
  function buildDots() {
    dotsWrap.innerHTML = '';
    const count = maxIndex() + 1;
    for (let i = 0; i < count; i++) {
      const d = document.createElement('button');
      d.className = 'testi-dot' + (i === current ? ' active' : '');
      d.addEventListener('click', () => { goTo(i); resetAuto(); });
      dotsWrap.appendChild(d);
    }
  }

  function updateDots() {
    dotsWrap.querySelectorAll('.testi-dot').forEach((d, i) => {
      d.classList.toggle('active', i === current);
    });
  }

  // Auto-play
  function startAuto() {
    autoTimer = setInterval(() => {
      goTo(current >= maxIndex() ? 0 : current + 1);
    }, 4000);
  }
  function resetAuto() {
    clearInterval(autoTimer);
    startAuto();
  }

  // Nav buttons — hanya tombol bawah (mobile & desktop)
  ['testi-prev-m'].forEach(id => {
    document.getElementById(id)?.addEventListener('click', () => {
      goTo(current <= 0 ? maxIndex() : current - 1);
      resetAuto();
    });
  });
  ['testi-next-m'].forEach(id => {
    document.getElementById(id)?.addEventListener('click', () => {
      goTo(current >= maxIndex() ? 0 : current + 1);
      resetAuto();
    });
  });

  // Touch / drag swipe
  overflow.addEventListener('touchstart', e => { startX = e.touches[0].clientX; }, { passive: true });
  overflow.addEventListener('touchend', e => {
    const diff = startX - e.changedTouches[0].clientX;
    if (Math.abs(diff) > 50) {
      goTo(diff > 0 ? current + 1 : current - 1);
      resetAuto();
    }
  });

  // Mouse drag
  overflow.addEventListener('mousedown', e => { isDragging = true; startX = e.clientX; });
  overflow.addEventListener('mouseup', e => {
    if (!isDragging) return;
    isDragging = false;
    const diff = startX - e.clientX;
    if (Math.abs(diff) > 50) {
      goTo(diff > 0 ? current + 1 : current - 1);
      resetAuto();
    }
  });
  overflow.addEventListener('mouseleave', () => { isDragging = false; });

  // Pause saat hover
  overflow.addEventListener('mouseenter', () => clearInterval(autoTimer));
  overflow.addEventListener('mouseleave', () => startAuto());

  // Rebuild saat resize
  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      goTo(Math.min(current, maxIndex()));
      buildDots();
    }, 150);
  });

  // Init
  buildDots();
  goTo(0);
  startAuto();
})();
</script>
<!-- ============================================================
     FAQ SECTION
============================================================ -->
<?php
/* ================================================================
   FAQ SECTION — Split Layout
   Kiri: headline + deskripsi + CTA WA
   Kanan: accordion FAQ
   JSON-LD schema tetap valid untuk SEO
================================================================ */
?>

<!-- FAQ Schema — tetap untuk SEO -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    <?php foreach ($faqs as $i => $faq): ?>
    {
      "@type": "Question",
      "name": "<?= addslashes($faq['question']) ?>",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "<?= addslashes($faq['answer']) ?>"
      }
    }<?= $i < count($faqs)-1 ? ',' : '' ?>
    <?php endforeach; ?>
  ]
}
</script>

<style>
  /* Accordion answer */
  .faq-body {
    display: grid;
    grid-template-rows: 0fr;
    transition: grid-template-rows .35s cubic-bezier(.4,0,.2,1);
  }
  .faq-body.open {
    grid-template-rows: 1fr;
  }
  .faq-body-inner {
    overflow: hidden;
  }

  /* Icon rotate */
  .faq-icon {
    transition: transform .3s cubic-bezier(.4,0,.2,1);
    flex-shrink: 0;
  }
  .faq-item.open .faq-icon {
    transform: rotate(180deg);
  }

  /* Card hover */
  .faq-item {
    transition: border-color .25s ease, box-shadow .25s ease;
  }
  .faq-item:hover {
    border-color: rgba(245,197,24,.25) !important;
  }
  .faq-item.open {
    border-color: rgba(245,197,24,.35) !important;
    box-shadow: 0 8px 32px rgba(0,0,0,.25);
  }

  /* Number dekoratif */
  .faq-num {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 11px;
    font-weight: 900;
    color: rgba(245,197,24,.4);
    letter-spacing: .05em;
    min-width: 28px;
    padding-top: 1px;
  }
  .faq-item.open .faq-num {
    color: #F5C518;
  }
</style>

<!-- ============================================================
     FAQ SECTION
============================================================ -->
<section id="faq" class="py-20 relative overflow-hidden"
         style="background: #081729;">

  <!-- Dekorasi top line -->
  <div class="absolute top-0 left-0 w-full h-px"
       style="background: linear-gradient(90deg, transparent, rgba(245,197,24,.3), transparent);"></div>

  <!-- Glow kiri -->
  <div class="absolute top-1/2 left-0 -translate-y-1/2 w-72 h-72 rounded-full pointer-events-none"
       style="background: radial-gradient(circle, rgba(245,197,24,.06) 0%, transparent 70%); filter: blur(60px);"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-4">

    <div class="grid md:grid-cols-2 gap-12 lg:gap-20 items-start">

      <!-- ══════════════════════════════
           KIRI — Headline + CTA
      ══════════════════════════════ -->
      <div class="md:sticky md:top-28">

        <!-- Overline -->
        <div class="inline-flex items-center gap-2 bg-[#F5C518]/10 border border-[#F5C518]/25 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase text-[#F5C518] mb-6">
          <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#F5C518]"></span>
          Ada Pertanyaan?
        </div>

        <h2 class="font-serif text-3xl md:text-4xl lg:text-5xl font-black text-white leading-tight mb-5">
          Pertanyaan yang<br>
          <span style="color: #F5C518;">Sering Ditanyakan</span>
        </h2>

        <p class="text-white/50 text-[15px] leading-relaxed mb-8 max-w-sm">
          Temukan jawaban atas pertanyaan umum seputar layanan, pengiriman, dan pemesanan bunga di Toko Bunga Tangerang.
        </p>

        <!-- Stats kecil -->
        <div class="flex items-center gap-6 mb-10 pb-10"
             style="border-bottom: 1px solid rgba(255,255,255,.07);">
          <div>
            <div class="font-serif text-2xl font-black text-white">
              <?= count($faqs) ?>
            </div>
            <div class="text-[10px] font-bold uppercase tracking-widest text-white/35 mt-0.5">
              <?= count($faqs) > 1 ? 'Pertanyaan' : 'Pertanyaan' ?>
            </div>
          </div>
          <div class="w-px h-10" style="background: rgba(255,255,255,.08);"></div>
          <div>
            <div class="font-serif text-2xl font-black text-white">24/7</div>
            <div class="text-[10px] font-bold uppercase tracking-widest text-white/35 mt-0.5">
              Siap Bantu
            </div>
          </div>
          <div class="w-px h-10" style="background: rgba(255,255,255,.08);"></div>
          <div>
            <div class="font-serif text-2xl font-black text-white">Free</div>
            <div class="text-[10px] font-bold uppercase tracking-widest text-white/35 mt-0.5">
              Konsultasi
            </div>
          </div>
        </div>

        <!-- CTA WA -->
        <div class="flex flex-col gap-3 items-start">
          <p class="text-white/40 text-sm">Masih ada pertanyaan lain?</p>
          <a href="<?= e($wa_url) ?>?text=<?= urlencode('Halo, saya punya pertanyaan tentang Toko Bunga Tangerang.') ?>"
             target="_blank"
             class="inline-flex items-center gap-2.5 font-bold text-[#0B1F4A] px-6 py-3.5 rounded-full no-underline transition hover:-translate-y-1 hover:shadow-[0_12px_32px_rgba(245,197,24,.4)]"
             style="background: #F5C518;">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
              <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
            </svg>
            Tanya via WhatsApp
          </a>
        </div>

      </div>

      <!-- ══════════════════════════════
           KANAN — Accordion FAQ
      ══════════════════════════════ -->
      <div class="space-y-3">

        <?php foreach ($faqs as $i => $faq): ?>
        <div class="faq-item rounded-2xl overflow-hidden cursor-pointer"
             style="background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08);"
             onclick="toggleFaqNew(this)">

          <!-- Trigger -->
          <div class="flex items-start gap-4 px-5 py-4 md:px-6 md:py-5">

            <!-- Nomor -->
            <span class="faq-num">
              <?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?>
            </span>

            <!-- Pertanyaan -->
            <span class="flex-1 font-semibold text-white text-sm md:text-[15px] leading-snug pr-2">
              <?= e($faq['question']) ?>
            </span>

            <!-- Icon -->
            <svg class="faq-icon w-5 h-5 mt-0.5" style="color: rgba(245,197,24,.5);"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </div>

          <!-- Answer body -->
          <div class="faq-body">
            <div class="faq-body-inner">
              <div class="px-5 pb-5 md:px-6 md:pb-6 pl-[52px]"
                   style="border-top: 1px solid rgba(255,255,255,.06);">
                <p class="text-white/55 text-[13px] leading-[1.85] pt-4">
                  <?= e($faq['answer']) ?>
                </p>
              </div>
            </div>
          </div>

        </div>
        <?php endforeach; ?>

      </div>

    </div>
  </div>
</section>

<script>
function toggleFaqNew(item) {
  const body    = item.querySelector('.faq-body');
  const isOpen  = item.classList.contains('open');

  // Tutup semua
  document.querySelectorAll('.faq-item.open').forEach(el => {
    el.classList.remove('open');
    el.querySelector('.faq-body').classList.remove('open');
  });

  // Buka yang diklik (kalau belum open)
  if (!isOpen) {
    item.classList.add('open');
    body.classList.add('open');
  }
}

// Buka item pertama by default
document.addEventListener('DOMContentLoaded', function() {
  const first = document.querySelector('.faq-item');
  if (first) toggleFaqNew(first);
});
</script>

<!-- ============================================================
     CTA BANNER SECTION
============================================================ -->
<?php
/* ================================================================
   CTA SECTION — Cinematic Banner
   Background foto bunga + overlay navy gelap
   Teks dramatis di tengah + dua tombol
================================================================ */
?>

<style>
  /* Parallax subtle di scroll */
  .cta-bg {
    background-attachment: fixed;
    background-size: cover;
    background-position: center;
    transition: filter .3s ease;
  }

  /* Shimmer animasi pada garis gold */
  @keyframes shimmer-line {
    0%   { background-position: -200% center; }
    100% { background-position: 200% center; }
  }
  .gold-shimmer-line {
    background: linear-gradient(90deg,
      transparent 0%,
      rgba(245,197,24,.8) 40%,
      rgba(255,220,80,1) 50%,
      rgba(245,197,24,.8) 60%,
      transparent 100%
    );
    background-size: 200% auto;
    animation: shimmer-line 3s linear infinite;
  }

  /* Teks headline gradient gold */
  .cta-headline {
    background: linear-gradient(135deg, #FFFFFF 0%, #F5C518 50%, #FFE066 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  /* Particle bunga kecil melayang */
  @keyframes petal-float {
    0%   { transform: translateY(0) rotate(0deg);   opacity: 0; }
    10%  { opacity: .6; }
    90%  { opacity: .3; }
    100% { transform: translateY(-120px) rotate(180deg); opacity: 0; }
  }
  .petal {
    position: absolute;
    font-size: 18px;
    animation: petal-float linear infinite;
    pointer-events: none;
    user-select: none;
  }
</style>

<!-- ============================================================
     CTA CINEMATIC SECTION
============================================================ -->
<section class="relative overflow-hidden" style="min-height: 520px;">

  <!-- Background foto bunga dengan parallax -->
  <div class="cta-bg absolute inset-0"
       style="background-image: url('<?= BASE_URL ?>/assets/images/banner.png');"></div>

  <!-- Overlay gradient navy berlapis — dramatis -->
  <div class="absolute inset-0"
       style="background: linear-gradient(160deg,
         rgba(8,23,41,.92) 0%,
         rgba(11,31,74,.85) 40%,
         rgba(8,23,41,.95) 100%
       );"></div>

  <!-- Overlay grain texture subtle -->
  <div class="absolute inset-0 opacity-[0.04]"
       style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22><filter id=%22n%22><feTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 stitchTiles=%22stitch%22/></filter><rect width=%22300%22 height=%22300%22 filter=%22url(%23n)%22 opacity=%221%22/></svg>');"></div>

  <!-- Garis gold atas -->
  <div class="absolute top-0 left-0 w-full h-[2px]">
    <div class="gold-shimmer-line w-full h-full"></div>
  </div>

  <!-- Garis gold bawah -->
  <div class="absolute bottom-0 left-0 w-full h-[2px]">
    <div class="gold-shimmer-line w-full h-full"></div>
  </div>

  <!-- Petals melayang (dekoratif) -->
  <span class="petal" style="left:8%;  bottom:0; animation-duration:8s;  animation-delay:0s;">🌸</span>
  <span class="petal" style="left:22%; bottom:0; animation-duration:11s; animation-delay:2s;">🌺</span>
  <span class="petal" style="left:45%; bottom:0; animation-duration:9s;  animation-delay:1s; font-size:12px;">✿</span>
  <span class="petal" style="left:65%; bottom:0; animation-duration:13s; animation-delay:3s;">🌸</span>
  <span class="petal" style="left:82%; bottom:0; animation-duration:10s; animation-delay:1.5s; font-size:14px;">🌺</span>
  <span class="petal" style="left:92%; bottom:0; animation-duration:7s;  animation-delay:4s; font-size:11px;">✿</span>

  <!-- Glow tengah -->
  <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
    <div class="w-[500px] h-[300px] rounded-full"
         style="background: radial-gradient(ellipse, rgba(245,197,24,.08) 0%, transparent 70%); filter: blur(40px);"></div>
  </div>

  <!-- ── KONTEN ── -->
  <div class="relative z-10 max-w-4xl mx-auto px-4 py-24 text-center flex flex-col items-center">

    <!-- Overline badge -->
    <div class="inline-flex items-center gap-2 bg-[#F5C518]/10 border border-[#F5C518]/25 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase text-[#F5C518] mb-8">
      <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#F5C518] animate-pulse"></span>
      Siap Memesan?
    </div>

    <!-- Headline dramatis -->
    <h2 class="cta-headline font-serif text-4xl md:text-5xl lg:text-6xl font-black leading-tight mb-6 max-w-2xl">
      Pesan Bunga Segar<br>Untuk Momen Spesialmu
    </h2>

    <!-- Garis dekoratif -->
    <div class="flex items-center gap-4 mb-6">
      <div class="h-px w-16 md:w-24" style="background: rgba(245,197,24,.3);"></div>
      <span class="text-[#F5C518] text-lg">✦</span>
      <div class="h-px w-16 md:w-24" style="background: rgba(245,197,24,.3);"></div>
    </div>

    <p class="text-white/60 text-base md:text-lg leading-relaxed mb-10 max-w-xl">
      Hubungi kami sekarang dan dapatkan bunga segar terbaik dengan pengiriman cepat ke seluruh Tangerang.
    </p>

    <!-- Tombol CTA -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center w-full sm:w-auto">

      <!-- WA — utama gold -->
      <a href="<?= e($wa_url) ?>?text=<?= urlencode('Halo, saya ingin memesan bunga dari Toko Bunga Tangerang!') ?>"
         target="_blank"
         class="inline-flex items-center justify-center gap-2.5 font-bold text-[#0B1F4A] px-8 py-4 rounded-full no-underline transition hover:-translate-y-1 hover:shadow-[0_16px_40px_rgba(245,197,24,.5)] text-[15px]"
         style="background: #F5C518;">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
          <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
          <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
        </svg>
        Pesan Sekarang via WhatsApp
      </a>

      <!-- Telepon — outline -->
      <a href="tel:<?= e(setting('whatsapp_number')) ?>"
         class="inline-flex items-center justify-center gap-2.5 font-semibold text-white px-8 py-4 rounded-full no-underline transition hover:-translate-y-1 hover:bg-white/20 text-[15px]"
         style="border: 1.5px solid rgba(255,255,255,.25); background: rgba(255,255,255,.08); backdrop-filter: blur(8px);">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/>
        </svg>
        Telepon Langsung
      </a>

    </div>

    <!-- Trust badges bawah -->
    <div class="flex flex-wrap items-center justify-center gap-4 mt-10">
      <div class="flex items-center gap-1.5 text-white/35 text-[11px] font-semibold">
        <span class="text-[#F5C518]">✓</span> Respon Cepat
      </div>
      <div class="w-px h-3 bg-white/15"></div>
      <div class="flex items-center gap-1.5 text-white/35 text-[11px] font-semibold">
        <span class="text-[#F5C518]">✓</span> Bunga Segar Dijamin
      </div>
      <div class="w-px h-3 bg-white/15"></div>
      <div class="flex items-center gap-1.5 text-white/35 text-[11px] font-semibold">
        <span class="text-[#F5C518]">✓</span> Pengiriman Tepat Waktu
      </div>
      <div class="w-px h-3 bg-white/15"></div>
      <div class="flex items-center gap-1.5 text-white/35 text-[11px] font-semibold">
        <span class="text-[#F5C518]">✓</span> Buka 24 Jam
      </div>
    </div>

  </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>

<script>
  // Slider Hero
let index = 0;
const slider = document.getElementById('heroSlider');
if (slider) {
  const totalSlides = slider.children.length;
  setInterval(() => {
    index = (index + 1) % totalSlides;
    slider.style.transform = `translateX(-${index * 100}%)`;
  }, 5000);
}

//lain
function toggleFaq(btn) {
  const answer = btn.nextElementSibling;
  const icon   = btn.querySelector('.faq-icon');
  answer.classList.toggle('hidden');
  icon.style.transform = answer.classList.contains('hidden') ? '' : 'rotate(180deg)';
}

// mini slider
(function() {

const testimonials = <?= json_encode($testimonials) ?>;
let miniReviewIndex = 0;

function renderMiniReview() {
  if (!testimonials.length) return;

  const t = testimonials[miniReviewIndex];

  document.getElementById('mini-content').textContent = `"${t.content}"`;
  document.getElementById('mini-author').textContent = `— ${t.name}, ${t.location}`;
  document.getElementById('mini-stars').textContent =
    "★".repeat(parseInt(t.rating));

  miniReviewIndex = (miniReviewIndex + 1) % testimonials.length;
}

renderMiniReview();
setInterval(renderMiniReview, 4000);

})();

</script>