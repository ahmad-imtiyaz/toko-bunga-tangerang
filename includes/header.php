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

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  theme: {
    extend: {
      colors: {
        cream: { DEFAULT:'#FDF8F0', dark:'#F5EDD8' },
        sage:  { DEFAULT:'#7A9E7E', dark:'#5C7C60', light:'#A8C5AC' },
        gold:  { DEFAULT:'#C9A96E', dark:'#A8843E' },
        navy:  { DEFAULT:'#2C3E6B', dark:'#1E2D52' },
      },
      fontFamily: {
        serif: ['"Playfair Display"','Georgia','serif'],
        sans:  ['"DM Sans"','sans-serif'],
      },
    }
  }
}
</script>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">

<style>
/* ════════════════════════════════════════════
   FLOATING PILL NAVBAR — ala iPhone / macOS
════════════════════════════════════════════ */

/* Wrapper fixed — beri ruang agar pill mengambang */
#navbar-wrap {
  position: fixed;
  top: 32px; left: 0; right: 0; /* 32px = tinggi top bar */
  z-index: 50;
  padding: 12px 20px 0;
  pointer-events: none; /* area luar pill tetap bisa diklik */
  transition: padding .4s cubic-bezier(.4,0,.2,1), top .4s cubic-bezier(.4,0,.2,1);
}
#navbar-wrap.scrolled { padding-top: 8px; top: 0; }

/* Pill utama */
#navbar {
  pointer-events: all;
  border-radius: 9999px;
  background: rgba(11,31,74,.3);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(255,255,255,.15);
  box-shadow: 0 4px 32px rgba(0,0,0,.3), inset 0 1px 0 rgba(255,255,255,.08);
  transition: background .4s ease, border-color .4s ease,
              box-shadow .4s ease, border-radius .3s ease;
}

/* Scrolled: pill lebih gelap & jelas */
#navbar-wrap.scrolled #navbar {
  background: rgba(8,23,41,.95);
  border-color: rgba(245,197,24,.2);
  box-shadow: 0 8px 48px rgba(0,0,0,.5), inset 0 1px 0 rgba(245,197,24,.06);
}

/* Mobile: pill jadi kotak rounded saat menu terbuka */
#navbar.menu-open {
  border-radius: 20px 20px 0 0 !important;
  border-bottom-color: transparent !important;
}

@media (max-width: 767px) {
  #navbar-wrap { padding: 10px 12px 0; }
}

/* Mobile menu dropdown dari bawah pill */
#mobile-menu {
  display: none;
  background: rgba(8,23,41,.98);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(245,197,24,.15);
  border-top: none;
  border-radius: 0 0 20px 20px;
}
#mobile-menu.open { display: block; }

