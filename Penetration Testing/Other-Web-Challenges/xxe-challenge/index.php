<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $xmlData = trim($_POST['xmlInput']);

    if (empty($xmlData)) {
        die("<p class='error-message'>❌ Invalid XML document!</p>");
    }

    $dom = new DOMDocument();
    try {
        // Enable entity loading for XXE (CTF purposes only - dangerous in production!)
        $dom->resolveExternals = true;
        $dom->substituteEntities = true;

        if (!$dom->loadXML($xmlData, LIBXML_NOENT)) { // Changed from LIBXML_NONET
            throw new Exception();
        }

        // Convert DOM to SimpleXML
        $vaultData = simplexml_import_dom($dom);
        
        // Fake "Processing" delay
        sleep(2);

        // Output success message with the resolved entity content
        echo "<div class='success-container'>";
        echo "<h2 class='success-heading'>✅ Document Processed Successfully!</h2>";
        echo "<p class='success-text'><strong>Security Log ID:</strong> " . htmlspecialchars($vaultData) . "</p>";
        echo "</div>";
    } catch (Exception $e) {
        die("<p class='error-message'>❌ Invalid XML document!</p>");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureDoc Validator</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #3a86ff;
            --primary-dark: #2667cc;
            --success: #2ecc71;
            --danger: #e74c3c;
            --dark: #1a1a2e;
            --dark-light: #22223b;
            --text: #e0e0e0;
            --text-muted: #aaaaaa;
            --border: #444444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--dark);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: var(--text);
            line-height: 1.6;
        }

        .container {
            background-color: var(--dark-light);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            border: 1px solid var(--border);
        }

        h2 {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 16px;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        p {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-bottom: 15px;
        }

        .upload-box {
            background-color: rgba(255, 255, 255, 0.03);
            padding: 25px;
            border-radius: 8px;
            border: 1px solid var(--border);
            transition: all 0.2s ease;
            margin: 20px 0;
        }

        .upload-box:hover {
            border-color: var(--primary);
        }

        textarea {
            width: 100%;
            height: 180px;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 6px;
            background-color: rgba(0, 0, 0, 0.2);
            color: var(--text);
            font-family: 'Inter', monospace;
            font-size: 14px;
            resize: none;
            transition: all 0.2s ease;
        }

        textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(58, 134, 255, 0.2);
        }

        button {
            background-color: var(--primary);
            color: white;
            padding: 12px 16px;
            border: none;
            border-radius: 6px;
            width: 100%;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        button:hover {
            background-color: var(--primary-dark);
        }

        .loading {
            display: none;
            font-size: 0.95rem;
            color: var(--text);
            margin-top: 20px;
            text-align: center;
        }

        .loading i {
            margin-right: 8px;
            animation: spin 1.2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .hint {
            margin-top: 20px;
            font-size: 0.9rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .hint a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .hint a:hover {
            text-decoration: underline;
        }

        .success-container {
            text-align: center;
            padding: 25px;
            background-color: var(--dark-light);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border: 1px solid var(--border);
        }

        .success-heading {
            color: var(--success);
            margin-bottom: 15px;
        }

        .success-text {
            color: var(--text);
            font-size: 0.95rem;
        }

        .error-message {
            text-align: center;
            color: var(--danger);
            font-size: 1rem;
            font-weight: 500;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-shield-alt"></i> SecureDoc XML Validator</h2>
        <p>Validate your XML documents with enterprise-grade security</p>

        <div class="upload-box">
            <form method="POST" action="" id="docForm">
                <textarea name="xmlInput" placeholder="Paste your XML document here..."></textarea>
                <button type="submit"><i class="fas fa-check-circle"></i> Validate Document</button>
            </form>
        </div>

        <div class="loading">
            <i class="fas fa-spinner fa-spin"></i> Processing your document...
        </div>

        <p class="hint"><i class="fas fa-lock"></i> Admin-only <a href="/admin">reports</a> available</p>
    </div>

    <script>
        document.getElementById('docForm').addEventListener('submit', function() {
            document.querySelector('.loading').style.display = 'block';
        });
        
        const textarea = document.querySelector('textarea');
        textarea.addEventListener('focus', function() {
            document.querySelector('.upload-box').style.borderColor = 'var(--primary)';
        });
        
        textarea.addEventListener('blur', function() {
            document.querySelector('.upload-box').style.borderColor = 'var(--border)';
        });
    </script>
</body>
</html>

