<?php
/* ================================================================
   KEUNGGULAN SECTION — Instagram Grid Tengah + Teks Kiri Kanan
   4 foto grid 2x2 mengapit teks, stats bar bawah
   Tema: Elegan & Mewah | ivory/rose/blush/cream
================================================================ */
?>

<style>
#tentang {
  --blush: #F2C4CE; --rose: #D4899A; --dusty: #C8778A;
  --cream: #FAF5EE; --ivory: #FDF9F4;
  --muted: #8C6B72; --dark: #2C1A1E; --soft: #F7EEF0;
}
#tentang {
  background: var(--ivory);
  overflow: hidden;
  position: relative;
}
/* Blob dekoratif */
#tentang::before, #tentang::after {
  content: ''; position: absolute; border-radius: 50%; pointer-events: none;
  background: radial-gradient(circle, rgba(242,196,206,.2) 0%, transparent 70%);
  filter: blur(50px);
}
#tentang::before { width:500px; height:500px; top:-100px; left:-100px; }
#tentang::after  { width:400px; height:400px; bottom:-80px; right:-80px; }

/* ════════════════════
   HEADER
════════════════════ */
.ku-header { text-align:center; padding:80px 16px 0; }
.ku-overline {
  display:inline-flex; align-items:center; gap:8px;
  font:600 11px/1 'Jost',sans-serif; letter-spacing:.22em;
  text-transform:uppercase; color:var(--dusty); margin-bottom:16px;
}
.ku-overline-dot { width:5px; height:5px; border-radius:50%; background:var(--rose); }
.ku-title {
  font:300 clamp(2rem,4vw,3.2rem)/1.1 'Cormorant Garamond',serif;
  color:var(--dark);
}
.ku-title em { font-style:italic; color:var(--dusty); }
.ku-divider { display:flex; align-items:center; gap:14px; margin:20px auto 0; max-width:360px; }
.ku-divider-line { height:1px; flex:1; background:linear-gradient(to right,transparent,rgba(212,137,154,.35),transparent); }
.ku-divider-orn  { color:var(--blush); font-size:11px; letter-spacing:.25em; }

/* ════════════════════
   LAYOUT UTAMA
   [teks kiri] [grid foto] [teks kanan]
════════════════════ */
.ku-body {
  display: grid;
  grid-template-columns: 1fr 420px 1fr;
  gap: 40px;
  align-items: center;
  max-width: 1200px;
  margin: 60px auto 0;
  padding: 0 24px;
  position: relative; z-index: 1;
}

/* ── Teks kiri & kanan ── */
.ku-side {
  display: flex;
  flex-direction: column;
  gap: 28px;
}
.ku-side-right { align-items: flex-start; }

.ku-point {
  display: flex;
  gap: 14px;
  align-items: flex-start;
}
.ku-point-icon {
  width: 44px; height: 44px; border-radius: 14px; flex-shrink: 0;
  background: linear-gradient(135deg, rgba(242,196,206,.5), rgba(212,137,154,.2));
  border: 1px solid rgba(212,137,154,.2);
  display: flex; align-items: center; justify-content: center;
  font-size: 18px;
  transition: transform .3s ease, background .3s ease;
}
.ku-point:hover .ku-point-icon {
  transform: scale(1.1) rotate(-4deg);
  background: linear-gradient(135deg, var(--blush), rgba(212,137,154,.4));
}
.ku-point-body { padding-top: 2px; }
.ku-point-title {
  font: 700 14px/1.3 'Jost',sans-serif;
  color: var(--dark); margin-bottom: 5px;
}
.ku-point-desc {
  font: 400 12.5px/1.7 'Jost',sans-serif;
  color: var(--muted);
}

/* Aksen garis kiri pada teks kiri */
.ku-side-left .ku-point {
  padding-left: 16px;
  border-left: 2px solid transparent;
  transition: border-color .3s;
}
.ku-side-left .ku-point:hover { border-color: var(--blush); }

