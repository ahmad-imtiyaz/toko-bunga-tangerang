
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
     HERO SECTION — Toko Bunga Tangerang
     Redesign: Elegan & Mewah | Teks Kanan | Palet Pink-Putih-Krem
============================================================ -->

<style>
  /* ── Google Fonts ── */
  @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&family=Jost:wght@300;400;500;600&display=swap');

  /* ── CSS Variables ── */
  :root {
    --blush:    #F2C4CE;
    --rose:     #D4899A;
    --dusty:    #C8788A;
    --cream:    #FAF5EE;
    --ivory:    #FDF9F4;
    --muted:    #8C6B72;
    --dark:     #2C1A1E;
    --gold:     #C9A96E;
  }

  /* ── Keyframes ── */
  @keyframes fadeUp {
    from { opacity:0; transform:translateY(28px); }
    to   { opacity:1; transform:translateY(0); }
  }
  @keyframes heroTicker {
    from { transform:translateX(0); }
    to   { transform:translateX(-33.333%); }
  }
  @keyframes softPulse {
    0%,100% { opacity:1; transform:scale(1); }
    50%      { opacity:.4; transform:scale(1.6); }
  }
  @keyframes floatBadge {
    0%,100% { transform:translateY(0); }
    50%      { transform:translateY(-6px); }
  }
  @keyframes shimmer {
    0%   { background-position: -200% center; }
    100% { background-position:  200% center; }
  }

  /* ── Hero wrapper ── */
  #hero-new {
    font-family: 'Jost', sans-serif;
    position: relative;
    min-height: 100svh;
    overflow: hidden;
    background: var(--dark);
    display: flex;
    flex-direction: column;
  }

  /* ── Background image ── */
  .hero-bg {
    position: absolute;
    inset: 0;
    background-image: url('<?= BASE_URL ?>/assets/images/banner.png');
    background-size: cover;
    background-position: center left;
    z-index: 0;
  }

  /* ── Gradient overlay: gelap di kiri, terang/transparan di kanan ── */
  .hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
      to right,
      rgba(44,26,30,.85) 0%,
      rgba(44,26,30,.70) 35%,
      rgba(44,26,30,.30) 65%,
      rgba(44,26,30,.10) 100%
    );
    z-index: 1;
  }

  /* Overlay bawah agar strip fitur nyambung */
  .hero-overlay-bottom {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 220px;
    background: linear-gradient(to top, rgba(44,26,30,.9) 0%, transparent 100%);
    z-index: 1;
  }

  /* ── Content wrapper ── */
  .hero-content {
    position: relative;
    z-index: 2;
    flex: 1;
    display: flex;
    align-items: center;
    max-width: 1280px;
    margin: 0 auto;
    width: 100%;
    padding: 120px 32px 60px;
  }

  /* ── Text box — pushed to right ── */
  .hero-textbox {
    margin-left: auto;
    width: 100%;
    max-width: 520px;
    display: flex;
    flex-direction: column;
    gap: 0;
  }

  /* ── Overline ── */
  .hero-overline {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: 'Jost', sans-serif;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .22em;
    text-transform: uppercase;
    color: var(--blush);
    margin-bottom: 18px;
    opacity: 0;
    animation: fadeUp .7s .2s forwards;
  }
  .hero-overline-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: var(--rose);
    animation: softPulse 2.2s infinite;
  }

  /* ── Headline ── */
  .hero-headline {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(2.6rem, 5vw, 4.2rem);
    font-weight: 300;
    line-height: 1.08;
    color: #fff;
    margin: 0 0 10px;
    opacity: 0;
    animation: fadeUp .75s .35s forwards;
  }
  .hero-headline em {
    font-style: italic;
    color: var(--blush);
  }
  .hero-headline strong {
    font-weight: 700;
    background: linear-gradient(90deg, #fff 0%, var(--blush) 40%, var(--gold) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  /* ── Decorative rule ── */
  .hero-rule {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 22px;
    opacity: 0;
    animation: fadeUp .7s .5s forwards;
  }
  .hero-rule-line {
    height: 1px;
    width: 48px;
    background: linear-gradient(to right, var(--rose), transparent);
  }
  .hero-rule-ornament {
    color: var(--rose);
    font-size: 13px;
    letter-spacing: .15em;
  }

  /* ── Subtitle ── */
  .hero-subtitle {
    font-size: 14px;
    line-height: 1.85;
    color: rgba(255,255,255,.62);
    margin-bottom: 28px;
    max-width: 400px;
    opacity: 0;
    animation: fadeUp .7s .6s forwards;
  }

  /* ── USP chips ── */
  .hero-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 32px;
    opacity: 0;
    animation: fadeUp .7s .72s forwards;
  }
  .hero-chip {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .05em;
    color: var(--blush);
    border: 1px solid rgba(242,196,206,.25);
    background: rgba(242,196,206,.07);
    padding: 6px 14px;
    border-radius: 100px;
    backdrop-filter: blur(6px);
    transition: background .25s, border-color .25s;
  }
  .hero-chip:hover {
    background: rgba(242,196,206,.15);
    border-color: rgba(242,196,206,.5);
  }

  /* ── CTA buttons ── */
  .hero-ctas {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    opacity: 0;
    animation: fadeUp .7s .85s forwards;
  }
  .btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    background: linear-gradient(135deg, var(--blush) 0%, var(--dusty) 100%);
    color: #fff;
    font-family: 'Jost', sans-serif;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: .06em;
    padding: 14px 26px;
    border-radius: 100px;
    text-decoration: none;
    transition: transform .3s, box-shadow .3s;
    box-shadow: 0 8px 28px rgba(212,137,154,.35);
  }
  .btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 16px 40px rgba(212,137,154,.5);
    text-decoration: none;
    color: #fff;
  }
  .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 1px solid rgba(255,255,255,.22);
    color: rgba(255,255,255,.75);
    font-family: 'Jost', sans-serif;
    font-size: 13px;
    font-weight: 500;
    letter-spacing: .06em;
    padding: 14px 24px;
    border-radius: 100px;
    text-decoration: none;
    transition: border-color .3s, color .3s, transform .3s;
    backdrop-filter: blur(6px);
  }
  .btn-secondary:hover {
    border-color: var(--blush);
    color: var(--blush);
    transform: translateY(-2px);
    text-decoration: none;
  }

  /* ── Floating badge ── */
  .hero-float-badge {
    position: absolute;
    left: 36px;
    bottom: 180px;
    z-index: 3;
    width: 90px; height: 90px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--blush) 0%, var(--dusty) 100%);
    border: 4px solid rgba(255,255,255,.15);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    box-shadow: 0 12px 36px rgba(212,137,154,.45);
    animation: floatBadge 3.5s ease-in-out infinite;
    opacity: 0;
    animation: floatBadge 3.5s ease-in-out infinite, fadeUp .7s 1s forwards;
  }
  .hero-float-badge-num {
    font-family: 'Cormorant Garamond', serif;
    font-size: 22px;
    font-weight: 700;
    color: #fff;
    line-height: 1;
  }
  .hero-float-badge-sub {
    font-size: 9px;
    font-weight: 600;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: rgba(255,255,255,.8);
    margin-top: 2px;
  }

  /* ── Stats bar ── */
  .hero-statsbar {
    position: relative;
    z-index: 2;
    border-top: 1px solid rgba(255,255,255,.08);
    padding: 22px 32px 28px;
    max-width: 1280px;
    margin: 0 auto;
    width: 100%;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 20px 36px;
  }
  .stat-item { display: flex; flex-direction: column; }
  .stat-num {
    font-family: 'Cormorant Garamond', serif;
    font-size: 28px;
    font-weight: 700;
    color: #fff;
    line-height: 1;
  }
  .stat-num sup { font-size: 14px; color: var(--blush); }
  .stat-label {
    font-size: 9px;
    font-weight: 600;
    letter-spacing: .18em;
    text-transform: uppercase;
    color: rgba(255,255,255,.38);
    margin-top: 4px;
  }
  .stats-divider {
    width: 1px; height: 40px;
    background: rgba(255,255,255,.1);
  }
  .stat-price-label {
    font-size: 10px;
    color: rgba(255,255,255,.45);
    font-weight: 500;
    margin-bottom: 2px;
  }
  .stat-price-val {
    font-family: 'Cormorant Garamond', serif;
    font-size: 26px;
    font-weight: 700;
    color: var(--blush);
  }

  /* ════════════════════════════
     STRIP FITUR
  ════════════════════════════ */
  #hero-features {
    background: var(--ivory);
    border-top: 1px solid rgba(200,120,138,.12);
    padding: 40px 32px;
  }
  .features-grid {
    max-width: 1280px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 0;
  }
  .feature-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 16px 20px;
    border-right: 1px solid rgba(200,120,138,.12);
    transition: background .3s;
  }
  .feature-item:last-child { border-right: none; }
  .feature-item:hover { background: rgba(242,196,206,.08); }
  .feature-icon img {
  width: 40px;
  height: 40px;
    margin-bottom: 12px;
  object-fit: contain;
}
  .feature-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 15px;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 6px;
    line-height: 1.3;
  }
  .feature-desc {
    font-size: 12px;
    color: var(--muted);
    line-height: 1.65;
  }

  /* ════════════════════════════
     TICKER
  ════════════════════════════ */
  #hero-ticker-wrap {
    background: linear-gradient(135deg, var(--dusty) 0%, var(--rose) 100%);
    padding: 12px 0;
    overflow: hidden;
  }
  .ticker-track-new {
    display: flex;
    width: max-content;
    animation: heroTicker 30s linear infinite;
  }
  .ticker-item-new {
    display: inline-flex;
    align-items: center;
    gap: 14px;
    padding: 0 28px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: rgba(255,255,255,.9);
    white-space: nowrap;
  }
  .ticker-sep { color: rgba(255,255,255,.4); font-size: 10px; }

  /* ════════════════════════════
     RESPONSIVE
  ════════════════════════════ */
  @media (max-width: 1023px) {
    .hero-content {
      padding-top: 100px;
      padding-bottom: 40px;
      align-items: flex-end;
    }
    .hero-textbox {
      margin-left: 0;
      max-width: 100%;
    }
    .hero-overlay {
      background: linear-gradient(to top,
        rgba(44,26,30,.92) 0%,
        rgba(44,26,30,.6) 50%,
        rgba(44,26,30,.25) 100%
      );
    }
    .hero-float-badge { display: none; }
    .features-grid { grid-template-columns: repeat(2, 1fr); }
    .feature-item:nth-child(2) { border-right: none; }
    .feature-item:nth-child(5) { grid-column: 1/-1; border-right: none; }
    .hero-statsbar { padding: 18px 20px 22px; }
    .hero-review-card { margin-left: 0; max-width: 100%; }
  }
  @media (max-width: 640px) {
    .hero-content { padding: 90px 20px 36px; }
    .hero-statsbar { padding: 16px 20px 20px; gap: 14px 20px; }
    #hero-features { padding: 28px 20px; }
    .features-grid { grid-template-columns: 1fr 1fr; gap: 0; }
    .feature-item:nth-child(even) { border-right: none; }
    .feature-item:nth-child(5) { grid-column: 1/-1; border-right: none; }
    .hero-review-card { display: none; }
  }
