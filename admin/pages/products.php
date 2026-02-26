<?php
requireLogin();
$page_title   = 'Kelola Produk';
$current_page = 'products';
$msg = '';

// Handle actions
$action = $_GET['action'] ?? '';
$id     = (int)($_GET['id'] ?? 0);

// DELETE
if ($action === 'delete' && $id) {
    $row = db()->prepare("SELECT image FROM products WHERE id=?")->execute([$id]);
    db()->prepare("DELETE FROM products WHERE id=?")->execute([$id]);
    $msg = 'Produk berhasil dihapus.';
}

// SAVE (add/edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pid         = (int)($_POST['id'] ?? 0);
    $name        = trim($_POST['name'] ?? '');
    $cat_id      = (int)($_POST['category_id'] ?? 0) ?: null;
    $description = trim($_POST['description'] ?? '');
    $price       = (float)str_replace(['.','Rp',' '], '', $_POST['price'] ?? 0);
    $status      = $_POST['status'] ?? 'active';

    // Handle image upload
    $image = $_POST['old_image'] ?? '';
    if (!empty($_FILES['image']['name'])) {
        $ext   = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $fname = time() . '_' . uniqid() . '.' . $ext;
        $dest  = UPLOAD_DIR . $fname;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
            $image = $fname;
        }
    }

    if ($pid) {
        db()->prepare("UPDATE products SET name=?,category_id=?,description=?,price=?,image=?,status=?,updated_at=NOW() WHERE id=?")
           ->execute([$name,$cat_id,$description,$price,$image,$status,$pid]);
        $msg = 'Produk berhasil diperbarui.';
    } else {
        db()->prepare("INSERT INTO products (name,category_id,description,price,image,status) VALUES (?,?,?,?,?,?)")
           ->execute([$name,$cat_id,$description,$price,$image,$status]);
        $msg = 'Produk berhasil ditambahkan.';
    }
}

// Load data
$edit = null;
if ($action === 'edit' && $id) {
    $stmt = db()->prepare("SELECT * FROM products WHERE id=?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
}

$products   = db()->query("SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id=c.id ORDER BY p.id DESC")->fetchAll();
$categories = db()->query("SELECT * FROM categories WHERE status='active' ORDER BY name")->fetchAll();

require __DIR__ . '/../includes/header.php';
?>

<?php if ($msg): ?>
<div id="flash-message" class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl mb-5">
  ✅ <?= e($msg) ?>
</div>
<?php endif; ?>

<div class="grid lg:grid-cols-3 gap-6">
  <!-- Form -->
  <div class="lg:col-span-1">
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm sticky top-20">
      <h2 class="font-serif font-semibold text-navy text-base mb-5">
        <?= $edit ? 'Edit Produk' : 'Tambah Produk Baru' ?>
      </h2>
      <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <?php if ($edit): ?>
        <input type="hidden" name="id" value="<?= $edit['id'] ?>">
        <input type="hidden" name="old_image" value="<?= e($edit['image']) ?>">
        <?php endif; ?>

        <div>
          <label class="form-label">Nama Produk *</label>
          <input type="text" name="name" required class="form-input" value="<?= e($edit['name'] ?? '') ?>" placeholder="Contoh: Hand Bouquet Wisuda Premium">
        </div>
        <div>
          <label class="form-label">Kategori</label>
          <select name="category_id" class="form-select">
            <option value="">— Pilih Kategori —</option>
            <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($edit['category_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
              <?= e($c['name']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="form-label">Deskripsi</label>
          <textarea name="description" rows="3" class="form-textarea" placeholder="Deskripsi singkat produk..."><?= e($edit['description'] ?? '') ?></textarea>
        </div>
        <div>
          <label class="form-label">Harga (Rp) *</label>
          <input type="number" name="price" required min="0" step="1000" class="form-input" value="<?= (int)($edit['price'] ?? 0) ?>" placeholder="350000">
        </div>
        <div>
          <label class="form-label">Gambar Produk</label>
          <?php if (!empty($edit['image'])): ?>
          <div class="mb-2">
            <img src="<?= e(imgUrl($edit['image'])) ?>" class="w-24 h-24 object-cover rounded-lg border">
            <p class="text-xs text-gray-400 mt-1">Gambar saat ini</p>
          </div>
          <?php endif; ?>
          <input type="file" name="image" accept="image/*" class="form-input text-sm">
          <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, WebP. Max 2MB. Rename: nama-produk-jakarta-utara.jpg</p>
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
            <?= $edit ? '💾 Simpan Perubahan' : '➕ Tambah Produk' ?>
          </button>
          <?php if ($edit): ?>
          <a href="<?= BASE_URL ?>/admin/?page=products" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm font-medium transition">Batal</a>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>

  <!-- Table -->
  <div class="lg:col-span-2">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-serif font-semibold text-navy text-base">Daftar Produk</h2>
        <span class="text-xs text-gray-400"><?= count($products) ?> produk</span>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full data-table">
          <thead><tr>
            <th>Gambar</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Status</th><th>Aksi</th>
          </tr></thead>
          <tbody>
            <?php foreach ($products as $p): ?>
            <tr>
              <td>
                <img src="<?= e(imgUrl($p['image'])) ?>" alt="<?= e($p['name']) ?>"
                     class="w-12 h-12 object-cover rounded-lg border">
              </td>
              <td class="font-medium max-w-[180px]">
                <div class="line-clamp-2 text-xs"><?= e($p['name']) ?></div>
              </td>
              <td class="text-xs text-gray-500"><?= e($p['cat_name'] ?? '-') ?></td>
              <td class="font-semibold text-sage text-xs"><?= rupiah((float)$p['price']) ?></td>
              <td>
                <span class="px-2 py-1 rounded-full text-xs font-semibold <?= $p['status']==='active' ? 'badge-active' : 'badge-inactive' ?>">
                  <?= $p['status'] === 'active' ? 'Aktif' : 'Nonaktif' ?>
                </span>
              </td>
              <td>
                <div class="flex gap-2">
                  <a href="?page=products&action=edit&id=<?= $p['id'] ?>"
                     class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1 rounded-lg font-medium transition">Edit</a>
                  <a href="?page=products&action=delete&id=<?= $p['id'] ?>"
                     onclick="return confirm('Hapus produk ini?')"
                     class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1 rounded-lg font-medium transition">Hapus</a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
