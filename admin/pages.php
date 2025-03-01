<?php
// admin/pages.php

require_once 'login_module.php';

$pagesDir = realpath(__DIR__ . '/../pages');
$pages = glob($pagesDir . '/*.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Pages</title>
</head>
<body>
  <h1>Manage Pages</h1>
  <p>
    <a href="index.php">Admin Dashboard</a> | 
    <a href="create_page.php">Create New Page</a>
  </p>
  <table border="1" cellpadding="5">
    <thead>
      <tr>
        <th>Filename</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($pages as $pagePath): 
              $filename = basename($pagePath);
      ?>
        <tr>
          <td><?php echo htmlspecialchars($filename); ?></td>
          <td>
            <a href="edit_page.php?page=<?php echo urlencode($filename); ?>">Edit</a> | 
            <a href="delete_page.php?page=<?php echo urlencode($filename); ?>" onclick="return confirm('Are you sure you want to delete this page?');">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
