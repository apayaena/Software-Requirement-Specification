from playwright.sync_api import sync_playwright
import time

def verify(page):
    try:
        page.goto("http://localhost:3000/")
        time.sleep(2)
        page.screenshot(path="dashboard_before.png", full_page=True)
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()
        try:
            verify(page)
        finally:
            browser.close()
