<?php
// pages/manageusers.php

$pageTitle = 'Manage Users';
include_once __DIR__ . '/../logger.php';

$htpasswdFile = __DIR__ . '/../.htpasswd';

// Only declare helpers if they don't already exist
if (!function_exists('load_htpasswd')) {
    function load_htpasswd($file) {
        if (!file_exists($file)) return [];
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $users = [];
        foreach ($lines as $line) {
            list($u, $h) = explode(':', $line, 2);
            $users[trim($u)] = trim($h);
        }
        return $users;
    }
}

if (!function_exists('save_htpasswd')) {
    function save_htpasswd($file, $map) {
        $lines = [];
        foreach ($map as $u => $h) {
            $lines[] = "$u:$h";
        }
        return file_put_contents($file, implode("\n", $lines)) !== false;
    }
}

$alert = '';
try {
    $map = load_htpasswd($htpasswdFile);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action  = $_POST['action']   ?? '';
        $user    = trim($_POST['username'] ?? '');
        $pass    = $_POST['password'] ?? '';
        $confirm = $_POST['confirm']  ?? '';

        switch ($action) {
            case 'add':
                if (!$user || !$pass || !$confirm) throw new Exception('All fields required.');
                if ($pass !== $confirm)         throw new Exception('Passwords do not match.');
                if (isset($map[$user]))         throw new Exception('User exists.');
                $map[$user] = password_hash($pass, PASSWORD_DEFAULT);
                save_htpasswd($htpasswdFile, $map);
                log_activity('Add user', "Username: $user");
                $msg = "User '$user' added.";
                break;

            case 'remove':
                if (!isset($map[$user]))         throw new Exception('User not found.');
                unset($map[$user]);
                save_htpasswd($htpasswdFile, $map);
                log_activity('Remove user', "Username: $user");
                $msg = "User '$user' removed.";
                break;

            case 'edit':
                if (!isset($map[$user]))         throw new Exception('User not found.');
                if (!$pass || !$confirm)         throw new Exception('Both password fields required.');
                if ($pass !== $confirm)         throw new Exception('Passwords do not match.');
                $map[$user] = password_hash($pass, PASSWORD_DEFAULT);
                save_htpasswd($htpasswdFile, $map);
                log_activity('Edit user', "Username: $user");
                $msg = "Password updated for '$user'.";
                break;
        }

        header("Location: index.php?p=manageusers&msg=" . urlencode($msg));
        exit;
    }

    if (isset($_GET['msg'])) {
        $alert = "<div class='alert alert-success'>" . htmlspecialchars(urldecode($_GET['msg'])) . "</div>";
    } elseif (isset($_GET['err'])) {
        $alert = "<div class='alert alert-danger'>" . htmlspecialchars(urldecode($_GET['err'])) . "</div>";
    }

} catch (Exception $e) {
    $alert = "<div class='alert alert-danger'>" . htmlspecialchars($e->getMessage()) . "</div>";
}

$users = array_keys($map);
sort($users);
?>

<div class="container mt-5">
  <h2 class="mb-4"><i class="bi bi-people-fill me-2"></i>User Access Control</h2>
  <?= $alert ?>

  <div class="table-responsive mb-4">
    <table class="table table-hover align-middle shadow-sm bg-white border rounded">
      <thead class="table-light">
        <tr><th>User</th><th class="text-end">Actions</th></tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u): ?>
          <?php if (isset($_GET['edit']) && $_GET['edit'] === $u): ?>
            <tr>
              <td><?= htmlspecialchars($u) ?></td>
              <td>
                <form method="post" class="d-flex justify-content-end gap-2">
                  <input type="hidden" name="action" value="edit">
                  <input type="hidden" name="username" value="<?= htmlspecialchars($u) ?>">
                  <input type="password" name="password" class="form-control form-control-sm w-auto" placeholder="New Password" required>
                  <input type="password" name="confirm" class="form-control form-control-sm w-auto" placeholder="Confirm" required>
                  <button class="btn btn-sm btn-primary"><i class="bi bi-check-lg"></i></button>
                  <a href="index.php?p=manageusers" class="btn btn-sm btn-secondary"><i class="bi bi-x-lg"></i></a>
                </form>
              </td>
            </tr>
          <?php else: ?>
            <tr>
              <td><?= htmlspecialchars($u) ?></td>
              <td class="text-end">
                <a href="?edit=<?= urlencode($u) ?>" class="btn btn-outline-primary btn-sm me-2"><i class="bi bi-pencil-fill"></i></a>
                <form method="post" class="d-inline">
                  <input type="hidden" name="action" value="remove">
                  <input type="hidden" name="username" value="<?= htmlspecialchars($u) ?>">
                  <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Remove user <?= htmlspecialchars($u) ?>?');"><i class="bi bi-trash-fill"></i></button>
                </form>
              </td>
            </tr>
          <?php endif; ?>
        <?php endforeach; ?>
        <?php if (empty($users)): ?>
          <tr><td colspan="2" class="text-center text-muted">No users found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="bg-white border rounded shadow-sm p-4">
    <h4 class="mb-3"><i class="bi bi-person-plus-fill me-2"></i>Add New User</h4>
    <form method="post" class="row g-3">
      <input type="hidden" name="action" value="add">
      <div class="col-md-4">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="confirm" class="form-control" required>
      </div>
      <div class="col-12 text-end">
        <button type="submit" class="btn btn-success px-4"><i class="bi bi-check-circle me-1"></i>Add User</button>
      </div>
    </form>
  </div>
</div>