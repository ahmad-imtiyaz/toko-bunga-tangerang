<?php
requireLogin();
require __DIR__ . '/../includes/crud_helper.php';

$page_title   = 'Kelola Kategori';
$current_page = 'categories';

$msg = handleCrud('categories', ['name','slug','parent_id','urutan','meta_title','meta_description','content','image','status'], 'categories');

$action = $_GET['action'] ?? '';
$id     = (int)($_GET['id'] ?? 0);
$edit   = null;
if ($action === 'edit' && $id) {
    $stmt = db()->prepare("SELECT * FROM categories WHERE id=?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
}

// Ambil semua kategori induk (parent_id IS NULL) untuk dropdown
$parent_options = db()->query("SELECT id, name FROM categories WHERE parent_id IS NULL ORDER BY urutan ASC, id ASC")->fetchAll();

// Ambil semua kategori dengan info induknya untuk tabel
$rows = db()->query("
    SELECT c.*, p.name as parent_name
    FROM categories c
    LEFT JOIN categories p ON c.parent_id = p.id
    ORDER BY COALESCE(c.parent_id, c.id), c.parent_id IS NOT NULL, c.urutan ASC
")->fetchAll();

require __DIR__ . '/../includes/header.php';
?>

<?php if ($msg): ?>
<div id="flash-message" class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl mb-5">
  ✅ <?= e($msg['text']) ?>
</div>
<?php endif; ?>

<div class="grid lg:grid-cols-3 gap-6">
  <!-- ── Form ───────────────────────────────────────────── -->
  <div class="lg:col-span-1">
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm sticky top-20">
      <h2 class="font-serif font-semibold text-navy text-base mb-5"><?= $edit ? 'Edit Kategori' : 'Tambah Kategori' ?></h2>
      <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <?php if ($edit): ?>
        <input type="hidden" name="id" value="<?= $edit['id'] ?>">
        <input type="hidden" name="old_image" value="<?= e($edit['image'] ?? '') ?>">
        <?php endif; ?>

        <!-- Nama -->
        <div>
          <label class="form-label">Nama Kategori *</label>
          <input type="text" name="name" required class="form-input" value="<?= e($edit['name'] ?? '') ?>">
        </div>

        <!-- Slug -->
        <div>
          <label class="form-label">Slug (URL) *</label>
          <input type="text" name="slug" required class="form-input" value="<?= e($edit['slug'] ?? '') ?>" placeholder="bunga-papan-jakarta-utara">
          <p class="text-xs text-gray-400 mt-1">Gunakan huruf kecil dan tanda hubung</p>
        </div>

        <!-- Parent Kategori -->
        <div>
          <label class="form-label">Kategori Induk</label>
          <select name="parent_id" class="form-select">
            <option value="">— Tidak ada (kategori utama) —</option>
            <?php foreach ($parent_options as $po):
              // Jangan tampilkan diri sendiri sebagai pilihan induk
              if ($edit && $po['id'] == $edit['id']) continue;
            ?>
            <option value="<?= $po['id'] ?>"
              <?= (isset($edit['parent_id']) && $edit['parent_id'] == $po['id']) ? 'selected' : '' ?>>
              <?= e($po['name']) ?>
            </option>
            <?php endforeach; ?>
          </select>
          <p class="text-xs text-gray-400 mt-1">Pilih jika ini adalah sub-kategori (misal: sub dari Bunga Papan)</p>
        </div>

        <!-- Urutan -->
        <div>
          <label class="form-label">Urutan Tampil</label>
          <input type="number" name="urutan" class="form-input" value="<?= e($edit['urutan'] ?? 0) ?>" min="0" placeholder="0">
          <p class="text-xs text-gray-400 mt-1">Angka lebih kecil tampil lebih dulu</p>
        </div>

        <!-- Meta Title -->
        <div>
          <label class="form-label">Meta Title <span class="text-gray-400 font-normal">(maks 70 karakter)</span></label>
          <input type="text" name="meta_title" maxlength="70" class="form-input" value="<?= e($edit['meta_title'] ?? '') ?>">
        </div>

        <!-- Meta Description -->
        <div>
          <label class="form-label">Meta Description <span class="text-gray-400 font-normal">(maks 160 karakter)</span></label>
          <textarea name="meta_description" rows="2" maxlength="160" class="form-textarea"><?= e($edit['meta_description'] ?? '') ?></textarea>
        </div>

        <!-- Konten -->
        <div>
          <label class="form-label">Konten Halaman <span class="text-gray-400 font-normal">(SEO)</span></label>
          <textarea name="content" rows="5" class="form-textarea" placeholder="Konten HTML untuk halaman kategori..."><?= htmlspecialchars($edit['content'] ?? '') ?></textarea>
        </div>

        <!-- Gambar -->
        <div>
          <label class="form-label">Gambar</label>
          <?php if (!empty($edit['image'])): ?>
          <img src="<?= e(imgUrl($edit['image'], 'category')) ?>" class="w-20 h-20 object-cover rounded-lg mb-2 border">
          <?php endif; ?>
          <input type="file" name="image" accept="image/*" class="form-input text-sm">
        </div>

        <!-- Status -->
        <div>
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="active"   <?= ($edit['status'] ?? 'active') === 'active'   ? 'selected' : '' ?>>Aktif</option>
            <option value="inactive" <?= ($edit['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
          </select>
        </div>

        <div class="flex gap-3 pt-2">
          <button type="submit" class="flex-1 bg-sage hover:bg-sage-dark text-white font-semibold py-2.5 rounded-xl transition text-sm">
            <?= $edit ? '💾 Simpan' : '➕ Tambah' ?>
          </button>
          <?php if ($edit): ?>
          <a href="?page=categories" class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-sm font-medium">Batal</a>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>

  <!-- ── Tabel ──────────────────────────────────────────── -->
  <div class="lg:col-span-2">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-serif font-semibold text-navy text-base">Daftar Kategori</h2>
        <span class="text-xs text-gray-400"><?= count($rows) ?> kategori</span>
      </div>
      <table class="w-full data-table">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Induk</th>
            <th class="text-center">Urutan</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
          <tr class="<?= $r['parent_id'] ? 'bg-gray-50/50' : '' ?>">
            <td class="font-medium text-sm">
              <?php if ($r['parent_id']): ?>
              <span class="text-gray-300 mr-1">└</span>
              <span class="text-gray-600"><?= e($r['name']) ?></span>
              <?php else: ?>
              <span class="text-navy font-semibold"><?= e($r['name']) ?></span>
              <?php endif; ?>
              <div class="text-xs text-gray-400 font-mono mt-0.5">/<?= e($r['slug']) ?>/</div>
            </td>
            <td class="text-xs text-gray-500">
              <?= $r['parent_name'] ? '📂 ' . e($r['parent_name']) : '<span class="text-sage font-medium">Utama</span>' ?>
            </td>
            <td class="text-center text-sm text-gray-500"><?= (int)$r['urutan'] ?></td>
            <td>
              <span class="px-2 py-1 rounded-full text-xs font-semibold <?= $r['status']==='active' ? 'badge-active' : 'badge-inactive' ?>">
                <?= $r['status'] === 'active' ? 'Aktif' : 'Nonaktif' ?>
              </span>
            </td>
            <td>
              <div class="flex gap-2 flex-wrap">
                <a href="?page=categories&action=edit&id=<?= $r['id'] ?>"
                   class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1 rounded-lg font-medium transition">Edit</a>
                <a href="?page=categories&action=delete&id=<?= $r['id'] ?>"
                   onclick="return confirm('Hapus kategori ini? Sub-kategorinya tidak ikut terhapus.')"
                   class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1 rounded-lg font-medium transition">Hapus</a>
                <a href="<?= BASE_URL ?>/<?= e($r['slug']) ?>/" target="_blank"
                   class="text-xs bg-green-50 text-green-600 hover:bg-green-100 px-3 py-1 rounded-lg font-medium transition">Preview</a>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>