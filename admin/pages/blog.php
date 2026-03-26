<?php
requireLogin();
$page_title   = 'Kelola Blog';
$current_page = 'blog';
$msg = '';

// ── Handle TinyMCE image upload — HARUS PALING ATAS ──────
$subpage = $_GET['sub'] ?? 'list';
if ($subpage === 'upload' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    header('Content-Type: application/json');
    $file    = $_FILES['file'];
    $allowed = ['image/jpeg','image/png','image/webp','image/gif'];
    if (!in_array($file['type'], $allowed)) {
        echo json_encode(['error' => 'Tipe file tidak diizinkan']); exit;
    }
    $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'blog_img_' . uniqid() . '.' . $ext;
    if (move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $filename)) {
        echo json_encode(['location' => UPLOAD_URL . $filename]);
    } else {
        echo json_encode(['error' => 'Gagal upload. Cek permission folder uploads/']);
    }
    exit;
}

// ── Helpers ───────────────────────────────────────────────
function makeBlogSlug(string $text): string {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}
function handleThumbUpload(string $field): string {
    if (empty($_FILES[$field]['tmp_name'])) return '';
    $file    = $_FILES[$field];
    $allowed = ['image/jpeg','image/png','image/webp','image/gif'];
    if (!in_array($file['type'], $allowed)) return '';
    $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'blog_' . uniqid() . '.' . $ext;
    if (move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $filename)) return $filename;
    return '';
}

$blog_cats = db()->query("SELECT id, name FROM blog_categories WHERE status='active' ORDER BY urutan ASC")->fetchAll();
$action    = $_POST['action'] ?? '';

// ── POST ──────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (in_array($action, ['add','edit'])) {
        $title      = trim($_POST['title']             ?? '');
        $slug       = makeBlogSlug(trim($_POST['slug'] ?? '') ?: $title);
        $cat_id     = (int)($_POST['blog_category_id'] ?? 0) ?: null;
        $excerpt    = trim($_POST['excerpt']           ?? '');
        $content    = $_POST['content']                ?? '';
        $meta_title = trim($_POST['meta_title']        ?? '');
        $meta_desc  = trim($_POST['meta_desc']         ?? '');
        $meta_kw    = trim($_POST['meta_keywords']     ?? '');
        $status     = in_array($_POST['status'] ?? '', ['active','inactive','draft']) ? $_POST['status'] : 'draft';
        $urutan     = (int)($_POST['urutan']           ?? 0);
        $new_thumb  = handleThumbUpload('thumbnail');

        if (!$title || !$slug) {
            $msg = '<div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl mb-5">❌ Judul dan slug wajib diisi.</div>';
        } else {
            try {
                if ($action === 'add') {
                    db()->prepare("INSERT INTO blogs (blog_category_id,title,slug,thumbnail,excerpt,content,meta_title,meta_desc,meta_keywords,status,urutan) VALUES (?,?,?,?,?,?,?,?,?,?,?)")
                       ->execute([$cat_id,$title,$slug,$new_thumb,$excerpt,$content,$meta_title,$meta_desc,$meta_kw,$status,$urutan]);
                    $msg = '<div id="flash-message" class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl mb-5">✅ Artikel berhasil dipublikasikan.</div>';
                } else {
                    $id        = (int)($_POST['id'] ?? 0);
                    $thumb_val = $new_thumb ?: trim($_POST['old_thumbnail'] ?? '');
                    db()->prepare("UPDATE blogs SET blog_category_id=?,title=?,slug=?,thumbnail=?,excerpt=?,content=?,meta_title=?,meta_desc=?,meta_keywords=?,status=?,urutan=? WHERE id=?")
                       ->execute([$cat_id,$title,$slug,$thumb_val,$excerpt,$content,$meta_title,$meta_desc,$meta_kw,$status,$urutan,$id]);
                    $msg = '<div id="flash-message" class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl mb-5">✅ Artikel berhasil diperbarui.</div>';
                }
                $subpage = 'list';
            } catch (PDOException $e) {
                $msg = '<div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl mb-5">❌ ' . e($e->getMessage()) . '</div>';
            }
        }
    }
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) { db()->prepare("DELETE FROM blogs WHERE id=?")->execute([$id]); }
        $msg = '<div id="flash-message" class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl mb-5">✅ Artikel berhasil dihapus.</div>';
    }
}

