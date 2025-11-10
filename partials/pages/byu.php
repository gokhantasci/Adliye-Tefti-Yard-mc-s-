<?php
	$pageTitle = 'Basit Yargılama Usulü Zaman Kontrolü';
	$active = 'byu';
?>

<main class="flex-grow-1 overflow-auto">
	<div class="container-fluid py-4">

		<header class="page-header d-flex justify-content-between align-items-center mb-4">
			<div>
				<h1>Basit Yargılama Usulü Zaman Kontrolü</h1>
				<p class="text-muted">Yüklediğiniz tabloyu işler ve denetim cetvelini hazırlar.</p>
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
						<p class="mb-0"><strong>Basit Yargılama Usulü Zaman Kontrolü</strong> tablonuzu yükleyin; sistem süreleri hesaplayıp denetim cetvelini üretir.</p>
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
							<div class="spinner-border spinner-border-sm text-primary mt-3" id="xlsInlineSpinnerByu" role="status" style="display:none;">
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

		<!-- BYU Warning Modal -->
		<div id="byuWarningModal" class="modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" style="display:none;">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header bg-warning bg-opacity-10 border-bottom border-warning">
						<h5 class="modal-title d-flex align-items-center gap-2">
							<span class="material-symbols-rounded text-warning">warning</span>
							<strong>DİKKAT</strong>
						</h5>
					</div>
					<div class="modal-body" style="line-height: 1.8;">
						<p class="mb-2">BYU tablolarında size gönderilen <strong>orijinal tablo</strong> üzerinde çalışınız.</p>
						<p class="mb-2"><strong>Son İşlem Tarihi</strong> dosyada karar vermek için gerekli son işlem tarihidir.</p>
						<p class="mb-0 text-danger"><strong>Lütfen bu hususlara dikkat ediniz.</strong></p>
					</div>
					<div class="modal-footer d-flex align-items-center justify-content-between">
						<div class="form-check">
							<input class="form-check-input" type="checkbox" id="byuWarningCheckbox">
							<label class="form-check-label" for="byuWarningCheckbox">
								Okudum ve anladım
							</label>
						</div>
						<button id="byuWarningOkBtn" class="btn btn-primary" disabled>Tamam</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Scripts for BYU -->
		<script src="/assets/js/jszip.min.js"></script>
		<script src="/assets/js/xlsx-loader.js"></script>
		<script defer src="/assets/js/byu.js?v=2"></script>

	</div>
</main>