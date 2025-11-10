<?php
	$pageTitle = "Yargılama Gideri";
	$active = "yargilama";
?>

<main class="flex-grow-1 overflow-auto">
	<div class="container-fluid py-4">
		<header class="page-header mb-4">
			<h2 class="mb-1">Yargılama Gideri Hesaplama</h2>
			<p class="text-muted mb-0">UYAP tebligat sorgulamasından elde ettiğiniz EXCEL dosyasını yükleyerek tüm tebligatları hesaplayabilirsiniz.</p>
		</header>

		<div class="row">
			<div class="col-12 col-xl-8">
				<div class="alert alert-info d-flex align-items-start position-relative mb-3" id="uploadInfoCard">
					<span class="material-symbols-rounded alert-icon me-3">info</span>
					<div class="alert-body flex-grow-1">
						<div class="alert-title fw-bold mb-1">Bilgi</div>
						<div>UYAP &gt; Tebligat sorgulanması ekranından elde edeceğiniz EXCEL dosyasını yükleyerek tüm tebligatların hesaplanmasını sağlayabilirsiniz.</div>
					</div>
					<button type="button" class="btn-close position-absolute top-0 end-0 m-2" onclick="this.parentElement.remove()" aria-label="Kapat"></button>
				</div>

				<div id="yg-ustsol" class="row g-3 mb-3"></div>

				<div class="row g-3 mb-3">
					<div class="col-12">
						<div class="card">
							<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
								<div class="d-flex align-items-center gap-2">
									<span class="material-symbols-rounded">toggle_off</span>
									<strong>Tebligat/E-Tebligat ayrı ayrı gösterilsin mi?</strong>
								</div>
								<div class="form-check form-switch">
									<input class="form-check-input" type="checkbox" role="switch" id="asSeparateSwitch">
									<label class="form-check-label" for="asSeparateSwitch">Evet</label>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row g-3 mb-3">
					<div class="col-md-6">
						<div class="card">
							<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
								<div class="d-flex align-items-center gap-2">
									<strong>Posta Gideri</strong>
								</div>
								<span id="postaGideriCount" class="badge bg-secondary">0</span>
							</div>
							<div class="card-body p-2">
								<div class="mb-2">
									<label class="form-label small mb-1">Posta Gideri</label>
									<input type="number" id="postaGideri" class="form-control form-control-sm" value="0" min="0" step="1">
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card">
							<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
								<div class="d-flex align-items-center gap-2">
									<strong>Yasa Yolu Gidiş-Dönüş</strong>
								</div>
								<span id="yasaYoluCount" class="badge bg-secondary">0</span>
							</div>
							<div class="card-body p-2">
								<div class="mb-2">
									<label class="form-label small mb-1">Yasa Yolu Gidiş-Dönüş</label>
									<input type="number" id="yasaYolu" class="form-control form-control-sm" value="0" min="0" step="1">
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row g-3 mb-3">
					<div class="col-md-6">
						<div class="card">
							<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
								<div class="d-flex align-items-center gap-2">
									<strong>Keşif Gideri</strong>
								</div>
								<span id="kesifCount" class="badge bg-secondary">0</span>
							</div>
							<div class="card-body p-2">
								<div class="mb-2">
									<label class="form-label small mb-1">Keşif Gideri</label>
									<input type="number" id="kesifGideri" class="form-control form-control-sm" value="0" min="0" step="1">
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card">
							<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
								<div class="d-flex align-items-center gap-2">
									<strong>Bilirkişi</strong>
								</div>
								<span id="bilirkisiCount" class="badge bg-secondary">0</span>
							</div>
							<div class="card-body p-2">
								<div class="mb-2">
									<label class="form-label small mb-1">Bilirkişi</label>
									<input type="number" id="bilirkisi" class="form-control form-control-sm" value="0" min="0" step="1">
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row g-3 mb-3">
					<div class="col-md-6">
						<div class="card">
							<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
								<div class="d-flex align-items-center gap-2">
									<strong>İlan Gideri</strong>
								</div>
								<span id="ilanCount" class="badge bg-secondary">0</span>
							</div>
							<div class="card-body p-2">
								<div class="mb-2">
									<label class="form-label small mb-1">İlan Gideri</label>
									<input type="number" id="ilanGideri" class="form-control form-control-sm" value="0" min="0" step="1">
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card">
							<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
								<div class="d-flex align-items-center gap-2">
									<strong>ATK Gideri</strong>
								</div>
								<span id="atkCount" class="badge bg-secondary">0</span>
							</div>
							<div class="card-body p-2">
								<div class="mb-2">
									<label class="form-label small mb-1">ATK Gideri</label>
									<input type="number" id="atkGideri" class="form-control form-control-sm" value="0" min="0" step="1">
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row g-3">
					<div class="col-12">
						<div class="card">
							<div class="card-header d-flex align-items-center gap-2">
								<span class="material-symbols-rounded">person_search</span>
								<strong>Uzlaştırmacı</strong>
							</div>
							<div class="card-body p-2">
								<div class="mb-2">
									<label class="form-label small mb-1">Uzlaştırmacı</label>
									<input type="number" id="uzlastirmaci" class="form-control form-control-sm" value="0" min="0" step="1">
								</div>
							</div>
						</div>
					</div>
				</div>

				<div id="liveAlertPlaceholder" class="mt-3"></div>
				<input id="opencount" type="hidden" value="0">
			</div>

			<aside class="col-12 col-xl-4">
				<div class="card mb-3">
					<div class="card-body">
						<div class="d-grid gap-2">
							<button id="giderhesapla" class="btn btn-primary d-flex align-items-center justify-content-center gap-2">
								<span class="material-symbols-rounded" style="font-size: 1rem;">calculate</span>
								<span>Hesapla</span>
							</button>
							<button id="btnClear" class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2">
								<span class="material-symbols-rounded" style="font-size: 1rem;">backspace</span>
								<span>Temizle</span>
							</button>
						</div>
					</div>
				</div>

				<div class="card mb-3">
					<div class="card-header d-flex align-items-center gap-2">
						<span class="material-symbols-rounded">summarize</span>
						<strong>Özet</strong>
					</div>
					<div class="card-body">
						<div class="row g-2 mb-3">
							<div class="col-4">
								<div class="text-center">
									<div class="text-muted small mb-1">Tebligat Toplam</div>
									<div id="kpiTeb" class="fs-5 fw-bold">—</div>
								</div>
							</div>
							<div class="col-4">
								<div class="text-center">
									<div class="text-muted small mb-1">E-Tebligat Toplam</div>
									<div id="kpiETeb" class="fs-5 fw-bold">—</div>
								</div>
							</div>
							<div class="col-4">
								<div class="text-center">
									<div class="text-muted small mb-1">Genel Toplam</div>
									<div id="kpiGenel" class="fs-4 fw-bold text-primary">—</div>
								</div>
							</div>
						</div>
						<hr class="my-3">
						<pre id="yargilamadokum" class="mb-3" style="white-space:pre-wrap;font-size:1.1rem;"></pre>
						<button id="btnCopyDokum" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2">
							<span class="material-symbols-rounded" style="font-size: 1rem;">content_copy</span>
							<span>Kopyala</span>
						</button>
					</div>
				</div>

				<div class="card" id="tebligatUploadCard">
					<div class="card-header d-flex align-items-center gap-2">
						<span class="material-symbols-rounded">upload_file</span>
						<strong>Tebligat Dosyası Yükle</strong>
					</div>
					<div class="card-body">
						<div id="dropZone" class="border border-2 border-dashed rounded p-4 text-center mb-3" style="cursor:pointer; transition: all 0.2s;">
							<span class="material-symbols-rounded d-block mb-2" style="font-size: 3rem; opacity: 0.5;">cloud_upload</span>
							<div class="mb-1">XLS, XLSX veya UDF dosyalarını buraya sürükleyip bırakın</div>
							<small class="text-muted">veya aşağıdan dosya seçin</small>
						</div>
						<input type="file" id="fileInput" accept=".xls,.xlsx,.udf" hidden>
						<button class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2" onclick="document.getElementById('fileInput').click()">
							<span class="material-symbols-rounded" style="font-size: 1rem;">folder_open</span>
							<span>Dosya Seç</span>
						</button>
					</div>
				</div>
			</aside>
		</div>

		<script src="/assets/js/xlsx-loader.js"></script>
		<script defer src="/assets/js/yargilamagideri.js?v=4"></script>
	</div>
</main>