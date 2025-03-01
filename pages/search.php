<?php
// pages/search.php

echo generateBreadcrumbs();


// Define the title for this page.
$pageTitle = "Search";
//$headerIncludes[] = "Content";
//$footerIncludes[] = "Content";


// Retrieve and sanitize the search query.
$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$query = filter_var($query, FILTER_SANITIZE_SPECIAL_CHARS);

// Initialize an array to hold search results.
$results = [];

if (!empty($query)) {
    // --- Search Static Pages ---
    // Define the directory containing your page files.
    $pagesDir = __DIR__;
    // Retrieve all PHP files in the pages directory.
    $pageFiles = glob($pagesDir . '/*.php');

    foreach ($pageFiles as $file) {
        $filename = basename($file);
        // Exclude certain pages that are not meant for public display.
        if (in_array($filename, ['search.php', 'blog.php', '404.php'])) {
            continue;
        }

        // Load the file content.
        $content = file_get_contents($file);

        // If the query is found within the content (case-insensitive search)
        if (stripos($content, $query) !== false) {
            // Optionally, you can extract a title if your page files set one.
            // For demonstration, we use the filename as a placeholder title.
            $results[] = [
                'type'    => 'page',
                'title'   => ucfirst(basename($file, '.php')),
                'snippet' => substr(strip_tags($content), 0, 200) . '...',
                'link'    => 'index.php?p=' . basename($file, '.php'),
            ];
        }
    }

    // --- Search Blog Posts ---
    // Define the directory where blog post files are stored.
    $blogPostsDir = __DIR__ . '/../blog_posts/';
    $blogPostFiles = glob($blogPostsDir . '*.php');

    foreach ($blogPostFiles as $file) {
        // Load the blog post content.
        $content = file_get_contents($file);

        // If the query is found within the content
        if (stripos($content, $query) !== false) {
            // Derive the slug from the filename.
            $slug = basename($file, '.php');
            // In a real implementation, you might extract the title and timestamp
            // by including the file in a controlled manner or storing metadata separately.
            $results[] = [
                'type'    => 'blog',
                'title'   => ucfirst($slug),
                'snippet' => substr(strip_tags($content), 0, 200) . '...',
                'link'    => 'index.php?p=blog&post=' . $slug,
            ];
        }
    }
}

// Include the search results template.
// This template will expect the $results array and the original $query.
include __DIR__ . '/../templates/search_template.php';
?>
