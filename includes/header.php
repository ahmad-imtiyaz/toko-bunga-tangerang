<?php
$meta_title    = $meta_title    ?? setting('meta_title_home');
$meta_desc     = $meta_desc     ?? setting('meta_desc_home');
$meta_keywords = $meta_keywords ?? setting('meta_keywords_home');
$wa_url        = setting('whatsapp_url');
$site_name     = setting('site_name');
$phone         = setting('phone_display');

$nav_categories = db()->query("
    SELECT * FROM categories WHERE status = 'active' ORDER BY urutan ASC, id ASC
")->fetchAll();

$nav_parents = []; $nav_children = [];
foreach ($nav_categories as $nc) {
    $pid = $nc['parent_id'] ?? null;
    if ($pid === null || $pid == 0) $nav_parents[] = $nc;
    else $nav_children[$pid][] = $nc;
}

$current_slug = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$base_path    = trim(parse_url(BASE_URL, PHP_URL_PATH), '/');
if ($base_path && str_starts_with($current_slug, $base_path))
    $current_slug = trim(substr($current_slug, strlen($base_path)), '/');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($meta_title) ?></title>
<meta name="description" content="<?= e($meta_desc) ?>">
<meta name="keywords"    content="<?= e($meta_keywords) ?>">
<meta name="robots"      content="index, follow">
<link rel="icon"         href="<?= BASE_URL ?>/assets/images/icon.png">
<link rel="canonical"    href="<?= e(BASE_URL . '/' . trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/')) ?>">
<meta property="og:title"       content="<?= e($meta_title) ?>">
<meta property="og:description" content="<?= e($meta_desc) ?>">
<meta property="og:type"        content="website">
<meta property="og:url"         content="<?= e(BASE_URL) ?>">
<meta name="color-scheme" content="light only">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,400&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  theme: {
    extend: {
      colors: {
        blush:  { DEFAULT:'#F2C4CE', dark:'#D4899A' },
        dusty:  { DEFAULT:'#C8788A' },
        cream:  { DEFAULT:'#FAF5EE', dark:'#F0E8DA' },
        ivory:  { DEFAULT:'#FDF9F4' },
        rose:   { DEFAULT:'#D4899A' },
        muted:  { DEFAULT:'#8C6B72' },
        floral: { DEFAULT:'#2C1A1E' },
      },
      fontFamily: {
        serif: ['"Cormorant Garamond"','Georgia','serif'],
        sans:  ['"Jost"','sans-serif'],
      },
    }
  }
}
</script>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">

<style>
/* ════════════════════════════════════════
   CSS VARIABLES
════════════════════════════════════════ */
:root {
  --blush:  #F2C4CE;
  --rose:   #D4899A;
  --dusty:  #C8788A;
  --cream:  #FAF5EE;
  --ivory:  #FDF9F4;
  --muted:  #8C6B72;
  --dark:   #2C1A1E;
}

body {
  font-family: 'Jost', sans-serif;
  background: var(--ivory);
  color: var(--dark);
}
h1,h2,h3,.font-serif { font-family: 'Cormorant Garamond', serif; }

/* Scroll offset */
section[id] { scroll-margin-top: 90px; }

/* ════════════════════════════════════════
   TOP BAR
════════════════════════════════════════ */
#topbar {
  background: var(--dark);
  font-family: 'Jost', sans-serif;
  font-size: 11px;
  font-weight: 500;
  letter-spacing: .04em;
  color: rgba(255,255,255,.5);
  padding: 7px 0;
}
#topbar a { color: var(--blush); text-decoration: none; transition: color .2s; }
#topbar a:hover { color: #fff; }
.topbar-sep { color: rgba(255,255,255,.2); margin: 0 10px; }

