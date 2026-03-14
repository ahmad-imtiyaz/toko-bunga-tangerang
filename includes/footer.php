<?php
$wa_url  = setting('whatsapp_url');
$wa_msg  = urlencode('Halo, saya ingin memesan bunga dari Toko Bunga Tangerang. Mohon info lebih lanjut.');
$wa_full = $wa_url . '?text=' . $wa_msg;
$cats    = db()->query("SELECT name, slug FROM categories WHERE status='active' ORDER BY id LIMIT 10")->fetchAll();
$locs    = db()->query("SELECT name, slug FROM locations WHERE status='active' ORDER BY id")->fetchAll();
?>

<style>
#site-footer {
  --blush: #F2C4CE;
  --rose:  #D4899A;
  --dusty: #C8778A;
  --dark:  #2C1A1E;
  --muted: #8C6B72;
  --fg:    rgba(44,26,30,.55);
  --border:rgba(212,137,154,.15);
}

@keyframes footer-shimmer {
  0%   { background-position: -200% center; }
  100% { background-position:  200% center; }
}
.footer-shimmer {
  background: linear-gradient(90deg,
    transparent 0%,
    rgba(212,137,154,.55) 40%,
    rgba(242,196,206,1)   50%,
    rgba(212,137,154,.55) 60%,
    transparent 100%
  );
  background-size: 200% auto;
  animation: footer-shimmer 4s linear infinite;
  height: 2px; width: 100%;
}

.footer-social {
  width: 36px; height: 36px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  border: 1.5px solid var(--border);
  background: rgba(212,137,154,.08);
  transition: all .25s ease;
  color: var(--rose);
}
.footer-social:hover {
  background: linear-gradient(135deg, var(--rose), var(--dusty));
  border-color: var(--rose);
  color: #fff;
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(212,137,154,.3);
}

.footer-link {
  color: var(--fg);
  font-size: 13.5px;
  text-decoration: none;
  display: flex; align-items: center; gap: 7px;
  transition: color .2s ease, padding-left .2s ease;
}
.footer-link:hover { color: var(--rose); padding-left: 4px; }
.footer-link .arrow { color: var(--blush); font-size: 11px; transition: color .2s ease; }
.footer-link:hover .arrow { color: var(--rose); }

.footer-heading {
  font-family: 'Playfair Display', Georgia, serif;
  font-weight: 700;
  font-size: 15px;
  color: var(--dark);
  margin-bottom: 16px;
  padding-bottom: 10px;
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center; gap: 8px;
}
.footer-heading::after {
  content: '';
  flex: 1;
  height: 1px;
  background: linear-gradient(90deg, var(--blush), transparent);
  margin-left: 4px;
}

.footer-contact-item {
  display: flex; gap: 10px; align-items: flex-start;
  font-size: 13px; color: var(--fg);
}
.footer-contact-icon {
  width: 28px; height: 28px;
  border-radius: 8px;
  background: rgba(212,137,154,.1);
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; flex-shrink: 0; margin-top: 1px;
}

.footer-wa-btn {
  display: inline-flex; align-items: center; gap: 8px;
  background: linear-gradient(135deg, var(--rose), var(--dusty));
  color: #fff; font-weight: 700; font-size: 13px;
  padding: 11px 22px; border-radius: 100px;
  text-decoration: none;
  box-shadow: 0 6px 20px rgba(212,137,154,.35);
  transition: transform .25s ease, box-shadow .25s ease;
}
.footer-wa-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 28px rgba(212,137,154,.45);
}

@keyframes footer-petal {
  0%   { transform: translateY(0) rotate(0deg); opacity: 0; }
  8%   { opacity: .2; }
  92%  { opacity: .08; }
  100% { transform: translateY(-80px) rotate(40deg); opacity: 0; }
}
.footer-petal {
  position: absolute; pointer-events: none;
  animation: footer-petal 9s ease-in-out infinite;
}

.sticky-wa {
  position: fixed; bottom: 20px; right: 20px; z-index: 50;
  display: flex; align-items: center; gap: 10px;
  background: linear-gradient(135deg, var(--rose), var(--dusty));
  color: #fff; font-weight: 700; font-size: 13px;
  padding: 12px 20px; border-radius: 100px;
  text-decoration: none;
  box-shadow: 0 8px 28px rgba(212,137,154,.45);
  transition: transform .25s ease, box-shadow .25s ease;
}
.sticky-wa:hover {
  transform: translateY(-3px);
  box-shadow: 0 14px 36px rgba(212,137,154,.55);
}
.sticky-wa-ping {
  position: absolute; top: -3px; right: -3px;
  width: 12px; height: 12px; border-radius: 50%;
  background: #F87171;
}
.sticky-wa-ping::before {
  content: '';
  position: absolute; inset: 0;
  border-radius: 50%;
  background: #F87171;
  animation: wa-ping 1.5s ease-out infinite;
}
@keyframes wa-ping {
  0%   { transform: scale(1); opacity: .8; }
  100% { transform: scale(2.2); opacity: 0; }
}

