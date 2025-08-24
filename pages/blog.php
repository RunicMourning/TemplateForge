<?php
// pages/blog.php

$pageTitle = "Blog";
$headerIncludes[] = "";
$footerIncludes[] = "";

// Directory where blog post files are stored
$blogPostsDir = __DIR__ . '/../blog_posts/';

// Determine the requested page slug from index.php
$requestedPage = $_GET['p'] ?? 'blog';
$requestedPage = strtolower($requestedPage);

// Detect if this is a single post request
$postSlug = null;

// URLs like "blog-first-post.html" ? slug = "first-post"
if (str_starts_with($requestedPage, 'blog-')) {
    $postSlug = substr($requestedPage, 5); // remove "blog-" prefix
} elseif (isset($_GET['post'])) {
    $postSlug = preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['post']);
}

if ($postSlug) {
    $postFile = $blogPostsDir . $postSlug . '.php';

    if (file_exists($postFile)) {
        // Capture the content of the blog post
        ob_start();
        include $postFile;
        $postContent = ob_get_clean();

        // Append post title if available
        if (isset($postTitle) && $postTitle !== '') {
            $pageTitle .= ' - ' . $postTitle;
        }

        // Load single post template
        include __DIR__ . '/../templates/blog_post_template.php';

    } else {
        // Post not found: directly include 404 page
        header("HTTP/1.0 404 Not Found");
        $missingPost = $postSlug; // optional: used in 404.php for suggestions
        include __DIR__ . '/404.php';
    }

} else {
    // Blog list view: scan the blog posts directory
    $postFiles = glob($blogPostsDir . '*.php');
    $blogPosts = [];

    foreach ($postFiles as $file) {
        ob_start();
        include $file;
        $content = ob_get_clean();

        $excerpt = '';
        if (preg_match('/<p>(.*?)<\/p>/s', $content, $matches)) {
            $excerpt = strip_tags($matches[1]);
        }

        $slug = basename($file, '.php');

        $blogPosts[] = [
            'slug'      => $slug,
            'title'     => $postTitle ?? '',
            'timestamp' => $postTimestamp ?? '',
            'excerpt'   => $excerpt,
        ];
    }

    // Sort posts newest first
    usort($blogPosts, function ($a, $b) {
        return strcmp($b['timestamp'], $a['timestamp']);
    });

    // Pagination settings
    $postsPerPage = 10;
    $totalPosts = count($blogPosts);
    $totalPages = ceil($totalPosts / $postsPerPage);
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $currentPage = max(1, min($currentPage, $totalPages));
    $offset = ($currentPage - 1) * $postsPerPage;
    $pagedPosts = array_slice($blogPosts, $offset, $postsPerPage);

    // Include blog list template
    include __DIR__ . '/../templates/blog_list_template.php';

    // Display Bootstrap pagination if needed
    if ($totalPages > 1) {
        echo '<nav aria-label="Blog Pagination">';
        echo '<ul class="pagination justify-content-center mt-3">';

        if ($currentPage > 1) {
            echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage - 1) . '">Previous</a></li>';
        } else {
            echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
        }

        for ($page = 1; $page <= $totalPages; $page++) {
            if ($page == $currentPage) {
                echo '<li class="page-item active"><span class="page-link">' . $page . '</span></li>';
            } else {
                echo '<li class="page-item"><a class="page-link" href="?page=' . $page . '">' . $page . '</a></li>';
            }
        }

        if ($currentPage < $totalPages) {
            echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage + 1) . '">Next</a></li>';
        } else {
            echo '<li class="page-item disabled"><span class="page-link">Next</span></li>';
        }

        echo '</ul>';
        echo '</nav>';
    }
}
?>
