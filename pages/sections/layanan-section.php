
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

</section>