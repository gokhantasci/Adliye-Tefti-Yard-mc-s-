<?php
  $pageTitle = "JSON İstatatikçi Robot";
  $active = "jrobot";
  include __DIR__."/partials/header.php";
  include __DIR__."/partials/navbar.php";
  include __DIR__."/partials/sidebar.php";
?>
 <main class="content">
    <header class="page-header">
      <h1>JSON İstatatikçi Robot</h1>
      <p class="muted">JSON İstatistikçiden Elde Ettiğiniz verilerin derin analizi.</p>
    </header>

    <!-- 10/12 - 2/12 grid -->
    <section id="htkGrid" style="display:grid;grid-template-columns:5fr 1fr;gap:16px;align-items:start">
      <!-- Sol (10/12) -->
      <div id="col10">
		  <!-- col10: 4 sütunlu ilk satır -->
			<div id="col10RowTop" class="row-4">
			  <!-- 1) Hakim/Başkan -->
			  <section class="card" id="hakimCard">
				<div class="card-head">
				  <span class="material-symbols-rounded">gavel</span>
				  <strong>Hakim / Başkan</strong>
				</div>
				<div class="card-body" id="hakimBody">
				  <div class="placeholder">Hazır.</div>
				</div>
			  </section>

			  <!-- 2) Cumhuriyet Savcısı -->
			  <section class="card" id="savciCard">
				<div class="card-head">
				  <span class="material-symbols-rounded">verified_user</span>
				  <strong>Cumhuriyet Savcısı</strong>
				</div>
				<div class="card-body" id="savciBody">
				  <div class="placeholder">Hazır.</div>
				</div>
			  </section>

			  <!-- 3) Katip -->
			  <section class="card" id="katipCard">
				<div class="card-head">
				  <span class="material-symbols-rounded">contact_mail</span>
				  <strong>Katip</strong>
				</div>
				<div class="card-body" id="katipBody">
				  <div class="placeholder">Hazır.</div>
				</div>
			  </section>

			  <!-- 4) JSON Yükleme kartı (mevcut #jsonUploadCard buraya taşınacak) -->
			  <div id="jsonUploadCol"></div>
			</div>
   		
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
            <p><strong>JSON İstatistikçiden</strong> elde edilmiş JSON uzantılı dosyayı yükleyin.</p>
          </div>
        </section>

        <!-- UDF yükleme -->
        <section class="card card-upload" id="jsonUploadCard">
          <div class="card-head">
            <span class="material-symbols-rounded">upload_file</span>
            <strong>JSON Yükleme</strong>
          </div>
          <div class="card-body" style="display:block">
            <div id="udfDrop" class="placeholder" style="text-align:center;padding:18px;cursor:pointer">
              <span class="material-symbols-rounded">drive_folder_upload</span>
              <div>JSON dosyalarını buraya sürükleyip bırakın</div>
              <small class="muted">veya tıklayıp seçin</small>
            </div>
            <input id="udfInput" type="file" accept=".json" multiple hidden>
            <div id="udfPickRow" style="margin-top:10px;text-align:right">
              <label class="btn" for="udfInput">
                <span class="material-symbols-rounded">folder_open</span> Dosya Seç
              </label>
            </div>
            <div id="udfChosen" class="muted" style="margin-top:8px"></div>
          </div>
        </section>

        <!-- EXCEL yükleme kartı: UDF başarılı olunca dinamik eklenecek -->
        <div id="excelUploadMount"></div>
      </aside>
    </section>
  </main>

<?php include __DIR__."/partials/footer.php"; ?>
</div>

<!-- Sayfaya özel JS -->

<script src="/assets/js/jszip.min.js"></script>
<script src="/assets/js/xlsx-loader.js"></script>
<script src="/assets/js/jrobot.js"></script>
<script>
(function(){
  // col10'u tamamen temizlemiştik; grid zaten yerleştirildi.
  // Sağdaki mevcut yükleme kartını (#jsonUploadCard) alıp 4. kolona taşıyoruz.
  const src = document.getElementById('jsonUploadCard');
  const dest = document.getElementById('jsonUploadCol');
  if (src && dest) {
    dest.appendChild(src);                // aynı düğümü yeni yere taşır
    src.style.marginTop = '0';            // kart arası boşluk grid’den gelsin
    // Eğer aside’da "excelUploadMount" boş kaldıysa orada kalabilir; istenirse ayrıca taşırız.
  }
})();
</script>


</body>
</html>
