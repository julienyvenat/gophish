from playwright.sync_api import sync_playwright, expect

def run():
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()

        # Capture console logs
        page.on("console", lambda msg: print(f"Browser Console: {msg.text}"))
        page.on("pageerror", lambda err: print(f"Browser Error: {err}"))

        try:
            print("Navigating to http://localhost:8000")
            page.goto("http://localhost:8000")

            # Wait a bit
            page.wait_for_timeout(2000)

            expect(page.get_by_role("heading", name="Campaigns")).to_be_visible()

            page.screenshot(path="verification/dashboard.png")
            print("Success.")
        except Exception as e:
            print(f"Error: {e}")
            page.screenshot(path="verification/dashboard_error.png")
        finally:
            browser.close()

if __name__ == "__main__":
    run()
