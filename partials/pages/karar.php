<?php
/* Karar Defteri – Excel yükle, KPI ve ayarlar */
$pageTitle = "Karar Defteri";
$active = "karar";
?>


		<!-- Ana içerik alanı -->
<main class="flex-grow-1 overflow-auto">
  <div class="container-fluid py-4">
    
    <!-- Sayfa başlığı -->
    <div class="mb-4">
      <h1 class="h2 mb-1">Karar Defteri Kontrolü</h1>
      <p class="text-muted mb-0">Yüklediğiniz tabloyu işler ve dosya bazlı karar sayılarını hazırlar.</p>
    </div>

    <div class="row g-4">
      
      <!-- Sol Kolon - KPIs + Rapor -->
      <div class="col-12 col-xl-8">
        
        <!-- KPI Kartları -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3 mb-4">
          <!-- Toplam Kayıt -->
          <div class="col">
            <div class="card h-100">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <span class="text-muted small">Toplam Kayıt</span>
                  <span class="material-symbols-rounded text-muted">work</span>
                </div>
                <div class="h3 mb-0 fw-bold" id="kpiTotal">0</div>
              </div>
            </div>
          </div>
          
          <!-- Hakim Sayısı -->
          <div class="col">
            <div class="card h-100">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <span class="text-muted small">Hakim Sayısı</span>
                  <span class="material-symbols-rounded text-muted">groups</span>
                </div>
                <div class="h3 mb-0 fw-bold" id="kpiHakim">0</div>
              </div>
            </div>
          </div>
          
          <!-- Savcı Sayısı -->
          <div class="col">
            <div class="card h-100">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <span class="text-muted small">Savcı Sayısı</span>
                  <span class="material-symbols-rounded text-muted">lock_person</span>
                </div>
                <div class="h3 mb-0 fw-bold" id="kpiSavci">0</div>
              </div>
            </div>
          </div>
          
          <!-- İşlem Sayısı -->
          <div class="col">
            <div class="card h-100">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <span class="text-muted small">İşlem Sayısı</span>
                  <span class="material-symbols-rounded text-muted">contract_edit</span>
                </div>
                <div class="h3 mb-0 fw-bold" id="kpiIslem">0</div>
                <small class="text-muted">28/10/2025 tarihinden bugüne</small>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Rapor Alanı (Dinamik) -->
        <div id="reportContainer"></div>
        
      </div>
      
      <!-- Sağ Kolon - Upload & Settings -->
      <div class="col-12 col-xl-4">
        
        <!-- Dikkat Alert -->
        <div class="alert alert-info d-flex align-items-start gap-2 mb-3" role="alert">
          <span class="material-symbols-rounded">info</span>
          <div>
            <strong class="d-block mb-1">Dikkat</strong>
            <small>Bu sayfayı kullanabilmek için UYAP > Raporlar > Defterler > Defter Sorgu > Karar Defterini Excel formatında ilgili tarihleri kapsayacak şekilde indirip, tümünü indirdikten sonra toplu olarak aşağıya yükleyebilirsiniz</small>
          </div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Kapat"></button>
        </div>
        
        <!-- Excel Yükle Card -->
        <div class="card mb-3">
          <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
              <span class="material-symbols-rounded">upload</span>
              <strong>Excel Yükle</strong>
            </div>
            <button class="btn btn-primary btn-sm" id="run" type="button">
              <span class="material-symbols-rounded me-1" style="font-size: 1rem;">calculate</span>
              Hesapla
            </button>
          </div>
          <div class="card-body">
            <form onsubmit="return false;">
              <div id="dropZone" class="border border-2 border-dashed rounded p-4 text-center" style="border-color: var(--adalet-border); transition: all 0.3s ease;" tabindex="0" aria-label="Excel yükleme alanı">
                <p class="mb-2">Dosyayı buraya sürükleyip bırakın</p>
                <p class="text-muted small mb-3">veya</p>
                <label for="excelInput" class="btn btn-outline-primary">
                  <span class="material-symbols-rounded me-1" style="font-size: 1rem;">file_upload</span>
                  Excel Seç
                </label>
                <input type="file" id="excelInput" accept=".xls,.xlsx" hidden multiple>
                <div class="mt-3">
                  <small class="text-muted">İzin verilen türler: <strong>.xls</strong>, <strong>.xlsx</strong></small>
                </div>
              </div>
            </form>
          </div>
        </div>
        
        <!-- Ayarlar Card -->
        <div class="card">
          <div class="card-header">
            <div class="d-flex align-items-center gap-2">
              <span class="material-symbols-rounded">settings</span>
              <strong>Ayarlar</strong>
            </div>
          </div>
          <div class="card-body">
            
            <!-- Hakim/Başkan Row -->
            <div class="row g-2 mb-3">
              <div class="col-4">
                <label for="col_i" class="form-label small mb-1">Hakim/Başkan (I)</label>
                <input id="col_i" name="col_i" type="text" class="form-control form-control-sm" value="I">
              </div>
              <div class="col-4">
                <label for="col_j" class="form-label small mb-1">Üye 1 (J)</label>
                <input id="col_j" name="col_j" type="text" class="form-control form-control-sm" value="J">
              </div>
              <div class="col-4">
                <label for="col_k" class="form-label small mb-1">Üye 2 (K)</label>
                <input id="col_k" name="col_k" type="text" class="form-control form-control-sm" value="K">
              </div>
            </div>
            
            <!-- Mahkumiyet Row -->
            <div class="row g-2 mb-3">
              <div class="col-4">
                <label for="col_o" class="form-label small mb-1">Mahkumiyet (O)</label>
                <input id="col_o" name="col_o" type="text" class="form-control form-control-sm" value="O">
              </div>
              <div class="col-4">
                <label for="col_p" class="form-label small mb-1">HAGB (P)</label>
                <input id="col_p" name="col_p" type="text" class="form-control form-control-sm" value="P">
              </div>
              <div class="col-4">
                <label for="col_t" class="form-label small mb-1">Gör/Yet/Birleş (T)</label>
                <input id="col_t" name="col_t" type="text" class="form-control form-control-sm" value="T">
              </div>
            </div>
            
            <!-- Beraat Row -->
            <div class="row g-2 mb-3">
              <div class="col-4">
                <label for="col_m" class="form-label small mb-1">Beraat (M)</label>
                <input id="col_m" name="col_m" type="text" class="form-control form-control-sm" value="M">
              </div>
              <div class="col-4">
                <label for="col_q" class="form-label small mb-1">Red (Q)</label>
                <input id="col_q" name="col_q" type="text" class="form-control form-control-sm" value="Q">
              </div>
              <div class="col-4">
                <label for="col_z" class="form-label small mb-1">Tazminat (Z)</label>
                <input id="col_z" name="col_z" type="text" class="form-control form-control-sm" value="Z">
              </div>
            </div>
            
            <!-- C.Savcısı -->
            <div class="mb-3">
              <label for="col_l" class="form-label small mb-1">C.Savcısı (L)</label>
              <input id="col_l" name="col_l" type="text" class="form-control form-control-sm" value="L">
            </div>
            
            <!-- Kaydet Butonu -->
            <button class="btn btn-secondary btn-sm w-100" id="saveBtn" type="button">
              Ayarları Kaydet
            </button>
            
          </div>
        </div>
        
      </div>
      
    </div>

	</div>
	
	<!-- Scroll to Top Button -->
	<button id="scrollToTop" class="scroll-to-top" aria-label="Yukarı git" title="Yukarı git">
		<span class="material-symbols-rounded">arrow_upward</span>
	</button>
	
