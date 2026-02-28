
<?php
/* ================================================================
   AREA PENGIRIMAN — Pure Card Grid
   Tema: ivory / rose / blush / cream
================================================================ */
?>

<style>
#area {
  --blush: #F2C4CE; --rose: #D4899A; --dusty: #C8778A;
  --ivory: #FDF9F4; --soft: #F7EEF0;
  --muted: #8C6B72; --dark: #2C1A1E;
}
#area { background: var(--soft); overflow: hidden; position: relative; }
#area::before {
  content:''; position:absolute; border-radius:50%; pointer-events:none;
  width:500px; height:500px; top:-100px; right:-100px;
  background:radial-gradient(circle,rgba(242,196,206,.22) 0%,transparent 70%);
  filter:blur(60px);
}
#area::after {
  content:''; position:absolute; border-radius:50%; pointer-events:none;
  width:380px; height:380px; bottom:-80px; left:-60px;
  background:radial-gradient(circle,rgba(212,137,154,.14) 0%,transparent 70%);
  filter:blur(50px);
}

/* ── Shimmer line ── */
@keyframes shimmer-x {
  0%   { background-position: -200% center; }
  100% { background-position:  200% center; }
}
.area-shimmer {
  height:1px;
  background:linear-gradient(90deg,transparent,var(--blush),var(--rose),var(--blush),transparent);
  background-size:200% auto;
  animation:shimmer-x 3.5s linear infinite;
}

/* ── Header ── */
.area-overline {
  display:inline-flex; align-items:center; gap:8px;
  font:600 11px/1 'Jost',sans-serif; letter-spacing:.22em;
  text-transform:uppercase; color:var(--dusty); margin-bottom:16px;
}
.area-overline-dot { width:5px; height:5px; border-radius:50%; background:var(--rose); }
.area-title {
  font:300 clamp(2rem,4vw,3rem)/1.1 'Cormorant Garamond',serif;
  color:var(--dark);
}
.area-title em { font-style:italic; color:var(--dusty); }

/* ════════════════════
   STATS ROW
════════════════════ */
.area-stats-row {
  display:flex; align-items:center; justify-content:center;
  gap:0; background:#fff;
  border:1px solid rgba(212,137,154,.15);
  border-radius:20px; overflow:hidden;
  margin-bottom:48px;
}
.area-stat {
  flex:1; padding:20px 12px; text-align:center;
  position:relative; transition:background .2s;
}
.area-stat:hover { background:rgba(242,196,206,.1); }
.area-stat + .area-stat::before {
  content:''; position:absolute; left:0; top:20%; bottom:20%;
  width:1px; background:rgba(212,137,154,.15);
}
.area-stat-num {
  font:700 1.7rem/1 'Cormorant Garamond',serif; color:var(--dusty);
}
.area-stat-lbl {
  font:600 9.5px/1 'Jost',sans-serif; letter-spacing:.12em;
  text-transform:uppercase; color:var(--muted); margin-top:5px; display:block;
}

/* ════════════════════
   LOCATION CARDS
════════════════════ */
.area-grid {
  display:grid;
  grid-template-columns: repeat(4, 1fr);
  gap:14px;
}

.loc-card {
  background:#fff;
  border:1px solid rgba(212,137,154,.15);
  border-radius:20px;
  padding:20px;
  text-decoration:none;
  display:flex; flex-direction:column; gap:10px;
  position:relative; overflow:hidden;
  transition:transform .3s ease, box-shadow .3s ease, border-color .3s ease;
}
/* Aksen garis atas tersembunyi */
.loc-card::before {
  content:'';
  position:absolute; top:0; left:10%; right:10%; height:2px;
  background:linear-gradient(to right, var(--blush), var(--dusty));
  border-radius:0 0 4px 4px;
  transform:scaleX(0);
  transform-origin:center;
  transition:transform .35s ease;
}
/* Blob lembut di pojok */
.loc-card::after {
  content:'';
  position:absolute; bottom:-30px; right:-30px;
  width:100px; height:100px; border-radius:50%;
  background:radial-gradient(circle, rgba(242,196,206,.2) 0%, transparent 70%);
  transition:transform .4s ease;
  pointer-events:none;
}
.loc-card:hover {
  transform:translateY(-5px);
  box-shadow:0 16px 44px rgba(44,26,30,.1);
  border-color:rgba(212,137,154,.35);
  text-decoration:none;
}
.loc-card:hover::before { transform:scaleX(1); }
.loc-card:hover::after  { transform:scale(1.6); }

/* Icon pin */
.loc-icon {
  width:40px; height:40px; border-radius:14px; flex-shrink:0;
  background:linear-gradient(135deg, rgba(242,196,206,.4), rgba(212,137,154,.15));
  border:1px solid rgba(212,137,154,.2);
  display:flex; align-items:center; justify-content:center;
  font-size:18px;
  transition:transform .3s ease, background .3s ease;
}
.loc-card:hover .loc-icon {
  transform:scale(1.1) rotate(-5deg);
  background:linear-gradient(135deg, var(--blush), rgba(212,137,154,.3));
}

/* Nama */
.loc-name {
  font:700 14px/1.3 'Cormorant Garamond',serif;
  color:var(--dark);
  transition:color .2s;
}
.loc-card:hover .loc-name { color:var(--dusty); }

