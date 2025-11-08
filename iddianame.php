<?php
  /* İddianame Değerlendirme Zaman Kontrolü – XLS yükle ve özet önizleme */
  $pageTitle = "İddianame Değerlendirme Zaman Kontrolü";
  $active = "iddianame";
  include __DIR__."/partials/header.php";
  include __DIR__."/partials/navbar.php";
  include __DIR__."/partials/sidebar.php";
?>
<main class="content">
  <header class="page-header">
    <h1>İddianame Değerlendirme Zaman Kontrolü</h1>
    <p class="muted">Yüklediğiniz tabloyu işler ve denetim cetvelini hazırlar.</p>
  </header>

  <!-- 10/12 - 2/12 grid -->
  <section id="htkGrid" style="display:grid;grid-template-columns:5fr 1fr;gap:16px;align-items:start">
    <!-- Sol (10/12) -->
    <div id="col10">
      <section class="panel" id="combinedSummaryCard" style="display:none; margin-top:0">
        <div class="panel-head">
          <div class="card-title">
            <span class="material-symbols-rounded">dataset</span> Birleştirilmiş Özet
          </div>
          <div class="title-actions muted" id="combinedStats"></div>
        </div>
        <div class="panel-body">
          <div class="table-wrap" id="combinedTableWrap">
            <div class="placeholder">Henüz veri yok.</div>
          </div>
        </div>
      </section>
    </div>

    <!-- Sağ (2/12) -->
    <aside id="col2">
      <!-- Uyarı metni -->
      <section class="card" style="margin-bottom:12px">
        <div class="card-head" style="color:var(--muted)">
          <span class="material-symbols-rounded">info</span>
          <strong>Bilgi</strong>
        </div>
        <div class="card-body">
          <p>Tarafınıza gönderilen <strong>İddianame Değerlendirme Zaman Kontrolü</strong> dosyasını yüklerseniz, işleyip size denetim cetveli olarak teslim edebiliriz.</p>
        </div>
      </section>

      <!-- XLS yükleme -->
      <section class="card card-upload" id="udfUploadCard">
        <div class="card-head">
          <span class="material-symbols-rounded">upload_file</span>
          <strong>XLS Yükleme</strong>
        </div>
        <div class="card-body" style="display:block">
          <div id="udfDrop" class="placeholder" style="text-align:center;padding:18px;cursor:pointer">
            <span class="material-symbols-rounded">drive_folder_upload</span>
            <div>XLS/XLSX dosyalarını buraya sürükleyip bırakın</div>
            <small class="muted">veya tıklayıp seçin</small>
          </div>
          <input id="udfInput" type="file" accept=".xls,.xlsx" hidden>
          <div id="udfPickRow" style="margin-top:10px;text-align:right">
            <label class="btn" for="udfInput">
              <span class="material-symbols-rounded">folder_open</span> Dosya Seç
            </label>
          </div>
          <div id="xlsChosen" class="muted" style="margin-top:8px"></div>
        </div>
      </section>
    </aside>
  </section>
</main>

<!-- Sayfaya özel JS -->
<script src="/assets/js/jszip.min.js"></script>
<script src="/assets/js/xlsx-loader.js"></script>
<script src="/assets/js/iddianame.js?v=1"></script>
<?php include __DIR__."/partials/footer.php"; ?>

