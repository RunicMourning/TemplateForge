<?php
// admin/edit_page.php

$pageTitle = 'Edit Page';

if (!isset($_GET['page'])) {
    die("No page specified.");
}

$filename = basename($_GET['page']);
$filePath = __DIR__ . "/../../pages/{$filename}";

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
include __DIR__.'/../logger.php'; // Adjust path as needed
log_activity('File Edited', 'Filename: ' . $filename);
        } else {
            $error = "Error updating page.";
        }
    }
} else {
    $existingContent = file_get_contents($filePath);
    $title = '';
    $headerIncludes = '';
    $footerIncludes = '';

    // Extract page title
    if (preg_match('/\$pageTitle\s*=\s*(["\'])(.*?)\1\s*;/', $existingContent, $matches)) {
        $title = $matches[2];
    }

    // Extract headerIncludes[] lines
    preg_match_all('/\$headerIncludes\[\]\s*=\s*(["\'])(.*?)\1\s*;/', $existingContent, $headerMatches);
    $headerIncludes = implode("\n", $headerMatches[2] ?? []);

    // Extract footerIncludes[] lines
    preg_match_all('/\$footerIncludes\[\]\s*=\s*(["\'])(.*?)\1\s*;/', $existingContent, $footerMatches);
    $footerIncludes = implode("\n", $footerMatches[2] ?? []);

    // Extract content while keeping any custom PHP intact
    $content = preg_replace('/<\?php.*?\$pageTitle\s*=.*?\?>\s*/s', '', $existingContent);
}

?>

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
        <label for="title">Page Title:</label>
        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
      </div>
			</div>
          </div>
          <div class="row mt-4">
            <div class="col-md-6">
              <div class="card">
                <div class="btn-group editor-controls w-100 card-header">
                  <button type="button" class="btn btn-light btn-sm" onclick="insertLink()" title="Insert Link"><i class="bi bi-link"></i></button>
                  <button type="button" class="btn btn-light btn-sm" onclick="insertImage()" title="Insert Image"><i class="bi bi-image"></i></button>
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
          <div class="row mt-4">
            <div class="col">
      <div class="form-group">
        <label for="header_includes">Header Includes (one per line):</label>
        <textarea class="form-control" id="header_includes" name="header_includes" rows="5"><?php echo htmlspecialchars($headerIncludes); ?></textarea>
      </div>
			</div>
            <div class="col">
      <div class="form-group">
        <label for="footer_includes">Footer Includes (one per line):</label>
        <textarea class="form-control" id="footer_includes" name="footer_includes" rows="5"><?php echo htmlspecialchars($footerIncludes); ?></textarea>
      </div>
			</div>
          </div>
          <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>

  
  
    
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
  

<script src="codeedit.js"></script>