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


    <div class="card shadow-lg mt-3">
        <div class="card-header">
            Recent Posts
        </div>
        <ul class="list-group list-group-flush">
            <?php if (!empty($recentPosts)) : ?>
                <?php foreach ($recentPosts as $post) : ?>
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="blog-<?php echo urlencode($post['slug']); ?>.html" class="text-decoration-none">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                            <span class="badge bg-secondary">
                                <?php echo date('F jS Y', strtotime($post['timestamp'])); ?>
                            </span>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-group-item">No posts found.</li>
            <?php endif; ?>
        </ul>
    </div>