// ── Fetch list ────────────────────────────────────────────
$search_q      = trim($_GET['q'] ?? '');
$filter_cat    = (int)($_GET['cat'] ?? 0);
$filter_status = trim($_GET['status'] ?? '');
$where  = ['1=1']; $params = [];
if ($search_q)      { $where[] = 'b.title LIKE ?';         $params[] = "%$search_q%"; }
if ($filter_cat)    { $where[] = 'b.blog_category_id = ?'; $params[] = $filter_cat; }
if ($filter_status) { $where[] = 'b.status = ?';           $params[] = $filter_status; }
$stmt = db()->prepare("SELECT b.*, bc.name AS cat_name FROM blogs b LEFT JOIN blog_categories bc ON b.blog_category_id=bc.id WHERE ".implode(' AND ',$where)." ORDER BY b.urutan ASC, b.created_at DESC");
$stmt->execute($params);
$blogs_list = $stmt->fetchAll();

$edit_blog = null;
if ($subpage === 'edit' && isset($_GET['id'])) {
    $s = db()->prepare("SELECT * FROM blogs WHERE id=?"); $s->execute([(int)$_GET['id']]);
    $edit_blog = $s->fetch();
    if (!$edit_blog) $subpage = 'list';
}

require __DIR__ . '/../includes/header.php';
?>

<?= $msg ?>

<?php if (in_array($subpage, ['add','edit'])): ?>
<!-- ══ FORM ADD / EDIT ══════════════════════════════════ -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.4/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
  selector: '#blog-content',
  height: 560,
   relative_urls: false,        // ← TAMBAH INI
  remove_script_host: false,   // ← TAMBAH INI  
  convert_urls: false,         // ← TAMBAH INI
  menubar: true,
  plugins: ['advlist','autolink','lists','link','image','charmap','preview','anchor','searchreplace','visualblocks','code','fullscreen','insertdatetime','media','table','help','wordcount','emoticons','codesample'],
  toolbar: 'undo redo | styles | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | forecolor backcolor | blockquote codesample emoticons | removeformat | fullscreen code',
  style_formats: [
    {title:'Heading 1',format:'h1'},{title:'Heading 2',format:'h2'},
    {title:'Heading 3',format:'h3'},{title:'Paragraph',format:'p'},
    {title:'Blockquote',format:'blockquote'},{title:'Code',format:'pre'},
  ],
  content_style: `
    body{font-family:'DM Sans',sans-serif;font-size:15px;color:#374151;padding:16px 20px;line-height:1.8;}
    h1,h2,h3,h4{font-family:'Playfair Display',Georgia,serif;color:#2C3E6B;}
    h1{font-size:1.875rem;font-weight:700;margin:1.5rem 0 0.75rem;}
    h2{font-size:1.5rem;font-weight:700;margin:1.5rem 0 0.75rem;border-bottom:2px solid #f0f7f1;padding-bottom:.5rem;}
    h3{font-size:1.2rem;font-weight:600;margin:1.25rem 0 .5rem;}
    p{margin:.75rem 0;}a{color:#7A9E7E;}
    img{max-width:100%;height:auto;border-radius:8px;}
    blockquote{border-left:4px solid #7A9E7E;background:#f0f7f1;padding:1rem 1.25rem;margin:1.25rem 0;border-radius:0 8px 8px 0;font-style:italic;}
    table{border-collapse:collapse;width:100%;}
    th{background:#2C3E6B;color:white;padding:8px 12px;text-align:left;}
    td{border:1px solid #e5e7eb;padding:8px 12px;}
  `,
  images_upload_url: '<?= BASE_URL ?>/admin/?page=blog&sub=upload',
  images_upload_handler: function(blobInfo) {
    return new Promise(function(resolve, reject) {
      const fd = new FormData();
      fd.append('file', blobInfo.blob(), blobInfo.filename());
      fetch('<?= BASE_URL ?>/admin/?page=blog&sub=upload', {method:'POST', body:fd})
        .then(r => r.json())
        .then(data => { if (data.location) resolve(data.location); else reject({message: data.error||'Upload gagal', remove:true}); })
        .catch(() => reject({message:'Network error', remove:true}));
    });
  },
  automatic_uploads: true,
  image_advtab: true,
  image_dimensions: true,
  object_resizing: 'img',
  branding: false,
  promotion: false,
});
</script>

