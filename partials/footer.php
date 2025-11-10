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
          <a href="#" id="openPrivacyModal">Gizlilik bildirimi</a>
          <span class="mx-2">•</span>
          <a href="#" id="openRecommendModal">Bizi Önerin</a>
          <span class="mx-2">•</span>
          Dosyalar sunucuya yüklenmez
        </small>
      </div>
    </div>
  </div>
</footer>

<!-- Scroll to Top Button -->
<button id="scrollToTop" class="scroll-to-top" aria-label="Yukarı git" title="Yukarı git">
  <span class="material-symbols-rounded">arrow_upward</span>
</button>

<!-- XLS Loading Overlay (shown while parsing Excel) -->
<div id="xlsLoadingOverlay" class="position-fixed top-0 start-0 w-100 h-100 d-none align-items-center justify-content-center" style="background: rgba(0,0,0,0.7); backdrop-filter: blur(8px); z-index: 9999; cursor: not-allowed;">
  <div class="rounded p-4 text-center" style="min-width: 260px; background: var(--adalet-card-bg); color: var(--adalet-text);">
    <div class="spinner-border mb-3" style="width: 3rem; height: 3rem; border-width: 0.3rem; color: var(--adalet-primary);" role="status">
      <span class="visually-hidden">Yükleniyor...</span>
    </div>
    <h5>Veriler hazırlanıyor…</h5>
    <p class="text-muted mb-0">Lütfen bekleyiniz, Excel işleniyor.</p>
  </div>
  <script>
    (function(){
      let visibleSince = 0; const MIN_MS = 2500;
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

<!-- Bizi Önerin Modal -->
<div class="modal fade" id="recommendModal" tabindex="-1" aria-labelledby="recommendTitle" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary bg-opacity-10">
        <h5 class="modal-title d-flex align-items-center gap-2" id="recommendTitle">
          <span class="material-symbols-rounded text-primary">share</span>
          <strong>Bizi Önerin</strong>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <p>Bu uygulamayı beğendiyseniz, lütfen meslektaşlarınıza önerin!</p>
        <div class="mb-3">
          <label for="recommendEmail" class="form-label">E-posta Adresi</label>
          <div class="input-group">
            <input type="text" class="form-control" id="recommendEmailUser" placeholder="ab139329" maxlength="50">
            <span class="input-group-text">@adalet.gov.tr</span>
          </div>
          <div class="form-text">Meslektaşınızın kullanıcı adını girin (örn: ab139329)</div>
        </div>
        <div class="alert alert-info d-flex align-items-start gap-2 mb-0">
          <span class="material-symbols-rounded">info</span>
          <small>Uygulama linki: <strong>https://657.com.tr</strong></small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
        <button type="button" class="btn btn-primary" id="sendRecommendBtn">
          <span class="material-symbols-rounded me-1" style="font-size: 1rem;">send</span>
          Gönder
        </button>
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

// Recommend modal handler
document.getElementById('openRecommendModal')?.addEventListener('click', function(e) {
  e.preventDefault();
  const modal = new bootstrap.Modal(document.getElementById('recommendModal'));
  modal.show();
});

// Recommend send button handler
document.getElementById('sendRecommendBtn')?.addEventListener('click', async function() {
  const emailUser = document.getElementById('recommendEmailUser').value.trim();
  
  if (!emailUser) {
    if (typeof window.toast === 'function') {
      window.toast({
        type: 'warning',
        title: 'Uyarı',
        body: 'Lütfen kullanıcı adını girin.',
        delay: 3000
      });
    }
    return;
  }
  
  // Kullanıcı adı validasyonu (alfanumerik ve bazı özel karakterler)
  const usernameRegex = /^[a-zA-Z0-9._-]+$/;
  if (!usernameRegex.test(emailUser)) {
    if (typeof window.toast === 'function') {
      window.toast({
        type: 'danger',
        title: 'Hata',
        body: 'Geçerli bir kullanıcı adı girin (örn: ab139329).',
        delay: 3000
      });
    }
    return;
  }
  
  const fullEmail = emailUser + '@adalet.gov.tr';
  
  // Mail içeriğini app.js'teki gibi oluştur
  function formatDateTimeTR() {
    const now = new Date();
    const d = String(now.getDate()).padStart(2, '0');
    const m = String(now.getMonth() + 1).padStart(2, '0');
    const y = now.getFullYear();
    const h = String(now.getHours()).padStart(2, '0');
    const min = String(now.getMinutes()).padStart(2, '0');
    return `${d}/${m}/${y} ${h}:${min}`;
  }
  
  const LINE_DATE = formatDateTimeTR() + ' teftis.657.com.tr adresine mail adresiniz bırakılması nedeniyle bu maili almaktasınız.';
  const mailBody = [
    'Merhaba Sevgili Meslektaşım,',
    '',
    LINE_DATE,
    'Gerekli sayfaya erişim için: teftis.657.com.tr adresini tarayıcınıza (Google Chrome ya da Edge) yazabilirsiniz.',
    'Kopyalamak için : ',
    '',
    'teftis.657.com.tr',
    '',
    '',
    'İyi çalışmalar dilerim.',
    '',
    '',
    '--------------------------',
    'Gökhan TAŞÇI',
    'Yazı İşleri Müdürü 139329',
    'Sakarya Adliyesi'
  ].join('\n');
  
  // Loading durumu
  const btn = this;
  const originalHTML = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Gönderiliyor...';
  
  try {
    const response = await fetch('/api/send-mail.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        to: fullEmail,
        subject: 'Teftiş bilgilendirme hk.',
        body: mailBody,
        reply_to: 'gkhntasci@gmail.com',
        from_name: 'Teftiş Sistemi'
      })
    });
    
    const result = await response.json();
    
    if (result.ok) {
      if (typeof window.toast === 'function') {
        window.toast({
          type: 'success',
          title: 'Başarılı',
          body: `E-posta ${fullEmail} adresine gönderildi!`,
          delay: 4000
        });
      }
      
      // Close modal and reset form
      const modal = bootstrap.Modal.getInstance(document.getElementById('recommendModal'));
      modal.hide();
      document.getElementById('recommendEmailUser').value = '';
    } else {
      throw new Error(result.error || 'Mail gönderilemedi');
    }
  } catch (error) {
    if (typeof window.toast === 'function') {
      window.toast({
        type: 'danger',
        title: 'Hata',
        body: 'Mail gönderilirken bir hata oluştu: ' + error.message,
        delay: 5000
      });
    }
  } finally {
    btn.disabled = false;
    btn.innerHTML = originalHTML;
  }
});

