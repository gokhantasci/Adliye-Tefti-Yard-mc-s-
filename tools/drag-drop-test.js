import puppeteer from 'puppeteer';
import fs from 'fs';
import path from 'path';

(async () => {
  const url = process.env.TEST_URL || 'http://localhost:8080/karar';
  const csvPath = path.resolve('./tools/test-files/test.csv');
  if (!fs.existsSync(csvPath)) {
    console.error('Test CSV not found:', csvPath);
    process.exit(2);
  }

  const browser = await puppeteer.launch({ args: ['--no-sandbox', '--disable-setuid-sandbox'] });
  const page = await browser.newPage();

  const consoleMsgs = [];
  page.on('console', msg => consoleMsgs.push({ type: msg.type(), text: msg.text() }));
  const pageErrors = [];
  page.on('pageerror', err => pageErrors.push(String(err)));
  // Capture JS dialogs (alert/confirm) which some handlers use for errors
  const dialogs = [];
  page.on('dialog', async d => {
    try { dialogs.push({ type: d.type(), message: d.message() }); await d.dismiss(); } catch (e) {}
  });

  try {
    const resp = await page.goto(url, { waitUntil: 'networkidle2', timeout: 30000 });
    const status = resp ? resp.status() : null;

    // Read file contents in Node and pass as base64
    const data = fs.readFileSync(csvPath);
    const b64 = data.toString('base64');

    // Simulate drag/drop by creating a File inside the page from base64 and dispatching events
    const result = await page.evaluate(async (b64data) => {
      function base64ToArrayBuffer(base64) {
        const binaryString = atob(base64);
        const len = binaryString.length;
        const bytes = new Uint8Array(len);
        for (let i = 0; i < len; i++) bytes[i] = binaryString.charCodeAt(i);
        return bytes.buffer;
      }
      const arr = base64ToArrayBuffer(b64data);
      const blob = new Blob([arr], { type: 'text/csv' });
      const file = new File([blob], 'test.csv', { type: 'text/csv' });

      const el = document.querySelector('#dropZone');
      if (!el) return { error: 'no-dropzone' };

      // Build a DataTransfer with the file
      const dt = new DataTransfer();
      dt.items.add(file);

      // Dispatch sequence: dragenter, dragover, drop
      const dragenter = new DragEvent('dragenter', { bubbles: true, cancelable: true, dataTransfer: dt });
      el.dispatchEvent(dragenter);
      const dragover = new DragEvent('dragover', { bubbles: true, cancelable: true, dataTransfer: dt });
      el.dispatchEvent(dragover);
      const drop = new DragEvent('drop', { bubbles: true, cancelable: true, dataTransfer: dt });
      el.dispatchEvent(drop);

      // Allow page scripts to run
      return { dropped: true };
    }, b64);

    // Wait up to 5s for result table rows or KPI total change
    let processed = false;
    let kpi = null;
    for (let i = 0; i < 10; i++) {
      await new Promise(r => setTimeout(r, 500));
      const rows = await page.$$eval('#resultBody tr', els => els.length).catch(() => 0);
      const k = await page.$eval('#kpiTotal', el => el.textContent).catch(() => null);
      if (rows > 0 || (k && k.trim() !== '0')) { processed = true; kpi = k; break; }
    }

    // Fallback: if synthetic drag/drop did not trigger processing (some browsers restrict DataTransfer files),
    // try directly setting the hidden input's files using Puppeteer's uploadFile API.
    if (!processed) {
      const input = await page.$('#excelInput');
      if (input) {
        // Install a temporary listener to detect if the page's change handlers fire
        await page.evaluate(() => {
          window.__test_change_fired = false;
          const inp = document.querySelector('#excelInput');
          if (inp) inp.addEventListener('change', () => { window.__test_change_fired = true; });
        });

        await input.uploadFile(csvPath);

        // Inspect files on the input after upload and trigger change
        const fileInfo = await page.evaluate(() => {
          const inp = document.querySelector('#excelInput');
          if (!inp) return { present: false };
          const len = inp.files ? inp.files.length : 0;
          const name = len ? inp.files[0].name : null;
          // Dispatch change in case listener is waiting for an event
          inp.dispatchEvent(new Event('change', { bubbles: true }));
          return { present: true, length: len, name, changeFired: window.__test_change_fired };
        });
        console.log('UPLOAD-FALLBACK-INPUT-INFO', fileInfo);
        // Read the uploaded file text inside the page and return a small preview to ensure File.text() works
        const preview = await page.evaluate(async () => {
          const inp = document.querySelector('#excelInput');
          if (!inp || !inp.files || !inp.files[0]) return null;
          const txt = await inp.files[0].text();
          return String(txt).slice(0, 400);
        });
        console.log('UPLOAD-FALLBACK-PREVIEW', preview && preview.length ? preview.replace(/\r?\n/g, '\\n') : '<empty>');
        // Give the page a moment to handle the change event
        for (let i = 0; i < 10; i++) {
          await new Promise(r => setTimeout(r, 500));
          const rows = await page.$$eval('#resultBody tr', els => els.length).catch(() => 0);
          const k = await page.$eval('#kpiTotal', el => el.textContent).catch(() => null);
          if (rows > 0 || (k && k.trim() !== '0')) { processed = true; kpi = k; break; }
        }
      }
    }

  console.log(JSON.stringify({ url, httpStatus: status, dropSimulated: result, processed, kpi, consoleMsgsLength: consoleMsgs.length, pageErrorsLength: pageErrors.length, dialogs }, null, 2));

    if (consoleMsgs.length) {
      console.log('\n-- Console messages (first 20) --');
      consoleMsgs.slice(0,20).forEach(m => console.log(m.type + ': ' + m.text));
    }
    if (pageErrors.length) {
      console.log('\n-- Page errors --');
      pageErrors.forEach(e => console.log(e));
    }

    await browser.close();
    process.exit(processed ? 0 : 3);
  } catch (err) {
    console.error('TEST-ERROR', err);
    await browser.close();
    process.exit(2);
  }
})();
