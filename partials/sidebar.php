<!--
  ========================================
  YAN MENÜ / SIDEBAR (SIDEBAR.PHP)
  ========================================
  Bootstrap 5.3 Offcanvas Component
  Mobilde offcanvas, desktop'ta static sidebar
  ========================================
-->
<!-- Desktop: d-lg-block ile lg ve üstünde görünür, Mobil: offcanvas -->
<aside id="sidebar" class="offcanvas offcanvas-start d-lg-block" tabindex="-1" data-bs-scroll="true" data-bs-backdrop="false" aria-labelledby="sidebarLabel">
  <div class="offcanvas-header border-bottom">
    <div class="d-flex align-items-center gap-2">
      <img src="/assets/img/favicon.svg" alt="Adalet Bakanlığı" width="24" height="24" />
      <h5 class="offcanvas-title mb-0" id="sidebarLabel">Teftiş - 657.com.tr</h5>
    </div>
    <button type="button" class="btn-close d-lg-none" data-bs-dismiss="offcanvas" aria-label="Kapat"></button>
  </div>
  <div class="offcanvas-body p-0">
    <nav class="nav flex-column">
      <!-- ANA SAYFA -->
  <a class="nav-link <?= ($active ?? '')==='dashboard'?'active':'' ?>" href="/index">
        <span class="material-symbols-rounded me-2">space_dashboard</span>
        <span>Anasayfa</span>
      </a>

      <!-- TEFTİŞ GRUBU -->
      <div class="menu-group">
        <h6 class="menu-group-title collapsible px-3 pt-3 pb-2 text-muted text-uppercase small fw-bold">
          <span>Teftiş</span>
          <span class="material-symbols-rounded toggle-icon">expand_more</span>
        </h6>
        <div class="menu-group-items">
          <a class="nav-link <?= ($active ?? '')==='karar'?'active':'' ?>" href="/karar">
            <span class="material-symbols-rounded me-2">bar_chart_4_bars</span>
            <span>Karar Defteri</span>
          </a>
          <a class="nav-link <?= ($active ?? '')==='istinaf'?'active':'' ?>" href="/istinaf">
            <span class="material-symbols-rounded me-2">checklist</span>
            <span>İstinaf Defteri</span>
          </a>
        </div>
      </div>

      <!-- DENETİM CETVELLERİ GRUBU -->
      <div class="menu-group">
        <h6 class="menu-group-title collapsible collapsed px-3 pt-3 pb-2 text-muted text-uppercase small fw-bold">
          <span>Denetim Cetvelleri</span>
          <span class="material-symbols-rounded toggle-icon">expand_more</span>
        </h6>
        <div class="menu-group-items" style="display: none;">
          <a class="nav-link <?= ($active ?? '')==='iddianame'?'active':'' ?>" href="/iddianame">
            <span class="material-symbols-rounded me-2">counter_1</span>
            <span>İddianame Değ.</span>
          </a>
          <a class="nav-link <?= ($active ?? '')==='tensip'?'active':'' ?>" href="/tensip">
            <span class="material-symbols-rounded me-2">counter_2</span>
            <span>Tensip</span>
          </a>
          <a class="nav-link <?= ($active ?? '')==='durusmakacagi'?'active':'' ?>" href="/durusmakacagi">
            <span class="material-symbols-rounded me-2">counter_3</span>
            <span>Duruşma Kaçağı</span>
          </a>
          <a class="nav-link <?= ($active ?? '')==='byu'?'active':'' ?>" href="/byu">
            <span class="material-symbols-rounded me-2">counter_4</span>
            <span>Basit Yargılama</span>
          </a>
          <a class="nav-link <?= ($active ?? '')==='gerekcelikarar'?'active':'' ?>" href="/gerekcelikarar">
            <span class="material-symbols-rounded me-2">counter_5</span>
            <span>Gerekçeli Karar</span>
          </a>
          <a class="nav-link <?= ($active ?? '')==='kanun_yolu'?'active':'' ?>" href="/kanunyolu">
            <span class="material-symbols-rounded me-2">counter_6</span>
            <span>Kanun Yolu</span>
          </a>
          <a class="nav-link <?= ($active ?? '')==='kesinlestirme'?'active':'' ?>" href="/kesinlestirme">
            <span class="material-symbols-rounded me-2">counter_7</span>
            <span>Kesinleştirme/İnfaz</span>
          </a>
        </div>
      </div>

      <!-- KONTROL GRUBU -->
      <div class="menu-group">
        <h6 class="menu-group-title collapsible px-3 pt-3 pb-2 text-muted text-uppercase small fw-bold">
          <span>Kontrol</span>
          <span class="material-symbols-rounded toggle-icon">expand_more</span>
        </h6>
        <div class="menu-group-items">
          <a class="nav-link <?= ($active ?? '')==='harctahsil'?'active':'' ?>" href="/harctahsilkontrol">
            <span class="material-symbols-rounded me-2">request_quote</span>
            <span>Harç Tahsil</span>
          </a>
          <a class="nav-link <?= ($active ?? '')==='kesinlesmekontrol'?'active':'' ?>" href="/kesinlesmek">
            <span class="material-symbols-rounded me-2">event_available</span>
            <span>Kesinleşme Kontrol</span>
          </a>
          <a class="nav-link <?= ($active ?? '')==='jrobot'?'active':'' ?>" href="/jrobot">
            <span class="material-symbols-rounded me-2" style="color:#F48FB1">smart_toy</span>
            <span>JSON Robot</span>
          </a>
        </div>
      </div>

      <!-- ARAÇLAR GRUBU -->
      <div class="menu-group">
        <h6 class="menu-group-title collapsible px-3 pt-3 pb-2 text-muted text-uppercase small fw-bold">
          <span>Araçlar</span>
          <span class="material-symbols-rounded toggle-icon">expand_more</span>
        </h6>
        <div class="menu-group-items">
          <a class="nav-link <?= ($active ?? '')==='kesinlesme'?'active':'' ?>" href="/kesinlesme">
            <span class="material-symbols-rounded me-2">work_history</span>
            <span>Kesinleşme Hesapla</span>
          </a>
          <a class="nav-link <?= ($active ?? '')==='yargilama'?'active':'' ?>" href="/yargilamagideri">
            <span class="material-symbols-rounded me-2">calculate</span>
            <span>Yargılama Gideri</span>
          </a>
        </div>
      </div>

      <!-- DİĞER UYGULAMALAR -->
      <div class="menu-group">
        <h6 class="menu-group-title collapsible px-3 pt-3 pb-2 text-muted text-uppercase small fw-bold">
          <span>Diğer Uygulamalarımız</span>
          <span class="material-symbols-rounded toggle-icon">expand_more</span>
        </h6>
        <div class="menu-group-items">
          <a class="nav-link" href="https://657.com.tr/" target="_blank" rel="noopener noreferrer">
            <span class="material-symbols-rounded me-2" style="color:#F44336">badge</span>
            <span>657 - Devlet Memurları</span>
          </a>
          <a class="nav-link" href="https://657.com.tr/mudurun-dolabi-adliye-dosya-takip-hatirlatma-programi/" target="_blank" rel="noopener noreferrer">
            <span class="material-symbols-rounded me-2" style="color:#3F51B5">inventory_2</span>
            <span>Müdürün Dolabı</span>
          </a>
          <a class="nav-link" href="https://657.com.tr/yargilama-gideri-hesap-makinesi/" target="_blank" rel="noopener noreferrer">
            <span class="material-symbols-rounded me-2" style="color:#4CAF50">request_quote</span>
            <span>Yargılama Gideri</span>
          </a>
          <a class="nav-link" href="https://657.com.tr/kesinlesme-hesaplama/" target="_blank" rel="noopener noreferrer">
            <span class="material-symbols-rounded me-2" style="color:#FF9800">check_circle</span>
            <span>Kesinleşme Hesapla</span>
          </a>
        </div>
      </div>
    </nav>
  </div>
</aside>

