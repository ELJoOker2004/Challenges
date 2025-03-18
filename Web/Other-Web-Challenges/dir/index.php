<?php

function checkPath($path) {
    if (strpos($path, 'Admin') !== false) {
        return false; // Deny access if Latin 'Admin' is found
    }
    return true; // Allow access otherwise
}

$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$path = trim($path, '/'); // Remove leading/trailing slashes
$path = urldecode($path); // Decode URL-encoded characters

if ($path === 'Аdmin') { // Cyrillic 'А' (U+0410)
    $message = 'CATF{Y0u_G0t_Y0U76r_w8y_again!!!!!}';
    $style = 'color: green;';
    $status = 200;
} elseif (checkPath($path)) {
    $message = 'HEllo again !!!!';
    $style = 'color: red;';
    $status = 404;
} else {
    $message = 'Access Denied.';
    $style = 'color: red;';
    $status = 403;
}

// Set HTTP status code
http_response_code($status);

// Output the styled HTML page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureVault</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #0f0f2a, #1a1a3d);
            color: #ff00ff;
            text-align: center;
            padding: 60px;
            margin: 0;
            overflow-x: hidden;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: rgba(15, 15, 42, 0.9);
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0px 0px 30px rgba(255, 0, 255, 0.4);
            border: 2px solid #ff00ff;
            position: relative;
            animation: pulse 3s infinite ease-in-out;
        }
        @keyframes pulse {
            0% { box-shadow: 0px 0px 30px rgba(255, 0, 255, 0.4); }
            50% { box-shadow: 0px 0px 40px rgba(255, 0, 255, 0.6); }
            100% { box-shadow: 0px 0px 30px rgba(255, 0, 255, 0.4); }
        }
        h1 {
            color: #00ffff;
            font-size: 3em;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0px 0px 20px rgba(0, 255, 255, 0.7);
            margin-bottom: 30px;
        }
        .message {
            font-size: 2em;
            font-weight: bold;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔒 SecureVault</h1>
        <p>Welcome to the vault. Can you find the hidden flag?</p>
        <div class="message" style="<?php echo $style; ?>">
            <?php echo $message; ?>
        </div>
    </div>
</body>
</html>
