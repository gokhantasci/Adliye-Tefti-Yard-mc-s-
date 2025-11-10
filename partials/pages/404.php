<?php
/**
 * ========================================
 * 404 NOT FOUND PAGE
 * ========================================
 * Sayfa bulunamadığında gösterilir
 * ========================================
 */

// Set 404 status code
http_response_code(404);

// Page title and meta
$pageTitle = '404 - Sayfa Bulunamadı';
$active = '';

// Include header
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../navbar.php';
?>

<div class="d-flex flex-grow-1 overflow-hidden">
  <?php require_once __DIR__ . '/../sidebar.php'; ?>
  
  <main class="main-content flex-grow-1 overflow-auto">
    <div class="container-fluid py-4">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-6">
          <!-- 404 Card -->
          <div class="card shadow-sm border-0 text-center">
            <div class="card-body py-5">
              <!-- Error Icon -->
              <div class="mb-4">
                <span class="material-symbols-rounded text-danger" style="font-size: 120px;">error</span>
              </div>
              
              <!-- Error Code -->
              <h1 class="display-1 fw-bold text-danger mb-3">404</h1>
              
              <!-- Error Message -->
              <h3 class="mb-3">Sayfa Bulunamadı</h3>
              <p class="text-muted mb-4">
                Aradığınız sayfa mevcut değil, taşınmış veya silinmiş olabilir.
              </p>
              
              <!-- Action Buttons -->
              <div class="d-flex gap-2 justify-content-center flex-wrap">
                <a href="/index.php" class="btn btn-primary">
                  <span class="material-symbols-rounded me-1" style="font-size: 1rem;">home</span>
                  Ana Sayfaya Dön
                </a>
                <button onclick="history.back()" class="btn btn-outline-secondary">
                  <span class="material-symbols-rounded me-1" style="font-size: 1rem;">arrow_back</span>
                  Geri Git
                </button>
              </div>
              
              <!-- Helpful Links -->
              <div class="mt-5 pt-4 border-top">
                <p class="text-muted small mb-3">Faydalı Bağlantılar:</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                  <a href="/karar.php" class="text-decoration-none">
                    <span class="material-symbols-rounded" style="font-size: 1rem; vertical-align: middle;">description</span>
                    Karar
                  </a>
                  <a href="/istinaf.php" class="text-decoration-none">
                    <span class="material-symbols-rounded" style="font-size: 1rem; vertical-align: middle;">gavel</span>
                    İstinaf
                  </a>
                  <a href="/temyiz.php" class="text-decoration-none">
                    <span class="material-symbols-rounded" style="font-size: 1rem; vertical-align: middle;">balance</span>
                    Temyiz
                  </a>
                  <a href="/kesinlesme.php" class="text-decoration-none">
                    <span class="material-symbols-rounded" style="font-size: 1rem; vertical-align: middle;">verified</span>
                    Kesinleşme
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>
