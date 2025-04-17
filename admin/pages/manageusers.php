<?php
// pages/manageusers.php

$pageTitle = 'Manage Users';
include_once __DIR__.'/../logger.php';

$htpasswdFile = __DIR__ . '/../.htpasswd';
$alert = '';


// Check if the function exists before declaring it.
if (!function_exists('load_htpasswd')) {
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
}

if (!function_exists('save_htpasswd')) {
    function save_htpasswd($file, $users) {
        $lines = [];
        foreach ($users as $user => $hash) {
            $lines[] = "{$user}:{$hash}";
        }
        return file_put_contents($file, implode("\n", $lines)) !== false;
    }
}

try {
    $map = load_htpasswd($htpasswdFile);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        switch ($action) {
            case 'add':
                $username = trim($_POST['username'] ?? '');
                $password = $_POST['password'] ?? '';
                $confirm  = $_POST['confirm'] ?? '';

                if ($username === '' || $password === '' || $confirm === '') {
                    throw new Exception('All fields are required.');
                }
                if ($password !== $confirm) {
                    throw new Exception('Passwords do not match.');
                }
                if (isset($map[$username])) {
                    throw new Exception('User already exists.');
                }

                $map[$username] = password_hash($password, PASSWORD_DEFAULT);
                if (!save_htpasswd($htpasswdFile, $map)) {
                    throw new Exception('Failed to save user.');
                }
                $message = "User '{$username}' added successfully.";
                log_activity('Add user', "Username: {$username}");
                break;

            case 'remove':
                $username = $_POST['username'] ?? '';
                if (!isset($map[$username])) {
                    throw new Exception('User not found.');
                }
                unset($map[$username]);
                if (!save_htpasswd($htpasswdFile, $map)) {
                    throw new Exception('Failed to remove user.');
                }
                $message = "User '{$username}' removed.";
                log_activity('Remove user', "Username: {$username}");
                break;

            case 'edit':
                $username = trim($_POST['username'] ?? '');
                $password = $_POST['password'] ?? '';
                $confirm  = $_POST['confirm']  ?? '';

                if (!isset($map[$username])) {
                    throw new Exception('User not found.');
                }
                if ($password === '' || $confirm === '') {
                    throw new Exception('Both password fields are required.');
                }
                if ($password !== $confirm) {
                    throw new Exception('Passwords do not match.');
                }

                $map[$username] = password_hash($password, PASSWORD_DEFAULT);
                if (!save_htpasswd($htpasswdFile, $map)) {
                    throw new Exception('Failed to update user password.');
                }
                $message = "Password updated for '{$username}'.";
                log_activity('Edit user', "Username: {$username}");
                break;
        }

        header("Location: index.php?p=manageusers&msg=" . urlencode($message));
        exit;
    }

    if (isset($_GET['msg'])) {
        $alert = '<div class="alert alert-success">' . htmlspecialchars(urldecode($_GET['msg'])) . '</div>';
    } elseif (isset($_GET['err'])) {
        $alert = '<div class="alert alert-danger">' . htmlspecialchars(urldecode($_GET['err'])) . '</div>';
    }

} catch (Exception $e) {
    $alert = '<div class="alert alert-danger">' . htmlspecialchars($e->getMessage()) . '</div>';
}

$users = array_keys($map);
sort($users);
?>

 <div class="container py-4">
    <h1 class="mb-4 display-5 fw-semibold text-primary">User Access Control</h1>
    <?= $alert ?>


  <div class="card shadow-sm mt-5">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-people-fill me-2"></i>Current Users</h4>
        </div>
        <ul class="list-group list-group-flush">
            <?php foreach ($users as $u): ?>
                <li class="list-group-item">
                    <?php if (isset($_GET['edit']) && $_GET['edit'] === $u): ?>
                        <form method="post" class="row g-2 align-items-center">
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="username" value="<?= htmlspecialchars($u) ?>">
                            <div class="col-md-3 fw-bold"><?= htmlspecialchars($u) ?></div>
                            <div class="col-md-3">
                                <input type="password" name="password" class="form-control" placeholder="New Password" required>
                            </div>
                            <div class="col-md-3">
                                <input type="password" name="confirm" class="form-control" placeholder="Confirm Password" required>
                            </div>
                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                <a href="index.php?p=manageusers" class="btn btn-sm btn-secondary">Cancel</a>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold text-dark"><?= htmlspecialchars($u) ?></span>
                            <div class="btn-group btn-group-sm">
                                <a href="?edit=<?= urlencode($u) ?>" class="btn btn-outline-primary"><i class="bi bi-pencil-fill"></i> Edit</a>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="username" value="<?= htmlspecialchars($u) ?>">
                                    <button type="submit" class="btn btn-outline-danger"
                                            onclick="return confirm('Remove user <?= htmlspecialchars($u) ?>?');">
                                        <i class="bi bi-trash-fill"></i> Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="card mt-5 shadow-sm">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0"><i class="bi bi-person-plus-fill me-2"></i>Add New User</h4>
        </div>
        <div class="card-body">
            <form method="post" class="row g-3">
                <input type="hidden" name="action" value="add">
                <div class="col-md-4">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirm" class="form-control" placeholder="Confirm password" required>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success px-4"><i class="bi bi-check-circle me-1"></i>Add User</button>
                </div>
            </form>
        </div>
    </div>

  
</div>
