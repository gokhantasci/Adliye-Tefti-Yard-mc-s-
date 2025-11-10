<!--
  ========================================
  ÜST GEZİNME ÇUBUĞU (NAVBAR.PHP)
  ========================================
  Adalet Bakanlığı Teması
  ========================================
-->
<nav class="navbar navbar-expand-xl navbar-dark sticky-top">
  <div class="container-fluid">
    <!-- Mobile toggle button - left side on mobile only -->
    <button class="navbar-toggler border-0 me-2 d-xl-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Menüyü aç/kapat">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <!-- Brand/Logo - left aligned -->
    <a class="navbar-brand d-flex align-items-center gap-2 mb-0 me-3" href="/index.php">
      <img src="/assets/img/favicon.svg" alt="Adalet Bakanlığı" width="28" height="28" />
      <span class="navbar-brand-text">Teftiş - 657.com.tr</span>
    </a>
    
    <!-- Desktop menu - centered -->
    <div class="d-none d-xl-flex align-items-center justify-content-center flex-grow-1">
      <ul class="navbar-nav">
        <!-- Ana Sayfa -->
        <li class="nav-item">
          <a class="nav-link <?= ($active ?? '')==='dashboard'?'active':'' ?>" href="/index">
            <span class="material-symbols-rounded me-1" style="font-size:18px;vertical-align:middle;">space_dashboard</span>
            Anasayfa
          </a>
        </li>
        
        <!-- Teftiş Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="material-symbols-rounded me-1" style="font-size:18px;vertical-align:middle;">assignment</span>
            Teftiş
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item <?= ($active ?? '')==='karar'?'active':'' ?>" href="/karar">
              <span class="material-symbols-rounded me-2" style="font-size:18px;">bar_chart_4_bars</span>
              Karar Defteri
            </a></li>
            <li><a class="dropdown-item <?= ($active ?? '')==='istinaf'?'active':'' ?>" href="/istinaf">
              <span class="material-symbols-rounded me-2" style="font-size:18px;">checklist</span>
              İstinaf Defteri
            </a></li>
          </ul>
        </li>
        
        <!-- Denetim Cetvelleri Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="material-symbols-rounded me-1" style="font-size:18px;vertical-align:middle;">fact_check</span>
            Denetim Cetvelleri
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item <?= ($active ?? '')==='iddianame'?'active':'' ?>" href="/iddianame">
              <span class="material-symbols-rounded me-2" style="font-size:18px;">counter_1</span>
              İddianame Değ.
            </a></li>
            <li><a class="dropdown-item <?= ($active ?? '')==='tensip'?'active':'' ?>" href="/tensip">
              <span class="material-symbols-rounded me-2" style="font-size:18px;">counter_2</span>
              Tensip
            </a></li>
            <li><a class="dropdown-item <?= ($active ?? '')==='durusmakacagi'?'active':'' ?>" href="/durusmakacagi">
              <span class="material-symbols-rounded me-2" style="font-size:18px;">counter_3</span>
              Duruşma Kaçağı
            </a></li>
            <li><a class="dropdown-item <?= ($active ?? '')==='byu'?'active':'' ?>" href="/byu">
              <span class="material-symbols-rounded me-2" style="font-size:18px;">counter_4</span>
              Basit Yargılama
            </a></li>
            <li><a class="dropdown-item <?= ($active ?? '')==='gerekcelikarar'?'active':'' ?>" href="/gerekcelikarar">
              <span class="material-symbols-rounded me-2" style="font-size:18px;">counter_5</span>
              Gerekçeli Karar
            </a></li>
            <li><a class="dropdown-item <?= ($active ?? '')==='kanun_yolu'?'active':'' ?>" href="/kanunyolu">
              <span class="material-symbols-rounded me-2" style="font-size:18px;">counter_6</span>
              Kanun Yolu
            </a></li>
            <li><a class="dropdown-item <?= ($active ?? '')==='kesinlestirme'?'active':'' ?>" href="/kesinlestirme">
              <span class="material-symbols-rounded me-2" style="font-size:18px;">counter_7</span>
              Kesinleştirme/İnfaz
            </a></li>
          </ul>
        </li>
        
        <!-- Kontrol Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="material-symbols-rounded me-1" style="font-size:18px;vertical-align:middle;">verified</span>
            Kontrol
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item <?= ($active ?? '')==='harctahsil'?'active':'' ?>" href="/harctahsilkontrol">
              <span class="material-symbols-rounded me-2" style="font-size:18px;">request_quote</span>
              Harç Tahsil
            </a></li>
            <li><a class="dropdown-item <?= ($active ?? '')==='kesinlesmekontrol'?'active':'' ?>" href="/kesinlesmek">
              <span class="material-symbols-rounded me-2" style="font-size:18px;">event_available</span>
              Kesinleşme Kontrol
            </a></li>
            <li><a class="dropdown-item <?= ($active ?? '')==='jrobot'?'active':'' ?>" href="/jrobot">
              <span class="material-symbols-rounded me-2" style="font-size:18px;color:#F48FB1;">smart_toy</span>
              JSON Robot
            </a></li>
          </ul>
        </li>
        
        <!-- Araçlar Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="material-symbols-rounded me-1" style="font-size:18px;vertical-align:middle;">build</span>
            Araçlar
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item <?= ($active ?? '')==='kesinlesme'?'active':'' ?>" href="/kesinlesme">
              <span class="material-symbols-rounded me-2" style="font-size:18px;">event</span>
              Kesinleşme Tarihi
            </a></li>
            <li><a class="dropdown-item <?= ($active ?? '')==='yargilamagideri'?'active':'' ?>" href="/yargilamagideri">
              <span class="material-symbols-rounded me-2" style="font-size:18px;">calculate</span>
              Yargılama Gideri
            </a></li>
            <li><a class="dropdown-item <?= ($active ?? '')==='temyiz'?'active':'' ?>" href="/temyiz">
              <span class="material-symbols-rounded me-2" style="font-size:18px;">gavel</span>
              Temyiz Süresi
            </a></li>
          </ul>
        </li>
      </ul>
    </div>
    
    <!-- Right side: Action buttons -->
    <div class="d-flex align-items-center gap-1 ms-auto">


      <!-- Haberler Dropdown (Teftis.json) -->
      <div class="position-relative">
        <button id="newsDropdownToggle" class="btn btn-link text-white p-2 position-relative" aria-label="Uygulama güncellemelerini göster" title="Uygulama Güncellemeleri">
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
            <button type="button" class="dropdown-close-btn" id="newsDropdownCloseBtn" aria-label="Kapat">
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
      
      <!-- Haber Kutusu (News.json) - Otomatik gösterim, buton gizli -->
      <div class="position-relative" style="display: none;">
        <button id="newsToggle" class="btn btn-link text-white p-2 position-relative" aria-label="Duyurular" title="Duyurular">
          <span class="material-symbols-rounded">campaign</span>
          <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" style="width: 10px; height: 10px;">
            <span class="visually-hidden">Yeni duyuru</span>
          </span>
        </button>
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
      
      <!-- Renk Soluklaştırma (Desaturate) -->
      <div class="position-relative">
        <button id="desaturateToggle" class="btn btn-link text-white p-2" aria-label="Renkleri soluklaştır" title="Renk Soluklaştırma" hidden>
          <span class="material-symbols-rounded">palette</span>
        </button>
      </div>
    </div>
  </div>
