const express = require('express');
const path = require('path');
const app = express();
const port = 10100;

// Serve static files from the "public" directory
app.use(express.static(path.join(__dirname, 'public')));

// Endpoint to fetch the flag (only accessible after XSS exploit)
app.get('/getFlag', (req, res) => {
    res.json({ flag: 'CATCTF{XSS_1s_Fun_4nd_3asy}' });
});

// Start the server
app.listen(port, () => {
    console.log(`Server running at http://localhost:${port}`);
});