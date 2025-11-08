<!-- Ortak başlık şablonu: sayfa üst kapsayıcı -->
<!doctype html>
<html lang="tr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <?php
    // Başlık formatı: "$pageTitle - Teftiş | 657.com.tr"
    $fullTitle = '';
    if (!empty($pageTitle)) {
      $fullTitle = htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') . ' - Teftiş | 657.com.tr';
    } else {
      $fullTitle = 'Teftiş | 657.com.tr';
    }
  ?>
  <title><?= $fullTitle; ?></title>
  <meta name="color-scheme" content="light dark" />
  <link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml" />
  <!-- Google Fonts & Material Symbols -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:FILL@0..1" rel="stylesheet" />
  <link rel="stylesheet" href="/assets/css/style.css?v=3" />
  <script defer src="/assets/js/app.js?v=8"></script>
  <script src="/assets/js/vendor/xlsx.min.js"></script>
  <script defer src="/assets/js/open-modal-bridge.js?v=1"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
<div id="app" class="layout">
