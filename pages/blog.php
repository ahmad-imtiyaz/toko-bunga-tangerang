<?php
require_once __DIR__ . '/../includes/config.php';

$meta_title    = 'Blog - ' . setting('site_name');
$meta_desc     = 'Artikel, tips, dan inspirasi seputar bunga dari ' . setting('site_name') . '.';
$meta_keywords = 'blog bunga, tips bunga, inspirasi rangkaian, florist tangerang';

$filter_cat = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';
$search     = isset($_GET['q'])        ? trim($_GET['q'])        : '';

$per_page    = 9;
$page        = max(1, (int)($_GET['page'] ?? 1));
$offset      = ($page - 1) * $per_page;

$where  = ["b.status = 'active'"];
$params = [];
if ($filter_cat) { $where[] = 'bc.slug = ?'; $params[] = $filter_cat; }
if ($search)     { $where[] = '(b.title LIKE ? OR b.excerpt LIKE ?)'; $params[] = "%$search%"; $params[] = "%$search%"; }
$where_sql = implode(' AND ', $where);

$count_stmt = db()->prepare("SELECT COUNT(*) FROM blogs b LEFT JOIN blog_categories bc ON b.blog_category_id = bc.id WHERE $where_sql");
$count_stmt->execute($params);
$total      = (int)$count_stmt->fetchColumn();
$total_page = (int)ceil($total / $per_page);

