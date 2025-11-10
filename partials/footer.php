<!--
  ========================================
  ALT BİLGİ / FOOTER (FOOTER.PHP)
  ========================================
  Adalet Bakanlığı Teması
  ========================================
-->
</div><!-- Close d-flex wrapper from navbar -->

<footer class="py-4">
  <div class="container-fluid">
    <div class="row align-items-center">
      <div class="col-12 col-md-6 text-center text-md-start mb-2 mb-md-0">
        <small>© <?= date('Y'); ?> Gökhan TAŞCI - 139329 - Sakarya Yazı İşleri Müdürü</small>
      </div>
      <div class="col-12 col-md-6 text-center text-md-end">
        <small>
          <a href="#" id="openPrivacyModal" class="text-white">Gizlilik bildirimi</a>
          <span class="mx-2">•</span>
          Dosyalar sunucuya yüklenmez
        </small>
      </div>
    </div>
  </div>
</footer>

<!-- XLS Loading Overlay (shown while parsing Excel) -->
<div id="xlsLoadingOverlay" class="position-fixed top-0 start-0 w-100 h-100 d-none align-items-center justify-content-center" style="background: rgba(0,0,0,0.5); z-index: 9999;">
  <div class="bg-white rounded p-4 text-center" style="min-width: 260px;">
    <div class="spinner-border text-primary mb-3" role="status">
      <span class="visually-hidden">Yükleniyor...</span>
    </div>
    <h5>Veriler hazırlanıyor…</h5>
    <p class="text-muted mb-0">Lütfen bekleyiniz, Excel işleniyor.</p>
  </div>
  <script>
    (function(){
      let visibleSince = 0; const MIN_MS = 350;
      function show(){
        const el = document.getElementById('xlsLoadingOverlay'); 
        if(!el) return;
        el.classList.remove('d-none');
        el.classList.add('d-flex');
        visibleSince = performance.now();
      }
      function hide(){
        const el = document.getElementById('xlsLoadingOverlay'); 
        if(!el) return;
        const elapsed = performance.now() - visibleSince;
        if (elapsed < MIN_MS){
          setTimeout(()=>{ el.classList.add('d-none'); el.classList.remove('d-flex'); }, MIN_MS - elapsed);
        } else {
          el.classList.add('d-none');
          el.classList.remove('d-flex');
        }
      }
      window.XlsSpinner = { show, hide };
    })();
    window.setInlineXlsLoading = function(container, active){
      try {
        if(!container) return; 
        const el = (typeof container === 'string') ? document.querySelector(container) : container;
        if(!el) return; 
        el.classList.toggle('active', !!active);
      } catch(e) { /* noop */ }
    };
  </script>
</div>

<!-- Gizlilik Modal -->
<div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyTitle" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="privacyTitle">
          <span class="material-symbols-rounded align-middle me-2">info</span>
          Hatırlatma
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0">
          Bu uygulamada hesaplamalar ve raporlamalar için seçtiğiniz dosyaların hiçbiri sunucuya yüklenmemekte, 
          tümü kendi tarayıcınız üzerinden hesaplanmaktadır.
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Anladım</button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap 5.3 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Privacy modal handler
document.getElementById('openPrivacyModal')?.addEventListener('click', function(e) {
  e.preventDefault();
  const modal = new bootstrap.Modal(document.getElementById('privacyModal'));
  modal.show();
});

// İlk ziyaret kontrolü - Hatırlatma modalini otomatik aç
document.addEventListener('DOMContentLoaded', function() {
  const hasVisited = localStorage.getItem('adalet-first-visit');
  
  if (!hasVisited) {
    // İlk ziyaret - modalı göster
    const privacyModal = document.getElementById('privacyModal');
    if (privacyModal) {
      const modal = new bootstrap.Modal(privacyModal);
      modal.show();
      
      // Modal kapatıldığında localStorage'a kaydet
      privacyModal.addEventListener('hidden.bs.modal', function() {
        localStorage.setItem('adalet-first-visit', 'true');
        console.log('[HATIRLATMA] İlk ziyaret kaydedildi');
      });
      
      console.log('[HATIRLATMA] İlk ziyaret - modal gösterildi');
    }
  } else {
    console.log('[HATIRLATMA] Kullanıcı daha önce ziyaret etmiş');
  }
});
</script>

</body>
</html>

