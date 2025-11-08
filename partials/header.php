<!-- 
  Ortak Başlık Şablonu
  
  Bu şablon tüm sayfalar için ortak HTML başlık bölümünü sağlar.
  Sayfa başlığı, meta etiketleri, stil dosyaları ve JavaScript dosyaları burada yüklenir.
  
  Kullanım: Sayfa dosyasında $pageTitle değişkenini tanımlayın (opsiyonel)
-->
<!doctype html>
<html lang="tr">
<head>
  <!-- Karakter kodlama ve viewport ayarları -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  
  <?php
    /**
     * Sayfa başlığını oluştur
     * Format: "$pageTitle - Teftiş | 657.com.tr"
     * Eğer $pageTitle tanımlı değilse sadece "Teftiş | 657.com.tr" kullanılır
     */
    $fullTitle = '';
    if (!empty($pageTitle)) {
      // XSS koruması için htmlspecialchars kullan
      $fullTitle = htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') . ' - Teftiş | 657.com.tr';
    } else {
      $fullTitle = 'Teftiş | 657.com.tr';
    }
  ?>
  
  <!-- Sayfa başlığı -->
  <title><?= $fullTitle; ?></title>
  
  <!-- Tema ayarları -->
  <meta name="color-scheme" content="light dark" />
  
  <!-- Favicon -->
  <link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml" />
  
  <!-- Google Fonts - Performans için preconnect -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Material Symbols İkonları -->
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:FILL@0..1" rel="stylesheet" />
  
  <!-- Ana stil dosyası -->
  <link rel="stylesheet" href="/assets/css/style.css?v=3" />
  
  <!-- JavaScript dosyaları -->
  <script defer src="/assets/js/app.js?v=8"></script>
  <script src="/assets/js/vendor/xlsx.min.js"></script>
  <script defer src="/assets/js/open-modal-bridge.js?v=1"></script>
  
  <!-- jQuery kütüphanesi -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
<!-- Ana uygulama konteyneri -->
<div id="app" class="layout">