</style>

<!-- ══════════════════════════════════════════
     HERO SECTION
══════════════════════════════════════════ -->
<section id="hero-new">

  <!-- Background image -->
  <div class="hero-bg"></div>
  <!-- Overlays -->
  <div class="hero-overlay"></div>
  <div class="hero-overlay-bottom"></div>

  <!-- Floating badge (10+ Tahun) — desktop only, kiri -->
  <div class="hero-float-badge">
    <span class="hero-float-badge-num">10+</span>
    <span class="hero-float-badge-sub">Tahun<br>Melayani</span>
  </div>

  <!-- Content -->
  <div class="hero-content">
    <div class="hero-textbox">

      <!-- Overline -->
      <div class="hero-overline">
        <span class="hero-overline-dot"></span>
        Florist Terpercaya Tangerang
      </div>

      <!-- Headline -->
      <h1 class="hero-headline">
        <?= e(setting('hero_title')) ?>
      </h1>

      <!-- Decorative rule -->
      <div class="hero-rule">
        <div class="hero-rule-line"></div>
        <span class="hero-rule-ornament">✦ ✦ ✦</span>
        <div class="hero-rule-line" style="background:linear-gradient(to left,var(--rose),transparent)"></div>
      </div>

      <!-- Subtitle -->
      <p class="hero-subtitle">
        <?= e(setting('hero_subtitle')) ?>
      </p>

      <!-- USP chips -->
      <div class="hero-chips">
        <span class="hero-chip">• Antar 2–4 Jam</span>
        <span class="hero-chip">• Bunga Segar</span>
        <span class="hero-chip">• Custom Design</span>
        <span class="hero-chip">• Buka 24 Jam</span>
      </div>

      <!-- CTA -->
      <div class="hero-ctas">
        <a href="<?= e($wa_url) ?>?text=<?= $wa_msg ?>" target="_blank" class="btn-primary">
          <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
            <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
          </svg>
          Pesan via WhatsApp
        </a>
        <a href="#produk" class="btn-secondary">
          Lihat Produk
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
        </a>
      </div>

    </div>
  </div>

  <!-- Stats bar -->
  <div class="hero-statsbar">

    <div>
      <p class="stat-price-label">Mulai dari</p>
      <p class="stat-price-val">Rp 300.000</p>
    </div>

    <div class="stats-divider"></div>

    <div class="stat-item">
      <span class="stat-num">500<sup>+</sup></span>
      <span class="stat-label">Pelanggan Puas</span>
    </div>
    <div class="stats-divider"></div>
    <div class="stat-item">
      <span class="stat-num">24<sup>H</sup></span>
      <span class="stat-label">Siap Antar</span>
    </div>
    <div class="stats-divider"></div>
    <div class="stat-item">
      <span class="stat-num">12</span>
      <span class="stat-label">Kecamatan</span>
    </div>

    <div class="stats-divider" style="display:none" id="stats-divider-review"></div>


  </div>

</section>

<!-- ══════════════════════════════════════════
     STRIP FITUR
══════════════════════════════════════════ -->
<section id="hero-features">
  <div class="features-grid">
    <div class="feature-item">
     <div class="feature-icon">
  <img src="<?= BASE_URL ?>/assets/svg/flower.svg" alt="Flower Icon">
</div>
      <p class="feature-title">Fresh Flower<br>Guarantee</p>
      <p class="feature-desc">Bunga selalu dikirim dalam kondisi segar dan berkualitas.</p>
    </div>
    <div class="feature-item">
       <div class="feature-icon">
  <img src="<?= BASE_URL ?>/assets/svg/timer.svg" alt="Timer Icon">
