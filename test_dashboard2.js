const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch();
  const context = await browser.newContext();
  const page = await context.newPage();

  // Load the dashboard directly without auth if we can mock it, or just visit login and login
  await page.goto('http://localhost:3000/login');
  await page.fill('input[id="email"]', 'pekerja.lapangan@safemine.com');
  await page.fill('input[id="password"]', 'password123');
  await page.click('button[type="submit"]');

  // Wait for navigation
  await page.waitForTimeout(3000); // give it time to load and render the dashboard

  await page.screenshot({ path: 'dashboard_after_login2.png', fullPage: true });

  await browser.close();
})();
