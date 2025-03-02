<?php
// admin/index.php

require_once 'login_module.php';
require_once '../config/config.php';


// Function to count the files in a directory
function count_files_in_directory($dir) {
    // Open the directory and count the number of files
    $files = array_diff(scandir($dir), array('..', '.')); // Ignore "." and ".."
    return count($files); // Return the number of files
}

// Get the number of pages and blog posts by counting files in the respective directories
$total_pages = count_files_in_directory('../pages');  // Adjust the path to point to the pages directory
$total_posts = count_files_in_directory('../blog_posts');  // Adjust the path to point to the blog_posts directory





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
    .dashboard-card {
      background-color: #f8f9fa;
      border: 1px solid #ddd;
      padding: 10px;
      border-radius: 5px;
      text-align: center;
      margin-bottom: 20px;
    }
    .card-header, .card-footer {
      background-color: #f1f3f5;
      border: none;
      text-align: center;
    }
    .btn-group .btn {
      margin-right: 5px;
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

        <div class="card m-5">
          <!-- Card Header -->
          <div class="card-header">
            <h5 class="card-title">Site Information</h5>
          </div>

          <!-- Card Body with 2 Columns -->
          <div class="card-body">
            <div class="row">
              <!-- Column 1 -->
              <div class="col-md-6">
                <h6>Site Stats</h6>
                <div class="row">
                  <div class="col-md-6">
                    <div class="dashboard-card">
                      <h5>Total Pages</h5>
                      <p><?php echo $total_pages; ?></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="dashboard-card">
                      <h5>Total Posts</h5>
                      <p><?php echo $total_posts; ?></p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Column 2 -->
              <div class="col-md-6">
                <h6>Other Stats</h6>
                <p>Additional content or stats can go here.</p>
              </div>
            </div>
          </div>

          <!-- Card Footer -->
          <div class="card-footer text-muted">
            <div class="btn-group mt-4" role="group" aria-label="Admin Actions">
              <a href="pages.php" class="btn btn-primary">
                <i class="bi bi-file-earmark-text"></i> Manage Pages
              </a>
              <a href="posts.php" class="btn btn-info">
                <i class="bi bi-pencil-square"></i> Manage Blog Posts
              </a>
              <a href="create_page.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Add New Page
              </a>
              <a href="create_post.php" class="btn btn-warning">
                <i class="bi bi-file-earmark-plus"></i> Add New Post
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
