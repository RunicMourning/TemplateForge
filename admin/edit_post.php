<?php
// admin/edit_post.php

require_once 'login_module.php';
require_once '../config/config.php';
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blog Text Editor</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <style>
    html, body {
      height: 100%;
    }
    pre {
      background-color: #f5f5f5;
      padding: 10px;
      border: 1px solid #ddd;
    }
    #preview {
      padding: 10px;
      margin-top: 10px;
    }
    #colorPickerOverlay {
      display: none;
      position: absolute;
      background-color: white;
      border: 1px solid #ddd;
      padding: 10px;
      border-radius: 5px;
      z-index: 999;
    }
  </style>
</head>
<body class="d-flex flex-column min-vh-100">
  <div class="container-fluid d-flex flex-column min-vh-100">

  
  
    <header class="bg-light p-2 d-flex justify-content-between align-items-center">
  <div><i class="bi bi-book-half"></i> <?php echo htmlspecialchars($siteTitle); ?> Admin</div>
  <a href="/" class="btn btn-sm btn-primary float-end" target="_blank" title="Visit Site">
    Visit Site
  </a>
</header>
  

    <div class="row flex-grow-1 h-100">
      <div class="col-sm-1 d-flex flex-column bg-body-tertiary h-100">
        <div class="d-flex flex-column flex-shrink-0">
          <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
<?php include('adminnav.inc'); ?>
          </ul>
        </div>
      </div>
      <div class="col-sm-11 h-100">

    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if (isset($success)): ?>
      <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="post" action="">
          <div class="row mt-4">
            <div class="col">
      <div class="form-group">
        <label for="title">Post Title:</label>
        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
      </div>
			</div>
            <div class="col">
      <div class="form-group">
        <label for="timestamp">Timestamp (YYYY-MM-DD HH:MM:SS):</label>
        <input type="datetime-local" class="form-control" id="timestamp" name="timestamp" value="<?php echo htmlspecialchars($timestamp); ?>" required>
      </div>
			</div>
          </div>
          <div class="row mt-4">
            <div class="col-md-6">
              <div class="card">
                <div class="btn-group editor-controls w-100 card-header">
                  <button type="button" class="btn btn-light btn-sm" onclick="insertLink()" title="Insert Link"><i class="bi bi-link"></i></button>
                  <button type="button" class="btn btn-light btn-sm" onclick="insertImage()" title="Insert Image"><i class="bi bi-image"></i></button>
                  <button type="button" class="btn btn-light btn-sm btn-color-picker" onclick="showColorPicker()" title="Text Color"><i class="bi bi-palette"></i></button>
                  <button type="button" class="btn btn-light btn-sm" onclick="insertTemplate('bold')" title="Bold Text"><i class="bi bi-type-bold"></i></button>
                  <button type="button" class="btn btn-light btn-sm" onclick="insertTemplate('italic')" title="Italic Text"><i class="bi bi-type-italic"></i></button>
                  <button type="button" class="btn btn-light btn-sm" onclick="insertTemplate('underline')" title="Underline Text"><i class="bi bi-type-underline"></i></button>
                  <button type="button" class="btn btn-light btn-sm" onclick="insertTemplate('strikethrough')" title="Strikethrough Text"><i class="bi bi-type-strikethrough"></i></button>
                  <button type="button" class="btn btn-light btn-sm" onclick="insertTemplate('unorderedList')" title="Unordered List"><i class="bi bi-list-ul"></i></button>
                  <button type="button" class="btn btn-light btn-sm" onclick="insertTemplate('orderedList')" title="Ordered List"><i class="bi bi-list-ol"></i></button>
                  <button type="button" class="btn btn-light btn-sm" onclick="insertTemplate('blockquote')" title="Blockquote"><i class="bi bi-blockquote-left"></i></button>
                  <button type="button" class="btn btn-light btn-sm" onclick="insertTemplate('leftAlign')" title="Left Align"><i class="bi bi-text-left"></i></button>
                  <button type="button" class="btn btn-light btn-sm" onclick="insertTemplate('centerAlign')" title="Center Align"><i class="bi bi-text-center"></i></button>
                  <button type="button" class="btn btn-light btn-sm" onclick="insertTemplate('rightAlign')" title="Right Align"><i class="bi bi-text-right"></i></button>
                </div>
                <div class="card-body">
                  <textarea class="form-control" id="content" name="content" rows="10" required><?php echo htmlspecialchars($content); ?></textarea>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card">
                <div class="card-header">
                  <h5>Preview</h5>
                </div>
                <div class="card-body">
                  <div id="preview"></div>
                </div>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
      </div>
    </div>
  </div>
  
  
      <!-- Hidden Elements -->
    <div id="colorPickerOverlay">
      <input type="color" id="colorPicker">
      <button onclick="applyTextColor()">OK</button>
    </div>
    
    <!-- Link Insertion Modal -->
    <div class="modal fade" id="linkModal" tabindex="-1" aria-labelledby="linkModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="linkModalLabel">Insert Link</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <label for="linkText">Text to Display:</label>
            <input type="text" id="linkText" class="form-control">
            <label for="linkURL" class="mt-2">URL:</label>
            <input type="url" id="linkURL" class="form-control">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="applyLink()">Insert</button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Image Insertion Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="imageModalLabel">Insert Image</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <label for="imageURL">Image URL:</label>
            <input type="url" id="imageURL" class="form-control">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="applyImage()">Insert</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="codeedit.js"></script>
</body>
</html>
