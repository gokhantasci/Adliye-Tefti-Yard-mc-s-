<!--
  ========================================
  ÜST GEZİNME ÇUBUĞU (NAVBAR.PHP)
  ========================================
  Adalet Bakanlığı Teması
  ========================================
-->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
  <div class="container-fluid">
    <!-- Sidebar toggle button (responsive - sadece 1400px altında göster) -->
    <button id="sidebarToggle" class="btn btn-link text-white p-2 me-2" type="button" aria-label="Menüyü aç/kapat">
      <span class="material-symbols-rounded">menu</span>
    </button>
    
    <!-- Brand/Logo -->
    <a class="navbar-brand d-flex align-items-center gap-2 mx-auto" href="/index.php">
      <img src="/assets/img/favicon.svg" alt="Adalet Bakanlığı" width="28" height="28" />
      <span class="navbar-brand-text" id="navbarBrandText">Teftiş - 657.com.tr</span>
    </a>
    
    <!-- Navbar actions (right side) -->
    <div class="d-flex align-items-center gap-1 position-relative">
      <!-- Haberler Dropdown -->
      <div class="position-relative">
        <button id="newsToggle" class="btn btn-link text-white p-2 position-relative" aria-label="Haberleri göster" title="Haberler">
          <span class="material-symbols-rounded">notifications</span>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="newsBadge" style="display:none;">
            0
          </span>
        </button>
        
        <!-- News Dropdown Panel -->
        <div id="newsDropdown" class="dropdown-panel" hidden>
          <div class="dropdown-panel-head">
            <span class="material-symbols-rounded" aria-hidden="true">notifications</span>
            <strong>Uygulama Güncellemeleri</strong>
            <button type="button" class="dropdown-close-btn" id="newsCloseBtn" aria-label="Kapat">
              <span class="material-symbols-rounded" aria-hidden="true">close</span>
            </button>
          </div>
          <div class="dropdown-panel-body" id="newsDropdownBody" aria-live="polite">
            <div id="newsDropdownMeta" class="news-meta">Yükleniyor...</div>
            <div id="newsDropdownList" class="news-list"></div>
          </div>
          <div class="dropdown-panel-foot">
            <nav id="newsDropdownPager" class="pager" role="navigation" aria-label="Haber sayfalama"></nav>
          </div>
        </div>
      </div>
      
      <!-- Log Kayıtları Dropdown -->
      <div class="position-relative">
        <button id="logToggle" class="btn btn-link text-white p-2 position-relative" aria-label="Log kayıtlarını göster" title="Log Kayıtları">
          <span class="material-symbols-rounded">list_alt</span>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark" id="logBadge" style="display:none;">
            0
          </span>
        </button>
      </div>
      
      <!-- Tema değiştirme -->
      <div class="position-relative">
        <button id="themeToggle" class="btn btn-link text-white p-2" aria-label="Tema değiştir" title="Tema Değiştir">
          <span class="material-symbols-rounded" id="themeIcon">dark_mode</span>
        </button>
      </div>
    </div>
  </div>
</nav>

<!-- Log Kayıtları Paneli (Bottom Right - Fixed Position) -->
<div id="logPanel" class="log-panel" hidden>
  <div class="log-panel-head">
    <span class="material-symbols-rounded" aria-hidden="true">list_alt</span>
    <strong>Log Kayıtları</strong>
    <button type="button" class="log-clear-btn" id="logClearBtn" aria-label="Logları temizle" title="Logları Temizle">
      <span class="material-symbols-rounded" aria-hidden="true">delete</span>
    </button>
  </div>
  <div class="log-panel-body" id="logPanelBody" aria-live="polite"></div>
  <div class="log-panel-foot">
    <div class="pager" id="logPager" aria-label="Log sayfalama">
      <div>
        <button type="button" class="btn btn-sm btn-secondary" id="logPrevBtn" disabled>
          <span class="material-symbols-rounded" style="font-size: 1rem;">chevron_left</span>
        </button>
      </div>
      <div class="text-muted small" id="logPagerInfo">Sayfa 1/1 — 0 kayıt</div>
      <div>
        <button type="button" class="btn btn-sm btn-secondary" id="logNextBtn" disabled>
          <span class="material-symbols-rounded" style="font-size: 1rem;">chevron_right</span>
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Main wrapper: sidebar + content -->
<div class="d-flex flex-grow-1 overflow-hidden">