<div class="flex items-center justify-between mb-6">
  <div>
    <h1 class="font-serif font-semibold text-navy text-lg"><?= $subpage==='edit' ? '✏️ Edit Artikel' : '➕ Tulis Artikel Baru' ?></h1>
    <?php if ($subpage==='edit' && $edit_blog): ?><p class="text-xs text-gray-400 mt-0.5">/blog/<?= e($edit_blog['slug']) ?>/</p><?php endif; ?>
  </div>
  <a href="?page=blog" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-xl font-medium transition">← Kembali</a>
</div>

<form method="POST" enctype="multipart/form-data" id="blog-form">
  <input type="hidden" name="action" value="<?= $subpage==='edit' ? 'edit' : 'add' ?>">
  <?php if ($edit_blog): ?>
  <input type="hidden" name="id"            value="<?= $edit_blog['id'] ?>">
  <input type="hidden" name="old_thumbnail" value="<?= e($edit_blog['thumbnail'] ?? '') ?>">
  <?php endif; ?>

  <div class="grid lg:grid-cols-4 gap-6 items-start">
    <!-- Kolom kiri 3/4 -->
    <div class="lg:col-span-3 space-y-5">

      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <input type="text" name="title" id="blog-title" required
               class="w-full text-xl font-semibold text-navy border-0 outline-none placeholder-gray-300 font-serif"
               placeholder="Tulis judul artikel di sini..."
               value="<?= e($edit_blog['title'] ?? '') ?>"
               oninput="autoSlugBlog(this.value)">
      </div>

      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
          <span class="text-sm font-semibold text-navy">📄 Konten Artikel</span>
          <span class="text-xs text-gray-400">— Bisa tambah gambar, heading, tabel, dll</span>
        </div>
        <div class="p-1">
          <textarea name="content" id="blog-content"><?= $edit_blog['content'] ?? '' ?></textarea>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">📝 Ringkasan / Excerpt</label>
        <textarea name="excerpt" class="form-textarea w-full" rows="3" placeholder="Ringkasan singkat artikel..."><?= e($edit_blog['excerpt'] ?? '') ?></textarea>
        <p class="text-xs text-gray-400 mt-1.5">Kosongkan untuk otomatis dari 200 karakter pertama konten.</p>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <h2 class="font-semibold text-navy text-sm mb-4">🔍 SEO Meta</h2>
        <div class="space-y-4">
          <div>
            <label class="form-label">Meta Title <span class="text-gray-400 font-normal">(maks 70 karakter)</span></label>
            <input type="text" name="meta_title" class="form-input w-full" maxlength="70" value="<?= e($edit_blog['meta_title'] ?? '') ?>" placeholder="Judul untuk Google...">
          </div>
          <div>
            <label class="form-label">Meta Description <span class="text-gray-400 font-normal">(maks 160 karakter)</span></label>
            <textarea name="meta_desc" class="form-textarea w-full" rows="2" maxlength="160" placeholder="Deskripsi untuk mesin pencari..."><?= e($edit_blog['meta_desc'] ?? '') ?></textarea>
          </div>
          <div>
            <label class="form-label">Meta Keywords</label>
            <input type="text" name="meta_keywords" class="form-input w-full" value="<?= e($edit_blog['meta_keywords'] ?? '') ?>" placeholder="tips bunga, rangkaian bunga, florist jakarta">
          </div>
        </div>
      </div>
    </div>

    <!-- Kolom kanan 1/4 -->
    <div class="lg:col-span-1 space-y-4 lg:sticky lg:top-20">
      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <h2 class="font-semibold text-navy text-sm mb-4">🚀 Publikasi</h2>
        <div class="space-y-3">
          <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-select w-full">
              <option value="draft"    <?= ($edit_blog['status'] ?? 'draft')==='draft'    ? 'selected':'' ?>>📝 Draft</option>
              <option value="active"   <?= ($edit_blog['status'] ?? '')==='active'        ? 'selected':'' ?>>✅ Aktif / Publish</option>
              <option value="inactive" <?= ($edit_blog['status'] ?? '')==='inactive'      ? 'selected':'' ?>>⏸ Nonaktif</option>
            </select>
          </div>
          <div>
            <label class="form-label">Urutan</label>
            <input type="number" name="urutan" class="form-input w-full" min="0" value="<?= $edit_blog['urutan'] ?? 0 ?>">
          </div>
          <button type="submit" class="w-full bg-sage hover:bg-sage-dark text-white font-semibold py-2.5 rounded-xl transition text-sm">
            <?= $subpage==='edit' ? '💾 Simpan Perubahan' : '🚀 Publikasikan' ?>
          </button>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <h2 class="font-semibold text-navy text-sm mb-3">📂 Kategori Blog</h2>
        <select name="blog_category_id" class="form-select w-full" required>
          <option value="">-- Pilih Kategori --</option>
          <?php foreach ($blog_cats as $bc): ?>
          <option value="<?= $bc['id'] ?>" <?= ($edit_blog['blog_category_id'] ?? '')==$bc['id'] ? 'selected':'' ?>><?= e($bc['name']) ?></option>
          <?php endforeach; ?>
        </select>
        <a href="?page=blog-categories" target="_blank" class="text-xs text-sage hover:underline mt-2 block">+ Tambah kategori baru</a>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <h2 class="font-semibold text-navy text-sm mb-3">🖼️ Thumbnail</h2>
        <?php if (!empty($edit_blog['thumbnail'])): ?>
        <div class="mb-3">
          <img src="<?= UPLOAD_URL . e($edit_blog['thumbnail']) ?>" alt="" class="w-full rounded-xl object-cover border border-gray-100" style="max-height:140px">
          <p class="text-xs text-gray-400 mt-1 text-center">Thumbnail saat ini</p>
        </div>
        <?php endif; ?>
        <input type="file" name="thumbnail" class="form-input w-full text-sm" accept="image/*" onchange="previewThumb(this)">
        <div id="thumb-preview" class="mt-2 hidden">
          <img id="thumb-preview-img" src="" alt="" class="w-full rounded-xl object-cover border border-gray-100" style="max-height:140px">
        </div>
        <p class="text-xs text-gray-400 mt-1.5">JPG, PNG, WebP. Rekomendasi 800×500px.</p>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <h2 class="font-semibold text-navy text-sm mb-3">🔗 Slug URL</h2>
        <input type="text" name="slug" id="blog-slug" class="form-input w-full text-sm"
               value="<?= e($edit_blog['slug'] ?? '') ?>" placeholder="url-artikel"
               oninput="document.getElementById('slug-preview').textContent=this.value">
        <p class="text-xs text-gray-400 mt-1.5 break-all">/blog/<strong id="slug-preview" class="text-navy"><?= e($edit_blog['slug'] ?? '') ?></strong>/</p>
      </div>
    </div>
  </div>