</div>
      <p class="feature-title">Last-Minute Orders<br>Accepted</p>
      <p class="feature-desc">Siap menerima pesanan mendadak dengan layanan cepat.</p>
    </div>
    <div class="feature-item">
       <div class="feature-icon">
  <img src="<?= BASE_URL ?>/assets/svg/delivery.svg" alt="Delivery Icon">
</div>
      <p class="feature-title">Same-Day Delivery<br>Services</p>
      <p class="feature-desc">Pesan sekarang dan paket kamu akan dikirim hari ini juga!</p>
    </div>
    <div class="feature-item">
      <div class="feature-icon">
  <img src="<?= BASE_URL ?>/assets/svg/chat.svg" alt="Chat Icon">
</div>
      <p class="feature-title">Free Flower<br>Consultation</p>
      <p class="feature-desc">Dapatkan saran bunga gratis dari florist kami.</p>
    </div>
    <div class="feature-item">
       <div class="feature-icon">
  <img src="<?= BASE_URL ?>/assets/svg/badge.svg" alt="Badge Icon">
</div>
      <p class="feature-title">Customer Satisfaction<br>Guarantee</p>
      <p class="feature-desc">Bunga rusak atau layu? Kami ganti tanpa biaya.</p>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     TICKER
══════════════════════════════════════════ -->
<div id="hero-ticker-wrap" aria-hidden="true">
  <div class="ticker-track-new">
    <?php
    $tickers = [
      '🌸 Hand Bouquet Premium',
      '📋 Bunga Papan Ucapan',
      '💍 Wedding Decoration',
      '🕊️ Duka Cita',
      '🎓 Buket Wisuda',
      '⚡ Pengiriman 2–4 Jam',
      '✏️ Custom Design',
      '💰 Mulai Rp 300.000',
    ];
    for ($i = 0; $i < 3; $i++):
      foreach ($tickers as $t): ?>
        <span class="ticker-item-new">
          <span class="ticker-sep">✦</span>
          <?= $t ?>
        </span>
    <?php endforeach; endfor; ?>
  </div>
</div>
<!-- ============================================================
     LAYANAN SECTION — Split Kiri-Kanan High-End Florist
     Tema: Elegan & Mewah | Palet ivory/rose/blush/cream
============================================================ -->
<?php
$parent_cats = array_filter($categories, fn($c) => empty($c['parent_id']) || $c['parent_id'] == 0);
$parent_cats = array_values($parent_cats);

$sub_cats = db()->query("
    SELECT * FROM categories
    WHERE parent_id IS NOT NULL AND parent_id != 0 AND status = 'active'
    ORDER BY urutan ASC, id ASC
")->fetchAll();

$subs_by_parent = [];
foreach ($sub_cats as $sc) {
    $subs_by_parent[$sc['parent_id']][] = $sc;
}
?>

<style>
/* ════════════════════════════════════════
   LAYANAN SECTION
════════════════════════════════════════ */
#layanan {
  background: var(--ivory);
  position: relative;
  overflow: hidden;
}

/* Ornamen background */
#layanan::before {
  content: '';
  position: absolute;
  top: -120px; right: -120px;
  width: 480px; height: 480px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(242,196,206,.22) 0%, transparent 70%);
  pointer-events: none;
}
#layanan::after {
  content: '';
  position: absolute;
  bottom: -80px; left: -80px;
  width: 360px; height: 360px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(212,137,154,.12) 0%, transparent 70%);
  pointer-events: none;
}

/* ── Section header ── */
.layanan-overline {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-family: 'Jost', sans-serif;
  font-size: 11px;
  font-weight: 600;
  letter-spacing: .22em;
  text-transform: uppercase;
  color: var(--dusty);
  margin-bottom: 16px;
}
.layanan-overline-dot {
  width: 6px; height: 6px;
  border-radius: 50%;
  background: var(--rose);
}
.layanan-title {
  font-family: 'Cormorant Garamond', serif;
  font-size: clamp(2rem, 4vw, 3rem);
  font-weight: 300;
  color: var(--dark);
  line-height: 1.15;
  margin-bottom: 14px;
}
.layanan-title em {
  font-style: italic;
  color: var(--dusty);
}
.layanan-subtitle {
  font-family: 'Jost', sans-serif;
  font-size: 14px;
  color: var(--muted);
  line-height: 1.8;
  max-width: 480px;
}

/* ── Divider ornament ── */
.section-ornament {
  display: flex;
  align-items: center;
  gap: 14px;
  margin: 14px 0 56px;
}
.ornament-line {
  height: 1px;
  width: 56px;
  background: linear-gradient(to right, var(--rose), transparent);
}
.ornament-text {
  color: var(--rose);
  font-size: 13px;
  letter-spacing: .2em;
}

/* ════════════════════════════════════════
   SPLIT ROW — tiap layanan
════════════════════════════════════════ */
.layanan-row {
  display: grid;
  grid-template-columns: 42% 58%;
  gap: 0;
  min-height: 260px;
  border-bottom: 1px solid rgba(212,137,154,.12);
  position: relative;
  overflow: visible;
}
.layanan-row.reversed {
  grid-template-columns: 58% 42%;
}
.layanan-row:first-child { border-top: 1px solid rgba(212,137,154,.12); }

/* Gambar side */
.layanan-img-side {
  position: relative;
  overflow: hidden;
  min-height: 240px;
}
.layanan-img-side img {
  width: 100%; height: 100%;
  object-fit: cover;
  display: block;
  transition: transform .7s cubic-bezier(.25,.46,.45,.94);
}
.layanan-row:hover .layanan-img-side img {
  transform: scale(1.04);
}

/* Overlay foto */
.layanan-img-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(242,196,206,.15) 0%, rgba(44,26,30,.1) 100%);
  transition: opacity .4s;
}
.layanan-row:hover .layanan-img-overlay {
  opacity: 0;
}

/* Nomor urut di sudut foto */
.layanan-img-num {
  position: absolute;
  top: 20px; left: 20px;
  font-family: 'Cormorant Garamond', serif;
  font-size: 11px;
  font-weight: 600;
  letter-spacing: .18em;
  text-transform: uppercase;
  color: rgba(255,255,255,.7);
  background: rgba(44,26,30,.4);
  backdrop-filter: blur(8px);
  padding: 5px 12px;
  border-radius: 100px;
  border: 1px solid rgba(255,255,255,.15);
}

/* Badge kategori di foto */
.layanan-img-badge {
  position: absolute;
  bottom: 20px; right: 20px;
  background: rgba(253,249,244,.92);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(212,137,154,.25);
  border-radius: 12px;
  padding: 8px 14px;
  font-family: 'Cormorant Garamond', serif;
  font-size: 14px;
  font-weight: 600;
  color: var(--dark);
  box-shadow: 0 4px 20px rgba(44,26,30,.12);
}

/* Text side */
.layanan-text-side {
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 28px 40px;
  background: var(--ivory);
  position: relative;
  transition: background .3s;
}
.layanan-row:hover .layanan-text-side {
  background: rgba(250,245,238,.7);
}

/* Reversed row: teks kiri, foto kanan */
.layanan-row.reversed .layanan-img-side { order: 2; }
.layanan-row.reversed .layanan-text-side { order: 1; }

