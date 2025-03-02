<?php
// admin/index.php

require_once 'login_module.php';
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
    <div class="bg-light p-2"><i class="bi bi-book-half"></i> Admin</div>
    <div class="row flex-grow-1 h-100">
      <div class="col-sm-1 d-flex flex-column bg-body-tertiary h-100">
        <div class="d-flex flex-column flex-shrink-0">
          <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
<?php include('adminnav.inc'); ?>
          </ul>
        </div>
      </div>
      <div class="col-sm-11 h-100">
  <ul>
    <li><a href="pages.php">Manage Pages</a></li>
    <li><a href="posts.php">Manage Blog Posts</a></li>
  </ul>
      </div>
    </div>
  </div>
  
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

