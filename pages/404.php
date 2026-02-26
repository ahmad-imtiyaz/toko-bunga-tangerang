<?php
require_once __DIR__ . '/../includes/config.php';
$meta_title = 'Halaman Tidak Ditemukan — Toko Bunga Tangerang';
$meta_desc  = 'Halaman yang Anda cari tidak ditemukan.';
require __DIR__ . '/../includes/header.php';
?>

<style>
:root {
  --blush: #F2C4CE; --rose: #D4899A; --dusty: #C8778A;
  --cream: #FAF5EE; --ivory: #FDF9F4;
  --dark:  #2C1A1E; --muted: rgba(44,26,30,.45);
}

@keyframes shimmer-x {
  0%{background-position:-200% center} 100%{background-position:200% center}
}
.rose-line {
  height:1px;
  background:linear-gradient(90deg,transparent,var(--rose),var(--blush),var(--rose),transparent);
  background-size:200% auto;
  animation:shimmer-x 3s linear infinite;
}

@keyframes floatPetal {
  0%,100%{transform:translateY(0) rotate(0deg);opacity:.3}
  33%{transform:translateY(-28px) rotate(12deg);opacity:.5}
  66%{transform:translateY(-12px) rotate(-8deg);opacity:.35}
}
.float-petal {
  position:absolute; pointer-events:none; user-select:none;
  font-size:18px;
  animation:floatPetal var(--dur,7s) ease-in-out var(--del,0s) infinite;
  opacity:.3;
}

@keyframes fadeUp {
  from{opacity:0;transform:translateY(28px)}
  to{opacity:1;transform:translateY(0)}
}
.reveal   { animation:fadeUp .65s ease both; }
.reveal-1 { animation-delay:.1s; }
.reveal-2 { animation-delay:.22s; }
.reveal-3 { animation-delay:.36s; }
.reveal-4 { animation-delay:.5s; }

@keyframes floatBig {
  0%,100%{transform:translateY(0) rotate(-4deg) scale(1);}
  50%{transform:translateY(-18px) rotate(4deg) scale(1.06);}
}
.big-petal { animation:floatBig 5s ease-in-out infinite; display:inline-block; }

@keyframes pulse-ring {
  0%,100%{box-shadow:0 0 0 0 rgba(212,137,154,.45)}
  50%{box-shadow:0 0 0 14px rgba(212,137,154,0)}
}
.pulse-btn { animation:pulse-ring 2.4s ease infinite; }
</style>

<section class="relative overflow-hidden min-h-[80vh] flex items-center justify-center py-24"
         style="background:var(--ivory);">

  <!-- Ambient blobs -->
  <div class="absolute top-0 right-0 pointer-events-none"
       style="width:480px;height:480px;background:radial-gradient(circle,rgba(242,196,206,.5),transparent 65%);filter:blur(70px);"></div>
  <div class="absolute bottom-0 left-0 pointer-events-none"
       style="width:380px;height:380px;background:radial-gradient(circle,rgba(200,119,138,.18),transparent 65%);filter:blur(90px);"></div>
  <div class="absolute top-0 left-0 w-full rose-line" style="z-index:5;"></div>
  <div class="absolute bottom-0 left-0 w-full rose-line" style="z-index:5;"></div>

  <!-- Floating petals -->
  <span class="float-petal" style="top:8%;left:6%;--dur:8s;--del:0s;">🌸</span>
  <span class="float-petal" style="top:15%;right:8%;--dur:10s;--del:1.5s;">🌷</span>
  <span class="float-petal" style="top:70%;left:4%;--dur:7s;--del:0.8s;">🌺</span>
  <span class="float-petal" style="top:80%;right:6%;--dur:9s;--del:2s;">🌼</span>
  <span class="float-petal" style="top:40%;left:2%;--dur:11s;--del:3s;">🌸</span>
  <span class="float-petal" style="top:55%;right:3%;--dur:8.5s;--del:1s;">🌷</span>
  <span class="float-petal" style="top:25%;left:18%;--dur:12s;--del:4s;">🌺</span>
  <span class="float-petal" style="top:85%;right:20%;--dur:9.5s;--del:0.5s;">🌸</span>

  <div class="relative z-10 text-center px-4 max-w-lg mx-auto">

    <!-- Big icon -->
    <div class="reveal reveal-1 mb-6">
      <span class="big-petal" style="font-size:80px;filter:drop-shadow(0 8px 24px rgba(212,137,154,.5));">🌸</span>
    </div>

    <!-- 404 number -->
    <div class="reveal reveal-1 relative inline-block mb-2">
      <span class="font-serif font-black select-none"
            style="font-size:clamp(5rem,20vw,8rem);line-height:1;
                   background:linear-gradient(135deg,var(--dusty),var(--rose),var(--blush));
                   -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
        404
      </span>
      <!-- decorative watermark -->
      <span class="absolute inset-0 font-serif font-black pointer-events-none select-none flex items-center justify-center"
            style="font-size:clamp(5rem,20vw,8rem);line-height:1;
                   color:rgba(212,137,154,.07);z-index:-1;transform:translate(4px,4px);">
        404
      </span>
    </div>

    <!-- Shimmer divider -->
    <div class="reveal reveal-2 mx-auto mb-6"
         style="height:2px;width:80px;
                background:linear-gradient(90deg,transparent,var(--rose),var(--blush),var(--rose),transparent);
                background-size:200% auto;
                animation:shimmer-x 3s linear infinite;
                border-radius:2px;">
    </div>

    <!-- Heading -->
    <h1 class="reveal reveal-2 font-serif font-black leading-tight mb-3"
        style="font-size:clamp(1.5rem,4vw,2.2rem);color:var(--dark);">
      Halaman Tidak Ditemukan
    </h1>

    <!-- Subtext -->
    <p class="reveal reveal-3 text-base leading-relaxed mb-10 mx-auto max-w-sm"
       style="color:var(--muted);">
      Sepertinya bunga yang Anda cari sudah terbang pergi. Yuk kembali ke halaman utama atau hubungi kami langsung!
    </p>

    <!-- CTA buttons -->
    <div class="reveal reveal-4 flex flex-col sm:flex-row gap-3 justify-center items-center">
      <a href="<?= BASE_URL ?>/"
         class="pulse-btn inline-flex items-center gap-2.5 font-bold px-8 py-3.5 rounded-full no-underline transition hover:-translate-y-1"
         style="background:linear-gradient(135deg,var(--dusty),var(--rose));
                color:#fff;
                box-shadow:0 8px 24px rgba(200,119,138,.35);">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Beranda
      </a>
      <a href="<?= e(setting('whatsapp_url')) ?>" target="_blank"
         class="inline-flex items-center gap-2.5 font-bold px-8 py-3.5 rounded-full no-underline transition hover:-translate-y-1 hover:bg-[rgba(212,137,154,.1)]"
         style="border:1.5px solid rgba(212,137,154,.35);color:var(--dark);background:#fff;">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
          <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
        </svg>
        Hubungi Kami
      </a>
    </div>

  </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>