const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch();
  const context = await browser.newContext();
  const page = await context.newPage();

  // Login as worker to view the dashboard
  await page.goto('http://localhost:3000/login');
  await page.fill('input[name="email"]', 'pekerja.lapangan@safemine.com');
  await page.fill('input[name="password"]', 'password123');
  await page.click('button[type="submit"]');

  // Wait for navigation
  await page.waitForTimeout(3000); // give it time to load and render the dashboard

  await page.screenshot({ path: 'dashboard_after_login.png', fullPage: true });

  await browser.close();
})();