</nav>

<!-- Mobile Menu Collapse - Below Navbar -->
<div class="collapse navbar-collapse d-xl-none" id="navbarNav" style="position: sticky; top: 60px; z-index: 1020;">
  <div class="container-fluid">
    <ul class="navbar-nav py-2">
      <!-- Ana Sayfa -->
      <li class="nav-item">
        <a class="nav-link <?= ($active ?? '')==='dashboard'?'active':'' ?>" href="/index">
          <span class="material-symbols-rounded me-2" style="font-size:18px;vertical-align:middle;">space_dashboard</span>
          Anasayfa
        </a>
      </li>
      
      <!-- Teftiş -->
      <li class="nav-item">
        <h6 class="text-white-50 px-3 pt-3 pb-1 mb-0 text-uppercase small fw-bold">Teftiş</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= ($active ?? '')==='karar'?'active':'' ?>" href="/karar">
          <span class="material-symbols-rounded me-2" style="font-size:18px;">bar_chart_4_bars</span>
          Karar Defteri
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= ($active ?? '')==='istinaf'?'active':'' ?>" href="/istinaf">
          <span class="material-symbols-rounded me-2" style="font-size:18px;">checklist</span>
          İstinaf Defteri
        </a>
      </li>
      
      <!-- Denetim Cetvelleri -->
      <li class="nav-item">
        <h6 class="text-white-50 px-3 pt-3 pb-1 mb-0 text-uppercase small fw-bold">Denetim Cetvelleri</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= ($active ?? '')==='iddianame'?'active':'' ?>" href="/iddianame">
          <span class="material-symbols-rounded me-2" style="font-size:18px;">counter_1</span>
          İddianame Değ.
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= ($active ?? '')==='tensip'?'active':'' ?>" href="/tensip">
          <span class="material-symbols-rounded me-2" style="font-size:18px;">counter_2</span>
          Tensip
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= ($active ?? '')==='durusmakacagi'?'active':'' ?>" href="/durusmakacagi">
          <span class="material-symbols-rounded me-2" style="font-size:18px;">counter_3</span>
          Duruşma Kaçağı
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= ($active ?? '')==='byu'?'active':'' ?>" href="/byu">
          <span class="material-symbols-rounded me-2" style="font-size:18px;">counter_4</span>
          Basit Yargılama
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= ($active ?? '')==='gerekcelikarar'?'active':'' ?>" href="/gerekcelikarar">
          <span class="material-symbols-rounded me-2" style="font-size:18px;">counter_5</span>
          Gerekçeli Karar
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= ($active ?? '')==='kanun_yolu'?'active':'' ?>" href="/kanunyolu">
          <span class="material-symbols-rounded me-2" style="font-size:18px;">counter_6</span>
          Kanun Yolu
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= ($active ?? '')==='kesinlestirme'?'active':'' ?>" href="/kesinlestirme">
          <span class="material-symbols-rounded me-2" style="font-size:18px;">counter_7</span>
          Kesinleştirme/İnfaz
        </a>
      </li>
      
      <!-- Kontrol -->
      <li class="nav-item">
        <h6 class="text-white-50 px-3 pt-3 pb-1 mb-0 text-uppercase small fw-bold">Kontrol</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= ($active ?? '')==='harctahsil'?'active':'' ?>" href="/harctahsilkontrol">
          <span class="material-symbols-rounded me-2" style="font-size:18px;">request_quote</span>
          Harç Tahsil
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= ($active ?? '')==='kesinlesmekontrol'?'active':'' ?>" href="/kesinlesmek">
          <span class="material-symbols-rounded me-2" style="font-size:18px;">event_available</span>
          Kesinleşme Kontrol
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= ($active ?? '')==='jrobot'?'active':'' ?>" href="/jrobot">
          <span class="material-symbols-rounded me-2" style="font-size:18px;color:#F48FB1;">smart_toy</span>
          JSON Robot
        </a>
      </li>
      
      <!-- Araçlar -->
      <li class="nav-item">
        <h6 class="text-white-50 px-3 pt-3 pb-1 mb-0 text-uppercase small fw-bold">Araçlar</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= ($active ?? '')==='kesinlesme'?'active':'' ?>" href="/kesinlesme">
          <span class="material-symbols-rounded me-2" style="font-size:18px;">event</span>
          Kesinleşme Tarihi
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= ($active ?? '')==='yargilamagideri'?'active':'' ?>" href="/yargilamagideri">
          <span class="material-symbols-rounded me-2" style="font-size:18px;">calculate</span>
          Yargılama Gideri
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= ($active ?? '')==='temyiz'?'active':'' ?>" href="/temyiz">
          <span class="material-symbols-rounded me-2" style="font-size:18px;">gavel</span>
          Temyiz Süresi
        </a>
      </li>
    </ul>
  </div>
</div>

<!-- Haber Kutusu Paneli (Top Right - Fixed Position) -->
<div id="newsPanel" class="position-fixed bg-white shadow-lg rounded" style="top: 70px; right: 20px; width: 400px; max-height: 500px; z-index: 1040; display: none; overflow: hidden;" hidden>
  <div class="d-flex align-items-center justify-content-between p-3 border-bottom" style="background: var(--adalet-primary); color: white;">
    <div class="d-flex align-items-center gap-2">
      <span class="material-symbols-rounded">campaign</span>
      <strong>Haberler</strong>
    </div>
    <button type="button" class="btn-close btn-close-white" id="newsCloseBtn" aria-label="Kapat"></button>
  </div>
  <div class="p-3" id="newsPanelBody" style="max-height: 400px; overflow-y: auto; background: var(--adalet-bg); color: var(--adalet-text);">
    <div class="text-center text-muted py-4">
      <div class="spinner-border spinner-border-sm mb-2" role="status">
        <span class="visually-hidden">Yükleniyor...</span>
      </div>
      <div>Haberler yükleniyor...</div>
    </div>
  </div>
</div>

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

<!-- Main wrapper: content only (no sidebar) -->
<div class="flex-grow-1">