/* Garis aksen kiri di text side */
.layanan-text-side::before {
  content: '';
  position: absolute;
  left: 0; top: 20%; bottom: 20%;
  width: 2px;
  background: linear-gradient(to bottom, transparent, var(--blush), transparent);
  opacity: 0;
  transition: opacity .4s;
}
.layanan-row:hover .layanan-text-side::before { opacity: 1; }
.layanan-row.reversed .layanan-text-side::before {
  left: auto; right: 0;
}

/* Icon */
.layanan-icon {
  font-size: 22px;
  margin-bottom: 10px;
  display: block;
  transition: transform .3s;
}
.layanan-row:hover .layanan-icon { transform: scale(1.1) rotate(-3deg); }

/* Nama layanan */
.layanan-name {
  font-family: 'Cormorant Garamond', serif;
  font-size: clamp(1.3rem, 1.8vw, 1.7rem);
  font-weight: 600;
  color: var(--dark);
  line-height: 1.2;
  margin-bottom: 10px;
}

/* Garis dekoratif bawah nama */
.layanan-name-rule {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 12px;
}
.layanan-name-rule-line {
  height: 1px;
  width: 28px;
  background: var(--rose);
  transition: width .4s;
}
.layanan-row:hover .layanan-name-rule-line { width: 44px; }
.layanan-name-rule-dot {
  width: 3px; height: 3px;
  border-radius: 50%;
  background: var(--blush);
}

/* Deskripsi */
.layanan-desc {
  font-family: 'Jost', sans-serif;
  font-size: 12.5px;
  color: var(--muted);
  line-height: 1.75;
  margin-bottom: 16px;
  max-width: 340px;
}

/* Sub-kategori pills */
.layanan-subs {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  margin-bottom: 18px;
}
.layanan-sub-pill {
  font-family: 'Jost', sans-serif;
  font-size: 10.5px;
  font-weight: 500;
  color: var(--dusty);
  border: 1px solid rgba(212,137,154,.28);
  background: rgba(242,196,206,.1);
  padding: 4px 11px;
  border-radius: 100px;
  text-decoration: none;
  transition: background .2s, border-color .2s, color .2s;
  white-space: nowrap;
}
.layanan-sub-pill:hover {
  background: rgba(242,196,206,.25);
  border-color: var(--rose);
  color: var(--dark);
}

/* CTA link */
.layanan-cta {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  font-family: 'Jost', sans-serif;
  font-size: 11px;
  font-weight: 600;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: var(--dusty);
  text-decoration: none;
  transition: gap .25s, color .2s;
}
.layanan-cta:hover {
  gap: 12px;
  color: var(--dark);
}
.layanan-cta-arrow {
  width: 24px; height: 24px;
  border-radius: 50%;
  border: 1.5px solid var(--rose);
  display: flex; align-items: center; justify-content: center;
  transition: background .25s, border-color .25s;
}
.layanan-cta:hover .layanan-cta-arrow {
  background: var(--rose);
  border-color: var(--rose);
}
.layanan-cta:hover .layanan-cta-arrow svg { color: #fff; }

/* ── Fallback tanpa gambar ── */
.layanan-img-fallback {
  width: 100%; height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, rgba(242,196,206,.3) 0%, rgba(212,137,154,.15) 100%);
}
.layanan-img-fallback-icon {
  font-size: 72px;
  opacity: .35;
  transition: transform .5s, opacity .3s;
}
.layanan-row:hover .layanan-img-fallback-icon {
  transform: scale(1.12);
  opacity: .5;
}

/* ════════════════════════════════════════
   MOBILE
════════════════════════════════════════ */
@media (max-width: 767px) {
  .layanan-row,
  .layanan-row.reversed {
    grid-template-columns: 1fr;
    min-height: auto;
  }
  .layanan-row .layanan-img-side  { order: 1 !important; min-height: 200px; }
  .layanan-row .layanan-text-side { order: 2 !important; padding: 22px 18px 26px; }
  .layanan-text-side::before      { display: none; }
  .layanan-name                   { font-size: 1.4rem; }
}

@media (min-width: 768px) and (max-width: 1023px) {
  .layanan-row,
  .layanan-row.reversed { grid-template-columns: 1fr 1fr; }
  .layanan-text-side { padding: 24px 28px; }
  .layanan-name { font-size: 1.4rem; }
}
</style>

<!-- ════════════════════════════════════════
     LAYANAN SECTION
════════════════════════════════════════ -->
<section id="layanan" class="py-20 relative">

  <div class="relative z-10 max-w-7xl mx-auto px-4">

    <!-- Header -->
    <div class="mb-0">
      <div class="layanan-overline">
        <span class="layanan-overline-dot"></span>
        Apa yang Kami Tawarkan
      </div>
      <h2 class="layanan-title">
        Layanan <em>Spesial</em><br>untuk Setiap Momen
      </h2>
      <p class="layanan-subtitle">
        Kami menyediakan berbagai rangkaian bunga segar berkualitas tinggi, dirancang khusus untuk setiap momen spesial Anda di Tangerang.
      </p>
      <div class="section-ornament">
        <div class="ornament-line"></div>
        <span class="ornament-text">✦ ✦ ✦</span>
        <div class="ornament-line" style="background:linear-gradient(to left,var(--rose),transparent)"></div>
      </div>
    </div>

  </div>

  <!-- ── Split rows — dalam max-width container ── -->
  <div class="relative z-10 max-w-7xl mx-auto px-4">
    <div class="rounded-2xl overflow-hidden border border-[rgba(212,137,154,.15)] shadow-sm">

    <?php foreach ($parent_cats as $i => $cat):
      $reversed  = ($i % 2 !== 0);
      $has_img   = !empty($cat['image']);
      $img_url   = $has_img ? e(imgUrl($cat['image'], 'category')) : '';
      $children  = $subs_by_parent[$cat['id']] ?? [];
      $has_subs  = !empty($children);
      $num       = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
    ?>

    <div class="layanan-row <?= $reversed ? 'reversed' : '' ?>">

      <!-- ── GAMBAR ── -->
      <div class="layanan-img-side">
        <?php if ($has_img): ?>
          <img src="<?= $img_url ?>" alt="<?= e($cat['name']) ?>" loading="lazy">
          <div class="layanan-img-overlay"></div>
          <div class="layanan-img-num"><?= $num ?></div>
          <div class="layanan-img-badge"><?= e($cat['name']) ?></div>
        <?php else: ?>
          <div class="layanan-img-fallback">
            <span class="layanan-img-fallback-icon">
              <?= !empty($cat['icon']) ? e($cat['icon']) : '🌸' ?>
            </span>
          </div>
          <div class="layanan-img-num"><?= $num ?></div>
        <?php endif; ?>
      </div>

      <!-- ── TEKS ── -->
      <div class="layanan-text-side">

        <?php if (!empty($cat['icon'])): ?>
        <span class="layanan-icon"><?= e($cat['icon']) ?></span>
        <?php endif; ?>

        <h3 class="layanan-name"><?= e($cat['name']) ?></h3>

        <div class="layanan-name-rule">
          <div class="layanan-name-rule-line"></div>
          <div class="layanan-name-rule-dot"></div>
        </div>

        <?php if (!empty($cat['description'])): ?>
        <p class="layanan-desc"><?= e($cat['description']) ?></p>
        <?php else: ?>
        <p class="layanan-desc">
          Rangkaian <?= e($cat['name']) ?> kami dirancang dengan penuh perhatian menggunakan bunga-bunga segar pilihan, siap diantar ke seluruh wilayah Tangerang.
        </p>
        <?php endif; ?>

        <!-- Sub-kategori pills -->
        <?php if ($has_subs): ?>
        <div class="layanan-subs">
          <?php foreach ($children as $ch): ?>
          <a href="<?= BASE_URL ?>/<?= e($ch['slug']) ?>/"
             class="layanan-sub-pill">
            <?= e($ch['name']) ?>
          </a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- CTA -->
        <a href="<?= BASE_URL ?>/<?= e($cat['slug']) ?>/"
           class="layanan-cta">
          Lihat Koleksi
          <span class="layanan-cta-arrow">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
          </span>
        </a>

      </div>

    </div><!-- /layanan-row -->
    <?php endforeach; ?>

  </div><!-- /rounded wrapper -->
  </div><!-- /max-width -->

