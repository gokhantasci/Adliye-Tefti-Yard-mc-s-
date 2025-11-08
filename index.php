<?php
/**
 * Panel / Anasayfa
 * 
 * Ana sayfa - Uygulama güncellemeleri ve e-posta bildirimi bölümü
 * 
 * @package AdliyeTeftis
 * @author  Gökhan TAŞÇI
 */

// Aktif menü öğesini belirle
$active = "dashboard";

// Ortak sayfa bileşenlerini dahil et
include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/navbar.php";
include __DIR__ . "/partials/sidebar.php";
?>

<!-- Ana içerik alanı -->
<main class="content">
  <!-- Sayfa başlığı -->
  <div class="page-header">
    <h1>Panel</h1>
    <p class="muted">Uygulama güncellemeleri ve e-posta bırakma kutusu</p>
  </div>

  <!-- Kart bölümü (2 sütunlu) -->
  <section class="cards cards--2">
    
    <!-- UYGULAMA GÜNCELLEMELERİ / HABER KARTI -->
    <article class="card news-drop" id="newsCard" role="region" aria-labelledby="newsTitle">
      <div class="news-drop__body">
        <!-- İkon -->
        <div class="news-drop__icon" aria-hidden="true">📰</div>
        
        <!-- Haber içeriği -->
        <div class="news-drop__texts">
          <h3 id="newsTitle" class="news-drop__title">Uygulama Güncellemeleri / Haber</h3>
          <p class="news-drop__hint">Platformdaki duyuru ve değişiklikler.</p>
          
          <!-- Haber içerik alanı -->
          <div class="news-drop__content">
            <!-- Haber meta bilgisi (dinamik) -->
            <div id="newsMeta" class="news-meta muted" aria-live="polite"></div>
            
            <!-- Haber listesi (dinamik olarak doldurulur) -->
            <div id="newsList" class="news-list"></div>
          </div>
        </div>
      </div>
    </article>

    <!-- E-POSTA BIRAKMA KUTUSU KARTI -->
    <article class="card mail-drop" id="mailDropBox" role="region" aria-labelledby="mailDropTitle">
      <div class="mail-drop__body">
        <!-- İkon -->
        <div class="mail-drop__icon" aria-hidden="true">✉️</div>
        
        <!-- E-posta formu -->
        <div class="mail-drop__texts">
          <h3 id="mailDropTitle" class="mail-drop__title">E-posta Adresini bırak</h3>
          <p class="mail-drop__hint">Buraya e-posta adresini <b>bırak</b>, sana site adresini mail atalım.</p>
          
          <!-- E-posta giriş alanı -->
          <div class="mail-drop__input">
            <input 
              id="mailDropInput" 
              type="email" 
              placeholder="ab139329@adalet.gov.tr" 
              autocomplete="email" 
              inputmode="email" 
              aria-label="E-posta adresi"
            >
            <button id="mailDropSendBtn" class="btn btn-primary" type="button" disabled>
              Mesajı Gönder
            </button>
          </div>
          
          <!-- Bildirim alanı (başarı/hata mesajları) -->
          <div id="mailDropToast" class="mail-drop__toast" aria-live="polite"></div>
          
          <!-- Honeypot alanı (spam koruması) -->
          <input 
            id="mailHp" 
            class="hp" 
            type="text" 
            aria-hidden="true" 
            tabindex="-1" 
            autocomplete="off" 
            style="position:absolute;left:-9999px;width:1px;height:1px;opacity:0;" 
          />
          
          <!-- Önizleme alanı (geliştirme için) -->
          <div class="mail-drop__preview">
            <pre id="mailPreview" class="mail-drop__pre" aria-hidden="true" hidden></pre>
          </div>
          
          <!-- Sayfalama (haberler için) -->
          <nav id="newsPager" class="pager" role="navigation" aria-label="Haber sayfalama"></nav>
        </div>
      </div>
    </article>
    
  </section>
</main>

<?php 
// Sayfa alt bilgisini dahil et
include __DIR__ . "/partials/footer.php"; 
?>
