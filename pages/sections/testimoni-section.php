
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

<?php
/* ================================================================
   TESTIMONI SECTION — Carousel Elegan
   Tema: ivory / rose / blush / cream — matching seluruh halaman
   Auto-play + drag/swipe + animasi percikan bunga
================================================================ */
?>

<style>
/* ── Variabel tema ── */
#testimoni {
  --blush:  #F2C4CE;
  --rose:   #D4899A;
  --dusty:  #C8778A;
  --cream:  #FAF5EE;
  --ivory:  #FDF9F4;
  --soft:   #F7EEF0;
  --muted:  #8C6B72;
  --dark:   #2C1A1E;
}

/* ── Floating petal background ── */
@keyframes petal-drift {
  0%   { transform: translateY(0px) rotate(0deg)   scale(1);   opacity: 0; }
  10%  { opacity: .35; }
  90%  { opacity: .2; }
  100% { transform: translateY(-120px) rotate(45deg) scale(.7); opacity: 0; }
}
@keyframes petal-sway {
  0%, 100% { margin-left: 0; }
  50%       { margin-left: 18px; }
}
.bg-petal {
  position: absolute;
  pointer-events: none;
  font-size: 20px;
  animation: petal-drift 7s ease-in-out infinite, petal-sway 3.5s ease-in-out infinite;
  will-change: transform, opacity;
}

/* ── Burst petal (saat tap/hover card) ── */
@keyframes burst-petal {
  0%   { transform: translate(0,0) rotate(0deg) scale(1); opacity: 1; }
  100% { transform: translate(var(--bx), var(--by)) rotate(var(--br)) scale(0); opacity: 0; }
}
.burst-petal {
  position: fixed;
  pointer-events: none;
  z-index: 9999;
  font-size: 18px;
  animation: burst-petal .85s cubic-bezier(.2,.8,.4,1) forwards;
}

/* ── Carousel ── */
.testi-track {
  display: flex;
  transition: transform .55s cubic-bezier(.4,0,.2,1);
  will-change: transform;
}
.testi-slide {
  flex: 0 0 100%;
  padding: 0 8px;
}
@media (min-width: 768px)  { .testi-slide { flex: 0 0 50%; } }
@media (min-width: 1024px) { .testi-slide { flex: 0 0 33.333%; } }

/* ── Card ── */
.testi-card {
  background: #fff;
  border: 1.5px solid rgba(212,137,154,.18);
  border-radius: 24px;
  padding: 28px;
  height: 100%;
  position: relative;
  overflow: hidden;
  transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
  cursor: pointer;
}
.testi-card::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(242,196,206,.15) 0%, transparent 60%);
  border-radius: 24px;
  pointer-events: none;
}
.testi-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 20px 48px rgba(212,137,154,.2), 0 4px 12px rgba(212,137,154,.1);
  border-color: rgba(212,137,154,.4);
}

/* Petal pojok dekoratif */
.testi-card-petal {
  position: absolute;
  top: 12px;
  right: 16px;
  font-size: 36px;
  opacity: .12;
  pointer-events: none;
  transition: opacity .3s ease, transform .3s ease;
  transform: rotate(-15deg);
}
.testi-card:hover .testi-card-petal {
  opacity: .22;
  transform: rotate(0deg) scale(1.1);
}

/* Garis aksen top */
.testi-card-accent {
  position: absolute;
  top: 0; left: 24px; right: 24px;
  height: 2px;
  background: linear-gradient(90deg, transparent, var(--rose), transparent);
  border-radius: 0 0 2px 2px;
  transform: scaleX(0);
  transition: transform .4s ease;
}
.testi-card:hover .testi-card-accent { transform: scaleX(1); }

/* Quote mark dekoratif */
.testi-quote {
  font-family: 'Playfair Display', Georgia, serif;
  font-size: 68px;
  line-height: 1;
  color: rgba(212,137,154,.15);
  position: absolute;
  top: 10px;
  right: 18px;
  pointer-events: none;
  user-select: none;
}

/* Bintang */
.testi-star-fill  { color: var(--rose); }
.testi-star-empty { color: rgba(200,119,138,.18); }