</section><!-- ============================================================
     PRODUK SECTION — Tab Filter + Card Elegan
     Tema: Elegan & Mewah | ivory/rose/blush/cream
============================================================ -->
<?php
/* ── Recursive root finder ── */
function getRootId(int $id, array &$map): int {
    if (!isset($map[$id])) return $id;
    $pid = (int)($map[$id]['parent_id'] ?? 0);
    return $pid === 0 ? $id : getRootId($pid, $map);
}

/* ── Load semua kategori ── */
$catMap = [];
foreach (db()->query("SELECT id,name,slug,parent_id FROM categories WHERE status='active'")->fetchAll() as $c) {
    $catMap[$c['id']] = $c;
}

/* ── Semua produk + root_cat_id ── */
$all_products = [];
foreach (db()->query("
    SELECT p.*, c.name AS cat_name, c.id AS cat_id, c.parent_id AS cat_pid
    FROM products p LEFT JOIN categories c ON p.category_id=c.id
    WHERE p.status='active' ORDER BY p.created_at DESC
")->fetchAll() as $p) {
    $p['root_cat_id'] = getRootId((int)$p['cat_id'], $catMap);
    $all_products[] = $p;
}

/* ── Tab: semua root category, ada produk atau tidak ── */
$tab_cats = [];
foreach ($catMap as $c) {
    if ((int)($c['parent_id'] ?? 0) === 0) {
        $tab_cats[] = $c;
    }
}
usort($tab_cats, fn($a,$b) => $a['id'] <=> $b['id']);

/* ── Sub-kategori per parent ── */
$subsMap = [];
foreach ($catMap as $c) {
    $pid = (int)($c['parent_id'] ?? 0);
    if ($pid === 0) continue;
    $cnt = count(array_filter($all_products, fn($p) => $p['cat_id'] == $c['id']));
    if ($cnt > 0) {
        $c['prod_count'] = $cnt;
        $subsMap[$pid][] = $c;
    }
}

/* ── Count per root ── */
$countByRoot = [];
foreach ($all_products as $p) {
    $countByRoot[$p['root_cat_id']] = ($countByRoot[$p['root_cat_id']] ?? 0) + 1;
}

/* ── Semua tab tampil langsung ── */
$tabsMain  = $tab_cats;
$tabsExtra = [];
$hasExtra  = false;

/* ── Konstanta card: 8 ditampilkan dulu ── */
$CARD_INIT = 8;
?>

<style>
:root {
  --blush: #F2C4CE; --rose: #D4899A; --dusty: #C8778A;
  --cream: #FAF5EE; --ivory: #FDF9F4; --muted: #8C6B72; --dark: #2C1A1E;
}
#produk { background: var(--cream); position: relative; overflow: hidden; }
#produk::before {
  content:''; position:absolute; top:-60px; left:-60px;
  width:320px; height:320px; border-radius:50%;
  background: radial-gradient(circle,rgba(242,196,206,.18) 0%,transparent 70%);
  pointer-events:none;
}

/* ── Header ── */
.produk-overline {
  display:inline-flex; align-items:center; gap:8px;
  font:600 11px/1 'Jost',sans-serif; letter-spacing:.22em;
  text-transform:uppercase; color:var(--dusty); margin-bottom:14px;
}
.produk-overline-dot { width:6px; height:6px; border-radius:50%; background:var(--rose); }
.produk-title {
  font:300 clamp(1.9rem,3.5vw,2.8rem)/1.15 'Cormorant Garamond',serif;
  color:var(--dark); margin-bottom:6px;
}
.produk-title em { font-style:italic; color:var(--dusty); }

/* ── Divider ── */
.produk-divider { display:flex; align-items:center; gap:14px; margin:14px 0 28px; }
.produk-divider-line { height:1px; flex:1; background:linear-gradient(to right,transparent,rgba(212,137,154,.3),transparent); }
.produk-divider-ornament { color:var(--blush); font-size:12px; letter-spacing:.2em; }

/* ════════════════════
   TAB PILLS
════════════════════ */
.tabs-row { display:flex; flex-wrap:wrap; gap:8px; align-items:center; }

/* Container tab extra */
#tabs-extra {
  max-height:0; overflow:hidden;
  transition: max-height .45s cubic-bezier(.4,0,.2,1), opacity .35s ease;
  opacity:0;
}
#tabs-extra.open { max-height:300px; opacity:1; }
#tabs-extra-inner { display:flex; flex-wrap:wrap; gap:8px; padding-top:8px; }

.produk-tab {
  display:inline-flex; align-items:center; gap:5px;
  font:500 12.5px/1 'Jost',sans-serif; letter-spacing:.04em;
  color:var(--muted); background:#fff;
  border:1px solid rgba(212,137,154,.22);
  padding:8px 18px; border-radius:100px;
  cursor:pointer; white-space:nowrap;
  transition:all .2s ease; user-select:none;
}
.produk-tab:hover { border-color:var(--rose); color:var(--dusty); background:rgba(242,196,206,.1); }
.produk-tab.active {
  background:linear-gradient(135deg,var(--blush),var(--dusty));
  border-color:transparent; color:#fff;
  box-shadow:0 4px 14px rgba(200,120,138,.3);
}
.tab-count {
  display:inline-flex; align-items:center; justify-content:center;
  min-width:18px; height:18px; padding:0 4px; border-radius:100px;
  font-size:10px; font-weight:600;
  background:rgba(212,137,154,.15); color:var(--dusty);
  transition:background .2s,color .2s;
}
.produk-tab.active .tab-count { background:rgba(255,255,255,.25); color:#fff; }
.tab-chevron {
  width:12px; height:12px; opacity:.55; flex-shrink:0;
  transition:transform .2s;
}
.produk-tab-wrap.open .tab-chevron { transform:rotate(180deg); }
.produk-tab.active .tab-chevron { opacity:.85; }

.tab-show-more {
  display:inline-flex; align-items:center; gap:6px;
  font:600 12px/1 'Jost',sans-serif; letter-spacing:.03em;
  color:var(--dusty); background:none;
  border:1.5px dashed rgba(212,137,154,.4);
  padding:7px 16px; border-radius:100px;
  cursor:pointer; white-space:nowrap;
  transition:border-color .2s,background .2s,color .2s;
}
.tab-show-more:hover { border-color:var(--rose); background:rgba(242,196,206,.12); color:var(--dark); }
.tab-show-more .sm-chevron { width:12px; height:12px; transition:transform .3s; flex-shrink:0; }
.tab-show-more.open .sm-chevron { transform:rotate(180deg); }

/* Sub-dropdown */
.produk-tab-wrap { position:relative; }
.tab-sub-dd {
  display:none; position:absolute;
  top:calc(100% + 8px); left:0;
  min-width:210px; background:#fff;
  border:1px solid rgba(212,137,154,.2);
  border-radius:16px;
  box-shadow:0 12px 40px rgba(44,26,30,.1);
  padding:7px; z-index:200;
  animation:ddIn .17s ease;
}
@keyframes ddIn { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }
.produk-tab-wrap.open .tab-sub-dd { display:block; }

.tab-sub-item {
  display:flex; align-items:center; justify-content:space-between; gap:8px;
  padding:8px 13px; border-radius:10px;
  font:500 12.5px/1 'Jost',sans-serif;
  color:var(--dark); cursor:pointer;
  background:none; border:none; width:100%; text-align:left;
  transition:background .15s,color .15s; white-space:nowrap;
}
.tab-sub-item:hover { background:rgba(242,196,206,.18); color:var(--dusty); }
.tab-sub-item.active { background:rgba(242,196,206,.1); color:var(--dusty); font-weight:600; }
.sub-count {
  font-size:10px; color:var(--muted);
  background:rgba(212,137,154,.1); padding:2px 7px; border-radius:100px;
}

/* ════════════════════
   PRODUK GRID + CARD
════════════════════ */
@keyframes cardFadeIn { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }

.produk-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; }

