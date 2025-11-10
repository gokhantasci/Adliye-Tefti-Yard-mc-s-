<?php
	$pageTitle = "İstinaf Defteri";
	$active = "istinaf";
?>

<!-- İstinaf defteri içerikleri -->
<main class="flex-grow-1 overflow-auto">
	<div class="container-fluid py-4">
		
		<!-- Üst Başlık -->
		<div class="mb-4">
			<h1 class="h2 mb-1">İstinaf Defteri</h1>
			<p class="text-muted">Yüklediğiniz istinaf defterini işler ve teftişe esas olabilecek değerleri gösterir.</p>
		</div>

		<!-- Ana Layout: Sol (KPIs + Rapor) + Sağ (Upload + Hatırlatma) -->
		<div class="row g-4">
			
			<!-- Sol Kolon - KPIs + Rapor -->
			<div class="col-12 col-xl-8">
				
				<!-- KPI Kartları -->
				<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-6 g-3 mb-4" id="kpiCards">
					<!-- KPI kartları JavaScript ile oluşturulacak -->
				</div>
				
				<!-- Rapor Alanı (Dinamik) -->
				<div id="reportContainer"></div>
				
			</div>
			
			<!-- Sağ Kolon - Upload & Hatırlatma -->
			<div class="col-12 col-xl-4">
				
				<!-- Excel Yükle Card -->
				<div class="card mb-3">
					<div class="card-header d-flex align-items-center gap-2">
						<span class="material-symbols-rounded">upload</span>
						<strong>Dosya Yükle</strong>
					</div>
					<div class="card-body">
						<form onsubmit="return false;">
							<div id="dropZone" class="border border-2 border-dashed rounded p-4 text-center mb-3" style="border-color: var(--adalet-border); transition: all 0.3s ease;" tabindex="0" aria-label="Excel yükleme alanı">
								<p class="mb-2">Dosyaları buraya sürükleyip bırakın</p>
								<p class="text-muted small mb-3">veya</p>
								<label for="excelInput" class="btn btn-outline-primary">
									<span class="material-symbols-rounded me-1" style="font-size: 1rem;">file_upload</span>
									Dosya Seç
								</label>
								<input type="file" id="excelInput" accept=".xls,.xlsx" hidden multiple>
								<div class="mt-3">
									<small class="text-muted">İzin verilen türler: <strong>.xls</strong>, <strong>.xlsx</strong></small>
								</div>
							</div>
							
							<!-- Özet Tabloyu Aç Butonu (JS ile oluşturulacak) -->
							<div id="btnOzetContainer"></div>
							
							<!-- Tarih Filtresi (JS ile oluşturulacak) -->
							<div id="dateFilterContainer"></div>
						</form>
					</div>
				</div>
				
				<!-- Hakime Göre Filtreleme Card -->
				<div class="card mb-3">
					<div class="card-header">
						<div class="d-flex align-items-center gap-2">
							<span class="material-symbols-rounded">gavel</span>
							<strong>Hakime Göre Filtreleme</strong>
						</div>
					</div>
					<div class="card-body">
						<p class="small text-muted mb-3">
							<span class="material-symbols-rounded" style="font-size: 1rem; vertical-align: middle;">info</span>
							Yukarıdaki raporu hakime göre filtrelemek için karar defterlerini yükleyin
						</p>
						
						<!-- Karar defteri yükleme alanı -->
						<div id="hakimDropZone" class="border border-2 border-dashed rounded p-3 text-center mb-3" style="border-color: var(--adalet-border); transition: all 0.3s ease; cursor: pointer;" tabindex="0" aria-label="Karar defteri yükleme alanı">
							<p class="mb-2 small">Karar defterlerini buraya yükleyin</p>
							<p class="text-muted small mb-3">veya</p>
							<label for="hakimExcelInput" class="btn btn-outline-primary btn-sm">
								<span class="material-symbols-rounded me-1" style="font-size: 1rem;">file_upload</span>
								Karar Defteri Seç
							</label>
							<input type="file" id="hakimExcelInput" accept=".xls,.xlsx" hidden multiple>
							<div class="mt-3">
								<small class="text-muted">İzin verilen türler: <strong>.xls</strong>, <strong>.xlsx</strong></small>
							</div>
						</div>
						
						<!-- Hakim filtresi (karar defteri yüklendikten sonra aktif olacak) -->
						<div id="hakimFilterContainer" style="display:none;">
							<label class="form-label small mb-1">Hakim Seçin</label>
							<select class="form-select form-select-sm mb-2" id="hakimSelect">
								<option value="">Tümü</option>
								<!-- JavaScript ile doldurulacak -->
							</select>
							<div class="d-flex align-items-center gap-2">
								<span class="material-symbols-rounded text-primary" style="font-size: 1rem;">filter_alt</span>
								<small class="text-muted">
									<span id="hakimMatchCount">0</span> dosya gösteriliyor
								</small>
							</div>
						</div>
					</div>
				</div>
				
				<!-- Hatırlatma Card -->
				<div class="card">
					<div class="card-header">
						<div class="d-flex align-items-center gap-2">
							<span class="material-symbols-rounded">info</span>
							<strong>Hatırlatma</strong>
						</div>
					</div>
					<div class="card-body">
						<p class="mb-0 small">Bu sayfayı kullanabilmek için UYAP > Raporlar > Defterler > İstinaf Defteri'ni Excel formatında ilgili tarihleri kapsayacak şekilde indirdikten sonra toplu olarak aşağıya yükleyebilirsiniz</p>
					</div>
				</div>
				
			</div>
			
		</div>

	</div>
	
	<!-- Detay Modal -->
	<div class="modal fade" id="istinafDetailModal" tabindex="-1" aria-labelledby="istinafDetailModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="istinafDetailModalLabel">Dosya Detayları</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
				</div>
				<div class="modal-body" id="istinafDetailModalBody">
					<!-- İçerik JavaScript ile doldurulacak -->
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
				</div>
			</div>
		</div>
	</div>
	
</main>

<!-- Page scripts -->
<script src="/assets/js/xlsx-loader.js"></script>
<script defer src="/assets/js/istinaf-kpi.js?v=3"></script>
<script defer src="/assets/js/istinaf-hakim-filter.js?v=1"></script>
<script defer src="/assets/js/istinaf.js?v=5"></script>
<script>
	(function(){
		try{
			var host = document.getElementById('istinafReminderHost') || document.querySelector('#cardUstSag');
			if (!host || typeof window.showAlert !== 'function') return;
			if (document.getElementById('istinafReminderAlert')) return;
			var wrap = document.createElement('div'); wrap.id = 'istinafReminderAlert';
			if (host.firstChild) host.insertBefore(wrap, host.firstChild); else host.appendChild(wrap);
			window.showAlert(wrap, {
				type: 'info',
				title: 'Hatırlatma',
				message: 'Bu sayfayı kullanabilmek için UYAP > Raporlar > Defterler > İstinaf Defteri\'ni indirdikten sonra toplu yükleyin',
				dismissible: true
			});
		} catch(e){}
	})();
</script>