/* Aksen garis kanan pada teks kanan */
.ku-side-right .ku-point {
  padding-right: 16px;
  border-right: 2px solid transparent;
  transition: border-color .3s;
  flex-direction: row-reverse;
}
.ku-side-right .ku-point:hover { border-color: var(--blush); }
.ku-side-right .ku-point-body { text-align: right; }

/* ════════════════════
   GRID FOTO 2x2
════════════════════ */
.ku-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 8px;
  position: relative;
}

/* Badge center mengambang di persilangan grid */
.ku-grid-badge {
  position: absolute;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  z-index: 10;
  width: 72px; height: 72px;
  border-radius: 50%;
  background: #fff;
  border: 3px solid rgba(212,137,154,.25);
  box-shadow: 0 8px 28px rgba(44,26,30,.12), 0 0 0 6px rgba(242,196,206,.15);
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  gap: 1px;
}
.ku-grid-badge-num {
  font: 800 15px/1 'Cormorant Garamond',serif;
  color: var(--dusty);
}
.ku-grid-badge-lbl {
  font: 600 8px/1 'Jost',sans-serif;
  letter-spacing: .1em; text-transform: uppercase;
  color: var(--muted);
}

/* Tiap cell foto */
.ku-photo-cell {
  position: relative;
  overflow: hidden;
  border-radius: 16px;
  aspect-ratio: 1 / 1;
  cursor: pointer;
  box-shadow: 0 8px 24px rgba(44,26,30,.12);
  transition: box-shadow .4s ease;
}
.ku-photo-cell:hover {
  box-shadow: 0 20px 48px rgba(44,26,30,.2);
  z-index: 2;
}

/* Radius berbeda tiap sudut untuk kesan asimetris */
.ku-photo-cell:nth-child(1) { border-radius: 24px 8px 8px 8px; }
.ku-photo-cell:nth-child(2) { border-radius: 8px 24px 8px 8px; }
.ku-photo-cell:nth-child(3) { border-radius: 8px 8px 8px 24px; }
.ku-photo-cell:nth-child(4) { border-radius: 8px 8px 24px 8px; }

.ku-photo-cell img {
  width: 100%; height: 100%; object-fit: cover; display: block;
  transition: transform .65s cubic-bezier(.25,.46,.45,.94);
}
.ku-photo-cell:hover img { transform: scale(1.12); }

/* Overlay gradient saat hover */
.ku-photo-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(135deg, rgba(242,196,206,.0), rgba(200,119,138,.0));
  transition: background .4s ease;
  pointer-events: none;
}
.ku-photo-cell:hover .ku-photo-overlay {
  background: linear-gradient(135deg, rgba(242,196,206,.18), rgba(200,119,138,.1));
}

/* Label di pojok setiap foto */
.ku-photo-label {
  position: absolute;
  font: 600 9.5px/1 'Jost',sans-serif;
  letter-spacing: .12em; text-transform: uppercase;
  color: var(--dusty);
  background: rgba(253,249,244,.9);
  backdrop-filter: blur(6px);
  border: 1px solid rgba(212,137,154,.2);
  padding: 5px 10px; border-radius: 100px;
  opacity: 0;
  transform: translateY(4px);
  transition: opacity .3s ease, transform .3s ease;
}
.ku-photo-cell:nth-child(1) .ku-photo-label,
.ku-photo-cell:nth-child(2) .ku-photo-label { top: 12px; }
.ku-photo-cell:nth-child(3) .ku-photo-label,
.ku-photo-cell:nth-child(4) .ku-photo-label { bottom: 12px; transform: translateY(-4px); }
.ku-photo-cell:nth-child(1) .ku-photo-label,
.ku-photo-cell:nth-child(3) .ku-photo-label { left: 12px; }
.ku-photo-cell:nth-child(2) .ku-photo-label,
.ku-photo-cell:nth-child(4) .ku-photo-label { right: 12px; }
.ku-photo-cell:hover .ku-photo-label {
  opacity: 1; transform: translateY(0);
}

