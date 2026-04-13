<?php
// blog-sidebar.php — Tangerang theme (Ivory + Rose/Blush)
// Variabel tersedia dari parent: $blog_cats, $locations, $wa_url, $filter_cat

$sidebar_recent = db()->query("
    SELECT b.title, b.slug, b.thumbnail, b.created_at, bc.name AS cat_name
    FROM blogs b
    LEFT JOIN blog_categories bc ON b.blog_category_id = bc.id
    WHERE b.status = 'active'
    ORDER BY b.created_at DESC LIMIT 5
")->fetchAll();

$sidebar_categories = db()->query("
    SELECT * FROM categories
    WHERE status = 'active' AND (parent_id IS NULL OR parent_id = 0)
    ORDER BY urutan ASC, id ASC
")->fetchAll();

$sidebar_products = db()->query("
    SELECT p.*, c.name AS cat_name FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.status = 'active'
    ORDER BY p.id ASC
    LIMIT 30
")->fetchAll();
?>

<div style="display:flex;flex-direction:column;gap:14px;">

  <!-- ── Kategori Artikel ── -->
  <div style="background:#fff;border:1px solid rgba(212,137,154,.18);border-radius:16px;overflow:hidden;box-shadow:0 2px 14px rgba(44,26,30,.05);">
    <div style="padding:14px 18px 10px;border-bottom:1px solid rgba(212,137,154,.12);background:rgba(242,196,206,.06);">
      <p style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(200,119,138,.55);margin-bottom:3px;">Filter</p>
      <h3 style="font-family:'Cormorant Garamond',Georgia,serif;font-size:15px;font-weight:700;color:#2C1A1E;">Kategori Artikel</h3>
    </div>
    <div style="padding:8px 10px;max-height:220px;overflow-y:auto;">
      <a href="<?= BASE_URL ?>/blog/"
         style="display:flex;align-items:center;justify-content:space-between;padding:7px 10px;border-radius:10px;text-decoration:none;margin-bottom:2px;
                background:<?= !$filter_cat ? 'rgba(212,137,154,.1)' : 'transparent' ?>;
                color:<?= !$filter_cat ? '#C8778A' : 'rgba(44,26,30,.45)' ?>;">
        <span style="font-size:12px;font-weight:600;">Semua Artikel</span>
        <span style="font-size:10px;background:rgba(212,137,154,.1);padding:1px 7px;border-radius:10px;color:rgba(44,26,30,.35);">
          <?= array_sum(array_column($blog_cats, 'total')) ?>
        </span>
      </a>
      <?php foreach ($blog_cats as $bc): $act = ($filter_cat === $bc['slug']); ?>
      <a href="<?= BASE_URL ?>/blog/?kategori=<?= e($bc['slug']) ?>"
         style="display:flex;align-items:center;justify-content:space-between;padding:7px 10px;border-radius:10px;text-decoration:none;margin-bottom:2px;
                background:<?= $act ? 'rgba(212,137,154,.1)' : 'transparent' ?>;
                color:<?= $act ? '#C8778A' : 'rgba(44,26,30,.45)' ?>;">
        <span style="font-size:12px;font-weight:<?= $act ? '700' : '500' ?>;"><?= e($bc['name']) ?></span>
        <span style="font-size:10px;background:rgba(212,137,154,.1);padding:1px 7px;border-radius:10px;color:rgba(44,26,30,.3);"><?= $bc['total'] ?></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- ── Slider Kategori Produk ── -->
  <?php if (!empty($sidebar_categories)): ?>
  <div style="background:#fff;border:1px solid rgba(212,137,154,.18);border-radius:16px;overflow:hidden;box-shadow:0 2px 14px rgba(44,26,30,.05);">
    <div style="padding:14px 18px 10px;border-bottom:1px solid rgba(212,137,154,.12);display:flex;align-items:center;justify-content:space-between;">
      <h3 style="font-family:'Cormorant Garamond',Georgia,serif;font-size:15px;font-weight:700;color:#2C1A1E;">Kategori Bunga</h3>
      <div style="display:flex;gap:4px;">
        <button onclick="slideCatTangerang(-1)"
                style="width:26px;height:26px;border-radius:50%;background:rgba(212,137,154,.1);border:1px solid rgba(212,137,154,.25);color:#C8778A;font-size:14px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s ease;"
                onmouseover="this.style.background='linear-gradient(135deg,#C8778A,#D4899A)';this.style.color='#fff';"
                onmouseout="this.style.background='rgba(212,137,154,.1)';this.style.color='#C8778A';">‹</button>
        <button onclick="slideCatTangerang(1)"
                style="width:26px;height:26px;border-radius:50%;background:rgba(212,137,154,.1);border:1px solid rgba(212,137,154,.25);color:#C8778A;font-size:14px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s ease;"
                onmouseover="this.style.background='linear-gradient(135deg,#C8778A,#D4899A)';this.style.color='#fff';"
                onmouseout="this.style.background='rgba(212,137,154,.1)';this.style.color='#C8778A';">›</button>
      </div>
    </div>
    <div style="padding:12px;">
      <div style="overflow:hidden;" id="cat-slider-track-tangerang">
        <div id="cat-slider-inner-tangerang" style="display:flex;gap:8px;transition:transform .3s ease;will-change:transform;">
          <?php foreach ($sidebar_categories as $sc):
            $cat_img = !empty($sc['image']) && file_exists(UPLOAD_DIR . $sc['image'])
                       ? UPLOAD_URL . $sc['image']
                       : 'https://images.unsplash.com/photo-1490750967868-88df5691cc69?w=120&h=120&fit=crop';
          ?>
          <a href="<?= BASE_URL ?>/<?= e($sc['slug']) ?>/"
             style="flex-shrink:0;width:calc(50% - 4px);text-align:center;text-decoration:none;display:block;">
            <div style="aspect-ratio:1/1;border-radius:12px;overflow:hidden;margin-bottom:7px;border:1px solid rgba(212,137,154,.15);transition:border-color .2s,transform .4s;"
                 onmouseover="this.style.borderColor='rgba(212,137,154,.45)';this.querySelector('img').style.transform='scale(1.07)';"
                 onmouseout="this.style.borderColor='rgba(212,137,154,.15)';this.querySelector('img').style.transform='scale(1)';">
              <img src="<?= e($cat_img) ?>" alt="<?= e($sc['name']) ?>"
                   style="width:100%;height:100%;object-fit:cover;transition:transform .5s ease;" loading="lazy">
            </div>
            <p style="font-size:11px;font-weight:600;color:#2C1A1E;line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;padding:0 4px;">
              <?= e($sc['name']) ?>
            </p>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
      <!-- Dots -->
      <div id="cat-dots-tangerang" style="display:flex;justify-content:center;gap:5px;margin-top:10px;"></div>
    </div>
  </div>
  <?php endif; ?>

  <!-- ── Produk Searchable ── -->
  <?php if (!empty($sidebar_products)): ?>
  <div style="background:#fff;border:1px solid rgba(212,137,154,.18);border-radius:16px;overflow:hidden;box-shadow:0 2px 14px rgba(44,26,30,.05);">
    <div style="padding:14px 18px 10px;border-bottom:1px solid rgba(212,137,154,.12);">
      <h3 style="font-family:'Cormorant Garamond',Georgia,serif;font-size:15px;font-weight:700;color:#2C1A1E;">Produk Kami</h3>
    </div>
    <div style="padding:10px 14px 8px;">
      <input type="text" id="sidebar-prod-search-tng"
             placeholder="Cari produk..."
             style="width:100%;padding:8px 14px;font-size:13px;border:1px solid rgba(212,137,154,.25);border-radius:30px;outline:none;color:#2C1A1E;background:rgba(242,196,206,.06);transition:border-color .2s,box-shadow .2s;"
             onfocus="this.style.borderColor='rgba(200,119,138,.5)';this.style.boxShadow='0 0 0 3px rgba(212,137,154,.12)';"
             onblur="this.style.borderColor='rgba(212,137,154,.25)';this.style.boxShadow='none';">
    </div>
    <div id="sidebar-prod-list-tng" style="padding:4px 10px 10px;max-height:280px;overflow-y:auto;">
      <?php foreach ($sidebar_products as $prod):
        $thumb = !empty($prod['image']) && file_exists(UPLOAD_DIR . $prod['image'])
                 ? UPLOAD_URL . $prod['image']
                 : 'https://images.unsplash.com/photo-1487530811015-780780dde0e4?w=80&h=80&fit=crop';
        $wa_prod = urlencode("Halo, saya tertarik memesan *{$prod['name']}*. Apakah masih tersedia?");
      ?>
      <a href="<?= e($wa_url) ?>?text=<?= $wa_prod ?>" target="_blank"
         class="sidebar-prod-item-tng"
         data-name="<?= strtolower(e($prod['name'])) ?>"
         style="display:flex;align-items:center;gap:10px;padding:8px;border-radius:12px;text-decoration:none;margin-bottom:2px;transition:background .2s ease;"
         onmouseover="this.style.background='rgba(242,196,206,.15)';"
         onmouseout="this.style.background='transparent';">
        <img src="<?= e($thumb) ?>" alt="<?= e($prod['name']) ?>"
             style="width:44px;height:44px;border-radius:10px;object-fit:cover;flex-shrink:0;border:1px solid rgba(212,137,154,.15);">
        <div style="flex:1;min-width:0;">
          <p style="font-size:12px;font-weight:600;color:#2C1A1E;line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin-bottom:3px;"><?= e($prod['name']) ?></p>
          <p style="font-size:11px;font-weight:700;color:#C8778A;"><?= rupiah($prod['price']) ?></p>
        </div>
        <svg style="width:16px;height:16px;flex-shrink:0;color:#22c55e;opacity:.7;" fill="currentColor" viewBox="0 0 24 24">
          <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
          <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
        </svg>
      </a>
      <?php endforeach; ?>
      <p id="sidebar-prod-nores-tng" style="display:none;text-align:center;font-size:12px;color:rgba(44,26,30,.3);padding:12px 0;">Produk tidak ditemukan</p>
    </div>
  </div>
  <?php endif; ?>

  <!-- ── CTA WhatsApp ── -->
  <div style="background:linear-gradient(135deg,rgba(242,196,206,.4) 0%,rgba(212,137,154,.15) 100%);border:1px solid rgba(212,137,154,.3);border-radius:16px;padding:18px;text-align:center;position:relative;overflow:hidden;">
    <div style="position:absolute;top:-30px;right:-30px;width:100px;height:100px;background:radial-gradient(circle,rgba(242,196,206,.6) 0%,transparent 65%);pointer-events:none;"></div>
    <div style="font-size:26px;margin-bottom:8px;">💬</div>
    <p style="font-family:'Cormorant Garamond',Georgia,serif;font-weight:700;color:#2C1A1E;font-size:15px;margin-bottom:4px;">Mau Pesan Bunga?</p>
    <p style="font-size:11px;color:rgba(44,26,30,.5);margin-bottom:14px;line-height:1.55;">Konsultasi gratis via WhatsApp. Siap 24 jam!</p>
    <a href="<?= e($wa_url) ?>" target="_blank"
       style="display:block;background:linear-gradient(135deg,#C8778A,#D4899A);color:#fff;font-size:12px;font-weight:700;padding:10px;border-radius:30px;text-decoration:none;letter-spacing:.03em;box-shadow:0 4px 14px rgba(200,119,138,.3);">
      Chat WhatsApp Sekarang
    </a>
  </div>

  <!-- ── Artikel Terbaru ── -->
  <?php if (!empty($sidebar_recent)): ?>
  <div style="background:#fff;border:1px solid rgba(212,137,154,.15);border-radius:16px;overflow:hidden;box-shadow:0 2px 14px rgba(44,26,30,.04);">
    <div style="padding:12px 16px 10px;border-bottom:1px solid rgba(212,137,154,.1);background:rgba(242,196,206,.04);">
      <h3 style="font-family:'Cormorant Garamond',Georgia,serif;font-size:14px;font-weight:700;color:#2C1A1E;">Artikel Terbaru</h3>
    </div>
    <div style="padding:8px 12px;">
      <?php foreach ($sidebar_recent as $sr):
        $sr_thumb = !empty($sr['thumbnail']) && file_exists(UPLOAD_DIR . $sr['thumbnail'])
                    ? UPLOAD_URL . $sr['thumbnail']
                    : 'https://images.unsplash.com/photo-1487530811015-780780dde0e4?w=80&h=80&fit=crop';
      ?>
      <a href="<?= BASE_URL ?>/blog/<?= e($sr['slug']) ?>/"
         style="display:flex;gap:10px;align-items:flex-start;padding:8px 0;border-bottom:1px solid rgba(212,137,154,.08);text-decoration:none;">
        <div style="flex-shrink:0;width:48px;height:48px;border-radius:10px;overflow:hidden;border:1px solid rgba(212,137,154,.15);">
          <img src="<?= e($sr_thumb) ?>" alt="" style="width:100%;height:100%;object-fit:cover;" loading="lazy">
        </div>
        <div style="flex:1;min-width:0;">
          <?php if ($sr['cat_name']): ?>
          <span style="font-size:9px;font-weight:700;color:rgba(200,119,138,.65);text-transform:uppercase;letter-spacing:.06em;"><?= e($sr['cat_name']) ?></span>
          <?php endif; ?>
          <p style="font-size:12px;font-weight:600;color:rgba(44,26,30,.8);line-height:1.35;margin-top:2px;display:-webkit-box;-webkit-line-clamp:2;line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;"><?= e($sr['title']) ?></p>
          <p style="font-size:10px;color:rgba(44,26,30,.3);margin-top:3px;"><?= date('d M Y', strtotime($sr['created_at'])) ?></p>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

<!-- ── Area Pengiriman ── -->
<div style="background:#fff;border:1px solid rgba(212,137,154,.15);border-radius:16px;padding:14px 16px;box-shadow:0 2px 14px rgba(44,26,30,.04);">
  <h3 style="font-family:'Cormorant Garamond',Georgia,serif;font-size:14px;font-weight:700;color:#2C1A1E;margin-bottom:10px;display:flex;align-items:center;gap:6px;">
    <span>📍</span> Area Pengiriman
  </h3>

  <?php
  $tng_desk_per_page = 10;
  $tng_desk_total    = count($locations);
  $tng_desk_pages    = (int)ceil($tng_desk_total / $tng_desk_per_page);
  ?>

  <?php for ($p = 0; $p < $tng_desk_pages; $p++): ?>
  <div id="tngDeskAreaPage<?= $p ?>"
       style="display:<?= $p === 0 ? 'flex' : 'none' ?>;
              flex-direction:column;gap:3px; min-height:60px;">
    <?php
    $slice = array_slice($locations, $p * $tng_desk_per_page, $tng_desk_per_page);
    foreach ($slice as $l):
    ?>
    <a href="<?= BASE_URL ?>/<?= e($l['slug']) ?>/"
       style="font-size:12px;color:rgba(44,26,30,.45);text-decoration:none;
              padding:4px 0;display:flex;align-items:center;gap:8px;
              border-bottom:1px solid rgba(212,137,154,.07);"
       onmouseenter="this.style.color='#C8778A'"
       onmouseleave="this.style.color='rgba(44,26,30,.45)'">
      <span style="width:4px;height:4px;border-radius:50%;background:rgba(212,137,154,.45);
                   flex-shrink:0;display:inline-block;"></span>
      <?= e($l['name']) ?>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endfor; ?>

  <?php if ($tng_desk_pages > 1): ?>
  <div style="display:flex;align-items:center;justify-content:space-between;margin-top:12px;padding-top:10px;border-top:1px solid rgba(212,137,154,.12);">
    <button id="tngDeskAreaPrev" onclick="tngDeskAreaSlider(-1)"
            style="font-size:11px;padding:4px 12px;border-radius:8px;
                   border:1px solid rgba(212,137,154,.25);background:#fff;
                   color:rgba(44,26,30,.45);cursor:pointer;">
      ‹ Prev
    </button>

    <div style="display:flex;gap:4px;align-items:center;">
      <?php for ($p = 0; $p < $tng_desk_pages; $p++): ?>
      <span id="tngDeskAreaDot<?= $p ?>" onclick="tngDeskAreaGoPage(<?= $p ?>)"
            style="display:inline-block;height:5px;border-radius:3px;cursor:pointer;transition:all .2s;
                   width:<?= $p === 0 ? '16px' : '5px' ?>;
                   background:<?= $p === 0 ? '#C8778A' : 'rgba(212,137,154,.25)' ?>;"></span>
      <?php endfor; ?>
    </div>

    <button id="tngDeskAreaNext" onclick="tngDeskAreaSlider(1)"
            style="font-size:11px;padding:4px 12px;border-radius:8px;
                   border:1px solid rgba(212,137,154,.25);background:#fff;
                   color:rgba(44,26,30,.45);cursor:pointer;">
      Next ›
    </button>
  </div>
  <p id="tngDeskAreaInfo" style="text-align:center;font-size:11px;color:rgba(44,26,30,.3);margin-top:5px;"></p>
  <?php endif; ?>
</div>

</div>

<!-- ── Scripts sidebar ── -->
<script>
/* Product search */
(function(){
  const input = document.getElementById('sidebar-prod-search-tng');
  const items = document.querySelectorAll('.sidebar-prod-item-tng');
  const noRes = document.getElementById('sidebar-prod-nores-tng');
  if (!input) return;
  input.addEventListener('input', function(){
    const q = this.value.toLowerCase().trim();
    let visible = 0;
    items.forEach(item => {
      const show = !q || item.dataset.name.includes(q);
      item.style.display = show ? '' : 'none';
      if (show) visible++;
    });
    noRes.style.display = visible > 0 ? 'none' : 'block';
  });
})();

/* Category slider */
(function(){
  const inner  = document.getElementById('cat-slider-inner-tangerang');
  const dotsEl = document.getElementById('cat-dots-tangerang');
  if (!inner) return;

  const items   = inner.querySelectorAll('a');
  const perPage = 2;
  const pages   = Math.ceil(items.length / perPage);
  let current   = 0;

  for (let i = 0; i < pages; i++) {
    const d = document.createElement('button');
    d.style.cssText = `width:${i===0?'16px':'6px'};height:6px;border-radius:3px;border:none;cursor:pointer;transition:all .25s ease;background:${i===0?'linear-gradient(135deg,#C8778A,#D4899A)':'rgba(212,137,154,.25)'};padding:0;`;
    d.onclick = () => goTo(i);
    dotsEl.appendChild(d);
  }

  function goTo(idx) {
    current = Math.max(0, Math.min(idx, pages - 1));
    const trackW = inner.parentElement.offsetWidth;
    inner.style.transform = `translateX(-${current * (trackW + 8)}px)`;
    dotsEl.querySelectorAll('button').forEach((d, i) => {
      d.style.width      = i === current ? '16px' : '6px';
      d.style.background = i === current ? 'linear-gradient(135deg,#C8778A,#D4899A)' : 'rgba(212,137,154,.25)';
    });
  }

  window.slideCatTangerang = function(dir) { goTo(current + dir); };
})();
/* Area Pengiriman slider — Tangerang desktop */
(function(){
  var perPage = <?= $tng_desk_per_page ?>;
  var total   = <?= $tng_desk_total ?>;
  var pages   = <?= $tng_desk_pages ?>;
  var cur     = 0;

  function update() {
    for (var i = 0; i < pages; i++) {
      var el = document.getElementById('tngDeskAreaPage' + i);
      if (el) el.style.display = (i === cur) ? 'flex' : 'none';
    }
    for (var i = 0; i < pages; i++) {
      var dot = document.getElementById('tngDeskAreaDot' + i);
      if (!dot) continue;
      dot.style.width      = (i === cur) ? '16px' : '5px';
      dot.style.background = (i === cur) ? '#C8778A' : 'rgba(212,137,154,.25)';
    }
    var prev = document.getElementById('tngDeskAreaPrev');
    var next = document.getElementById('tngDeskAreaNext');
    if (prev) {
      prev.disabled      = (cur === 0);
      prev.style.opacity = (cur === 0) ? '0.35' : '1';
      prev.style.cursor  = (cur === 0) ? 'not-allowed' : 'pointer';
      prev.onmouseenter  = function() { if (!prev.disabled) { prev.style.background='rgba(212,137,154,.1)'; prev.style.borderColor='rgba(212,137,154,.4)'; prev.style.color='#C8778A'; }};
      prev.onmouseleave  = function() { prev.style.background='#fff'; prev.style.borderColor='rgba(212,137,154,.25)'; prev.style.color='rgba(44,26,30,.45)'; };
    }
    if (next) {
      next.disabled      = (cur === pages - 1);
      next.style.opacity = (cur === pages - 1) ? '0.35' : '1';
      next.style.cursor  = (cur === pages - 1) ? 'not-allowed' : 'pointer';
      next.onmouseenter  = function() { if (!next.disabled) { next.style.background='rgba(212,137,154,.1)'; next.style.borderColor='rgba(212,137,154,.4)'; next.style.color='#C8778A'; }};
      next.onmouseleave  = function() { next.style.background='#fff'; next.style.borderColor='rgba(212,137,154,.25)'; next.style.color='rgba(44,26,30,.45)'; };
    }
    var info = document.getElementById('tngDeskAreaInfo');
    if (info) {
      var start = cur * perPage + 1;
      var end   = Math.min((cur + 1) * perPage, total);
      info.textContent = start + '–' + end + ' dari ' + total + ' area';
    }
  }

  window.tngDeskAreaSlider  = function(dir) { cur = Math.max(0, Math.min(pages - 1, cur + dir)); update(); };
  window.tngDeskAreaGoPage  = function(p)   { cur = p; update(); };

  update();
})();
</script>

<style>
#sidebar-prod-list-tng::-webkit-scrollbar { width:3px; }
#sidebar-prod-list-tng::-webkit-scrollbar-track { background:rgba(242,196,206,.15); border-radius:3px; }
#sidebar-prod-list-tng::-webkit-scrollbar-thumb { background:rgba(212,137,154,.4); border-radius:3px; }
#sidebar-prod-list-tng::-webkit-scrollbar-thumb:hover { background:#C8778A; }
</style>