<?php
	$pageTitle = 'Kanun Yolu Kontrolü';
	$active = 'kanun_yolu';
?>

<main class="flex-grow-1 overflow-auto">
	<div class="container-fluid py-4">
		<header class="page-header mb-4">
			<h1>Kanun Yolu Kontrolü</h1>
			<p class="text-muted">İstinaf / Yargıtay süreçleri için UYAP kayıtları üzerinden tetkik notları ve yönlendirmeler.</p>
		</header>

		<div class="row justify-content-center">
			<div class="col-12 col-lg-10 col-xl-8">
				<div class="card">
					<div class="card-header d-flex align-items-center justify-content-center gap-2">
						<span class="material-symbols-rounded" style="font-size: 1.25rem;">info</span>
						<strong>Bilgi</strong>
					</div>
					<div class="card-body" style="line-height: 1.6;">
						<p class="mb-3">Teftişlerde <strong>Kanun Yolu</strong> için herhangi bir Excel dosyası gönderilmediği için kayıtların <b>UYAP üzerinden tetkiki</b> gerekmektedir.</p>
						
						<p class="mb-2"><strong>1- Karar kesin olmasına veya öngörülen süre dolmasına rağmen kanun yolu merciine (Yargıtay veya Bölge Adliye Mahkemesi) gönderilen dosyalar</strong><br>Bu madde için sitede bulunan <a href="/istinaf" class="text-decoration-none">İstinaf</a> menüsünü kullanabilirsiniz.</p>
						
						<p class="mb-2"><strong>2- Kanun yolu merciine (Yargıtay veya Bölge Adliye Mahkemesi) henüz gönderilmeyen dosyalar;</strong><br>Bu madde için yine <a href="/istinaf" class="text-decoration-none">İstinaf</a> menüsünü kullanabilirsiniz.</p>
						
						<p class="mb-2"><strong>3- Kanun yolu merciine (Yargıtay veya Bölge Adliye Mahkemesi) geç gönderilen dosyalar;</strong><br>Bu madde için <a href="/istinaf" class="text-decoration-none">İstinaf</a> menüsü içerisinde yer alan <strong>Özet Tablo</strong>dan faydalanabilirsiniz.</p>
						
						<p class="mb-4"><strong>4- Kanun yolu (Yargıtay veya Bölge Adliye Mahkemesi) incelemesi için görevli dairenin hatalı belirlendiği dosyalar;</strong><br>Ceza Mahkemelerinde görevli daire seçimi yapılmadığı için dosyalar otomatik tevzi edilmektedir; bu tablo genellikle boş olur. Ancak İstinaf çevreniz yeni değişmiş ve dosya evvelce başka İstinaf Mahkemesinde incelenmiş ise sehven yeni İstinaf Mahkemesine gönderilen dosyalar yazılmalıdır.</p>
						
						<div class="text-center">
							<button class="btn btn-primary d-inline-flex align-items-center gap-2" id="downloadKanunYoluBtn" type="button">
								<span class="material-symbols-rounded" style="font-size: 1rem;">download</span>
								<span>Şablonu İndir</span>
							</button>
						</div>
						
						<div id="kanunYoluStatus" class="text-muted text-center mt-3"></div>
					</div>
				</div>
			</div>
		</div>

		<section style="display:none;" aria-hidden="true">
			<div id="dropZone" class="placeholder" style="padding:12px;border:1px dashed var(--border);text-align:center;cursor:pointer;">Kanun Yolu için XLS yükleme beklenmiyor.</div>
			<input id="fileInput" type="file" multiple accept=".xls,.xlsx" hidden>
			<div id="selectedFilesHint" class="text-muted" style="margin-top:4px;"></div>
		</section>

		<script>
		document.addEventListener('DOMContentLoaded', function(){
			const btn = document.getElementById('downloadKanunYoluBtn');
			if (btn){
				btn.addEventListener('click', function(){
					try {
						const a = document.createElement('a');
						a.href = '/kanunyolu.docx';
						a.download = '6- KANUN YOLU KONTROLÜ.docx';
						document.body.appendChild(a); a.click(); a.remove();
						window.toast?.({ type:'success', title:'İndiriliyor', body:'Kanun Yolu şablonu indiriliyor.' });
					} catch(e){ window.toast?.({ type:'warning', title:'Hata', body:'Şablon indirilemedi.' }); }
				});
			}
		});
		</script>

		<script src="/assets/js/jszip.min.js"></script>
		<script src="/assets/js/xlsx-loader.js"></script>
		<script src="/assets/js/kanunyolu.js?v=1"></script>
	</div>
</main>