/* ════════════════════
   SEO TEKS BLOCK
════════════════════ */
.ku-seo-block {
  max-width: 1200px;
  margin: 56px auto 0;
  padding: 0 24px;
  position: relative; z-index: 1;
}
.ku-seo-toggle-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font: 600 12px/1 'Jost', sans-serif;
  letter-spacing: .08em;
  text-transform: uppercase;
  color: var(--dusty);
  background: rgba(242,196,206,.15);
  border: 1px solid rgba(212,137,154,.25);
  padding: 9px 18px;
  border-radius: 100px;
  cursor: pointer;
  transition: background .2s, border-color .2s;
  margin-bottom: 0;
}
.ku-seo-toggle-btn:hover {
  background: rgba(242,196,206,.3);
  border-color: rgba(212,137,154,.4);
}
.ku-seo-toggle-btn svg {
  transition: transform .3s ease;
  flex-shrink: 0;
}
.ku-seo-toggle-btn.open svg { transform: rotate(180deg); }

.ku-seo-content {
  display: none;
  padding: 28px 32px;
  background: var(--soft);
  border: 1px solid rgba(212,137,154,.15);
  border-radius: 16px;
  margin-top: 12px;
}
.ku-seo-content.visible { display: block; }

.ku-seo-content p {
  font: 400 13.5px/1.9 'Jost', sans-serif;
  color: var(--muted);
  margin-bottom: 1rem;
}
.ku-seo-content p:last-child { margin-bottom: 0; }
.ku-seo-content strong {
  color: var(--dark);
  font-weight: 600;
}
.ku-seo-content a {
  color: var(--dusty);
  text-decoration: underline;
  text-underline-offset: 2px;
  transition: color .2s;
}
.ku-seo-content a:hover { color: var(--dark); }

