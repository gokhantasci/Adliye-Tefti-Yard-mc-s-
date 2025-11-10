// Tema ve UI Kontrolleri
(function(){
  const themeKey = 'adalet-theme';
  const desaturateKey = 'adalet-desaturate';
  const root = document.documentElement;
  let saved = localStorage.getItem(themeKey) || 'light';
  root.setAttribute('data-bs-theme', saved);
  const themeToggle = document.getElementById('themeToggle');
  const themeIcon = document.getElementById('themeIcon');
  const desaturateToggle = document.getElementById('desaturateToggle');
  const logToggle = document.getElementById('logToggle');
  const logPanel = document.getElementById('logPanel');
  const logClearBtn = document.getElementById('logClearBtn');
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
  }
  function toggleDesaturate(){
    const isDesaturated = document.body.classList.toggle('desaturate');
    localStorage.setItem(desaturateKey, isDesaturated ? '1' : '0');
  }
  function initDesaturate(){
    const savedDesaturate = localStorage.getItem(desaturateKey);
    if (savedDesaturate === '1') {
      document.body.classList.add('desaturate');
    }
  }
  function toggleLogPanel(){
    if (!logPanel) return;
    logPanel.hidden = !logPanel.hidden;
  }
  function clearLogs(){
    const body = document.getElementById('logPanelBody');
    if (body) body.innerHTML = '<div class="text-muted text-center py-4">Loglar temizlendi</div>';
    if (window.__LOG_BUFFER__) window.__LOG_BUFFER__.length = 0;
    try { localStorage.removeItem('app_logs'); } catch (e) {}
    
    // Pager'ı güncelle
    if (typeof window.renderLogsPage === 'function') {
      window.renderLogsPage();
    } else {
      // Manuel pager güncelleme
      const info = document.getElementById('logPagerInfo');
      const stats = document.getElementById('logStats');
      const prev = document.getElementById('logPrevBtn');
      const next = document.getElementById('logNextBtn');
      if (info) info.textContent = 'Sayfa 1/1 — 0 kayıt';
      if (stats) stats.textContent = '0 kayıt';
      if (prev) prev.disabled = true;
      if (next) next.disabled = true;
    }
  }
  if (themeToggle) {
    themeToggle.addEventListener('click', function(e){
      e.preventDefault();
      toggleTheme();
    });
  }
  if (desaturateToggle) {
    desaturateToggle.addEventListener('click', function(e){
      e.preventDefault();
      toggleDesaturate();
    });
  }
  if (logToggle) {
    logToggle.addEventListener('click', function(e){
      e.preventDefault();
      toggleLogPanel();
    });
  }
  if (logClearBtn) {
    logClearBtn.addEventListener('click', function(e){
      e.preventDefault();
      clearLogs();
    });
  }
  document.addEventListener('click', function(e){
    if (logPanel && !logPanel.hidden) {
      if (!logPanel.contains(e.target) && (!logToggle || !logToggle.contains(e.target))) {
        logPanel.hidden = true;
      }
    }
  });
  document.addEventListener('keydown', function(e){
    if (e.key === 'Escape') {
      const newsDropdown = document.getElementById('newsDropdown');
      if (newsDropdown) newsDropdown.hidden = true;
      if (logPanel) logPanel.hidden = true;
    }
  });
  updateIcon();
  initDesaturate();
})();
