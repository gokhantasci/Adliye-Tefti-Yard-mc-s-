
<?php
// Centralized router: render layout and include page partials from partials/pages
// Sanitize page parameter and fall back to 'home'
$page = isset($_GET['page']) && is_string($_GET['page']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['page']) : 'home';
$pageFile = __DIR__ . "/partials/pages/" . $page . ".php";

// Load page file to get $active and $pageTitle variables
if (file_exists($pageFile)) {
  // Start output buffering to capture page content
  ob_start();
  include $pageFile;
  $pageContent = ob_get_clean();
} else {
  http_response_code(404);
  $active = '';
  $pageTitle = '404 - Sayfa Bulunamadı';
  ob_start();
  echo '<main class="flex-grow-1 overflow-auto"><div class="container-fluid py-4"><h1 class="h3">Sayfa bulunamadı</h1><p>Aradığınız sayfa mevcut değil.</p></div></main>';
  $pageContent = ob_get_clean();
}

// Include layout with $active variable set
include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/navbar.php";

?>
<div class="d-flex flex-grow-1 overflow-hidden">
  <?php include __DIR__ . "/partials/sidebar.php"; ?>
  <?php echo $pageContent; ?>
</div>
<?php include __DIR__ . "/partials/footer.php"; ?>
