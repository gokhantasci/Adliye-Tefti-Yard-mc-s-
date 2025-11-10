import puppeteer from 'puppeteer';

(async () => {
  const url = process.env.TEST_URL || 'http://localhost:8080/karar';
  const browser = await puppeteer.launch({ args: ['--no-sandbox', '--disable-setuid-sandbox'] });
  const page = await browser.newPage();

  const consoleMsgs = [];
  page.on('console', msg => {
    consoleMsgs.push({ type: msg.type(), text: msg.text() });
  });
  const pageErrors = [];
  page.on('pageerror', err => pageErrors.push(String(err)));

  try {
    const resp = await page.goto(url, { waitUntil: 'networkidle2', timeout: 30000 });
    const status = resp ? resp.status() : null;

  // Wait a short time to allow xlsx-loader to run (if present)
  await new Promise(r => setTimeout(r, 1000));

    const result = await page.evaluate(() => {
      const drop = !!document.querySelector('#dropZone');
      const xlsx = !!window.XLSX;
      return { hasDropZone: drop, hasXLSX: xlsx, title: document.title };
    });

    console.log(JSON.stringify({ url, httpStatus: status, ...result, consoleMsgsLength: consoleMsgs.length, pageErrorsLength: pageErrors.length }, null, 2));

    if (consoleMsgs.length) {
      console.log('\n-- Console messages (first 20) --');
      consoleMsgs.slice(0,20).forEach(m => console.log(m.type + ': ' + m.text));
    }
    if (pageErrors.length) {
      console.log('\n-- Page errors --');
      pageErrors.forEach(e => console.log(e));
    }

    await browser.close();
    process.exit(0);
  } catch (err) {
    console.error('SMOKE-ERROR', err);
    await browser.close();
    process.exit(2);
  }
})();
