<?php
	$pageTitle = "Tensip Zaman Kontrolü";
	$active = "tensip";
?>

<main class="flex-grow-1 overflow-auto">
	<div class="container-fluid py-4">
		<!-- Page Header -->
		<div class="d-flex justify-content-between align-items-center mb-4">
			<div>
				<h2 class="mb-1">Tensip Zaman Kontrolü</h2>
				<p class="text-muted mb-0">Yüklediğiniz tabloyu işler ve denetim cetvelini hazırlar.</p>
			</div>
		</div>

		<div class="row">
			<!-- Sol Kolon - Ana İçerik -->
			<div class="col-12 col-xl-8">
				<!-- Birleştirilmiş Özet Card -->
				<div class="card mb-3" id="combinedSummaryCard" style="display:none;">
					<div class="card-header d-flex justify-content-between align-items-center">
						<div class="d-flex align-items-center gap-2">
							<span class="material-symbols-rounded">dataset</span>
							<strong>Birleştirilmiş Özet</strong>
						</div>
						<div class="d-flex align-items-center gap-2">
							<span class="text-muted small" id="combinedStats"></span>
							<input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Ara..." style="width: 200px;">
						</div>
					</div>
					<div class="card-body">
						<div class="table-responsive" id="combinedTableWrap">
							<div class="text-center text-muted py-4">Henüz veri yok.</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Sağ Kolon - Upload & Bilgi -->
			<div class="col-12 col-xl-4">
				<!-- Bilgi Card -->
				<div class="card mb-3">
					<div class="card-header d-flex align-items-center gap-2">
						<span class="material-symbols-rounded">info</span>
						<strong>Bilgi</strong>
					</div>
					<div class="card-body">
						<p class="mb-0"><strong>Tensip Zaman Kontrolü</strong> tablonuzu yükleyin; sistem süreleri hesaplayıp denetim cetvelini üretir.</p>
					</div>
				</div>

				<!-- XLS Yükleme Card -->
				<div class="card" id="udfUploadCard">
					<div class="card-header d-flex align-items-center gap-2">
						<span class="material-symbols-rounded">upload</span>
						<strong>Dosya Yükle</strong>
					</div>
					<div class="card-body">
						<form onsubmit="return false;">
							<div id="udfDrop" class="border border-2 border-dashed rounded p-4 text-center mb-3" style="border-color: var(--adalet-border); transition: all 0.3s ease; cursor: pointer;" tabindex="0" aria-label="Excel yükleme alanı">
								<span class="material-symbols-rounded text-primary mb-2" style="font-size: 48px;">folder_open</span>
								<p class="mb-2">XLS/XLSX dosyalarını buraya sürükleyip bırakın</p>
								<p class="text-muted small mb-3">veya</p>
								<label for="udfInput" class="btn btn-outline-primary">
									<span class="material-symbols-rounded me-1" style="font-size: 1rem;">file_upload</span>
									Dosya Seç
								</label>
								<input type="file" id="udfInput" accept=".xls,.xlsx" hidden>
								<div class="mt-3">
									<small class="text-muted">İzin verilen türler: <strong>.xls</strong>, <strong>.xlsx</strong></small>
								</div>
								
								<!-- Loading Spinner -->
								<div id="xlsInlineSpinnerTen" class="mt-3" style="display: none;">
									<div class="spinner-border spinner-border-sm text-primary" role="status">
										<span class="visually-hidden">Yükleniyor...</span>
									</div>
									<div class="text-muted small mt-2">İşleniyor…</div>
								</div>
							</div>
							
							<!-- Seçilen Dosya -->
							<div id="xlsChosen" class="text-muted small"></div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<script src="/assets/js/jszip.min.js"></script>
		<script src="/assets/js/xlsx-loader.js"></script>
		<script defer src="/assets/js/tensip.js?v=2"></script>
	</div>
</main>