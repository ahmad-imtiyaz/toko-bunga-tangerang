<?php
require_once __DIR__ . '/../includes/config.php';

$meta_title    = $location['meta_title']       ?: 'Toko Bunga ' . $location['name'] . ' - Florist Tangerang Terpercaya';
$meta_desc     = $location['meta_description'] ?: '';
$meta_keywords = 'toko bunga ' . strtolower($location['name']) . ', florist ' . strtolower($location['name']) . ', bunga Tangerang';

$all_cats_raw = db()->query("SELECT * FROM categories WHERE status='active' ORDER BY urutan ASC, id ASC")->fetchAll();
$all_cats = []; $all_cats_subs = [];
foreach ($all_cats_raw as $ac) {
    $pid = $ac['parent_id'] ?? null;
    if ($pid === null || $pid == 0) $all_cats[] = $ac;
    else $all_cats_subs[$pid][] = $ac;
}

$cats_with_products = [];
foreach ($all_cats as $cat) {
    $sub_ids   = array_column($all_cats_subs[$cat['id']] ?? [], 'id');
    $all_ids   = array_merge([$cat['id']], $sub_ids);
    $in_clause = implode(',', array_fill(0, count($all_ids), '?'));
    $stmt      = db()->prepare("SELECT * FROM products WHERE status='active' AND category_id IN ($in_clause) ORDER BY id ASC");
    $stmt->execute($all_ids);
    $prods     = $stmt->fetchAll();
    if (!empty($prods)) $cats_with_products[] = ['cat' => $cat, 'products' => $prods];
}

$locations = db()->query("SELECT * FROM locations WHERE status='active' ORDER BY id")->fetchAll();
$faqs      = db()->query("SELECT * FROM faqs WHERE status='active' ORDER BY urutan LIMIT 6")->fetchAll();
$wa_url    = setting('whatsapp_url');

// ── Slider kalkulasi ──
$slider_per_page    = 10;
$slider_total       = count($locations);
$slider_pages       = (int)ceil($slider_total / $slider_per_page);
$slider_active_idx  = array_search($location['id'], array_column($locations, 'id'));
$slider_active_page = ($slider_active_idx !== false) ? (int)floor($slider_active_idx / $slider_per_page) : 0;
$all_prices = [];
foreach ($cats_with_products as $row) foreach ($row['products'] as $p) $all_prices[] = $p['price'];
$min_price = !empty($all_prices) ? min($all_prices) : 300000;

require __DIR__ . '/../includes/header.php';
?>

<style>
:root {
  --blush: #F2C4CE; --rose: #D4899A; --dusty: #C8778A;
  --cream: #FAF5EE; --ivory: #FDF9F4; --soft: #F7EEF0;
  --dark:  #2C1A1E; --muted: rgba(44,26,30,.45);
}

/* Ticker */
@keyframes ticker { from{transform:translateX(0)} to{transform:translateX(-50%)} }
.loc-ticker-inner { animation:ticker 22s linear infinite; display:flex; white-space:nowrap; }

/* Shimmer */
@keyframes shimmer-x { 0%{background-position:-200% center} 100%{background-position:200% center} }
.rose-line {
  height:1px;
  background:linear-gradient(90deg,transparent,var(--rose),var(--blush),var(--rose),transparent);
  background-size:200% auto; animation:shimmer-x 3s linear infinite;
}

/* Reveal */
@keyframes fadeUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
.reveal{animation:fadeUp .6s ease both}
.reveal-1{animation-delay:.1s} .reveal-2{animation-delay:.2s}
.reveal-3{animation-delay:.3s} .reveal-4{animation-delay:.45s}

/* Petals */
@keyframes floatPetal {
  0%,100%{transform:translateY(0) rotate(0deg);opacity:.35}
  33%{transform:translateY(-28px) rotate(12deg);opacity:.55}
  66%{transform:translateY(-12px) rotate(-8deg);opacity:.4}
}
.float-petal { position:absolute; pointer-events:none; user-select:none; font-size:16px; animation:floatPetal var(--dur,7s) ease-in-out var(--del,0s) infinite; opacity:.35; }

.stat-num { font-family:'Playfair Display',serif; background:linear-gradient(135deg,var(--dusty),var(--rose),var(--blush)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
@keyframes pin-pulse{0%,100%{box-shadow:0 0 0 0 rgba(212,137,154,.5)}50%{box-shadow:0 0 0 8px rgba(212,137,154,0)}}
.pin-dot{animation:pin-pulse 2s ease infinite}

/* ━━━ UNIFORM GRID LAYANAN ━━━ */
.pin-grid {
  display: grid;
  grid-template-columns: 1fr;        /* mobile: 1 kolom */
  gap: 12px;
}
@media(min-width: 640px) { .pin-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; } }
@media(min-width: 1024px) { .pin-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; } }

.pin-item {
  display: block;
  position: relative;
}

/* ── Card shell — tinggi seragam ── */
.pin-card {
  position: relative;
  border-radius: 18px;
  overflow: hidden;
  cursor: pointer;
  display: block;
  text-decoration: none;
  height: 220px; /* tinggi tetap, semua sama */
  transform: translateZ(0);
  transition: transform .45s cubic-bezier(.34,1.56,.64,1), box-shadow .45s ease;
  will-change: transform, box-shadow;
  box-shadow: 0 4px 20px rgba(44,26,30,.1);
}
@media(min-width: 640px) { .pin-card { height: 240px; } }
@media(min-width: 1024px) { .pin-card { height: 260px; } }

.pin-card:hover {
  transform: translateY(-6px) scale(1.015);
  box-shadow: 0 20px 60px rgba(200,119,138,.38), 0 4px 16px rgba(44,26,30,.12);
}
/* ── Background image layer ── */
.pin-bg {
  position: absolute;
  inset: 0;
  background-size: cover;
  background-position: center;
  transition: transform .7s cubic-bezier(.4,0,.2,1);
}
.pin-card:hover .pin-bg { transform: scale(1.08); }

.pin-bg-grad {
  position: absolute;
  inset: 0;
}

/* ── Overlays ── */
.pin-ov-base {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(44,26,30,.9) 0%, rgba(44,26,30,.4) 50%, transparent 100%);
}
.pin-ov-shimmer { position: absolute; inset: 0; }
.pin-sparkle { display: none; }
.pin-bignum { display: none; }
.pin-icon {
  position: absolute;
  top: 50%; left: 50%;
  transform: translate(-50%, -60%);
  font-size: 36px;
  opacity: .4;
  pointer-events: none;
}

/* ── Top badge ── */
.pin-eyebrow {
  position: absolute;
  top: 12px; left: 12px;
  z-index: 6;
  display: inline-flex;
  align-items: center;
  gap: 5px;
  font-size: 9px;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: .09em;
  color: rgba(253,249,244,.9);
  background: rgba(44,26,30,.45);
  backdrop-filter: blur(8px);
  padding: 4px 10px;
  border-radius: 999px;
}
.pin-sub-badge { display: none; }

