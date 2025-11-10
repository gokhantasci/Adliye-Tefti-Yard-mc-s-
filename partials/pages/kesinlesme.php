<?php
$pageTitle = "Kesinleşme Hesapla";
$active = "kesinlesme";
?>

<main class="flex-grow-1 overflow-auto">
	<div class="container-fluid py-4">
		<header class="page-header mb-4">
			<h1>Kesinleşme Hesapla</h1>
			<p class="text-muted">Girilen tarihlere göre mevzuata uygun hesaplama yapılır.</p>
		</header>

		<div class="row">
	<!-- Form -->
	<div class="col-12 col-md-4 mb-3">
		<div class="card" id="kesFormPanel">
			<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
				<div class="d-flex align-items-center gap-2">
					<span class="material-symbols-rounded">schedule</span>
					<strong>Tebliğ ve Süre</strong>
				</div>
				<div class="d-flex gap-2">
					<button id="btnCalc" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-2" type="button">
						<span class="material-symbols-rounded" style="font-size:1rem;">calculate</span>
						<span>Hesapla</span>
					</button>
					<button id="btnClear" class="btn btn-outline-secondary btn-sm" type="button">Temizle</button>
				</div>
			</div>
			<div class="card-body" id="formMount"></div>
		</div>
	</div>

	<!-- Kesinleşme Tarihi -->
	<div class="col-12 col-md-3 mb-3">
		<div class="card" id="kesResultPanel">
			<div class="card-header d-flex align-items-center gap-2">
				<span class="material-symbols-rounded">task_alt</span>
				<strong>Kesinleşme Tarihi</strong>
			</div>
			<div class="card-body text-center">
				<div id="resultBox">
					<div class="display-6 text-muted mb-2" id="resultValue">—</div>
					<div class="text-muted small" id="resultLabel">Hesap sonrası burada görünecek</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Açıklamalar -->
	<div class="col-12 col-md-5 mb-3">
		<div class="card" id="kesAciklamaPanel">
			<div class="card-header d-flex align-items-center gap-2">
				<span class="material-symbols-rounded">help</span>
				<strong>Açıklamalar</strong>
			</div>
			<div class="card-body">
				<ul id="explainList" class="list-unstyled small"></ul>
			</div>
		</div>
	</div>

	<!-- Tatiller - Tam genişlik -->
	<div class="col-12">
		<div class="card" id="kesTatillerPanel">
			<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
				<div class="d-flex align-items-center gap-2">
					<span class="material-symbols-rounded">event</span>
					<strong>Tatil Günleri</strong>
				</div>
				<span id="holidayInfo" class="text-muted small"></span>
			</div>
			<div class="card-body" id="holidayMount"></div>
		</div>
	</div>
</div>

	</div>
</main>

<script defer src="/assets/js/kesinlesme.js?v=4"></script>