<?php
// admin/posts.php

require_once 'login_module.php';

$postsDir = realpath(__DIR__ . '/../blog_posts');
$posts = glob($postsDir . '/*.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Blog Posts</title>
</head>
<body>
  <h1>Manage Blog Posts</h1>
  <p>
    <a href="index.php">Admin Dashboard</a> | 
    <a href="create_post.php">Create New Post</a>
  </p>
  <table border="1" cellpadding="5">
    <thead>
      <tr>
        <th>Filename</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($posts as $postPath): 
              $filename = basename($postPath);
      ?>
        <tr>
          <td><?php echo htmlspecialchars($filename); ?></td>
          <td>
            <a href="edit_post.php?post=<?php echo urlencode($filename); ?>">Edit</a> | 
            <a href="delete_post.php?post=<?php echo urlencode($filename); ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
