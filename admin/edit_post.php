<?php
// admin/edit_post.php

require_once 'login_module.php';
if (!isset($_GET['post'])) {
    die("No post specified.");
}
$filename = basename($_GET['post']);
$filePath = realpath(__DIR__ . '/../blog_posts') . "/$filename";
if (!file_exists($filePath)) {
    die("Post not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $timestamp = trim($_POST['timestamp']);
    $content = $_POST['content'];
    
    if ($title == '' || $timestamp == '' || $content == '') {
        $error = "All fields are required.";
    } else {
        $phpContent = "<?php\n" .
                      "// blog_posts/{$filename}\n\n" .
                      "\$postTitle = " . var_export($title, true) . ";\n" .
                      "\$postTimestamp = " . var_export($timestamp, true) . ";\n" .
                      "?>\n" .
                      $content;
        if (file_put_contents($filePath, $phpContent) !== false) {
            $success = "Post updated successfully.";
        } else {
            $error = "Error updating post.";
        }
    }
} else {
    $existingContent = file_get_contents($filePath);
    $title = '';
    $timestamp = '';
    if (preg_match('/\$postTitle\s*=\s*(["\'])(.*?)\1\s*;/', $existingContent, $matches)) {
        $title = $matches[2];
    }
    if (preg_match('/\$postTimestamp\s*=\s*(["\'])(.*?)\1\s*;/', $existingContent, $matches)) {
        $timestamp = $matches[2];
    }
    $content = preg_replace('/^<\?php.*?\?>\s*/s', '', $existingContent);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Blog Post</title>
</head>
<body>
  <h1>Edit Blog Post: <?php echo htmlspecialchars($filename); ?></h1>
  <p><a href="posts.php">Back to Manage Posts</a></p>
  <?php if (isset($error)): ?>
    <div style="color:red;"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>
  <?php if (isset($success)): ?>
    <div style="color:green;"><?php echo htmlspecialchars($success); ?></div>
  <?php endif; ?>
  <form method="post" action="">
    <label>Post Title:</label><br>
    <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required><br><br>
    
    <label>Timestamp (YYYY-MM-DD HH:MM:SS):</label><br>
    <input type="date" name="timestamp" value="<?php echo htmlspecialchars($timestamp); ?>" required><br><br>
    
    <label>Post Content (HTML/PHP):</label><br>
    <textarea name="content" rows="10" cols="60" required><?php echo htmlspecialchars($content); ?></textarea><br><br>
    
    <input type="submit" value="Update Post">
  </form>
</body>
</html>
