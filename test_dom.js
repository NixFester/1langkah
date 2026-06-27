const { chromium } = require('playwright');
(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage();
  await page.goto('http://127.0.0.1:8000');
  const btn = await page.$('a[href="http://127.0.0.1:8000/signup"]');
  const styles = await page.evaluate(el => {
    const s = window.getComputedStyle(el);
    return {
      width: s.width,
      height: s.height,
      padding: s.padding,
      fontSize: s.fontSize,
      lineHeight: s.lineHeight,
      display: s.display
    };
  }, btn);
  console.log('Button styles:', styles);
  
  const htmlStyles = await page.evaluate(() => {
    const s = window.getComputedStyle(document.documentElement);
    return { fontSize: s.fontSize };
  });
  console.log('HTML font-size:', htmlStyles);
  
  await browser.close();
})();
