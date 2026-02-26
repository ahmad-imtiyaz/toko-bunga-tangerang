

// ── Navbar scroll — handle navbar-wrap (pill) BUKAN #navbar langsung ──
// Kalau pakai header-navbar-pill.php, scroll handler sudah ada di dalam file itu.
// Baris ini hanya sebagai fallback safety, tidak akan konflik.
window.addEventListener('scroll', () => {
  const wrap = document.getElementById('navbar-wrap'); // pill wrapper
  const topbar = document.getElementById('topbar');

  if (wrap) {
    wrap.classList.toggle('scrolled', window.scrollY > 50);
  }
  if (topbar) {
    topbar.classList.toggle('hide', window.scrollY > 50);
  }

  // DIHAPUS: jangan toggle 'scrolled' langsung ke #navbar
  // const nav = document.getElementById('navbar');
  // if (nav) nav.classList.toggle('scrolled', window.scrollY > 10);
}, { passive: true });

// ── Smooth scroll untuk anchor links ──
document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', e => {
    const href = a.getAttribute('href');
    if (href === '#') return;
    const target = document.querySelector(href);
    if (target) {
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
});

// ── Lazy image fallback ──
document.querySelectorAll('img').forEach(img => {
  img.addEventListener('error', () => {
    img.src = 'https://images.unsplash.com/photo-1487530811015-780780dde0e4?w=400&h=300&fit=crop';
  });
});