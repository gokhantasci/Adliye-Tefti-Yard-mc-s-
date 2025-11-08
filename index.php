<?php
  /*
   * Panel / Anasayfa
   * - Uygulama güncellemeleri ve e-posta bırakma kutusu
   */
  $active = "dashboard";
  include __DIR__."/partials/header.php";
  include __DIR__."/partials/navbar.php";
  include __DIR__."/partials/sidebar.php";
?>
<main class="content">
  <div class="page-header">
    <h1>Panel</h1>
    <p class="muted">Uygulama güncellemeleri ve e-posta bırakma kutusu</p>
  </div>
  <section class="cards cards--2">
    <!-- UYGULAMA GÜNCELLEMELERİ / HABER -->
    <article class="card news-drop" id="newsCard" role="region" aria-labelledby="newsTitle">
      <div class="news-drop__body">
        <div class="news-drop__icon" aria-hidden="true">📰</div>
        <div class="news-drop__texts">
          <h3 id="newsTitle" class="news-drop__title">Uygulama Güncellemeleri / Haber</h3>
          <p class="news-drop__hint">Platformdaki duyuru ve değişiklikler.</p>
          <!-- .news-drop__actions BLOĞU KALDIRILDI (Yenile butonu yok) -->
          <div class="news-drop__content">
            <div id="newsMeta" class="news-meta muted" aria-live="polite"></div>
            <!-- Liste artık <div>, numara yok -->
            <div id="newsList" class="news-list"></div>
            <!-- Pager kaldırıldı -->
          </div>
        </div>
      </div>
    </article>
    <!-- E-POSTA BIRAKMA KUTUSU -->
    <article class="card mail-drop" id="mailDropBox" role="region" aria-labelledby="mailDropTitle">
      <div class="mail-drop__body">
        <div class="mail-drop__icon" aria-hidden="true">✉️</div>
        <div class="mail-drop__texts">
          <h3 id="mailDropTitle" class="mail-drop__title">E-posta Adresini bırak</h3>
          <p class="mail-drop__hint">Buraya e-posta adresini <b> bırak</b>, sana site adresini mail atalım.</p>
          <div class="mail-drop__input">
            <input id="mailDropInput" type="email" placeholder="ab139329@adalet.gov.tr" autocomplete="email" inputmode="email" aria-label="E-posta adresi">
            <button id="mailDropSendBtn" class="btn btn-primary" type="button" disabled>Mesajı Gönder</button>
          </div>
          <div id="mailDropToast" class="mail-drop__toast" aria-live="polite"></div>
          <input id="mailHp" class="hp" type="text" aria-hidden="true" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px;width:1px;height:1px;opacity:0;" />
          <div class="mail-drop__preview">
            <pre id="mailPreview" class="mail-drop__pre" aria-hidden="true" hidden></pre>
          </div>
          <nav id="newsPager" class="pager" role="navigation" aria-label="Haber sayfalama"></nav>
        </div>
      </div>
    </article>
  </section>
</main>
<?php include __DIR__."/partials/footer.php"; ?>
