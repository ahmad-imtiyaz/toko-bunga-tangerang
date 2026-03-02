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

<!-- perlu di ganti mode terang mode gelap layar harus tetep ikut mode terang -->