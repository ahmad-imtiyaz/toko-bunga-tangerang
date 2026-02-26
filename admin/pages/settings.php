<?php
requireLogin();
$page_title   = 'Pengaturan Website';
$current_page = 'settings';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $allowed = [
        'site_name','site_tagline','whatsapp_number','whatsapp_url','phone_display',
        'address','email','jam_buka','maps_embed',
        'meta_title_home','meta_desc_home','meta_keywords_home',
        'hero_title','hero_subtitle','about_text','footer_text'
    ];
    $stmt = db()->prepare("UPDATE settings SET value=? WHERE `key`=?");
    foreach ($allowed as $key) {
        if (isset($_POST[$key])) {
            $stmt->execute([trim($_POST[$key]), $key]);
        }
    }

    // Handle logo upload
    if (!empty($_FILES['logo']['name'])) {
        $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $fname = 'logo.' . $ext;
        if (move_uploaded_file($_FILES['logo']['tmp_name'], UPLOAD_DIR . $fname)) {
            db()->prepare("UPDATE settings SET value=? WHERE `key`='logo'")->execute([$fname]);
        }
    }

    $msg = 'Pengaturan berhasil disimpan.';
}

$s = allSettings();

require __DIR__ . '/../includes/header.php';
?>

<?php if ($msg): ?>
<div id="flash-message" class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl mb-5">
  ✅ <?= e($msg) ?>
</div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="space-y-6 max-w-3xl">

  <!-- Identitas -->
  <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
    <h2 class="font-serif font-semibold text-navy text-base mb-5">🏪 Identitas Toko</h2>
    <div class="grid md:grid-cols-2 gap-4">
      <div><label class="form-label">Nama Toko</label><input type="text" name="site_name" class="form-input" value="<?= e($s['site_name'] ?? '') ?>"></div>
      <div><label class="form-label">Tagline</label><input type="text" name="site_tagline" class="form-input" value="<?= e($s['site_tagline'] ?? '') ?>"></div>
      <div><label class="form-label">Nomor WhatsApp (format: 628xxx)</label><input type="text" name="whatsapp_number" class="form-input" value="<?= e($s['whatsapp_number'] ?? '') ?>" placeholder="6281322991131"></div>
      <div><label class="form-label">URL WhatsApp</label><input type="text" name="whatsapp_url" class="form-input" value="<?= e($s['whatsapp_url'] ?? '') ?>" placeholder="https://wa.me/628xxx"></div>
      <div><label class="form-label">Nomor Tampil</label><input type="text" name="phone_display" class="form-input" value="<?= e($s['phone_display'] ?? '') ?>" placeholder="0813-xxxx-xxxx"></div>
      <div><label class="form-label">Email</label><input type="email" name="email" class="form-input" value="<?= e($s['email'] ?? '') ?>"></div>
      <div class="md:col-span-2"><label class="form-label">Alamat Lengkap</label><input type="text" name="address" class="form-input" value="<?= e($s['address'] ?? '') ?>"></div>
      <div><label class="form-label">Jam Operasional</label><input type="text" name="jam_buka" class="form-input" value="<?= e($s['jam_buka'] ?? '') ?>" placeholder="Senin - Minggu, 07.00 - 21.00 WIB"></div>
      <div>
        <label class="form-label">Logo</label>
        <?php if (!empty($s['logo'])): ?>
        <img src="<?= UPLOAD_URL . e($s['logo']) ?>" class="h-12 mb-2 rounded-lg border object-contain bg-white p-1">
        <?php endif; ?>
        <input type="file" name="logo" accept="image/*" class="form-input text-sm">
      </div>
    </div>
  </div>

  <!-- SEO -->
  <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
    <h2 class="font-serif font-semibold text-navy text-base mb-5">🔍 SEO Homepage</h2>
    <div class="space-y-4">
      <div>
        <label class="form-label">Meta Title (maks 60 karakter)</label>
        <input type="text" name="meta_title_home" maxlength="60" class="form-input" value="<?= e($s['meta_title_home'] ?? '') ?>">
        <p class="text-xs text-gray-400 mt-1">Contoh: Toko Bunga Grogol Terpercaya | Kirim Cepat 24 Jam</p>
      </div>
      <div>
        <label class="form-label">Meta Description (140-155 karakter)</label>
        <textarea name="meta_desc_home" rows="2" maxlength="160" class="form-textarea"><?= e($s['meta_desc_home'] ?? '') ?></textarea>
      </div>
      <div>
        <label class="form-label">Meta Keywords</label>
        <input type="text" name="meta_keywords_home" class="form-input" value="<?= e($s['meta_keywords_home'] ?? '') ?>">
      </div>
    </div>
  </div>

  <!-- Konten Homepage -->
  <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
    <h2 class="font-serif font-semibold text-navy text-base mb-5">📄 Konten Homepage</h2>
    <div class="space-y-4">
      <div><label class="form-label">Hero Title (H1)</label><input type="text" name="hero_title" class="form-input" value="<?= e($s['hero_title'] ?? '') ?>"></div>
      <div><label class="form-label">Hero Subtitle</label><textarea name="hero_subtitle" rows="2" class="form-textarea"><?= e($s['hero_subtitle'] ?? '') ?></textarea></div>
      <div><label class="form-label">Tentang Kami</label><textarea name="about_text" rows="4" class="form-textarea"><?= e($s['about_text'] ?? '') ?></textarea></div>
      <div><label class="form-label">Footer Text</label><textarea name="footer_text" rows="2" class="form-textarea"><?= e($s['footer_text'] ?? '') ?></textarea></div>
      <div><label class="form-label">Google Maps Embed URL</label><input type="text" name="maps_embed" class="form-input" value="<?= e($s['maps_embed'] ?? '') ?>"></div>
    </div>
  </div>

  <button type="submit" class="bg-sage hover:bg-sage-dark text-white font-bold px-10 py-3.5 rounded-xl transition shadow text-sm">
    💾 Simpan Semua Pengaturan
  </button>
</form>

<?php require __DIR__ . '/../includes/footer.php'; ?>
