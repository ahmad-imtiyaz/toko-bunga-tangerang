
<?php
/* ================================================================
   CTA SECTION — Luxury Ivory Elegan
   Tema: ivory / rose / blush / cream
   Foto bunga "melayang" di tengah, teks mengapit, sangat feminine
================================================================ */
?>

<style>
#cta-section {
  --blush: #F2C4CE;
  --rose:  #D4899A;
  --dusty: #C8778A;
  --cream: #FAF5EE;
  --ivory: #FDF9F4;
  --muted: #8C6B72;
  --dark:  #2C1A1E;
}

/* ── Shimmer garis rose ── */
@keyframes cta-shimmer {
  0%   { background-position: -200% center; }
  100% { background-position:  200% center; }
}
.cta-shimmer-line {
  background: linear-gradient(90deg,
    transparent 0%,
    rgba(212,137,154,.6) 40%,
    rgba(242,196,206,1)  50%,
    rgba(212,137,154,.6) 60%,
    transparent 100%
  );
  background-size: 200% auto;
  animation: cta-shimmer 3.5s linear infinite;
}

/* ── Foto oval melayang ── */
.cta-photo-wrap {
  position: relative;
  width: 320px;
  height: 420px;
  flex-shrink: 0;
}
@media (max-width: 1023px) {
  .cta-photo-wrap { width: 260px; height: 340px; }
}
@media (max-width: 767px) {
  .cta-photo-wrap { width: 200px; height: 260px; margin: 0 auto; }
}

.cta-photo-wrap img {
  width: 100%; height: 100%;
  object-fit: cover;
  border-radius: 60% 40% 55% 45% / 50% 50% 50% 50%;
  display: block;
  position: relative;
  z-index: 2;
  box-shadow:
    0 30px 70px rgba(212,137,154,.3),
    0 10px 30px rgba(44,26,30,.12);
  animation: cta-float 5s ease-in-out infinite;
}
@keyframes cta-float {
  0%,100% { transform: translateY(0px) rotate(-1deg); }
  50%      { transform: translateY(-14px) rotate(1deg); }
}

/* Frame dekoratif di belakang foto */
.cta-photo-frame {
  position: absolute;
  inset: -14px;
  border-radius: 60% 40% 55% 45% / 50% 50% 50% 50%;
  border: 1.5px dashed rgba(212,137,154,.35);
  z-index: 1;
  animation: cta-float 5s ease-in-out infinite;
  animation-delay: -.5s;
}
.cta-photo-frame2 {
  position: absolute;
  inset: -28px;
  border-radius: 55% 45% 60% 40% / 45% 55% 45% 55%;
  border: 1px solid rgba(242,196,206,.2);
  z-index: 0;
  animation: cta-float 6s ease-in-out infinite;
  animation-delay: -1s;
}

/* Badge mengambang di foto */
.cta-photo-badge {
  position: absolute;
  z-index: 10;
  background: #fff;
  border: 1.5px solid rgba(212,137,154,.2);
  border-radius: 100px;
  padding: 8px 14px;
  display: flex; align-items: center; gap: 7px;
  box-shadow: 0 8px 24px rgba(212,137,154,.2);
  animation: cta-badge-float 4s ease-in-out infinite;
  white-space: nowrap;
}
@keyframes cta-badge-float {
  0%,100% { transform: translateY(0); }
  50%      { transform: translateY(-6px); }
}

/* ── Petal melayang bg ── */
@keyframes cta-petal {
  0%   { transform: translateY(0) rotate(0deg) scale(1);   opacity: 0; }
  8%   { opacity: .35; }
  92%  { opacity: .15; }
  100% { transform: translateY(-130px) rotate(50deg) scale(.6); opacity: 0; }
}
@keyframes cta-petal-sway {
  0%,100% { margin-left: 0; }
  50%      { margin-left: 20px; }
}
.cta-petal {
  position: absolute; pointer-events: none;
  animation: cta-petal 8s ease-in-out infinite,
             cta-petal-sway 4s ease-in-out infinite;
}

/* ── Trust badge pill ── */
.cta-trust {
  display: inline-flex; align-items: center; gap: 6px;
  background: rgba(212,137,154,.08);
  border: 1px solid rgba(212,137,154,.2);
  border-radius: 100px;
  padding: 6px 14px;
  font-size: 11px; font-weight: 700;
  color: var(--muted);
  letter-spacing: .03em;
}

