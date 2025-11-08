<?php
  $pageTitle = "Kesinleşme Hesapla";
  $active = "kesinlesme";
  include __DIR__."/partials/header.php";
  include __DIR__."/partials/navbar.php";
  include __DIR__."/partials/sidebar.php";
?>
<main class="content">
  <div class="page-header">
    <h1>Kesinleşme Hesapla</h1>
  </div>

  <div class="container"><!-- 4 bölgeli grid: ustsol, ustsag, altsol, altsag -->
    <!-- Üst Sol: Form -->
    <section class="ustsol" style="grid-area: ustsol;">
      <div class="card">
        <div class="card-head">
          <h2 class="card-title">Tebliğ ve Süre</h2>
          <div class="title-actions">
            <button id="btnCalc" class="btn btn-primary">Hesapla</button>
            <button id="btnClear" class="btn">Temizle</button>
          </div>
        </div>
        <div class="card-body" id="formMount"><!-- JS doldurur --></div>
      </div>
    </section>

    <!-- Üst Sağ: Kesinleşme Tarihi -->
    <section class="ustsag" style="grid-area: ustsag;">
      <div class="card">
        <div class="card-head">
          <h2 class="card-title">Kesinleşme Tarihi</h2>
        </div>
        <div class="card-body">
          <div id="resultBox" class="kpi">
            <div class="kpi-value muted">—</div>
            <div class="kpi-label">Hesap sonrası burada görünecek</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Alt Sol: Tatiller -->
    <section class="altsol" style="grid-area: altsol;">
      <div class="card">
        <div class="card-head">
          <h2 class="card-title">Tatil Günleri</h2>
          <div class="title-actions"><span id="holidayInfo" class="muted"></span></div>
        </div>
        <div class="card-body" id="holidayMount"><!-- JS doldurur --></div>
      </div>
    </section>

    <!-- Alt Sağ: Açıklamalar -->
    <section class="altsag" style="grid-area: altsag;">
      <div class="card">
        <div class="card-head">
          <h2 class="card-title">Açıklamalar</h2>
        </div>
        <div class="card-body">
          <ul id="explainList" class="list"></ul>
        </div>
      </div>
    </section>
  </div>
</main>
<?php include __DIR__."/partials/footer.php"; ?>
<script src="/assets/js/kesinlesme.js?v=2"></script>
