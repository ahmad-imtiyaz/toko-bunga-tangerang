<?php
requireLogin();
$page_title = 'Dashboard';
$stats = db()->query("SELECT * FROM v_statistics")->fetch();
$recent_orders = db()->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5")->fetchAll();

require __DIR__ . '/../includes/header.php';
?>

<!-- Stats cards -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
  <?php
  $cards = [
    ['label'=>'Produk Aktif',     'value'=>$stats['total_products'],    'icon'=>'🌺', 'color'=>'bg-rose-50 border-rose-100'],
    ['label'=>'Kategori',         'value'=>$stats['total_categories'],  'icon'=>'📂', 'color'=>'bg-blue-50 border-blue-100'],
    ['label'=>'Area Layanan',     'value'=>$stats['total_locations'],   'icon'=>'📍', 'color'=>'bg-green-50 border-green-100'],
    ['label'=>'Testimoni',        'value'=>$stats['total_testimonials'],'icon'=>'⭐', 'color'=>'bg-yellow-50 border-yellow-100'],
    ['label'=>'FAQ',              'value'=>$stats['total_faqs'],        'icon'=>'❓', 'color'=>'bg-purple-50 border-purple-100'],
    ['label'=>'Pesanan Pending',  'value'=>$stats['pending_orders'],    'icon'=>'📦', 'color'=>'bg-orange-50 border-orange-100'],
  ];
  foreach ($cards as $card):
  ?>
  <div class="<?= $card['color'] ?> border rounded-2xl p-5 text-center">
    <div class="text-2xl mb-2"><?= $card['icon'] ?></div>
    <div class="font-bold text-navy text-2xl"><?= $card['value'] ?></div>
    <div class="text-xs text-gray-500 mt-1"><?= $card['label'] ?></div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Quick links -->
<div class="grid md:grid-cols-2 gap-6 mb-8">
  <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
    <h2 class="font-serif font-semibold text-navy text-base mb-5">Kelola Konten</h2>
    <div class="space-y-3">
      <?php
      $links = [
        ['page'=>'products',     'label'=>'Tambah / Edit Produk',      'icon'=>'🌺'],
        ['page'=>'categories',   'label'=>'Kelola Kategori',           'icon'=>'📂'],
        ['page'=>'locations',    'label'=>'Kelola Area Kecamatan',     'icon'=>'📍'],
        ['page'=>'testimonials', 'label'=>'Kelola Testimoni',          'icon'=>'⭐'],
        ['page'=>'faqs',         'label'=>'Kelola FAQ',                'icon'=>'❓'],
        ['page'=>'settings',     'label'=>'Pengaturan Website',        'icon'=>'⚙️'],
      ];
      foreach ($links as $l):
      ?>
      <a href="<?= BASE_URL ?>/admin/?page=<?= $l['page'] ?>"
         class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gray-50 hover:bg-cream border border-gray-100 hover:border-sage/30 transition group">
        <span class="text-xl"><?= $l['icon'] ?></span>
        <span class="text-sm font-medium text-gray-700 group-hover:text-sage transition"><?= $l['label'] ?></span>
        <svg class="w-4 h-4 ml-auto text-gray-300 group-hover:text-sage transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </a>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
    <h2 class="font-serif font-semibold text-navy text-base mb-5">Info Website</h2>
    <div class="space-y-3 text-sm">
      <div class="flex items-center gap-3 p-3 bg-cream rounded-xl">
        <span class="text-sage">🌐</span>
        <div>
          <div class="font-medium text-navy">Website Aktif</div>
          <a href="<?= BASE_URL ?>/" target="_blank" class="text-sage hover:underline text-xs"><?= BASE_URL ?>/</a>
        </div>
      </div>
      <div class="flex items-center gap-3 p-3 bg-cream rounded-xl">
        <span class="text-sage">📞</span>
        <div>
          <div class="font-medium text-navy">WhatsApp</div>
          <div class="text-xs text-gray-500"><?= e(setting('phone_display')) ?></div>
        </div>
      </div>
      <div class="flex items-center gap-3 p-3 bg-cream rounded-xl">
        <span class="text-sage">⏰</span>
        <div>
          <div class="font-medium text-navy">Jam Operasional</div>
          <div class="text-xs text-gray-500"><?= e(setting('jam_buka')) ?></div>
        </div>
      </div>
      <div class="mt-4">
        <a href="<?= BASE_URL ?>/admin/?page=settings"
           class="block w-full text-center bg-sage hover:bg-sage-dark text-white font-semibold py-2.5 rounded-xl transition text-sm">
          ⚙️ Edit Pengaturan
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Recent orders -->
<div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
  <div class="flex items-center justify-between mb-5">
    <h2 class="font-serif font-semibold text-navy text-base">Pesanan Terbaru</h2>
    <span class="text-xs text-gray-400">Pesanan masuk via WA</span>
  </div>
  <?php if (empty($recent_orders)): ?>
  <div class="text-center py-10 text-gray-400">
    <div class="text-4xl mb-3">📭</div>
    <p class="text-sm">Belum ada pesanan masuk.</p>
  </div>
  <?php else: ?>
  <div class="overflow-x-auto">
    <table class="w-full data-table">
      <thead><tr>
        <th>Nama</th><th>Telepon</th><th>Produk</th><th>Total</th><th>Status</th><th>Tanggal</th>
      </tr></thead>
      <tbody>
        <?php foreach ($recent_orders as $o): ?>
        <tr>
          <td class="font-medium"><?= e($o['customer_name']) ?></td>
          <td><?= e($o['customer_phone']) ?></td>
          <td><?= $o['product_id'] ? '#'.$o['product_id'] : '-' ?></td>
          <td><?= $o['total_price'] ? rupiah((float)$o['total_price']) : '-' ?></td>
          <td><span class="px-2 py-1 rounded-full text-xs font-semibold badge-<?= $o['status'] === 'pending' ? 'active' : 'inactive' ?>"><?= e($o['status']) ?></span></td>
          <td class="text-gray-400 text-xs"><?= date('d M Y', strtotime($o['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
