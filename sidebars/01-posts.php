<?php
// sidebars/recent_posts.php

// Define the blog posts directory relative to this file.
$blogPostsDir = __DIR__ . '/../blog_posts/';

// Retrieve all PHP files in the blog posts directory.
$postFiles = glob($blogPostsDir . '*.php');
$blogPosts = [];

foreach ($postFiles as $file) {
    // Start output buffering to capture the content without direct output.
    ob_start();
    include $file;
    ob_end_clean();
    
    // Check if the blog post file defined the required variables.
    if (isset($postTitle, $postTimestamp)) {
        // Derive a slug from the filename (without the .php extension).
        $slug = basename($file, '.php');
        
        $blogPosts[] = [
            'slug'      => $slug,
            'title'     => $postTitle,
            'timestamp' => $postTimestamp,
        ];
    }
    // Unset these variables to avoid interference with subsequent iterations.
    unset($postTitle, $postTimestamp);
}

// Sort the blog posts by timestamp in descending order (newest first).
usort($blogPosts, function($a, $b) {
    return strcmp($b['timestamp'], $a['timestamp']);
});

// Limit the list to the 5 most recent posts.
$recentPosts = array_slice($blogPosts, 0, 5);
?>

<div class="card mt-3">
    <div class="card-header">
        <h3><i class="bi bi-clock-history me-2"></i> Recent Posts</h3>
    </div>
    <ul class="list-group list-group-flush">
        <?php if (!empty($recentPosts)) : ?>
            <?php foreach ($recentPosts as $post) : ?>
                <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                    <a href="blog-<?php echo urlencode($post['slug']); ?>.html" class="text-decoration-none flex-grow-1">
                        <small><i class="bi bi-file-earmark-text me-1 text-primary"></i> <?php echo htmlspecialchars($post['title']); ?></small>
                    </a>
                    <span class="badge bg-secondary rounded-pill">
                        <small><i class="bi bi-calendar3 me-1"></i> <?php echo date('M j', strtotime($post['timestamp'])); ?></small>
                    </span>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li class="list-group-item text-muted text-center">No posts found.</li>
        <?php endif; ?>
    </ul>
</div>
