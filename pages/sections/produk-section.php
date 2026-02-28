
<!-- ============================================================
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
  font:600 15px/1.3 'Cormorant Garamond',serif;
  color:var(--dark);
  margin-bottom:6px;

  display:-webkit-box;
  -webkit-box-orient:vertical;

  -webkit-line-clamp:2;
  line-clamp:2;

  overflow:hidden;
}

.prod-desc {
  font:400 11.5px/1.65 'Jost',sans-serif;
  color:var(--muted);
  margin-bottom:12px;
  flex:1;

  display:-webkit-box;
  -webkit-box-orient:vertical;

  -webkit-line-clamp:2;
  line-clamp:2;

  overflow:hidden;
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