/* Sub teks */
.loc-sub {
  font:400 11.5px/1.6 'Jost',sans-serif;
  color:var(--muted); flex:1;
}

/* Badge estimasi */
.loc-badge {
  display:inline-flex; align-items:center; gap:5px;
  background:rgba(242,196,206,.2);
  border:1px solid rgba(212,137,154,.2);
  border-radius:100px; padding:4px 10px;
  font:600 10px/1 'Jost',sans-serif;
  color:var(--dusty); width:fit-content;
  transition:background .2s;
}
.loc-card:hover .loc-badge {
  background:rgba(242,196,206,.4);
}

/* CTA link di bawah card */
.loc-cta {
  display:flex; align-items:center; gap:5px;
  font:600 11px/1 'Jost',sans-serif; letter-spacing:.04em;
  color:var(--rose); margin-top:2px;
  transition:gap .2s, color .2s;
}
.loc-card:hover .loc-cta { gap:8px; color:var(--dusty); }
.loc-cta svg { transition:transform .2s; }
.loc-card:hover .loc-cta svg { transform:translateX(3px); }

/* ── Footer CTA ── */
.area-footer { text-align:center; margin-top:48px; }
.area-footer p {
  font:400 italic 1.15rem/1.5 'Cormorant Garamond',serif;
  color:var(--muted); margin-bottom:18px;
}
.area-footer-btn {
  display:inline-flex; align-items:center; gap:8px;
  font:700 13px/1 'Jost',sans-serif; letter-spacing:.05em;
  color:#fff; text-decoration:none;
  background:linear-gradient(135deg, var(--blush), var(--dusty));
  padding:13px 28px; border-radius:100px;
  box-shadow:0 5px 18px rgba(200,119,138,.3);
  transition:transform .25s, box-shadow .25s;
}
.area-footer-btn:hover {
  transform:translateY(-2px);
  box-shadow:0 12px 30px rgba(200,119,138,.42);
  color:#fff; text-decoration:none;
}

/* ── Responsive ── */
@media (max-width:1023px) { .area-grid { grid-template-columns:repeat(3,1fr); } }
@media (max-width:767px)  { .area-grid { grid-template-columns:repeat(2,1fr); } .loc-card { padding:16px; } }
@media (max-width:479px)  { .area-grid { grid-template-columns:1fr 1fr; gap:10px; } }
</style>

<section id="area" class="py-20">
<div class="area-shimmer"></div>

<div class="relative z-10 max-w-6xl mx-auto px-4">

  <!-- Header -->
  <div class="text-center mb-10">
    <div class="area-overline justify-center">
      <span class="area-overline-dot"></span>
      Jangkauan Layanan
    </div>
    <h2 class="area-title">
      Kami Hadir di <em>Seluruh Tangerang</em>
    </h2>
    <p class="mt-3 max-w-md mx-auto" style="font:400 14px/1.7 'Jost',sans-serif; color:var(--muted);">
      Pengiriman bunga ke seluruh wilayah — cepat, aman, dan tepat waktu.
    </p>
  </div>

  <!-- Stats row -->
  <div class="area-stats-row max-w-lg mx-auto">
    <div class="area-stat">
      <div class="area-stat-num"><?= count($locations) ?>+</div>
      <span class="area-stat-lbl">Wilayah</span>
    </div>
    <div class="area-stat">
      <div class="area-stat-num">2–4<span style="font-size:1rem">Jam</span></div>
      <span class="area-stat-lbl">Estimasi Kirim</span>
    </div>
    <div class="area-stat">
      <div class="area-stat-num">24/7</div>
      <span class="area-stat-lbl">Siap Melayani</span>
    </div>
  </div>

  <!-- Location Cards — auto dari database -->
  <div class="area-grid">
    <?php
    $loc_flowers = ['🌸','🌺','🌷','🌼','🪷','🌹','💐','🌻'];
    foreach ($locations as $idx => $loc):
      $flower = $loc_flowers[$idx % count($loc_flowers)];
      $wa_text = urlencode('Halo, saya ingin pesan bunga dengan pengiriman ke ' . $loc['name'] . '. Apakah bisa?');
    ?>
    <a href="<?= BASE_URL ?>/<?= e($loc['slug']) ?>/"
       class="loc-card">

      <div class="flex items-center gap-3">
        <div class="loc-icon"><?= $flower ?></div>
        <div class="loc-name"><?= e($loc['name']) ?></div>
      </div>

      <?php if (!empty($loc['address'])): ?>
      <div class="loc-sub"><?= e($loc['address']) ?></div>
      <?php else: ?>
      <div class="loc-sub">Layanan pengiriman bunga segar tersedia di area ini.</div>
      <?php endif; ?>

      <div class="loc-badge">
        <span>⚡</span> Kirim 2–4 Jam
      </div>

      <div class="loc-cta">
        Lihat layanan
        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
        </svg>
      </div>

    </a>
    <?php endforeach; ?>
  </div>

  <!-- Footer -->
  <div class="area-footer">
    <p>Tidak menemukan area Anda? Kami tetap bisa bantu! 🌸</p>
    <a href="<?= e($wa_url) ?>?text=<?= urlencode('Halo, apakah ada layanan pengiriman ke area saya?') ?>"
       target="_blank" class="area-footer-btn">
      <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
        <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
      </svg>
      Tanya via WhatsApp
    </a>
  </div>

</div>
</section>