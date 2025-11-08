<?php
  header('Content-Type: text/html; charset=UTF-8');
?><!DOCTYPE html>
<html lang="tr" data-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kesinleşme Zamanı Kontrol · Teftis</title>
  <link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,600,0,0" rel="stylesheet">
  <link rel="stylesheet" href="/data/style.css">
  <style>
    .drop-zone{border:2px dashed var(--border); border-radius:12px; padding:16px; text-align:center;}
    .drop-zone.dragover{background: color-mix(in oklab, var(--panel) 88%, var(--bg));}
    .hint{color: var(--muted); font-size: 13px;}
    .file-list{display:grid; gap:8px; margin-top:10px}
    .file-item{display:flex; align-items:center; justify-content:space-between; border:1px solid var(--border); border-radius:10px; padding:8px 10px;}
    .badge-sm{font-size:11px; padding:3px 6px; border-radius:999px; border:1px solid var(--border);}
    .muted{color: var(--muted);}
    .kpi-grid{display:grid; grid-template-columns: repeat(4, 1fr); gap:12px;}
    .kpi-card{border:1px solid var(--border); border-radius:12px; padding:12px;}
    .kpi-title{color: var(--muted); font-weight:600;}
    .kpi-value{font-weight:800; font-size:28px; margin-top:6px;}
    .table-wrap{overflow:auto;}
    .sr-only{position:absolute; left:-9999px; width:1px; height:1px; overflow:hidden;}
  </style>
  <script src="https://cdn.jsdelivr.net/npm/xlsx@0.20.2/dist/xlsx.full.min.js"></script>