</main>

<!-- Page Scripts -->
<script>
console.log('[KARAR PAGE] Sayfa yüklendi');
console.log('[KARAR PAGE] XLSX mevcut mu?', typeof XLSX !== 'undefined');

// XLSX yüklenmesini bekle
(function checkXLSX() {
  if (typeof XLSX !== 'undefined') {
    console.log('[KARAR PAGE] XLSX hazır, script\'ler yükleniyor');
    
    // Karar KPI script'ini yükle
    const script1 = document.createElement('script');
    script1.src = '/assets/js/karar-excel-kpis.js?v=3';
    script1.onload = function() {
      console.log('[KARAR PAGE] karar-excel-kpis.js yüklendi');
    };
    script1.onerror = function() {
      console.error('[KARAR PAGE] karar-excel-kpis.js yüklenemedi!');
    };
    document.body.appendChild(script1);
    
    // Diğer script'ler
    const scripts = [
      '/assets/js/modal-click.js',
      '/assets/js/g-global.js?v=1',
      '/assets/js/modal-click.compat.js?v=1'
    ];
    
    scripts.forEach(function(src) {
      const script = document.createElement('script');
      script.src = src;
      document.body.appendChild(script);
    });
  } else {
    console.warn('[KARAR PAGE] XLSX henüz hazır değil, 100ms sonra tekrar denenecek');
    setTimeout(checkXLSX, 100);
  }
})();
</script>