/* ================================================================
   AREA PENGIRIMAN SLIDER — tema rose/blush (Tangerang)
   ================================================================ */
.fas-badge-rose {
  font-size: 11px;
  background: rgba(212,137,154,.12);
  color: var(--dusty);
  border: 0.5px solid rgba(212,137,154,.3);
  border-radius: 20px;
  padding: 2px 10px;
  white-space: nowrap;
  font-weight: 600;
}
.fas-viewport {
  width: 100%;
  overflow: hidden;
}
.fas-track-rose {
  display: flex;
  flex-direction: row;
  width: 100%;
  transition: transform .38s cubic-bezier(.4,0,.2,1);
  will-change: transform;
}
.fas-slide-rose {
  min-width: 100%;
  width: 100%;
  flex-shrink: 0;
  display: flex;
  flex-direction: column;
  gap: 5px;
  box-sizing: border-box;
}
.fas-item-rose { display: none !important; }
.fas-link-rose {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 7px 10px;
  background: rgba(212,137,154,.06);
  border: 0.5px solid rgba(212,137,154,.18);
  border-radius: 7px;
  text-decoration: none;
  transition: background .18s, border-color .18s;
  width: 100%;
  box-sizing: border-box;
}
.fas-link-rose:hover,
.fas-link-rose:active {
  background: rgba(212,137,154,.14);
  border-color: rgba(212,137,154,.45);
}
.fas-dot-rose {
  width: 7px;
  height: 7px;
  min-width: 7px;
  border-radius: 50%;
  background: var(--rose);
  flex-shrink: 0;
}
.fas-link-rose-name {
  font-size: 12px;
  color: var(--fg);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.fas-controls-rose {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-top: 12px;
}
.fas-dots-rose { display: flex; gap: 5px; align-items: center; flex-wrap: wrap; }
.fas-dot-btn-rose {
  width: 6px;
  height: 6px;
  min-width: 6px;
  border-radius: 3px;
  background: rgba(212,137,154,.25);
  cursor: pointer;
  transition: background .2s, width .25s;
  border: none;
  padding: 0;
}
.fas-dot-btn-rose.active {
  width: 18px;
  background: var(--rose);
}
.fas-nav-rose { display: flex; align-items: center; gap: 6px; }
.fas-page-lbl-rose {
  font-size: 11px;
  color: var(--muted);
  min-width: 30px;
  text-align: center;
}
.fas-btn-rose {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  border: 1px solid rgba(212,137,154,.3);
  background: rgba(212,137,154,.08);
  color: var(--rose);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background .18s, border-color .18s;
  padding: 0;
  flex-shrink: 0;
}
.fas-btn-rose:hover  { background: rgba(212,137,154,.18); border-color: var(--rose); }
.fas-btn-rose:disabled { opacity: .3; cursor: default; }
.fas-hint-rose {
  display: none;
  font-size: 10px;
  color: var(--muted);
  text-align: center;
  margin-top: 6px;
  opacity: .6;
}
@media (max-width: 767px) {
  .fas-btn-rose  { width: 34px; height: 34px; }
  .fas-hint-rose { display: block; }
}
</style>

<!-- ══════════════════════════════════════════
     FOOTER
══════════════════════════════════════════ -->
<footer id="site-footer" class="relative overflow-hidden pt-16 pb-8 mt-0"
        style="background: #FDF9F4; border-top: 1px solid rgba(212,137,154,.15);">

  <!-- Shimmer top line -->
  <div class="absolute top-0 left-0 w-full">
    <div class="footer-shimmer"></div>
  </div>

  <!-- Floating bg petals -->
  <div id="footer-petals" class="absolute inset-0 pointer-events-none overflow-hidden"></div>

  <!-- Blob bg -->
  <div class="absolute bottom-0 left-0 w-72 h-72 rounded-full pointer-events-none"
       style="background: radial-gradient(circle, rgba(242,196,206,.18) 0%, transparent 70%); filter: blur(60px);"></div>
  <div class="absolute top-0 right-0 w-64 h-64 rounded-full pointer-events-none"
       style="background: radial-gradient(circle, rgba(212,137,154,.1) 0%, transparent 70%); filter: blur(55px);"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">

      <!-- ── Brand ── -->
      <div class="lg:col-span-1">
        <div class="flex items-center gap-3 mb-5 group">
          <div class="w-11 h-11 rounded-full overflow-hidden flex-shrink-0 transition duration-300 group-hover:scale-110"
               style="box-shadow: 0 4px 16px rgba(212,137,154,.3); border: 2px solid rgba(212,137,154,.25);">
            <img src="<?= BASE_URL ?>/assets/images/icon.png" alt="Logo"
                 class="w-full h-full object-cover transition duration-500 group-hover:rotate-6">
          </div>
          <div class="font-serif font-bold text-lg leading-tight transition duration-300 group-hover:scale-105"
               style="color: var(--dark);">
            <?= e(setting('site_name')) ?>
          </div>
        </div>

        <p class="text-[13.5px] leading-relaxed mb-5" style="color: var(--fg);">
          <?= e(setting('footer_text')) ?>
        </p>

        <div class="flex gap-1 mb-5 text-base select-none" style="opacity:.45;">
          🌸 🌺 🌷 🌼
        </div>

        <div class="flex gap-2.5">
          <a href="<?= e($wa_full) ?>" target="_blank" class="footer-social" title="WhatsApp">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
              <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
            </svg>
          </a>
        </div>
      </div>

      <!-- ── Layanan ── -->
      <div>
        <div class="footer-heading">
          <span>🌸</span> Layanan Kami
        </div>
        <ul class="space-y-2.5">
          <?php foreach ($cats as $cat): ?>
          <li>
            <a href="<?= BASE_URL ?>/<?= e($cat['slug']) ?>/" class="footer-link">
              <span class="arrow">›</span> <?= e($cat['name']) ?>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- ── Area Pengiriman — SLIDER ── -->
      <div>
        <div class="footer-heading" style="justify-content: space-between;">
          <span style="display:flex;align-items:center;gap:8px;">
            <span>📍</span> Area Pengiriman
          </span>
          <span class="fas-badge-rose"><?= count($locs) ?> kota</span>
        </div>

        <div class="fas-viewport">
          <div class="fas-track-rose" id="fasTrack">
            <?php foreach ($locs as $loc): ?>
            <span class="fas-item-rose"
                  data-name="<?= e($loc['name']) ?>"
                  data-href="<?= BASE_URL ?>/<?= e($loc['slug']) ?>/"></span>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="fas-controls-rose">
          <div class="fas-dots-rose" id="fasDots"></div>
          <div class="fas-nav-rose">
            <span class="fas-page-lbl-rose" id="fasLbl"></span>
            <button class="fas-btn-rose" id="fasPrev" aria-label="Sebelumnya" disabled>
              <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M8 2L4 6l4 4"/></svg>
            </button>
            <button class="fas-btn-rose" id="fasNext" aria-label="Berikutnya">
              <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 2l4 4-4 4"/></svg>
            </button>
          </div>
        </div>
        <p class="fas-hint-rose">Geser untuk lihat area lainnya</p>
      </div>

      <!-- ── Kontak ── -->
      <div>
        <div class="footer-heading">
          <span>💌</span> Hubungi Kami
        </div>
        <ul class="space-y-3 mb-5">
          <li class="footer-contact-item">
            <div class="footer-contact-icon">📍</div>
            <span><?= e(setting('address')) ?></span>
          </li>
          <li class="footer-contact-item">
            <div class="footer-contact-icon">📞</div>
            <a href="tel:<?= e(setting('whatsapp_number')) ?>"
               class="hover:text-[var(--rose)] transition" style="color:var(--fg);">
              <?= e(setting('phone_display')) ?>
            </a>
          </li>
          <li class="footer-contact-item">
            <div class="footer-contact-icon">✉️</div>
            <a href="mailto:<?= e(setting('email')) ?>"
               class="hover:text-[var(--rose)] transition break-all" style="color:var(--fg);">
              <?= e(setting('email')) ?>
            </a>
          </li>
          <li class="footer-contact-item">
            <div class="footer-contact-icon">⏰</div>
            <span><?= e(setting('jam_buka')) ?></span>
          </li>
        </ul>
        <a href="<?= e($wa_full) ?>" target="_blank" class="footer-wa-btn">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
            <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
          </svg>
          Chat WhatsApp
        </a>
      </div>

    </div>

    <!-- Bottom bar -->
    <div class="pt-6 flex flex-col md:flex-row justify-between items-center gap-3 text-xs"
         style="border-top: 1px solid rgba(212,137,154,.15); color: var(--fg);">
      <p>© <?= date('Y') ?> <?= e(setting('site_name')) ?>. Hak cipta dilindungi.</p>
      <div class="flex items-center gap-2">
        <span>🌸</span>
        <p>Website Florist Tangerang Terpercaya | Pengiriman 24 Jam</p>
        <span>🌸</span>
      </div>
    </div>
  </div>
</footer>

<!-- ══ STICKY WA BUTTON ══ -->
<a href="<?= e($wa_full) ?>" target="_blank" rel="noopener"
   class="sticky-wa" aria-label="Chat WhatsApp">
  <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
    <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
  </svg>
  <span class="whitespace-nowrap">Pesan Sekarang</span>
  <div class="sticky-wa-ping"></div>
</a>

<script src="<?= BASE_URL ?>/assets/js/main.js"></script>

<script>
/* Floating petals footer */
(function () {
  const wrap  = document.getElementById('footer-petals');
  if (!wrap) return;
  const icons = ['🌸','🌺','🌷','🌼','🪷','🌹'];
  for (let i = 0; i < 10; i++) {
    const el  = document.createElement('span');
    el.className = 'footer-petal';
    el.textContent = icons[i % icons.length];
    const dur = 8 + Math.random() * 5;
    el.style.cssText =
      'left:'        + (2 + Math.random() * 96) + '%;' +
      'top:'         + (5 + Math.random() * 88) + '%;' +
      'font-size:'   + (9 + Math.random() * 10) + 'px;' +
      'animation-duration:' + dur + 's;' +
      'animation-delay:-'   + (Math.random() * dur) + 's;';
    wrap.appendChild(el);
  }
})();

/* ================================================================
   AREA PENGIRIMAN SLIDER — Tangerang (tema rose)
   ================================================================ */
(function () {
  var track   = document.getElementById('fasTrack');
  var dotsEl  = document.getElementById('fasDots');
  var lbl     = document.getElementById('fasLbl');
  var btnPrev = document.getElementById('fasPrev');
  var btnNext = document.getElementById('fasNext');
  if (!track) return;

  var cities = Array.from(track.querySelectorAll('.fas-item-rose')).map(function (el) {
    return { name: el.dataset.name, href: el.dataset.href };
  });

  var cur     = 0;
  var perPage = 0;

  function isMobile() { return window.innerWidth < 768; }

  function chunk(arr, n) {
    var r = [];
    for (var i = 0; i < arr.length; i += n) r.push(arr.slice(i, i + n));
    return r;
  }

  function rebuild() {
    var pp = isMobile() ? 4 : 5;
    if (pp === perPage && track.querySelectorAll('.fas-slide-rose').length > 0) return;
    perPage = pp;

    var pages = chunk(cities, perPage);

    Array.from(track.querySelectorAll('.fas-slide-rose')).forEach(function (s) { track.removeChild(s); });

    track.style.transition = 'none';
    track.style.transform  = 'translateX(0)';

    pages.forEach(function (page) {
      var slide = document.createElement('div');
      slide.className = 'fas-slide-rose';
      page.forEach(function (city) {
        var a = document.createElement('a');
        a.className = 'fas-link-rose';
        a.href = city.href;
        a.innerHTML =
          '<span class="fas-dot-rose"></span>' +
          '<span class="fas-link-rose-name">' + city.name + '</span>';
        slide.appendChild(a);
      });
      track.appendChild(slide);
    });

    dotsEl.innerHTML = '';
    pages.forEach(function (_, i) {
      var d = document.createElement('button');
      d.className = 'fas-dot-btn-rose';
      d.setAttribute('aria-label', 'Halaman ' + (i + 1));
      (function (idx) {
        d.addEventListener('click', function () { goTo(idx); });
      })(i);
      dotsEl.appendChild(d);
    });

    if (cur >= pages.length) cur = pages.length - 1;
    goTo(cur, true);
  }

  function goTo(n, instant) {
    var total = track.querySelectorAll('.fas-slide-rose').length;
    cur = Math.max(0, Math.min(n, total - 1));

    track.style.transition = instant ? 'none' : 'transform .38s cubic-bezier(.4,0,.2,1)';
    track.style.transform  = 'translateX(-' + (cur * 100) + '%)';

    Array.from(dotsEl.children).forEach(function (d, i) {
      d.classList.toggle('active', i === cur);
    });
    lbl.textContent  = (cur + 1) + ' / ' + total;
    btnPrev.disabled = cur === 0;
    btnNext.disabled = cur === total - 1;
  }

  btnPrev.addEventListener('click', function () { goTo(cur - 1); });
  btnNext.addEventListener('click', function () { goTo(cur + 1); });

  var tx0 = null;
  track.addEventListener('touchstart', function (e) {
    tx0 = e.touches[0].clientX;
  }, { passive: true });
  track.addEventListener('touchend', function (e) {
    if (tx0 === null) return;
    var dx = e.changedTouches[0].clientX - tx0;
    if (Math.abs(dx) > 40) goTo(cur + (dx < 0 ? 1 : -1));
    tx0 = null;
  }, { passive: true });

  var lastMobile = isMobile();
  var rTimer;
  window.addEventListener('resize', function () {
    clearTimeout(rTimer);
    rTimer = setTimeout(function () {
      var nowMobile = isMobile();
      if (nowMobile !== lastMobile) {
        lastMobile = nowMobile;
        perPage    = 0;
        rebuild();
      }
    }, 150);
  });

  rebuild();
})();
</script>
</body>
</html>