$stmt = db()->prepare("
    SELECT b.*, bc.name AS cat_name, bc.slug AS cat_slug
    FROM blogs b
    LEFT JOIN blog_categories bc ON b.blog_category_id = bc.id
    WHERE $where_sql
    ORDER BY b.urutan ASC, b.created_at DESC
    LIMIT $per_page OFFSET $offset
");
$stmt->execute($params);
$blogs = $stmt->fetchAll();

$blog_cats = db()->query("
    SELECT bc.*, COUNT(b.id) AS total
    FROM blog_categories bc
    LEFT JOIN blogs b ON b.blog_category_id = bc.id AND b.status = 'active'
    WHERE bc.status = 'active'
    GROUP BY bc.id ORDER BY bc.urutan ASC
")->fetchAll();

$locations = db()->query("SELECT * FROM locations WHERE status='active' ORDER BY id")->fetchAll();
$wa_url    = setting('whatsapp_url');

require __DIR__ . '/../includes/header.php';
?>

<style>
:root {
  --blush: #F2C4CE; --rose: #D4899A; --dusty: #C8778A;
  --cream: #FAF5EE; --ivory: #FDF9F4; --soft: #F7EEF0;
  --dark:  #2C1A1E; --muted: rgba(44,26,30,.5);
}

@keyframes shimmer-x { 0%{background-position:-200% center} 100%{background-position:200% center} }
@keyframes ticker    { from{transform:translateX(0)} to{transform:translateX(-50%)} }
@keyframes fadeUp    { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
@keyframes floatPetal{
  0%,100%{transform:translateY(0) rotate(0deg);opacity:.25}
  50%{transform:translateY(-22px) rotate(10deg);opacity:.45}
}

.rose-line-blog {
  height:1px;
  background:linear-gradient(90deg,transparent,var(--rose),var(--blush),var(--rose),transparent);
  background-size:200% auto; animation:shimmer-x 3.5s linear infinite;
}
.blog-ticker-inner { animation:ticker 22s linear infinite; display:flex; white-space:nowrap; }
.float-petal { position:absolute; pointer-events:none; user-select:none; font-size:16px; animation:floatPetal var(--dur,8s) ease-in-out var(--del,0s) infinite; opacity:.25; }
.reveal { animation:fadeUp .6s ease both; }
.reveal-1{animation-delay:.08s} .reveal-2{animation-delay:.18s} .reveal-3{animation-delay:.3s}

/* ── Blog article card ── */
.blog-article-card {
  display:flex; flex-direction:row; align-items:stretch;
  padding:20px 0; border-bottom:1px solid rgba(212,137,154,.12);
  transition:background .25s ease;
}
.blog-article-card:hover { background:rgba(242,196,206,.04); border-radius:12px; padding-left:10px; padding-right:10px; margin:0 -10px; }
.blog-thumb-link {
  flex-shrink:0; width:195px; height:135px; border-radius:14px;
  overflow:hidden; position:relative; display:block;
  background:var(--soft); border:1px solid rgba(212,137,154,.15);
}
.blog-thumb-link img { width:100%; height:100%; object-fit:cover; transition:transform .6s cubic-bezier(.4,0,.2,1); }
.blog-article-card:hover .blog-thumb-link img { transform:scale(1.07); }
.blog-read-badge {
  position:absolute; bottom:8px; right:8px;
  background:rgba(44,26,30,.7); color:var(--blush);
  font-size:10px; font-weight:700; padding:2px 8px; border-radius:20px;
  backdrop-filter:blur(4px);
}

/* ── Filter pills ── */
.blog-filter-pill {
  font-size:11px; font-weight:700; padding:5px 14px; border-radius:20px;
  text-transform:uppercase; letter-spacing:.05em; text-decoration:none;
  border:1px solid rgba(212,137,154,.35); color:var(--muted);
  transition:all .2s ease;
}
.blog-filter-pill:hover, .blog-filter-pill.active {
  background:linear-gradient(135deg,var(--blush),var(--dusty));
  color:#fff; border-color:transparent;
}

/* ── Pagination ── */
.blog-page-btn {
  width:36px; height:36px; display:flex; align-items:center; justify-content:center;
  border-radius:50%; font-size:13px; font-weight:700; text-decoration:none;
  border:1px solid rgba(212,137,154,.25); color:var(--muted); transition:all .2s;
}
.blog-page-btn:hover, .blog-page-btn.active {
  background:linear-gradient(135deg,var(--blush),var(--dusty)); color:#fff; border-color:transparent;
}

@media(max-width:1023px){
  .blog-sidebar-desktop { display:none !important; }
  .blog-main-grid { grid-template-columns:1fr !important; }
}
@media(max-width:640px){
  .blog-thumb-link { width:110px; height:110px; }
}
</style>

<?php
function blogPetals(int $n = 10): string {
  $out = ''; $fl = ['🌸','🌷','🌺','🌼'];
  for ($i = 0; $i < $n; $i++) {
    $e = $fl[$i % 4]; $top = rand(2,95); $left = rand(2,95);
    $dur = rand(6,13); $del = rand(0,7);
    $out .= "<span class=\"float-petal\" style=\"top:{$top}%;left:{$left}%;--dur:{$dur}s;--del:{$del}s;\">{$e}</span>";
  }
  return $out;
}
?>

<!-- ════════════ HERO ════════════ -->
<section class="reveal reveal-1" style="background:var(--ivory);position:relative;overflow:hidden;padding:72px 24px 68px;text-align:center;">
  <?= blogPetals(14) ?>

  <!-- Blobs -->
  <div style="position:absolute;top:-60px;right:-80px;width:480px;height:480px;background:radial-gradient(circle,rgba(242,196,206,.45),transparent 65%);filter:blur(70px);pointer-events:none;"></div>
  <div style="position:absolute;bottom:-40px;left:-60px;width:360px;height:360px;background:radial-gradient(circle,rgba(200,119,138,.15),transparent 65%);filter:blur(80px);pointer-events:none;"></div>

  <!-- Dot pattern -->
  <div style="position:absolute;inset:0;opacity:.04;background-image:radial-gradient(circle,var(--rose) 1px,transparent 1px);background-size:38px 38px;pointer-events:none;"></div>

  <div style="position:relative;z-index:2;max-width:640px;margin:0 auto;">

    <!-- Badge -->
    <div class="reveal reveal-1" style="display:inline-flex;align-items:center;gap:8px;background:rgba(212,137,154,.1);border:1px solid rgba(212,137,154,.3);border-radius:20px;padding:5px 16px;margin-bottom:18px;">
      <span style="width:7px;height:7px;border-radius:50%;background:var(--rose);display:inline-block;animation:shimmer-x 2s linear infinite;"></span>
      <span style="font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--dusty);">Artikel &amp; Inspirasi</span>
    </div>

    <!-- Judul -->
    <h1 class="reveal reveal-2" style="font-family:'Cormorant Garamond',Georgia,serif;font-size:clamp(34px,6vw,56px);font-weight:700;color:var(--dark);line-height:1.1;margin-bottom:14px;letter-spacing:-1px;">
      Blog <em style="font-style:italic;background:linear-gradient(135deg,var(--dusty),var(--rose),var(--blush));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Bunga</em>
    </h1>
    <p style="font-size:11px;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:rgba(200,119,138,.55);margin-bottom:16px;">Florist Tangerang</p>

    <!-- Desc -->
    <p class="reveal reveal-2" style="font-size:14px;color:var(--muted);line-height:1.8;margin-bottom:30px;max-width:440px;margin-left:auto;margin-right:auto;">
      Tips merawat bunga, inspirasi rangkaian, dan cerita seputar dunia florist dari <?= e(setting('site_name')) ?>.
    </p>

    <!-- Search -->
    <form class="reveal reveal-3" method="GET" action="<?= BASE_URL ?>/blog/"
          style="display:flex;max-width:480px;margin:0 auto;border-radius:14px;overflow:hidden;border:1.5px solid rgba(212,137,154,.3);background:#fff;box-shadow:0 4px 20px rgba(212,137,154,.12);">
      <input type="text" name="q" value="<?= e($search) ?>" placeholder="Cari artikel, tips, inspirasi..."
             style="flex:1;padding:14px 20px;font-size:14px;background:transparent;color:var(--dark);border:none;outline:none;min-width:0;">
      <button type="submit"
              style="padding:14px 24px;background:linear-gradient(135deg,var(--dusty),var(--rose));color:#fff;font-size:13px;font-weight:700;border:none;cursor:pointer;white-space:nowrap;letter-spacing:.03em;">
        Cari
      </button>
    </form>

    <!-- Stats -->
    <div class="reveal reveal-3" style="display:flex;justify-content:center;gap:32px;margin-top:28px;align-items:center;">
      <div style="text-align:center;">
        <div style="font-family:'Cormorant Garamond',Georgia,serif;font-size:22px;font-weight:700;background:linear-gradient(135deg,var(--dusty),var(--rose));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;"><?= $total ?></div>
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--muted);margin-top:2px;">Artikel</div>
      </div>
      <div style="width:1px;background:rgba(212,137,154,.25);height:36px;"></div>
      <div style="text-align:center;">
        <div style="font-family:'Cormorant Garamond',Georgia,serif;font-size:22px;font-weight:700;background:linear-gradient(135deg,var(--dusty),var(--rose));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;"><?= count($blog_cats) ?></div>
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--muted);margin-top:2px;">Kategori</div>
      </div>
      <div style="width:1px;background:rgba(212,137,154,.25);height:36px;"></div>
      <div style="text-align:center;">
        <div style="font-family:'Cormorant Garamond',Georgia,serif;font-size:22px;font-weight:700;background:linear-gradient(135deg,var(--dusty),var(--rose));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Gratis</div>
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--muted);margin-top:2px;">Untuk semua</div>
      </div>
    </div>
  </div>

  <div class="rose-line-blog" style="position:absolute;bottom:0;left:0;right:0;"></div>
