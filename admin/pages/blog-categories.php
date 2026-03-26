<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

$page_title   = 'Kategori Blog';
$current_page = 'blog-categories';
$msg = '';

// ── Helper slug ───────────────────────────────────────────
function makeBlogCatSlug(string $text): string {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

// ── CRUD ──────────────────────────────────────────────────
$action = $_POST['action'] ?? '';

if ($action === 'add') {
    $name   = trim($_POST['name'] ?? '');
    $slug   = makeBlogCatSlug($_POST['slug'] ?? $name);
    $desc   = trim($_POST['description'] ?? '');
    $status = in_array($_POST['status'] ?? '', ['active','inactive']) ? $_POST['status'] : 'active';
    $urutan = (int)($_POST['urutan'] ?? 0);

    if ($name && $slug) {
        try {
            $stmt = db()->prepare("INSERT INTO blog_categories (name, slug, description, status, urutan) VALUES (?,?,?,?,?)");
            $stmt->execute([$name, $slug, $desc, $status, $urutan]);
            $msg = '<div id="flash-message" class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl mb-5">✅ Kategori berhasil ditambahkan.</div>';
        } catch (PDOException $e) {
            $msg = '<div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl mb-5">❌ Gagal: ' . e($e->getMessage()) . '</div>';
        }
    } else {
        $msg = '<div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl mb-5">❌ Nama dan slug wajib diisi.</div>';
    }
}

if ($action === 'edit') {
    $id     = (int)($_POST['id'] ?? 0);
    $name   = trim($_POST['name'] ?? '');
    $slug   = makeBlogCatSlug($_POST['slug'] ?? $name);
    $desc   = trim($_POST['description'] ?? '');
    $status = in_array($_POST['status'] ?? '', ['active','inactive']) ? $_POST['status'] : 'active';
    $urutan = (int)($_POST['urutan'] ?? 0);

    if ($id && $name && $slug) {
        try {
            $stmt = db()->prepare("UPDATE blog_categories SET name=?, slug=?, description=?, status=?, urutan=? WHERE id=?");
            $stmt->execute([$name, $slug, $desc, $status, $urutan, $id]);
            $msg = '<div id="flash-message" class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl mb-5">✅ Kategori berhasil diperbarui.</div>';
        } catch (PDOException $e) {
            $msg = '<div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl mb-5">❌ Gagal: ' . e($e->getMessage()) . '</div>';
        }
    }
}

if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id) {
     
        db()->prepare("DELETE FROM blog_categories WHERE id = ?")->execute([$id]);
        $msg = '<div id="flash-message" class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl mb-5">✅ Kategori berhasil dihapus.</div>';
    }
}

