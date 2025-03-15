<?php
// pages/create_post.php

$pageTitle = 'Create Post';
include_once __DIR__.'/../logger.php';



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
                          
            $filePath = realpath(__DIR__ . '/../../blog_posts') . "/{$filename}.php";
            
            if (file_put_contents($filePath, $phpContent) !== false) {
                $success = "Post created successfully.";

log_activity('Post Created', 'Filename: ' . $filename);
            } else {
                $error = "Error creating post.";
            }
        }
    }
}
?>

    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
      <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="post" action="" class="mt-4">
          <div class="row mt-4">
            <div class="col">
      <div class="mb-3 input-group">
        <span class="input-group-text" id="title-label">Title</span>
        <input type="text" id="title" name="title" class="form-control" required aria-describedby="title-label">
      </div>
      <div class="mb-3 input-group">
        <span class="input-group-text" id="date-label">Date</span>
        <input type="date" id="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required aria-describedby="date-label">
      </div>
			</div>
            <div class="col">
      <div class="mb-3 input-group">
        <span class="input-group-text" id="filename-label">Filename</span>
        <input type="text" id="filename" name="filename" class="form-control" required aria-describedby="filename-label">
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
  