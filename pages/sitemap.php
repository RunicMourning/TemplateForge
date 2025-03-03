<?php
// pages/sitemap.php

// Define the title for this page.
$pageTitle = "Sitemap";

//$headerIncludes[] = "Content";
//$footerIncludes[] = "Content";

// Prepare the data for the sitemap.
$pagesDir = 'pages/';
$blogDir = 'blog_posts/';
$pages = [];
$blogPosts = [];
$blogPage = null;

// Scan pages directory
foreach (glob($pagesDir . '*.php') as $file) {
    $filename = basename($file, '.php');
    if ($filename === 'sitemap') continue; // Ignore sitemap.php
    if ($filename === '404') continue; // Ignore 404.php
    if ($filename === 'blog') {
        $blogPage = $filename; // Store blog.php separately
    } else {
        $pages[] = $filename;
    }
}

// Scan blog posts directory
foreach (glob($blogDir . '*.php') as $file) {
    $filename = basename($file, '.php');
    $postTitle = 'Untitled'; // Default fallback title
    $postTimestamp = '1970-01-01 00:00:00'; // Default fallback timestamp
    
    // Extract metadata without executing the file
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (preg_match('/\$postTitle\s*=\s*["\'](.+?)["\'];/', $content, $matches)) {
            $postTitle = $matches[1];
        }
        if (preg_match('/\$postTimestamp\s*=\s*["\'](.+?)["\'];/', $content, $matches)) {
            $postTimestamp = $matches[1];
        }
    }
    
    $blogPosts[] = [
        'filename' => $filename,
        'title' => $postTitle,
        'timestamp' => $postTimestamp
    ];
}

// Sort blog posts by timestamp (newest first)
usort($blogPosts, function ($a, $b) {
    return strtotime($b['timestamp']) - strtotime($a['timestamp']);
}); 
?>

<div class="card mt-3">
  <div class="card-header">
    Sitemap
  </div>
  <div class="card-body">
<pre>
<a href="/"><?php echo htmlspecialchars($siteTitle); ?></a>
 <?php foreach ($pages as $page): ?>
  └── <a href="/<?= $page ?>.html"><?= ucfirst($page) ?></a>
 <?php endforeach; ?>
 <?php if ($blogPage): ?>
 └── <a href="/blog.html">blog</a>
    <?php foreach ($blogPosts as $post): ?>
    └── <a href="/blog-<?= $post['filename'] ?>.html"><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></a>
    <?php endforeach; ?>
 <?php endif; ?>
</pre>
  </div>
</div>

