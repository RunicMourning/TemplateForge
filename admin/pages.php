<?php
// admin/pages.php

require_once 'login_module.php';

$pagesDir = realpath(__DIR__ . '/../pages');
$pages = glob($pagesDir . '/*.php');
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
    .card {
      margin-bottom: 15px;
    }
    .card-body {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .card-title {
      font-size: 1.1rem;
    }
    .card-footer {
      display: flex;
      justify-content: flex-start;
    }
    .btn-group a {
      margin-right: 10px;
    }
  </style>
</head>
<body class="d-flex flex-column min-vh-100">
  <div class="container-fluid d-flex flex-column min-vh-100">
    <div class="bg-light p-2"><a href="/"><i class="bi bi-book-half"></i> Admin</a></div>
    <div class="row flex-grow-1 h-100">
      <div class="col-sm-1 d-flex flex-column bg-body-tertiary h-100">
        <div class="d-flex flex-column flex-shrink-0">
          <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
<?php include('adminnav.inc'); ?>
          </ul>
        </div>
      </div>
      <div class="col-sm-11 h-100">
        <div class="row">
          <?php foreach ($pages as $pagePath): 
                  $filename = basename($pagePath);
          ?>
            <div class="col-md-4 col-sm-6">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title"><?php echo htmlspecialchars($filename); ?></h5>
                </div>
                <div class="card-footer btn-group" role="group" aria-label="Page Actions">
                    <a href="edit_page.php?page=<?php echo urlencode($filename); ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_page.php?page=<?php echo urlencode($filename); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this page?');">Delete</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