/* ════════════════════════════════════════
   NAVBAR — sticky, solid ivory
════════════════════════════════════════ */
#navbar {
  position: sticky;
  top: 0;
  z-index: 50;
  font-family: 'Jost', sans-serif;
  background: var(--ivory);
  border-bottom: 1px solid rgba(212,137,154,.2);
  transition: box-shadow .3s ease, background .3s ease;
}
#navbar.scrolled {
  background: #fff;
  box-shadow: 0 2px 24px rgba(44,26,30,.08);
  border-bottom-color: rgba(212,137,154,.3);
}

/* ════════════════════════════════════════
   BRAND
════════════════════════════════════════ */
.nav-brand {
  display: flex;
  align-items: center;
  gap: 10px;
  text-decoration: none;
  flex-shrink: 0;
  transition: opacity .2s;
}
.nav-brand:hover { opacity: .82; }

.nav-logo-ring {
  width: 40px; height: 40px;
  border-radius: 50%;
  overflow: hidden;
  border: 2px solid rgba(212,137,154,.3);
  box-shadow: 0 2px 10px rgba(212,137,154,.18);
  flex-shrink: 0;
  transition: box-shadow .3s;
}
.nav-brand:hover .nav-logo-ring {
  box-shadow: 0 0 14px rgba(212,137,154,.45), 0 0 28px rgba(212,137,154,.2);
}
.nav-logo-ring img { width:100%; height:100%; object-fit:cover; }

.nav-brand-name {
  font-family: 'Cormorant Garamond', serif;
  font-size: 17px;
  font-weight: 700;
  color: var(--dark);
  line-height: 1.15;
}
.nav-brand-sub {
  font-size: 10px;
  font-weight: 500;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: var(--muted);
  margin-top: 1px;
}

/* ════════════════════════════════════════
   DESKTOP NAV LINKS
════════════════════════════════════════ */
.nav-link {
  font-size: 13px;
  font-weight: 500;
  letter-spacing: .03em;
  color: var(--dark);
  padding: 7px 13px;
  border-radius: 10px;
  text-decoration: none;
  white-space: nowrap;
  transition: background .2s, color .2s;
  background: none;
  border: none;
  cursor: pointer;
}
.nav-link:hover  { background: rgba(242,196,206,.2);  color: var(--dusty); }
.nav-link.active { background: rgba(242,196,206,.15); color: var(--dusty); font-weight: 600; }

/* ════════════════════════════════════════
   DESKTOP DROPDOWN
════════════════════════════════════════ */
.nav-dropdown { position: relative; }

.nav-dropdown-menu {
  display: none;
  position: absolute;
  top: calc(100% + 6px);
  left: 0;
  min-width: 220px;
  background: #fff;
  border: 1px solid rgba(212,137,154,.18);
  border-radius: 16px;
  box-shadow: 0 12px 40px rgba(44,26,30,.11);
  padding: 7px;
  z-index: 999;
  animation: ddIn .17s ease;
}
@keyframes ddIn {
  from { opacity:0; transform:translateY(-6px); }
  to   { opacity:1; transform:translateY(0); }
}
.nav-dropdown:hover .nav-dropdown-menu,
.nav-dropdown:focus-within .nav-dropdown-menu { display: block; }

.nav-dropdown:hover .dd-chevron,
.nav-dropdown:focus-within .dd-chevron { transform: rotate(180deg); }
.dd-chevron { transition: transform .2s; display: inline-block; }

/* Nested submenu */
.nav-sub-dropdown { position: relative; }
.nav-sub-menu {
  display: none;
  position: absolute;
  top: -7px;
  left: calc(100% + 5px);
  min-width: 215px;
  background: #fff;
  border: 1px solid rgba(212,137,154,.18);
  border-radius: 16px;
  box-shadow: 0 12px 40px rgba(44,26,30,.11);
  padding: 7px;
  z-index: 1000;
  animation: ddIn .17s ease;
}
@media (max-width: 1100px) {
  .nav-sub-menu { left: auto; right: calc(100% + 5px); }
}
.nav-sub-dropdown:hover .nav-sub-menu,
.nav-sub-dropdown:focus-within .nav-sub-menu { display: block; }

