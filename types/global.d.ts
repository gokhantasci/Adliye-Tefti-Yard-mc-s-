// Global window extensions
interface Window {
  TeftisUtils: typeof TeftisUtils;
  XLSX: any;
  JSZip: any;
  jQuery: any;
  $: any;
  addNote: (val?: string) => void;
  renderNotes: () => void;
  toast: (opts: ToastOptions) => void;
  escapeHtml: (s: string) => string;
  formatRetryMessage: (sec: number) => string;
  letterToIndex: (col: string) => number;
  normalizeTurkish: (s: string) => string;
  showSpinner: (text?: string) => void;
  hideSpinner: () => void;
  logEvent: (type: string, message: string) => void;
  XlsSpinner: {
    show: () => void;
    hide: () => void;
  };
  setInlineXlsLoading: (container: string | HTMLElement, active: boolean) => void;
}

interface ToastOptions {
  type: 'success' | 'danger' | 'warning' | 'info';
  title: string;
  body: string;
  delay?: number;
}

// TeftisUtils namespace
declare namespace TeftisUtils {
  function letterToIndex(col: string): number;
  function escapeHtml(s: string): string;
  function normalizeTurkish(s: string): string;
  function showToast(opts: ToastOptions): void;
  function toastWithIcon(
    type: 'success' | 'danger' | 'warning' | 'info',
    title: string,
    msg: string,
    delay?: number
  ): void;
  function formatNumber(n: number): string;
  function formatDate(d: Date | string): string;
  function debounce<T extends (...args: any[]) => any>(
    fn: T,
    delay: number
  ): (...args: Parameters<T>) => void;
  function isExcelFile(file: File): boolean;
  function isValidAdaletEmail(email: string): boolean;
  function extractEmail(text: string): string | null;
  function formatRetryMessage(sec: number): string;
  function createElement<K extends keyof HTMLElementTagNameMap>(
    tag: K,
    attrs?: Record<string, string>,
    html?: string
  ): HTMLElementTagNameMap[K];
}