/* Wrapper slide-down untuk card extra */
#cards-extra-wrap {
  max-height:0; overflow:hidden;
  transition: max-height .6s cubic-bezier(.4,0,.2,1), opacity .45s ease;
  opacity:0;
}
#cards-extra-wrap.open { max-height:9999px; opacity:1; }
/* Grid di dalam wrapper extra */
#cards-extra-grid {
  display:grid; grid-template-columns:repeat(4,1fr); gap:20px;
  padding-top:20px;
}

.prod-card {
  background:#fff; border-radius:18px; overflow:hidden;
  border:1px solid rgba(212,137,154,.12);
  transition:transform .3s,box-shadow .3s,border-color .3s;
  display:flex; flex-direction:column;
  animation:cardFadeIn .32s ease both;
}
.prod-card:hover { transform:translateY(-5px); box-shadow:0 16px 48px rgba(44,26,30,.1); border-color:rgba(212,137,154,.3); }

.prod-img-wrap { position:relative; aspect-ratio:4/5; overflow:hidden; background:var(--cream); }
.prod-img-wrap img { width:100%; height:100%; object-fit:cover; display:block; transition:transform .6s cubic-bezier(.25,.46,.45,.94); }
.prod-card:hover .prod-img-wrap img { transform:scale(1.05); }

.prod-cat-badge {
  position:absolute; top:12px; left:12px;
  font:600 10px/1 'Jost',sans-serif; letter-spacing:.08em; text-transform:uppercase;
  color:var(--dusty); background:rgba(253,249,244,.92); backdrop-filter:blur(8px);
  border:1px solid rgba(212,137,154,.22); padding:4px 10px; border-radius:100px;
}
.prod-img-overlay { position:absolute; inset:0; background:linear-gradient(to top,rgba(44,26,30,.04),transparent 50%); }

.prod-info { padding:14px 16px 18px; display:flex; flex-direction:column; flex:1; }
.prod-name {
  font:600 15px/1.3 'Cormorant Garamond',serif; color:var(--dark); margin-bottom:6px;
  display:-webkit-box; -webkit-box-orient:vertical; -webkit-line-clamp:2; overflow:hidden;
}
.prod-desc {
  font:400 11.5px/1.65 'Jost',sans-serif; color:var(--muted); margin-bottom:12px; flex:1;
  display:-webkit-box; -webkit-box-orient:vertical; -webkit-line-clamp:2; overflow:hidden;
}
.prod-footer { display:flex; align-items:center; justify-content:space-between; gap:8px; margin-top:auto; }
.prod-price-label { font:500 9px/1 'Jost',sans-serif; letter-spacing:.08em; text-transform:uppercase; color:var(--muted); margin-bottom:2px; }
.prod-price { font:700 17px/1 'Cormorant Garamond',serif; color:var(--dusty); }
.prod-btn {
  display:inline-flex; align-items:center; gap:6px;
  font:600 11px/1 'Jost',sans-serif; letter-spacing:.04em; color:#fff;
  background:linear-gradient(135deg,var(--blush),var(--dusty));
  padding:8px 14px; border-radius:100px; text-decoration:none; flex-shrink:0;
  box-shadow:0 3px 10px rgba(200,120,138,.25);
  transition:transform .2s,box-shadow .2s;
}
.prod-btn:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(200,120,138,.38); color:#fff; text-decoration:none; }

/* Tombol lihat semua / sembunyikan */
.cards-show-more-wrap { text-align:center; margin-top:28px; }
.cards-show-btn {
  display:inline-flex; align-items:center; gap:8px;
  font:600 13px/1 'Jost',sans-serif; letter-spacing:.06em;
  color:var(--dusty); background:#fff;
  border:1.5px solid rgba(212,137,154,.35);
  padding:12px 28px; border-radius:100px;
  cursor:pointer; transition:all .25s ease;
}
.cards-show-btn:hover { border-color:var(--rose); background:rgba(242,196,206,.08); color:var(--dark); box-shadow:0 4px 16px rgba(212,137,154,.15); }
.cards-show-btn .csb-chevron { width:14px; height:14px; transition:transform .3s; flex-shrink:0; }
.cards-show-btn.open .csb-chevron { transform:rotate(180deg); }

/* Empty state */
.produk-empty { grid-column:1/-1; text-align:center; padding:60px 20px; }
.produk-empty-icon { font-size:48px; margin-bottom:14px; opacity:.4; }
.produk-empty-text { font:400 20px/1 'Cormorant Garamond',serif; color:var(--muted); }