/* Avatar */
.testi-avatar {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--blush), var(--rose));
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Playfair Display', Georgia, serif;
  font-weight: 700;
  font-size: 17px;
  color: #fff;
  flex-shrink: 0;
  box-shadow: 0 4px 12px rgba(212,137,154,.35);
}

/* ── Dot indicator ── */
.testi-dot {
  width: 7px; height: 7px;
  border-radius: 100px;
  background: rgba(212,137,154,.25);
  transition: all .3s ease;
  cursor: pointer;
}
.testi-dot.active {
  width: 26px;
  background: linear-gradient(90deg, var(--rose), var(--blush));
}

/* ── Nav button ── */
.testi-nav {
  width: 44px; height: 44px;
  border-radius: 50%;
  border: 1.5px solid rgba(212,137,154,.3);
  background: #fff;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  transition: all .25s ease;
  flex-shrink: 0;
  box-shadow: 0 2px 8px rgba(212,137,154,.15);
}
.testi-nav:hover {
  background: linear-gradient(135deg, var(--rose), var(--dusty));
  border-color: var(--rose);
  transform: scale(1.08);
}
.testi-nav:hover svg { stroke: #fff; }
.testi-nav svg { stroke: var(--dusty); transition: stroke .25s ease; }

/* Fade edge */
.testi-fade-left  { background: linear-gradient(to right,  var(--ivory), transparent); }
.testi-fade-right { background: linear-gradient(to left, var(--ivory), transparent); }

/* Badge source */
.testi-source {
  display: inline-flex; align-items: center; gap: 4px;
  font-size: 10px; font-weight: 700; letter-spacing: .06em;
  text-transform: uppercase;
  color: var(--muted);
  background: rgba(242,196,206,.25);
  border: 1px solid rgba(212,137,154,.2);
  border-radius: 100px;
  padding: 2px 8px;
}
</style>

<!-- ============================================================ TESTIMONI SECTION ============================================================ -->
<section id="testimoni" class="py-20 relative overflow-hidden"
         style="background: var(--ivory, #FDF9F4);">

  <!-- Dekorasi top line -->
  <div class="absolute top-0 left-0 w-full h-px"
       style="background: linear-gradient(90deg, transparent, rgba(212,137,154,.4), transparent);"></div>

  <!-- Floating background petals (diam, dekoratif) -->
  <div id="testi-bg-petals" class="absolute inset-0 pointer-events-none overflow-hidden"></div>

  <!-- Blob bg kiri -->
  <div class="absolute -left-32 top-1/3 w-72 h-72 rounded-full pointer-events-none"
       style="background: radial-gradient(circle, rgba(242,196,206,.25) 0%, transparent 70%); filter: blur(48px);"></div>
  <!-- Blob bg kanan -->
  <div class="absolute -right-24 bottom-1/4 w-64 h-64 rounded-full pointer-events-none"
       style="background: radial-gradient(circle, rgba(200,119,138,.15) 0%, transparent 70%); filter: blur(48px);"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-4">

    <!-- ── Header ── -->
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12">
      <div>
        <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase mb-5"
             style="background: rgba(212,137,154,.12); border: 1px solid rgba(212,137,154,.25); color: var(--dusty);">
          <span class="inline-block w-1.5 h-1.5 rounded-full" style="background: var(--rose);"></span>
          Apa Kata Mereka
        </div>
        <h2 class="font-serif text-3xl md:text-4xl font-black mt-2 mb-3"
            style="color: var(--dark);">
          Testimoni Pelanggan
        </h2>
        <p class="max-w-md text-[15px] leading-relaxed" style="color: var(--muted);">
          Kepercayaan pelanggan adalah motivasi terbesar kami untuk terus memberikan yang terbaik.
        </p>
      </div>

      
    </div>

    <!-- ── Carousel Wrapper ── -->
    <div class="relative">

      <!-- Fade edges desktop -->
      <div class="testi-fade-left absolute left-0 top-0 bottom-0 w-10 z-10 pointer-events-none hidden md:block"></div>
      <div class="testi-fade-right absolute right-0 top-0 bottom-0 w-10 z-10 pointer-events-none hidden md:block"></div>

      <!-- Overflow container -->
      <div class="overflow-hidden" id="testi-overflow">
        <div class="testi-track" id="testi-track">

          <?php
          $petalIcons = ['🌸','🌺','🌷','🌼','🪷','🌹','💐','🌻'];
          foreach ($testimonials as $ti => $t):
            $petal = $petalIcons[$ti % count($petalIcons)];
          ?>
          <div class="testi-slide">
            <div class="testi-card" data-petal="<?= $petal ?>">
              <!-- Aksen top -->
              <div class="testi-card-accent"></div>

              <!-- Petal pojok -->
              <div class="testi-card-petal"><?= $petal ?></div>

              <!-- Quote dekoratif -->
              <div class="testi-quote">"</div>

              <!-- Bintang -->
              <div class="flex gap-0.5 mb-3">
                <?php for ($s = 0; $s < (int)$t['rating']; $s++): ?>
                <span class="testi-star-fill text-sm leading-none">★</span>
                <?php endfor; ?>
                <?php for ($s = (int)$t['rating']; $s < 5; $s++): ?>
                <span class="testi-star-empty text-sm leading-none">★</span>
                <?php endfor; ?>
              </div>

              <!-- Isi testimoni -->
              <p class="text-[13px] leading-[1.85] mb-5 relative z-10"
                 style="color: var(--muted);">
                "<?= e($t['content']) ?>"
              </p>

              <!-- Author -->
              <div class="flex items-center justify-between gap-3 mt-auto pt-4"
                   style="border-top: 1px solid rgba(212,137,154,.15);">
                <div class="flex items-center gap-3">
                  <div class="testi-avatar">
                    <?= strtoupper(substr($t['name'], 0, 1)) ?>
                  </div>
                  <div>
                    <div class="font-bold text-sm" style="color: var(--dark);">
                      <?= e($t['name']) ?>
                    </div>
                    <div class="text-[11px] mt-0.5" style="color: var(--muted);">
                      <?= e($t['location']) ?>
                    </div>
                  </div>
                </div>
                <?php if (!empty($t['source'])): ?>
                <span class="testi-source"><?= e($t['source']) ?></span>
                <?php else: ?>
                <span class="testi-source">Google</span>
                <?php endif; ?>
              </div>

            </div>
          </div>
          <?php endforeach; ?>

        </div><!-- /testi-track -->
      </div><!-- /overflow -->
    </div><!-- /carousel wrapper -->

    <!-- ── Dot indicators + nav ── -->
    <div class="flex items-center justify-center gap-4 mt-8">
      <button class="testi-nav" id="testi-prev-m" aria-label="Sebelumnya">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
      </button>
      <div class="flex items-center gap-2" id="testi-dots"></div>
      <button class="testi-nav" id="testi-next-m" aria-label="Berikutnya">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
      </button>
    </div>

  </div><!-- /container -->
</section>

<script>
(function () {
  /* ── Data ── */
  const petals   = ['🌸','🌺','🌷','🌼','🪷','🌹','💐','🌻'];
  const track    = document.getElementById('testi-track');
  const overflow = document.getElementById('testi-overflow');
  const dotsWrap = document.getElementById('testi-dots');
  const slides   = track ? track.querySelectorAll('.testi-slide') : [];
  const total    = slides.length;

  let current    = 0;
  let autoTimer  = null;
  let startX     = 0;
  let isDragging = false;

  /* ── Carousel helpers ── */
  function visibleCount() {
    if (window.innerWidth >= 1024) return 3;
    if (window.innerWidth >= 768)  return 2;
    return 1;
  }
  function maxIndex() { return Math.max(0, total - visibleCount()); }

  function goTo(idx) {
    current = Math.max(0, Math.min(idx, maxIndex()));
    const slideW = slides[0] ? slides[0].offsetWidth : 0;
    track.style.transform = `translateX(-${current * slideW}px)`;
    updateDots();
  }

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

  /* ── Auto-play ── */
  function startAuto() {
    autoTimer = setInterval(() => goTo(current >= maxIndex() ? 0 : current + 1), 4500);
  }
  function resetAuto() { clearInterval(autoTimer); startAuto(); }

  /* ── Nav buttons ── */
  document.getElementById('testi-prev-m')?.addEventListener('click', () => {
    goTo(current <= 0 ? maxIndex() : current - 1); resetAuto();
  });
  document.getElementById('testi-next-m')?.addEventListener('click', () => {
    goTo(current >= maxIndex() ? 0 : current + 1); resetAuto();
  });

  /* ── Touch / drag ── */
  overflow.addEventListener('touchstart', e => { startX = e.touches[0].clientX; }, { passive: true });
  overflow.addEventListener('touchend', e => {
    const diff = startX - e.changedTouches[0].clientX;
    if (Math.abs(diff) > 50) { goTo(diff > 0 ? current + 1 : current - 1); resetAuto(); }
  });
  overflow.addEventListener('mousedown',  e => { isDragging = true; startX = e.clientX; });
  overflow.addEventListener('mouseup',    e => {
    if (!isDragging) return; isDragging = false;
    const diff = startX - e.clientX;
    if (Math.abs(diff) > 50) { goTo(diff > 0 ? current + 1 : current - 1); resetAuto(); }
  });
  overflow.addEventListener('mouseleave', () => { isDragging = false; });
  overflow.addEventListener('mouseenter', () => clearInterval(autoTimer));
  overflow.addEventListener('mouseleave', () => startAuto());

  /* ── Resize ── */
  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => { goTo(Math.min(current, maxIndex())); buildDots(); }, 150);
  });

  /* ──────────────────────────────────────────────
     BURST PETALS — percikan bunga saat hover/tap
  ────────────────────────────────────────────── */
  function burstPetals(cx, cy, count) {
    for (let i = 0; i < count; i++) {
      const el   = document.createElement('span');
      const icon = petals[Math.floor(Math.random() * petals.length)];
      const ang  = (Math.PI * 2 / count) * i + (Math.random() - .5) * .8;
      const dist = 55 + Math.random() * 65;
      const bx   = Math.cos(ang) * dist;
      const by   = Math.sin(ang) * dist - 20;
      const br   = (Math.random() - .5) * 360 + 'deg';
      const size = 14 + Math.random() * 12;
      const delay = i * 35;

      el.className = 'burst-petal';
      el.textContent = icon;
      el.style.cssText = `
        left: ${cx - size/2}px; top: ${cy - size/2}px;
        font-size: ${size}px;
        --bx: ${bx}px; --by: ${by}px; --br: ${br};
        animation-delay: ${delay}ms;
      `;
      document.body.appendChild(el);
      setTimeout(() => el.remove(), 850 + delay + 200);
    }
  }

  /* Hover desktop */
  document.querySelectorAll('#testimoni .testi-card').forEach(card => {
    let entered = false;
    card.addEventListener('mouseenter', e => {
      if (entered) return; entered = true;
      const rect = card.getBoundingClientRect();
      burstPetals(rect.left + rect.width / 2, rect.top + rect.height / 2, 8);
    });
    card.addEventListener('mouseleave', () => { entered = false; });

    /* Tap mobile */
    card.addEventListener('touchstart', e => {
      const t = e.touches[0];
      burstPetals(t.clientX, t.clientY, 6);
    }, { passive: true });
  });

  /* ──────────────────────────────────────────
     BG FLOATING PETALS — melayang pelan di bg
  ────────────────────────────────────────── */
  (function spawnBgPetals() {
    const wrap = document.getElementById('testi-bg-petals');
    if (!wrap) return;
    const BG_COUNT = 14;
    for (let i = 0; i < BG_COUNT; i++) {
      const el    = document.createElement('span');
      el.className = 'bg-petal';
      el.textContent = petals[i % petals.length];
      const leftPct  = 3 + Math.random() * 94;
      const startY   = 10 + Math.random() * 80;
      const dur      = 6 + Math.random() * 5;
      const swayDur  = 2.5 + Math.random() * 2;
      const delay    = -(Math.random() * dur);
      const fontSize = 13 + Math.random() * 14;

      el.style.cssText = `
        left: ${leftPct}%;
        top:  ${startY}%;
        font-size: ${fontSize}px;
        animation-duration: ${dur}s, ${swayDur}s;
        animation-delay:    ${delay}s, ${delay * .7}s;
      `;
      wrap.appendChild(el);
    }
  })();

  /* ── Init ── */
  buildDots();
  goTo(0);
  startAuto();
})();
</script>