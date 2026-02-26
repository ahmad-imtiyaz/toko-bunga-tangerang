<?php
requireLogin();
require __DIR__ . '/../includes/crud_helper.php';

$page_title   = 'Kelola FAQ';
$current_page = 'faqs';

$msg = handleCrud('faqs', ['question','answer','urutan','status'], 'faqs');

$action = $_GET['action'] ?? '';
$id     = (int)($_GET['id'] ?? 0);
$edit   = null;
if ($action === 'edit' && $id) {
    $stmt = db()->prepare("SELECT * FROM faqs WHERE id=?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
}

$rows = db()->query("SELECT * FROM faqs ORDER BY urutan, id")->fetchAll();

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
      <h2 class="font-serif font-semibold text-navy text-base mb-5"><?= $edit ? 'Edit FAQ' : 'Tambah FAQ' ?></h2>
      <form method="POST" class="space-y-4">
        <?php if ($edit): ?><input type="hidden" name="id" value="<?= $edit['id'] ?>"><?php endif; ?>

        <div><label class="form-label">Pertanyaan *</label><input type="text" name="question" required class="form-input" value="<?= e($edit['question'] ?? '') ?>" placeholder="Apakah bisa kirim hari yang sama?"></div>
        <div><label class="form-label">Jawaban *</label><textarea name="answer" required rows="5" class="form-textarea"><?= e($edit['answer'] ?? '') ?></textarea></div>
        <div><label class="form-label">Urutan</label><input type="number" name="urutan" class="form-input" value="<?= (int)($edit['urutan'] ?? 0) ?>"></div>
        <div>
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="active"   <?= ($edit['status'] ?? 'active') === 'active'   ? 'selected' : '' ?>>Aktif</option>
            <option value="inactive" <?= ($edit['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
          </select>
        </div>
        <div class="flex gap-3 pt-2">
          <button type="submit" class="flex-1 bg-sage hover:bg-sage-dark text-white font-semibold py-2.5 rounded-xl transition text-sm"><?= $edit ? '💾 Simpan' : '➕ Tambah' ?></button>
          <?php if ($edit): ?><a href="?page=faqs" class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-sm font-medium">Batal</a><?php endif; ?>
        </div>
      </form>
    </div>
  </div>

  <div class="lg:col-span-2">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-serif font-semibold text-navy text-base">Daftar FAQ (<?= count($rows) ?> pertanyaan)</h2>
      </div>
      <div class="divide-y divide-gray-50">
        <?php foreach ($rows as $r): ?>
        <div class="px-6 py-4">
          <div class="flex items-start justify-between gap-4">
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-mono text-gray-400 bg-gray-100 px-2 py-0.5 rounded">#<?= $r['urutan'] ?></span>
                <span class="px-2 py-0.5 rounded-full text-xs <?= $r['status']==='active' ? 'badge-active' : 'badge-inactive' ?>"><?= $r['status'] === 'active' ? 'Aktif' : 'Nonaktif' ?></span>
              </div>
              <p class="font-semibold text-navy text-sm mb-1"><?= e($r['question']) ?></p>
              <p class="text-gray-500 text-xs line-clamp-2"><?= e($r['answer']) ?></p>
            </div>
            <div class="flex gap-2 flex-shrink-0">
              <a href="?page=faqs&action=edit&id=<?= $r['id'] ?>" class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1 rounded-lg font-medium">Edit</a>
              <a href="?page=faqs&action=delete&id=<?= $r['id'] ?>" onclick="return confirm('Hapus FAQ ini?')" class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1 rounded-lg font-medium">Hapus</a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