/* CTA bawah */
.produk-cta-wrap { text-align:center; margin-top:44px; padding-top:36px; border-top:1px solid rgba(212,137,154,.15); }
.produk-cta-text { font:400 italic 18px/1 'Cormorant Garamond',serif; color:var(--muted); margin-bottom:18px; }
.produk-cta-btn {
  display:inline-flex; align-items:center; gap:9px;
  font:600 13px/1 'Jost',sans-serif; letter-spacing:.06em; color:#fff;
  background:linear-gradient(135deg,var(--blush),var(--dusty));
  padding:13px 28px; border-radius:100px; text-decoration:none;
  box-shadow:0 6px 22px rgba(200,120,138,.3);
  transition:transform .25s,box-shadow .25s;
}
.produk-cta-btn:hover { transform:translateY(-2px); box-shadow:0 12px 32px rgba(200,120,138,.42); color:#fff; text-decoration:none; }

/* Responsive */
@media(max-width:1023px) {
  .produk-grid, #cards-extra-grid { grid-template-columns:repeat(3,1fr); gap:16px; }
}
@media(max-width:767px) {
  .produk-grid, #cards-extra-grid { grid-template-columns:repeat(2,1fr); gap:12px; }
  .prod-info   { padding:12px 12px 14px; }
  .prod-name   { font-size:14px; }
  .prod-price  { font-size:15px; }
  .prod-btn    { padding:7px 11px; font-size:10px; }
  .tabs-row, #tabs-extra-inner { gap:6px; }
  .produk-tab  { font-size:11.5px; padding:7px 14px; }
}
</style>

<section id="produk" class="py-20 relative">
  <div class="relative z-10 max-w-7xl mx-auto px-4">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-10">
      <div>
        <div class="produk-overline"><span class="produk-overline-dot"></span>Koleksi Terbaik Kami</div>
        <h2 class="produk-title">Produk <em>Pilihan</em></h2>
      </div>
      <p style="font:400 13px/1.7 'Jost',sans-serif;color:var(--muted);max-width:280px;text-align:right;padding-bottom:4px">
        Setiap rangkaian dibuat dengan bunga segar pilihan, siap diantar ke seluruh wilayah.
      </p>
    </div>

    <div class="produk-divider">
      <div class="produk-divider-line"></div>
      <span class="produk-divider-ornament">✦ ✦ ✦</span>
      <div class="produk-divider-line"></div>
    </div>

    <!-- ════════════════
         TABS
    ════════════════ -->
    <div class="tabs-row" style="margin-bottom:0">

      <!-- Tab Semua -->
      <div class="produk-tab-wrap">
        <button class="produk-tab active" onclick="filterProduk('semua',this,null)">
          Semua <span class="tab-count"><?= count($all_products) ?></span>
        </button>
      </div>

      <?php foreach ($tabsMain as $tc):
        $rc  = $countByRoot[$tc['id']] ?? 0;
        $sub = $subsMap[$tc['id']] ?? [];
        $hs  = !empty($sub);
        $id  = (int)$tc['id'];
      ?>
      <div class="produk-tab-wrap"<?= $hs ? ' id="wrap-'.$id.'"' : '' ?>>
        <button class="produk-tab" onclick="<?= $hs ? 'toggleSub(event,'.$id.')' : 'filterProduk(\''.$id.'\',this,null)' ?>">
          <?= e($tc['name']) ?> <span class="tab-count"><?= $rc ?></span>
          <?php if($hs): ?>
          <svg class="tab-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
          <?php endif; ?>
        </button>
        <?php if($hs): ?>
        <div class="tab-sub-dd" id="subdrop-<?= $id ?>">
          <button class="tab-sub-item" onclick="filterProduk('root-<?= $id ?>',this,<?= $id ?>)">
            Semua <?= e($tc['name']) ?><span class="sub-count"><?= $rc ?></span>
          </button>
          <hr style="border:none;border-top:1px solid rgba(212,137,154,.1);margin:5px 4px">
          <?php foreach($sub as $ch): ?>
          <button class="tab-sub-item" onclick="filterProduk('<?= $ch['id'] ?>',this,<?= $id ?>)">
            <?= e($ch['name']) ?><span class="sub-count"><?= $ch['prod_count'] ?></span>
          </button>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>

      <?php if($hasExtra): ?>
      <button class="tab-show-more" id="btn-show-more" onclick="toggleTabsExtra(this)">
        +<?= count($tabsExtra) ?> kategori lainnya
        <svg class="sm-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
      </button>
      <?php endif; ?>
    </div>

    <?php if($hasExtra): ?>
    <div id="tabs-extra">
      <div id="tabs-extra-inner">
        <?php foreach($tabsExtra as $tc):
          $rc  = $countByRoot[$tc['id']] ?? 0;
          $sub = $subsMap[$tc['id']] ?? [];
          $hs  = !empty($sub);
          $id  = (int)$tc['id'];
        ?>
        <div class="produk-tab-wrap"<?= $hs ? ' id="wrap-'.$id.'"' : '' ?>>
          <button class="produk-tab" onclick="<?= $hs ? 'toggleSub(event,'.$id.')' : 'filterProduk(\''.$id.'\',this,null)' ?>">
            <?= e($tc['name']) ?> <span class="tab-count"><?= $rc ?></span>
            <?php if($hs): ?>
            <svg class="tab-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            <?php endif; ?>
          </button>
          <?php if($hs): ?>
          <div class="tab-sub-dd" id="subdrop-<?= $id ?>">
            <button class="tab-sub-item" onclick="filterProduk('root-<?= $id ?>',this,<?= $id ?>)">
              Semua <?= e($tc['name']) ?><span class="sub-count"><?= $rc ?></span>
            </button>
            <hr style="border:none;border-top:1px solid rgba(212,137,154,.1);margin:5px 4px">
            <?php foreach($sub as $ch): ?>
            <button class="tab-sub-item" onclick="filterProduk('<?= $ch['id'] ?>',this,<?= $id ?>)">
              <?= e($ch['name']) ?><span class="sub-count"><?= $ch['prod_count'] ?></span>
            </button>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- ════════════════
         PRODUK GRID
    ════════════════ -->

    <!-- 8 card pertama — selalu tampil -->
    <div class="produk-grid" id="produk-grid" style="margin-top:28px">
      <?php
      $init_shown = array_slice($all_products, 0, $CARD_INIT);
      foreach($init_shown as $i => $prod):
        $img     = imgUrl($prod['image'], 'product');
        $wa_text = urlencode("Halo, saya tertarik memesan *{$prod['name']}* seharga ".rupiah($prod['price']).". Apakah masih tersedia?");
      ?>
      <div class="prod-card"
           data-cat="<?= (int)$prod['cat_id'] ?>"
           data-root="<?= (int)$prod['root_cat_id'] ?>"
           style="animation-delay:<?= ($i % 4) * .06 ?>s">
        <div class="prod-img-wrap">
          <img src="<?= e($img) ?>" alt="<?= e($prod['name']) ?>" loading="lazy">
          <div class="prod-img-overlay"></div>
          <?php if(!empty($prod['cat_name'])): ?>
          <span class="prod-cat-badge"><?= e($prod['cat_name']) ?></span>
          <?php endif; ?>
        </div>
        <div class="prod-info">
          <h3 class="prod-name"><?= e($prod['name']) ?></h3>
          <p class="prod-desc"><?= !empty($prod['description']) ? e($prod['description']) : 'Bunga segar berkualitas tinggi, siap diantar ke seluruh wilayah.' ?></p>
          <div class="prod-footer">
            <div>
              <div class="prod-price-label">Mulai dari</div>
              <div class="prod-price"><?= rupiah($prod['price']) ?></div>
            </div>
            <a href="<?= e($wa_url) ?>?text=<?= $wa_text ?>" target="_blank" class="prod-btn" onclick="event.stopPropagation()">
              <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/></svg>
              Pesan
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <?php
    $extra_products = array_slice($all_products, $CARD_INIT);
    if(!empty($extra_products)):
    ?>
    <!-- Card extra — slide down saat tombol diklik -->
    <div id="cards-extra-wrap">
      <div id="cards-extra-grid">
        <?php foreach($extra_products as $i => $prod):
          $img     = imgUrl($prod['image'], 'product');
          $wa_text = urlencode("Halo, saya tertarik memesan *{$prod['name']}* seharga ".rupiah($prod['price']).". Apakah masih tersedia?");
        ?>
        <div class="prod-card"
             data-cat="<?= (int)$prod['cat_id'] ?>"
             data-root="<?= (int)$prod['root_cat_id'] ?>"
             style="animation-delay:<?= ($i % 4) * .06 ?>s">
          <div class="prod-img-wrap">
            <img src="<?= e($img) ?>" alt="<?= e($prod['name']) ?>" loading="lazy">
            <div class="prod-img-overlay"></div>
            <?php if(!empty($prod['cat_name'])): ?>
            <span class="prod-cat-badge"><?= e($prod['cat_name']) ?></span>
            <?php endif; ?>
          </div>
          <div class="prod-info">
            <h3 class="prod-name"><?= e($prod['name']) ?></h3>
            <p class="prod-desc"><?= !empty($prod['description']) ? e($prod['description']) : 'Bunga segar berkualitas tinggi, siap diantar ke seluruh wilayah.' ?></p>
            <div class="prod-footer">
              <div>
                <div class="prod-price-label">Mulai dari</div>
                <div class="prod-price"><?= rupiah($prod['price']) ?></div>
              </div>
              <a href="<?= e($wa_url) ?>?text=<?= $wa_text ?>" target="_blank" class="prod-btn" onclick="event.stopPropagation()">
                <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/></svg>
                Pesan
              </a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Tombol lihat semua -->
    <div class="cards-show-more-wrap" id="cards-show-more-wrap">
      <button class="cards-show-btn" id="cards-show-btn" onclick="toggleCardsExtra(this)">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        Lihat Semua <?= count($all_products) ?> Produk
        <svg class="csb-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
      </button>
    </div>
    <?php endif; ?>

    <!-- CTA -->
    <div class="produk-cta-wrap">
      <p class="produk-cta-text">Tidak menemukan yang kamu cari? Konsultasi langsung dengan kami 🌸</p>
      <a href="<?= e($wa_url) ?>?text=<?= urlencode('Halo, saya ingin melihat katalog bunga lengkap.') ?>"
         target="_blank" class="produk-cta-btn">
        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/></svg>
        Lihat Katalog Lengkap
      </a>
    </div>

  </div>
</section>

<script>
/* ─────────────────────────────────────────────
   TOGGLE TABS EXTRA (kategori lebih dari 8)
───────────────────────────────────────────── */
function toggleTabsExtra(btn) {
  var el  = document.getElementById('tabs-extra');
  var now = el.classList.contains('open');
  el.classList.toggle('open', !now);
  btn.classList.toggle('open', !now);
  btn.childNodes[0].textContent = now
    ? '+<?= count($tabsExtra) ?> kategori lainnya '
    : 'Sembunyikan ';
}

/* ─────────────────────────────────────────────
   TOGGLE CARDS EXTRA (slide down / slide up)
───────────────────────────────────────────── */
function toggleCardsExtra(btn) {
  var wrap = document.getElementById('cards-extra-wrap');
  var now  = wrap.classList.contains('open');

  if (!now) {
    /* Buka: trigger animasi card satu per satu */
    wrap.classList.add('open');
    btn.classList.add('open');
    btn.innerHTML = '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg> Sembunyikan <svg class="csb-chevron open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>';

    /* Re-trigger animasi kartu yang baru muncul */
    document.querySelectorAll('#cards-extra-grid .prod-card').forEach(function(card, i) {
      card.style.animation = 'none';
      card.offsetWidth; /* reflow */
      card.style.animation = 'cardFadeIn .32s ease ' + (i % 4 * 0.06) + 's both';
    });

  } else {
    /* Tutup: scroll naik dulu, lalu collapse */
    document.getElementById('produk-grid').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    setTimeout(function() {
      wrap.classList.remove('open');
    }, 300);
    btn.classList.remove('open');
    btn.innerHTML = '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg> Lihat Semua <?= count($all_products) ?> Produk <svg class="csb-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>';
  }
}

/* ─────────────────────────────────────────────
   TOGGLE SUB-DROPDOWN
───────────────────────────────────────────── */
function toggleSub(e, id) {
  e.stopPropagation();
  var wrap = document.getElementById('wrap-' + id);
  var open = wrap.classList.contains('open');
  document.querySelectorAll('.produk-tab-wrap.open').forEach(function(w){ w.classList.remove('open'); });
  if (!open) wrap.classList.add('open');
}
document.addEventListener('click', function() {
  document.querySelectorAll('.produk-tab-wrap.open').forEach(function(w){ w.classList.remove('open'); });
});

/* ─────────────────────────────────────────────
   FILTER PRODUK
   Saat filter aktif: semua card (init + extra) ikut difilter.
   Extra wrap dibuka otomatis jika ada hasil di sana.
───────────────────────────────────────────── */
function filterProduk(catId, btn, parentId) {
  /* Reset semua tab */
  document.querySelectorAll('.produk-tab').forEach(function(t){
    t.classList.remove('active');
  });
  document.querySelectorAll('.tab-sub-item').forEach(function(s){ s.classList.remove('active'); });

  if (btn.classList.contains('produk-tab')) {
    btn.classList.add('active');
  } else {
    btn.classList.add('active');
    if (parentId) {
      var pw = document.getElementById('wrap-' + parentId);
      if (pw) { var pt = pw.querySelector('.produk-tab'); if (pt) pt.classList.add('active'); }
    }
  }
  document.querySelectorAll('.produk-tab-wrap.open').forEach(function(w){ w.classList.remove('open'); });

  /* Kumpulkan SEMUA card (init + extra) */
  var allCards = Array.from(document.querySelectorAll('.prod-card'));
  var delay    = 0;
  var hasInExtra = false;

  allCards.forEach(function(card) {
    var match = false;
    if      (catId === 'semua')              match = true;
    else if (catId.indexOf('root-') === 0)   match = card.dataset.root === catId.replace('root-','');
    else                                     match = card.dataset.cat  === String(catId);

    if (match) {
      card.style.display = 'flex';
      card.style.animation = 'none';
      card.offsetWidth;
      card.style.animation = 'cardFadeIn .3s ease ' + delay + 's both';
      delay += 0.05;

      /* Cek apakah card ini ada di dalam extra wrap */
      if (card.closest('#cards-extra-grid')) hasInExtra = true;
    } else {
      card.style.display = 'none';
    }
  });

  /* Jika ada hasil di extra, buka extra wrap otomatis */
  var extraWrap = document.getElementById('cards-extra-wrap');
  var showBtn   = document.getElementById('cards-show-btn');
  if (extraWrap) {
    if (hasInExtra || catId === 'semua') {
      extraWrap.classList.add('open');
      if (showBtn) showBtn.style.display = 'none'; /* sembunyikan tombol saat filter aktif */
    }
    /* Kalau balik ke semua, kembalikan tombol */
    if (catId === 'semua') {
      extraWrap.classList.remove('open');
      if (showBtn) showBtn.style.display = '';
    }
  }

  /* Empty state */
  var grid = document.getElementById('produk-grid');
  var ex   = grid.querySelector('.produk-empty');
  if (ex) ex.remove();
  var vis = allCards.filter(function(c){ return c.style.display !== 'none'; });
  if (vis.length === 0) {
    grid.insertAdjacentHTML('beforeend',
      '<div class="produk-empty"><div class="produk-empty-icon">🌸</div><p class="produk-empty-text">Belum ada produk di kategori ini</p></div>'
    );
  }
}
</script>
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
//lain
function toggleFaq(btn) {
  const answer = btn.nextElementSibling;
  const icon   = btn.querySelector('.faq-icon');
  answer.classList.toggle('hidden');
  icon.style.transform = answer.classList.contains('hidden') ? '' : 'rotate(180deg)';
}



</script>