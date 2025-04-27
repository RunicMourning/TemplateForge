<?php
// sidebars/recent_posts.php

$blogPostsDir = __DIR__ . '/../blog_posts/';
$postFiles = glob($blogPostsDir . '*.php');
$blogPosts = [];

foreach ($postFiles as $file) {
    ob_start();
    include $file;
    ob_end_clean();
    
    if (isset($postTitle, $postTimestamp)) {
        $slug = basename($file, '.php');
        $blogPosts[] = [
            'slug'      => $slug,
            'title'     => $postTitle,
            'timestamp' => $postTimestamp,
        ];
    }
    unset($postTitle, $postTimestamp);
}

usort($blogPosts, fn($a, $b) => strcmp($b['timestamp'], $a['timestamp']));
$recentPosts = array_slice($blogPosts, 0, 5);
?>

<div class="card mt-3 shadow-sm border-0 rounded-4 overflow-hidden">
    <div class="card-header bg-transparent border-0">
        <h5 class="mb-0 d-flex align-items-center">
            <i class="bi bi-clock-history me-2 text-primary"></i> Recent Posts
        </h5>
    </div>
    <div class="card-body p-3">
        <ul class="list-unstyled mb-0">
            <?php if (!empty($recentPosts)) : ?>
                <?php foreach ($recentPosts as $post) : ?>
                    <li class="mb-3">
                        <a href="blog-<?php echo urlencode($post['slug']); ?>.html" class="text-decoration-none d-flex flex-column small link-dark hover-link">
                            <span class="fw-semibold"><?php echo htmlspecialchars($post['title']); ?></span>
                            <span class="text-muted"><?php echo date('M j, Y', strtotime($post['timestamp'])); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="text-muted small">No posts found.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>