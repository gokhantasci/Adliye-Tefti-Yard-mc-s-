<?php
	$pageTitle = "Gerekçeli Karar Zaman Kontrolü";
	$active = "gerekcelikarar";
?>

<main class="flex-grow-1 overflow-auto">
	<div class="container-fluid py-4">
		<header class="page-header d-flex justify-content-between align-items-center mb-4">
			<div>
				<h2 class="mb-1">Gerekçeli Karar Zaman Kontrolü</h2>
				<p class="text-muted mb-0">Yüklediğiniz tabloyu işler ve denetim cetvelini hazırlar.</p>
			</div>
		</header>

		<div class="row">
			<div class="col-12 col-xl-8">
				<div class="card mb-3" id="combinedSummaryCard" style="display:none">
					<div class="card-header d-flex align-items-center justify-content-between">
						<div class="d-flex align-items-center gap-2">
							<span class="material-symbols-rounded">dataset</span>
							<strong>Birleştirilmiş Özet</strong>
						</div>
						<div class="d-flex align-items-center gap-3">
							<span class="text-muted" id="combinedStats"></span>
							<input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Ara..." style="width: 200px;">
						</div>
					</div>
					<div class="card-body">
						<div class="table-wrap" id="combinedTableWrap">
							<div class="placeholder">Henüz veri yok.</div>
						</div>
					</div>
				</div>
			</div>

			<aside class="col-12 col-xl-4">
				<div class="card mb-3">
					<div class="card-header d-flex align-items-center gap-2">
						<span class="material-symbols-rounded">info</span>
						<strong>Bilgi</strong>
					</div>
					<div class="card-body">
						<p class="mb-0">Tarafınıza gönderilen <strong>Gerekçeli Karar Zaman Kontrolü</strong> dosyasını yüklerseniz, işleyip size denetim cetveli olarak teslim edebiliriz.</p>
					</div>
				</div>

				<div class="card" id="udfUploadCard">
					<div class="card-header d-flex align-items-center gap-2">
						<span class="material-symbols-rounded">upload_file</span>
						<strong>XLS Yükleme</strong>
					</div>
					<div class="card-body">
						<div id="udfDrop" class="border border-2 border-dashed rounded p-4 text-center mb-3" style="cursor:pointer; transition: all 0.2s;">
						<span class="material-symbols-rounded d-block mb-2" style="font-size: 3rem; opacity: 0.5;">folder_open</span>
						<div class="mb-1">XLS/XLSX dosyalarını buraya sürükleyip bırakın</div>
						<small class="text-muted">veya tıklayıp seçin</small>
						<div class="spinner-border mt-3" id="xlsInlineSpinnerGk" role="status" style="display:none; width: 2rem; height: 2rem; border-width: 0.25rem; color: var(--adalet-primary);">
							<span class="visually-hidden">İşleniyor...</span>
						</div>
						</div>
						<input id="udfInput" type="file" accept=".xls,.xlsx" hidden>
						<button class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2" onclick="document.getElementById('udfInput').click()">
							<span class="material-symbols-rounded" style="font-size: 1rem;">folder_open</span>
							<span>Dosya Seç</span>
						</button>
						<div id="xlsChosen" class="text-muted mt-2 small"></div>
					</div>
				</div>
			</aside>
		</div>

		<script src="/assets/js/jszip.min.js"></script>
		<script src="/assets/js/xlsx-loader.js"></script>
		<script defer src="/assets/js/gerekcelikarar.js?v=4"></script>
	</div>
</main>