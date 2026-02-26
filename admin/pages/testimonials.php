<?php
// testimonials.php
requireLogin();
require __DIR__ . '/../includes/crud_helper.php';

$page_title   = 'Kelola Testimoni';
$current_page = 'testimonials';

$msg = handleCrud('testimonials', ['name','content','rating','location','urutan','status'], 'testimonials');

$action = $_GET['action'] ?? '';
$id     = (int)($_GET['id'] ?? 0);
$edit   = null;
if ($action === 'edit' && $id) {
    $stmt = db()->prepare("SELECT * FROM testimonials WHERE id=?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
}

$rows = db()->query("SELECT * FROM testimonials ORDER BY urutan, id DESC")->fetchAll();

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
      <h2 class="font-serif font-semibold text-navy text-base mb-5"><?= $edit ? 'Edit Testimoni' : 'Tambah Testimoni' ?></h2>
      <form method="POST" class="space-y-4">
        <?php if ($edit): ?><input type="hidden" name="id" value="<?= $edit['id'] ?>"><?php endif; ?>

        <div><label class="form-label">Nama *</label><input type="text" name="name" required class="form-input" value="<?= e($edit['name'] ?? '') ?>"></div>
        <div><label class="form-label">Testimoni *</label><textarea name="content" required rows="4" class="form-textarea" placeholder="Isi testimoni pelanggan..."><?= e($edit['content'] ?? '') ?></textarea></div>
        <div>
          <label class="form-label">Rating</label>
          <select name="rating" class="form-select">
            <?php for ($i=5; $i>=1; $i--): ?>
            <option value="<?= $i ?>" <?= ($edit['rating'] ?? 5) == $i ? 'selected' : '' ?>><?= str_repeat('★', $i) ?> (<?= $i ?>)</option>
            <?php endfor; ?>
          </select>
        </div>
        <div><label class="form-label">Lokasi</label><input type="text" name="location" class="form-input" value="<?= e($edit['location'] ?? '') ?>" placeholder="Kelapa Gading, Grogol"></div>
        <div><label class="form-label">Urutan Tampil</label><input type="number" name="urutan" class="form-input" value="<?= (int)($edit['urutan'] ?? 0) ?>"></div>
        <div>
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="active"   <?= ($edit['status'] ?? 'active') === 'active'   ? 'selected' : '' ?>>Aktif</option>
            <option value="inactive" <?= ($edit['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
          </select>
        </div>
        <div class="flex gap-3 pt-2">
          <button type="submit" class="flex-1 bg-sage hover:bg-sage-dark text-white font-semibold py-2.5 rounded-xl transition text-sm"><?= $edit ? '💾 Simpan' : '➕ Tambah' ?></button>
          <?php if ($edit): ?><a href="?page=testimonials" class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-sm font-medium">Batal</a><?php endif; ?>
        </div>
      </form>
    </div>
  </div>

  <div class="lg:col-span-2">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100"><h2 class="font-serif font-semibold text-navy text-base">Daftar Testimoni</h2></div>
      <div class="divide-y divide-gray-50">
        <?php foreach ($rows as $r): ?>
        <div class="px-6 py-4 flex items-start gap-4">
          <div class="w-10 h-10 bg-sage/20 rounded-full flex items-center justify-center font-bold text-sage flex-shrink-0">
            <?= strtoupper(substr($r['name'], 0, 1)) ?>
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
              <span class="font-semibold text-navy text-sm"><?= e($r['name']) ?></span>
              <span class="text-yellow-400 text-xs"><?= str_repeat('★', (int)$r['rating']) ?></span>
              <span class="px-2 py-0.5 rounded-full text-xs <?= $r['status']==='active' ? 'badge-active' : 'badge-inactive' ?>"><?= $r['status'] === 'active' ? 'Aktif' : 'Nonaktif' ?></span>
            </div>
            <p class="text-gray-500 text-xs mt-0.5"><?= e($r['location']) ?></p>
            <p class="text-gray-600 text-sm mt-1 line-clamp-2">"<?= e($r['content']) ?>"</p>
          </div>
          <div class="flex gap-2 flex-shrink-0">
            <a href="?page=testimonials&action=edit&id=<?= $r['id'] ?>" class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1 rounded-lg font-medium">Edit</a>
            <a href="?page=testimonials&action=delete&id=<?= $r['id'] ?>" onclick="return confirm('Hapus?')" class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1 rounded-lg font-medium">Hapus</a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