/* ════════════════════
   STATS BAR BAWAH
════════════════════ */
.ku-statsbar {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  max-width: 1200px;
  margin: 64px auto 0;
  padding: 0 24px;
  position: relative; z-index: 1;
  border-top: 1px solid rgba(212,137,154,.15);
  border-bottom: 1px solid rgba(212,137,154,.15);
  background: var(--soft);
}
.ku-sb-item {
  padding: 32px 20px;
  text-align: center;
  border-right: 1px solid rgba(212,137,154,.12);
  position: relative;
  transition: background .25s;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
.ku-sb-item:last-child { border-right: none; }
.ku-sb-item:hover { background: rgba(242,196,206,.1); }
.ku-sb-item::before {
  content: ''; position: absolute;
  top: 0; left: 50%; transform: translateX(-50%);
  width: 0; height: 2px;
  background: linear-gradient(to right, var(--blush), var(--dusty));
  transition: width .35s ease;
}
.ku-sb-item:hover::before { width: 55%; }
.ku-sb-icon  { font-size: 20px; margin-bottom: 8px; display: block; }
.ku-sb-num   { font:700 1.85rem/1 'Cormorant Garamond',serif; color:var(--dusty); }
.ku-sb-lbl   { font:600 10px/1 'Jost',sans-serif; letter-spacing:.1em; text-transform:uppercase; color:var(--muted); margin-top:4px; display:block; }

/* ── Footer CTA ── */
.ku-footer-cta {
  text-align: center;
  padding: 56px 16px 80px;
  position: relative; z-index: 1;
}
.ku-footer-cta p {
  font: 400 italic 1.25rem/1.5 'Cormorant Garamond',serif;
  color: var(--muted); margin-bottom: 22px;
}
.ku-cta-group { display:flex; align-items:center; justify-content:center; gap:16px; flex-wrap:wrap; }
.ku-btn-primary {
  display:inline-flex; align-items:center; gap:8px;
  font:600 13px/1 'Jost',sans-serif; letter-spacing:.06em; color:#fff;
  background:linear-gradient(135deg,var(--blush),var(--dusty));
  padding:13px 28px; border-radius:100px; text-decoration:none;
  box-shadow:0 6px 22px rgba(200,119,138,.3);
  transition:transform .25s,box-shadow .25s;
}
.ku-btn-primary:hover { transform:translateY(-2px); box-shadow:0 12px 32px rgba(200,119,138,.42); color:#fff; text-decoration:none; }
.ku-btn-ghost {
  display:inline-flex; align-items:center; gap:7px;
  font:600 13px/1 'Jost',sans-serif; letter-spacing:.04em;
  color:var(--dusty); text-decoration:none;
  border:1.5px solid rgba(200,119,138,.3); padding:12px 24px; border-radius:100px;
  transition:border-color .2s, background .2s, color .2s;
}
.ku-btn-ghost:hover { border-color:var(--dusty); background:rgba(242,196,206,.1); color:var(--dark); text-decoration:none; }

/* svg icon kiri kanan */
.ku-point-icon img {
  width: 28px;
  height: 28px;
  object-fit: contain;
}
/* svg icon bawah */
.ku-sb-icon img {
  width: 40px;
  height: 40px;
  object-fit: contain;
  margin-bottom: 10px;
}

/* ── Responsive ── */
@media (max-width: 1023px) {
  .ku-body { grid-template-columns: 1fr; gap: 32px; }
  .ku-side-right .ku-point, .ku-side-left .ku-point {
    flex-direction: row; text-align: left; border: none; padding: 0;
  }
  .ku-side-right .ku-point-body { text-align: left; }
  .ku-side { flex-direction: row; flex-wrap: wrap; gap: 16px; }
  .ku-point { flex: 1; min-width: 220px; }
  .ku-grid { max-width: 380px; margin: 0 auto; }
  .ku-statsbar { grid-template-columns: repeat(2,1fr); }
  .ku-sb-item:nth-child(2) { border-right: none; }
  .ku-sb-item:nth-child(n+3) { border-top: 1px solid rgba(212,137,154,.12); }
}
@media (max-width: 640px) {
  .ku-grid { max-width: 300px; gap: 6px; }
  .ku-grid-badge { width:58px; height:58px; }
  .ku-grid-badge-num { font-size:13px; }
  .ku-seo-content { padding: 20px 18px; }
}
</style>

<section id="tentang" class="pb-0">

  <!-- Header -->
  <div class="ku-header">
    <div class="ku-overline justify-center">
      <span class="ku-overline-dot"></span>
      Cerita &amp; Keunggulan Kami
    </div>
    <h2 class="ku-title">
      Merangkai Bunga<br>dengan <em>Sepenuh Hati</em>
    </h2>
    <div class="ku-divider">
      <div class="ku-divider-line"></div>
      <span class="ku-divider-orn">✦ ✦ ✦</span>
      <div class="ku-divider-line"></div>
    </div>
  </div>

  <!-- Body: Teks Kiri | Grid Foto | Teks Kanan -->
  <div class="ku-body">

    <!-- ── Teks Kiri ── -->
    <div class="ku-side ku-side-left">
      <div class="ku-point">
        <div class="ku-point-icon">
          <img src="<?= BASE_URL ?>/assets/svg/flowers.svg" alt="Flowers Icon">
        </div>
        <div class="ku-point-body">
          <div class="ku-point-title">Bunga 100% Segar</div>
          <div class="ku-point-desc">Dipilih langsung dari pasar setiap pagi. Layu sebelum waktunya? Kami ganti tanpa syarat.</div>
        </div>
      </div>
      <div class="ku-point">
        <div class="ku-point-icon">
          <img src="<?= BASE_URL ?>/assets/svg/brush.svg" alt="Brush Icon">
        </div>
        <div class="ku-point-body">
          <div class="ku-point-title">Desain Custom</div>
          <div class="ku-point-desc">Tim florist kami siap membuat rangkaian sesuai keinginan dan budget Anda, gratis konsultasi.</div>
        </div>
      </div>
      <div class="ku-point">
        <div class="ku-point-icon">
          <img src="<?= BASE_URL ?>/assets/svg/star.svg" alt="Star Icon">
        </div>
        <div class="ku-point-body">
          <div class="ku-point-title">Rating 4.9 Bintang</div>
          <div class="ku-point-desc">Dipercaya lebih dari 500 pelanggan setia dalam 10 tahun melayani Tangerang.</div>
        </div>
      </div>
    </div>

    <!-- ── Grid Foto 2x2 ── -->
    <div class="ku-grid">

      <div class="ku-photo-cell">
        <img src="<?= BASE_URL ?>/assets/images/pink 1.jpg" alt="Bunga segar Tangerang" loading="lazy">
        <div class="ku-photo-overlay"></div>
      </div>

      <div class="ku-photo-cell">
        <img src="<?= BASE_URL ?>/assets/images/pink 2.jpg" alt="Hand Bouquet Tangerang" loading="lazy">
        <div class="ku-photo-overlay"></div>
      </div>

      <div class="ku-photo-cell">
        <img src="<?= BASE_URL ?>/assets/images/pink 3.jpg" alt="Bunga Papan Tangerang" loading="lazy">
        <div class="ku-photo-overlay"></div>
      </div>

      <div class="ku-photo-cell">
        <img src="<?= BASE_URL ?>/assets/images/pink 4.jpg" alt="Standing Flower Tangerang" loading="lazy">
        <div class="ku-photo-overlay"></div>
      </div>

      <!-- Badge di tengah persilangan -->
      <div class="ku-grid-badge">
        <span class="ku-grid-badge-num">10+</span>
        <span class="ku-grid-badge-lbl">Tahun</span>
      </div>

    </div>

    <!-- ── Teks Kanan ── -->
    <div class="ku-side ku-side-right">
      <div class="ku-point">
        <div class="ku-point-icon">
          <img src="<?= BASE_URL ?>/assets/svg/thunder.svg" alt="Thunder Icon">
        </div>
        <div class="ku-point-body">
          <div class="ku-point-title">Kirim 2–4 Jam</div>
          <div class="ku-point-desc">Armada siap antar ke seluruh 12 kecamatan Tangerang, hari yang sama.</div>
        </div>
      </div>
      <div class="ku-point">
        <div class="ku-point-icon">
          <img src="<?= BASE_URL ?>/assets/svg/delivery.svg" alt="Delivery Icon">
        </div>
        <div class="ku-point-body">
          <div class="ku-point-title">Layanan 24/7</div>
          <div class="ku-point-desc">Terima pesanan kapan saja termasuk malam hari dan hari libur nasional.</div>
        </div>
      </div>
      <div class="ku-point">
        <div class="ku-point-icon">
          <img src="<?= BASE_URL ?>/assets/svg/envelope.svg" alt="Envelope Icon">
        </div>
        <div class="ku-point-body">
          <div class="ku-point-title">Free Gift Message</div>
          <div class="ku-point-desc">Sertakan kartu ucapan personal di setiap pesanan tanpa biaya tambahan.</div>
        </div>
      </div>
    </div>

  </div>

  <!-- ════════════════════
       SEO TEXT BLOCK (accordion)
  ════════════════════ -->
  <div class="ku-seo-block">
    <button class="ku-seo-toggle-btn" id="kuSeoBtn" onclick="(function(){var c=document.getElementById('kuSeoContent'),b=document.getElementById('kuSeoBtn');c.classList.toggle('visible');b.classList.toggle('open');b.querySelector('.ku-btn-lbl').textContent=c.classList.contains('visible')?'Sembunyikan Info':'Selengkapnya Tentang Kami';})()">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
      <span class="ku-btn-lbl">Selengkapnya Tentang Kami</span>
    </button>

    <div class="ku-seo-content" id="kuSeoContent">

      <p>
        Selamat datang di <strong><?= e(setting('site_name')) ?></strong> — <strong>toko bunga online Indonesia</strong> yang hadir khusus untuk wilayah Tangerang dan sekitarnya. Sebagai <strong>florist online terpercaya</strong> dengan pengalaman lebih dari 10 tahun, kami telah melayani ribuan pelanggan dengan satu komitmen utama: menghadirkan rangkaian bunga berkualitas tinggi, pengiriman cepat, dan harga yang bersahabat. Pelajari lebih lanjut tentang perjalanan kami di <a href="https://jualbungatangerang.com/#tentang">halaman tentang kami</a>.
      </p>

      <p>
        Kami hadir sebagai solusi terbaik bagi Anda yang mencari <strong>buket bunga online same day delivery seluruh Indonesia</strong>. Setiap momen berharga — ulang tahun, pernikahan, wisuda, perpisahan, hari jadi, maupun ungkapan simpati — layak dirayakan dengan bunga indah yang tiba tepat waktu. Mulai dari mawar merah elegan, buket bunga matahari ceria, hingga rangkaian lily putih yang mewah, semuanya tersedia dan siap dikirim dari Tangerang ke seluruh penjuru Indonesia. Cek selengkapnya di <a href="https://jualbungatangerang.com/#layanan">halaman layanan kami</a>.
      </p>

      <p>
        Sebagai <strong>toko bunga terdekat online</strong> yang berbasis di Tangerang, kami memahami kebutuhan pelanggan di wilayah Banten dan sekitarnya. Namun lebih dari itu, kami juga beroperasi sebagai <strong>florist nasional kirim ke rumah, kantor &amp; hotel</strong> yang melayani pengiriman ke seluruh kota besar di Indonesia — Jakarta, Surabaya, Bandung, Bali, Medan, Makassar, dan masih banyak lagi. Tidak perlu khawatir soal jarak — cukup pesan dari smartphone Anda, dan kami yang urus selebihnya. Pastikan area Anda sudah terjangkau dengan melihat <a href="https://jualbungatangerang.com/#area">area pengiriman kami</a>.
      </p>

      <p>
        Bagi Anda yang membutuhkan bunga segera, layanan <strong>kirim bunga online seluruh Indonesia</strong> kami hadir dengan sistem pemrosesan pesanan yang cepat dan responsif. Begitu pesanan masuk dan pembayaran dikonfirmasi, tim florist kami langsung bekerja menyiapkan rangkaian bunga terbaik untuk Anda. Dengan estimasi waktu pengiriman yang transparan dan layanan fast response, kejutan indah Anda tidak akan terlambat. Kami bangga menjadi pilihan <strong>toko bunga online Indonesia kirim cepat seluruh kota</strong> yang dapat diandalkan kapan pun Anda membutuhkannya.
      </p>

      <p>
        Kami mengoperasikan <strong>toko bunga 24 jam online</strong> yang siap menerima pesanan kapan saja — pagi, siang, sore, malam, bahkan dini hari sekalipun. Tim customer service kami selalu siap membantu Anda menemukan pilihan bunga yang paling tepat, termasuk di hari libur nasional dan akhir pekan. Dengan sistem pemesanan yang mudah melalui website kami, Anda bisa memesan dalam hitungan menit tanpa perlu keluar rumah. Ini adalah arti sesungguhnya dari kemudahan berbelanja bunga di era digital.
      </p>

      <p>
        Berbeda dengan banyak toko konvensional yang terbatas area, kami hadir sebagai <strong>florist Indonesia murah dan premium</strong> yang tidak mengorbankan kualitas demi harga. Setiap bunga yang masuk ke workshop kami telah melalui seleksi ketat oleh tim florist berpengalaman. Kami bekerja sama dengan supplier bunga segar terpercaya yang menjamin kesegaran dan daya tahan bunga tinggi, sehingga buket yang diterima oleh orang tersayang Anda selalu tampil prima dan mekar sempurna.
      </p>

      <p>
        Keunggulan kami sebagai <strong>buket bunga online terbaik kualitas premium</strong> bukan hanya pada tampilan yang memukau, tetapi juga pada perhatian kami terhadap setiap detail — mulai dari pemilihan bunga, teknik merangkai, pemilihan wrapping, hingga pengemasan akhir yang aman untuk pengiriman. Setiap buket dikerjakan dengan penuh cinta dan profesionalisme oleh tim florist kami yang berpengalaman. Ingin tahu apa kata pelanggan kami? Baca langsung <a href="https://jualbungatangerang.com/#testimoni">testimoni pelanggan setia kami</a>.
      </p>

      <p>
        Sebagai <strong>toko bunga online harga terjangkau kirim cepat</strong>, kami menyediakan berbagai pilihan harga mulai dari ekonomis hingga premium. Harga mulai Rp 300.000 sudah bisa Anda dapatkan rangkaian bunga segar berkualitas tinggi yang dikerjakan langsung oleh florist profesional kami. Anda bisa menyesuaikan pilihan sesuai budget dan kebutuhan acara — tidak ada minimal order untuk pengiriman dalam kota, dan kami selalu memberikan nilai terbaik untuk setiap rupiah yang Anda keluarkan.
      </p>

      <p>
        Kami juga melayani kebutuhan korporat — dekorasi meja resepsionis, pengiriman bunga ucapan selamat untuk mitra bisnis, hingga rangkaian untuk acara internal perusahaan. Dengan jaringan pengiriman yang luas dan armada yang handal, kami siap menangani pesanan dalam jumlah besar dengan harga spesial dan layanan yang tetap profesional. Grand opening, seminar, pelantikan, anniversary perusahaan — semua kebutuhan bunga korporat Anda bisa kami tangani dengan standar terbaik.
      </p>

      <p>
        Kami juga terus mengikuti tren desain rangkaian bunga modern agar pilihan yang tersedia selalu relevan dengan selera pelanggan masa kini. Mulai dari buket bergaya Korean style yang kekinian, wrapping premium minimalis yang elegan, bunga papan yang megah, standing flower mewah, hingga hampers bunga kombinasi hadiah spesial — semuanya dapat Anda pesan dengan mudah. Dapatkan inspirasi terbaru dan tips merawat bunga dari <a href="https://jualbungatangerang.com/blog/">blog bunga kami</a> yang selalu diperbarui.
      </p>

      <p>
        Kami percaya bahwa bunga adalah bahasa universal yang mampu mewakili berbagai emosi — ucapan selamat, permintaan maaf, rasa rindu, dukungan, hingga ungkapan cinta yang tulus. Karena itu, setiap pesanan yang masuk selalu kami tangani secara istimewa. Tim kami akan memastikan jenis bunga, komposisi warna, kartu ucapan custom, hingga detail pengemasan disiapkan dengan teliti agar pesan yang ingin Anda sampaikan dapat diterima dengan sempurna oleh penerima.
      </p>

      <p>
        Bagi pelanggan yang baru pertama kali memesan secara online, proses pemesanan di website kami dibuat sesederhana mungkin. Anda cukup memilih kategori produk, menentukan desain favorit, mengisi alamat tujuan, lalu menyelesaikan pembayaran. Setelah itu, tim kami memproses pesanan dan memberikan update status secara berkala. Masih ada pertanyaan? Kunjungi <a href="https://jualbungatangerang.com/#faq">halaman FAQ kami</a> untuk jawaban lengkap seputar pemesanan, pengiriman, dan metode pembayaran yang tersedia.
      </p>

      <p>
        Selain tampilan yang cantik, daya tahan bunga juga menjadi prioritas utama kami. Oleh sebab itu, kami memberikan penanganan khusus mulai dari proses penyimpanan, perakitan, hingga pengiriman menggunakan teknik florist profesional yang menjaga bunga tetap segar lebih lama. Kepuasan pelanggan selalu menjadi alasan utama kami untuk terus berkembang — dan itulah mengapa banyak pelanggan kembali memesan berulang kali serta merekomendasikan layanan kami kepada keluarga, teman, dan rekan kerja mereka.
      </p>

      <p>
        Kami juga memahami bahwa setiap pelanggan memiliki kebutuhan yang berbeda-beda. Ada yang membutuhkan buket sederhana namun elegan, ada pula yang mencari rangkaian mewah untuk acara penting dan momen spesial. Karena itu, kami menyediakan layanan konsultasi personal agar setiap pesanan benar-benar sesuai dengan tujuan, karakter penerima, serta anggaran yang Anda siapkan. Dengan bantuan tim florist berpengalaman, Anda tidak perlu bingung menentukan pilihan terbaik.
      </p>

      <p>
        Tidak hanya fokus pada keindahan rangkaian, kami juga memperhatikan keamanan selama proses pengiriman. Setiap bunga dikemas dengan rapi menggunakan material pelindung yang sesuai agar tetap aman saat perjalanan. Untuk area Tangerang, Banten, maupun pengiriman ke kota lain di Indonesia, kami berusaha memastikan bunga tiba dalam kondisi segar, utuh, dan siap memberikan kesan terbaik kepada penerima.
      </p>

      <p>
        Seiring berkembangnya kebutuhan pelanggan, kami terus meningkatkan kualitas layanan mulai dari kecepatan respon, variasi produk, metode pembayaran, hingga sistem pemesanan yang lebih praktis. Kami ingin setiap orang dapat merasakan mudahnya memesan bunga secara online tanpa rasa khawatir. Ketika Anda membutuhkan hadiah yang berkesan, kejutan romantis, atau ungkapan perhatian yang tulus, florist kami di Tangerang siap membantu mewujudkannya dengan pelayanan terbaik.
      </p>

    </div>
  </div>
  <!-- ════ END SEO TEXT BLOCK ════ -->

  <!-- Stats Bar -->
  <div class="ku-statsbar">
    <div class="ku-sb-item">
      <span class="ku-sb-icon">
        <img src="<?= BASE_URL ?>/assets/svg/flower.svg" alt="Flower Icon">
      </span>
      <div class="ku-sb-num">100%</div>
      <span class="ku-sb-lbl">Bunga Segar</span>
    </div>
    <div class="ku-sb-item">
      <span class="ku-sb-icon">
        <img src="<?= BASE_URL ?>/assets/svg/thunder.svg" alt="Thunder Icon">
      </span>
      <div class="ku-sb-num">2–4 Jam</div>
      <span class="ku-sb-lbl">Estimasi Kirim</span>
    </div>
    <div class="ku-sb-item">
      <span class="ku-sb-icon">
        <img src="<?= BASE_URL ?>/assets/svg/location.svg" alt="Location Icon">
      </span>
      <div class="ku-sb-num">Tangerang</div>
      <span class="ku-sb-lbl">Area Layanan</span>
    </div>
    <div class="ku-sb-item">
      <span class="ku-sb-icon">
        <img src="<?= BASE_URL ?>/assets/svg/clock.svg" alt="Clock Icon">
      </span>
      <div class="ku-sb-num">24/7</div>
      <span class="ku-sb-lbl">Siap Melayani</span>
    </div>
  </div>

  <!-- Footer CTA -->
  <div class="ku-footer-cta">
    <p>Siap membuat momen Anda menjadi lebih istimewa? 🌸</p>
    <div class="ku-cta-group">
      <a href="<?= e($wa_url) ?>?text=<?= urlencode('Halo, saya ingin konsultasi tentang pesanan bunga.') ?>"
         target="_blank" class="ku-btn-primary">
        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
          <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
          <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
        </svg>
        Konsultasi Gratis
      </a>
      <a href="#produk" class="ku-btn-ghost">
        Lihat Produk Kami
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
        </svg>
      </a>
    </div>
  </div>

</section>