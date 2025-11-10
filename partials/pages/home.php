<?php
  /**
    * ========================================
    * Adliye Teftiş Yardımcısı - Ana Sayfa
    * ========================================
    */
   
  // Sayfa başlığını ayarla (header.php'de kullanılacak)
  $pageTitle = "Panel";
  $active = "dashboard";

?>

<!-- Ana içerik alanı - Bootstrap Container -->
<main class="flex-grow-1 overflow-auto">
  <div class="container-fluid py-4">
    <!-- Sayfa başlığı -->
    <div class="mb-4">
      <h1 class="h2 mb-1">Panel</h1>
      <p class="text-muted mb-0">Uygulama güncellemeleri ve e-posta bırakma kutusu</p>
    </div>
    
    <div class="row g-4">
      
      <!-- E-POSTA BIRAKMA KUTUSU - Ortalanmış 3 col -->
      <div class="col-12">
        <div class="row justify-content-center">
          <div class="col-12 col-md-6 col-lg-4 col-xl-3">
            <div class="card h-100" id="mailDropBox">
              <div class="card-body">
                <div class="d-flex align-items-start gap-3 mb-3">
                  <div class="fs-1" aria-hidden="true">✉️</div>
                  <div class="flex-grow-1">
                    <h5 class="card-title mb-1" id="mailDropTitle">E-posta Adresini Bırak</h5>
                    <p class="card-text text-muted small mb-0">
                      Buraya e-posta adresini <strong>bırak</strong>, sana site adresini mail atalım.
                    </p>
                  </div>
                </div>
                
                <!-- E-posta giriş formu -->
                <div class="mb-3">
                  <div class="input-group">
                    <input 
                      id="mailDropInput" 
                      type="email" 
                      class="form-control" 
                      placeholder="ab139329@adalet.gov.tr" 
                      autocomplete="email" 
                      aria-label="E-posta adresi">
                    <button id="mailDropSendBtn" class="btn btn-primary" type="button" disabled>
                      Gönder
                    </button>
                  </div>
                </div>
                
                <!-- Toast mesajları -->
                <div id="mailDropToast" class="alert d-none" role="alert"></div>
                
                <!-- Honeypot -->
                <input 
                  id="mailHp" 
                  type="text" 
                  class="visually-hidden"
                  aria-hidden="true" 
                  tabindex="-1" 
                  autocomplete="off">
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- HAKKINDA KARTI - Alt satır, tam genişlik -->
      <div class="col-12">
        <div class="card" id="aboutCard">
          <div class="card-body">
            <h5 class="card-title d-flex align-items-center gap-2 mb-3" id="aboutTitle">
              <span class="material-symbols-rounded">info</span>
              Hakkında
            </h5>
            <div id="aboutContent" class="card-text">
              <p class="text-muted mb-0">Yükleniyor...</p>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</main>

<!-- About loader: dynamically loads README.md into the About card -->
<script defer src="/dist/about-loader.js"></script>