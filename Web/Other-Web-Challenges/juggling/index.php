<?php

$admin_username = "admin";
$admin_password_hash = "0e215962017";

if (isset($_POST['username']) && isset($_POST['password'])) {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];
    $input_password_hash = md5($input_password);

    if ($input_username == $admin_username && $input_password_hash == $admin_password_hash) {
        echo "<h1>Correct! Here's your reward:</h1>";
        echo "<p class='flag'>Flag: FLAG{php_juggle_master}</p>";
    } else {
        echo "<h2>Nope!</h2>";
        echo "<p>Give me the admin password to get the flag.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #1e1e2f, #2a2a4a);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h1 {
            color: #333;
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }
        h2 {
            color: #e63946;
            margin: 0.5rem 0;
        }
        p {
            color: #666;
            margin: 0.5rem 0;
        }
        .flag {
            color: #2ecc71;
            font-weight: bold;
            font-size: 1.2rem;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        label {
            text-align: left;
            color: #333;
            font-weight: bold;
        }
        input[type="text"] {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus {
            border-color: #007bff;
            outline: none;
        }
        input[type="submit"] {
            padding: 0.75rem;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
        }
        input[type="submit"]:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Login</h1>
        <form method="POST" action="">
            <label>Username:</label>
            <input type="text" name="username" required>
            <label>Password:</label>
            <input type="text" name="password" required>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