</form>

<script>
function autoSlugBlog(val) {
  if (document.getElementById('blog-slug').dataset.manual==='1') return;
  const s = val.toLowerCase().replace(/[^a-z0-9\s-]/g,'').replace(/[\s-]+/g,'-').replace(/^-+|-+$/g,'');
  document.getElementById('blog-slug').value = s;
  document.getElementById('slug-preview').textContent = s;
}
document.getElementById('blog-slug').addEventListener('input',function(){
  this.dataset.manual='1';
  document.getElementById('slug-preview').textContent=this.value;
});
function previewThumb(input){
  if(input.files&&input.files[0]){const r=new FileReader();r.onload=e=>{document.getElementById('thumb-preview-img').src=e.target.result;document.getElementById('thumb-preview').classList.remove('hidden');};r.readAsDataURL(input.files[0]);}
}
document.getElementById('blog-form').addEventListener('submit',function(){
  if(typeof tinymce!=='undefined') tinymce.triggerSave();
});
</script>

<?php else: ?>
<!-- ══ LIST VIEW ════════════════════════════════════════ -->
<div class="flex items-center justify-between mb-6">
  <div>
    <h1 class="font-serif font-semibold text-navy text-lg">📝 Blog</h1>
    <p class="text-xs text-gray-400 mt-0.5">Kelola artikel blog website</p>
  </div>
  <div class="flex gap-2">
    <a href="?page=blog-categories" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-xl font-medium transition">📂 Kategori</a>
    <a href="?page=blog&sub=add"    class="text-sm bg-sage hover:bg-sage-dark text-white px-4 py-2 rounded-xl font-semibold transition">➕ Tulis Artikel</a>
  </div>
