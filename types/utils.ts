/**
 * ========================================
 * GLOBAL UTILITY FUNCTIONS - TypeScript Version
 * ========================================
 * Centralized utility functions used across the application
 * This file should be loaded before other JavaScript files
 * ========================================

// Extend Window interface for global properties
// Types and global declarations moved to utils-globals.d.ts

(function(window: Window): void {
  'use strict';

// Implementation code moved to top-level scope for TypeScript compatibility
'use strict';
const windowAny = window as any;
  /**
   * Convert Excel column letter to zero-based index
   */
  function letterToIndex(col: string): number {
    col = String(col || '').trim().toUpperCase();
    let n = 0;
    for (let i = 0; i < col.length; i++) {
      n = n * 26 + (col.charCodeAt(i) - 64);
    }
    return n - 1;
  }

  /**
   * Escape HTML special characters to prevent XSS
   */
  function escapeHtml(s: string): string {
    const map: Record<string, string> = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;'
    };
    return String(s ?? '').replace(/[&<>"']/g, (m) => map[m]);
  }

  /**
   * Normalize Turkish text for comparison
   */
  function normalizeTurkish(s: string): string {
    return String(s ?? '')
      .replace(/\u00A0/g, ' ')
      .replace(/\r?\n+/g, ' ')
      .trim()
      .toLowerCase()
      .replace(/\s+/g, ' ')
      .replaceAll('ı', 'i')
      .replaceAll('İ', 'i')
      .replaceAll('ş', 's')
      .replaceAll('Ş', 's')
      .replaceAll('ğ', 'g')
      .replaceAll('Ğ', 'g')
      .replaceAll('ö', 'o')
      .replaceAll('Ö', 'o')
      .replaceAll('ü', 'u')
      .replaceAll('Ü', 'u')
      .replaceAll('ç', 'c')
      .replaceAll('Ç', 'c');
  }

  /**
   * Show a toast notification
   */
  function showToast(opts: ToastOptions): void {
    if (typeof window.toast === 'function') {
      window.toast(opts);
    } else {
      /* Toast sistem yoksa sessizce atla */
    }
  }

  /**
   * Toast with icon helper
   */
  function toastWithIcon(type: ToastType, title: string, msg: string, delay: number = 5000): void {
    const icons: Record<ToastType, string> = {
      success: 'check_circle',
      warning: 'warning',
      danger: 'error',
      info: 'info'
    };
    const icon = icons[type] || 'info';

    const bodyHtml = `<div style="display:flex;align-items:flex-start;gap:.5rem;">
      <span class="material-symbols-rounded" style="font-size:22px;">${icon}</span>
      <div>${msg}</div>
    </div>`;

    showToast({ type, title, body: bodyHtml, delay });
  }

  /**
   * Format number with Turkish locale
   */
  function formatNumber(n: number): string {
    return new Intl.NumberFormat('tr-TR').format(n || 0);
  }

  /**
   * Format date with Turkish locale
   */
  function formatDate(d: Date | string, options: Intl.DateTimeFormatOptions = { dateStyle: 'short', timeStyle: 'short' }): string {
    try {
      const date = d instanceof Date ? d : new Date(d);
      return new Intl.DateTimeFormat('tr-TR', options).format(date);
    } catch (e) {
      return String(d);
    }
  }

  // ...existing code...

  /**
   * Check if file is an Excel file
   */
  function isExcelFile(file: File): boolean {
    if (!file || !file.name) return false;
    const name = file.name.toLowerCase();
    return name.endsWith('.xls') || name.endsWith('.xlsx');
  }

  /**
   * Validate email with @adalet.gov.tr domain
   */
  function isValidAdaletEmail(email: string): boolean {
    return /^[A-Z0-9._%+-]+@adalet\.gov\.tr$/i.test(String(email || '').trim());
  }

  /**
   * Extract email from text
   */
  function extractEmail(text: string): string | null {
    const match = String(text || '').match(/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i);
    return match ? match[0].trim() : null;
  }

  /**
   * Format retry message based on seconds
   */
  function formatRetryMessage(sec: number): string {
    sec = Number(sec) || 0;
    if (sec <= 0) return 'Bir süre sonra tekrar deneyin.';
    if (sec < 60) return sec + ' sn sonra tekrar deneyin.';
    const mins = Math.ceil(sec / 60);
    return mins + ' dk sonra tekrar deneyin.';
  }

  /**
   * Simple DOM element creator
   */
  function createElement<K extends keyof HTMLElementTagNameMap>(
    tag: K,
    attrs?: Record<string, string>,
    html?: string
  ): HTMLElementTagNameMap[K] {
    const el = document.createElement(tag);
    if (attrs) {
      for (const key in attrs) {
        if (attrs.hasOwnProperty(key)) {
          el.setAttribute(key, attrs[key]);
        }
      }
    }
    if (html != null) {
      el.innerHTML = html;
    }
    return el;
  }

  // ...existing code...

  /**
   * Multiple selector helper
   */
  function $$<E extends Element = Element>(selector: string, root: ParentNode = document): NodeListOf<E> {
    return root.querySelectorAll<E>(selector);
  }

  /**
   * Show global loading spinner
   */
  function showSpinner(text?: string): void {
    const spinner = document.getElementById('globalSpinner');
    if (!spinner) return;

    const textEl = spinner.querySelector('.spinner-text');
    if (textEl && text) {
      textEl.textContent = text;
    }

    spinner.style.display = 'flex';
  }

  /**
   * Hide global loading spinner
   */
  function hideSpinner(): void {
    const spinner = document.getElementById('globalSpinner');
    if (!spinner) return;
    spinner.style.display = 'none';
  }

  // =============================
  // Unified Log System
  // =============================
  const LOG_STORAGE_KEY = 'app_logs';
  const LOG_EXCLUDE_TYPES = new Set(['ui', 'warn', 'log', 'error']);
  const _logBuffer: LogEntry[] = loadLogsFromStorage();
  const LOG_PAGER = { page: 1, pageSize: 10 };

  function loadLogsFromStorage(): LogEntry[] {
    try {
      const stored = localStorage.getItem(LOG_STORAGE_KEY);
      if (!stored) return [];
      const parsed = JSON.parse(stored);
      return parsed.map((e: any) => ({...e, time: new Date(e.time)}));
    } catch (_) { 
      return []; 
    }
  }

  function saveLogsToStorage(): void {
    try {
      const toSave = _logBuffer.slice(-500).map(e => ({
        time: e.time.toISOString(),
        type: e.type,
        message: e.message
      }));
      localStorage.setItem(LOG_STORAGE_KEY, JSON.stringify(toSave));
    } catch (_) { 
      /* silent */ 
    }
  }

  function logEvent(type: string, message: string): void {
    try {
      if (LOG_EXCLUDE_TYPES.has(type)) return;
      const time = new Date();
      _logBuffer.push({ time, type, message });
      if (_logBuffer.length > 500) _logBuffer.shift();
      renderLogsPage();
      saveLogsToStorage();
    } catch (_e) { 
      /* silent */ 
    }
  }

  function renderLogPanelRow(entry: LogEntry, targetBody?: HTMLElement): void {
    const body = targetBody || document.getElementById('logPanelBody');
    if (!body) return;
    const row = document.createElement('div');
    row.className = 'log-row';
    const hh = String(entry.time.getHours()).padStart(2,'0');
    const mm = String(entry.time.getMinutes()).padStart(2,'0');
    const ss = String(entry.time.getSeconds()).padStart(2,'0');
    const t = String(entry.type || 'info').toLowerCase();
    const typeClass = `log-type log-type-${t}`;
    row.innerHTML = `<div class="log-time">${hh}:${mm}:${ss}</div>
      <div><span class="${typeClass}">${escapeHtml(entry.type)}</span> <span class="log-msg">${escapeHtml(entry.message)}</span></div>`;
    body.appendChild(row);
  }

  function updateLogStats(): void {
    const stats = document.getElementById('logStats');
    const filtered = _logBuffer.filter(e => !LOG_EXCLUDE_TYPES.has(e.type));
    if (stats) stats.textContent = filtered.length + ' kayıt';
  }

  function renderLogsPage(): void {
    const body = document.getElementById('logPanelBody');
    const info = document.getElementById('logPagerInfo');
    const prev = document.getElementById('logPrevBtn');
    const next = document.getElementById('logNextBtn');
    if (!body) return;

    const data = _logBuffer.filter(e => !LOG_EXCLUDE_TYPES.has(e.type)).sort((a,b) => b.time.getTime() - a.time.getTime());
    const total = data.length;
    const pages = Math.max(1, Math.ceil(total / LOG_PAGER.pageSize));
    if (LOG_PAGER.page < 1) LOG_PAGER.page = 1;
    if (LOG_PAGER.page > pages) LOG_PAGER.page = pages;

    const start = (LOG_PAGER.page - 1) * LOG_PAGER.pageSize;
    const slice = data.slice(start, start + LOG_PAGER.pageSize);
    body.innerHTML = '';
    slice.forEach(entry => renderLogPanelRow(entry, body));
    updateLogStats();

    if (info) info.textContent = `Sayfa ${LOG_PAGER.page}/${pages} — ${total} kayıt`;
    if (prev) (prev as HTMLButtonElement).disabled = (LOG_PAGER.page === 1);
    if (next) (next as HTMLButtonElement).disabled = (LOG_PAGER.page === pages);
  }

  function renderAllLogs(): void {
    renderLogsPage();
  }

  function bindLogPagerControls(): void {
    const prev = document.getElementById('logPrevBtn');
    const next = document.getElementById('logNextBtn');
    if (prev) prev.addEventListener('click', () => { LOG_PAGER.page--; renderLogsPage(); });
    if (next) next.addEventListener('click', () => { LOG_PAGER.page++; renderLogsPage(); });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => { bindLogPagerControls(); renderAllLogs(); });
  } else {
    bindLogPagerControls();
    setTimeout(renderAllLogs, 100);
  }

  // Ensure toast logging
  (function ensureToastLogging() {
    function normalize(args: IArguments | any[]): ToastOptions {
      if (!args.length) return { type: 'info', title: '', body: '' };
      if (typeof args[0] === 'object' && !Array.isArray(args[0])) return {...args[0]};
      const type = args[0] as ToastType;
      const title = args[1] as string;
      const body = args[2] as string;
      const extra = (typeof args[3] === 'object' && args[3]) || {};
      return { ...extra, type, title, body };
    }

    function wrap(fn: Function): Function {
      if (typeof fn !== 'function') return fn;
      if ((fn as any).__wrappedToastLogger) return fn;
      const wrapped = function(this: any, ...args: any[]): any {
        const opts = normalize(args);
        try {
          const toastType = String(opts.type || 'info').toLowerCase();
          const msg = (opts.title || '') + (opts.body ? ': ' + opts.body : '');
          if (window.logEvent) window.logEvent(toastType, msg);
        } catch (_) { }
        return fn.apply(this, [opts]);
      };
      (wrapped as any).__wrappedToastLogger = true;
      return wrapped;
    }

    function hook(): void {
      if (typeof window.toast === 'function' && !(window.toast as any).__wrappedToastLogger) {
        window.toast = wrap(window.toast) as any;
        (window as any).__TOAST_LOG_WRAP_ACTIVE = true;
      }
    }

    hook();
    setTimeout(hook, 0);
    setInterval(hook, 1000);
  })();

  // Export utilities to global scope
  window.TeftisUtils = {
    letterToIndex,
    escapeHtml,
    normalizeTurkish,
    showToast,
    toastWithIcon,
    formatNumber,
    formatDate,
    debounce,
    isExcelFile,
    isValidAdaletEmail,
    extractEmail,
    formatRetryMessage,
    createElement,
    $,
    $$,
    showSpinner,
    hideSpinner,
    logEvent
  } as any;

  // Backward compatibility
  window.letterToIndex = letterToIndex;
  window.escapeHtml = escapeHtml;
  window.normalizeTurkish = normalizeTurkish;
  window.formatRetryMessage = formatRetryMessage;
  window.showSpinner = showSpinner;
  window.hideSpinner = hideSpinner;
  window.logEvent = logEvent;

  // Expose buffer for export
  (window as any).__LOG_BUFFER__ = _logBuffer;