/* ── CTA primary button ── */
.cta-btn-primary {
  display: inline-flex; align-items: center; justify-content: center; gap: 10px;
  background: linear-gradient(135deg, var(--rose), var(--dusty));
  color: #fff;
  font-weight: 700; font-size: 15px;
  padding: 16px 32px;
  border-radius: 100px;
  text-decoration: none;
  box-shadow: 0 12px 32px rgba(212,137,154,.4);
  transition: transform .25s ease, box-shadow .25s ease;
}
.cta-btn-primary:hover {
  transform: translateY(-3px);
  box-shadow: 0 18px 42px rgba(212,137,154,.5);
}

/* ── CTA secondary button ── */
.cta-btn-secondary {
  display: inline-flex; align-items: center; justify-content: center; gap: 8px;
  background: #fff;
  color: var(--dark);
  font-weight: 600; font-size: 14px;
  padding: 15px 28px;
  border-radius: 100px;
  text-decoration: none;
  border: 1.5px solid rgba(212,137,154,.3);
  box-shadow: 0 4px 16px rgba(212,137,154,.1);
  transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
}
.cta-btn-secondary:hover {
  transform: translateY(-3px);
  border-color: var(--rose);
  box-shadow: 0 10px 28px rgba(212,137,154,.2);
}

/* ── Ornamen teks italic serif ── */
.cta-ornament {
  font-family: 'Playfair Display', Georgia, serif;
  font-style: italic;
  color: var(--rose);
  opacity: .55;
  font-size: 13px;
  letter-spacing: .04em;
}
</style>

<!-- ============================================================
     CTA SECTION