</section>

<!-- ════════════ TICKER ════════════ -->
<div style="background:linear-gradient(135deg,var(--blush),var(--dusty));overflow:hidden;padding:10px 0;">
  <div class="blog-ticker-inner">
    <?php for ($r = 0; $r < 2; $r++): foreach ($blog_cats as $bc): ?>
    <a href="<?= BASE_URL ?>/blog/?kategori=<?= e($bc['slug']) ?>"
       style="display:inline-flex;align-items:center;gap:10px;margin:0 20px;color:rgba(255,255,255,.9);font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;text-decoration:none;white-space:nowrap;">
      <span style="width:4px;height:4px;border-radius:50%;background:rgba(255,255,255,.4);display:inline-block;flex-shrink:0;"></span>
      <?= e($bc['name']) ?>
    </a>
    <?php endforeach; endfor; ?>
  </div>
</div>

<!-- ════════════ MAIN ════════════ -->
<section style="background:var(--cream);padding:44px 0 64px;">
  <div style="max-width:1280px;margin:0 auto;padding:0 24px;">
    <div class="blog-main-grid" style="display:grid;grid-template-columns:1fr 296px;gap:44px;align-items:start;">

      <!-- ── ARTIKEL ── -->
      <div style="min-width:0;">

        <!-- Filter pills -->
        <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:24px;">
          <a href="<?= BASE_URL ?>/blog/" class="blog-filter-pill <?= !$filter_cat ? 'active' : '' ?>">Semua</a>
          <?php foreach ($blog_cats as $bc): ?>
          <a href="<?= BASE_URL ?>/blog/?kategori=<?= e($bc['slug']) ?>"
             class="blog-filter-pill <?= $filter_cat === $bc['slug'] ? 'active' : '' ?>">
            <?= e($bc['name']) ?> <span style="opacity:.6;">(<?= $bc['total'] ?>)</span>
          </a>
          <?php endforeach; ?>
        </div>

        <?php if ($search): ?>
        <p style="font-size:13px;color:var(--muted);margin-bottom:20px;">
          Hasil pencarian: <strong style="color:var(--dusty);">"<?= e($search) ?>"</strong> — <?= $total ?> artikel.
          <a href="<?= BASE_URL ?>/blog/" style="color:var(--rose);margin-left:8px;">Reset</a>
        </p>
        <?php endif; ?>

        <?php if (empty($blogs)): ?>
        <div style="text-align:center;padding:72px 0;color:var(--muted);">
          <div style="font-size:52px;margin-bottom:12px;">🌸</div>
          <p style="font-size:16px;">Belum ada artikel ditemukan.</p>
        </div>
        <?php else: ?>

        <!-- Divider -->
        <div style="height:1px;background:linear-gradient(90deg,rgba(212,137,154,.4),transparent);margin-bottom:8px;"></div>

        <!-- List -->
        <div style="display:flex;flex-direction:column;">
          <?php
          $cat_colors = [
            'informasi'  => 'background:rgba(100,149,237,.1);color:#4169B8;border:1px solid rgba(100,149,237,.25);',
            'tips'       => 'background:rgba(82,168,120,.1);color:#2E7A50;border:1px solid rgba(82,168,120,.25);',
            'pernikahan' => 'background:rgba(212,137,154,.12);color:var(--dusty);border:1px solid rgba(212,137,154,.3);',
            'dekorasi'   => 'background:rgba(210,150,80,.1);color:#9A6320;border:1px solid rgba(210,150,80,.25);',
            'perawatan'  => 'background:rgba(100,180,160,.1);color:#2A7A68;border:1px solid rgba(100,180,160,.25);',
          ];

          foreach ($blogs as $blog):
            $thumb = !empty($blog['thumbnail']) && file_exists(UPLOAD_DIR . $blog['thumbnail'])
                     ? UPLOAD_URL . $blog['thumbnail']
                     : 'https://images.unsplash.com/photo-1487530811015-780780dde0e4?w=400&h=280&fit=crop';

            $updated      = $blog['updated_at'] ?? $blog['created_at'];
            $date_label   = date('d M Y', strtotime($updated));
            $content_text = strip_tags($blog['content'] ?? '');
            $char_count   = mb_strlen($content_text);
            $char_label   = $char_count >= 1000 ? round($char_count / 1000, 1) . 'k karakter' : $char_count . ' karakter';
            $read_min     = max(1, ceil($char_count / 1000));
            $cat_key      = strtolower($blog['cat_slug'] ?? '');
            $badge_style  = $cat_colors[$cat_key] ?? 'background:rgba(212,137,154,.12);color:var(--dusty);border:1px solid rgba(212,137,154,.3);';
          ?>
          <article class="blog-article-card">

            <!-- Thumb -->
            <a href="<?= BASE_URL ?>/blog/<?= e($blog['slug']) ?>/" class="blog-thumb-link">
              <img src="<?= e($thumb) ?>" alt="<?= e($blog['title']) ?>" loading="lazy">
              <span class="blog-read-badge"><?= $read_min ?> mnt</span>
            </a>

            <!-- Body -->
            <div style="flex:1;padding-left:18px;display:flex;flex-direction:column;justify-content:space-between;min-width:0;">
              <div>
                <!-- Badges -->
                <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;margin-bottom:8px;">
                  <?php if ($blog['cat_name']): ?>
                  <a href="<?= BASE_URL ?>/blog/?kategori=<?= e($blog['cat_slug']) ?>"
                     style="font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;text-transform:uppercase;letter-spacing:.05em;text-decoration:none;<?= $badge_style ?>">
                    <?= e($blog['cat_name']) ?>
                  </a>
                  <?php endif; ?>
                  <span style="font-size:10px;background:rgba(212,137,154,.08);color:var(--muted);padding:2px 8px;border-radius:20px;border:1px solid rgba(212,137,154,.15);">
                    <?= $char_label ?>
                  </span>
                </div>

                <!-- Judul -->
                <h2 style="font-family:'Cormorant Garamond',Georgia,serif;font-size:16px;font-weight:700;color:var(--dark);line-height:1.35;margin-bottom:6px;display:-webkit-box;-webkit-line-clamp:2;line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                  <a href="<?= BASE_URL ?>/blog/<?= e($blog['slug']) ?>/" style="color:inherit;text-decoration:none;">
                    <?= e($blog['title']) ?>
                  </a>
                </h2>

                <!-- Excerpt -->
                <?php if ($blog['excerpt']): ?>
                <p style="font-size:12px;color:var(--muted);line-height:1.65;margin:0;display:-webkit-box;-webkit-line-clamp:2;line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                  <?= e($blog['excerpt']) ?>
                </p>
                <?php endif; ?>
              </div>

              <!-- Meta bawah -->
              <div style="display:flex;align-items:center;gap:10px;margin-top:10px;flex-wrap:wrap;">
                <span style="font-size:11px;color:rgba(44,26,30,.35);">Diperbarui <?= $date_label ?></span>
                <span style="width:3px;height:3px;border-radius:50%;background:rgba(212,137,154,.4);"></span>
                <a href="<?= BASE_URL ?>/blog/<?= e($blog['slug']) ?>/"
                   style="font-size:11px;font-weight:700;color:var(--dusty);text-decoration:none;letter-spacing:.02em;">
                  Baca selengkapnya →
                </a>
              </div>
            </div>

          </article>
          <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_page > 1): ?>
        <div style="display:flex;justify-content:center;gap:6px;margin-top:36px;flex-wrap:wrap;">
          <?php for ($p = 1; $p <= $total_page; $p++):
            $q_arr = array_filter(['kategori' => $filter_cat, 'q' => $search, 'page' => $p > 1 ? $p : null]);
            $qs    = $q_arr ? '?' . http_build_query($q_arr) : '';
          ?>
          <a href="<?= BASE_URL ?>/blog/<?= $qs ?>"
             class="blog-page-btn <?= $p === $page ? 'active' : '' ?>">
            <?= $p ?>
          </a>
          <?php endfor; ?>
        </div>
        <?php endif; ?>

        <?php endif; ?>
      </div>

      <!-- ── SIDEBAR DESKTOP ── -->
      <aside class="blog-sidebar-desktop" style="position:sticky;top:90px;">
        <?php include __DIR__ . '/sections/blog-sidebar.php'; ?>
      </aside>

    </div>
  </div>
</section>

<!-- Sidebar mobile -->
<div id="blog-sidebar-mobile-wrap">
  <?php include __DIR__ . '/sections/blog-sidebar-mobile.php'; ?>
</div>
<style>@media(min-width:1024px){#blog-sidebar-mobile-wrap{display:none !important;}}</style>

<?php require __DIR__ . '/../includes/footer.php'; ?>