/* ── Nav links ── */
.nav-link {
  color: rgba(255,255,255,.82);
  font-size: .875rem; font-weight: 500;
  padding: 7px 12px; border-radius: 9999px;
  transition: color .2s, background .2s;
  text-decoration: none; white-space: nowrap;
}
.nav-link:hover { color: #F5C518; background: rgba(245,197,24,.1); }
.nav-link.active { color: #F5C518; font-weight: 600; }

.brand-name { color: #fff; font-size: 15px; }
.brand-sub  { color: rgba(255,255,255,.5); font-size: 11px; }
#navbar-wrap.scrolled .brand-sub { color: rgba(245,197,24,.55); }

/* ── Desktop Dropdown ── */
.nav-dropdown { position: relative; }
.nav-dropdown-menu {
  display: none;
  position: absolute;
  top: calc(100% + 14px);
  left: 50%; transform: translateX(-50%);
  min-width: 230px;
  background: rgba(8,23,41,.98);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(245,197,24,.2);
  border-radius: 18px;
  box-shadow: 0 20px 60px rgba(0,0,0,.5);
  z-index: 999; padding: 8px;
  animation: dropIn .18s ease;
}
@keyframes dropIn {
  from { opacity:0; transform:translateX(-50%) translateY(-8px); }
  to   { opacity:1; transform:translateX(-50%) translateY(0); }
}
.nav-dropdown:hover .nav-dropdown-menu,
.nav-dropdown:focus-within .nav-dropdown-menu { display: block; }

/* ── Nested submenu ── */
.nav-sub-dropdown { position: relative; }
.nav-sub-dropdown-menu {
  display: none;
  position: absolute;
  top: -8px; left: calc(100% + 6px);
  min-width: 220px;
  background: rgba(8,23,41,.98);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(245,197,24,.2);
  border-radius: 18px;
  box-shadow: 0 20px 60px rgba(0,0,0,.5);
  z-index: 1000; padding: 8px;
  animation: dropInR .18s ease;
}
@keyframes dropInR {
  from { opacity:0; transform:translateY(-6px); }
  to   { opacity:1; transform:translateY(0); }
}
@media (max-width: 1100px) {
  .nav-sub-dropdown-menu { left:auto; right:calc(100% + 6px); }
}
.nav-sub-dropdown:hover .nav-sub-dropdown-menu,
.nav-sub-dropdown:focus-within .nav-sub-dropdown-menu { display: block; }

/* ── Dropdown item ── */
.dd-item {
  display:flex; align-items:center; gap:8px;
  padding:9px 13px; border-radius:12px;
  font-size:.84rem; font-weight:500;
  color:rgba(255,255,255,.7); white-space:nowrap;
  transition:background .15s, color .15s;
  cursor:pointer; text-decoration:none;
}
.dd-item:hover { background:rgba(245,197,24,.1); color:#F5C518; }
.dd-item.has-sub { justify-content:space-between; }
.dd-item .chevron-right { opacity:.4; transition:opacity .15s; }
.dd-item.has-sub:hover .chevron-right { opacity:1; }

/* ── Mobile link ── */
.mob-link {
  display:flex; align-items:center; gap:10px;
  padding:11px 16px; border-radius:12px;
  font-size:.875rem; font-weight:500;
  color:rgba(255,255,255,.75);
  transition:background .15s, color .15s;
  text-decoration:none; width:100%; cursor:pointer;
}
.mob-link:hover { background:rgba(245,197,24,.08); color:#F5C518; }

/* ── Mobile accordion ── */
.mob-acc-content { max-height:0; overflow:hidden; transition:max-height .3s ease; }
.mob-acc-content.open { max-height:800px; }
.mob-acc-btn .acc-arrow { transition:transform .25s; }
.mob-acc-btn.open .acc-arrow { transform:rotate(180deg); }

/* ── Hamburger ── */
#menu-btn {
  color:rgba(255,255,255,.85); border-radius:9999px;
  padding:6px 10px; transition:background .2s, color .2s;
}
#menu-btn:hover { background:rgba(245,197,24,.1); color:#F5C518; }

/* ── Top bar ── */
#topbar {
  background:#081729;
  border-bottom:1px solid rgba(245,197,24,.08);
  transition: opacity .3s, max-height .4s;
  overflow:hidden; max-height:40px;
}
#topbar.hide { opacity:0; max-height:0; pointer-events:none; }
</style>

<script type="application/ld+json">
{
  "@context":"https://schema.org","@type":"LocalBusiness",
  "name":"<?= e($site_name) ?>","description":"<?= e($meta_desc) ?>",
  "url":"<?= BASE_URL ?>","telephone":"<?= e(setting('phone_display')) ?>",
  "address":{"@type":"PostalAddress","streetAddress":"<?= e(setting('address')) ?>",
    "addressLocality":"Grogol","addressRegion":"DKI Jakarta","addressCountry":"ID"},
  "openingHours":"Mo-Su 07:00-21:00","priceRange":"Rp300.000 - Rp1.500.000"
}
</script>
</head>
<body class="font-sans bg-[#0B1F4A] text-gray-800 antialiased">

<!-- TOP BAR — hilang saat scroll -->
<div id="topbar" class="hidden md:block text-xs py-2">
  <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
    <span class="text-white/45">📍 <?= e(setting('address')) ?></span>
    <span class="text-white/45">
      ⏰ <?= e(setting('jam_buka')) ?> &nbsp;|&nbsp;
      📞 <a href="tel:<?= e(setting('whatsapp_number')) ?>"
            class="text-[#F5C518]/65 hover:text-[#F5C518] transition"><?= e($phone) ?></a>
    </span>
  </div>
</div>

<!-- ══════════════════════════════════════════════
     FLOATING PILL NAVBAR
══════════════════════════════════════════════ -->
<div id="navbar-wrap">
<nav id="navbar">
  <div class="max-w-7xl mx-auto px-4 md:px-5">
    <div class="flex items-center justify-between h-14 md:h-[60px]">

      <!-- Logo -->
      <a href="<?= BASE_URL ?>/" class="flex items-center gap-2.5 flex-shrink-0">
        <div class="w-8 h-8 rounded-full overflow-hidden ring-2 ring-[#F5C518]/30 flex-shrink-0">
          <img src="<?= BASE_URL ?>/assets/images/icon.png" alt="Logo" class="w-full h-full object-cover">
        </div>
        <div>
          <div class="brand-name font-serif font-bold leading-tight"><?= e($site_name) ?></div>
          <div class="brand-sub hidden sm:block"><?= e(setting('site_tagline')) ?></div>
        </div>
      </a>

      <!-- Desktop Menu -->
      <div class="hidden md:flex items-center gap-0.5">
        <a href="<?= BASE_URL ?>/"           class="nav-link <?= $current_slug==='' ? 'active':'' ?>">Home</a>
        <a href="<?= BASE_URL ?>/#tentang"   class="nav-link">Tentang</a>
        <a href="<?= BASE_URL ?>/#layanan"   class="nav-link">Layanan</a>

        <!-- Produk dropdown -->
        <div class="nav-dropdown">
          <button class="nav-link flex items-center gap-1 focus:outline-none">
            Produk
            <svg class="w-3.5 h-3.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>
          <div class="nav-dropdown-menu">
            <?php
            $desktop_tree = [];
            if (!empty($nav_parents)) {
              foreach ($nav_parents as $par)
                $desktop_tree[] = ['name'=>$par['name'],'slug'=>$par['slug'],'children'=>$nav_children[$par['id']]??[]];
            } else {
              $desktop_tree = [
                ['name'=>'Bunga Papan','slug'=>'bunga-papan-jakarta-utara','children'=>[
                  ['name'=>'Happy Wedding','slug'=>'bunga-papan-happy-wedding-jakarta-utara'],
                  ['name'=>'Duka Cita','slug'=>'bunga-papan-duka-cita-jakarta-utara'],
                  ['name'=>'Selamat & Sukses','slug'=>'bunga-papan-selamat-sukses-jakarta-utara'],
                ]],
                ['name'=>'Hand Bouquet','slug'=>'hand-bouquet-jakarta-utara','children'=>[
                  ['name'=>'Anniversary','slug'=>'hand-bouquet-anniversary-jakarta-utara'],
                  ['name'=>'Birthday','slug'=>'hand-bouquet-birthday-jakarta-utara'],
                  ['name'=>'Graduation','slug'=>'hand-bouquet-graduation-jakarta-utara'],
                ]],
                ['name'=>'Bunga Meja','slug'=>'bunga-meja-jakarta-utara','children'=>[]],
                ['name'=>'Standing Flowers','slug'=>'standing-flowers-jakarta-utara','children'=>[]],
              ];
            }
            foreach ($desktop_tree as $node):
              $hk = !empty($node['children']); ?>
              <?php if ($hk): ?>
              <div class="nav-sub-dropdown">
                <div class="dd-item has-sub">
                  <a href="<?= BASE_URL ?>/<?= e($node['slug']) ?>/"
                     class="flex-1 <?= $current_slug===$node['slug']?'text-[#F5C518]':'' ?>">
                    <?= e($node['name']) ?>
                  </a>
                  <svg class="chevron-right w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                  </svg>
                </div>
                <div class="nav-sub-dropdown-menu">
                  <?php foreach ($node['children'] as $ch): ?>
                  <a href="<?= BASE_URL ?>/<?= e($ch['slug']) ?>/"
                     class="dd-item <?= $current_slug===$ch['slug']?'text-[#F5C518]':'' ?>">
                    <?= e($ch['name']) ?>
                  </a>
                  <?php endforeach; ?>
                </div>
              </div>
              <?php else: ?>
              <a href="<?= BASE_URL ?>/<?= e($node['slug']) ?>/"
                 class="dd-item <?= $current_slug===$node['slug']?'text-[#F5C518]':'' ?>">
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

      <!-- CTA Desktop -->
      <a href="<?= e($wa_url) ?>" target="_blank" rel="noopener"
         class="hidden md:inline-flex items-center gap-2 font-bold text-[#0B1F4A] text-[13px] px-4 py-2 rounded-full transition hover:brightness-110 hover:-translate-y-0.5 flex-shrink-0"
         style="background:#F5C518;">
        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
          <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
          <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
        </svg>
        Pesan Sekarang
      </a>

      <!-- Hamburger Mobile -->
      <button id="menu-btn" class="md:hidden flex items-center" aria-label="Menu">
        <svg id="icon-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <svg id="icon-close" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>

    </div><!-- /flex row -->

    <!-- MOBILE MENU -->
    <div id="mobile-menu">
      <div class="flex flex-col gap-1 px-2 py-3">
        <a href="<?= BASE_URL ?>/"          class="mob-link mob-close">🏠 Home</a>
        <a href="<?= BASE_URL ?>/#tentang"  class="mob-link mob-close">💐 Tentang</a>
        <a href="<?= BASE_URL ?>/#layanan"  class="mob-link mob-close">🌸 Layanan</a>

        <!-- Produk accordion -->
        <div>
          <button class="mob-acc-btn mob-link justify-between" onclick="toggleMobAcc(this)">
            <span>🛍️ Produk</span>
            <svg class="acc-arrow w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>
          <div class="mob-acc-content pl-4 mt-1">
            <?php foreach ($desktop_tree as $node):
              $hk = !empty($node['children']); ?>
              <?php if ($hk): ?>
              <div>
                <button class="mob-acc-btn mob-link justify-between text-sm" onclick="toggleMobAcc(this)">
                  <span><?= e($node['name']) ?></span>
                  <svg class="acc-arrow w-3.5 h-3.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                  </svg>
                </button>
                <div class="mob-acc-content pl-3 border-l border-[#F5C518]/15 ml-4">
                  <a href="<?= BASE_URL ?>/<?= e($node['slug']) ?>/"
                     class="block px-3 py-1.5 text-xs font-bold text-[#F5C518]/55 hover:text-[#F5C518] mob-close">
                    Lihat semua <?= e($node['name']) ?> →
                  </a>
                  <?php foreach ($node['children'] as $ch): ?>
                  <a href="<?= BASE_URL ?>/<?= e($ch['slug']) ?>/"
                     class="block px-3 py-2 text-sm text-white/55 hover:text-[#F5C518] transition mob-close">
                    <?= e($ch['name']) ?>
                  </a>
                  <?php endforeach; ?>
                </div>
              </div>
              <?php else: ?>
              <a href="<?= BASE_URL ?>/<?= e($node['slug']) ?>/" class="mob-link text-sm mob-close">
                <?= e($node['name']) ?>
              </a>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>

        <a href="<?= BASE_URL ?>/#area"      class="mob-link mob-close">📍 Area Kirim</a>
        <a href="<?= BASE_URL ?>/#testimoni" class="mob-link mob-close">⭐ Testimoni</a>
        <a href="<?= BASE_URL ?>/#faq"       class="mob-link mob-close">❓ FAQ</a>

        <a href="<?= e($wa_url) ?>" target="_blank"
           class="mt-2 mx-1 flex items-center justify-center gap-2 font-bold text-[#0B1F4A] py-3 rounded-full transition hover:brightness-110"
           style="background:#F5C518;">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
            <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
          </svg>
          Pesan via WhatsApp
        </a>
      </div>
    </div>

  </div>
</nav>
</div><!-- /navbar-wrap -->

<script>
// ── Scroll handler ───────────────────────────────────────────────
const wrap   = document.getElementById('navbar-wrap');
const topbar = document.getElementById('topbar');
window.addEventListener('scroll', () => {
  const s = window.scrollY > 50;
  wrap.classList.toggle('scrolled', s);
  topbar?.classList.toggle('hide', s);
}, { passive: true });

// ── Hamburger toggle ─────────────────────────────────────────────
const menuBtn    = document.getElementById('menu-btn');
const mobileMenu = document.getElementById('mobile-menu');
const navbar     = document.getElementById('navbar');
const iconOpen   = document.getElementById('icon-open');
const iconClose  = document.getElementById('icon-close');

menuBtn.addEventListener('click', () => {
  const isOpen = mobileMenu.classList.toggle('open');
  navbar.classList.toggle('menu-open', isOpen);
  iconOpen.classList.toggle('hidden', isOpen);
  iconClose.classList.toggle('hidden', !isOpen);
});

// Tutup mobile menu
function closeMobileMenu() {
  mobileMenu.classList.remove('open');
  navbar.classList.remove('menu-open');
  iconOpen.classList.remove('hidden');
  iconClose.classList.add('hidden');
}
document.querySelectorAll('.mob-close').forEach(el => el.addEventListener('click', closeMobileMenu));

// ── Mobile accordion ─────────────────────────────────────────────
function toggleMobAcc(btn) {
  const content = btn.nextElementSibling;
  const isOpen  = content.classList.contains('open');
  const parent  = btn.parentElement.parentElement;
  parent.querySelectorAll(':scope > div > .mob-acc-btn').forEach(b => {
    b.classList.remove('open');
    const c = b.nextElementSibling;
    if (c) c.classList.remove('open');
  });
  if (!isOpen) { btn.classList.add('open'); content.classList.add('open'); }
}
</script>