/* ── Footer ── */
.pin-footer {
  position: absolute;
  bottom: 0; left: 0; right: 0;
  padding: 0 14px 14px;
  z-index: 5;
}
.pin-accent-line {
  width: 24px; height: 2px;
  background: linear-gradient(90deg, var(--blush), var(--rose));
  border-radius: 2px;
  margin-bottom: 6px;
}
.pin-name {
  font-family: 'Playfair Display', Georgia, serif;
  font-weight: 900;
  color: #fff;
  font-size: 15px;
  line-height: 1.2;
  margin-bottom: 6px;
}

/* ── Sub list (hover reveal) ── */
.pin-subs {
  overflow: hidden;
  max-height: 0;
  opacity: 0;
  transition: max-height .4s ease, opacity .3s ease;
}
.pin-card:hover .pin-subs { max-height: 120px; opacity: 1; }

.pin-sub-item {
  display: block;
  font-size: 10.5px;
  font-weight: 600;
  color: rgba(255,255,255,.55);
  padding: 2px 0;
  text-decoration: none;
}
.pin-sub-item:hover { color: var(--blush); }

.pin-cta {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  font-size: 9.5px;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: .09em;
  color: #fff;
  background: linear-gradient(135deg, var(--dusty), var(--rose));
  border-radius: 999px;
  padding: 5px 13px;
  text-decoration: none;
  opacity: 0;
  transition: opacity .3s ease;
}
.pin-card:hover .pin-cta { opacity: 1; }

