<!-- 
  Sol Menü / Kenar Çubuğu (Sidebar)
  
  Uygulamanın ana navigasyon menüsü.
  Tüm modüllere ve diğer uygulamalara erişim sağlar.
  
  Kullanım: $active değişkenini tanımlayarak aktif menü öğesini belirleyin
-->
<aside id="sidebar" class="sidebar">
  <nav class="menu">
    
    <!-- ANASAYFA -->
    <a class="menu-item <?= ($active ?? '') === 'dashboard' ? 'active' : '' ?>" href="/index.php">
      <span class="material-symbols-rounded">space_dashboard</span>
      <span class="label">Anasayfa</span>
    </a>

    <!-- TEFTİŞ MODÜLLERI -->
    <h4 class="menu-group-title">Teftiş</h4>
    
    <!-- Karar Defteri -->
    <a class="menu-item <?= ($active ?? '') === 'karar' ? 'active' : '' ?>" href="/karar.php">
      <span class="material-symbols-rounded">bar_chart_4_bars</span>
      <span class="label">Karar Defteri</span>
    </a>
    
    <!-- İstinaf Defteri -->
    <a class="menu-item <?= ($active ?? '') === 'istinaf' ? 'active' : '' ?>" href="/istinaf.php">
      <span class="material-symbols-rounded">checklist</span>
      <span class="label">İstinaf Defteri</span>
    </a>
    
    <!-- İddianame Değerlendirme -->
    <a class="menu-item <?= ($active ?? '') === 'iddianame' ? 'active' : '' ?>" href="/iddianame.php">
      <span class="material-symbols-rounded">align_space_between</span>
      <span class="label">İddianame Değ.</span>
    </a>

    <!-- KONTROL MODÜLLERI -->
    <h4 class="menu-group-title">Kontrol</h4>
    
    <!-- Harç Tahsil Kontrolü -->
    <a class="menu-item <?= ($active ?? '') === 'harctahsil' ? 'active' : '' ?>" href="/harctahsilkontrol.php">
      <span class="material-symbols-rounded">request_quote</span>
      <span class="label">Harç Tahsil</span>
    </a>
    
    <!-- Kesinleşme Kontrolü -->
    <a class="menu-item <?= ($active ?? '') === 'kesinlesmekontrol' ? 'active' : '' ?>" href="/kesinlesmek.php">
      <span class="material-symbols-rounded">event_available</span>
      <span class="label">Kesinleşme Kontrol</span>
    </a>
    
    <!-- JSON Robot -->
    <a class="menu-item <?= ($active ?? '') === 'jrobot' ? 'active' : '' ?>" href="/jrobot.php">
      <span class="material-symbols-rounded" style="color:#F48FB1">smart_toy</span>
      <span class="label">JSON Robot</span>
    </a>

    <!-- ARAÇLAR -->
    <h4 class="menu-group-title">Araçlar</h4>
    
    <!-- Kesinleşme Hesaplama -->
    <a class="menu-item <?= ($active ?? '') === 'kesinlesme' ? 'active' : '' ?>" href="/kesinlesme.php">
      <span class="material-symbols-rounded">work_history</span>
      <span class="label">Kesinleşme Hesapla</span>
    </a>
    
    <!-- Yargılama Gideri Hesaplama -->
    <a class="menu-item <?= ($active ?? '') === 'yargilama' ? 'active' : '' ?>" href="/yargilamagideri.php">
      <span class="material-symbols-rounded">calculate</span>
      <span class="label">Yargılama Gideri</span>
    </a>

    <!-- DİĞER UYGULAMALARIMIZ -->
    <h4 class="menu-group-title">Diğer Uygulamalarımız</h4>
    <div class="menu">
      
      <!-- 657.com.tr - Devlet Memurları -->
      <a class="menu-item" href="https://657.com.tr/" target="_blank" rel="noopener noreferrer">
        <span class="material-symbols-rounded" aria-hidden="true" style="color:#F44336">badge</span>
        <span class="label">657 - Devlet Memurları</span>
      </a>
      
      <!-- Müdürün Dolabı -->
      <a class="menu-item" href="https://657.com.tr/mudurun-dolabi-adliye-dosya-takip-hatirlatma-programi/" target="_blank" rel="noopener noreferrer">
        <span class="material-symbols-rounded" aria-hidden="true" style="color:#3F51B5">inventory_2</span>
        <span class="label">Müdürün Dolabı</span>
      </a>
      
      <!-- Yargılama Gideri Hesap Makinesi -->
      <a class="menu-item" href="https://657.com.tr/yargilama-gideri-hesap-makinesi/" target="_blank" rel="noopener noreferrer">
        <span class="material-symbols-rounded" aria-hidden="true" style="color:#4CAF50">request_quote</span>
        <span class="label">Yargılama Gideri</span>
      </a>
      
      <!-- Kesinleşme Hesaplama -->
      <a class="menu-item" href="https://657.com.tr/kesinlesme-hesaplama/" target="_blank" rel="noopener noreferrer">
        <span class="material-symbols-rounded" aria-hidden="true" style="color:#FF9800">check_circle</span>
        <span class="label">Kesinleşme Hesapla</span>
      </a>
      
    </div>
  </nav>
</aside>