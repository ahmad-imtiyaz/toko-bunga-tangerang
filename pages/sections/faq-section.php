
<!-- ============================================================
     FAQ SECTION
============================================================ -->
<?php
/* ================================================================
   FAQ SECTION — Split Layout
   Kiri: headline + deskripsi + CTA WA
   Kanan: accordion FAQ
   JSON-LD schema tetap valid untuk SEO
================================================================ */
?>

<!-- FAQ Schema — tetap untuk SEO -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    <?php foreach ($faqs as $i => $faq): ?>
    {
      "@type": "Question",
      "name": "<?= addslashes($faq['question']) ?>",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "<?= addslashes($faq['answer']) ?>"
      }
    }<?= $i < count($faqs)-1 ? ',' : '' ?>
    <?php endforeach; ?>
  ]
}
</script>

<style>
  /* Accordion answer */
  .faq-body {
    display: grid;
    grid-template-rows: 0fr;
    transition: grid-template-rows .35s cubic-bezier(.4,0,.2,1);
  }
  .faq-body.open {
    grid-template-rows: 1fr;
  }
  .faq-body-inner {
    overflow: hidden;
  }

  /* Icon rotate */
  .faq-icon {
    transition: transform .3s cubic-bezier(.4,0,.2,1);
    flex-shrink: 0;
  }
  .faq-item.open .faq-icon {
    transform: rotate(180deg);
  }

  /* Card hover */
  .faq-item {
    transition: border-color .25s ease, box-shadow .25s ease;
  }
  .faq-item:hover {
    border-color: rgba(245,197,24,.25) !important;
  }
  .faq-item.open {
    border-color: rgba(245,197,24,.35) !important;
    box-shadow: 0 8px 32px rgba(0,0,0,.25);
  }

  /* Number dekoratif */
  .faq-num {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 11px;
    font-weight: 900;
    color: rgba(245,197,24,.4);
    letter-spacing: .05em;
    min-width: 28px;
    padding-top: 1px;
  }
  .faq-item.open .faq-num {
    color: #F5C518;
  }
</style>

<?php
/* ================================================================
   FAQ SECTION — Chat Bubble Style (WhatsApp Vibes)
   Tema: ivory / rose / blush / cream
   Pertanyaan = bubble kiri (customer), Jawaban = bubble kanan (toko)
   Animasi: typing dots → bubble muncul + percikan bunga
================================================================ */
?>

<!-- FAQ Schema SEO -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    <?php foreach ($faqs as $i => $faq): ?>
    {
      "@type": "Question",
      "name": "<?= addslashes($faq['question']) ?>",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "<?= addslashes($faq['answer']) ?>"
      }
    }<?= $i < count($faqs)-1 ? ',' : '' ?>
    <?php endforeach; ?>
  ]
}
</script>

<style>
#faq {
  --blush: #F2C4CE;
  --rose:  #D4899A;
  --dusty: #C8778A;
  --cream: #FAF5EE;
  --ivory: #FDF9F4;
  --soft:  #F7EEF0;
  --muted: #8C6B72;
  --dark:  #2C1A1E;
  --chat-bg: #EDE0D4;
}

.faq-chat-window {
  background: var(--chat-bg);
  background-image:
    radial-gradient(circle at 20% 30%, rgba(242,196,206,.35) 0%, transparent 45%),
    radial-gradient(circle at 80% 70%, rgba(212,137,154,.2) 0%, transparent 40%);
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 0 20px 60px rgba(44,26,30,.12), 0 4px 16px rgba(212,137,154,.15);
}

