const puppeteer = require('puppeteer');

(async () => {
    const browser = await puppeteer.launch({ headless: "new" });
    const page = await browser.newPage();
    await page.setViewport({ width: 1200, height: 800 });
    await page.goto('http://localhost:8000/quiz/start/1', { waitUntil: 'networkidle0' });
    
    const rects = await page.evaluate(() => {
        const els = {
            'body': document.body,
            '.main-content': document.querySelector('.main-content'),
            '.page-content': document.querySelector('.page-content'),
            '.max-w-4xl': document.querySelector('.max-w-4xl'),
            'red-box': document.querySelector('.bg-gradient-to-br')
        };
        
        const result = {};
        for (const [key, el] of Object.entries(els)) {
            if (el) {
                const rect = el.getBoundingClientRect();
                result[key] = {
                    left: rect.left,
                    right: rect.right,
                    width: rect.width,
                    scrollWidth: el.scrollWidth
                };
            }
        }
        
        result['window'] = {
            innerWidth: window.innerWidth,
            scrollX: window.scrollX
        };
        
        return result;
    });
    
    console.log(JSON.stringify(rects, null, 2));
    await browser.close();
})();