// ── Fetch ─────────────────────────────────────────────────
$categories = db()->query("
    SELECT bc.*, COUNT(b.id) AS total_blogs
    FROM blog_categories bc
    LEFT JOIN blogs b ON b.blog_category_id = bc.id
    GROUP BY bc.id ORDER BY bc.urutan ASC, bc.id ASC
")->fetchAll();

// Edit mode
$edit_item = null;
if (isset($_GET['edit'])) {
    $stmt = db()->prepare("SELECT * FROM blog_categories WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $edit_item = $stmt->fetch();
}

require __DIR__ . '/../includes/header.php';
?>

<?= $msg ?>

<!-- Top bar -->
<div class="flex items-center justify-between mb-6">
  <div>
    <h1 class="font-serif font-semibold text-navy text-lg">📂 Kategori Blog</h1>
    <p class="text-xs text-gray-400 mt-0.5">Kelola kategori untuk artikel blog</p>
  </div>
  <a href="?page=blog"
     class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-xl font-medium transition">← Kembali ke Blog</a>
</div>

<div class="grid lg:grid-cols-3 gap-6">

  <!-- ── Form Tambah / Edit ──────────────────────────── -->
  <div class="lg:col-span-1">
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm sticky top-20">
      <h2 class="font-serif font-semibold text-navy text-base mb-5">
        <?= $edit_item ? '✏️ Edit Kategori' : '➕ Tambah Kategori' ?>
      </h2>
      <form method="POST" class="space-y-4">
        <input type="hidden" name="action" value="<?= $edit_item ? 'edit' : 'add' ?>">
        <?php if ($edit_item): ?>
        <input type="hidden" name="id" value="<?= $edit_item['id'] ?>">
        <?php endif; ?>

        <div>
          <label class="form-label">Nama Kategori *</label>
          <input type="text" name="name" id="cat-name" required
                 class="form-input"
                 value="<?= e($edit_item['name'] ?? '') ?>"
                 placeholder="Contoh: Tips & Trik"
                 oninput="autoSlugCat(this.value)">
        </div>

        <div>
          <label class="form-label">Slug *</label>
          <input type="text" name="slug" id="cat-slug" required
                 class="form-input"
                 value="<?= e($edit_item['slug'] ?? '') ?>"
                 placeholder="tips-trik">
          <p class="text-xs text-gray-400 mt-1">
            /blog/?kategori=<strong id="slug-preview" class="text-navy"><?= e($edit_item['slug'] ?? '') ?></strong>
          </p>
        </div>

        <div>
          <label class="form-label">Deskripsi</label>
          <textarea name="description" class="form-textarea" rows="3"
                    placeholder="Deskripsi singkat kategori..."><?= e($edit_item['description'] ?? '') ?></textarea>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option value="active"   <?= ($edit_item['status'] ?? 'active') === 'active'   ? 'selected' : '' ?>>Aktif</option>
              <option value="inactive" <?= ($edit_item['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
            </select>
          </div>
          <div>
            <label class="form-label">Urutan</label>
            <input type="number" name="urutan" class="form-input"
                   value="<?= $edit_item['urutan'] ?? 0 ?>" min="0">
          </div>
        </div>

        <div class="flex gap-3 pt-1">
          <button type="submit"
                  class="flex-1 bg-sage hover:bg-sage-dark text-white font-semibold py-2.5 rounded-xl transition text-sm">
            <?= $edit_item ? '💾 Simpan Perubahan' : '➕ Tambah Kategori' ?>
          </button>
          <?php if ($edit_item): ?>
          <a href="?page=blog-categories"
             class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm font-medium transition">Batal</a>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>

  <!-- ── Daftar Kategori ─────────────────────────────── -->
  <div class="lg:col-span-2">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-serif font-semibold text-navy text-base">Daftar Kategori</h2>
        <span class="text-xs text-gray-400"><?= count($categories) ?> kategori</span>
      </div>

      <?php if (empty($categories)): ?>
      <div class="text-center py-14 text-gray-400">
        <div class="text-4xl mb-3">📂</div>
        <p class="font-medium text-sm">Belum ada kategori blog.</p>
        <p class="text-xs mt-1">Tambahkan kategori pertama di form sebelah kiri.</p>
      </div>
      <?php else: ?>
      <div class="overflow-x-auto">
        <table class="w-full data-table">
          <thead>
            <tr>
              <th>Nama</th>
              <th>Slug</th>
              <th class="text-center">Artikel</th>
              <th class="text-center">Urutan</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($categories as $cat): ?>
            <tr>
              <td class="font-medium text-navy text-sm"><?= e($cat['name']) ?></td>
              <td>
                <code class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-lg"><?= e($cat['slug']) ?></code>
              </td>
              <td class="text-center">
                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600">
                  <?= $cat['total_blogs'] ?>
                </span>
              </td>
              <td class="text-center text-sm text-gray-500"><?= $cat['urutan'] ?></td>
              <td>
                <span class="px-2 py-1 rounded-full text-xs font-semibold
                  <?= $cat['status'] === 'active' ? 'badge-active' : 'badge-inactive' ?>">
                  <?= $cat['status'] === 'active' ? 'Aktif' : 'Nonaktif' ?>
                </span>
              </td>
              <td>
                <div class="flex gap-2">
                  <a href="?page=blog-categories&edit=<?= $cat['id'] ?>"
                     class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1 rounded-lg font-medium transition">Edit</a>
                  <form method="POST" style="display:inline"
                        onsubmit="return confirmDeleteCat(this, '<?= e(addslashes($cat['name'])) ?>', <?= $cat['total_blogs'] ?>)">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id"     value="<?= $cat['id'] ?>">
                    <button type="submit"
                            class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1 rounded-lg font-medium transition">Hapus</button>
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
  </div>

</div>

<!-- Modal Konfirmasi Hapus -->
<div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="background:rgba(0,0,0,0.5);">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 animate-fade-in">
    <div class="flex items-start gap-4 mb-4">
      <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0 text-2xl">⚠️</div>
      <div>
        <h3 class="font-serif font-bold text-navy text-lg" id="modal-title">Konfirmasi Penghapusan</h3>
        <p class="text-gray-500 text-sm mt-0.5" id="modal-cat-name"></p>
      </div>
    </div>

    <p class="text-gray-600 text-sm mb-3" id="modal-desc"></p>

    <div id="modal-warning-articles" class="hidden bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-3 text-sm text-red-700 font-medium flex items-center gap-2">
      <span>🗂️</span>
      <span id="modal-articles-text"></span>
    </div>

    <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 mb-5 text-sm text-amber-700 font-medium flex items-center gap-2">
      <span>⚠️</span>
      <span>Tindakan ini tidak dapat dibatalkan. Semua data di atas akan hilang selamanya.</span>
    </div>

    <div class="flex gap-3">
      <button onclick="closeDeleteModal()"
              class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold py-2.5 rounded-xl transition text-sm">
        Batal
      </button>
      <button onclick="submitDeleteForm()"
              class="flex-1 bg-red-500 hover:bg-red-600 text-white font-semibold py-2.5 rounded-xl transition text-sm">
        Ya, Hapus Permanen
      </button>
    </div>
  </div>
</div>

<script>
function autoSlugCat(val) {
  const slug = val.toLowerCase()
    .replace(/[^a-z0-9\s-]/g, '')
    .replace(/[\s-]+/g, '-')
    .replace(/^-+|-+$/g, '');
  document.getElementById('cat-slug').value = slug;
  document.getElementById('slug-preview').textContent = slug;
}
document.getElementById('cat-slug').addEventListener('input', function() {
  document.getElementById('slug-preview').textContent = this.value;
});

let _deleteForm = null;

function confirmDeleteCat(form, catName, totalBlogs) {
  _deleteForm = form;
  document.getElementById('modal-cat-name').textContent = catName;
  document.getElementById('modal-desc').innerHTML =
    `Anda akan menghapus kategori "<strong>${catName}</strong>". Perhatikan dampak berikut:`;

  const warningEl  = document.getElementById('modal-warning-articles');
  const articlesEl = document.getElementById('modal-articles-text');

  if (totalBlogs > 0) {
    warningEl.classList.remove('hidden');
    warningEl.classList.add('flex');
    articlesEl.textContent = `${totalBlogs} artikel di dalam kategori ini akan IKUT TERHAPUS permanen.`;
  } else {
    warningEl.classList.add('hidden');
    warningEl.classList.remove('flex');
  }

  const modal = document.getElementById('delete-modal');
  modal.classList.remove('hidden');
  modal.classList.add('flex');
  return false; // cegah submit langsung
}

function closeDeleteModal() {
  const modal = document.getElementById('delete-modal');
  modal.classList.add('hidden');
  modal.classList.remove('flex');
  _deleteForm = null;
}

function submitDeleteForm() {
  if (_deleteForm) _deleteForm.submit();
}

// Tutup modal kalau klik backdrop
document.getElementById('delete-modal').addEventListener('click', function(e) {
  if (e.target === this) closeDeleteModal();
});
</script>

<?php require __DIR__ . '/../includes/footer.php'; ?>