/* ── Gradient fallbacks ── */
.pg-0 { background: linear-gradient(145deg, #fde8ef, #f2c4ce, #e8a8b8); }
.pg-1 { background: linear-gradient(145deg, #f5dce0, #edb5c0, #d4899a); }
.pg-2 { background: linear-gradient(145deg, #fdf0f2, #f7e0e5, #f2c4ce); }
.pg-3 { background: linear-gradient(145deg, #f0d4da, #d4899a, #c8778a); }
.pg-4 { background: linear-gradient(145deg, #fce4eb, #f2c4ce, #eaaebb); }
.pg-5 { background: linear-gradient(145deg, #f9e8ed, #e8b8c5, #d4899a); }

/* ━━━ ZIGZAG PINTEREST PRODUK ━━━ */
.zzrow{display:grid;grid-template-columns:320px 1fr;gap:0;align-items:stretch}
.zzrow.rev{grid-template-columns:1fr 320px}
.zzrow.rev .zz-cat{order:2}
.zzrow.rev .zz-rail{order:1}
@media(max-width:860px){
  .zzrow,.zzrow.rev{grid-template-columns:1fr}
  .zzrow.rev .zz-cat,.zzrow .zz-cat{order:1}
  .zzrow.rev .zz-rail,.zzrow .zz-rail{order:2}
}

.zz-cat{
  position:relative;overflow:hidden;min-height:300px;
  display:flex;flex-direction:column;justify-content:flex-end;
  text-decoration:none;cursor:pointer;
  border-radius:var(--cbr,28px 6px 6px 28px);
  transition:box-shadow .4s ease;
}
.zzrow.rev .zz-cat{--cbr:6px 28px 28px 6px}
.zz-cat:hover{box-shadow:0 28px 72px rgba(200,119,138,.42)}

.zz-cat-bg{position:absolute;inset:0;background-size:cover;background-position:center;transition:transform .75s cubic-bezier(.4,0,.2,1)}
.zz-cat:hover .zz-cat-bg{transform:scale(1.07)}

.zz-cat-ov{position:absolute;inset:0;background:linear-gradient(to top,rgba(44,26,30,.97) 0%,rgba(44,26,30,.65) 38%,rgba(44,26,30,.18) 65%,transparent 100%);transition:background .4s ease}
.zz-cat:hover .zz-cat-ov{background:linear-gradient(to top,rgba(44,26,30,.98) 0%,rgba(44,26,30,.78) 40%,rgba(200,119,138,.12) 68%,transparent 100%)}

.zz-cat-stripe{position:absolute;top:-40px;right:-40px;width:150px;height:150px;border-radius:50%;background:conic-gradient(from 130deg,rgba(242,196,206,.2) 0deg,transparent 85deg);transition:transform .45s ease;pointer-events:none}
.zz-cat:hover .zz-cat-stripe{transform:scale(1.35) rotate(18deg)}

.zz-cat-num{position:absolute;bottom:-14px;right:14px;z-index:1;font-family:'Playfair Display',serif;font-size:88px;font-weight:900;line-height:1;color:rgba(242,196,206,.06);user-select:none;pointer-events:none;transition:color .35s ease}
.zz-cat:hover .zz-cat-num{color:rgba(242,196,206,.13)}

.zz-cat-chip{position:absolute;top:16px;left:0;z-index:4;display:inline-flex;align-items:center;gap:5px;font-size:9px;font-weight:900;text-transform:uppercase;letter-spacing:.11em;color:var(--dusty);background:rgba(253,249,244,.93);padding:5px 14px 5px 10px;border-radius:0 999px 999px 0;backdrop-filter:blur(8px);transition:background .2s ease}
.zzrow.rev .zz-cat .zz-cat-chip{left:auto;right:0;padding:5px 10px 5px 14px;border-radius:999px 0 0 999px}
.zz-cat:hover .zz-cat-chip{background:rgba(242,196,206,.95)}

.zz-cat-petal{position:absolute;top:18px;right:18px;font-size:26px;opacity:.2;pointer-events:none;transition:opacity .35s ease,transform .45s ease}
.zzrow.rev .zz-cat .zz-cat-petal{right:auto;left:18px}
.zz-cat:hover .zz-cat-petal{opacity:.55;transform:rotate(22deg) scale(1.25)}

.zz-cat-body{position:relative;z-index:2;padding:22px 22px 24px}
.zz-cat-growline{width:0;height:2px;margin-bottom:10px;background:linear-gradient(90deg,var(--blush),var(--rose));border-radius:2px;transition:width .45s cubic-bezier(.4,0,.2,1) .05s}
.zz-cat:hover .zz-cat-growline{width:44px}
.zz-cat-eyebrow{font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.13em;color:rgba(242,196,206,.5);margin-bottom:5px}
.zz-cat-name{font-family:'Playfair Display',Georgia,serif;font-size:clamp(1.1rem,1.8vw,1.5rem);font-weight:900;color:#fff;line-height:1.2;margin-bottom:8px;transition:color .25s ease}
.zz-cat:hover .zz-cat-name{color:var(--blush)}
.zz-cat-desc{
  font-size:11px;
  line-height:1.55;
  color:rgba(255,255,255,.42);

  display:-webkit-box;
  -webkit-line-clamp:2;
  -webkit-box-orient:vertical;

  line-clamp:2; /* tambahkan ini */

  overflow:hidden;
  margin-bottom:14px;

  max-height:0;
  opacity:0;
  transition:max-height .4s ease .05s,opacity .3s ease .05s
}
.zz-cat:hover .zz-cat-desc{max-height:50px;opacity:1}
.zz-cat-cta{display:inline-flex;align-items:center;gap:6px;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:.1em;color:#fff;background:linear-gradient(135deg,var(--dusty),var(--rose));border-radius:999px;padding:7px 18px;text-decoration:none;opacity:0;transform:translateY(7px);transition:opacity .3s ease .1s,transform .3s ease .1s}
.zz-cat:hover .zz-cat-cta{opacity:1;transform:translateY(0)}

/* Rail */
.zz-rail{background:var(--soft);border-radius:var(--rbr,6px 28px 28px 6px);position:relative;overflow:hidden;display:flex;flex-direction:column;padding:18px 0 12px}
.zzrow.rev .zz-rail{--rbr:28px 6px 6px 28px}
.zz-rail-lbl{padding:0 22px 10px;font-size:9px;font-weight:900;text-transform:uppercase;letter-spacing:.14em;color:var(--rose);display:flex;align-items:center;gap:8px}
.zz-rail-lbl::after{content:'';flex:1;height:1px;background:linear-gradient(90deg,rgba(212,137,154,.3),transparent)}
.zz-rail::before,.zz-rail::after{content:'';position:absolute;top:0;bottom:0;width:44px;z-index:4;pointer-events:none}
.zz-rail::before{left:0;background:linear-gradient(90deg,var(--soft),transparent)}
.zz-rail::after{right:0;background:linear-gradient(-90deg,var(--soft),transparent)}

.zz-scroll{display:flex;gap:12px;overflow-x:auto;overflow-y:hidden;padding:4px 22px 8px;scroll-behavior:smooth;scrollbar-width:none;flex:1}
.zz-scroll::-webkit-scrollbar{display:none}

.zz-progwrap{padding:0 22px 2px}
.zz-progtrack{height:3px;background:rgba(212,137,154,.15);border-radius:2px;overflow:hidden}
.zz-progbar{height:100%;background:linear-gradient(90deg,var(--dusty),var(--blush));border-radius:2px;width:15%;transition:width .15s ease}

.zz-nav{position:absolute;top:50%;transform:translateY(-50%);z-index:10;width:34px;height:34px;border-radius:50%;background:#fff;border:1.5px solid rgba(212,137,154,.25);color:var(--dusty);display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 4px 14px rgba(212,137,154,.2);transition:all .2s ease}
.zz-nav:hover{background:var(--rose);color:#fff;border-color:var(--rose)}
.zz-nav.l{left:8px}
.zz-nav.r{right:8px}
.zz-nav.hide{opacity:0;pointer-events:none}

/* Product card */
.zz-pcard{flex:0 0 165px;border-radius:18px;overflow:hidden;background:#fff;border:1px solid rgba(212,137,154,.12);text-decoration:none;display:block;transition:box-shadow .3s ease,transform .3s ease;position:relative}
.zz-pcard:hover{box-shadow:0 14px 40px rgba(212,137,154,.35);transform:translateY(-5px)}
.zz-pcard::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--dusty),var(--blush));opacity:0;transition:opacity .25s ease;z-index:3}
.zz-pcard:hover::before{opacity:1}
.zz-pimg-wrap{position:relative;aspect-ratio:3/4;overflow:hidden;background:var(--cream)}
.zz-pimg{width:100%;height:100%;object-fit:cover;transition:transform .55s cubic-bezier(.4,0,.2,1)}
.zz-pcard:hover .zz-pimg{transform:scale(1.07)}
.zz-pimg-ov{position:absolute;inset:0;background:linear-gradient(to top,rgba(44,26,30,.55) 0%,transparent 50%);opacity:0;transition:opacity .35s ease}
.zz-pcard:hover .zz-pimg-ov{opacity:1}
.zz-ppill{position:absolute;bottom:8px;left:8px;right:8px;z-index:2;background:rgba(253,249,244,.93);backdrop-filter:blur(8px);border-radius:10px;padding:5px 8px;font-size:11px;font-weight:800;color:var(--dusty);text-align:center;opacity:0;transform:translateY(4px);transition:opacity .3s ease .05s,transform .3s ease .05s}
.zz-pcard:hover .zz-ppill{opacity:1;transform:translateY(0)}
.zz-pcat{position:absolute;top:8px;left:8px;z-index:2;font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:var(--dusty);background:rgba(253,249,244,.93);padding:3px 8px;border-radius:999px;backdrop-filter:blur(6px)}
.zz-pbody{padding:10px 12px 12px}
.zz-pname{
  font-family:'Playfair Display',serif;
  font-size:12.5px;
  font-weight:700;
  color:var(--dark);
  line-height:1.35;
  margin-bottom:4px;

  display:-webkit-box;
  -webkit-line-clamp:2;
  -webkit-box-orient:vertical;

  line-clamp:2; /* tambahkan ini */

  overflow:hidden
}
.zz-pprice{font-size:11px;font-weight:800;color:var(--rose);margin-bottom:8px}
.zz-pwa{display:flex;align-items:center;justify-content:center;gap:5px;font-size:9.5px;font-weight:900;text-transform:uppercase;letter-spacing:.07em;color:#fff;background:linear-gradient(135deg,var(--dusty),var(--rose));border-radius:999px;padding:6px 10px;text-decoration:none;transition:opacity .2s ease}
.zz-pwa:hover{opacity:.85}

.zz-sep{display:flex;align-items:center;gap:14px;padding:6px 0}
.zz-sep-line{flex:1;height:1px;background:linear-gradient(90deg,transparent,rgba(212,137,154,.25),transparent)}
.zz-sep-icon{font-size:16px;opacity:.5}

/* ━━━ FAQ ━━━ */
.faq-new-card{position:relative;border-radius:1rem;padding:1.25rem 1.25rem 1.25rem 1.5rem;background:rgba(242,196,206,.06);border:1px solid rgba(212,137,154,.12);transition:border-color .25s ease,background .25s ease;cursor:pointer}
.faq-new-card::before{content:'';position:absolute;left:0;top:16px;bottom:16px;width:3px;border-radius:0 4px 4px 0;background:rgba(212,137,154,.2);transition:background .25s ease,top .25s ease,bottom .25s ease}
.faq-new-card.open::before{background:var(--rose);top:0;bottom:0}
.faq-new-card:hover{background:rgba(242,196,206,.1);border-color:rgba(212,137,154,.25)}
.faq-new-card.open{background:rgba(212,137,154,.07);border-color:rgba(212,137,154,.3)}
.faq-new-card.open .faq-new-icon{background:var(--rose);color:#fff}
.faq-new-body{max-height:0;overflow:hidden;opacity:0;transition:max-height .4s cubic-bezier(.4,0,.2,1),opacity .3s ease}
.faq-new-body.open{max-height:300px;opacity:1}
.faq-new-icon{width:28px;height:28px;border-radius:8px;background:rgba(212,137,154,.12);color:var(--rose);font-size:11px;font-weight:900;font-family:'Playfair Display',serif;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background .2s ease,color .2s ease}
.faq-new-chevron{transition:transform .3s cubic-bezier(.4,0,.2,1);flex-shrink:0}
.faq-new-card.open .faq-new-chevron{transform:rotate(180deg)}
.area-pill{transition:all .2s ease}
.area-pill:hover,.area-pill.active{background:rgba(212,137,154,.15)!important;border-color:rgba(212,137,154,.4)!important;color:var(--dusty)!important}
.sidebar-acc-content{max-height:0;overflow:hidden;transition:max-height .3s ease}
.sidebar-acc-content.open{max-height:600px}
.sidebar-acc-btn.open .acc-chevron{transform:rotate(180deg)}

/* ━━━ LOC CONTENT SEO ━━━ */
.loc-content h1 { font-family:'Playfair Display',Georgia,serif; font-size:1.9rem; font-weight:900; color:var(--dark); margin-bottom:1rem; margin-top:1.5rem; line-height:1.2; }
.loc-content h2 { font-family:'Playfair Display',Georgia,serif; font-size:1.45rem; font-weight:800; color:var(--dark); margin-bottom:0.75rem; margin-top:1.25rem; line-height:1.3; }
.loc-content h3 { font-family:'Playfair Display',Georgia,serif; font-size:1.15rem; font-weight:700; color:var(--dusty); margin-bottom:0.5rem; margin-top:1rem; }
.loc-content p  { margin-bottom:0.75rem; }
.loc-content ul { list-style:disc; padding-left:1.5rem; margin-bottom:0.75rem; }
.loc-content ol { list-style:decimal; padding-left:1.5rem; margin-bottom:0.75rem; }
.loc-content li { margin-bottom:0.25rem; color:var(--muted); }
.loc-content strong { color:var(--dark); font-weight:700; }
.loc-content em { color:var(--dusty); font-style:italic; }
.loc-content a  { color:var(--dusty); text-decoration:underline; transition:color .2s ease; }
.loc-content a:hover { color:var(--rose); }
</style>

<?php
function renderPetals(int $n, string $emojis='🌸🌺🌷🌼'): string {
    $out=''; $arr=mb_str_split($emojis);
    for($i=0;$i<$n;$i++){
        $e=$arr[$i%count($arr)];$top=rand(0,95);$left=rand(0,95);$dur=rand(6,14);$del=rand(0,8);
        $out.="<span class=\"float-petal\" style=\"top:{$top}%;left:{$left}%;--dur:{$dur}s;--del:{$del}s;\">{$e}</span>";
    }
    return $out;
}
?>


<!-- ════ HERO ════ -->
<section class="relative overflow-hidden" style="min-height:580px;padding-top:100px;background:var(--ivory);">
  <?= renderPetals(14) ?>
  <div class="absolute top-0 right-0 pointer-events-none" style="width:520px;height:520px;background:radial-gradient(circle,rgba(242,196,206,.45),transparent 65%);filter:blur(60px);"></div>
  <div class="absolute bottom-0 left-0 pointer-events-none" style="width:400px;height:400px;background:radial-gradient(circle,rgba(200,119,138,.15),transparent 65%);filter:blur(80px);"></div>
  <div class="absolute bottom-0 left-0 right-0 rose-line" style="z-index:5;"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-4 pt-4 mb-10 reveal reveal-1">
    <nav class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest">
      <a href="<?= BASE_URL ?>/" style="color:var(--muted);" class="hover:text-[var(--dusty)] transition">Beranda</a>
      <span style="color:rgba(44,26,30,.2);">—</span>
      <a href="<?= BASE_URL ?>/#area" style="color:var(--muted);" class="hover:text-[var(--dusty)] transition">Area Kirim</a>
      <span style="color:rgba(44,26,30,.2);">—</span>
      <span style="color:var(--dusty);"><?= e($location['name']) ?></span>
    </nav>
  </div>

  <div class="relative z-10 max-w-7xl mx-auto px-4 pb-20">
    <div class="grid md:grid-cols-2 gap-12 items-center">
      <div>
        <div class="reveal reveal-1 inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase mb-6" style="background:rgba(212,137,154,.12);border:1px solid rgba(212,137,154,.3);color:var(--dusty);">
          <span class="pin-dot w-2 h-2 rounded-full flex-shrink-0 inline-block" style="background:var(--rose);"></span>
          📍 <?= e($location['name']) ?>, Tangerang
        </div>
        <h1 class="reveal reveal-2 font-serif font-black leading-tight mb-5" style="font-size:clamp(2.2rem,5vw,3.6rem);color:var(--dark);">
          Toko Bunga<br>
          <span style="background:linear-gradient(135deg,var(--dusty),var(--rose),var(--blush));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;"><?= e($location['name']) ?></span>
        </h1>
        <p class="reveal reveal-3 text-base md:text-lg leading-relaxed mb-8 max-w-md" style="color:var(--muted);">
          <?= !empty($location['meta_description']) ? e($location['meta_description']) : 'Florist '.e($location['name']).' terpercaya — karangan bunga papan, hand bouquet, wedding, duka cita. Pengiriman cepat 2–4 jam ke seluruh '.e($location['name']).'.' ?>
        </p>
        <div class="reveal reveal-3 flex flex-wrap items-center gap-6 mb-8">
          <div><div class="stat-num text-3xl font-black">10+</div><div class="text-[10px] font-bold uppercase tracking-widest mt-0.5" style="color:var(--muted);">Tahun Berpengalaman</div></div>
          <div class="w-px h-10" style="background:rgba(212,137,154,.2);"></div>
          <div><div class="stat-num text-3xl font-black">2–4<span class="text-lg">Jam</span></div><div class="text-[10px] font-bold uppercase tracking-widest mt-0.5" style="color:var(--muted);">Pengiriman</div></div>
          <div class="w-px h-10" style="background:rgba(212,137,154,.2);"></div>
          <div><div class="stat-num text-2xl font-black"><?= 'Rp '.number_format($min_price/1000,0,',','.').'rb' ?></div><div class="text-[10px] font-bold uppercase tracking-widest mt-0.5" style="color:var(--muted);">Mulai dari</div></div>
        </div>
        <div class="reveal reveal-4 flex flex-wrap gap-3">
          <a href="<?= e($wa_url) ?>?text=<?= urlencode('Halo, saya ingin memesan bunga di '.$location['name'].', Tangerang.') ?>" target="_blank"
             class="inline-flex items-center gap-2.5 font-bold px-7 py-3.5 rounded-full no-underline transition hover:-translate-y-1"
             style="background:linear-gradient(135deg,var(--dusty),var(--rose));color:#fff;box-shadow:0 8px 24px rgba(200,119,138,.35);">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/></svg>
            Pesan Sekarang
          </a>
          <a href="tel:<?= e(setting('whatsapp_number')) ?>"
             class="inline-flex items-center gap-2 font-semibold px-7 py-3.5 rounded-full no-underline transition hover:bg-[rgba(212,137,154,.1)]"
             style="border:1.5px solid rgba(212,137,154,.3);color:var(--dark);">
            📞 <?= e(setting('phone_display')) ?>
          </a>
        </div>
      </div>
      <div class="reveal reveal-4 hidden md:block">
        <div class="rounded-3xl p-6 relative overflow-hidden" style="background:#fff;border:1px solid rgba(212,137,154,.2);box-shadow:0 16px 48px rgba(212,137,154,.2);">
          <div class="absolute top-0 right-0 w-28 h-28" style="background:linear-gradient(225deg,rgba(242,196,206,.4),transparent 65%);"></div>
          <p class="text-[11px] font-bold uppercase tracking-widest mb-4" style="color:var(--rose);">Info Pengiriman</p>
          <div class="space-y-3 mb-5">
            <div class="flex items-center gap-3 py-2.5 border-b" style="border-color:rgba(212,137,154,.1);"><span class="text-xl flex-shrink-0">📍</span><div><p class="text-[10px] uppercase tracking-wider" style="color:var(--muted);">Lokasi</p><p class="text-sm font-semibold" style="color:var(--dark);"><?= e($location['name']) ?>, Tangerang</p></div></div>
            <div class="flex items-center gap-3 py-2.5 border-b" style="border-color:rgba(212,137,154,.1);"><span class="text-xl flex-shrink-0">⚡</span><div><p class="text-[10px] uppercase tracking-wider" style="color:var(--muted);">Estimasi Pengiriman</p><p class="text-sm font-semibold" style="color:var(--dark);">2–4 Jam</p></div></div>
            <div class="flex items-center gap-3 py-2.5 border-b" style="border-color:rgba(212,137,154,.1);"><span class="text-xl flex-shrink-0">⏰</span><div><p class="text-[10px] uppercase tracking-wider" style="color:var(--muted);">Jam Operasional</p><p class="text-sm font-semibold" style="color:var(--dark);"><?= e(setting('jam_buka')) ?></p></div></div>
            <div class="flex items-center gap-3 py-2.5"><span class="text-xl flex-shrink-0">💐</span><div><p class="text-[10px] uppercase tracking-wider" style="color:var(--muted);">Harga Mulai</p><p class="text-sm font-black font-serif" style="color:var(--dusty);"><?= rupiah($min_price) ?></p></div></div>
          </div>
          <a href="<?= e($wa_url) ?>" target="_blank" class="flex items-center justify-center gap-2 font-bold py-3 rounded-2xl no-underline transition hover:opacity-90" style="background:linear-gradient(135deg,var(--dusty),var(--rose));color:#fff;">Chat WhatsApp</a>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ════ TICKER ════ -->
<div class="overflow-hidden py-3" style="background:linear-gradient(135deg,var(--dusty),var(--rose));">
  <div class="loc-ticker-inner">
    <?php for($r=0;$r<2;$r++): foreach($locations as $l): ?>
    <a href="<?= BASE_URL ?>/<?= e($l['slug']) ?>/"
       class="inline-flex items-center gap-3 mx-6 font-bold text-[11px] uppercase tracking-widest no-underline hover:opacity-70 transition flex-shrink-0"
       style="color:rgba(255,255,255,<?= $l['id']==$location['id']?'1':'.7' ?>);">
      <span style="opacity:.5;">📍</span><?= e($l['name']) ?>
    </a>
    <?php endforeach; endfor; ?>
  </div>
</div>


<!-- ════ LAYANAN MASONRY — PINTEREST STYLE (UPDATED) ════ -->
<section class="py-20 relative overflow-hidden" style="background:var(--cream);">
  <div class="absolute top-0 left-0 w-full h-px rose-line"></div>
  <?= renderPetals(10,'🌸🌷🌺🌼') ?>
  <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none" style="width:600px;height:400px;background:radial-gradient(ellipse,rgba(242,196,206,.35),transparent 65%);filter:blur(80px);"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-4">
    <div class="text-center mb-12">
      <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase mb-5" style="background:rgba(212,137,154,.1);border:1px solid rgba(212,137,154,.25);color:var(--dusty);">
        <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:var(--rose);"></span>Tersedia di <?= e($location['name']) ?>
      </div>
      <h2 class="font-serif font-black" style="font-size:clamp(1.8rem,4vw,2.6rem);color:var(--dark);">
        Layanan Bunga di<br>
        <span style="background:linear-gradient(135deg,var(--dusty),var(--rose));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Toko Bunga Tangerang <?= e($location['name']) ?></span>
      </h2>
      <p class="mt-3 max-w-lg mx-auto text-[15px]" style="color:var(--muted);">Semua kebutuhan bunga Anda tersedia dan siap dikirim ke <?= e($location['name']) ?></p>
    </div>

    <div class="pin-grid">
      <?php
      /* Pinterest-style random heights — creates organic flow */
      $pin_heights = [
        'h-sm'  => 200,
        'h-md'  => 265,
        'h-lg'  => 330,
        'h-xl'  => 395,
        'h-xxl' => 460,
      ];
      $pg_classes = ['pg-0','pg-1','pg-2','pg-3','pg-4','pg-5'];

      foreach ($all_cats as $i => $cat):
        $subs       = $all_cats_subs[$cat['id']] ?? [];
        $has_sub    = !empty($subs);
        $has_img    = !empty($cat['image']);
        $pg         = $pg_classes[$i % count($pg_classes)];
        $num        = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
        $cat_url    = BASE_URL . '/' . e($cat['slug']) . '/';
        $name_size = 'text-base';

        $tag   = $has_sub ? 'div' : 'a';
        $href  = $has_sub ? '' : 'href="' . $cat_url . '"';
        $extra = $has_sub ? 'role="button" tabindex="0"' : '';
      ?>
      <div class="pin-item">
        <<?= $tag ?> <?= $href ?> <?= $extra ?> class="pin-card">

          <!-- Background -->
          <?php if ($has_img): ?>
          <div class="pin-bg" style="background-image:url('<?= e(imgUrl($cat['image'], 'category')) ?>');"></div>
          <?php else: ?>
          <div class="pin-bg-grad <?= $pg ?>"></div>
          <?php if (!empty($cat['icon'])): ?>
          <div class="pin-icon"><?= e($cat['icon']) ?></div>
          <?php endif; ?>
          <?php endif; ?>

          <!-- Overlays -->
          <div class="pin-ov-base"></div>
          <div class="pin-ov-shimmer"></div>
          <div class="pin-sparkle"></div>
          <div class="pin-bignum"><?= $num ?></div>

          <!-- Top badges -->
          <div class="pin-eyebrow">
            <?php if ($has_sub): ?>
            <span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;opacity:.6;flex-shrink:0;"></span>
            <?= count($subs) ?> layanan
            <?php else: ?>
            <svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            Lihat
            <?php endif; ?>
          </div>
          <?php if ($has_sub): ?>
          <div class="pin-sub-badge"><?= count($subs) ?> sub</div>
          <?php endif; ?>

          <!-- Footer content -->
          <div class="pin-footer">
            <div class="pin-accent-line"></div>
            <div class="pin-name <?= $name_size ?>"><?= e($cat['name']) ?></div>

            <?php if ($has_sub): ?>
            <!-- Sub-categories reveal -->
            <div class="pin-subs">
              <a href="<?= $cat_url ?>" class="pin-sub-item" style="color:rgba(242,196,206,.55);font-weight:800;" onclick="event.stopPropagation()">
                Semua <?= e($cat['name']) ?> →
              </a>
              <?php foreach (array_slice($subs, 0, 5) as $sub): ?>
              <a href="<?= BASE_URL ?>/<?= e($sub['slug']) ?>/" class="pin-sub-item" onclick="event.stopPropagation()">
                <?= e($sub['name']) ?>
              </a>
              <?php endforeach; ?>
              <?php if (count($subs) > 5): ?>
              <span class="pin-sub-item" style="color:rgba(212,137,154,.35);font-style:italic;">
                +<?= count($subs) - 5 ?> lainnya
              </span>
              <?php endif; ?>
            </div>
            <?php elseif (!empty($cat['description'])): ?>
            <!-- Short desc + CTA for leaf categories -->
            <div class="pin-subs">
              <p style="
  font-size:11.5px;
  line-height:1.5;
  color:rgba(255,255,255,.45);
  margin-bottom:10px;

  display:-webkit-box;
  -webkit-line-clamp:2;
  -webkit-box-orient:vertical;

  line-clamp:2;

  overflow:hidden;
">
                <?= e($cat['description']) ?>
              </p>
              <a href="<?= $cat_url ?>" class="pin-cta" onclick="event.stopPropagation()">
                <svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                Lihat Produk
              </a>
            </div>
            <?php else: ?>
            <div class="pin-subs">
              <a href="<?= $cat_url ?>" class="pin-cta" onclick="event.stopPropagation()">
                <svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                Lihat Produk
              </a>
            </div>
            <?php endif; ?>
          </div>

        </<?= $tag ?>>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ════ PRODUK ZIGZAG — PINTEREST STYLE ════ -->
<section id="produk" class="py-20 relative overflow-hidden" style="background:var(--ivory);">
  <div class="absolute top-0 left-0 w-full rose-line"></div>
  <?= renderPetals(8,'🌸🌷🌺') ?>
  <div style="position:absolute;top:5%;right:-80px;width:420px;height:420px;border-radius:50%;background:radial-gradient(circle,rgba(242,196,206,.28),transparent 65%);filter:blur(80px);pointer-events:none;"></div>
  <div style="position:absolute;bottom:5%;left:-60px;width:360px;height:360px;border-radius:50%;background:radial-gradient(circle,rgba(200,119,138,.14),transparent 65%);filter:blur(80px);pointer-events:none;"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-4">
    <div class="text-center mb-16">
      <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase mb-5" style="background:rgba(212,137,154,.1);border:1px solid rgba(212,137,154,.25);color:var(--dusty);">
        <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:var(--rose);"></span>Semua Produk
      </div>
      <h2 class="font-serif font-black" style="font-size:clamp(1.8rem,4vw,2.6rem);color:var(--dark);">
        Koleksi Bunga
        <em style="font-style:italic;background:linear-gradient(135deg,var(--dusty),var(--rose),var(--blush));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;"><?= e($location['name']) ?></em>
      </h2>
      <p class="mt-3 max-w-md mx-auto text-[15px]" style="color:var(--muted);">Tersusun per kategori — geser kanan kiri untuk melihat lebih banyak pilihan</p>
    </div>

    <?php
    $ptl=['🌸','🌺','🌷','🌼'];
    $gfl=['linear-gradient(135deg,#f7e0e5,#f2c4ce)','linear-gradient(135deg,#fde8ef,#edb5c0)','linear-gradient(135deg,#fdf0f2,#e8a8b8)','linear-gradient(135deg,#f5d5dc,#d4899a)'];
    foreach($cats_with_products as $ri=>$row):
      $cat=$row['cat'];$prods=$row['products'];
      $isRev=($ri%2!==0);$sid='zzs-'.$cat['id'];
      $catUrl=BASE_URL.'/'.e($cat['slug']).'/';
      $num=str_pad($ri+1,2,'0',STR_PAD_LEFT);
      $petal=$ptl[$ri%4];$gfall=$gfl[$ri%4];
    ?>

    <div class="zzrow <?= $isRev?'rev':'' ?>">

      <!-- Category card -->
      <a href="<?= $catUrl ?>" class="zz-cat">
        <?php if(!empty($cat['image'])): ?>
        <div class="zz-cat-bg" style="background-image:url('<?= e(imgUrl($cat['image'],'category')) ?>');"></div>
        <?php else: ?>
        <div class="zz-cat-bg" style="background:<?= $gfall ?>;"></div>
        <?php endif; ?>
        <div class="zz-cat-ov"></div>
        <div class="zz-cat-stripe"></div>
        <div class="zz-cat-num"><?= $num ?></div>
        <div class="zz-cat-chip">
          <span style="width:5px;height:5px;border-radius:50%;background:var(--rose);display:inline-block;flex-shrink:0;"></span>
          <?= count($prods) ?> Produk
        </div>
        <div class="zz-cat-petal"><?= $petal ?></div>
        <div class="zz-cat-body">
          <div class="zz-cat-growline"></div>
          <div class="zz-cat-eyebrow">Kategori <?= $num ?></div>
          <div class="zz-cat-name"><?= e($cat['name']) ?></div>
          <?php if(!empty($cat['description'])): ?><div class="zz-cat-desc"><?= e($cat['description']) ?></div><?php endif; ?>
          <span class="zz-cat-cta">
            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            Lihat Semua
          </span>
        </div>
      </a>

      <!-- Rail + products -->
      <div class="zz-rail">
        <div class="zz-rail-lbl"><?= e($cat['name']) ?> &nbsp;·&nbsp; <?= count($prods) ?> produk</div>
        <button class="zz-nav l hide" onclick="zzNav('<?= $sid ?>',this,-1)">
          <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button class="zz-nav r" onclick="zzNav('<?= $sid ?>',this,1)">
          <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
        </button>
        <div id="<?= $sid ?>" class="zz-scroll" onscroll="zzScrollEvt(this,'<?= $sid ?>-bar')">
          <?php foreach($prods as $prod):
            $img=imgUrl($prod['image'],'product');
            $wamsg=urlencode("Halo, saya tertarik memesan *{$prod['name']}* untuk dikirim ke {$location['name']}. Apakah tersedia?");
          ?>
          <a href="<?= e($wa_url) ?>?text=<?= $wamsg ?>" target="_blank" class="zz-pcard">
            <div class="zz-pimg-wrap">
              <img src="<?= e($img) ?>" alt="<?= e($prod['name']) ?>" class="zz-pimg" loading="lazy">
              <div class="zz-pimg-ov"></div>
              <span class="zz-pcat"><?= e($cat['name']) ?></span>
              <div class="zz-ppill"><?= rupiah($prod['price']) ?></div>
            </div>
            <div class="zz-pbody">
              <div class="zz-pname"><?= e($prod['name']) ?></div>
              <div class="zz-pprice"><?= rupiah($prod['price']) ?></div>
              <div class="zz-pwa">
                <svg width="11" height="11" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/></svg>
                Pesan WA
              </div>
            </div>
          </a>
          <?php endforeach; ?>
        </div>
        <div class="zz-progwrap"><div class="zz-progtrack"><div id="<?= $sid ?>-bar" class="zz-progbar"></div></div></div>
      </div>

    </div><!-- /zzrow -->

    <?php if($ri < count($cats_with_products)-1): ?>
    <div class="zz-sep"><div class="zz-sep-line"></div><span class="zz-sep-icon"><?= $petal ?></span><div class="zz-sep-line"></div></div>
    <?php endif; ?>

    <?php endforeach; ?>
  </div>
</section>


<!-- ════ SEO + FAQ + SIDEBAR ════ -->
<section class="py-20 relative overflow-hidden" style="background:var(--cream);">
  <div class="absolute top-0 left-0 w-full h-px rose-line"></div>
  <?= renderPetals(8,'🌷🌸🌺🌼') ?>

  <div class="relative z-10 max-w-7xl mx-auto px-4">
    <div class="grid md:grid-cols-2 gap-16 items-start">

      <div class="space-y-6">
        <div>
          <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase mb-6" style="background:rgba(212,137,154,.1);border:1px solid rgba(212,137,154,.25);color:var(--dusty);">
            <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:var(--rose);"></span>Tentang Kami
          </div>
          <h2 class="font-serif font-black leading-tight mb-5" style="font-size:clamp(1.5rem,3vw,2rem);color:var(--dark);">
            Toko Bunga <?= e($location['name']) ?><br>
            <span style="background:linear-gradient(135deg,var(--dusty),var(--rose));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Terpercaya & Berpengalaman</span>
          </h2>
          <?php if(!empty($location['content'])): ?><div class="leading-relaxed text-[15px] mb-5 loc-content" style="color:var(--muted);"><?= $location['content'] ?></div><?php endif; ?>
          <p class="text-[15px] leading-relaxed mb-6" style="color:var(--muted);">Sebagai <strong style="color:var(--dark);">toko bunga <?= e(strtolower($location['name'])) ?></strong> yang telah melayani pelanggan lebih dari 10 tahun, kami memahami setiap momen memerlukan rangkaian bunga yang tepat. Tim florist profesional siap membantu 24 jam.</p>
          <a href="<?= e($wa_url) ?>?text=<?= urlencode('Halo, saya ingin memesan bunga di '.$location['name'].'. Mohon info harga dan ketersediaannya.') ?>" target="_blank"
             class="inline-flex items-center gap-2.5 font-bold px-7 py-3.5 rounded-full no-underline transition hover:-translate-y-1"
             style="background:linear-gradient(135deg,var(--dusty),var(--rose));color:#fff;box-shadow:0 8px 24px rgba(200,119,138,.3);">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/></svg>
            Pesan via WhatsApp
          </a>
        </div>

     <div class="rounded-2xl p-5" style="background:#fff;border:1px solid rgba(212,137,154,.15);box-shadow:0 4px 20px rgba(212,137,154,.1);">
  <div class="flex items-center gap-2 mb-4">
    <span style="color:var(--rose);">📍</span>
    <h3 class="font-serif font-black" style="color:var(--dark);">Area Lainnya</h3>
  </div>

  <!-- Halaman-halaman area -->
  <?php for ($p = 0; $p < $slider_pages; $p++): ?>
  <div id="blushAreaPage<?= $p ?>"
       style="display:<?= $p === $slider_active_page ? 'grid' : 'none' ?>;
              grid-template-columns: repeat(2, 1fr);
              gap: 6px; min-height: 60px;">
    <?php
    $slice = array_slice($locations, $p * $slider_per_page, $slider_per_page);
    foreach ($slice as $l):
      $is_active = $l['id'] == $location['id'];
    ?>
    <a href="<?= BASE_URL ?>/<?= e($l['slug']) ?>/"
       class="area-pill inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[12px] font-semibold no-underline transition overflow-hidden"
       style="background:<?= $is_active ? 'rgba(212,137,154,.15)' : 'rgba(212,137,154,.08)' ?>;
              border:1px solid <?= $is_active ? 'rgba(212,137,154,.4)' : 'rgba(212,137,154,.15)' ?>;
              color:<?= $is_active ? 'var(--dusty)' : 'var(--muted)' ?>;
              min-width:0;">
      <span class="w-1 h-1 rounded-full inline-block flex-shrink-0"
            style="background:<?= $is_active ? 'var(--rose)' : 'rgba(44,26,30,.2)' ?>;"></span>
      <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;min-width:0;">
        <?= e($l['name']) ?>
      </span>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endfor; ?>

  <!-- Navigasi -->
  <?php if ($slider_pages > 1): ?>
  <div style="display:flex;align-items:center;justify-content:space-between;margin-top:12px;padding-top:10px;border-top:1px solid rgba(212,137,154,.12);">
    <button id="blushAreaPrev" onclick="blushAreaSlider(-1)"
            style="font-size:11px;padding:4px 11px;border-radius:8px;
                   border:1px solid rgba(212,137,154,.2);
                   background:#fff;color:var(--muted);cursor:pointer;">
      ‹ Prev
    </button>

    <div style="display:flex;gap:4px;align-items:center;">
      <?php for ($p = 0; $p < $slider_pages; $p++): ?>
      <span id="blushAreaDot<?= $p ?>" onclick="blushAreaGoPage(<?= $p ?>)"
            style="display:inline-block;height:5px;border-radius:3px;cursor:pointer;transition:all .2s;
                   width:<?= $p === $slider_active_page ? '16px' : '5px' ?>;
                   background:<?= $p === $slider_active_page ? 'var(--rose)' : 'rgba(212,137,154,.25)' ?>;"></span>
      <?php endfor; ?>
    </div>

    <button id="blushAreaNext" onclick="blushAreaSlider(1)"
            style="font-size:11px;padding:4px 11px;border-radius:8px;
                   border:1px solid rgba(212,137,154,.2);
                   background:#fff;color:var(--muted);cursor:pointer;">
      Next ›
    </button>
  </div>
  <p id="blushAreaInfo" style="text-align:center;font-size:11px;color:rgba(44,26,30,.3);margin-top:5px;"></p>
  <?php endif; ?>
</div>

        <div class="rounded-2xl p-6 text-center" style="background:linear-gradient(135deg,rgba(212,137,154,.12),rgba(242,196,206,.08));border:1px solid rgba(212,137,154,.2);">
          <div class="text-4xl mb-3">💬</div>
          <p class="font-serif font-bold text-lg mb-1" style="color:var(--dark);">Siap Pesan?</p>
          <p class="text-sm mb-5" style="color:var(--muted);">Respon dalam hitungan menit, 24 jam</p>
          <a href="<?= e($wa_url) ?>" target="_blank" class="inline-flex items-center justify-center gap-2 font-bold px-6 py-3 rounded-full w-full no-underline transition hover:opacity-90" style="background:linear-gradient(135deg,var(--dusty),var(--rose));color:#fff;">Chat WhatsApp Sekarang</a>
        </div>
      </div>

      <div class="space-y-6">
        <?php if(!empty($faqs)): ?>
        <div class="rounded-2xl overflow-hidden" style="background:#fff;border:1px solid rgba(212,137,154,.15);box-shadow:0 4px 24px rgba(212,137,154,.1);">
          <div class="px-6 pt-6 pb-4 flex items-center justify-between">
            <div>
              <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color:rgba(212,137,154,.6);">Pertanyaan Umum</p>
              <h3 class="font-serif font-black text-xl leading-tight" style="color:var(--dark);">FAQ — <span style="background:linear-gradient(135deg,var(--dusty),var(--rose));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;"><?= e($location['name']) ?></span></h3>
            </div>
            <span class="text-3xl opacity-40">❓</span>
          </div>
          <div class="h-px mx-6 mb-5" style="background:linear-gradient(90deg,rgba(212,137,154,.35),transparent);"></div>
          <div class="px-5 pb-5 space-y-2.5">
            <?php foreach($faqs as $i=>$faq): ?>
            <div class="faq-new-card <?= $i===0?'open':'' ?>" onclick="toggleFaqNew(this)">
              <div class="flex items-start gap-3">
                <div class="faq-new-icon"><?= str_pad($i+1,2,'0',STR_PAD_LEFT) ?></div>
                <div class="flex-1 min-w-0">
                  <div class="flex items-start justify-between gap-2">
                    <p class="text-[13px] font-semibold leading-snug pr-1" style="color:var(--dark);"><?= e($faq['question']) ?></p>
                    <svg class="faq-new-chevron w-4 h-4 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                  </div>
                  <div class="faq-new-body <?= $i===0?'open':'' ?>">
                    <p class="text-[12.5px] leading-relaxed mt-3 pt-3 border-t" style="color:var(--muted);border-color:rgba(212,137,154,.12);"><?= e($faq['answer']) ?></p>
                  </div>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <div class="rounded-2xl p-5" style="background:#fff;border:1px solid rgba(212,137,154,.15);box-shadow:0 4px 20px rgba(212,137,154,.08);">
          <h3 class="font-serif font-black mb-4" style="color:var(--dark);">Layanan Kami</h3>
          <div class="space-y-1">
            <?php foreach($all_cats as $c):
              $c_subs=$all_cats_subs[$c['id']]??[];$has_sub=!empty($c_subs);
            ?>
            <?php if($has_sub): ?>
            <div>
              <button onclick="toggleSidebarAcc(this)" class="sidebar-acc-btn w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition" style="background:transparent;border:none;cursor:pointer;">
                <span class="text-[13px] font-medium text-left" style="color:var(--muted);"><?= e($c['name']) ?></span>
                <svg class="acc-chevron w-3.5 h-3.5 transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:rgba(44,26,30,.2);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
              </button>
              <div class="sidebar-acc-content pl-3 ml-4 mt-1" style="border-left:1.5px solid rgba(212,137,154,.2);">
                <a href="<?= BASE_URL ?>/<?= e($c['slug']) ?>/" class="block px-3 py-1.5 text-[11px] font-bold no-underline transition" style="color:rgba(200,119,138,.6);">Lihat semua <?= e($c['name']) ?> →</a>
                <?php foreach($c_subs as $sub): ?>
                <a href="<?= BASE_URL ?>/<?= e($sub['slug']) ?>/" class="flex items-center gap-2 px-3 py-2 rounded-lg text-[12px] no-underline transition" style="color:var(--muted);">
                  <span class="w-1 h-1 rounded-full flex-shrink-0 inline-block" style="background:rgba(212,137,154,.3);"></span><?= e($sub['name']) ?>
                </a>
                <?php endforeach; ?>
              </div>
            </div>
            <?php else: ?>
            <a href="<?= BASE_URL ?>/<?= e($c['slug']) ?>/" class="flex items-center justify-between px-3 py-2.5 rounded-xl no-underline transition" style="color:var(--muted);">
              <span class="text-[13px] font-medium"><?= e($c['name']) ?></span>
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:rgba(44,26,30,.2);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>

<script>
function toggleFaqNew(card) {
  const body=card.querySelector('.faq-new-body'),isOpen=card.classList.contains('open');
  document.querySelectorAll('.faq-new-card.open').forEach(c=>{c.classList.remove('open');c.querySelector('.faq-new-body').classList.remove('open')});
  if(!isOpen){card.classList.add('open');body.classList.add('open')}
}
function toggleSidebarAcc(btn) {
  const content=btn.nextElementSibling,isOpen=content.classList.contains('open');
  document.querySelectorAll('.sidebar-acc-content.open').forEach(el=>el.classList.remove('open'));
  document.querySelectorAll('.sidebar-acc-btn.open').forEach(el=>el.classList.remove('open'));
  if(!isOpen){btn.classList.add('open');content.classList.add('open')}
}
function zzNav(sid,btn,dir) {
  const el=document.getElementById(sid);if(!el)return;
  el.scrollBy({left:dir*600,behavior:'smooth'});
  setTimeout(()=>zzScrollEvt(el,sid+'-bar'),350);
}
function zzScrollEvt(el,barId) {
  const bar=document.getElementById(barId),maxS=el.scrollWidth-el.clientWidth;
  const pct=maxS>0?(el.scrollLeft/maxS)*72+12:12;
  if(bar)bar.style.width=pct+'%';
  const rail=el.closest('.zz-rail');
  const navL=rail.querySelector('.zz-nav.l'),navR=rail.querySelector('.zz-nav.r');
  if(navL)navL.classList.toggle('hide',el.scrollLeft<20);
  if(navR)navR.classList.toggle('hide',el.scrollLeft>=maxS-20);
}
document.addEventListener('DOMContentLoaded',()=>{
  document.querySelectorAll('.zz-scroll').forEach(el=>zzScrollEvt(el,el.id+'-bar'));
});
/* ── Area slider ── */
(function() {
  var perPage = <?= $slider_per_page ?>;
  var total   = <?= $slider_total ?>;
  var pages   = <?= $slider_pages ?>;
  var cur     = <?= $slider_active_page ?>;

  function update() {
    for (var i = 0; i < pages; i++) {
      var el = document.getElementById('blushAreaPage' + i);
      if (el) el.style.display = (i === cur) ? 'grid' : 'none';
    }
    for (var i = 0; i < pages; i++) {
      var dot = document.getElementById('blushAreaDot' + i);
      if (!dot) continue;
      dot.style.width      = (i === cur) ? '16px' : '5px';
      dot.style.background = (i === cur) ? 'var(--rose)' : 'rgba(212,137,154,.25)';
    }
    var prev = document.getElementById('blushAreaPrev');
    var next = document.getElementById('blushAreaNext');
    if (prev) {
      prev.disabled      = (cur === 0);
      prev.style.opacity = (cur === 0) ? '0.35' : '1';
      prev.style.cursor  = (cur === 0) ? 'not-allowed' : 'pointer';
      prev.onmouseenter  = function() { if (!prev.disabled) { prev.style.background='rgba(212,137,154,.1)'; prev.style.borderColor='rgba(212,137,154,.4)'; prev.style.color='var(--dusty)'; }};
      prev.onmouseleave  = function() { prev.style.background='#fff'; prev.style.borderColor='rgba(212,137,154,.2)'; prev.style.color='var(--muted)'; };
    }
    if (next) {
      next.disabled      = (cur === pages - 1);
      next.style.opacity = (cur === pages - 1) ? '0.35' : '1';
      next.style.cursor  = (cur === pages - 1) ? 'not-allowed' : 'pointer';
      next.onmouseenter  = function() { if (!next.disabled) { next.style.background='rgba(212,137,154,.1)'; next.style.borderColor='rgba(212,137,154,.4)'; next.style.color='var(--dusty)'; }};
      next.onmouseleave  = function() { next.style.background='#fff'; next.style.borderColor='rgba(212,137,154,.2)'; next.style.color='var(--muted)'; };
    }
    var info = document.getElementById('blushAreaInfo');
    if (info) {
      var start = cur * perPage + 1;
      var end   = Math.min((cur + 1) * perPage, total);
      info.textContent = start + '–' + end + ' dari ' + total + ' area';
    }
  }

  window.blushAreaSlider  = function(dir) { cur = Math.max(0, Math.min(pages - 1, cur + dir)); update(); };
  window.blushAreaGoPage  = function(p)   { cur = p; update(); };

  update();
})();
</script>