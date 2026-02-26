<?php
requireLogin();
require __DIR__ . '/../includes/crud_helper.php';

$page_title   = 'Kelola Kategori';
$current_page = 'categories';

// ── Cascade Delete ────────────────────────────────────────────────────────────
// Tangani SEBELUM handleCrud agar cascade terjadi lebih dulu
$action = $_GET['action'] ?? '';
$id     = (int)($_GET['id'] ?? 0);

if ($action === 'delete' && $id) {
    $db  = db();
    $cat = $db->prepare("SELECT parent_id FROM categories WHERE id = ?");
    $cat->execute([$id]);
    $row = $cat->fetch();

    if ($row) {
        $isParent = ($row['parent_id'] == 0 || $row['parent_id'] === null);

        if ($isParent) {
            // Ambil semua sub-kategori
            $subStmt = $db->prepare("SELECT id FROM categories WHERE parent_id = ?");
            $subStmt->execute([$id]);
            $subIds = array_column($subStmt->fetchAll(), 'id');

            // Hapus produk di sub-kategori
            if (!empty($subIds)) {
                $ph = implode(',', array_fill(0, count($subIds), '?'));

                // Ambil gambar produk sub-kategori untuk dihapus dari disk
                $imgs = $db->prepare("SELECT image FROM products WHERE category_id IN ($ph)");
                $imgs->execute($subIds);
                foreach ($imgs->fetchAll() as $img) {
                    if (!empty($img['image'])) @unlink(UPLOAD_DIR . $img['image']);
                }

                $db->prepare("DELETE FROM products WHERE category_id IN ($ph)")->execute($subIds);
                $db->prepare("DELETE FROM categories WHERE parent_id = ?")->execute([$id]);
            }
        }

        // Hapus produk di kategori ini sendiri
        $imgs2 = $db->prepare("SELECT image FROM products WHERE category_id = ?");
        $imgs2->execute([$id]);
        foreach ($imgs2->fetchAll() as $img) {
            if (!empty($img['image'])) @unlink(UPLOAD_DIR . $img['image']);
        }
        $db->prepare("DELETE FROM products WHERE category_id = ?")->execute([$id]);

        // Hapus gambar kategori dari disk
        $catImg = $db->prepare("SELECT image FROM categories WHERE id = ?");
        $catImg->execute([$id]);
        $ci = $catImg->fetch();
        if (!empty($ci['image'])) @unlink(UPLOAD_DIR . $ci['image']);

        // Hapus kategorinya
        $db->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);

        // Redirect agar tidak re-trigger handleCrud
        header("Location: ?page=categories&deleted=1");
        exit;
    }
}

$msg = handleCrud('categories', ['name','slug','parent_id','urutan','meta_title','meta_description','content','image','status'], 'categories');

// Flash dari redirect cascade delete
if (!$msg && ($_GET['deleted'] ?? '') === '1') {
    $msg = ['text' => 'Kategori beserta seluruh sub-kategori dan produk terkait berhasil dihapus.'];
}

