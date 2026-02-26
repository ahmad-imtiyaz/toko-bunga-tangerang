    </div><!-- end page content -->
  </main>
</div>

<script>
// Flash message auto-hide
const flash = document.getElementById('flash-message');
if (flash) {
  setTimeout(() => { flash.style.opacity = '0'; flash.style.transition = 'opacity 0.5s'; setTimeout(() => flash.remove(), 500); }, 3000);
}
</script>
</body>
</html>
