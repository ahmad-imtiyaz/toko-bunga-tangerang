<?php
require_once __DIR__ . '/../includes/config.php';

if (empty($blog)) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$meta_title    = !empty($blog['meta_title'])    ? $blog['meta_title']    : $blog['title'] . ' - ' . setting('site_name');
$meta_desc     = !empty($blog['meta_desc'])     ? $blog['meta_desc']     : ($blog['excerpt'] ?? '');
$meta_keywords = !empty($blog['meta_keywords']) ? $blog['meta_keywords'] : $blog['title'];

$related = [];
if (!empty($blog['blog_category_id'])) {
    $stmt = db()->prepare("
        SELECT b.id, b.title, b.slug, b.thumbnail, b.excerpt, b.created_at,
               bc.name AS cat_name, bc.slug AS cat_slug
        FROM blogs b
        LEFT JOIN blog_categories bc ON b.blog_category_id = bc.id
        WHERE b.blog_category_id = ? AND b.id != ? AND b.status = 'active'
        ORDER BY b.created_at DESC LIMIT 3
    ");
    $stmt->execute([$blog['blog_category_id'], $blog['id']]);
    $related = $stmt->fetchAll();
}

$blog_cats = db()->query("
    SELECT bc.*, COUNT(b.id) AS total
    FROM blog_categories bc
    LEFT JOIN blogs b ON b.blog_category_id = bc.id AND b.status = 'active'
    WHERE bc.status = 'active'
    GROUP BY bc.id ORDER BY bc.urutan ASC
")->fetchAll();

$locations  = db()->query("SELECT * FROM locations WHERE status='active' ORDER BY id")->fetchAll();
$wa_url     = setting('whatsapp_url');
$filter_cat = $blog['cat_slug'] ?? '';

$thumb_url    = !empty($blog['thumbnail']) && file_exists(UPLOAD_DIR . $blog['thumbnail'])
                ? UPLOAD_URL . $blog['thumbnail']
                : 'https://images.unsplash.com/photo-1487530811015-780780dde0e4?w=1200&h=630&fit=crop';

$content_text = strip_tags($blog['content'] ?? '');
$read_min     = max(1, ceil(mb_strlen($content_text) / 1000));
$char_count   = mb_strlen($content_text);
$char_label   = $char_count >= 1000 ? round($char_count / 1000, 1) . 'k karakter' : $char_count . ' karakter';
$updated      = $blog['updated_at'] ?? $blog['created_at'];

require __DIR__ . '/../includes/header.php';
?>

<style>
:root {
  --blush:#F2C4CE; --rose:#D4899A; --dusty:#C8778A;
  --cream:#FAF5EE; --ivory:#FDF9F4; --soft:#F7EEF0;
  --dark:#2C1A1E; --muted:rgba(44,26,30,.5);
}
html,body{overflow-x:hidden !important;}
*{box-sizing:border-box;}

@keyframes shimmer-x{0%{background-position:-200% center}100%{background-position:200% center}}
.rose-line-det{height:1px;background:linear-gradient(90deg,transparent,var(--rose),var(--blush),var(--rose),transparent);background-size:200% auto;animation:shimmer-x 3.5s linear infinite;}

/* ── Blog content styling ── */
.blog-content{word-break:break-word;overflow-wrap:break-word;max-width:100%;}
.blog-content *{box-sizing:border-box;}
.blog-content img{max-width:100%;height:auto;border-radius:12px;margin:1.25rem auto;display:block;border:1px solid rgba(212,137,154,.2);box-shadow:0 4px 16px rgba(44,26,30,.08);}
.blog-content iframe,.blog-content video{max-width:100% !important;}
.blog-content table{display:block;width:100%;overflow-x:auto;border-collapse:collapse;margin:1.25rem 0;font-size:.82rem;}
.blog-content h1{font-family:'Cormorant Garamond',Georgia,serif;font-size:clamp(1.4rem,5vw,2rem);font-weight:700;color:var(--dark);margin:1.5rem 0 .75rem;line-height:1.25;}
.blog-content h2{font-family:'Cormorant Garamond',Georgia,serif;font-size:clamp(1.15rem,4vw,1.6rem);font-weight:700;color:var(--dark);margin:1.5rem 0 .75rem;border-bottom:1px solid rgba(212,137,154,.25);padding-bottom:.5rem;}
.blog-content h3{font-family:'Cormorant Garamond',Georgia,serif;font-size:clamp(1rem,3.5vw,1.3rem);font-weight:700;color:var(--dusty);margin:1.25rem 0 .5rem;}
.blog-content h4,.blog-content h5,.blog-content h6{font-family:'Cormorant Garamond',Georgia,serif;font-weight:600;color:var(--dark);margin:1rem 0 .5rem;}
.blog-content p{margin:.75rem 0;line-height:1.85;font-size:clamp(.875rem,2.5vw,1rem);color:var(--muted);}
.blog-content ul,.blog-content ol{margin:.75rem 0 .75rem 1.25rem;}
.blog-content ul{list-style:disc;} .blog-content ol{list-style:decimal;}
.blog-content li{margin:.35rem 0;line-height:1.7;font-size:clamp(.875rem,2.5vw,1rem);color:var(--muted);}
.blog-content strong{color:var(--dark);font-weight:700;}
.blog-content em{color:var(--dusty);font-style:italic;}
.blog-content a{color:var(--dusty);text-decoration:underline;word-break:break-all;}
.blog-content a:hover{color:var(--rose);}
.blog-content blockquote{border-left:3px solid var(--rose);background:rgba(242,196,206,.08);padding:.75rem 1rem;margin:1rem 0;border-radius:0 10px 10px 0;font-style:italic;color:var(--muted);font-size:.9rem;}
.blog-content th{background:rgba(212,137,154,.1);color:var(--dusty);padding:8px 10px;text-align:left;white-space:nowrap;border:1px solid rgba(212,137,154,.2);}
.blog-content td{border:1px solid rgba(44,26,30,.08);padding:7px 10px;color:var(--muted);}
.blog-content tr:nth-child(even) td{background:rgba(242,196,206,.04);}
.blog-content pre{background:var(--soft);color:var(--dark);padding:1rem;border-radius:10px;overflow-x:auto;font-size:.8rem;margin:1.25rem 0;border:1px solid rgba(212,137,154,.15);}
.blog-content code{background:rgba(212,137,154,.1);color:var(--dusty);padding:2px 5px;border-radius:4px;font-size:.82em;}
.blog-content pre code{background:none;color:inherit;padding:0;}
.blog-content hr{border:none;border-top:1px solid rgba(212,137,154,.2);margin:1.5rem 0;}

@media(max-width:1023px){
  .blog-det-sidebar-desktop{display:none !important;}
  .blog-det-grid{grid-template-columns:1fr !important;}
}
</style>

<div style="overflow-x:hidden;width:100%;max-width:100vw;">

<!-- Breadcrumb -->
<div style="background:var(--soft);border-bottom:1px solid rgba(212,137,154,.15);">
  <div style="max-width:1280px;margin:0 auto;padding:12px 24px;">
    <nav style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;">
      <a href="<?= BASE_URL ?>/" style="color:var(--muted);text-decoration:none;">Beranda</a>
      <span style="color:rgba(212,137,154,.4);">—</span>
      <a href="<?= BASE_URL ?>/blog/" style="color:var(--muted);text-decoration:none;">Blog</a>
      <?php if (!empty($blog['cat_name'])): ?>
      <span style="color:rgba(212,137,154,.4);">—</span>
      <a href="<?= BASE_URL ?>/blog/?kategori=<?= e($blog['cat_slug']) ?>" style="color:var(--rose);text-decoration:none;"><?= e($blog['cat_name']) ?></a>
      <?php endif; ?>
      <span style="color:rgba(212,137,154,.4);">—</span>
      <span style="color:var(--dusty);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:180px;"><?= e($blog['title']) ?></span>
    </nav>
  </div>
</div>

<!-- Main -->
<section style="background:var(--cream);padding:40px 0 60px;">
  <div style="max-width:1280px;margin:0 auto;padding:0 24px;">
    <div class="blog-det-grid" style="display:grid;grid-template-columns:1fr 300px;gap:44px;align-items:start;">

      <!-- ══ ARTIKEL ══ -->
      <article style="min-width:0;max-width:100%;">

        <!-- Card utama -->
        <div style="background:#fff;border:1px solid rgba(212,137,154,.15);border-radius:20px;overflow:hidden;margin-bottom:20px;box-shadow:0 4px 24px rgba(44,26,30,.06);">

          <!-- Thumbnail -->
          <div style="width:100%;overflow:hidden;aspect-ratio:16/7;max-height:420px;">
            <img src="<?= e($thumb_url) ?>" alt="<?= e($blog['title']) ?>"
                 style="width:100%;height:100%;object-fit:cover;display:block;">
          </div>
          <div class="rose-line-det"></div>

          <div style="padding:28px 32px 36px;">

            <!-- Meta atas -->
            <div style="display:flex;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:16px;">
              <?php if (!empty($blog['cat_name'])): ?>
              <a href="<?= BASE_URL ?>/blog/?kategori=<?= e($blog['cat_slug']) ?>"
                 style="font-size:10px;font-weight:700;background:rgba(212,137,154,.12);color:var(--dusty);border:1px solid rgba(212,137,154,.3);padding:4px 12px;border-radius:20px;text-decoration:none;text-transform:uppercase;letter-spacing:.06em;">
                <?= e($blog['cat_name']) ?>
              </a>
              <?php endif; ?>
              <span style="font-size:11px;color:var(--muted);display:flex;align-items:center;gap:5px;">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="opacity:.5;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <?= date('d F Y', strtotime($blog['created_at'])) ?>
              </span>
              <span style="font-size:11px;color:var(--dusty);background:rgba(212,137,154,.1);padding:3px 10px;border-radius:20px;"><?= $read_min ?> mnt baca</span>
              <span style="font-size:11px;color:var(--muted);background:rgba(212,137,154,.06);padding:3px 10px;border-radius:20px;border:1px solid rgba(212,137,154,.15);"><?= $char_label ?></span>
            </div>

            <!-- Judul -->
            <h1 style="font-family:'Cormorant Garamond',Georgia,serif;font-weight:700;color:var(--dark);line-height:1.2;margin-bottom:16px;font-size:clamp(1.4rem,4vw,2.1rem);">
              <?= e($blog['title']) ?>
            </h1>

            <!-- Excerpt -->
            <?php if (!empty($blog['excerpt'])): ?>
            <p style="color:var(--muted);line-height:1.8;border-left:3px solid var(--rose);padding-left:16px;margin-bottom:28px;font-style:italic;font-size:clamp(.875rem,2.5vw,1rem);background:rgba(242,196,206,.05);padding:12px 16px;border-radius:0 10px 10px 0;">
              <?= e($blog['excerpt']) ?>
            </p>
            <?php endif; ?>

            <!-- Konten -->
            <div class="blog-content">
              <?= $blog['content'] ?>
            </div>

            <!-- Updated -->
            <div style="margin-top:28px;padding-top:20px;border-top:1px solid rgba(212,137,154,.15);display:flex;align-items:center;gap:8px;">
              <span style="font-size:11px;color:rgba(44,26,30,.3);">Terakhir diperbarui:</span>
              <span style="font-size:11px;color:var(--dusty);font-weight:700;"><?= date('d F Y, H:i', strtotime($updated)) ?> WIB</span>
            </div>

          </div>
        </div>

        <!-- CTA Banner -->
        <div style="background:linear-gradient(135deg,rgba(242,196,206,.35) 0%,rgba(212,137,154,.1) 100%);border:1px solid rgba(212,137,154,.3);border-radius:18px;padding:28px;text-align:center;margin-bottom:28px;position:relative;overflow:hidden;">
          <div style="position:absolute;top:-50px;right:-50px;width:180px;height:180px;background:radial-gradient(circle,rgba(242,196,206,.5) 0%,transparent 65%);pointer-events:none;"></div>
          <div style="font-size:32px;margin-bottom:10px;">💐</div>
          <p style="font-family:'Cormorant Garamond',Georgia,serif;font-size:1.3rem;font-weight:700;color:var(--dark);margin-bottom:6px;">Butuh rangkaian bunga spesial?</p>
          <p style="font-size:13px;color:var(--muted);margin-bottom:18px;">Konsultasi gratis dengan florist kami via WhatsApp — siap melayani 24 jam</p>
          <a href="<?= e($wa_url) ?>" target="_blank"
             style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,var(--dusty),var(--rose));color:#fff;font-size:13px;font-weight:700;padding:12px 28px;border-radius:30px;text-decoration:none;letter-spacing:.03em;box-shadow:0 6px 20px rgba(200,119,138,.3);">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/></svg>
            Pesan Sekarang
          </a>
        </div>

        <!-- Artikel Terkait -->
        <?php if (!empty($related)): ?>
        <div>
          <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
            <h2 style="font-family:'Cormorant Garamond',Georgia,serif;font-size:1.15rem;font-weight:700;color:var(--dark);white-space:nowrap;">Artikel Terkait</h2>
            <div style="flex:1;height:1px;background:linear-gradient(90deg,rgba(212,137,154,.4),transparent);"></div>
          </div>
          <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;">
            <?php foreach ($related as $rel):
              $rel_thumb = !empty($rel['thumbnail']) && file_exists(UPLOAD_DIR . $rel['thumbnail'])
                           ? UPLOAD_URL . $rel['thumbnail']
                           : 'https://images.unsplash.com/photo-1487530811015-780780dde0e4?w=400&h=250&fit=crop';
            ?>
            <a href="<?= BASE_URL ?>/blog/<?= e($rel['slug']) ?>/"
               style="background:#fff;border:1px solid rgba(212,137,154,.15);border-radius:14px;overflow:hidden;text-decoration:none;display:block;box-shadow:0 2px 12px rgba(44,26,30,.05);transition:box-shadow .3s ease,transform .3s ease;">
              <div style="aspect-ratio:16/9;overflow:hidden;">
                <img src="<?= e($rel_thumb) ?>" alt="<?= e($rel['title']) ?>"
                     style="width:100%;height:100%;object-fit:cover;display:block;transition:transform .5s ease;" loading="lazy">
              </div>
              <div style="padding:12px 14px;">
                <?php if ($rel['cat_name']): ?>
                <span style="font-size:10px;font-weight:700;color:var(--rose);text-transform:uppercase;letter-spacing:.06em;"><?= e($rel['cat_name']) ?></span>
                <?php endif; ?>
                <h3 style="font-family:'Cormorant Garamond',Georgia,serif;font-size:13px;font-weight:700;color:var(--dark);line-height:1.4;margin-top:4px;display:-webkit-box;-webkit-line-clamp:2;line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                  <?= e($rel['title']) ?>
                </h3>
                <p style="font-size:11px;color:var(--muted);margin-top:6px;"><?= date('d M Y', strtotime($rel['created_at'])) ?></p>
              </div>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

      </article>

      <!-- Sidebar desktop -->
      <aside class="blog-det-sidebar-desktop" style="position:sticky;top:90px;">
        <?php include __DIR__ . '/sections/blog-sidebar.php'; ?>
      </aside>

    </div>
  </div>
</section>

<!-- Sidebar mobile -->
<div id="blog-det-sidebar-mobile-wrap">
  <?php include __DIR__ . '/sections/blog-sidebar-mobile.php'; ?>
</div>
<style>@media(min-width:1024px){#blog-det-sidebar-mobile-wrap{display:none !important;}}</style>

</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>