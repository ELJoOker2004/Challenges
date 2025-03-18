<?php
$conn = new mysqli(getenv('MYSQL_HOST'), 'ctf_user', 'ctf_pass', 'vault_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    // Directly inject user input into the query (deliberately vulnerable)
    $query = "SELECT * FROM users WHERE username='$user' AND password='$pass'";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $message = "<div class='alert alert-success'><h4>Welcome, {$row['username']}!</h4>Your secret: <strong>" . htmlspecialchars($row['password']) . "</strong></div>";
    } else {
        $message = "<div class='alert alert-danger'>Invalid credentials!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret Vault Login</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #fff;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-box {
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        .login-box h2 {
            margin-bottom: 1.5rem;
            font-weight: bold;
        }
        .form-control {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: #fff;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        .btn-primary {
            background: #007bff;
            border: none;
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .alert {
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2 class="text-center">Secret Vault Login</h2>
        <?php if (isset($message)) echo $message; ?>
        <form method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <!-- can you be admin !!! -->
        <div class="text-center mt-3">
            <small class="text-muted">please help me to be admin!!!!!!!!!!!!!!!!!!</small>
        </div>
    </div>

    <!-- Bootstrap 5 JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
