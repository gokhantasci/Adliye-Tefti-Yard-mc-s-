(function(){
  const $ = (s) => document.querySelector(s);
  const input = $('#excelInput');
  const runBtn = $('#run');
  if (!input) return;
  // Prevent the browser default behavior of opening files when dropped outside the drop zone
  // (drops on the document can navigate to the file). We prevent default globally and
  // let the dropzone handle the actual files.
  function _globalPrevent(e){ e.preventDefault(); e.stopPropagation && e.stopPropagation(); }
  document.addEventListener('dragover', _globalPrevent);
  document.addEventListener('drop', _globalPrevent);
  const dropZone = document.getElementById('dropZone');
  let resultCard = $('#resultCard');
  // References to parts of the result card; will be set when the card is created.
  let headEl = null;
  let bodyEl = null;

  // Factory: create the results panel only when the first file is processed.
  function createResultCard(){
    if (resultCard) return;
    const panel = document.createElement('section');
    panel.className = 'panel';
    panel.id = 'resultCard';
    panel.style.marginTop = '14px';
    panel.innerHTML = `
      <div class="panel-head"><h3><span class="material-symbols-rounded" style="vertical-align:-4px;margin-right:6px;">analytics</span> Sonuçlar</h3></div>
      <div class="panel-body">
        <div class="cards cards--3" style="margin-bottom: 14px;">
          <div class="card kpi"><div class="kpi-head"><span class="kpi-title">Toplam</span></div><div class="kpi-value" id="kpiTotal">0</div></div>
          <div class="card kpi"><div class="kpi-head"><span class="kpi-title">Mahkumiyet</span></div><div class="kpi-value" id="kpiMah">0</div></div>
          <div class="card kpi"><div class="kpi-head"><span class="kpi-title">HAGB</span></div><div class="kpi-value" id="kpiHagb">0</div></div>
          <div class="card kpi"><div class="kpi-head"><span class="kpi-title">Gör/Yet/Birleş</span></div><div class="kpi-value" id="kpiGyb">0</div></div>
          <div class="card kpi"><div class="kpi-head"><span class="kpi-title">BERAAT</span></div><div class="kpi-value" id="kpiBer">0</div></div>
          <div class="card kpi"><div class="kpi-head"><span class="kpi-title">RED</span></div><div class="kpi-value" id="kpiRed">0</div></div>
          <div class="card kpi"><div class="kpi-head"><span class="kpi-title">Tazminat</span></div><div class="kpi-value" id="kpiTaz">0</div></div>
          <div class="card kpi"><div class="kpi-head"><span class="kpi-title">Düşme/Cvyo/Diğer</span></div><div class="kpi-value" id="kpiDgr">0</div></div>
        </div>
        <div class="table-wrap" style="overflow:auto;max-height:420px;border:1px dashed var(--border,#e7eaf0); border-radius:12px;">
          <table class="table report-table" id="resultTable">
            <thead><tr id="resultHead"></tr></thead>
            <tbody id="resultBody"></tbody>
          </table>
        </div>
      </div>`;
    // Prefer the left-column report container when present so results appear in the main report area
    const preferredHost = document.getElementById('reportContainer') || document.querySelector('.panel-body');
    if (preferredHost && typeof preferredHost.appendChild === 'function') {
      preferredHost.appendChild(panel);
    } else if (preferredHost && preferredHost.parentElement) {
      preferredHost.parentElement.after(panel);
    } else {
      document.body.appendChild(panel);
    }
    resultCard = panel;
    headEl = resultCard.querySelector('#resultHead');
    bodyEl = resultCard.querySelector('#resultBody');
    // hide initially until first render
    resultCard.style.display = 'none';
  }
  function parseCSV(text){
    const hasSemicolon = text.indexOf(';') > -1;
    const hasComma = text.indexOf(',') > -1;
    const sep = hasSemicolon && !hasComma ? ';' : ',';
    const rows = text.split(/\r?\n/).filter(r => r.trim().length > 0).map(r => r.split(sep).map(c => c.replace(/^\uFEFF/, '').trim()));
    return {header: rows[0] || [], rows: rows.slice(1)};
  }
  // Wait for the xlsx-loader to initialize (fires 'xlsx-ready' CustomEvent)
  let _xlsxReady = null;
  function waitForXlsxReady(timeout = 3000){
    if (_xlsxReady) return _xlsxReady;
    _xlsxReady = new Promise(resolve => {
      // If XLSX already present, resolve true
      if (window.XLSX && typeof XLSX.read === 'function') return resolve(true);
      // Listen for loader event
      function onReady(e){
        try { document.removeEventListener('xlsx-ready', onReady); } catch (e){}
        resolve(!!(e && e.detail && e.detail.ok));
      }
      document.addEventListener('xlsx-ready', onReady);
      // Fallback timeout
      setTimeout(() => {
        // If XLSX is now present, ok; otherwise resolve false
        resolve(!!(window.XLSX && typeof XLSX.read === 'function'));
      }, timeout);
    });
    return _xlsxReady;
  }

  async function parseXLSX(file){
    console.debug('[karar-upload] parseXLSX start', file && file.name);
    const ok = await waitForXlsxReady();
    if (!ok || !(window.XLSX && typeof XLSX.read === 'function')){
      console.error('[karar-upload] XLSX not available');
      throw new Error('XLSX kütüphanesi yüklenemedi. Lütfen internet bağlantınızı veya /assets/js/xlsx.full.min.js dosyasını kontrol edin. Alternatif olarak .csv kullanabilirsiniz.');
    }
    const data = await file.arrayBuffer();
    const wb = XLSX.read(data, {type:'array'});
    const sheet = wb.Sheets[wb.SheetNames[0]];
    const json = XLSX.utils.sheet_to_json(sheet, {header:1, raw:true});
    const header = (json[0] || []).map(String);
    const rows = json.slice(1).map(r => r.map(v => (v == null ? '' : v)));
    return {header, rows};
  }
  function toNumber(v){
    const n = Number(String(v).replace(/\./g,'').replace(',','.'));
    return isFinite(n) ? n : 0;
  }
  function resolveIndexes(header){
    const upper = header.map(h => String(h).trim().toUpperCase());
    const letterToIndex = (L) => {
      L = L.toUpperCase();
      let idx = 0;
      for (let i = 0;i < L.length;i++){ idx = idx * 26 + (L.charCodeAt(i) - 64); }
      return idx - 1;
    };
    const byName = (names) => {
      for (const n of names){
        const i = upper.indexOf(n.toUpperCase());
        if (i > -1) return i;
      }
      return -1;
    };
    const idx = {
      M: byName(['BERAAT','BER']),
      O: byName(['MAHKUMIYET','MAHKUMİYET','HÜKÜM','HUKUM','CEZA']),
      P: byName(['HAGB','HÜKMÜN AÇIKLANMASININ GERİ BIRAKILMASI']),
      Q: byName(['RED','RET']),
      T: byName(['GÖR/YET/BİRLEŞ','GÖREV','YETKİ','BİRLEŞ']),
      Z: byName(['TAZMİNAT','TAZMINAT'])
    };
    const fallback = {M:'M', O:'O', P:'P', Q:'Q', T:'T', Z:'Z'};
    for (const k of Object.keys(fallback)){
      if (idx[k] < 0) idx[k] = letterToIndex(fallback[k]);
    }
    return idx;
  }
  function classifyRow(row, idx){
    const vO = toNumber(row[idx.O]);
    const vP = toNumber(row[idx.P]);
    const vT = toNumber(row[idx.T]);
    const vM = toNumber(row[idx.M]);
    const vQ = toNumber(row[idx.Q]);
    const vZ = toNumber(row[idx.Z]);
    if (vO > 0) return 'Mahkumiyet';
    if (vP > 0) return 'HAGB';
    if (vT > 0) return 'Gör/Yet/Birleş';
    if (vM > 0) return 'BERAAT';
    if (vQ > 0) return 'RED';
    if (vZ > 0) return 'Tazminat';
    return 'Düşme/Cvyo/Diğer';
  }
  function render(header, rows, idx){
    console.debug('[karar-upload] render', { headerLength: (header||[]).length, rowsLength: (rows||[]).length });
    // Ensure results panel exists before attempting to render
    if (!resultCard || !headEl || !bodyEl) createResultCard();
    headEl.innerHTML = '';
    const head = document.createDocumentFragment();
    header.forEach(h => { const th = document.createElement('th'); th.textContent = h; head.appendChild(th); });
    { const th = document.createElement('th'); th.textContent = 'Tür'; head.appendChild(th); }
    headEl.appendChild(head);
    const counts = { total:0, Mahkumiyet:0, HAGB:0, 'Gör/Yet/Birleş':0, BERAAT:0, RED:0, Tazminat:0, 'Düşme/Cvyo/Diğer':0 };
    bodyEl.innerHTML = '';
    const frag = document.createDocumentFragment();
    rows.forEach(r => {
      if (r.every(c => String(c).trim() === '')) return;
      const typ = classifyRow(r, idx);
      counts.total++; counts[typ] = (counts[typ] || 0) + 1;
      const tr = document.createElement('tr');
      header.forEach((_,i) => { const td = document.createElement('td'); td.textContent = r[i] ?? ''; tr.appendChild(td); });
      const td = document.createElement('td'); td.textContent = typ; tr.appendChild(td);
      frag.appendChild(tr);
    });
    bodyEl.appendChild(frag);
    const set = (id,v) => { const el = document.getElementById(id); if (el) el.textContent = v; };
    set('kpiTotal', counts.total);
    set('kpiMah', counts.Mahkumiyet || 0);
    set('kpiHagb', counts.HAGB || 0);
    set('kpiGyb', counts['Gör/Yet/Birleş'] || 0);
    set('kpiBer', counts.BERAAT || 0);
    set('kpiRed', counts.RED || 0);
    set('kpiTaz', counts.Tazminat || 0);
    set('kpiDgr', counts['Düşme/Cvyo/Diğer'] || 0);
    resultCard.style.display = '';
  }
  async function runFromFile(f){
    console.debug('[karar-upload] runFromFile called', f && f.name);
    let parsed;
    const ext = (f.name.split('.').pop() || '').toLowerCase();
    // Helper: show a small inline spinner inside the report container or next to the drop zone
    function createInlineSpinner(host){
      try {
        if (!host) return null;
        const wrapper = document.createElement('div');
        wrapper.className = 'inline-xls-spinner';
        wrapper.innerHTML = `
          <div class="d-flex align-items-center gap-2 p-2" style="background:var(--adalet-card-bg);border-radius:8px;border:1px solid var(--adalet-border);">
            <div class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></div>
            <div class="small text-muted">Veriler hazırlanıyor…</div>
          </div>`;
        host.appendChild(wrapper);
        return wrapper;
      } catch (e) { return null; }
    }

    function removeInlineSpinner(sp){ if (!sp) return; try { sp.remove(); } catch(e){} }

    // Show inline spinner
    const reportHost = document.getElementById('reportContainer') || document.querySelector('.panel-body') || document.body;
    const _spinner = createInlineSpinner(reportHost);
    try {
      if (ext === 'xlsx' || ext === 'xls'){
        parsed = await parseXLSX(f);
      } else if (ext === 'csv'){
        parsed = parseCSV(await f.text());
      } else {
        const msg = 'Desteklenen uzantılar: .xlsx, .xls, .csv';
        if (window.toast) window.toast({type:'warning', title:'Dosya Türü', body: msg}); else console.warn(msg);
        return;
      }
    } catch (err){
      const msg = err && err.message ? err.message : 'Dosya okunamadı.';
      console.error('[karar-upload] runFromFile error', err);
      if (window.toast) window.toast({type:'danger', title:'Hata', body: msg}); else console.error(msg);
      return;
    } finally {
      // Remove spinner regardless of success/failure
      removeInlineSpinner(_spinner);
    }

    const idx = resolveIndexes(parsed.header);
    render(parsed.header, parsed.rows, idx);
  }
  function onInputChanged(e){
    const f = e.target.files && e.target.files[0];
    if (f) runFromFile(f);
  }
  input.addEventListener('change', onInputChanged);
  if (runBtn){
    runBtn.addEventListener('click', () => {
      const f = input.files && input.files[0];
      if (!f) { alert('Lütfen bir dosya seçin.'); return; }
      onInputChanged({target: {files:[f]}});
    });
  }

  // Dropzone etkileşimi (sürükle-bırak)
  if (dropZone){
    dropZone.addEventListener('click', () => input && input.click());
    ['dragenter','dragover'].forEach(ev => dropZone.addEventListener(ev, e => { e.preventDefault(); dropZone.classList.add('is-over'); }));
    ['dragleave','drop'].forEach(ev => dropZone.addEventListener(ev, e => { e.preventDefault(); dropZone.classList.remove('is-over'); }));
    dropZone.addEventListener('drop', e => {
      e.preventDefault(); e.stopPropagation && e.stopPropagation();
      const dt = e.dataTransfer; if (!dt || !dt.files || dt.files.length === 0) return;
      const f = dt.files[0];
      // Fire and forget - runFromFile handles errors and toasts
      runFromFile(f);
    });
  }
})();
