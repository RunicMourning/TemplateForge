<?php
// admin/create_page.php

$pageTitle = 'Create Page';

include_once __DIR__.'/../logger.php';

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
            $filePath = __DIR__ . "/../../pages/{$filename}.php";

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

log_activity('Page Created', 'Filename: ' . $filename);

                    $success = "Page created successfully.";
                } else {
                    $error = "Error creating page. Check directory permissions.";
                }
            }
        }
    }
}
?>
<?php if (isset($error)): ?>
  <div class="message alert alert-danger">
    <?php echo htmlspecialchars($error); ?>
  </div>
<?php endif; ?>
<?php if (isset($success)): ?>
  <div class="message alert alert-success">
    <?php echo htmlspecialchars($success); ?>
  </div>
<?php endif; ?>
<form method="post" action="">
  <div class="row mt-4">
    <div class="col">
      <div class="mb-3 input-group">
        <span class="input-group-text" id="title-addon">Title</span>
        <input type="text" id="title" name="title" class="form-control" aria-describedby="title-addon" required>
      </div>
    </div>
    <div class="col">
      <div class="mb-3 input-group">
        <span class="input-group-text" id="filename-addon">Filename</span>
        <input type="text" id="filename" name="filename" class="form-control" aria-describedby="filename-addon" required>
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
          <textarea id="content" name="content" class="form-control" rows="10" required></textarea>
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
      <div class="mb-3">
        <label for="header_includes" class="form-label">Header Includes (one per line):</label>
        <div class="input-group">
          <span class="input-group-text">Header Includes</span>
          <textarea id="header_includes" name="header_includes" class="form-control" rows="4" placeholder="E.g., &lt;link rel=&quot;stylesheet&quot; href=&quot;css/custom.css&quot;&gt;"></textarea>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="mb-3">
        <label for="footer_includes" class="form-label">Footer Includes (one per line):</label>
        <div class="input-group">
          <span class="input-group-text">Footer Includes</span>
          <textarea id="footer_includes" name="footer_includes" class="form-control" rows="4" placeholder="E.g., &lt;script src=&quot;js/custom.js&quot;&gt;&lt;/script&gt;"></textarea>
        </div>
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


<script src="codeedit.js"></script>
