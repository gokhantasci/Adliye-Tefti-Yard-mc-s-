// TypeScript global declarations for utils
// Place in the project root or with utils.ts

export {};

declare global {
  interface Window {
    TeftisUtils?: any;
    toast?: (opts: ToastOptions) => void;
    logEvent?: (type: string, message: string) => void;
    letterToIndex?: (col: string) => number;
    escapeHtml?: (s: string) => string;
    normalizeTurkish?: (s: string) => string;
    formatRetryMessage?: (sec: number) => string;
    showSpinner?: (text?: string) => void;
    hideSpinner?: () => void;
  }

  type ToastType = 'success' | 'danger' | 'warning' | 'info';

  interface ToastOptions {
    type: ToastType;
    title: string;
    body: string;
    delay?: number;
  }

  interface LogEntry {
    time: Date;
    type: string;
    message: string;
  }
}
