<?php
// login_module.php

// Start session if not already started.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Process login form if submitted.
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Hard-coded credentials (for demonstration only).
        $valid_username = 'admin';
        $valid_password = 'password123';

        if ($username === $valid_username && $password === $valid_password) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login Required</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .login-form { max-width: 400px; margin: 0 auto; }
            .login-form label { display: block; margin-top: 10px; }
            .login-form input { width: 100%; padding: 8px; margin-top: 5px; }
            .login-form input[type="submit"] { margin-top: 15px; }
            .error { color: red; }
        </style>
    </head>
    <body>
        <h1>Login Required</h1>
        <?php if (isset($error)) { echo '<p class="error">' . htmlspecialchars($error) . '</p>'; } ?>
        <div class="login-form">
            <form method="post" action="">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <input type="submit" value="Login">
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}
?>
