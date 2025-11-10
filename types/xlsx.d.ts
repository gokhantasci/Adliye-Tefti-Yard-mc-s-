// Basic XLSX type definitions
declare module 'xlsx' {
  export interface WorkBook {
    SheetNames: string[];
    Sheets: { [sheet: string]: WorkSheet };
  }

  export interface WorkSheet {
    [cell: string]: CellObject;
  }

  export interface CellObject {
    v: any;
    w?: string;
    t: string;
    f?: string;
    r?: string;
    h?: string;
    c?: any[];
    z?: string;
  }

  export function read(data: any, opts?: any): WorkBook;
  export const utils: {
    sheet_to_json: (sheet: WorkSheet, opts?: any) => any[];
    sheet_to_csv: (sheet: WorkSheet, opts?: any) => string;
  };
}
