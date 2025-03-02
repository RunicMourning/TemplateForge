<?php
// admin/posts.php

require_once 'login_module.php';
require_once '../config/config.php';

$postsDir = realpath(__DIR__ . '/../blog_posts');
$posts = glob($postsDir . '/*.php');

// Array to store posts with their timestamps
$postsWithTimestamp = [];

// Extract filename and timestamp for each post
foreach ($posts as $postPath) {
    $filename = basename($postPath);
    
    // Read the content of the post without including it
    $fileContent = file_get_contents($postPath);

    // Ensure the postTimestamp is correctly extracted, using a more flexible regex
    if (preg_match('/\$postTimestamp\s*=\s*(["\'])(.*?)\1/', $fileContent, $matches)) {
        // The timestamp should be in the second captured group
        $timestampString = $matches[2];

        // Attempt to convert the timestamp string to a Unix timestamp
        $timestamp = strtotime($timestampString);
        if ($timestamp !== false) {
            // Store the filename and its timestamp
            $postsWithTimestamp[] = [
                'filename' => $filename,
                'timestamp' => $timestamp
            ];
        } else {
            // If strtotime fails, skip this post (or log an error)
            continue;
        }
    } else {
        // If no timestamp found, skip this post (or log an error)
        continue;
    }
}

// Sort posts by timestamp (newest first)
usort($postsWithTimestamp, function ($a, $b) {
    return $b['timestamp'] - $a['timestamp'];
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Blog Posts</title>
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
        <div class="row">
          <?php foreach ($postsWithTimestamp as $post): ?>
            <div class="col-md-4 col-sm-6">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title"><?php echo htmlspecialchars($post['filename']); ?></h5>
                  <p class="card-text text-muted"><?php echo date('F j, Y g:i A', $post['timestamp']); ?></p>
                </div>
                <div class="card-footer btn-group" role="group" aria-label="Post Actions">
                    <a href="edit_post.php?post=<?php echo urlencode($post['filename']); ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_post.php?post=<?php echo urlencode($post['filename']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
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
