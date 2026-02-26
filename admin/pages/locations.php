<?php
requireLogin();
require __DIR__ . '/../includes/crud_helper.php';

$page_title   = 'Kelola Area/Kecamatan';
$current_page = 'locations';

$msg = handleCrud('locations', ['name','slug','address','phone','meta_title','meta_description','content','status'], 'locations');

$action = $_GET['action'] ?? '';
$id     = (int)($_GET['id'] ?? 0);
$edit   = null;
if ($action === 'edit' && $id) {
    $stmt = db()->prepare("SELECT * FROM locations WHERE id=?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
}

$rows = db()->query("SELECT * FROM locations ORDER BY id")->fetchAll();

require __DIR__ . '/../includes/header.php';
?>

<?php if ($msg): ?>
<div id="flash-message" class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl mb-5">
  ✅ <?= e($msg['text']) ?>
</div>
<?php endif; ?>

<div class="grid lg:grid-cols-3 gap-6">
  <div class="lg:col-span-1">
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm sticky top-20">
      <h2 class="font-serif font-semibold text-navy text-base mb-5"><?= $edit ? 'Edit Area' : 'Tambah Area Baru' ?></h2>
      <form method="POST" class="space-y-4">
        <?php if ($edit): ?><input type="hidden" name="id" value="<?= $edit['id'] ?>"><?php endif; ?>

        <div><label class="form-label">Nama Kecamatan *</label><input type="text" name="name" required class="form-input" value="<?= e($edit['name'] ?? '') ?>" placeholder="Penjaringan"></div>
        <div>
          <label class="form-label">Slug (URL) *</label>
          <input type="text" name="slug" required class="form-input" value="<?= e($edit['slug'] ?? '') ?>" placeholder="toko-bunga-penjaringan">
          <p class="text-xs text-gray-400 mt-1">Format: toko-bunga-nama-kecamatan</p>
        </div>
        <div><label class="form-label">Alamat</label><input type="text" name="address" class="form-input" value="<?= e($edit['address'] ?? '') ?>"></div>
        <div><label class="form-label">Telepon</label><input type="text" name="phone" class="form-input" value="<?= e($edit['phone'] ?? '') ?>"></div>
        <div><label class="form-label">Meta Title (maks 70 karakter)</label><input type="text" name="meta_title" maxlength="70" class="form-input" value="<?= e($edit['meta_title'] ?? '') ?>"></div>
        <div><label class="form-label">Meta Description (maks 160 karakter)</label><textarea name="meta_description" rows="2" maxlength="160" class="form-textarea"><?= e($edit['meta_description'] ?? '') ?></textarea></div>
        <div><label class="form-label">Konten Halaman (SEO)</label><textarea name="content" rows="5" class="form-textarea"><?= htmlspecialchars($edit['content'] ?? '') ?></textarea></div>
        <div>
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="active"   <?= ($edit['status'] ?? 'active') === 'active'   ? 'selected' : '' ?>>Aktif</option>
            <option value="inactive" <?= ($edit['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
          </select>
        </div>
        <div class="flex gap-3 pt-2">
          <button type="submit" class="flex-1 bg-sage hover:bg-sage-dark text-white font-semibold py-2.5 rounded-xl transition text-sm"><?= $edit ? '💾 Simpan' : '➕ Tambah' ?></button>
          <?php if ($edit): ?><a href="?page=locations" class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-sm font-medium">Batal</a><?php endif; ?>
        </div>
      </form>
    </div>
  </div>

  <div class="lg:col-span-2">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100"><h2 class="font-serif font-semibold text-navy text-base">Daftar Area (<?= count($rows) ?> kecamatan)</h2></div>
      <table class="w-full data-table">
        <thead><tr><th>Nama</th><th>Slug</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
          <tr>
            <td class="font-medium">📍 <?= e($r['name']) ?></td>
            <td class="text-xs text-gray-400 font-mono">/<?= e($r['slug']) ?>/</td>
            <td><span class="px-2 py-1 rounded-full text-xs font-semibold <?= $r['status']==='active' ? 'badge-active' : 'badge-inactive' ?>"><?= $r['status'] === 'active' ? 'Aktif' : 'Nonaktif' ?></span></td>
            <td>
              <div class="flex gap-2">
                <a href="?page=locations&action=edit&id=<?= $r['id'] ?>" class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1 rounded-lg font-medium transition">Edit</a>
                <a href="?page=locations&action=delete&id=<?= $r['id'] ?>" onclick="return confirm('Hapus area ini?')" class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1 rounded-lg font-medium transition">Hapus</a>
                <a href="<?= BASE_URL ?>/<?= e($r['slug']) ?>/" target="_blank" class="text-xs bg-green-50 text-green-600 hover:bg-green-100 px-3 py-1 rounded-lg font-medium transition">Preview</a>
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
