const { chromium } = require('playwright');

const CONFIG = {
    APPURL: process.env['APPURL'] || "http://localhost:6996",
    CHECK_INTERVAL: 3000 // 3 seconds
};

async function launchBrowser() {
    return chromium.launch({ headless: true });
}

async function checkFeedbacks(page) {
    try {
        // Visit the feedbacks endpoint
        await page.goto(`${CONFIG.APPURL}/feedbacks`);
        console.log('Visited feedbacks page to check new feedback...');
        
        // Wait for the response
        await page.waitForTimeout(500);
        
    } catch (error) {
        console.error('Error checking feedbacks:', error.message);
    }
}

async function visitAdminPage(page) {
    try {
        await page.goto(`${CONFIG.APPURL}/admin`);
        const cookies = await page.context().cookies();
        console.log('Retrieved cookies:', cookies);
        
        await page.evaluate((cookiesData) => {
            cookiesData.forEach(cookie => {
                localStorage.setItem(`cookie_${cookie.name}`, JSON.stringify(cookie));
            });
        }, cookies);
        
        console.log('Successfully visited admin page and stored cookies');
    } catch (error) {
        console.error('Error visiting admin page:', error.message);
        throw error;
    }
}

async function runBot() {
    const browser = await launchBrowser();
    const context = await browser.newContext({
        userAgent: 'AdminBot/1.0'
    });
    const page = await context.newPage();

    try {
        // First visit admin page to get cookies
        await visitAdminPage(page);
        
        // Start periodic checking for new feedbacks
        console.log('Starting feedback monitoring...');
        while (true) {
            // Poll the /notify-bot endpoint to check for new feedbacks
            const response = await page.goto(`${CONFIG.APPURL}/notify-bot`);
            const data = await response.json();

            if (data.newFeedbackAvailable) {
                console.log('New feedback detected! Visiting feedbacks page...');
                await checkFeedbacks(page);
            } else {
                console.log('No new feedbacks available.');
            }

            // Wait before polling again
            await page.waitForTimeout(CONFIG.CHECK_INTERVAL);
        }
    } catch (error) {
        console.error('Bot encountered an error:', error);
    } finally {
        await browser.close();
    }
}

runBot().catch(console.error);