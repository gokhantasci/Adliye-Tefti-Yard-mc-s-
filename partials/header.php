<!--
  ========================================
  ORTAK BAŞLIK ŞABLONU (HEADER.PHP)
  ========================================
  Tüm sayfalarda kullanılan ortak HTML başlığı ve meta bilgileri
  Bu dosya her PHP sayfasının başında include edilir
  ========================================
-->
<!doctype html>
<html lang="tr">
<head>
  <!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-XXXXXXX');</script>
  <!-- End Google Tag Manager -->
  
  <!-- Karakter seti: Türkçe karakterler için UTF-8 -->
  <meta charset="utf-8" />
  
  <!-- Responsive tasarım için viewport ayarı -->
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  
  <!-- SEO ve sosyal medya meta etiketleri -->
  <meta name="description" content="Adliye teftiş işlemlerini kolaylaştırmak ve hızlandırmak için geliştirilmiş kapsamlı bir web uygulaması" />
  <meta name="keywords" content="adliye, teftiş, judiciary, inspection, turkish" />
  <meta name="author" content="Gökhan TAŞÇI" />
  
  <!-- PWA Manifest -->
  <link rel="manifest" href="/manifest.json?v=3" />
  
  <!-- PWA Theme colors -->
  <meta name="theme-color" content="#F44336" media="(prefers-color-scheme: dark)" />
  <meta name="theme-color" content="#5b6cff" media="(prefers-color-scheme: light)" />
  
  <!-- Apple specific meta tags for PWA -->
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
  <meta name="apple-mobile-web-app-title" content="Teftiş" />
  <link rel="apple-touch-icon" href="/assets/img/favicon.svg" />
  
  <!-- Safari pinned tab icon -->
  <link rel="mask-icon" href="/assets/img/favicon.svg" color="#F44336" />
  
  <!-- Microsoft Tiles -->
  <meta name="msapplication-TileColor" content="#F44336" />
  <meta name="msapplication-TileImage" content="/assets/img/favicon.svg" />
  
  <!-- Mobile optimization -->
  <meta name="format-detection" content="telephone=no" />
  <meta name="mobile-web-app-capable" content="yes" />
  
  <?php
    /**
     * Sayfa başlığı oluşturma
     * 
     * Her sayfa kendi $pageTitle değişkenini set eder
     * Format: "Sayfa Adı - Teftiş | 657.com.tr"
     * Örnek: "İddianame Değerlendirme - Teftiş | 657.com.tr"
     */
    $fullTitle = '';
    if (!empty($pageTitle)) {
      // XSS koruması için htmlspecialchars kullanılıyor
      $fullTitle = htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') . ' - Teftiş | 657.com.tr';
    } else {
      // Varsayılan başlık (ana sayfa için)
      $fullTitle = 'Teftiş | 657.com.tr';
    }
  ?>
  <title><?= $fullTitle; ?></title>
  
  <!-- Koyu/Açık tema desteği için color-scheme meta etiketi -->
  <meta name="color-scheme" content="light dark" />
  
  <!-- RSS/Atom Feed Auto-discovery -->
  <link rel="alternate" type="application/rss+xml" title="Teftiş Haberleri (RSS)" href="/api/feed.php" />
  <link rel="alternate" type="application/atom+xml" title="Teftiş Haberleri (Atom)" href="/api/feed.php?format=atom" />
  
  <!-- Favicon: SVG formatında responsive ikon -->
  <link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml" />
  
  <!-- 
    Google Fonts ve Material Symbols yükleme
    - Inter: Modern, okunabilir sans-serif font ailesi
    - Material Symbols: Google'ın ikon seti
  -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:FILL@0..1" rel="stylesheet" />
  
  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Custom CSS (minimal) -->
  <link rel="stylesheet" href="/assets/css/style.css?v=34&t=<?php echo time(); ?>" />
  
  <!-- Preload critical resources for better performance -->
  <link rel="preload" href="/assets/js/utils.js?v=1" as="script" />
  
  <!-- 
    JavaScript dosyaları
    - utils.js: Ortak yardımcı fonksiyonlar (önce yüklenmeli)
    - app.js: Ana uygulama mantığı (tema, notlar, modaller)
    - xlsx.min.js: Excel dosya işleme kütüphanesi
    - open-modal-bridge.js: Modal açma yardımcı fonksiyonları
    - jQuery: Bazı eski modüller için gerekli
  -->
  <script src="/assets/js/utils.js?v=1"></script>
  <script defer src="/assets/js/theme-manager.js?v=1&t=<?php echo time(); ?>"></script>
  <script defer src="/assets/js/app.js?v=21&t=<?php echo time(); ?>"></script>
  <script defer src="/assets/js/footer-slider.js?v=1"></script>
  <!-- SheetJS (xlsx) - use CDN to ensure library is available in containerized environments -->
  <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
  <script defer src="/assets/js/open-modal-bridge.js?v=1"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  
  <!-- Service Worker Registration for PWA -->
  <script>
    // Service Worker geçici olarak devre dışı - cache sorunları için
    // Eski SW'leri temizle
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.getRegistrations().then(function(registrations) {
        for(let registration of registrations) {
          registration.unregister();
        }
      });
    }
  </script>
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-XXXXXXX"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<!-- Accessibility: Skip to main content link -->
<a href="#main-content" class="visually-hidden-focusable">Ana içeriğe geç</a>

<!-- Partials (navbar, sidebar, main, footer) buraya include edilecek -->
