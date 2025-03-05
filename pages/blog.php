<?php
// pages/blog.php

echo generateBreadcrumbs();
$pageTitle = "Blog";
$headerIncludes[] = "";
$footerIncludes[] = "";


// Define the directory where blog post files are stored.
$blogPostsDir = __DIR__ . '/../blog_posts/';

// Check if a specific post is requested.
if (isset($_GET['post']) && !empty($_GET['post'])) {
    // Sanitize the post slug.
    $postSlug = preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['post']);
    $postFile = $blogPostsDir . $postSlug . '.php';
    
    if (!file_exists($postFile)) {
        header("HTTP/1.0 404 Not Found");
        echo "Post not found.";
        exit;
    }
    
    // Capture the content of the blog post.
    ob_start();
    include $postFile;
    $postContent = ob_get_clean();
    
    // Load the single post template.
    include __DIR__ . '/../templates/blog_post_template.php';

} else {
    // Blog list view: scan the blog posts directory.
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
            'title'     => $postTitle,
            'timestamp' => $postTimestamp,
            'excerpt'   => $excerpt,
        ];
    }

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

    include __DIR__ . '/../templates/blog_list_template.php';

    // Display Bootstrap pagination
    if ($totalPages > 1) {
        echo '<nav aria-label="Blog Pagination">';
        echo '<ul class="pagination justify-content-center mt-3">';

        // Previous button
        if ($currentPage > 1) {
            echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage - 1) . '">Previous</a></li>';
        } else {
            echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
        }

        // Page numbers
        for ($page = 1; $page <= $totalPages; $page++) {
            if ($page == $currentPage) {
                echo '<li class="page-item active"><span class="page-link">' . $page . '</span></li>';
            } else {
                echo '<li class="page-item"><a class="page-link" href="?page=' . $page . '">' . $page . '</a></li>';
            }
        }

        // Next button
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
