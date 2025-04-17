<?php
// login_module.php

include __DIR__.'/logger.php'; // Adjust path as needed

// Start session if not already started.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Path to the .htpasswd file
$htpasswdFile = __DIR__ . '/.htpasswd';

// Function to load users from .htpasswd file
function load_htpasswd($file) {
    if (!file_exists($file)) return [];
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $users = [];
    foreach ($lines as $line) {
        [$user, $hash] = explode(':', $line, 2);
        $users[trim($user)] = trim($hash);
    }
    return $users;
}

// Check if user is logged in.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Process login form if submitted.
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Load users from .htpasswd
        $users = load_htpasswd($htpasswdFile);

        if (isset($users[$username]) && password_verify($password, $users[$username])) {
            // If username exists and password matches the hash, log the user in
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;

            log_activity('Admin login', 'Username: ' . $username);

            // Redirect to the same page to prevent form resubmission
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        } else {
            // Invalid username or password
            $error = "Invalid username or password.";
            log_activity('Failed login', 'Username: ' . $username);
        }
    }

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Required</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                font-family: Arial, sans-serif;
                padding-top: 50px; /* Add padding to top to center the form */
            }
            .error {
                color: red;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <h2 class="text-center">Login Required</h2>
                    <?php if (isset($error)) { echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>'; } ?>
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" id="username" name="username" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" id="password" name="password" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS and dependencies -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    exit;
}
?>