.faq-chat-topbar {
  background: linear-gradient(135deg, var(--rose), var(--dusty));
  padding: 14px 20px;
  display: flex;
  align-items: center;
  gap: 12px;
}
.faq-chat-avatar {
  width: 40px; height: 40px;
  border-radius: 50%;
  background: rgba(255,255,255,.25);
  display: flex; align-items: center; justify-content: center;
  font-size: 20px; flex-shrink: 0;
}
.faq-online-dot {
  width: 9px; height: 9px;
  border-radius: 50%;
  background: #4ADE80;
  box-shadow: 0 0 0 2px rgba(255,255,255,.4);
  animation: faq-blink 2s ease-in-out infinite;
}
@keyframes faq-blink {
  0%,100% { opacity: 1; } 50% { opacity: .4; }
}

.faq-chat-body {
  padding: 20px 16px;
  min-height: 360px;
  max-height: 520px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 6px;
  scroll-behavior: smooth;
}
.faq-chat-body::-webkit-scrollbar { width: 4px; }
.faq-chat-body::-webkit-scrollbar-thumb { background: rgba(212,137,154,.35); border-radius: 4px; }

.faq-bubble {
  max-width: 78%;
  padding: 10px 14px;
  border-radius: 18px;
  font-size: 13px;
  line-height: 1.65;
  position: relative;
  animation: faq-pop .3s cubic-bezier(.34,1.56,.64,1) both;
  word-break: break-word;
}
@keyframes faq-pop {
  from { transform: scale(.75) translateY(8px); opacity: 0; }
  to   { transform: scale(1)   translateY(0);   opacity: 1; }
}

.faq-bubble-q {
  align-self: flex-start;
  background: #fff;
  color: var(--dark);
  border-bottom-left-radius: 4px;
  box-shadow: 0 2px 8px rgba(44,26,30,.08);
  cursor: pointer;
  transition: transform .2s ease, box-shadow .2s ease;
  border: 1.5px solid rgba(212,137,154,.15);
}
.faq-bubble-q:hover {
  transform: translateY(-2px) scale(1.01);
  box-shadow: 0 6px 18px rgba(212,137,154,.2);
  border-color: rgba(212,137,154,.35);
}
.faq-bubble-q::before {
  content: '';
  position: absolute;
  left: -8px; bottom: 8px;
  border: 8px solid transparent;
  border-right-color: #fff;
  border-left: 0;
  filter: drop-shadow(-2px 1px 2px rgba(44,26,30,.06));
}