</div>

<div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm mb-5">
  <form method="GET" class="flex flex-wrap gap-3 items-center">
    <input type="hidden" name="page" value="blog">
    <input type="text" name="q" value="<?= e($search_q) ?>" class="form-input flex-1 min-w-[160px]" placeholder="🔍 Cari judul artikel...">
    <select name="cat" class="form-select">
      <option value="">Semua Kategori</option>
      <?php foreach ($blog_cats as $bc): ?><option value="<?= $bc['id'] ?>" <?= $filter_cat==$bc['id']?'selected':'' ?>><?= e($bc['name']) ?></option><?php endforeach; ?>
    </select>
    <select name="status" class="form-select">
      <option value="">Semua Status</option>
      <option value="active"   <?= $filter_status==='active'  ?'selected':'' ?>>✅ Aktif</option>
      <option value="draft"    <?= $filter_status==='draft'   ?'selected':'' ?>>📝 Draft</option>
      <option value="inactive" <?= $filter_status==='inactive'?'selected':'' ?>>⏸ Nonaktif</option>
    </select>
    <button type="submit" class="bg-sage hover:bg-sage-dark text-white text-sm font-semibold px-4 py-2 rounded-xl transition">Filter</button>
    <a href="?page=blog" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-xl font-medium transition">Reset</a>
  </form>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
    <h2 class="font-serif font-semibold text-navy text-base">Daftar Artikel</h2>
    <span class="text-xs text-gray-400"><?= count($blogs_list) ?> artikel</span>
  </div>
  <?php if (empty($blogs_list)): ?>
  <div class="text-center py-16 text-gray-400">
    <div class="text-5xl mb-3">📝</div>
    <p class="font-medium">Belum ada artikel.</p>
    <a href="?page=blog&sub=add" class="text-sage hover:underline text-sm mt-1 inline-block">Tulis artikel pertama →</a>
  </div>
  <?php else: ?>
  <div class="overflow-x-auto">
    <table class="w-full data-table">
      <thead><tr><th>Thumbnail</th><th>Judul & Slug</th><th>Kategori</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php foreach ($blogs_list as $b):
          $b_thumb = !empty($b['thumbnail']) && file_exists(UPLOAD_DIR.$b['thumbnail']) ? UPLOAD_URL.$b['thumbnail'] : 'https://images.unsplash.com/photo-1487530811015-780780dde0e4?w=80&h=60&fit=crop';
          $badge = match($b['status']){'active'=>'badge-active','draft'=>'bg-yellow-100 text-yellow-700',default=>'badge-inactive'};
          $label = match($b['status']){'active'=>'Aktif','draft'=>'Draft',default=>'Nonaktif'};
        ?>
        <tr>
          <td><img src="<?= e($b_thumb) ?>" alt="" class="w-16 h-12 object-cover rounded-lg border border-gray-100"></td>
          <td>
            <div class="font-medium text-navy text-sm line-clamp-2 max-w-xs"><?= e($b['title']) ?></div>
            <div class="text-xs text-gray-400 font-mono mt-0.5">/blog/<?= e($b['slug']) ?>/</div>
          </td>
          <td class="text-xs text-gray-500"><?= e($b['cat_name'] ?? '—') ?></td>
          <td><span class="px-2 py-1 rounded-full text-xs font-semibold <?= $badge ?>"><?= $label ?></span></td>
          <td class="text-xs text-gray-400 whitespace-nowrap"><?= date('d M Y', strtotime($b['created_at'])) ?></td>
          <td>
            <div class="flex gap-2 flex-wrap">
              <a href="<?= BASE_URL ?>/blog/<?= e($b['slug']) ?>/" target="_blank" class="text-xs bg-green-50 text-green-700 hover:bg-green-100 px-3 py-1 rounded-lg font-medium transition">👁 Lihat</a>
              <a href="?page=blog&sub=edit&id=<?= $b['id'] ?>" class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1 rounded-lg font-medium transition">Edit</a>
              <form method="POST" style="display:inline" onsubmit="return confirm('Hapus artikel ini?')">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id"     value="<?= $b['id'] ?>">
                <button type="submit" class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1 rounded-lg font-medium transition">Hapus</button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>