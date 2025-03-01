<?php
// admin/edit_page.php

require_once 'login_module.php';

if (!isset($_GET['page'])) {
    die("No page specified.");
}

$filename = basename($_GET['page']);
$filePath = realpath(__DIR__ . '/../pages') . "/$filename";

// Security check: Ensure file is within the pages directory
if (!file_exists($filePath) || strpos($filePath, realpath(__DIR__ . '/../pages')) !== 0) {
    die("Invalid or non-existent page.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $headerIncludes = trim($_POST['header_includes']);
    $footerIncludes = trim($_POST['footer_includes']);
    $content = $_POST['content'];

    if ($title == '' || $content == '') {
        $error = "Title and main content are required.";
    } else {
        // Process header includes
        $headerArray = array_filter(array_map('trim', preg_split("/\r\n|\n|\r/", $headerIncludes)));
        $footerArray = array_filter(array_map('trim', preg_split("/\r\n|\n|\r/", $footerIncludes)));

        $headerCode = '';
        foreach ($headerArray as $line) {
            $headerCode .= '$headerIncludes[] = ' . var_export($line, true) . ";\n";
        }

        $footerCode = '';
        foreach ($footerArray as $line) {
            $footerCode .= '$footerIncludes[] = ' . var_export($line, true) . ";\n";
        }

        // Build and save the PHP file
        $phpContent = "<?php\n" .
                      "// pages/{$filename}\n\n" .
                      "\$pageTitle = " . var_export($title, true) . ";\n" .
                      $headerCode .
                      $footerCode .
                      "?>\n" .
                      $content;

        if (file_put_contents($filePath, $phpContent) !== false) {
            $success = "Page updated successfully.";
        } else {
            $error = "Error updating page.";
        }
    }
} else {
    $existingContent = file_get_contents($filePath);
    $title = '';
    $headerIncludesText = '';
    $footerIncludesText = '';

    // Extract page title
    if (preg_match('/\$pageTitle\s*=\s*(["\'])(.*?)\1\s*;/', $existingContent, $matches)) {
        $title = $matches[2];
    }

    // Extract headerIncludes[] lines
    preg_match_all('/\$headerIncludes\[\]\s*=\s*(["\'])(.*?)\1\s*;/', $existingContent, $headerMatches);
    $headerIncludesText = implode("\n", $headerMatches[2] ?? []);

    // Extract footerIncludes[] lines
    preg_match_all('/\$footerIncludes\[\]\s*=\s*(["\'])(.*?)\1\s*;/', $existingContent, $footerMatches);
    $footerIncludesText = implode("\n", $footerMatches[2] ?? []);

    // Extract content while keeping any custom PHP intact
    $content = preg_replace('/<\?php.*?\$pageTitle\s*=.*?\?>\s*/s', '', $existingContent);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Page</title>
</head>
<body>
  <h1>Edit Page: <?php echo htmlspecialchars($filename); ?></h1>
  <p><a href="pages.php">Back to Manage Pages</a></p>

  <?php if (isset($error)): ?>
    <div style="color:red;"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>
  <?php if (isset($success)): ?>
    <div style="color:green;"><?php echo htmlspecialchars($success); ?></div>
  <?php endif; ?>

  <form method="post" action="">
    <label>Page Title:</label><br>
    <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required><br><br>
    
    <label>Header Includes (one per line):</label><br>
    <textarea name="header_includes" rows="5"><?php echo htmlspecialchars($headerIncludesText); ?></textarea><br><br>
    
    <label>Footer Includes (one per line):</label><br>
    <textarea name="footer_includes" rows="5"><?php echo htmlspecialchars($footerIncludesText); ?></textarea><br><br>
    
    <label>Main Content (HTML/PHP):</label><br>
    <textarea name="content" rows="10" cols="60" required><?php echo htmlspecialchars($content); ?></textarea><br><br>
    
    <input type="submit" value="Update Page">
  </form>
</body>
</html>
