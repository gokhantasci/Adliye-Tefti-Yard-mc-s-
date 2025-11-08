<!-- 
  Üst Gezinme Çubuğu (Navbar)
  
  Uygulamanın üst kısmında görünen gezinme çubuğu.
  Logo, menü butonu ve tema değiştirici buton içerir.
-->
<header class="navbar">
  <!-- Yan menü açma/kapama butonu -->
  <button id="sidebarToggle" class="icon-btn" aria-label="Menüyü aç/kapat">
    <span class="material-symbols-rounded">menu</span>
  </button>
  
  <!-- Marka/Logo alanı -->
  <div class="brand">
    <img src="/assets/img/favicon.svg" alt="Teftiş Logo" width="24" height="24" />
    <span class="brand-title">Teftiş - 657.com.tr</span>
  </div>
  
  <!-- Navbar sağ taraf aksiyonları -->
  <div class="navbar-actions">
    <!-- Tema değiştirme butonu (koyu/açık mod) -->
    <button id="themeToggle" class="icon-btn" aria-label="Tema değiştir">
      <span class="material-symbols-rounded" id="themeIcon">dark_mode</span>
    </button>
  </div>
</header>