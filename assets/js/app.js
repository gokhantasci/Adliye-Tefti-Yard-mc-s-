// formatRetryMessage is now in utils.js - use window.formatRetryMessage

// Theme management moved to theme-manager.js to avoid conflicts
// This IIFE only handles sidebar toggle and collapsible menus

(function(){
  const sidebar = document.getElementById('sidebar');
  const sidebarToggle = document.getElementById('sidebarToggle');
  const navbarBrandText = document.getElementById('navbarBrandText');
  let bsOffcanvas = null;
  
  function toggleSidebar(){
    if (!sidebar) return;
    
    // Bootstrap Offcanvas kullan (mobil için)
    if (window.innerWidth <= 1400) {
      if (!bsOffcanvas) {
        bsOffcanvas = new bootstrap.Offcanvas(sidebar);
      }
      bsOffcanvas.toggle();
    } else {
      // Desktop'ta class toggle
      const isOpen = sidebar.classList.toggle('open');
      if (navbarBrandText) {
        navbarBrandText.textContent = isOpen ? 'Menü' : 'Teftiş - 657.com.tr';
      }
    }
  }
  
  // Küçük ekranlarda sidebar'ı otomatik kapat
  function handleResponsiveSidebar() {
    if (!sidebar) return;
    // 1400px altında sidebar otomatik kapansın (main taşmasını önler)
    if (window.innerWidth <= 1400) {
      sidebar.classList.remove('open');
      if (navbarBrandText) {
        navbarBrandText.textContent = 'Teftiş - 657.com.tr';
      }
    } else {
      // Geniş ekranlarda sidebar her zaman açık (statik)
      sidebar.classList.remove('open');
      if (navbarBrandText) {
        navbarBrandText.textContent = 'Teftiş - 657.com.tr';
      }
    }
  }
  
  // Sayfa yüklendiğinde ve resize olduğunda kontrol et
  handleResponsiveSidebar();
  window.addEventListener('resize', handleResponsiveSidebar);
  
  // Mobilde sidebar dışına tıklayınca kapat
  document.addEventListener('click', (e) => {
    if (!sidebar) return;
    if (window.innerWidth > 1400) return; // Geniş ekranlarda aktif değil
    if (!sidebar.classList.contains('open')) return; // Sidebar kapalıysa zaten
    
    // Sidebar veya toggle butonuna tıklanmadıysa kapat
    const clickedSidebar = sidebar.contains(e.target);
    const clickedToggle = sidebarToggle && sidebarToggle.contains(e.target);
    
    if (!clickedSidebar && !clickedToggle) {
      sidebar.classList.remove('open');
      if (navbarBrandText) {
        navbarBrandText.textContent = 'Teftiş - 657.com.tr';
      }
    }
  });
  
  if (sidebarToggle) sidebarToggle.addEventListener('click', toggleSidebar);
  
  // Collapsible menu groups
  document.querySelectorAll('.menu-group-title.collapsible').forEach(title => {
    const items = title.nextElementSibling;
    if (items && items.classList.contains('menu-group-items')) {
      // Aktif menu item bu grup içinde mi kontrol et
      const hasActiveItem = items.querySelector('.nav-link.active');
      
      // Title text içeriğini al (sadece span içindeki text)
      const titleText = title.querySelector('span:first-child')?.textContent.trim() || '';
      const isDenetimCetvelleri = titleText === 'Denetim Cetvelleri';
      
      // Eğer bu grupta aktif sayfa varsa, grubu aç
      if (hasActiveItem) {
        // Aktif sayfa varsa açık bırak ve collapsed'ı kaldır
        title.classList.remove('collapsed');
        items.style.display = '';
      } else if (isDenetimCetvelleri) {
        // Denetim Cetvelleri aktif sayfa yoksa kapalı başlasın
        title.classList.add('collapsed');
        items.style.display = 'none';
      } else {
        // Diğer gruplar açık başlasın
        title.classList.remove('collapsed');
        items.style.display = '';
      }
      
      // Click event - toggle
      title.addEventListener('click', () => {
        const isCollapsed = title.classList.contains('collapsed');
        
        if (isCollapsed) {
          // Şu anda kapalı, aç
          title.classList.remove('collapsed');
          items.style.display = '';
        } else {
          // Şu anda açık, kapat
          title.classList.add('collapsed');
          items.style.display = 'none';
        }
      });
    }
  });
  
  const notesEl = document.getElementById('notes');
  const notesKey = 'minimal-notes';
  const readNotes = () => JSON.parse(localStorage.getItem(notesKey) || '[]');
  const writeNotes = (arr) => localStorage.setItem(notesKey, JSON.stringify(arr));
  // escapeHtml is now in utils.js - use window.escapeHtml
  function renderNotes(){
    if (!notesEl) return;
    const data = readNotes();
    notesEl.innerHTML = '';
    if (data.length === 0) {
      notesEl.innerHTML = '<p class="muted">Hic not yok. "Yeni Not" ile baslayin.</p>';
      return;
    }
    data.forEach(function(text, idx){
      const item = document.createElement('div');
      item.className = 'note';
      item.innerHTML =
        '<div class="text">' + window.escapeHtml(text) + '</div>' +
        '<div class="actions">' +
        '<button class="btn ghost" data-edit="' + idx + '">Duzenle</button>' +
        '<button class="btn" data-del="' + idx + '">Sil</button>' +
        '</div>';
      notesEl.appendChild(item);
    });
    notesEl.addEventListener('click', onNoteClick);
  }
  function onNoteClick(e){
    const t = e.target;
    if (t.matches('[data-del]')){
      const idx = +t.getAttribute('data-del');
      const arr = readNotes(); arr.splice(idx,1); writeNotes(arr); renderNotes();
    } else if (t.matches('[data-edit]')){
      const idx = +t.getAttribute('data-edit');
      const arr = readNotes(); const val = prompt('Notu duzenle:', arr[idx] || '');
      if (val !== null){ arr[idx] = val.trim(); writeNotes(arr); renderNotes(); }
    }
  }
  window.addNote = function(val){
    if (!val) {
      val = prompt('Yeni not:');
      if (!val) return;
    }
    const arr = readNotes(); arr.unshift(String(val).trim()); writeNotes(arr); renderNotes();
  };
  renderNotes();
})();
(function(){
  const API = '/api/notes.php';
  const notesKey = 'minimal-notes';
  async function syncDown(){
    try {
      const r = await fetch(API, {headers:{'Accept':'application/json'}});
      if (!r.ok) return;
      const j = await r.json();
      const remote = (j && j.data && j.data.items) ? j.data.items : [];
      const local = JSON.parse(localStorage.getItem(notesKey) || '[]');
      if (local.length === 0 && remote.length > 0) {
        localStorage.setItem(notesKey, JSON.stringify(remote.map(function(x){ return x.text; })));
        if (typeof window.renderNotes === 'function') window.renderNotes();
        else {
          const ev = document.createEvent('Event');
          ev.initEvent('notes-sync', true, true);
          document.dispatchEvent(ev);
        }
      }
    } catch (e){ /* Error handled silently */ }
  }
  async function pushAdd(text){
    try {
      await fetch(API, {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({action:'add', text:text})
      });
    } catch (e){  }
  }
  const origAdd = window.addNote;
  if (typeof origAdd === 'function'){
    window.addNote = function(val){
      if (!val) {
        val = prompt('Yeni not:');
        if (!val) return;
      }
      origAdd(val);
      pushAdd(val);
    };
  }
})();
(function(){
  const btn = document.getElementById('collapseBtn');
  const det = document.querySelector('details.settings');
  if (btn && det) {
    btn.addEventListener('click', function(){
      det.open = false;
    });
  }
})();
(function(){
  const key = 'dashboard-settings';
  const ids = ['col_i','col_j','col_k','col_o','col_p','col_t','col_m','col_q','col_z'];
  const $ = (id) => document.getElementById(id);
  function load(){
    try {
      const raw = localStorage.getItem(key);
      if (!raw) return;
      const data = JSON.parse(raw);
      ids.forEach(k => { if ($(k) && Object.prototype.hasOwnProperty.call(data, k)) $(k).value = data[k]; });
    } catch (e){ /* Error handled silently */ }
  }
  function save(){
    const data = {};
    ids.forEach(k => { if ($(k)) data[k] = String(($(k).value || '')).trim(); });
    try {
      localStorage.setItem(key, JSON.stringify(data));
      return true;
    } catch (e){ return false; }
  }
  const btn = document.getElementById('saveBtn');
  if (btn) {
    btn.addEventListener('click', function(){
      const ok = save();
      const old = btn.textContent;
      btn.textContent = ok ? 'Kaydedildi ✔' : 'Kaydedilemedi ✖';
      setTimeout(() => { btn.textContent = old; }, 1400);
    });
  }
  load();
})();
(function(){
  const det = document.querySelector('details.settings');
  if (!det) return;
  function apply(){
    const wide = window.matchMedia('(min-width: 1024px)').matches;
    det.open = !!wide;
  }
  apply();
  window.addEventListener('resize', function(){
    apply();
  });
})();
(function(){
  function el(tag, attrs, html){
    const e = document.createElement(tag);
    if (attrs) for (const k in attrs){ if (attrs.hasOwnProperty(k)) e.setAttribute(k, attrs[k]); }
    if (html != null) e.innerHTML = html;
    return e;
  }
  window.showAlert = function(target, opts){
    opts = opts || {};
    const type = opts.type || 'primary';
    const title = opts.title || '';
    const msg = opts.message || '';
    const icon = opts.icon || 'info';
    const dismiss = opts.dismissible !== false;
    const wrap = el('div', {class: 'alert alert-' + type + ' d-flex align-items-start position-relative'});
    const icn = el('span', {class: 'material-symbols-rounded alert-icon me-3'}, icon);
    const body = el('div', {class: 'alert-body flex-grow-1'});
    if (title) body.appendChild(el('div', {class:'alert-title fw-bold mb-1'}, title));
    body.appendChild(el('div', null, msg));
    wrap.appendChild(icn);
    wrap.appendChild(body);
    if (dismiss) {
      const btn = el('button', {class:'btn-close position-absolute top-0 end-0 m-2', type:'button', 'aria-label':'Kapat'});
      btn.addEventListener('click', function(){ wrap.remove(); });
      wrap.appendChild(btn);
    }
    let host = (typeof target === 'string') ? document.querySelector(target) : target;
    if (!host) host = document.body;
    host.appendChild(wrap);
    return wrap;
  };
  /**
   * Toast System (AdminLTE-like)
   * Kullanım: window.toast({ type: 'success', title: 'Başarılı', body: 'İşlem tamamlandı', delay: 5000 })
   * type: 'success', 'error', 'warning', 'info', 'primary'
   */
  window.toast = function(opts){
    opts = opts || {};
    const type = opts.type || 'primary';
    const title = opts.title || 'Bilgi';
    const body = opts.body || '';
    const autohide = opts.autohide !== false;
    const delay = (typeof opts.delay === 'number') ? opts.delay : 5000;

    // Toast container
    let container = document.querySelector('.toast-container');
    if (!container) {
      container = document.createElement('div');
      container.className = 'toast-container';
      document.body.appendChild(container);
    }

    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'toast toast-' + type;
    
    const head = document.createElement('div');
    head.className = 'toast-head';
    
    const titleEl = document.createElement('div');
    titleEl.className = 'toast-title';
    titleEl.textContent = title;
    
    const closeBtn = document.createElement('button');
    closeBtn.className = 'btn-close';
    closeBtn.type = 'button';
    closeBtn.setAttribute('aria-label', 'Kapat');
    closeBtn.innerHTML = '&times;';
    closeBtn.addEventListener('click', function() {
      toast.remove();
    });
    
    head.appendChild(titleEl);
    head.appendChild(closeBtn);
    
    const bodyEl = document.createElement('div');
    bodyEl.className = 'toast-body';
    bodyEl.innerHTML = body;
    
    toast.appendChild(head);
    toast.appendChild(bodyEl);
    container.appendChild(toast);

    // Auto-hide
    if (autohide) {
      setTimeout(function() {
        if (toast && toast.parentNode) {
          toast.remove();
        }
      }, delay);
    }

    return toast;
  };
})();
function dismissTestAlert() {
  const alert = document.getElementById('testDataAlert');
  if (alert) alert.style.display = 'none';
}
document.getElementById('excelInput')?.addEventListener('change', () => {
  dismissTestAlert();
});
function debounce(fn, delay){ let t; return function(){ clearTimeout(t); t = setTimeout(() => fn.apply(this, arguments), delay); }; }
(function(){
  function debounce(fn, delay){ let t; return function(){ clearTimeout(t); t = setTimeout(function(){ fn.apply(this, arguments); }, delay); }; }
  function equalizeSettingsToUpload(){
    try {
      const upload = document.querySelector('.grid-left .card.card-upload');
      const settings = document.querySelector('.sidecard details.settings');
      if (!upload || !settings) return;
      const root = document.documentElement;
      const h = upload.getBoundingClientRect().height || 0;
      if (h > 0){
        root.style.setProperty('--settings-target-height', h + 'px');
      } else {
        root.style.removeProperty('--settings-target-height');
      }
    } catch (e){ /* Error handled silently */ }
  }
  const run = debounce(equalizeSettingsToUpload, 100);
  window.addEventListener('load', run);
  window.addEventListener('resize', run);
  document.addEventListener('DOMContentLoaded', run);
  const uploadNode = document.querySelector('.grid-left .card.card-upload');
  if (uploadNode && 'MutationObserver' in window){
    const mo = new MutationObserver(run);
    mo.observe(uploadNode, {childList:true, subtree:true, attributes:true, characterData:true});
  }
})();
if (typeof dismissTestAlert !== 'function') {
  function dismissTestAlert(){
    try {
      const el = document.getElementById('testDataAlert');
      if (el){ el.remove(); }
    } catch (e){ /* Error handled silently */ }
  }
}
(function(){
  window.dismissTestAlert = function dismissTestAlert(){
    try {
      const el = document.getElementById('testDataAlert');
      if (el){ el.remove(); }
    } catch (e){ /* Error handled silently */ }
  };
})();
(function() {
  const NEWS_URL = '/data/teftis.json';
  const LS_KEY = 'teftisNews_v1';
  const LS_FETCHED_AT = 'teftisNewsFetchedAt_v1';
  const REFRESH_MS = 60 * 60 * 1000;
  const PAGE_SIZE = 2;
  const listEl = document.getElementById('newsList');
  const metaEl = document.getElementById('newsMeta');
  if (!listEl || !metaEl) return;
  let pagerEl = document.getElementById('newsPager');
  if (!pagerEl) {
    pagerEl = document.createElement('nav');
    pagerEl.id = 'newsPager';
    pagerEl.className = 'pager';
    pagerEl.setAttribute('role', 'navigation');
    pagerEl.setAttribute('aria-label', 'Haber sayfalama');
  }

  // -- pager konumu: newsCard'ın card-footer'ında --
  (function placePager() {
    const newsCard = document.getElementById('newsCard');
    if (!newsCard) return;

    let cardFoot = newsCard.querySelector('.card-footer') || newsCard.querySelector('.card-foot');
    if (!cardFoot) {
      cardFoot = document.createElement('div');
      cardFoot.className = 'card-footer';
      newsCard.appendChild(cardFoot);
    }

    if (pagerEl.parentNode !== cardFoot) {
      cardFoot.appendChild(pagerEl);
    }
  })();
  let itemsCache = [];
  let currentPage = 1;
  function parseItems(raw) {
    if (!raw || !raw.length) return [];
    const out = [];
    for (let i = 0; i < raw.length; i++) {
      const x = raw[i] || {};
      const t = x.tarih || x.Tarih || x.date || '';
      const c = x.icerik || x.İcerik || x.İÇERİK || x.content || '';
      if (t && c) out.push({ tarih: t, icerik: c });
    }
    function toTime(s) { const d = new Date(s); return isNaN(d) ? 0 : d.getTime(); }
    out.sort(function(a, b) { return toTime(b.tarih) - toTime(a.tarih); });
    return out;
  }
  function save(items) {
    try {
      localStorage.setItem(LS_KEY, JSON.stringify(items));
      localStorage.setItem(LS_FETCHED_AT, String(Date.now()));
    } catch (e) {}
  }
  function load() {
    try {
      const s = localStorage.getItem(LS_KEY);
      return s ? JSON.parse(s) : [];
    } catch (e) { return []; }
  }
  function lastFetchedMs() { return Number(localStorage.getItem(LS_FETCHED_AT) || 0); }
  function shouldRefresh() { return (Date.now() - lastFetchedMs()) >= REFRESH_MS; }
  function setMetaLoading() { try { metaEl.textContent = 'Yükleniyor…'; } catch (e) {} }
  function setErrorInCard() {
    const body = document.querySelector('#newsCard .news-drop__content') || document.querySelector('#newsCard .card-body');
    if (body) body.innerHTML = '<p class="news-error">Haberler alınamadı.</p>';
  }
  function fetchFromRemote() {
    setMetaLoading();
    return fetch(NEWS_URL, { cache: 'no-store' })
      .then(function(res) {
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.json();
      })
      .then(function(data) {
        const items = parseItems(Array.isArray(data) ? data : []);
        save(items);
        return items;
      })
      .catch(function(e) {
        // Error: '[NEWS]', e
        setErrorInCard();
        return [];
      });
  }
  function renderMeta(total) {
    const fetchedAt = lastFetchedMs();
    let fetchedText = '—';
    if (fetchedAt) {
      try {
        fetchedText = new Intl.DateTimeFormat('tr-TR', { dateStyle: 'short', timeStyle: 'short' })
          .format(new Date(fetchedAt));
      } catch (e) {
        fetchedText = new Date(fetchedAt).toLocaleString('tr-TR');
      }
    }
    metaEl.textContent = total + ' haber · Son güncelleme: ' + fetchedText;
  }
  function renderListPage(items, page) {
    if (!items || !items.length) {
      listEl.innerHTML = "<p class='news-empty'>Haber bulunamadı.</p>";
      if (pagerEl) { pagerEl.innerHTML = ''; pagerEl.style.display = 'none'; }
      return;
    }
    const total = items.length;
    const totalPages = Math.max(1, Math.ceil(total / PAGE_SIZE));
    if (page < 1) page = 1;
    if (page > totalPages) page = totalPages;
    currentPage = page;
    const start = (page - 1) * PAGE_SIZE;
    const end = start + PAGE_SIZE;
    const slice = items.slice(start, end);
    
    // Haberleri render et
    listEl.innerHTML = '';
    slice.forEach(function(item) {
      const newsItem = document.createElement('div');
      newsItem.className = 'news-item';
      
      const dateEl = document.createElement('div');
      dateEl.className = 'news-date muted';
      dateEl.textContent = item.tarih;
      
      const contentEl = document.createElement('div');
      contentEl.className = 'news-content';
      // \n karakterlerini <br> ile değiştir
      contentEl.innerHTML = (item.icerik || '').replace(/\n/g, '<br>');
      
      newsItem.appendChild(dateEl);
      newsItem.appendChild(contentEl);
      listEl.appendChild(newsItem);
    });
    
    pagerEl.innerHTML = '';
    pagerEl.style.display = (totalPages <= 1 ? 'none' : '');
    if (totalPages > 1) {
      // unified pager layout: left(prev), center(info), right(next)
      const left = document.createElement('div');
      const center = document.createElement('div');
      const right = document.createElement('div');
      const prev = document.createElement('button');
      prev.type = 'button'; prev.className = 'btn ghost'; prev.textContent = 'Önceki'; prev.disabled = (page === 1);
      prev.addEventListener('click', function(){ renderListPage(itemsCache, currentPage - 1); });
      left.appendChild(prev);
      const info = document.createElement('div'); info.className = 'muted'; info.textContent = 'Sayfa ' + page + ' / ' + totalPages; center.appendChild(info);
      const next = document.createElement('button');
      next.type = 'button'; next.className = 'btn ghost'; next.textContent = 'Sonraki'; next.disabled = (page === totalPages);
      next.addEventListener('click', function(){ renderListPage(itemsCache, currentPage + 1); });
      right.appendChild(next);
      // Track interactions for keyboard pager shortcuts
      [prev, next].forEach(function(btn){
        ['focus','mouseenter','click'].forEach(function(ev){ btn.addEventListener(ev, function(){ window.__lastPager = pagerEl; }); });
      });
      pagerEl.appendChild(left); pagerEl.appendChild(center); pagerEl.appendChild(right);
    }
  }
  function render(items) {
    itemsCache = items || [];
    renderMeta(itemsCache.length);
    renderListPage(itemsCache, 1);
    // === Footer Slider News Injection ===
    (function injectFooterNews(){
      if (window.__footerNewsApplied) return; // tek seferlik
      const track = document.querySelector('.fs-track');
      if (!track) return;
      try {
        const storedKey = 'footerNews_v1';
        const existingRaw = localStorage.getItem(storedKey);
        let existing = [];
        if (existingRaw){ try { existing = JSON.parse(existingRaw) || []; } catch (e){} }
        const seen = new Set(existing.map(x => (x.date + '__' + x.text)));
        const addList = [];
        itemsCache.forEach(function(it){
          const dateStr = it.tarih; // varsayılan format korunur
          const parts = String(it.icerik || '').split(/\n+/).map(p => p.trim()).filter(Boolean);
          parts.forEach(function(part){
            const key = dateStr + '__' + part;
            if (seen.has(key)) return;
            seen.add(key);
            addList.push({ date: dateStr, text: part });
          });
        });
        if (!addList.length) { window.__footerNewsApplied = true; return; }
        // Birleştir, son eklenen ilk görünsün (slider zaten döner)
        const merged = existing.concat(addList).slice(-200); // max 200 kayıt tut
        localStorage.setItem(storedKey, JSON.stringify(merged));
        // DOM'a ekle
        addList.forEach(function(row){
          const div = document.createElement('div');
          div.className = 'fs-item';
          // Tarihi başa koy, içerik devamında; uzunluk taşarsa CSS kırpar
          div.innerHTML = '<span class="muted" style="font-variant-numeric:tabular-nums;">' + escapeHtml(row.date) + '</span> ' + escapeHtml(row.text);
          track.appendChild(div);
        });
        window.__footerNewsApplied = true;
      } catch (e){ console.error('[FooterNews]', e); }
    })();
  }
  // Toast queue & throttle (max 2 concurrent, 500ms interval)
  (function enhanceToastQueue(){
    if (window.__toastQueueEnhanced) return;
    const originalToast = window.toast; // expects opts object
    const queue = [];
    let active = 0;          // currently visible count
    let lastShown = 0;       // timestamp of last shown
    function showNext(){
      if (!queue.length) return;
      if (active >= 2) return; // max 2 concurrent
      const now = Date.now();
      const since = now - lastShown;
      if (since < 500){ // enforce 500ms spacing
        setTimeout(showNext, 500 - since + 5);
        return;
      }
      const opts = queue.shift();
      lastShown = Date.now();
      active++;
      // Toast logging is handled in utils.js wrapper, no need to log here
      const el = originalToast(opts);
      const delay = (typeof opts.delay === 'number') ? opts.delay : 5000;
      setTimeout(() => { active = Math.max(0, active - 1); showNext(); }, delay + 60);
    }
    function normalizeArgs(args){
      if (!args.length) return {};
      if (typeof args[0] === 'object' && !Array.isArray(args[0])) return {...args[0]};
      const type = args[0];
      const title = args[1];
      const body = args[2];
      const extra = (typeof args[3] === 'object' && args[3]) ? args[3] : {};
      return { ...extra, type, title, body };
    }
    function queuedToast(){
      const opts = normalizeArgs(arguments);
      // Log toast event only if global toast logger isn't active yet
      try {
        if (!window.__TOAST_LOG_WRAP_ACTIVE) {
          const toastType = String(opts.type || 'info').toLowerCase();
          const msg = (opts.title || '') + (opts.body ? ': ' + opts.body : '');
          if (window.logEvent) window.logEvent(toastType, msg);
        }
      } catch (_) { }
      queue.push(opts);
      showNext();
      return { queued:true };
    }
    queuedToast.__queueEnhanced = true;
    window.toast = queuedToast;
    window.__toastQueueEnhanced = true;
  })();
  // Basit HTML kaçış (footer news injection için)
  function escapeHtml(str){
    return String(str).replace(/[&<>"']/g, function(ch){
      return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'})[ch] || ch;
    });
  }
  function msUntilNextHour() {
    const now = new Date();
    return ((59 - now.getMinutes()) * 60 + (60 - now.getSeconds())) * 1000 - now.getMilliseconds();
  }
  function refreshIfStale() {
    if (!shouldRefresh()) return;
    fetchFromRemote().then(function(it) { render(it); });
  }
  function scheduleHourly() {
    const wait = msUntilNextHour();
    setTimeout(function() {
      refreshIfStale();
      setInterval(refreshIfStale, 60 * 60 * 1000);
    }, Math.max(1000, wait));
  }
  function ensureData() {
    const cached = load();
    if (cached.length) {
      render(cached);
      if (shouldRefresh()) { fetchFromRemote().then(function(it) { render(it); }); }
    } else {
      fetchFromRemote().then(function(it) { render(it); });
    }
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() { ensureData(); scheduleHourly(); });
  } else {
    ensureData(); scheduleHourly();
  }
  // Global ArrowLeft/ArrowRight shortcuts for pagers
  (function bindGlobalPagerShortcuts(){
    if (window.__globalPagerKeysBound) return; window.__globalPagerKeysBound = true;
    function isEditable(el){ return el && (el.isContentEditable || /^(input|textarea|select)$/i.test(el.tagName)); }
    function clickIf(btn){ if (btn && !btn.disabled) btn.click(); }
    document.addEventListener('keydown', function(e){
      if (e.altKey || e.ctrlKey || e.metaKey) return;
      if (isEditable(document.activeElement)) return;
      if (e.key !== 'ArrowLeft' && e.key !== 'ArrowRight') return;
      let pager = window.__lastPager;
      if (!pager) pager = document.querySelector('.card-footer .pager, .modal-foot .pager, .log-panel-foot .pager');
      if (!pager){
        // Try modal foot fallbacks
        const mf = document.querySelector('.modal-foot');
        if (mf){
          const prev = mf.querySelector('#jrListPrev, #ozet-page-prev');
          const next = mf.querySelector('#jrListNext, #ozet-page-next');
          if (e.key === 'ArrowLeft' && prev){ e.preventDefault(); clickIf(prev); }
          if (e.key === 'ArrowRight' && next){ e.preventDefault(); clickIf(next); }
        }
        return;
      }
      const first = pager.firstElementChild;
      const last  = pager.lastElementChild;
      if (e.key === 'ArrowLeft'){ const btn = first && first.querySelector('button'); if (btn){ e.preventDefault(); clickIf(btn); } }
      if (e.key === 'ArrowRight'){ const btn = last && last.querySelector('button');  if (btn){ e.preventDefault(); clickIf(btn); } }
    });
    // Delegated tracking for any pager button interaction (focus/click/mouseenter)
    document.addEventListener('click', function(e){ const b = e.target.closest('.pager button'); if (b){ window.__lastPager = b.closest('.pager'); } });
    document.addEventListener('focusin', function(e){ const b = e.target.closest('.pager button'); if (b){ window.__lastPager = b.closest('.pager'); } });
    document.addEventListener('mouseover', function(e){ const b = e.target.closest('.pager button'); if (b){ window.__lastPager = b.closest('.pager'); } });
  })();
})();
(function() {
  if (window.__mailDropInitDone) return;
  window.__mailDropInitDone = true;
  function formatRetryMessage(sec) {
    sec = Number(sec) || 0;
    if (sec <= 0) return 'Bir süre sonra tekrar deneyin.';
    if (sec < 60) return sec + ' sn sonra tekrar deneyin.';
    const mins = Math.ceil(sec / 60);
    return mins + ' dk sonra tekrar deneyin.';
  }
  function formatDateTimeTR(d) {
    d = d || new Date();
    const dd = String(d.getDate()).padStart(2, '0');
    const mm = String(d.getMonth() + 1).padStart(2, '0');
    const yyyy = d.getFullYear();
    const hh = String(d.getHours()).padStart(2, '0');
    const mi = String(d.getMinutes()).padStart(2, '0');
    return dd + '.' + mm + '.' + yyyy + ' günü saat ' + hh + ':' + mi;
  }
  const BOX_ID = 'mailDropBox';
  const INPUT_ID = 'mailDropInput';
  const BTN_ID = 'mailDropSendBtn';
  const PREVIEW_ID = 'mailPreview';
  const TOAST_ID = 'mailDropToast';
  const SUBJECT_SAFE = 'Teftiş bilgilendirme hk.';
  const REPLY_TO = 'gkhntasci@gmail.com';
  const LINE_DATE = formatDateTimeTR() + ' teftis.657.com.tr adresine mail adresiniz bırakılması nedeniyle bu maili almaktasınız.';
  const SAFE_PLAIN_TEXT = [
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
    'Sakarya'
  ].join('\n');
  const emailRe = /^[A-Z0-9._%+-]+@adalet\.gov\.tr$/i;
  const $ = function(id) { return document.getElementById(id); };
  function setToast(t, ok) {
    const el = $(TOAST_ID);
    if (!el) return;
    el.textContent = t || '';
    el.style.color = ok ? 'var(--md-sys-color-primary,#2e7d32)' : 'var(--md-sys-color-on-error,#b00020)';
  }
  function setPreview() {
    const pre = $(PREVIEW_ID);
    if (pre) pre.textContent = SAFE_PLAIN_TEXT;
  }
  function extractEmail(txt) {
    const m = (txt || '').match(/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i);
    return m ? m[0].trim() : '';
  }
  function setEmail(val) {
    const inp = $(INPUT_ID);
    const btn = $(BTN_ID);
    if (!inp || !btn) return;
    inp.value = (val || '').trim();
    if (!inp.value) { btn.disabled = true; setToast(''); return; }
    if (emailRe.test(inp.value)) { btn.disabled = false; setToast('Adres alındı: ' + inp.value, true); } else { btn.disabled = true; setToast('Sadece @adalet.gov.tr uzantılı e-posta adresleri kabul edilir.'); }
  }
  function init() {
    const box = $(BOX_ID), inp = $(INPUT_ID), btn = $(BTN_ID);
    if (!box || !inp || !btn) return;
    setPreview();
    box.addEventListener('dragover', function(e) { e.preventDefault(); box.classList.add('drag-over'); });
    box.addEventListener('dragleave', function() { box.classList.remove('drag-over'); });
    box.addEventListener('drop', function(e) {
      e.preventDefault(); box.classList.remove('drag-over');
      let text = '';
      if (e.dataTransfer) {
        if (e.dataTransfer.getData('text/plain')) text = e.dataTransfer.getData('text/plain');
        else if (e.dataTransfer.items && e.dataTransfer.items.length) {
          const item = e.dataTransfer.items[0];
          if (item.kind === 'string') {
            item.getAsString(function(s) {
              const mail = extractEmail(s); if (mail) setEmail(mail); else setToast('Geçerli bir e-posta bulunamadı.');
            }); return;
          }
        }
      }
      const mail = extractEmail(text);
      if (mail) setEmail(mail); else setToast('Geçerli bir e-posta bulunamadı.');
    });
    box.addEventListener('click', function(e) { if (e.target !== inp && e.target !== btn) inp.focus(); });
    function handlePaste(e) {
      e.preventDefault(); e.stopPropagation();
      const text = (e.clipboardData && e.clipboardData.getData('text')) || '';
      const mail = extractEmail(text);
      if (mail) setEmail(mail); else setToast('Geçerli bir e-posta bulunamadı.');
    }
    inp.addEventListener('paste', handlePaste);
    box.addEventListener('paste', handlePaste);
    inp.addEventListener('input', function() {
      const v = inp.value.trim();
      if (!v) { btn.disabled = true; setToast(''); return; }
      if (emailRe.test(v)) { btn.disabled = false; setToast(''); } else { btn.disabled = true; setToast('Sadece @adalet.gov.tr uzantılı e-posta adresleri kabul edilir.'); }
    });
    btn.addEventListener('click', function() {
      const to = inp.value.trim();
      if (!emailRe.test(to)) { setToast('Sadece @adalet.gov.tr uzantılı e-posta adresleri kabul edilir.'); return; }
      btn.disabled = true;
      setToast('Gönderiliyor…');
      const bodyPayload = { to: to, subject: SUBJECT_SAFE, body: SAFE_PLAIN_TEXT };
      if (REPLY_TO) bodyPayload.reply_to = REPLY_TO;
      fetch('/api/send-mail.php?test=1&DEBUG=1', {
        method: 'POST',
        headers: (function(){
          const hpEl = document.getElementById('mailHp');
          const hpVal = hpEl ? hpEl.value : '';
          return { 'Content-Type': 'application/json', 'X-HP': hpVal };
        })(),
        body: JSON.stringify(bodyPayload)
      })
        .then(function(res) {
          return res.json().catch(function(){ return {}; }).then(function(data) {
            if ((res.ok && data && data.ok) || (data && data.msg)) {
              setToast('Mail gönderildi ✅', true);
            } else if (data && data.stage === 'GUARD') {
              const retry = data.retry_after || 0;
              setToast(formatRetryMessage(retry));
              btn.disabled = false;
            } else {
              setToast((data && (data.error || data.msg)) || 'Gönderim sırasında bir sorun oluştu.');
              btn.disabled = false;
            }
          });
        })
        .catch(function(err) {
          setToast('Ağ hatası: Gönderilemedi.');
          btn.disabled = false;
          // Error: err
        });
    });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();


// [fusion] searchSicil: Enter => filterBtn, then check column; toast if not found
(function(){
  if (window.__bindSearchEnterToast) return; window.__bindSearchEnterToast = true;
  const searchEl = document.getElementById('searchSicil');
  if (searchEl){
    searchEl.addEventListener('keydown', (ev) => {
      if (ev.key === 'Enter'){
        ev.preventDefault();
        const btn = document.getElementById('filterBtn');
        if (btn) btn.click();
      }
    });
  }
  // Hook filter button to show "sicil yok" toast if column not found
  const filterBtn = document.getElementById('filterBtn');
  if (filterBtn){
    filterBtn.addEventListener('click', () => {
      try {
        const qEl = document.getElementById('searchSicil');
        const q = qEl ? (qEl.value || '').trim() : '';
        if (!q) return;
        const wrap = document.querySelector('.table-wrap');
        let found = false;
        if (wrap){
          const ths = wrap.querySelectorAll('th');
          const needle = '(' + q + ')';
          for (let i = 0;i < ths.length;i++){
            if (ths[i].textContent.trim() === needle){ found = true; break; }
          }
        }
        if (!found && window.toast){
          window.toast({ type:'warning', title:'Kayıt bulunamadı', body:'Bu sicile ait bir kayıt bulunamamıştır.' });
        }
      } catch (_){}
    });
  }
})();


// [fusion] Modal utilities: close on outside/backdrop & ESC
(function(){
  if (window.__modalUtilitiesBound) return; window.__modalUtilitiesBound = true;
  function bindModalClose(modal){
    if (!modal || modal.__closeBound) return;
    modal.__closeBound = true;
    const close = () => { modal.classList.remove('is-open','active'); modal.setAttribute('aria-hidden','true'); };
    modal.__close = modal.__close || close;
    modal.addEventListener('click', (e) => {
      const card = modal.querySelector('.modal-card');
      const isBackdrop = e.target.classList && e.target.classList.contains('cm-backdrop');
      const outsideCard = card && !card.contains(e.target) && !isBackdrop;
      if (isBackdrop || outsideCard) close();
    });
    document.addEventListener('keydown', (ev) => { if (ev.key === 'Escape') close(); });
  }
  ['caseModal','noJudgeModal','detail-modal'].forEach(id => {
    const m = document.getElementById(id);
    if (m) bindModalClose(m);
    const obs = new MutationObserver(() => {
      const mm = document.getElementById(id);
      if (mm) bindModalClose(mm);
    });
    obs.observe(document.body, {childList:true, subtree:true});
  });
})();


// [fusion] Simple pager + auto paginate for modal tables
(function(){
  if (window.__pagerHelpersBound) return; window.__pagerHelpersBound = true;

  window.__applyPager = function(tableSelector, pageSize){
    try {
      pageSize = pageSize || 20;
      const table = document.querySelector(tableSelector);
      if (!table) return;
      const tbody = table.querySelector('tbody');
      if (!tbody) return;
      const rows = Array.from(tbody.querySelectorAll('tr'));
      if (rows.length <= pageSize) return; // no need

      // Preserve all rows
      const data = rows.map(tr => tr.outerHTML);
      tbody.innerHTML = '';

      let page = 1;
      const total = data.length;
      const pages = Math.ceil(total / pageSize);

      // Find appropriate footer: modal-foot or card-footer/card-foot
      const modal = table.closest('.cmodal, .modal-card');
      let footer = modal ? modal.querySelector('.modal-foot') : null;

      if (!footer) {
        const card = table.closest('.card');
        footer = card ? (card.querySelector('.card-footer') || card.querySelector('.card-foot')) : null;
      }

      const pagerId = (table.id || 'table').replace(/[^a-z0-9_-]/gi,'') + '_pager';
      let pager = footer ? footer.querySelector('#' + pagerId) : table.parentElement.querySelector('#' + pagerId);

      if (!pager){
        pager = document.createElement('div');
        pager.id = pagerId;
        pager.className = 'pager';
        pager.style.display = 'flex';
        pager.style.justifyContent = 'space-between';
        pager.style.alignItems = 'center';
        pager.style.gap = '8px';

        if (footer) {
          footer.appendChild(pager);
        } else {
          pager.style.marginTop = '10px';
          table.parentElement.appendChild(pager);
        }
      }

      function renderPage(){
        const start = (page - 1) * pageSize;
        const slice = data.slice(start, start + pageSize);
        tbody.innerHTML = slice.join('');
        pager.innerHTML = '';
        const info = document.createElement('div');
        info.className = 'muted';
        info.textContent = 'Sayfa ' + page + ' / ' + pages + ' — ' + total + ' kayıt';
        const left = document.createElement('div');
        const right = document.createElement('div');
        function mkBtn(label, disabled, on){
          const b = document.createElement('button');
          b.className = 'btn ghost';
          b.type = 'button';
          b.textContent = label;
          b.disabled = !!disabled;
          if (on) b.addEventListener('click', on);
          return b;
        }
        left.appendChild(mkBtn('« İlk', page === 1, () => { page = 1; renderPage(); }));
        left.appendChild(mkBtn('‹ Önceki', page === 1, () => { page--; renderPage(); }));
        right.appendChild(mkBtn('Sonraki ›', page === pages, () => { page++; renderPage(); }));
        right.appendChild(mkBtn('Son »', page === pages, () => { page = pages; renderPage(); }));
        pager.appendChild(left);
        pager.appendChild(info);
        pager.appendChild(right);
      }
      renderPage();
    } catch (e){ console.error('pager error', e); }
  };

  function watch(tableSelector){
    function bind(){
      const table = document.querySelector(tableSelector);
      if (!table) return;
      const tbody = table.querySelector('tbody');
      if (!tbody) return;
      const obs = new MutationObserver(() => {
        const count = tbody.querySelectorAll('tr').length;
        if (count > 20) window.__applyPager(tableSelector, 20);
      });
      obs.observe(tbody, {childList:true});
    }
    // initial bind after DOM
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', bind);
    } else {
      bind();
    }
    // observe future creations
    const rootObs = new MutationObserver(() => {
      if (document.querySelector(tableSelector)) bind();
    });
    rootObs.observe(document.body, {childList:true, subtree:true});
  }

  ['#modal-table','#noJudgeTable'].forEach(watch);
})();

// === Modal utilities: close on backdrop/outside & ESC ===
(function ensureModalBehaviors(){
  function bindModalClose(modal){
    if (!modal || modal.__closeBound) return;
    modal.__closeBound = true;
    const close = () => { modal.classList.remove('is-open','active'); modal.setAttribute('aria-hidden','true'); };
    modal.__close = modal.__close || close;
    // backdrop
    modal.addEventListener('click', function(e){
      const card = modal.querySelector('.modal-card');
      const isBackdrop = e.target.classList && e.target.classList.contains('cm-backdrop');
      const outsideCard = card && !card.contains(e.target) && !isBackdrop;
      if (isBackdrop || outsideCard) close();
    });
    // Esc
    document.addEventListener('keydown', function(ev){
      if (ev.key === 'Escape') close();
    });
  }
  ['caseModal','noJudgeModal','detail-modal'].forEach(function(id){
    const m = document.getElementById(id);
    if (m) bindModalClose(m);
    // observe later-created
    const obs = new MutationObserver(() => {
      const mm = document.getElementById(id);
      if (mm) bindModalClose(mm);
    });
    obs.observe(document.body, {childList:true, subtree:true});
  });
})();

// === Simple pager helpers ===
function __applyPager(tableSelector, pageSize){
  try {
    pageSize = pageSize || 20;
    const table = document.querySelector(tableSelector);
    if (!table) return;
    const tbody = table.querySelector('tbody');
    if (!tbody) return;
    const rows = Array.from(tbody.querySelectorAll('tr'));
    if (rows.length <= pageSize) return; // no need

    // Preserve all rows
    const data = rows.map(tr => tr.outerHTML);
    tbody.innerHTML = '';

    let page = 1;
    const total = data.length;
    const pages = Math.ceil(total / pageSize);

    // Find appropriate footer: modal-foot or card-footer/card-foot
    const modal = table.closest('.cmodal, .modal-card');
    let footer = modal ? modal.querySelector('.modal-foot') : null;

    if (!footer) {
      const card = table.closest('.card');
      footer = card ? (card.querySelector('.card-footer') || card.querySelector('.card-foot')) : null;
    }

    const pagerId = tableSelector.replace(/[^a-z0-9_-]/gi,'') + '_pager';
    let pager = footer ? footer.querySelector('#' + pagerId) : table.parentElement.querySelector('#' + pagerId);

    if (!pager){
      pager = document.createElement('div');
      pager.id = pagerId;
      pager.style.display = 'flex';
      pager.style.justifyContent = 'space-between';
      pager.style.alignItems = 'center';
      pager.style.gap = '8px';

      if (footer) {
        footer.appendChild(pager);
      } else {
        pager.style.marginTop = '10px';
        table.parentElement.appendChild(pager);
      }
    }

    function renderPage(){
      const start = (page - 1) * pageSize;
      const slice = data.slice(start, start + pageSize);
      tbody.innerHTML = slice.join('');
      pager.innerHTML = '';
      const info = document.createElement('div');
      info.className = 'muted';
      info.textContent = 'Sayfa ' + page + ' / ' + pages + ' — ' + total + ' kayıt';
      const left = document.createElement('div');
      const right = document.createElement('div');
      function mkBtn(label, disabled, on){
        const b = document.createElement('button');
        b.className = 'btn' + (disabled ? ' ghost' : '');
        b.type = 'button';
        b.textContent = label;
        b.disabled = !!disabled;
        if (on) b.addEventListener('click', on);
        return b;
      }
      left.appendChild(mkBtn('« İlk', page === 1, () => { page = 1; renderPage(); }));
      left.appendChild(mkBtn('‹ Önceki', page === 1, () => { page--; renderPage(); }));
      right.appendChild(mkBtn('Sonraki ›', page === pages, () => { page++; renderPage(); }));
      right.appendChild(mkBtn('Son »', page === pages, () => { page = pages; renderPage(); }));
      pager.appendChild(left);
      pager.appendChild(info);
      pager.appendChild(right);
    }
    renderPage();
  } catch (e){ console.error('pager error', e); }
}

// Auto-paginate #noJudgeTable when caseModal opens
(function(){
  const modal = document.getElementById('caseModal');
  if (!modal) return;
  const obs = new MutationObserver(() => {
    if (modal.classList.contains('is-open') || modal.getAttribute('aria-hidden') === 'false') {
      setTimeout(() => __applyPager('#noJudgeTable', 20), 0);
    }
  });
  obs.observe(modal, { attributes:true, attributeFilter:['class','aria-hidden'] });
})();


// [fusion] saveBtn toast
(function(){
  if (window.__saveBtnToastBound) return; window.__saveBtnToastBound = true;
  const btn = document.getElementById('saveBtn');
  if (!btn) return;
  btn.addEventListener('click', function(){
    try {
      let ok = true;
      if (typeof save === 'function') ok = !!save();
      const old = btn.textContent;
      btn.textContent = ok ? 'Kaydedildi ✔' : 'Kaydedilemedi ✖';
      setTimeout(() => { btn.textContent = old; }, 1400);
      if (window.toast) {
        window.toast({
          type: ok ? 'success' : 'error',
          title: ok ? 'Başarılı' : 'Hata',
          body: ok ? 'Ayarlar başarıyla kaydedildi.' : 'Kayıt sırasında bir hata oluştu.'
        });
      }
    } catch (_e){ if (window.toast) window.toast({type:'success', title:'Başarılı', body:'Ayarlar başarıyla kaydedildi.'}); }
  });
})();

// ========================================
// AUTO SPINNER FOR FILE INPUTS
// ========================================
(function initAutoSpinner() {
  // Tüm file input'ları dinle
  document.addEventListener('change', function(e) {
    const target = e.target;

    // Sadece file input'ları için çalış
    if (target.tagName === 'INPUT' && target.type === 'file' && target.files.length > 0) {
      const file = target.files[0];

      // Geçerli dosya uzantısı kontrolü
      const accept = target.getAttribute('accept') || '';
      const validExtensions = accept.split(',').map(ext => ext.trim().toLowerCase());
      const fileName = file.name.toLowerCase();

      // Uzantı kontrolü
      const isValid = validExtensions.length === 0 || validExtensions.some(ext => {
        const cleanExt = ext.replace('.', '');
        return fileName.endsWith('.' + cleanExt);
      });

      if (isValid && window.showSpinner) {
        window.showSpinner('Veriler işleniyor...');

        // 2 saniye sonra spinner'ı kapat
        setTimeout(function() {
          if (window.hideSpinner) {
            window.hideSpinner();
          }
        }, 2000);
      }
    }
  });

  // Dropzone'lar için drop olayını dinle
  document.addEventListener('drop', function(e) {
    try {
      const dt = e.dataTransfer;
      if (!dt || !dt.files || dt.files.length === 0) return;
      // Sadece .dropzone veya #dropZone içine bırakıldıysa tetikle
      const el = e.target instanceof Element ? e.target.closest('.dropzone, #dropZone') : null;
      if (!el) return;
      if (window.showSpinner) {
        window.showSpinner('Veriler işleniyor...');
        setTimeout(function(){ window.hideSpinner && window.hideSpinner(); }, 2000);
      }
    } catch (_) { /* ignore */ }
  }, true);
})();

// ========================================
// Log Panel Interaction
// NOT: Toggle işlevi theme-manager.js'de yönetiliyor
// ========================================
(function initLogPanel(){
  const badge = document.getElementById('logBadge');
  
  // Badge güncelleme fonksiyonu
  function updateLogBadge() {
    const logCount = (window.__LOG_BUFFER__ || []).length;
    if (badge) {
      if (logCount > 0) {
        badge.textContent = logCount > 99 ? '99+' : logCount;
        badge.hidden = false;
      } else {
        badge.hidden = true;
      }
    }
  }
  
  // İlk badge güncellemesi
  updateLogBadge();
  // Periyodik badge güncellemesi (her 2 saniyede)
  setInterval(updateLogBadge, 2000);
})();

// ========================================
// News Dropdown Panel
// NOT: Toggle işlevi theme-manager.js'de yönetiliyor
// ========================================
(function initNewsDropdown(){
  const dropdown = document.getElementById('newsDropdown');
  const badge = document.getElementById('newsBadge');
  const metaEl = document.getElementById('newsDropdownMeta');
  const listEl = document.getElementById('newsDropdownList');
  const pagerEl = document.getElementById('newsDropdownPager');
  
  if (!dropdown) return;
  
  const NEWS_URL = '/data/teftis.json';
  const PAGE_SIZE = 5;
  let newsItems = [];
  let currentPage = 1;
  
  function updateBadge() {
    if (badge && newsItems.length > 0) {
      badge.textContent = newsItems.length > 99 ? '99+' : newsItems.length;
      badge.hidden = false;
    } else if (badge) {
      badge.hidden = true;
    }
  }
  
  function loadNews() {
    if (metaEl) metaEl.textContent = 'Yükleniyor...';
    
    fetch(NEWS_URL, { cache: 'no-store' })
      .then(res => res.ok ? res.json() : Promise.reject('HTTP ' + res.status))
      .then(data => {
        console.log('[News] Haberler yüklendi:', data);
        // data direkt array veya {haberler: [...]} formatında olabilir
        const haberler = Array.isArray(data) ? data : (data.haberler || []);
        newsItems = haberler.map(h => ({
          tarih: h.tarih || h.Tarih || h.date || '',
          icerik: h.icerik || h.İcerik || h.content || ''
        })).filter(h => h.tarih && h.icerik);
        
        console.log('[News] İşlenmiş haberler:', newsItems);
        
        // Tarihe göre sırala (yeni → eski)
        newsItems.sort((a, b) => {
          const dateA = new Date(a.tarih).getTime() || 0;
          const dateB = new Date(b.tarih).getTime() || 0;
          return dateB - dateA;
        });
        
        updateBadge();
        renderNews(1);
        addNewsToFooterSlider();
        console.log('[News] Footer slider\'a eklendi');
      })
      .catch(err => {
        console.error('[News] Yükleme hatası:', err);
        if (metaEl) metaEl.textContent = 'Haberler yüklenemedi.';
        if (listEl) listEl.innerHTML = '<p class="muted">Bir hata oluştu.</p>';
      });
  }
  
  function formatDateWithDay(dateStr) {
    try {
      // Tarihi YYYY-MM-DD veya YYYY-M-D formatından parse et
      if (dateStr && /^\d{4}-\d{1,2}-\d{1,2}$/.test(dateStr)) {
        const parts = dateStr.split('-');
        const year = parts[0];
        const month = parts[1].padStart(2, '0');
        const day = parts[2].padStart(2, '0');
        dateStr = `${year}-${month}-${day}`;
      }
      
      const date = new Date(dateStr);
      if (!isNaN(date.getTime())) {
        const formatted = date.toLocaleDateString('tr-TR', {
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        });
        const dayName = date.toLocaleDateString('tr-TR', { weekday: 'long' });
        const capitalizedDay = dayName.charAt(0).toUpperCase() + dayName.slice(1);
        return `${formatted} - ${capitalizedDay}`;
      }
    } catch {
      return dateStr;
    }
    return dateStr;
  }
  
  function addNewsToFooterSlider() {
    console.log('[News] addNewsToFooterSlider çağrıldı');
    console.log('[News] appendFooterItems mevcut mu?', typeof window.appendFooterItems);
    console.log('[News] newsItems:', newsItems);
    
    if (typeof window.appendFooterItems !== 'function') {
      console.warn('[News] appendFooterItems fonksiyonu bulunamadı');
      return;
    }
    if (!newsItems || newsItems.length === 0) {
      console.warn('[News] newsItems boş');
      return;
    }
    
    const sliderItems = [];
    newsItems.forEach(item => {
      const formattedDate = formatDateWithDay(item.tarih);
      const lines = item.icerik.split('\n').filter(line => line.trim());
      
      lines.forEach(line => {
        const html = `${formattedDate} - ${line.trim()}`;
        sliderItems.push(html);
      });
    });
    
    console.log('[News] Slider\'a eklenecek item sayısı:', sliderItems.length);
    window.appendFooterItems(sliderItems);
  }
  
  function renderNews(page) {
    if (!newsItems || newsItems.length === 0) {
      if (metaEl) metaEl.textContent = 'Haber bulunamadı.';
      if (listEl) listEl.innerHTML = '';
      if (pagerEl) pagerEl.innerHTML = '';
      return;
    }
    
    const total = newsItems.length;
    const totalPages = Math.max(1, Math.ceil(total / PAGE_SIZE));
    if (page < 1) page = 1;
    if (page > totalPages) page = totalPages;
    currentPage = page;
    
    const start = (page - 1) * PAGE_SIZE;
    const end = start + PAGE_SIZE;
    const slice = newsItems.slice(start, end);
    
    // Meta güncelle
    if (metaEl) {
      const now = new Date().toLocaleDateString('tr-TR', { 
        year: 'numeric', month: '2-digit', day: '2-digit' 
      });
      metaEl.textContent = `${total} haber · Son güncelleme: ${now}`;
    }
    
    // Liste render et
    if (listEl) {
      listEl.innerHTML = '';
      console.log('[News] Rendering news items, slice:', slice);
      
      slice.forEach(item => {
        // Tarihi Türkçe formata çevir (gün adıyla)
        const formattedDate = formatDateWithDay(item.tarih);
        console.log('[News] Formatted date:', formattedDate);
        
        // Dropdown'da haber bölünmeden gösterilir
        const newsItem = document.createElement('div');
        newsItem.className = 'news-item';
        
        const dateDiv = document.createElement('div');
        dateDiv.className = 'news-date muted';
        dateDiv.textContent = formattedDate;
        
        const contentDiv = document.createElement('div');
        contentDiv.className = 'news-content';
        // \n karakterlerini <br> ile değiştir
        contentDiv.innerHTML = (item.icerik || '').replace(/\n/g, '<br>');
        
        newsItem.appendChild(dateDiv);
        newsItem.appendChild(contentDiv);
        listEl.appendChild(newsItem);
      });
      
      console.log('[News] Rendered items count:', listEl.children.length);
    }
    
    // Pager render et
    if (pagerEl && totalPages > 1) {
      pagerEl.innerHTML = '';
      
      const prevDiv = document.createElement('div');
      const prevBtn = document.createElement('button');
      prevBtn.type = 'button';
      prevBtn.className = 'btn ghost';
      prevBtn.textContent = 'Önceki';
      prevBtn.disabled = page === 1;
      prevBtn.onclick = () => renderNews(currentPage - 1);
      prevDiv.appendChild(prevBtn);
      
      const centerDiv = document.createElement('div');
      const pageInfo = document.createElement('div');
      pageInfo.className = 'muted';
      pageInfo.textContent = `Sayfa ${page} / ${totalPages}`;
      centerDiv.appendChild(pageInfo);
      
      const nextDiv = document.createElement('div');
      const nextBtn = document.createElement('button');
      nextBtn.type = 'button';
      nextBtn.className = 'btn ghost';
      nextBtn.textContent = 'Sonraki';
      nextBtn.disabled = page === totalPages;
      nextBtn.onclick = () => renderNews(currentPage + 1);
      nextDiv.appendChild(nextBtn);
      
      pagerEl.appendChild(prevDiv);
      pagerEl.appendChild(centerDiv);
      pagerEl.appendChild(nextDiv);
    } else if (pagerEl) {
      pagerEl.innerHTML = '';
    }
  }
  
  // İlk yükleme
  loadNews();
  
  // Global fonksiyonları expose et (theme-manager.js kullanabilsin)
  window.newsDropdownLoadNews = loadNews;
  window.newsDropdownRenderNews = renderNews;
})();

/* ========================================
   SCROLL TO TOP BUTTON
   ======================================== */

(function() {
  const scrollBtn = document.getElementById('scrollToTop');
  if (!scrollBtn) return;
  
  // Scroll pozisyonunu kontrol et
  function checkScroll() {
    if (window.pageYOffset > 300) {
      scrollBtn.classList.add('show');
    } else {
      scrollBtn.classList.remove('show');
    }
  }
  
  // Yukarı scroll
  function scrollToTop() {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  }
  
  // Event listeners
  window.addEventListener('scroll', checkScroll);
  scrollBtn.addEventListener('click', scrollToTop);
  
  // İlk kontrol
  checkScroll();
})();