/* Dropdown item */
.dd-item {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 9px 13px;
  border-radius: 11px;
  font-size: 13px;
  font-weight: 500;
  color: var(--dark);
  text-decoration: none;
  white-space: nowrap;
  transition: background .15s, color .15s;
  cursor: pointer;
}
.dd-item:hover       { background: rgba(242,196,206,.18); color: var(--dusty); }
.dd-item.active      { color: var(--dusty); font-weight: 600; }
.dd-item .sub-arrow  { margin-left: auto; opacity: .35; transition: opacity .15s; }
.dd-item:hover .sub-arrow { opacity: 1; }

/* ════════════════════════════════════════
   CTA BUTTON
════════════════════════════════════════ */
.nav-cta {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-family: 'Jost', sans-serif;
  font-size: 13px;
  font-weight: 600;
  letter-spacing: .04em;
  color: #fff;
  background: linear-gradient(135deg, var(--blush) 0%, var(--dusty) 100%);
  padding: 10px 20px;
  border-radius: 100px;
  text-decoration: none;
  flex-shrink: 0;
  box-shadow: 0 4px 16px rgba(200,120,138,.28);
  transition: transform .25s, box-shadow .25s;
}
.nav-cta:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(200,120,138,.42);
  color: #fff;
  text-decoration: none;
}

/* ════════════════════════════════════════
   HAMBURGER — mobile only
════════════════════════════════════════ */
#menu-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 38px; height: 38px;
  border-radius: 10px;
  border: 1px solid rgba(212,137,154,.25);
  background: rgba(242,196,206,.1);
  color: var(--dark);
  cursor: pointer;
  transition: background .2s, border-color .2s;
}
#menu-btn:hover {
  background: rgba(242,196,206,.25);
  border-color: rgba(212,137,154,.4);
}
@media (min-width: 768px) {
  #menu-btn { display: none !important; }
}

/* ════════════════════════════════════════
   MOBILE MENU
════════════════════════════════════════ */
#mobile-menu {
  border-top: 1px solid rgba(212,137,154,.12);
  background: #fff;
  padding: 10px 12px 18px;
}

.mob-link {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 11px 14px;
  border-radius: 12px;
  font-size: 14px;
  font-weight: 500;
  color: var(--dark);
  text-decoration: none;
  width: 100%;
  background: none;
  border: none;
  text-align: left;
  cursor: pointer;
  transition: background .15s, color .15s;
}
.mob-link:hover { background: rgba(242,196,206,.15); color: var(--dusty); }

/* Accordion */
.mob-acc-content {
  max-height: 0;
  overflow: hidden;
  transition: max-height .3s ease;
}
.mob-acc-content.open { max-height: 900px; }
.mob-acc-btn .acc-chevron {
  margin-left: auto;
  opacity: .4;
  transition: transform .25s, opacity .2s;
}
.mob-acc-btn.open .acc-chevron { transform: rotate(180deg); opacity: 1; }

.mob-sub-link {
  display: block;
  padding: 8px 14px 8px 18px;
  font-size: 13px;
  color: var(--muted);
  text-decoration: none;
  border-radius: 9px;
  transition: background .15s, color .15s;
}
.mob-sub-link:hover { background: rgba(242,196,206,.12); color: var(--dusty); }
.mob-sub-link.see-all {
  font-size: 11px;
  font-weight: 600;
  letter-spacing: .06em;
  text-transform: uppercase;
  color: var(--rose);
  padding-top: 10px;
}

.mob-divider {
  border: none;
  border-top: 1px solid rgba(212,137,154,.12);
  margin: 8px 2px;
}

/* ════════════════════════════════════════
   UTILITIES
════════════════════════════════════════ */
.line-clamp-2 {
  display: -webkit-box;
  display: box;
  -webkit-box-orient: vertical;
  
  -webkit-line-clamp: 2;
  line-clamp: 2;

  overflow: hidden;
}
.prose p  { margin-bottom: 1rem; line-height: 1.75; }
.prose ul { padding-left: 1.25rem; margin-bottom: 1rem; }
.prose li { margin-bottom: .5rem; }
.prose a  { color: var(--dusty); text-decoration: underline; }
.card-hover { transition: transform .2s ease, box-shadow .2s ease; }
.card-hover:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,.07); }

