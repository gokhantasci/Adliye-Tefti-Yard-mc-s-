<?php
	$pageTitle = "Duruşma Kaçağı Kontrolü";
	$active = "durusmakacagi";
?>

<main class="flex-grow-1 overflow-auto">
	<div class="container-fluid py-4">
		<header class="page-header mb-4">
			<h2 class="mb-1">Duruşma Kaçağı Kontrolü</h2>
			<p class="text-muted mb-0">Teftiş kayıtları için bilgilendirme ve şablon indirme.</p>
		</header>

		<div class="row justify-content-center">
			<div class="col-12 col-lg-8 col-xl-6">
				<div class="card">
					<div class="card-header d-flex align-items-center justify-content-center gap-2">
						<span class="material-symbols-rounded" style="font-size: 1.25rem;">info</span>
						<strong>Bilgi</strong>
					</div>
					<div class="card-body">
						<p class="mb-3">Teftişlerde <b>Duruşma Kaçağı Kontrolü</b> için gönderilen kayıtlar genellikle esas defterine <i>yeni kayıt edilmiş ve henüz tensibi yapılmamış</i> dosyaları gösterir.</p>
						<p class="mb-4">Bu nedenlerle dosyaların tek tek kontrolünün daha sağlıklı olması nedeniyle bu konuda bir <b>otomasyon hazırlanmamıştır.</b></p>
						<p class="mb-4">Teftişte kullanılacak düzeltilmiş şablonu indirmek için aşağıdaki butonu kullanabilirsiniz.</p>
						
						<div class="text-center">
							<button class="btn btn-primary d-inline-flex align-items-center gap-2" id="downloadDurusmaBtn" type="button">
								<span class="material-symbols-rounded" style="font-size: 1rem;">download</span>
								<span>Şablonu İndir</span>
							</button>
						</div>
						
						<div id="durusmaStatus" class="text-muted text-center mt-3"></div>
					</div>
				</div>
			</div>
		</div>

		<script defer src="/assets/js/durusmakacagi.js?v=2"></script>
	</div>
</main>