.faq-bubble-a {
  align-self: flex-end;
  background: linear-gradient(135deg, #fce4ec, #f8d7e3);
  color: var(--dark);
  border-bottom-right-radius: 4px;
  box-shadow: 0 2px 8px rgba(212,137,154,.2);
}
.faq-bubble-a::after {
  content: '';
  position: absolute;
  right: -8px; bottom: 8px;
  border: 8px solid transparent;
  border-left-color: #f8d7e3;
  border-right: 0;
}

.faq-time {
  font-size: 10px; color: var(--muted); opacity: .65;
  display: block; text-align: right; margin-top: 4px;
}
.faq-bubble-q .faq-time { text-align: left; }
.faq-check { font-size: 11px; color: var(--rose); }

.faq-typing {
  align-self: flex-end;
  background: linear-gradient(135deg, #fce4ec, #f8d7e3);
  border-radius: 18px;
  border-bottom-right-radius: 4px;
  padding: 12px 18px;
  display: none;
  gap: 5px;
  align-items: center;
  box-shadow: 0 2px 8px rgba(212,137,154,.2);
  animation: faq-pop .25s ease both;
}
.faq-typing.show { display: flex; }
.faq-typing span {
  width: 7px; height: 7px;
  border-radius: 50%;
  background: var(--dusty);
  animation: faq-dot-bounce .9s ease-in-out infinite;
}
.faq-typing span:nth-child(2) { animation-delay: .15s; }
.faq-typing span:nth-child(3) { animation-delay: .30s; }
@keyframes faq-dot-bounce {
  0%,80%,100% { transform: translateY(0);   opacity: .5; }
  40%         { transform: translateY(-6px); opacity: 1;  }
}

.faq-chip {
  display: inline-flex; align-items: center; gap: 6px;
  background: #fff;
  border: 1.5px solid rgba(212,137,154,.22);
  border-radius: 100px;
  padding: 7px 14px;
  font-size: 12.5px; font-weight: 600;
  color: var(--dark);
  cursor: pointer;
  transition: all .22s ease;
  white-space: nowrap;
  text-align: left;
}
.faq-chip:hover {
  background: linear-gradient(135deg, #fff8f9, #fce4ec);
  border-color: var(--rose); color: var(--dusty);
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(212,137,154,.2);
}
.faq-chip.used { opacity: .4; pointer-events: none; }

@keyframes faq-petal-burst {
  0%   { transform: translate(0,0) rotate(0deg) scale(1); opacity: 1; }
  100% { transform: translate(var(--bx),var(--by)) rotate(var(--br)) scale(0); opacity: 0; }
}
.faq-petal-el {
  position: fixed; pointer-events: none; z-index: 9999;
  animation: faq-petal-burst .8s cubic-bezier(.2,.8,.4,1) forwards;
}

@keyframes faq-bg-float {
  0%   { transform: translateY(0) rotate(0deg); opacity: 0; }
  10%  { opacity: .2; } 90% { opacity: .1; }
  100% { transform: translateY(-90px) rotate(35deg); opacity: 0; }
}
.faq-bg-petal {
  position: absolute; pointer-events: none;
  animation: faq-bg-float 8s ease-in-out infinite;
}

.faq-chips-wrap {
  display: flex; flex-wrap: wrap; gap: 8px;
  padding: 16px;
  background: rgba(242,196,206,.12);
  border-top: 1px solid rgba(212,137,154,.18);
  max-height: 160px;
  overflow-y: auto;
}
</style>

<section id="faq" class="py-20 relative overflow-hidden"
         style="background: var(--ivory, #FDF9F4);">

  <div class="absolute top-0 left-0 w-full h-px"
       style="background: linear-gradient(90deg, transparent, rgba(212,137,154,.4), transparent);"></div>
  <div id="faq-bg-petals" class="absolute inset-0 pointer-events-none overflow-hidden"></div>
  <div class="absolute -right-20 top-20 w-72 h-72 rounded-full pointer-events-none"
       style="background: radial-gradient(circle, rgba(242,196,206,.25) 0%, transparent 70%); filter: blur(55px);"></div>
  <div class="absolute -left-16 bottom-16 w-64 h-64 rounded-full pointer-events-none"
       style="background: radial-gradient(circle, rgba(200,119,138,.12) 0%, transparent 70%); filter: blur(50px);"></div>

  <div class="relative z-10 max-w-7xl mx-auto px-4">

    <div class="text-center mb-14">
      <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-[11px] font-bold tracking-widest uppercase mb-5"
           style="background: rgba(212,137,154,.12); border: 1px solid rgba(212,137,154,.25); color: var(--dusty);">
        <span class="inline-block w-1.5 h-1.5 rounded-full" style="background: var(--rose);"></span>
        Ada Pertanyaan?
      </div>
      <h2 class="font-serif text-3xl md:text-4xl font-black mb-3" style="color: var(--dark);">
        Pertanyaan yang
        <span style="background: linear-gradient(135deg, var(--rose), var(--dusty)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">
          Sering Ditanyakan
        </span>
      </h2>
      <p class="text-[15px] max-w-md mx-auto" style="color: var(--muted);">
        Ketuk pertanyaan di bawah chat untuk melihat jawaban kami 🌸
      </p>
    </div>

    <div class="grid lg:grid-cols-[1fr_300px] gap-8 items-start max-w-5xl mx-auto">

      <!-- Chat Window -->
      <div class="faq-chat-window">
        <div class="faq-chat-topbar">
          <div class="faq-chat-avatar">🌸</div>
          <div class="flex-1">
            <div class="font-bold text-white text-sm leading-tight">Toko Bunga — CS</div>
            <div class="flex items-center gap-1.5 mt-0.5">
              <div class="faq-online-dot"></div>
              <span class="text-white/70 text-[11px]">Online sekarang</span>
            </div>
          </div>
          <div class="text-white/60 text-[11px]">💬 FAQ</div>
        </div>

        <div class="faq-chat-body" id="faq-chat-body">
          <div class="faq-bubble faq-bubble-a" style="animation-delay:.1s">
            Halo! 👋 Ada yang bisa kami bantu? Silakan pilih pertanyaan di bawah ini ya~
            <span class="faq-time">Sekarang <span class="faq-check">✓✓</span></span>
          </div>
        </div>

        <div style="padding: 0 16px 8px; display:flex; justify-content:flex-end;">
          <div class="faq-typing" id="faq-typing">
            <span></span><span></span><span></span>
          </div>
        </div>

        <div class="faq-chips-wrap" id="faq-chips">
          <?php foreach ($faqs as $i => $faq): ?>
          <button class="faq-chip"
                  data-index="<?= $i ?>"
                  data-q="<?= htmlspecialchars($faq['question'], ENT_QUOTES) ?>"
                  data-a="<?= htmlspecialchars($faq['answer'], ENT_QUOTES) ?>"
                  onclick="faqAskQuestion(this)">
            <span style="color:var(--rose);">🌸</span>
            <?= e($faq['question']) ?>
          </button>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Panel kanan -->
      <div class="flex flex-col gap-5">
        <div class="rounded-2xl p-6"
             style="background:#fff; border:1.5px solid rgba(212,137,154,.18); box-shadow:0 4px 20px rgba(212,137,154,.1);">
          <div class="text-center mb-4">
            <div class="text-3xl mb-1">💐</div>
            <div class="font-serif font-black text-2xl" style="color:var(--dark);"><?= count($faqs) ?></div>
            <div class="text-[11px] font-bold uppercase tracking-widest mt-0.5" style="color:var(--muted);">Pertanyaan Tersedia</div>
          </div>
          <div class="grid grid-cols-2 gap-3 pt-4" style="border-top:1px solid rgba(212,137,154,.15);">
            <div class="text-center">
              <div class="font-serif font-black text-xl" style="color:var(--dark);">24/7</div>
              <div class="text-[10px] font-bold uppercase tracking-widest" style="color:var(--muted);">Siap Bantu</div>
            </div>
            <div class="text-center">
              <div class="font-serif font-black text-xl" style="color:var(--dark);">Free</div>
              <div class="text-[10px] font-bold uppercase tracking-widest" style="color:var(--muted);">Konsultasi</div>
            </div>
          </div>
        </div>

        <div class="rounded-2xl p-6 text-center"
             style="background: linear-gradient(135deg, #fce4ec, #f8d7e3); border: 1.5px solid rgba(212,137,154,.25);">
          <p class="text-sm font-semibold mb-1" style="color:var(--dark);">Tidak menemukan jawaban?</p>
          <p class="text-[12px] mb-4" style="color:var(--muted);">Hubungi kami langsung via WhatsApp</p>
          <a href="<?= e($wa_url) ?>?text=<?= urlencode('Halo, saya punya pertanyaan tentang Toko Bunga.') ?>"
             target="_blank"
             class="inline-flex items-center gap-2 font-bold text-sm text-white px-5 py-3 rounded-full no-underline"
             style="background: linear-gradient(135deg, var(--rose), var(--dusty)); box-shadow: 0 8px 20px rgba(212,137,154,.35);"
             onmouseover="this.style.transform='translateY(-2px)'"
             onmouseout="this.style.transform=''">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
              <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.861L0 24l6.305-1.508A11.954 11.954 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.002-1.374l-.36-.214-3.735.893.944-3.639-.234-.374A9.818 9.818 0 1112 21.818z"/>
            </svg>
            Chat WhatsApp
          </a>
        </div>

        <button onclick="faqResetChat()"
                class="text-[12px] font-semibold text-center w-full py-2 rounded-xl transition"
                style="color:var(--muted); background:rgba(212,137,154,.08); border:1px solid rgba(212,137,154,.18);"
                onmouseover="this.style.background='rgba(212,137,154,.16)'"
                onmouseout="this.style.background='rgba(212,137,154,.08)'">
          🔄 Reset Percakapan
        </button>
      </div>

    </div>
  </div>
</section>

<script>
(function () {
  const petals   = ['🌸','🌺','🌷','🌼','🪷','🌹','💐'];
  const chatBody = document.getElementById('faq-chat-body');
  const typing   = document.getElementById('faq-typing');

  function nowTime() {
    const d = new Date();
    return d.getHours().toString().padStart(2,'0') + ':' + d.getMinutes().toString().padStart(2,'0');
  }

  function burst(cx, cy, count) {
    for (let i = 0; i < count; i++) {
      const el   = document.createElement('span');
      const icon = petals[Math.floor(Math.random() * petals.length)];
      const ang  = (Math.PI * 2 / count) * i + (Math.random() - .5) * .7;
      const dist = 45 + Math.random() * 55;
      el.className = 'faq-petal-el';
      el.textContent = icon;
      el.style.cssText = `
        left:${cx}px; top:${cy}px;
        --bx:${Math.cos(ang)*dist}px;
        --by:${Math.sin(ang)*dist - 18}px;
        --br:${(Math.random()-.5)*320}deg;
        font-size:${12+Math.random()*9}px;
        animation-delay:${i*28}ms;
      `;
      document.body.appendChild(el);
      setTimeout(() => el.remove(), 900 + i * 28);
    }
  }

  function scrollChat() {
    setTimeout(() => { chatBody.scrollTop = chatBody.scrollHeight; }, 50);
  }

  function addBubble(type, text, delay) {
    return new Promise(res => {
      setTimeout(() => {
        const div = document.createElement('div');
        div.className = 'faq-bubble faq-bubble-' + type;
        const check = type === 'a' ? '<span class="faq-check">✓✓</span>' : '';
        div.innerHTML = text + '<span class="faq-time">' + nowTime() + ' ' + check + '</span>';
        chatBody.appendChild(div);
        scrollChat();
        res();
      }, delay);
    });
  }

  window.faqAskQuestion = async function(chip) {
    if (chip.classList.contains('used')) return;
    chip.classList.add('used');

    const q    = chip.dataset.q;
    const a    = chip.dataset.a;
    const rect = chip.getBoundingClientRect();
    burst(rect.left + rect.width/2, rect.top + rect.height/2, 7);

    await addBubble('q', q, 0);

    typing.classList.add('show');
    scrollChat();

    await new Promise(res => setTimeout(res, 1300));
    typing.classList.remove('show');
    await addBubble('a', a, 0);

    const cbRect = chatBody.getBoundingClientRect();
    burst(cbRect.right - 60, cbRect.bottom - 40, 6);
  };

  window.faqResetChat = function () {
    chatBody.querySelectorAll('.faq-bubble:not(:first-child)').forEach(b => b.remove());
    typing.classList.remove('show');
    document.querySelectorAll('#faq-chips .faq-chip').forEach(c => c.classList.remove('used'));
  };

  (function () {
    const wrap = document.getElementById('faq-bg-petals');
    if (!wrap) return;
    for (let i = 0; i < 10; i++) {
      const el  = document.createElement('span');
      el.className = 'faq-bg-petal';
      el.textContent = petals[i % petals.length];
      const dur = 7 + Math.random() * 5;
      el.style.cssText =
        'left:' + (3+Math.random()*94) + '%;' +
        'top:'  + (5+Math.random()*88) + '%;' +
        'font-size:' + (10+Math.random()*11) + 'px;' +
        'animation-duration:' + dur + 's;' +
        'animation-delay:-' + (Math.random()*dur) + 's;';
      wrap.appendChild(el);
    }
  })();
})();
</script>
