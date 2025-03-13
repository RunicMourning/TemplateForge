<?php
session_start();
require_once 'login_module.php'; // Ensure correct path

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die("Access denied");
}

// Process form submissions (upload or deletion)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process file upload
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        if ($file['error'] === 0) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array(strtolower($ext), $allowed)) {
                $year = date('Y');
                $month = date('m');
                $uploadDir = __DIR__ . "/../images/$year/$month/";
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $fileName = uniqid() . ".$ext";
                $filePath = $uploadDir . $fileName;
                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    $_SESSION['message'] = [
                        'text' => "Image uploaded successfully: <a href='images/$year/$month/$fileName' target='_blank'>images/$year/$month/$fileName</a>",
                        'type' => 'success'
                    ];
                } else {
                    $_SESSION['message'] = ['text' => 'Upload failed.', 'type' => 'danger'];
                }
            } else {
                $_SESSION['message'] = ['text' => 'Invalid file type.', 'type' => 'danger'];
            }
        } else {
            $_SESSION['message'] = ['text' => 'File error.', 'type' => 'danger'];
        }
    }

    // Process image deletion
    if (isset($_POST['delete_image'])) {
        $imageToDelete = $_POST['delete_image'];  // Expected format: "images/2025/03/abc123.jpg"
        $baseDir = __DIR__ . "/../images/";
        // Remove "images/" from the beginning
        $relativePath = str_replace('images/', '', $imageToDelete);
        $imagePath = $baseDir . $relativePath;
        if (file_exists($imagePath)) {
            if (unlink($imagePath)) {
                $_SESSION['message'] = ['text' => 'Image deleted successfully!', 'type' => 'success'];
            } else {
                $_SESSION['message'] = ['text' => 'Failed to delete image.', 'type' => 'danger'];
            }
        } else {
            $_SESSION['message'] = ['text' => 'Image does not exist.', 'type' => 'danger'];
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <style>
    html, body {
      height: 100%;
    }
    .card-img-top {
      max-height: 200px;
      object-fit: cover;
    }
    .dashboard-card {
      background-color: #f8f9fa;
      border: 1px solid #ddd;
      padding: 10px;
      border-radius: 5px;
      text-align: center;
      margin-bottom: 20px;
    }
  </style>
</head>
<body class="d-flex flex-column min-vh-100">
  <div class="container-fluid d-flex flex-column min-vh-100">
    <header class="bg-light p-2 d-flex justify-content-between align-items-center">
      <div><i class="bi bi-book-half"></i> <?php echo htmlspecialchars($siteTitle); ?> Admin</div>
      <a href="/" class="btn btn-sm btn-primary" target="_blank" title="Visit Site">Visit Site</a>
    </header>
  
    <div class="row flex-grow-1">
      <!-- Sidebar -->
      <div class="col-sm-1 d-flex flex-column bg-body-tertiary">
        <div class="d-flex flex-column flex-shrink-0">
          <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
            <?php include('adminnav.inc'); ?>
          </ul>
        </div>
      </div>
      <!-- Main Content: Two Columns -->
      <div class="col-sm-11">
        <div class="row">
          <!-- Left Column: Upload Form -->
          <div class="col-md-4">
            <div class="card mb-4">
              <div class="card-header">
                <h5>Upload Image</h5>
              </div>
              <div class="card-body">
                <form method="POST" enctype="multipart/form-data" id="uploadForm">
                  <div class="mb-3">
                    <input type="file" name="file" accept="image/*" class="form-control">
                  </div>
                  <button type="submit" class="btn btn-success w-100">
                    <i class="bi bi-check-circle"></i> Upload
                  </button>
                </form>
              </div>
            </div>
            <!-- Upload/Deletion Message -->
            <?php if (isset($_SESSION['message'])): ?>
              <div class="alert alert-<?php echo $_SESSION['message']['type']; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']['text']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
          </div>
          
          <!-- Right Column: Uploaded Images Gallery -->
          <div class="col-md-8">
            <h3>Uploaded Images</h3>
            <div class="row">
<?php
$baseDir = __DIR__ . "/../images/";
if (is_dir($baseDir)) {
    // Group images by year.
    $years = array_filter(glob($baseDir . '*'), 'is_dir');
    foreach ($years as $yearDir) {
        $year = basename($yearDir);
        echo "<div class='col-12'><h4 class='mt-3'>$year</h4></div>";
        $months = array_filter(glob($yearDir . '/*'), 'is_dir');
        foreach ($months as $monthDir) {
            $month = basename($monthDir);
            echo "<div class='col-12'><h5 class='ms-3'>$month</h5></div>";
            $images = glob($monthDir . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
            foreach ($images as $image) {
                // Look for the position of "images/" in the absolute path.
                $pos = strpos($image, 'images/');
                if ($pos !== false) {
                    $relativeUrl = substr($image, $pos);
                } else {
                    $relativeUrl = $image; // Fallback if "images/" isn't found.
                }
                // Prepend "../" for display since this page is in admin/
                $displayUrl = "../" . $relativeUrl;
                $imageName = basename($image);
                echo "<div class='col-md-4 mb-3'>
                        <div class='card'>
                          <img src='" . htmlspecialchars($displayUrl, ENT_QUOTES, 'UTF-8') . "' class='card-img-top' alt='" . htmlspecialchars($imageName, ENT_QUOTES, 'UTF-8') . "'>
                          <div class='card-body p-2'>
                            <h6 class='card-title text-truncate'>" . htmlspecialchars($imageName, ENT_QUOTES, 'UTF-8') . "</h6>
                            <form method='POST' onsubmit='return confirm(\"Are you sure you want to delete this image?\");'>
                              <input type='hidden' name='delete_image' value='" . htmlspecialchars($relativeUrl, ENT_QUOTES, 'UTF-8') . "'>
                              <button type='submit' class='btn btn-danger btn-sm w-100'>
                                <i class='bi bi-trash'></i> Delete
                              </button>
                            </form>
                          </div>
                        </div>
                      </div>";
            }
        }
    }
} else {
    echo "<div class='col-12'>No images uploaded yet.</div>";
}
?>
            </div>
          </div>
        </div><!-- End row -->
      </div>
    </div>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Optional: Toggle folder visibility if needed -->
  <script>
    function toggleVisibility(id) {
      var element = document.getElementById(id);
      element.style.display = (element.style.display === 'none' ? 'block' : 'none');
    }
  </script>
</body>
</html>