$edit = null;
if ($action === 'edit' && $id) {
    $stmt = db()->prepare("SELECT * FROM categories WHERE id=?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
}

// Dropdown: hanya kategori utama (parent_id = 0 atau NULL)
$parent_options = db()->query("
    SELECT id, name FROM categories
    WHERE parent_id = 0 OR parent_id IS NULL
    ORDER BY urutan ASC, id ASC
")->fetchAll();

// Tabel: semua kategori dengan info induk, diurutkan parent → child
$rows = db()->query("
    SELECT c.*, p.name AS parent_name
    FROM categories c
    LEFT JOIN categories p ON c.parent_id = p.id
    ORDER BY COALESCE(NULLIF(c.parent_id, 0), c.id), c.parent_id != 0, c.urutan ASC
")->fetchAll();

// ── Hitung sub & produk per kategori (embed ke JS, tanpa AJAX) ────────────────
$_db = db();

// Semua sub-kategori
$subRows = $_db->query("SELECT id, name, parent_id FROM categories WHERE parent_id > 0")->fetchAll();
$subCountMap = [];
$subNamesMap = [];
$subIdsByParent = [];
foreach ($subRows as $s) {
    $pid = (int)$s['parent_id'];
    $subCountMap[$pid] = ($subCountMap[$pid] ?? 0) + 1;
    $subNamesMap[$pid][]    = $s['name'];
    $subIdsByParent[$pid][] = (int)$s['id'];
}

// Jumlah produk per category_id
$prodCountMap = [];
$prodRows = $_db->query("SELECT category_id, COUNT(*) as cnt FROM products WHERE category_id IS NOT NULL GROUP BY category_id")->fetchAll();
foreach ($prodRows as $p) {
    $prodCountMap[(int)$p['category_id']] = (int)$p['cnt'];
}

// Bangun array meta per kategori untuk di-embed ke tombol via data-attribute
$categoryMeta = [];
foreach ($rows as $r) {
    $rid      = (int)$r['id'];
    $isParent = ($r['parent_id'] == 0 || $r['parent_id'] === null);
    $prodSelf = $prodCountMap[$rid] ?? 0;
    $prodSub  = 0;
    if ($isParent && !empty($subIdsByParent[$rid])) {
        foreach ($subIdsByParent[$rid] as $sid) {
            $prodSub += $prodCountMap[$sid] ?? 0;
        }
    }
    $categoryMeta[$rid] = [
        'id'           => $rid,
        'name'         => $r['name'],
        'is_parent'    => $isParent,
        'sub_count'    => $subCountMap[$rid] ?? 0,
        'sub_names'    => $subNamesMap[$rid] ?? [],
        'product_self' => $prodSelf,
        'product_sub'  => $prodSub,
        'product_total'=> $prodSelf + $prodSub,
    ];
}

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

        <div>
          <label class="form-label">Nama Kategori *</label>
          <input type="text" name="name" required class="form-input" value="<?= e($edit['name'] ?? '') ?>">
        </div>
        <div>
          <label class="form-label">Slug (URL) *</label>
          <input type="text" name="slug" required class="form-input" value="<?= e($edit['slug'] ?? '') ?>" placeholder="bunga-papan-jakarta-utara">
          <p class="text-xs text-gray-400 mt-1">Gunakan huruf kecil dan tanda hubung</p>
        </div>
        <div>
          <label class="form-label">Kategori Induk</label>
          <select name="parent_id" class="form-select">
            <option value="">— Tidak ada (kategori utama) —</option>
            <?php foreach ($parent_options as $po):
              if ($edit && $po['id'] == $edit['id']) continue;
            ?>
            <option value="<?= $po['id'] ?>"
              <?= (isset($edit['parent_id']) && $edit['parent_id'] == $po['id']) ? 'selected' : '' ?>>
              <?= e($po['name']) ?>
            </option>
            <?php endforeach; ?>
          </select>
          <p class="text-xs text-gray-400 mt-1">Pilih jika ini adalah sub-kategori</p>
        </div>
        <div>
          <label class="form-label">Urutan Tampil</label>
          <input type="number" name="urutan" class="form-input" value="<?= e($edit['urutan'] ?? 0) ?>" min="0" placeholder="0">
          <p class="text-xs text-gray-400 mt-1">Angka lebih kecil tampil lebih dulu</p>
        </div>
        <div>
          <label class="form-label">Meta Title <span class="text-gray-400 font-normal">(maks 70 karakter)</span></label>
          <input type="text" name="meta_title" maxlength="70" class="form-input" value="<?= e($edit['meta_title'] ?? '') ?>">
        </div>
        <div>
          <label class="form-label">Meta Description <span class="text-gray-400 font-normal">(maks 160 karakter)</span></label>
          <textarea name="meta_description" rows="2" maxlength="160" class="form-textarea"><?= e($edit['meta_description'] ?? '') ?></textarea>
        </div>
        <div>
          <label class="form-label">Konten Halaman <span class="text-gray-400 font-normal">(SEO)</span></label>
          <textarea name="content" rows="5" class="form-textarea" placeholder="Konten HTML untuk halaman kategori..."><?= htmlspecialchars($edit['content'] ?? '') ?></textarea>
        </div>
        <div>
          <label class="form-label">Gambar</label>
          <?php if (!empty($edit['image'])): ?>
          <img src="<?= e(imgUrl($edit['image'], 'category')) ?>" class="w-20 h-20 object-cover rounded-lg mb-2 border">
          <?php endif; ?>
          <input type="file" name="image" accept="image/*" class="form-input text-sm">
        </div>
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
          <tr class="<?= ($r['parent_id'] && $r['parent_id'] != 0) ? 'bg-gray-50/50' : '' ?>">
            <td class="font-medium text-sm">
              <?php if ($r['parent_id'] && $r['parent_id'] != 0): ?>
              <span class="text-gray-300 mr-1">└</span>
              <span class="text-gray-600"><?= e($r['name']) ?></span>
              <?php else: ?>
              <span class="text-navy font-semibold"><?= e($r['name']) ?></span>
              <?php endif; ?>
              <div class="text-xs text-gray-400 font-mono mt-0.5">/<?= e($r['slug']) ?>/</div>
            </td>
            <td class="text-xs text-gray-500">
              <?= ($r['parent_name'] ?? '') ? '📂 ' . e($r['parent_name']) : '<span class="text-sage font-medium">Utama</span>' ?>
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

                <!-- Tombol Hapus — data embed langsung dari PHP, tanpa AJAX -->
                <button type="button"
                        data-meta="<?= htmlspecialchars(json_encode($categoryMeta[(int)$r['id']]), ENT_QUOTES) ?>"
                        onclick="confirmDeleteCategory(this)"
                        class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1 rounded-lg font-medium transition">
                  Hapus
                </button>

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

<!-- ═══════════════════════════════════════════════════════
     MODAL KONFIRMASI HAPUS
════════════════════════════════════════════════════════ -->
<div id="deleteModal"
     style="display:none; position:fixed; inset:0; z-index:9999; align-items:center; justify-content:center; background:rgba(0,0,0,0.5); padding:1rem;">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 space-y-5 animate-fade-in">

    <!-- Header -->
    <div class="flex items-start gap-3">
      <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center text-red-500 text-xl">
        ⚠️
      </div>
      <div>
        <h3 class="font-serif font-semibold text-navy text-base" id="modal-title">
          Konfirmasi Penghapusan
        </h3>
        <p class="text-xs text-gray-400 mt-0.5" id="modal-category-name"></p>
      </div>
    </div>

    <!-- Body — diisi dinamis oleh JS -->
    <div id="modal-body" class="text-sm text-gray-600 space-y-3 min-h-[60px]">
      <div class="flex items-center gap-2 text-gray-400 text-xs">
        <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
        </svg>
        Memeriksa data kategori...
      </div>
    </div>

    <!-- Footer -->
    <div class="flex gap-3 pt-1">
      <button id="modal-cancel"
              onclick="closeDeleteModal()"
              class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm font-medium transition">
        Batal
      </button>
      <a id="modal-confirm"
         href="#"
         style="opacity:0.5; pointer-events:none;"
         class="flex-1 py-2.5 bg-red-500 hover:bg-red-600 text-white text-center rounded-xl text-sm font-semibold transition">
        Ya, Hapus Permanen
      </a>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     SCRIPT
════════════════════════════════════════════════════════ -->
<script>
function confirmDeleteCategory(btn) {
  const data       = JSON.parse(btn.getAttribute('data-meta'));
  const modal      = document.getElementById('deleteModal');
  const confirmBtn = document.getElementById('modal-confirm');

  // Set nama kategori di header modal
  document.getElementById('modal-category-name').textContent = data.name;

  // Lock tombol konfirmasi dulu
  confirmBtn.style.opacity       = '0.5';
  confirmBtn.style.pointerEvents = 'none';
  confirmBtn.href                = '#';

  // Render isi modal langsung (tidak perlu fetch)
  renderModalBody(data);

  // Aktifkan tombol konfirmasi
  confirmBtn.href                = `?page=categories&action=delete&id=${data.id}`;
  confirmBtn.style.opacity       = '1';
  confirmBtn.style.pointerEvents = 'auto';

  // Buka modal
  modal.style.display          = 'flex';
  document.body.style.overflow = 'hidden';
}

function renderModalBody(data) {
  const hasSubs    = data.sub_count > 0;
  const hasProduct = data.product_total > 0;
  let html = '';

  if (!hasSubs && !hasProduct) {
    html = `
      <p style="font-size:14px;color:#374151;">Apakah Anda yakin ingin menghapus kategori <strong>"${data.name}"</strong>?</p>
      <p style="color:#9ca3af;font-size:12px;margin-top:6px;">Kategori ini tidak memiliki sub-kategori maupun produk yang terkait.</p>`;
  } else {
    html = `<p style="font-size:14px;color:#374151;margin-bottom:10px;">Anda akan menghapus kategori <strong>"${data.name}"</strong>. Perhatikan dampak berikut:</p>`;

    if (data.is_parent && hasSubs) {
      const subList = data.sub_names.map(n =>
        `<li style="margin-left:16px;list-style:disc;font-size:11px;color:#6b7280;line-height:1.8;">${n}</li>`
      ).join('');
      html += `
        <div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:8px;padding:10px 12px;margin-bottom:8px;">
          <div style="font-weight:600;color:#b45309;font-size:12px;margin-bottom:4px;">📂 ${data.sub_count} Sub-Kategori akan ikut terhapus:</div>
          <ul>${subList}</ul>
        </div>`;
    }

    if (data.product_self > 0) {
      html += `
        <div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;padding:10px 12px;margin-bottom:8px;font-size:12px;font-weight:600;color:#b91c1c;">
          🛒 ${data.product_self} produk pada kategori ini akan terhapus secara permanen.
        </div>`;
    }

    if (data.product_sub > 0) {
      html += `
        <div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;padding:10px 12px;margin-bottom:8px;font-size:12px;font-weight:600;color:#b91c1c;">
          🛒 ${data.product_sub} produk di dalam sub-kategori juga akan terhapus secara permanen.
        </div>`;
    }

    html += `
      <div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;padding:10px 12px;font-size:12px;font-weight:600;color:#dc2626;">
        ⚠️ Tindakan ini tidak dapat dibatalkan. Semua data di atas akan hilang selamanya.
      </div>`;
  }

  document.getElementById('modal-body').innerHTML = html;
}

function closeDeleteModal() {
  document.getElementById('deleteModal').style.display = 'none';
  document.body.style.overflow = '';
}

// Tutup modal jika klik backdrop
document.getElementById('deleteModal').addEventListener('click', function(e) {
  if (e.target === this) closeDeleteModal();
});
</script>

<?php require __DIR__ . '/../includes/footer.php'; ?>