============================================================ -->
<section id="cta-section" class="relative overflow-hidden py-20 md:py-28"
         style="background: var(--ivory, #FDF9F4);">

  <!-- Shimmer line atas -->
  <div class="absolute top-0 left-0 w-full h-[2px]">
    <div class="cta-shimmer-line w-full h-full"></div>
  </div>
  <!-- Shimmer line bawah -->
  <div class="absolute bottom-0 left-0 w-full h-[2px]">
    <div class="cta-shimmer-line w-full h-full"></div>
  </div>

  <!-- Blob bg -->
  <div class="absolute top-0 left-1/4 w-96 h-96 rounded-full pointer-events-none"
       style="background: radial-gradient(circle, rgba(242,196,206,.22) 0%, transparent 70%); filter: blur(64px);"></div>
  <div class="absolute bottom-0 right-1/4 w-80 h-80 rounded-full pointer-events-none"
       style="background: radial-gradient(circle, rgba(212,137,154,.15) 0%, transparent 70%); filter: blur(56px);"></div>

  <!-- Floating petals bg -->
  <div id="cta-petals" class="absolute inset-0 pointer-events-none overflow-hidden"></div>

  <!-- Ornamen teks besar transparan -->
  <div class="absolute left-6 top-1/2 -translate-y-1/2 font-serif font-black text-[140px] leading-none pointer-events-none select-none hidden lg:block"
       style="color: rgba(212,137,154,.04); white-space: nowrap;">Bunga</div>
  <div class="absolute right-4 top-1/3 font-serif font-black text-[100px] leading-none pointer-events-none select-none hidden lg:block"
       style="color: rgba(212,137,154,.04);">🌸</div>

  <div class="relative z-10 max-w-6xl mx-auto px-4">

    <!-- Layout: teks kiri — foto tengah — teks kanan -->
    <div class="flex flex-col lg:flex-row items-center justify-center gap-10 lg:gap-14">

      <!-- ── Teks Kiri ── -->
      <div class="flex-1 text-center lg:text-right order-2 lg:order-1">

        <div class="cta-ornament mb-3">— Untuk momen terbaik hidupmu</div>

        <h2 class="font-serif text-3xl md:text-4xl xl:text-5xl font-black leading-tight mb-5"
            style="color: var(--dark);">
          Pesan Bunga<br>
          <span style="background: linear-gradient(135deg, var(--rose), var(--dusty)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">
            Segar & Indah
          </span>
        </h2>

        <p class="text-[15px] leading-relaxed mb-7 max-w-xs lg:ml-auto" style="color: var(--muted);">
          Rangkaian bunga segar terbaik, dikirim tepat waktu ke seluruh wilayah Tangerang.
        </p>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row lg:flex-col xl:flex-row gap-3 justify-center lg:justify-end">
          <a href="<?= e($wa_url) ?>?text=<?= urlencode('Halo, saya ingin memesan bunga!') ?>"
             target="_blank" class="cta-btn-primary">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
              <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
            </svg>
            Pesan via WhatsApp
          </a>
          <a href="tel:<?= e(setting('whatsapp_number')) ?>" class="cta-btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"
                 style="stroke: var(--rose);">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/>
            </svg>
            Telepon Langsung
          </a>
        </div>

      </div>

      <!-- ── Foto Tengah ── -->
      <div class="cta-photo-wrap order-1 lg:order-2">
        <div class="cta-photo-frame2"></div>
        <div class="cta-photo-frame"></div>
        <img src="<?= BASE_URL ?>/assets/images/cta.jpg" alt="Bunga Segar">

        <!-- Badge atas kiri -->
        <div class="cta-photo-badge" style="top: -10px; left: -20px; animation-delay: 0s;">
          <span style="font-size:18px;">🌸</span>
          <div>
            <div class="font-bold text-[12px]" style="color:var(--dark);">100% Segar</div>
            <div class="text-[10px]" style="color:var(--muted);">Dijamin</div>
          </div>
        </div>

        <!-- Badge kanan bawah -->
        <div class="cta-photo-badge" style="bottom: 10px; right: -24px; animation-delay: -2s;">
          <span style="font-size:18px;">⚡</span>
          <div>
            <div class="font-bold text-[12px]" style="color:var(--dark);">Kirim 2–4 Jam</div>
            <div class="text-[10px]" style="color:var(--muted);">Se-Tangerang</div>
          </div>
        </div>

        <!-- Badge bintang -->
        <div class="cta-photo-badge" style="bottom: 90px; left: -28px; animation-delay: -1.2s; padding: 6px 12px;">
          <span style="color:#F59E0B; font-size:13px;">★★★★★</span>
          <div class="font-bold text-[11px]" style="color:var(--dark);">5.0</div>
        </div>
      </div>

      <!-- ── Teks Kanan ── -->
      <div class="flex-1 text-center lg:text-left order-3">

        <div class="cta-ornament mb-3">Percayakan pada kami —</div>

        <h2 class="font-serif text-3xl md:text-4xl xl:text-5xl font-black leading-tight mb-5"
            style="color: var(--dark);">
          Pengiriman<br>
          <span style="background: linear-gradient(135deg, var(--rose), var(--dusty)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">
            Tepat Waktu
          </span>
        </h2>

        <p class="text-[15px] leading-relaxed mb-7 max-w-xs" style="color: var(--muted);">
          Kami melayani 24 jam, 7 hari seminggu. Bunga tiba dalam kondisi segar dan cantik.
        </p>

        <!-- Trust badges -->
        <div class="flex flex-col gap-2.5 items-center lg:items-start">
          <span class="cta-trust">✓ &nbsp;Respon Cepat</span>
          <span class="cta-trust">✓ &nbsp;Bunga Segar Dijamin</span>
          <span class="cta-trust">✓ &nbsp;Buka 24 Jam</span>
          <span class="cta-trust">✓ &nbsp;Pengiriman Tepat Waktu</span>
        </div>

      </div>

    </div>

    <!-- Overline badge tengah bawah -->
    <div class="flex justify-center mt-14">
      <div class="inline-flex items-center gap-3 px-6 py-3 rounded-full"
           style="background:#fff; border:1.5px solid rgba(212,137,154,.2); box-shadow:0 4px 20px rgba(212,137,154,.12);">
        <span class="text-lg">🌺</span>
        <span class="text-[13px] font-semibold" style="color:var(--dark);">
          Terpercaya melayani Tangerang sejak 10+ tahun
        </span>
        <span class="text-lg">🌸</span>
      </div>
    </div>

  </div>
</section>


<script>
/* ── Floating petals CTA ── */
(function () {
  const wrap   = document.getElementById('cta-petals');
  if (!wrap) return;
  const icons  = ['🌸','🌺','🌷','🌼','🪷','🌹'];
  for (let i = 0; i < 14; i++) {
    const el  = document.createElement('span');
    el.className = 'cta-petal';
    el.textContent = icons[i % icons.length];
    const dur  = 7 + Math.random() * 6;
    const sway = 3 + Math.random() * 3;
    el.style.cssText =
      'left:'        + (2 + Math.random() * 96) + '%;' +
      'top:'         + (5 + Math.random() * 88) + '%;' +
      'font-size:'   + (11 + Math.random() * 13) + 'px;' +
      'animation-duration:' + dur + 's,' + sway + 's;' +
      'animation-delay:-'   + (Math.random() * dur) + 's,-' + (Math.random() * sway) + 's;';
    wrap.appendChild(el);
  }
})();
</script>