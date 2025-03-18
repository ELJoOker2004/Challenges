const express = require('express');
const path = require('path');
const app = express();
const port = 6996;

// Set EJS as the templating engine
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views')); // Set the views directory

// Add body parser middleware
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Array to store feedbacks
let feedbacks = [];

// Serve static files from the "public" directory
app.use(express.static(path.join(__dirname, 'public')));

let newFeedbackAvailable = false;

// Endpoint to notify the bot
app.get('/notify-bot', (req, res) => {
    const clientIp = req.ip;

    // Check if request is from localhost (admin bot)
    if (clientIp === '127.0.0.1' || clientIp === '::1' || clientIp === 'localhost') {
        res.json({ newFeedbackAvailable });
        newFeedbackAvailable = false; // Reset the flag after notifying the bot
    } else {
        res.status(403).json({ error: 'Access denied. Only admin bot can access this endpoint.' });
    }
});

// Endpoint to submit feedback
app.post('/feedback', (req, res) => {
    const { feedback } = req.body;
    if (!feedback) {
        return res.status(400).json({ error: 'Feedback is required' });
    }

    // Clear all previous feedbacks
    feedbacks = [];

    // Add the new feedback
    feedbacks.push({
        id: feedbacks.length + 1,
        feedback: feedback,
        timestamp: new Date()
    });

    // Set the flag to notify the bot
    newFeedbackAvailable = true;

    res.json({ message: 'Feedback submitted successfully' });
});

// Serve feedbacks.html for GET requests to /feedbacks
app.get('/feedbacks', (req, res) => {
    const clientIp = req.ip;
    console.log('the bot has visited');

    // Check if request is from localhost (admin bot)
    if (clientIp === '127.0.0.1' || clientIp === '::1' || clientIp === 'localhost') {
        // Render the feedbacks.ejs template and pass the feedbacks array
        res.render('feedbacks', { feedbacks });
    } else {
        res.status(403).json({ error: 'Access denied. Only admin can view feedbacks.' });
    }
});

// Endpoint to fetch the flag (only accessible from localhost)
app.get('/admin', (req, res) => {
    const clientIp = req.ip;

    // Check if request is from localhost
    if (clientIp === '127.0.0.1' || clientIp === '::1' || clientIp === 'localhost') {
        // Set the flag as a cookie
        res.cookie('flag', 'CATCTF{XSS_IS_STILL_DANGEROUS}', {
            httpOnly: false,  // Makes cookie inaccessible to JavaScript
            secure: false,    // Only sent over HTTPS
            maxAge: 3600000 * 8  // Cookie expires in 8 hours
        });
        res.json({ message: 'Flag has been set in cookies' });
    } else {
        res.status(403).json({ error: 'Access denied. Only localhost requests allowed.' });
    }
});

// Start the server
app.listen(port, async () => {
    console.log(`Server running at http://localhost:${port}`);

    // Start the admin bot
    try {
        const { spawn } = require('child_process');
        const adminBot = spawn('node', ['adminbot.js'], {
            stdio: 'inherit'
        });

        adminBot.on('error', (error) => {
            console.error('Failed to start admin bot:', error);
        });

        process.on('exit', () => {
            adminBot.kill();
        });

    } catch (error) {
        console.error('Error starting admin bot:', error);
    }
});