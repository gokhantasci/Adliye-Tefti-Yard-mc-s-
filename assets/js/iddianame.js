(() => {
  "use strict";
  if (window.__iddianameBooted) return;
  window.__iddianameBooted = true;
  

  const $ = (s, r=document) => r.querySelector(s);
  const PAGE_SIZE = 20;
  const fmtInt = n => new Intl.NumberFormat("tr-TR").format(n || 0);

  // ---------- helpers
  function toast(type, title, body){
    if (typeof window.toast === "function") window.toast({type, title, body});
    else console[type === 'danger' ? 'error' : type === 'warning' ? 'warn' : 'log'](`${title}: ${body}`);
  }
  const norm = (s) => String(s ?? "")
    .replace(/\u00A0/g," ").replace(/\r?\n+/g," ")
    .trim().toLowerCase().replace(/\s+/g," ")
    .replaceAll("ı","i").replaceAll("İ","i")
    .replaceAll("ş","s").replaceAll("Ş","s")
    .replaceAll("ğ","g").replaceAll("Ğ","g")
    .replaceAll("ö","o").replaceAll("Ö","o")
    .replaceAll("ü","u").replaceAll("Ü","u")
    .replaceAll("ç","c").replaceAll("Ç","c");
  const cell = (aoa, r, c) => (aoa?.[r]?.[c] ?? "");
  const txt  = (v) => String(v ?? "").trim();
  const esc  = (s) => String(s ?? "").replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[m]));

  // --- doğrulayıcılar
  const RX_ID = /^\s*\d{4}\s*\/\s*\d{1,6}\s*$/;      // C ve F: YYYY/N...
  const RX_DT = /^\s*\d{2}\/\d{2}\/\d{4}\s*$/;       // G: dd/mm/yyyy
  function isValidId(v){ return RX_ID.test(String(v||"")); }
  function isValidDate(v){ return RX_DT.test(String(v||"")); }

  // 0-bazlı indeksler
  const COL = { C:2, D:3, F:5, G:6, I:8, J:9, K:10, L:11 };
  const ROW = { TITLE:2, BIRIM:4, ARALIK:5, HEADER:10, DATA_START:11 }; // D3,F5,F6,C11 başlık; veri C12
  const TITLE_NEEDLE = norm("İDDİANAME DEĞERLENDİRİLME ZAMAN KONTROLÜ");

  const state = {
    rows: [], sheetName: "", birimAdi: "", denetimAraligi: "", currentPage: 1
  };

  function isDataHeaderRow(row){
    const C = norm(row[2]), F = norm(row[5]), G = norm(row[6]),
          I = norm(row[8]), J = norm(row[9]), K = norm(row[10]), L = norm(row[11]);
    let score = 0;
    if (C.startsWith("iddianame no")) score++;
    if (F.includes("degerlendirme no") || F.includes("değerlendirme no")) score++;
    if (G.includes("gonderildigi") || G.includes("gönderildiği")) score++;
    if (I.includes("degerlendirme tarihi") || I.includes("değerlendirme tarihi")) score++;
    if (J.includes("kabul") || J.includes("iade") || J.includes("degerlendirme") || J.includes("değerlendirme")) score++;
    if (K.includes("sure") || K.includes("süre")) score++;
    if (L === "hakim" || L === "hakim adi" || L === "hakim adı") score++;
    return score >= 6;
  }

  function looksLikeIddianameNo(s){
    const v = String(s || "").trim();
    return /^\d{4}\s*[/\-]\s*\d{1,6}$/.test(v);
  }

  // ---------- Excel oku (StyleSheet’e sabit)
  async function readSheetExact(file){
    const XLSX = window.XLSX;
    if (!XLSX){ toast('danger','Bağımlılık','xlsx-loader.js yüklenemedi.'); return null; }

    const ab = await file.arrayBuffer();
    const wb = XLSX.read(ab, { type:"array", cellDates:true, dateNF:"dd.mm.yyyy" });

    const sheetName = wb.SheetNames.includes("StyleSheet") ? "StyleSheet" : wb.SheetNames[0];
    const ws  = wb.Sheets[sheetName];
    const aoa = XLSX.utils.sheet_to_json(ws, { header:1, raw:false, defval:"" });

    // D3 başlık kontrolü
    const d3 = cell(aoa, ROW.TITLE, COL.D);
    if (!d3 || !norm(d3).includes(TITLE_NEEDLE)){
      toast('danger','Hatalı Tablo','D3 hücresinde beklenen başlık yok. Lütfen orijinal tabloyu yükleyiniz.');
      return null;
    }

    // Meta (F5, F6)
    const birim  = txt(cell(aoa, ROW.BIRIM, COL.F));
    const aralik = txt(cell(aoa, ROW.ARALIK, COL.F));

    const headerRow = aoa[ROW.HEADER] || [];
    const okHeader =
      norm(headerRow[COL.C]).startsWith("iddianame no") &&
      (norm(headerRow[COL.F]).includes("degerlendirme") || norm(headerRow[COL.F]).includes("değerlendirme")) &&
      (norm(headerRow[COL.G]).includes("gonderildigi") || norm(headerRow[COL.G]).includes("gönderildiği"));
    if (!okHeader){ console.warn("Başlık satırı beklenenden farklı; veri C12’den okunacak."); }

    // Veriyi C12’den itibaren oku
    const rows = [];
    for (let r = ROW.DATA_START; r < aoa.length; r++){
      const row = aoa[r] || [];
      if (isDataHeaderRow(row)) continue;

      const C = txt(row[COL.C]);
      const F = txt(row[COL.F]);
      const G = txt(row[COL.G]);
      const I = txt(row[COL.I]);
      const J = txt(row[COL.J]);
      const K = txt(row[COL.K]);
      const L = txt(row[COL.L]);

      // sadece doğru formatlı satırlar
      if (!isValidId(C) || !isValidId(F) || !isValidDate(G)) continue;

      const cN = norm(C);
      const dN = norm(row[COL.D] ?? "");

      if (cN.includes("sistemden alinmistir") || dN.includes("sistemden alinmistir")) continue;
      if (cN === "kurul mufettisi" || cN === "hakim" || cN === "hâkim") continue;
      if (cN.startsWith("gelen dosya listesi") || cN.startsWith("birim adi") || cN.startsWith("denetim araligi")) continue;
      if (!C && !F && !G) continue;

      const nonDataTail = !looksLikeIddianameNo(C) && !F && !G && !I && !J && !K && !L;
      if (nonDataTail) continue;

      rows.push({ iddianameNo:C, degerNo:F, gonderimTarihi:G, degerTar:I, degerDurum:J, sureGun:K, hakim:L });
    }

    if (!rows.length){
      toast('warning','Veri Yok','C12’den itibaren veri satırı bulunamadı.');
      return null;
    }

    return { rows, sheetName, birimAdi: birim, denetimAraligi: aralik };
  }

  // ---------- render + pager
  function clearPreview(){
    const wrap = $("#combinedTableWrap");
    const card = $("#combinedSummaryCard");
    const stats = $("#combinedStats");
    if (wrap) wrap.innerHTML = `<div class="placeholder">Henüz veri yok.</div>`;
    if (stats) stats.innerHTML = "";
    if (card) card.style.display = "none";
  }

  function renderCombinedPreview(){
    const wrap = $("#combinedTableWrap");
    const card = $("#combinedSummaryCard");
    const stats = $("#combinedStats");
    if (!wrap || !card) return;

    const total = state.rows.length;
    if (!total){ clearPreview(); return; }

    if (stats){
      stats.innerHTML = `
        <span class="badge">${total} kayıt</span>
        <span class="muted">(${esc(state.sheetName)})</span>
        ${state.birimAdi ? `<span class="badge">${esc(state.birimAdi)}</span>` : ""}
        ${state.denetimAraligi ? `<span class="badge">${esc(state.denetimAraligi)}</span>` : ""}
      `;
    }

    const pageCount = Math.max(1, Math.ceil(total / PAGE_SIZE));
    if (state.currentPage > pageCount) state.currentPage = pageCount;
    const start = (state.currentPage - 1) * PAGE_SIZE;
    const end   = Math.min(total, start + PAGE_SIZE);
    const pageRows = state.rows.slice(start, end);

    let html = `<div class="table-wrap-inner">`;
    html += `<table class="table compact" id="previewXls">`;
    html += `<thead><tr>
      <th>İddianame No</th>
      <th>İddianame Değerlendirme No</th>
      <th>İddianamenin Gönderildiği Tarih</th>
      <th>İddianame Değerlendirme Tarihi</th>
      <th>Değerlendirme (Kabul-İade)</th>
      <th>Süre (Gün)</th>
      <th>Hakim</th>
    </tr></thead><tbody>`;
    for (const r of pageRows){
      html += `<tr>
        <td>${esc(r.iddianameNo)}</td>
        <td>${esc(r.degerNo)}</td>
        <td>${esc(r.gonderimTarihi)}</td>
        <td>${esc(r.degerTar)}</td>
        <td>${esc(r.degerDurum)}</td>
        <td class="num">${esc(r.sureGun)}</td>
        <td>${esc(r.hakim)}</td>
      </tr>`;
    }
    html += `</tbody></table>`;
    html += renderPagerHtml(state.currentPage, pageCount);
    html += `</div>`;

    wrap.innerHTML = html;
    bindPager(pageCount);
    card.style.display = "block";
  }

  function renderPagerHtml(cp, pc){
    return `
      <div class="card-footer pager d-flex justify-content-between align-items-center" id="combinedPager">
        <button class="btn btn-sm" id="pgFirst" ${cp<=1?'disabled':''} title="İlk Sayfa">‹‹</button>
        <button class="btn btn-sm" id="pgPrev"  ${cp<=1?'disabled':''} title="Önceki">‹</button>
        <div class="muted">Sayfa ${cp} / ${pc} · ${PAGE_SIZE}/sayfa</div>
        <button class="btn btn-sm" id="pgNext"  ${cp>=pc?'disabled':''} title="Sonraki">›</button>
        <button class="btn btn-sm" id="pgLast"  ${cp>=pc?'disabled':''} title="Son Sayfa">››</button>
      </div>
    `;
  }
  function bindPager(pageCount){
    const toPage = (p) => { state.currentPage = Math.min(pageCount, Math.max(1, p)); renderCombinedPreview(); };
    $("#pgFirst")?.addEventListener("click", () => toPage(1));
    $("#pgPrev") ?.addEventListener("click", () => toPage(state.currentPage - 1));
    $("#pgNext") ?.addEventListener("click", () => toPage(state.currentPage + 1));
    $("#pgLast") ?.addEventListener("click", () => toPage(pageCount));
  }

  async function exportToDocx(){
    const payload = {
	  birimAdi: state.birimAdi || "",
	  denetimAraligi: state.denetimAraligi || "",
	  rows: Array.isArray(state.rows) ? state.rows : [],
	  replaceVars: {
		YER: (state.birimAdi || "").toUpperCase(),
		TARIH: new Date().toLocaleDateString("tr-TR")
	  }
	};
    if (!payload.rows.length) {
      window.toast?.({type:'warning', title:'Veri yok', body:'Tabloda satır bulunamadı.'});
      return;
    }

    console.log('Gönderilen veriler:', payload);  // Debug için

    // Absolute URL kullan
    const apiUrl = '/api/iddianame_writer.php';

    try{
      const res = await fetch(apiUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(payload)
      });
      
      console.log('Sunucu yanıtı:', {
        status: res.status,
        contentType: res.headers.get('Content-Type'),
        disposition: res.headers.get('Content-Disposition')
      });

      const ct = (res.headers.get('Content-Type') || '').toLowerCase();
      const cd = res.headers.get('Content-Disposition') || '';
      let blob = null;

      if (res.ok && (ct.includes('application/vnd.openxmlformats-officedocument.wordprocessingml.document') || /filename\s*=\s*"?[^";]*\.docx"?/i.test(cd))){
        blob = await res.blob();
      } else if (res.ok) {
        // İçerik türü yanlış gelse bile ZIP imzasını kontrol ederek kabul et
        const tmpBlob = await res.blob();
        const ab = await tmpBlob.arrayBuffer();
        const u8 = new Uint8Array(ab);
        const looksZip = u8.length >= 4 && u8[0] === 0x50 && u8[1] === 0x4B; // 'PK' imzası
        if (looksZip) blob = new Blob([ab], { type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' });
      }

      if (!blob) {
        let msg = `(${res.status}) Word dosyası oluşturulamadı.`;
        try {
          const text = await res.clone().text();
          try { const j = JSON.parse(text); if (j && j.reason) msg = j.reason; }
          catch { if (text) msg = text.slice(0, 500); }
        } catch {}
        window.toast?.({type:'danger', title:'Hata', body: msg});
        return;
      }

      const url = URL.createObjectURL(blob);
      const a = document.createElement('a'); a.href = url; a.download = 'iddianame.docx';
      document.body.appendChild(a); a.click(); document.body.removeChild(a);
      URL.revokeObjectURL(url);
      window.toast?.({type:'success', title:'İndiriliyor', body:'Denetime uygun Word belgesi indiriliyor.'});
    }catch(e){
      window.toast?.({type:'danger', title:'Ağ Hatası', body: String(e)});
    }
  }

  // İndirme kartı (buton görünümü Dosya Seç ile aynı)
  function renderExportCard(){
    const host = document.getElementById('udfUploadCard');
    if (!host) return;

    const old = document.getElementById('exportInfoCard');
    if (old) old.remove();

    const birim  = state.birimAdi || 'Belirtilmeyen birim';
    const aralik = state.denetimAraligi || 'belirtilen';
    const count  = state.rows.length;

    // Sayfadaki "Dosya Seç" alanıyla birebir görünüm için aynı kart/başlık yapısını kullan
    const html = `
    <section class="card card-upload" id="exportInfoCard" style="margin-top:12px">
      <div class="card-head">
        <span class="material-symbols-rounded">description</span>
        <strong>Word Çıktısı</strong>
      </div>
      <div class="card-body" style="display:block">
        <div class="muted" style="margin-bottom:8px">
          ${esc(birim)} – ${esc(aralik)} aralığında <b>${count}</b> satır hazır.
        </div>
        <div id="exportPickRow" style="margin-top:10px;text-align:right">
          <label class="btn" id="exportDocxBtn">
            <span class="material-symbols-rounded">description</span> Word'e Aktar
          </label>
        </div>
      </div>
    </section>`;

    host.insertAdjacentHTML('afterend', html);
    document.getElementById('exportDocxBtn')?.addEventListener('click', exportToDocx);
  }

  // ---------- süreç
  async function processExcel(file){
    toast('info','Okunuyor','Excel dosyası okunuyor…');
    const res = await readSheetExact(file);
    if (!res){ clearPreview(); return; }

    state.rows = res.rows;
    state.sheetName = res.sheetName;
    state.birimAdi = res.birimAdi || "";
    state.denetimAraligi = res.denetimAraligi || "";
    state.currentPage = 1;

    renderCombinedPreview();

    const birim = state.birimAdi || "Belirtilmeyen birim";
    const aralik = state.denetimAraligi || "belirtilmeyen tarih aralığı";
    toast('success','Tablo Yüklendi', `${birim}'nin ${aralik} denetim tarihleri arasında yüklenen İddianame Değerlendirme tablosunda ${state.rows.length} adet kayda rastlanılmış olup yanda gösterilmektedir.`);
    renderExportCard();
  }

  // ---------- yükleme UI (tek dosya)
  const elDrop   = $("#udfDrop");
  const elInput  = $("#udfInput");
  const elChosen = $("#xlsChosen");
  const setChosenText = (t) => { if (elChosen) elChosen.textContent = t || ""; };

  function isExcelFile(f){
    if (!f) return false;
    const nameOk = /\.xlsx?$/i.test(f.name);
    const typeOk = /sheet|excel|spreadsheet/i.test(f.type || "") || nameOk;
    return nameOk || typeOk;
  }
  function pickFirstExcelFile(fileList){
    if (!fileList || fileList.length===0) return null;
    if (fileList.length>1) toast('warning','Tek Dosya','Yalnızca 1 adet XLS/XLSX seçebilirsiniz. İlk dosya işlendi.');
    const f = fileList[0];
    if (!isExcelFile(f)){ toast('warning','Dosya Türü','Lütfen XLS/XLSX dosyası seçiniz.'); return null; }
    return f;
  }
  function handleFiles(fl){
    const f = pickFirstExcelFile(fl);
    if (!f){ setChosenText(""); return; }
    setChosenText(`Seçilen: ${f.name}`);
    processExcel(f).catch(err => { console.error(err); toast('danger','Okuma Hatası','Excel okunurken sorun oluştu.'); });
	if (window.jQuery && typeof window.jQuery.getJSON === "function") {
		  window.jQuery.getJSON("https://sayac.657.com.tr/arttirkarar", function(response) {
			try {
			  const adetRaw = (response && typeof response.adet !== "undefined") ? Number(response.adet) : 0;
			  if (adetRaw > 0) {
				const msg = `28/10/2025 tarihinden bugüne kadar ${fmtInt(adetRaw)} adet işlem yaptık.`;
				window.toast?.({ type: "info", title: "Başarılı", body: msg, delay : 9000 });
			  }
			} catch (e) {
			  console.warn("Sayaç verisi okunamadı:", e);
			}
		  }).fail(function() {
			console.warn("Sayaç servisine ulaşılamadı.");
		  });
		}
  }

  if (elDrop){
    elDrop.addEventListener("click", () => elInput?.click());
    ["dragenter","dragover"].forEach(ev => elDrop.addEventListener(ev, e => { e.preventDefault(); e.stopPropagation(); elDrop.classList.add("dragover"); }));
    ["dragleave","drop"].forEach(ev => elDrop.addEventListener(ev, e => { e.preventDefault(); e.stopPropagation(); elDrop.classList.remove("dragover"); }));
    elDrop.addEventListener("drop", e => {
      const files = e.dataTransfer?.files;
      if (!files || files.length===0){ toast('warning','Dosya','Bırakılan dosya algılanamadı.'); return; }
      handleFiles(files);
    });
  }
  if (elInput){
    elInput.addEventListener("change", () => {
      const files = elInput.files;
      if (!files || !files.length){ toast('warning','Dosya','Herhangi bir dosya seçilmedi.'); return; }
      handleFiles(files);
    });
  }
})();