</head>
<body>
  <div class="layout">
    <header class="navbar" role="banner">
      <div class="brand">
        <button id="sidebarToggle" class="icon-btn" aria-label="Menüyü aç/kapat"><span class="material-symbols-rounded">menu</span></button>
        <span class="brand-title">Teftis</span>
      </div>
      <div class="navbar-actions">
        <button id="themeToggle" class="icon-btn" title="Temayı değiştir"><span id="themeIcon" class="material-symbols-rounded">dark_mode</span></button>
      </div>
    </header>

    <aside id="sidebar" class="sidebar" role="navigation" aria-label="Yan menü">
      <div class="menu-section">Menü</div>
      <nav class="menu">
        <a class="menu-item" href="harctahsilkontrol.php">
          <span class="material-symbols-rounded">paid</span>
          <span class="label">Harç Tahsil Kontrol</span>
        </a>
        <a class="menu-item active" href="#">
          <span class="material-symbols-rounded">event_available</span>
          <span class="label">Kesinleşme Zamanı</span>
        </a>
      </nav>
    </aside>

    <main class="content" role="main">
      <div class="page-header">
        <h1>Kesinleşme Zamanı Kontrol</h1>
        <p class="muted">UDF yükleyin, ardından aynı dosyalarla ilişkilendirilecek XLS/XLSX ekleyin. Eşleşme Esas No üzerinden yapılır (Excel E sütunu).</p>
      </div>

      <div class="container">
        <section id="cardUstSol" class="ustsol">
          <div class="grid-stack">
            <section class="card kpi-grid" aria-label="Özet Göstergeler">
              <div class="kpi-card">
                <div class="kpi-title">UDF Kayıt</div>
                <div id="kpiUdf" class="kpi-value">0</div>
              </div>
              <div class="kpi-card">
                <div class="kpi-title">Excel Satırı</div>
                <div id="kpiXls" class="kpi-value">0</div>
              </div>
              <div class="kpi-card">
                <div class="kpi-title">Eşleşen Esas</div>
                <div id="kpiMatch" class="kpi-value">0</div>
              </div>
              <div class="kpi-card">
                <div class="kpi-title">Kontrole Tabi</div>
                <div id="kpiKontrol" class="kpi-value">0</div>
              </div>
            </section>

            <section class="card card-upload" aria-labelledby="uploadTitle">
              <div class="card-head">
                <h2 id="uploadTitle" class="page-title">Dosya Yükleme</h2>
                <div class="actions-row" style="margin-left:auto">
                  <label for="udfInput" class="btn ghost"><span class="material-symbols-rounded">upload_file</span>UDF Yükle</label>
                  <input id="udfInput" type="file" accept=".udf,.xml,.json" hidden>
                  <label for="excelInput" class="btn"><span class="material-symbols-rounded">upload</span>XLS/XLSX Ekle</label>
                  <input id="excelInput" type="file" accept=".xls,.xlsx" hidden multiple>
                  <button id="btnMerge" class="btn ghost" type="button"><span class="material-symbols-rounded">join</span>Birleştir</button>
                  <button id="btnExport" class="btn" type="button"><span class="material-symbols-rounded">file_download</span>Excel'e Aktar</button>
                </div>
              </div>
              <div class="card-body">
                <div id="udfDrop" class="drop-zone" tabindex="0" aria-label="UDF bırakma alanı">
                  <strong>UDF bırakın</strong>
                  <div class="hint">.udf / .xml / .json — tek dosya</div>
                </div>
                <div id="excelDrop" class="drop-zone" style="margin-top:10px" tabindex="0" aria-label="Excel bırakma alanı">
                  <strong>XLS/XLSX bırakın</strong>
                  <div class="hint">Birden fazla dosya ekleyebilirsiniz</div>
                </div>
                <div id="excelList" class="file-list" aria-live="polite"></div>
              </div>
            </section>

            <section id="result-card" class="card">
              <div class="panel-head panel-head--with-actions">
                <strong>Sonuçlar</strong>
                <div class="actions">
                  <input id="q" class="input" type="search" placeholder="Ara (Esas No / Ad...)" />
                  <button id="btnClear" class="btn ghost" type="button">Temizle</button>
                </div>
              </div>
              <div class="panel-body table-wrap">
                <table id="resultTable" class="table">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Esas No (E)</th>
                      <th>Taraf/Ad</th>
                      <th>Tebligat Tarihi</th>
                      <th>Süre</th>
                      <th>Son Gün</th>
                      <th>Durum</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                  <tfoot>
                    <tr><td colspan="7" class="muted">UDF + Excel birleştikten sonra doldurulur.</td></tr>
                  </tfoot>
                </table>
              </div>
            </section>
          </div>
        </section>

        <aside class="ustsag">
          <section class="sidecard">
            <details class="settings" open>
              <summary><span class="material-symbols-rounded">tune</span> Ayarlar</summary>
              <div class="content">
                <div class="row">
                  <div>
                    <label for="holidaySrc">Tatiller</label>
                    <input id="holidaySrc" type="text" value="/data/tatiller.json" />
                  </div>
                  <div>
                    <label for="sureDefault">Varsayılan Süre (gün)</label>
                    <input id="sureDefault" type="text" value="7" />
                  </div>
                  <div>
                    <label for="iconChoice">Menü İkonu</label>
                    <input id="iconChoice" type="text" value="event_available" />
                  </div>
                </div>
                <button id="saveBtn" class="btn small" type="button">Kaydet</button>
              </div>
            </details>

            <section id="extraSummary" class="card summary-card">
              <h3 class="card-title"><span class="material-symbols-rounded">summarize</span>Özet</h3>
              <div id="summaryBody" class="muted">Henüz veri yok.</div>
            </section>
          </section>
        </aside>

        <section class="altsol">
          <div class="card">
            <div class="panel-head"><strong>Notlar</strong></div>
            <div class="panel-body">
              <div id="notes" class="note-list"></div>
              <button class="btn" onclick="addNote()">Yeni Not</button>
            </div>
          </div>
        </section>

        <section class="altsag">
          <div id="newsCard" class="card news-drop">
            <div class="news-drop__body">
              <div class="news-drop__icon material-symbols-rounded">campaign</div>
              <div class="news-drop__texts">
                <div class="news-drop__title">Sürüm Notları</div>
                <div id="newsMeta" class="news-meta muted">—</div>
                <div class="news-drop__content">
                  <ol id="newsList" class="news-list"></ol>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </main>

    <footer class="footer">
      <div>© <?php echo date('Y'); ?> Teftis</div>
      <div class="muted">Kesinleşme Zamanı Kontrol</div>
    </footer>
  </div>

  <div class="toast-container" aria-live="polite" aria-atomic="true"></div>

  <script src="/data/app.js"></script>
  <script src="/assets/js/kesinlesme-kontrol.js?v=1"></script>
</body>
</html>
