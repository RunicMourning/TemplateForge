<?php
    $username = ucfirst(strtolower(htmlspecialchars($_SESSION['username'])));
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($siteTitle); ?> - <?php echo htmlspecialchars($pageTitle); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/yeti/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .mainnav .bi-file-earmark-text-fill { color: #0d6efd; }
    .mainnav .bi-stickies-fill { color: #6610f2; }
    .mainnav .bi-images { color: #20c997; }
    .mainnav .bi-server { color: #fd7e14; }
    .mainnav .bi-person-fill-check { color: #198754; }
    .mainnav .bi-gear-fill { color: #6c757d; }
    .mainnav .bi-cloud-upload { color: #0dcaf0; }
    .mainnav .bi-graph-up { color: #ffc107; }
  </style>
</head>
<body class="bg-light">

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <!-- Admin panel brand -->
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <i class="bi bi-speedometer2 me-1"></i>
      <?= htmlspecialchars($siteTitle) ?> Admin Dashboard
    </a>

    <!-- “View Site” link as a badge -->
    <a href="/" class="badge bg-primary text-decoration-none ms-3 d-flex align-items-center">
      <i class="bi bi-house-fill me-1"></i>
      View Site
    </a>
    <div class="dropdown ms-auto">
      <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-person-circle me-2"></i><?php echo $username; ?>
      </a>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
        <li><a class="dropdown-item" href="index.php?p=manageusers"><i class="bi bi-shield-lock me-2"></i>Manage Password</a></li>
        <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid">
  <div class="row" style="margin-top: 56px;">
    <!-- Sidebar -->
    <nav class="col-md-3 col-lg-2 d-md-block bg-secondary text-white border-end vh-100 position-fixed pt-3">
      <div class="position-sticky">
         <ul class="list-group mainnav">
          <li class="list-group-item"><a href="index.php?p=pages" class="link-underline-light"><i class="bi bi-file-earmark-text-fill me-2"></i>Pages</a></li>
          <li class="list-group-item"><a href="index.php?p=posts" class="link-underline-light"><i class="bi bi-stickies-fill me-2"></i>Blog Posts</a></li>
          <li class="list-group-item"><a href="index.php?p=uploads" class="link-underline-light"><i class="bi bi-cloud-upload me-2"></i>Uploads</a></li>
          <li class="list-group-item"><a href="index.php?p=analytics" class="link-underline-light"><i class="bi bi-graph-up me-2"></i>Analytics</a></li>
          <li class="list-group-item"><a href="index.php?p=gallery" class="link-underline-light"><i class="bi bi-images me-2"></i>Media Library</a></li>
          <li class="list-group-item"><a href="index.php?p=systeminfo" class="link-underline-light"><i class="bi bi-server me-2"></i>System Info</a></li>
          <li class="list-group-item"><a href="index.php?p=manageusers" class="link-underline-light"><i class="bi bi-person-fill-check me-2"></i>Users</a></li>
          <li class="list-group-item"><a href="index.php?p=settings" class="link-underline-light"><i class="bi bi-gear-fill me-2"></i>Settings</a></li>
        </ul>
      </div>
            <!--<?php
            // Include sidebar files if present
            $sidebarDir = 'sidebars';
            $sidebarFiles = array_diff(scandir($sidebarDir), array('.', '..'));
            if (!empty($sidebarFiles)) {
                foreach ($sidebarFiles as $file) {
                    if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') continue;
                    try {
                        include $sidebarDir . '/' . $file;
                    } catch (Exception $e) {
                        error_log("Error including sidebar file: " . $e->getMessage() . " in " . $sidebarDir . '/' . $file);
                        echo '<p class="mt-3 text-danger">Error loading sidebar item.</p>'; // Friendly message
                    }
                }
            } else {
                echo '<p class="mt-3">No sidebar items found.</p>';
            }
            ?> -->
    </nav>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
<?php echo $pageContent; ?>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
