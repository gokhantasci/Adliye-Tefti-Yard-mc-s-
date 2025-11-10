// Tema ve UI Kontrolleri
(function(){
  const themeKey = 'adalet-theme';
  const root = document.documentElement;
  let saved = localStorage.getItem(themeKey) || 'light';
  root.setAttribute('data-bs-theme', saved);
  const themeToggle = document.getElementById('themeToggle');
  const themeIcon = document.getElementById('themeIcon');
  const logToggle = document.getElementById('logToggle');
  const logPanel = document.getElementById('logPanel');
  const logClearBtn = document.getElementById('logClearBtn');
  const newsToggle = document.getElementById('newsToggle');
  const newsDropdown = document.getElementById('newsDropdown');
  const newsCloseBtn = document.getElementById('newsCloseBtn');
  function updateIcon(){
    const mode = root.getAttribute('data-bs-theme') || 'light';
    if (themeIcon) themeIcon.textContent = (mode === 'dark' ? 'light_mode' : 'dark_mode');
  }
  function toggleTheme(){
    const cur = root.getAttribute('data-bs-theme') || 'light';
    const next = cur === 'dark' ? 'light' : 'dark';
    root.setAttribute('data-bs-theme', next);
    localStorage.setItem(themeKey, next);
    updateIcon();
    console.log('[THEME] Degistirildi:', next);
  }
  function toggleLogPanel(){
    if (!logPanel) return;
    logPanel.hidden = !logPanel.hidden;
    console.log('[LOG] Panel:', logPanel.hidden ? 'kapatildi' : 'acildi');
  }
  function clearLogs(){
    const body = document.getElementById('logPanelBody');
    if (body) body.innerHTML = '<div class="text-muted text-center py-4">Loglar temizlendi</div>';
    if (window.__LOG_BUFFER__) window.__LOG_BUFFER__.length = 0;
    try { localStorage.removeItem('app_logs'); } catch (e) {}
    console.log('[LOG] Temizlendi');
  }
  function toggleNewsDropdown(){
    if (!newsDropdown) return;
    newsDropdown.hidden = !newsDropdown.hidden;
    if (!newsDropdown.hidden && typeof window.newsDropdownLoadNews === 'function') {
      window.newsDropdownLoadNews();
    }
    console.log('[NEWS] Dropdown:', newsDropdown.hidden ? 'kapatildi' : 'acildi');
  }
  if (themeToggle) {
    themeToggle.addEventListener('click', function(e){
      e.preventDefault();
      toggleTheme();
    });
    console.log('[INIT] Theme toggle OK');
  }
  if (logToggle) {
    logToggle.addEventListener('click', function(e){
      e.preventDefault();
      toggleLogPanel();
    });
    console.log('[INIT] Log toggle OK');
  }
  if (logClearBtn) {
    logClearBtn.addEventListener('click', function(e){
      e.preventDefault();
      clearLogs();
    });
    console.log('[INIT] Log clear OK');
  }
  if (newsToggle) {
    newsToggle.addEventListener('click', function(e){
      e.preventDefault();
      toggleNewsDropdown();
    });
    console.log('[INIT] News toggle OK');
  }
  if (newsCloseBtn) {
    newsCloseBtn.addEventListener('click', function(e){
      e.preventDefault();
      toggleNewsDropdown();
    });
    console.log('[INIT] News close OK');
  }
  document.addEventListener('click', function(e){
    if (newsDropdown && !newsDropdown.hidden) {
      if (!newsDropdown.contains(e.target) && (!newsToggle || !newsToggle.contains(e.target))) {
        newsDropdown.hidden = true;
      }
    }
    if (logPanel && !logPanel.hidden) {
      if (!logPanel.contains(e.target) && (!logToggle || !logToggle.contains(e.target))) {
        logPanel.hidden = true;
      }
    }
  });
  document.addEventListener('keydown', function(e){
    if (e.key === 'Escape') {
      if (newsDropdown) newsDropdown.hidden = true;
      if (logPanel) logPanel.hidden = true;
    }
  });
  updateIcon();
  console.log('[THEME-MANAGER] Hazir');
})();
