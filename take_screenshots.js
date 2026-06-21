import puppeteer from 'puppeteer';

(async () => {
  console.log("Launching browser...");
  let browser;
  try {
    browser = await puppeteer.launch({
      executablePath: 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
      headless: true,
      args: ['--no-sandbox', '--disable-setuid-sandbox']
    });
  } catch (e) {
    console.log("Chrome not found, trying Edge...");
    try {
      browser = await puppeteer.launch({
        executablePath: 'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
        headless: true,
        args: ['--no-sandbox', '--disable-setuid-sandbox']
      });
    } catch (edgeError) {
      console.log("Edge not found, trying standard puppeteer launch (downloading may be required)...");
      browser = await puppeteer.launch({
        headless: true,
        args: ['--no-sandbox', '--disable-setuid-sandbox']
      });
    }
  }
  
  const page = await browser.newPage();
  
  // Set viewport to standard desktop resolution
  await page.setViewport({ width: 1280, height: 800 });

  // 1. Capture Login Page before authenticating
  console.log("Navigating to login page...");
  await page.goto('http://localhost:8000/login', { waitUntil: 'networkidle2' });
  await page.screenshot({ path: 'public/screenshots/login_screenshot.png' });
  console.log("Saved login_screenshot.png");

  // 2. Perform authentication
  console.log("Logging in...");
  await page.type('input#email', 'omarfaruuuk@gmail.com');
  await page.type('input#password', 'password123');
  
  await Promise.all([
    page.click('button[type="submit"]'),
    page.waitForNavigation({ waitUntil: 'networkidle2' })
  ]);

  console.log("Login successful! Navigating to Home...");
  await page.goto('http://localhost:8000/home', { waitUntil: 'networkidle2' });
  await page.screenshot({ path: 'public/screenshots/home_screenshot.png' });
  console.log("Saved home_screenshot.png");

  console.log("Navigating to Tasbih Counter...");
  await page.goto('http://localhost:8000/tasbih', { waitUntil: 'networkidle2' });
  await new Promise(r => setTimeout(r, 1000));
  await page.screenshot({ path: 'public/screenshots/tasbih_screenshot.png' });
  console.log("Saved tasbih_screenshot.png");

  console.log("Navigating to Records Dashboard...");
  await page.goto('http://localhost:8000/records', { waitUntil: 'networkidle2' });
  await new Promise(r => setTimeout(r, 2000));
  await page.screenshot({ path: 'public/screenshots/records_screenshot.png' });
  console.log("Saved records_screenshot.png");

  console.log("Navigating to Settings Panel...");
  await page.goto('http://localhost:8000/settings', { waitUntil: 'networkidle2' });
  await new Promise(r => setTimeout(r, 1000));
  await page.screenshot({ path: 'public/screenshots/settings_screenshot.png' });
  console.log("Saved settings_screenshot.png");

  console.log("Navigating to Admin Control Center...");
  await page.goto('http://localhost:8000/admin/dashboard', { waitUntil: 'networkidle2' });
  await new Promise(r => setTimeout(r, 2500)); // wait extra for charts
  await page.screenshot({ path: 'public/screenshots/admin_screenshot.png' });
  console.log("Saved admin_screenshot.png");

  await browser.close();
  console.log("All screenshots captured successfully!");
})();
