<?php
// admin/posts.php
$pageTitle = 'Manage Posts';

$postsDir = realpath(__DIR__ . '/../../blog_posts');
$posts = glob($postsDir . '/*.php');

// Array to store posts with their timestamps and titles
$postsWithDetails = [];

// Extract filename, title, and timestamp for each post
foreach ($posts as $postPath) {
    $filename = basename($postPath);
    
    // Read the content of the post without including it
    $fileContent = file_get_contents($postPath);

    // Extract post title
    $title = 'Untitled Post'; // Default title if not found
    if (preg_match('/\$postTitle\s*=\s*(["\'])(.*?)\1/', $fileContent, $titleMatches)) {
        $title = $titleMatches[2];
    }

    // Extract timestamp
    if (preg_match('/\$postTimestamp\s*=\s*(["\'])(.*?)\1/', $fileContent, $timestampMatches)) {
        $timestampString = $timestampMatches[2];
        $timestamp = strtotime($timestampString);
        if ($timestamp !== false) {
            // Store the details
            $postsWithDetails[] = [
                'filename' => $filename,
                'title' => $title,
                'timestamp' => $timestamp
            ];
        }
    }
}

// Sort posts by timestamp (newest first)
usort($postsWithDetails, function ($a, $b) {
    return $b['timestamp'] - $a['timestamp'];
});
?>

<div class="container mt-4">
    <!-- Create Page Button -->
    <div class="row mb-4">
        <div class="col">
            <a href="index.php?p=create_post" class="btn btn-primary btn-lg w-100">
                Create New Page
            </a>
        </div>
    </div>
        <div class="row">
          <?php foreach ($postsWithDetails as $post): ?>
            <div class="col-md-4 col-sm-6">
              <div class="card mb-2">
                <div class="card-body">
                  <h5 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h5>
                  <p class="card-text text-muted"><?php echo date('F j, Y g:i A', $post['timestamp']); ?></p>
                </div>
                <div class="card-footer btn-group" role="group" aria-label="Post Actions">
                    <a href="index.php?p=edit_post&post=<?php echo urlencode($post['filename']); ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_post.php?post=<?php echo urlencode($post['filename']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
    </div>
</div>