/* Form */
.form-input, .form-select, .form-textarea {
  width: 100%; padding: .625rem .875rem;
  border: 1px solid #D1D5DB; border-radius: .5rem;
  font-size: .875rem; outline: none;
  transition: border-color .15s, box-shadow .15s; background: white;
}
.form-input:focus, .form-select:focus, .form-textarea:focus {
  border-color: var(--rose);
  box-shadow: 0 0 0 3px rgba(212,137,154,.15);
}
.form-label {
  display: block; font-size: .8125rem;
  font-weight: 600; color: var(--dark); margin-bottom: .375rem;
}
.badge-active   { background: #D1FAE5; color: #065F46; }
.badge-inactive { background: #FEE2E2; color: #991B1B; }
</style>

<script type="application/ld+json">
{
  "@context":"https://schema.org","@type":"LocalBusiness",
  "name":"<?= e($site_name) ?>","description":"<?= e($meta_desc) ?>",
  "url":"<?= BASE_URL ?>","telephone":"<?= e(setting('phone_display')) ?>",
  "address":{"@type":"PostalAddress","streetAddress":"<?= e(setting('address')) ?>",
    "addressLocality":"Tangerang","addressRegion":"Tangerang","addressCountry":"ID"},
  "openingHours":"Mo-Su 07:00-21:00","priceRange":"Rp300.000 - Rp1.500.000"
}
</script>
</head>
<body class="font-sans antialiased">

<!-- ════════════════════════════
     TOP BAR
════════════════════════════ -->
<div id="topbar" class="hidden md:block">
  <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
    <span>📍 <?= e(setting('address')) ?></span>
    <span>
      🌸 <?= e(setting('jam_buka')) ?>
      <span class="topbar-sep">|</span>
      📞 <a href="tel:<?= e(setting('whatsapp_number')) ?>"><?= e($phone) ?></a>
    </span>
  </div>
</div>

<!-- ════════════════════════════
     NAVBAR — sticky
════════════════════════════ -->
<nav id="navbar" role="navigation" aria-label="Navigasi utama">
  <div class="max-w-7xl mx-auto px-4">

    <div class="flex items-center justify-between h-16 md:h-20">

      <!-- Logo -->
      <a href="<?= BASE_URL ?>/" class="nav-brand">
        <div class="nav-logo-ring">
          <img src="<?= BASE_URL ?>/assets/images/icon.png" alt="Logo <?= e($site_name) ?>">
        </div>
        <div>
          <div class="nav-brand-name"><?= e($site_name) ?></div>
          <div class="nav-brand-sub hidden sm:block"><?= e(setting('site_tagline')) ?></div>
        </div>
      </a>

      <!-- Desktop menu -->
      <div class="hidden md:flex items-center gap-0.5 text-sm">

        <a href="<?= BASE_URL ?>/"
           class="nav-link <?= $current_slug==='' ? 'active':'' ?>">Beranda</a>
        <a href="<?= BASE_URL ?>/#tentang"   class="nav-link">Tentang</a>
        <a href="<?= BASE_URL ?>/#layanan"   class="nav-link">Layanan</a>

        <!-- Produk dropdown -->
        <div class="nav-dropdown">
          <button class="nav-link flex items-center gap-1.5 focus:outline-none" aria-haspopup="true">
            Produk
            <svg class="dd-chevron w-3.5 h-3.5 opacity-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>
          <div class="nav-dropdown-menu" role="menu">
            <?php
            $desktop_tree = [];
            if (!empty($nav_parents)) {
              foreach ($nav_parents as $par)
                $desktop_tree[] = ['name'=>$par['name'],'slug'=>$par['slug'],'children'=>$nav_children[$par['id']]??[]];
            } else {
              $desktop_tree = [
                ['name'=>'Bunga Papan','slug'=>'bunga-papan-tangerang','children'=>[
                  ['name'=>'Happy Wedding','slug'=>'bunga-papan-happy-wedding-tangerang'],
                  ['name'=>'Duka Cita','slug'=>'bunga-papan-duka-cita-tangerang'],
                  ['name'=>'Selamat & Sukses','slug'=>'bunga-papan-selamat-sukses-tangerang'],
                ]],
                ['name'=>'Hand Bouquet','slug'=>'hand-bouquet-tangerang','children'=>[
                  ['name'=>'Anniversary','slug'=>'hand-bouquet-anniversary-tangerang'],
                  ['name'=>'Birthday','slug'=>'hand-bouquet-birthday-tangerang'],
                  ['name'=>'Graduation','slug'=>'hand-bouquet-graduation-tangerang'],
                ]],
                ['name'=>'Bunga Meja','slug'=>'bunga-meja-tangerang','children'=>[]],
                ['name'=>'Standing Flowers','slug'=>'standing-flowers-tangerang','children'=>[]],
              ];
            }
            foreach ($desktop_tree as $node):
              $hasKids = !empty($node['children']);
            ?>
              <?php if ($hasKids): ?>
              <div class="nav-sub-dropdown">
                <div class="dd-item">
                  <a href="<?= BASE_URL ?>/<?= e($node['slug']) ?>/"
                     class="flex-1 <?= $current_slug===$node['slug']?'font-semibold':'' ?>"
                     style="text-decoration:none;color:inherit">
                    <?= e($node['name']) ?>
                  </a>
                  <svg class="sub-arrow w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                  </svg>
                </div>
                <div class="nav-sub-menu" role="menu">
                  <?php foreach ($node['children'] as $ch): ?>
                  <a href="<?= BASE_URL ?>/<?= e($ch['slug']) ?>/"
                     class="dd-item <?= $current_slug===$ch['slug']?'active':'' ?>" role="menuitem">
                    <?= e($ch['name']) ?>
                  </a>
                  <?php endforeach; ?>
                </div>
              </div>
              <?php else: ?>
              <a href="<?= BASE_URL ?>/<?= e($node['slug']) ?>/"
                 class="dd-item <?= $current_slug===$node['slug']?'active':'' ?>" role="menuitem">
                <?= e($node['name']) ?>
              </a>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>

        <a href="<?= BASE_URL ?>/#area"      class="nav-link">Area Kirim</a>
        <a href="<?= BASE_URL ?>/#testimoni" class="nav-link">Testimoni</a>
        <a href="<?= BASE_URL ?>/#faq"       class="nav-link">FAQ</a>

      </div>

      <!-- CTA + Hamburger -->
      <div class="flex items-center gap-3">
        <a href="<?= e($wa_url) ?>" target="_blank" rel="noopener"
           class="nav-cta hidden md:inline-flex">
          <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
            <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
          </svg>
          Pesan Sekarang
        </a>

        <button id="menu-btn" aria-label="Buka menu">
          <svg id="icon-open"  class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
          <svg id="icon-close" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

    </div><!-- /row -->

    <!-- MOBILE MENU -->
    <div id="mobile-menu" class="md:hidden hidden">

      <a href="<?= BASE_URL ?>/"           class="mob-link mob-close"><span>🏠</span> Beranda</a>
      <a href="<?= BASE_URL ?>/#tentang"   class="mob-link mob-close"><span>🌿</span> Tentang</a>
      <a href="<?= BASE_URL ?>/#layanan"   class="mob-link mob-close"><span>💐</span> Layanan</a>

      <div>
        <button class="mob-acc-btn mob-link" onclick="toggleAcc(this)">
          <span>🛍️</span><span>Produk</span>
          <svg class="acc-chevron w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
        <div class="mob-acc-content">
          <div class="pl-3 ml-5 border-l-2" style="border-color:rgba(212,137,154,.22)">
            <?php foreach ($desktop_tree as $node):
              $hasKids2 = !empty($node['children']); ?>
              <?php if ($hasKids2): ?>
              <div>
                <button class="mob-acc-btn mob-link text-sm" onclick="toggleAcc(this)">
                  <?= e($node['name']) ?>
                  <svg class="acc-chevron w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                  </svg>
                </button>
                <div class="mob-acc-content">
                  <div class="pl-2 ml-3 border-l" style="border-color:rgba(212,137,154,.15)">
                    <a href="<?= BASE_URL ?>/<?= e($node['slug']) ?>/"
                       class="mob-sub-link see-all mob-close">Lihat semua →</a>
                    <?php foreach ($node['children'] as $ch): ?>
                    <a href="<?= BASE_URL ?>/<?= e($ch['slug']) ?>/"
                       class="mob-sub-link mob-close"><?= e($ch['name']) ?></a>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
              <?php else: ?>
              <a href="<?= BASE_URL ?>/<?= e($node['slug']) ?>/"
                 class="mob-sub-link mob-close"><?= e($node['name']) ?></a>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <a href="<?= BASE_URL ?>/#area"      class="mob-link mob-close"><span>📍</span> Area Kirim</a>
      <a href="<?= BASE_URL ?>/#testimoni" class="mob-link mob-close"><span>⭐</span> Testimoni</a>
      <a href="<?= BASE_URL ?>/#faq"       class="mob-link mob-close"><span>❓</span> FAQ</a>

      <hr class="mob-divider">

      <a href="<?= e($wa_url) ?>" target="_blank"
         class="nav-cta justify-center mt-1" style="border-radius:14px;">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
          <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
          <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
        </svg>
        Pesan via WhatsApp
      </a>

    </div><!-- /mobile-menu -->

  </div>
</nav>

<script>
/* ── Navbar scroll shadow ── */
window.addEventListener('scroll', () => {
  document.getElementById('navbar')
    .classList.toggle('scrolled', window.scrollY > 10);
}, { passive: true });

/* ── Hamburger toggle ── */
const menuBtn    = document.getElementById('menu-btn');
const mobileMenu = document.getElementById('mobile-menu');
const iconOpen   = document.getElementById('icon-open');
const iconClose  = document.getElementById('icon-close');

menuBtn.addEventListener('click', () => {
  const isHidden = mobileMenu.classList.toggle('hidden');
  iconOpen.classList.toggle('hidden', !isHidden);
  iconClose.classList.toggle('hidden', isHidden);
  document.body.style.overflow = !isHidden ? 'hidden' : '';
});

function closeMob() {
  mobileMenu.classList.add('hidden');
  iconOpen.classList.remove('hidden');
  iconClose.classList.add('hidden');
  document.body.style.overflow = '';
}
document.querySelectorAll('.mob-close').forEach(el =>
  el.addEventListener('click', closeMob)
);

/* ── Mobile accordion ── */
function toggleAcc(btn) {
  const content = btn.nextElementSibling;
  const isOpen  = content.classList.contains('open');
  btn.parentElement.parentElement
    .querySelectorAll(':scope > div > .mob-acc-btn')
    .forEach(b => {
      b.classList.remove('open');
      const c = b.nextElementSibling;
      if (c) c.classList.remove('open');
    });
  if (!isOpen) { btn.classList.add('open'); content.classList.add('open'); }
}

/* ── Smooth scroll ── */
document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', e => {
    const href = a.getAttribute('href');
    if (href === '#') return;
    const target = document.querySelector(href);
    if (target) { e.preventDefault(); target.scrollIntoView({ behavior:'smooth', block:'start' }); }
  });
});

/* ── Image fallback ── */
document.querySelectorAll('img').forEach(img => {
  img.addEventListener('error', () => {
    img.src = 'https://images.unsplash.com/photo-1487530811015-780780dde0e4?w=400&h=300&fit=crop';
  });
});
</script>