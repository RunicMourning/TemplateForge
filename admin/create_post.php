<?php
// admin/create_post.php

require_once 'login_module.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filename = trim($_POST['filename']);
    $title    = trim($_POST['title']);
    $date     = trim($_POST['date']);  // Expected format: YYYY-MM-DD
    $content  = $_POST['content'];

    if ($filename === '' || $title === '' || $date === '' || $content === '') {
        $error = "All fields are required.";
    } else {
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $filename)) {
            $error = "Filename can only contain letters, numbers, dashes, and underscores.";
        } else {
            // Combine the selected date with the current time.
            $currentTime = date('H:i:s');
            $postTimestamp = $date . ' ' . $currentTime;
            
            $phpContent = "<?php\n" .
                          "// blog_posts/{$filename}.php\n\n" .
                          "\$postTitle = " . var_export($title, true) . ";\n" .
                          "\$postTimestamp = " . var_export($postTimestamp, true) . ";\n" .
                          "?>\n" .
                          $content;
                          
            $filePath = realpath(__DIR__ . '/../blog_posts') . "/{$filename}.php";
            
            if (file_put_contents($filePath, $phpContent) !== false) {
                $success = "Post created successfully.";
            } else {
                $error = "Error creating post.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create New Blog Post</title>
</head>
<body>
  <h1>Create New Blog Post</h1>
  <p><a href="posts.php">Back to Manage Posts</a></p>
  <?php if (isset($error)): ?>
    <div style="color:red;"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>
  <?php if (isset($success)): ?>
    <div style="color:green;"><?php echo htmlspecialchars($success); ?></div>
  <?php endif; ?>
  <form method="post" action="">
    <label>Filename (without .php):</label><br>
    <input type="text" name="filename" required><br><br>
    
    <label>Post Title:</label><br>
    <input type="text" name="title" required><br><br>
    
    <label>Post Date:</label><br>
    <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" required><br><br>
    
    <label>Post Content (HTML/PHP):</label><br>
    <textarea name="content" rows="10" cols="60" required></textarea><br><br>
    
    <input type="submit" value="Create Post">
  </form>
</body>
</html>