// İlk ziyaret kontrolü - Hatırlatma modalını otomatik aç
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
      });
    }
  }
  
  // Bizi Önerin modal - 3 sayfa ziyareti, günde 1 kez
  try {
    const pageVisits = parseInt(localStorage.getItem('adalet-page-visits') || '0', 10);
    const lastRecommendDate = localStorage.getItem('adalet-last-recommend-date');
    const today = new Date().toDateString();
    
    // Sayfa ziyaret sayısını artır
    localStorage.setItem('adalet-page-visits', String(pageVisits + 1));
    
    // 3. ziyaretten sonra ve bugün henüz gösterilmediyse
    if (pageVisits >= 2 && lastRecommendDate !== today) {
      setTimeout(() => {
        const recommendModal = document.getElementById('recommendModal');
        if (recommendModal) {
          const modal = new bootstrap.Modal(recommendModal);
          modal.show();
          localStorage.setItem('adalet-last-recommend-date', today);
        }
      }, 2000); // 2 saniye bekle
    }
  } catch(e) { /* noop */ }
});
</script>

<!-- Haber Kutusu Script -->
<script src="/assets/js/news-box.js"></script>

<!-- GTM Button Tracking -->
<script src="/assets/js/gtm-tracking.js"></script>

</body>
</html>

