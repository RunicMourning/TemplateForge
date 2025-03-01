<?php
// admin/create_page.php

require_once 'login_module.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and trim input values.
    $filename        = trim($_POST['filename']);
    $title           = trim($_POST['title']);
    $headerIncludes  = trim($_POST['header_includes']);
    $footerIncludes  = trim($_POST['footer_includes']);
    $content         = $_POST['content']; // Main content may include HTML and PHP.

    // Basic validation.
    if ($filename === '' || $title === '' || $content === '') {
        $error = "Filename, title, and main content are required.";
    } else {
        // Validate filename: allow only letters, numbers, dashes, and underscores.
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $filename)) {
            $error = "Filename can only contain letters, numbers, dashes, and underscores.";
        } else {
            // Define file path safely without realpath().
            $filePath = __DIR__ . "/../pages/{$filename}.php";

            // Check if file already exists to prevent overwriting.
            if (file_exists($filePath)) {
                $error = "A page with this filename already exists.";
            } else {
                // Process header includes.
                $headerArray = array_filter(array_map('trim', preg_split("/\r\n|\n|\r/", $headerIncludes)));
                $footerArray = array_filter(array_map('trim', preg_split("/\r\n|\n|\r/", $footerIncludes)));

                // Build PHP code for headerIncludes.
                $headerCode = '';
                foreach ($headerArray as $line) {
                    $headerCode .= '$headerIncludes[] = ' . var_export($line, true) . ";\n";
                }

                // Build PHP code for footerIncludes.
                $footerCode = '';
                foreach ($footerArray as $line) {
                    $footerCode .= '$footerIncludes[] = ' . var_export($line, true) . ";\n";
                }

                // Build the complete PHP file content.
                $phpContent = "<?php\n" .
                              "// pages/{$filename}.php\n\n" .
                              "\$pageTitle = " . var_export($title, true) . ";\n" .
                              ($headerCode ? $headerCode . "\n" : "") .
                              ($footerCode ? $footerCode . "\n" : "") .
                              "?>\n" .
                              $content;

                // Attempt to write file
                if (file_put_contents($filePath, $phpContent) !== false) {
                    $success = "Page created successfully.";
                } else {
                    $error = "Error creating page. Check directory permissions.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create New Page</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    label { display: block; margin-top: 10px; font-weight: bold; }
    input[type="text"], textarea { width: 100%; padding: 8px; margin-top: 5px; }
    textarea { height: 100px; }
    .message { margin-top: 15px; padding: 10px; }
    .error { background: #fdd; color: #900; }
    .success { background: #dfd; color: #060; }
  </style>
</head>
<body>
  <h1>Create New Page</h1>
  <p><a href="pages.php">Back to Manage Pages</a></p>
  
  <?php if (isset($error)): ?>
    <div class="message error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>
  <?php if (isset($success)): ?>
    <div class="message success"><?php echo htmlspecialchars($success); ?></div>
  <?php endif; ?>
  
  <form method="post" action="">
    <label>Filename (without .php):</label>
    <input type="text" name="filename" required>
    
    <label>Page Title:</label>
    <input type="text" name="title" required>
    
    <label>Header Includes (one per line):</label>
    <textarea name="header_includes" placeholder="E.g., &lt;link rel=&quot;stylesheet&quot; href=&quot;css/custom.css&quot;&gt;"></textarea>
    
    <label>Footer Includes (one per line):</label>
    <textarea name="footer_includes" placeholder="E.g., &lt;script src=&quot;js/custom.js&quot;&gt;&lt;/script&gt;"></textarea>
    
    <label>Main Content (HTML/PHP):</label>
    <textarea name="content" rows="10" required></textarea>
    
    <br>
    <input type="submit" value="Create Page">
  </form>
</body>
</html>
