<?php
// Define the file path for analytics storage
$logFile = __DIR__ . '/config/data/analytics.json';

// Determine if it's a blog post or a standard page
if (isset($_GET['p']) && $_GET['p'] === 'blog' && isset($_GET['post'])) {
    // Blog Post Tracking
    $postSlug = preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['post']);
    $contentFile = __DIR__ . "/blog_posts/{$postSlug}.php";

    if (file_exists($contentFile)) {
        $postTitle = 'Unknown Blog Post'; // Default in case title isn't set
        ob_start();
        include $contentFile;
        ob_end_clean();

        // Store as "blog:slug" to differentiate from regular pages
        $pageSlug = "blog:$postSlug";
        $pageTitle = $postTitle;
    } else {
        $pageSlug = "blog:404";
        $pageTitle = "404 - Blog Post Not Found";
    }
} else {
    // Standard Page Tracking
    $pageSlug = isset($_GET['p']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['p']) : 'home';
    $contentFile = __DIR__ . "/pages/{$pageSlug}.php";

    if (file_exists($contentFile)) {
        $pageTitle = 'Default Page Title';
        ob_start();
        include $contentFile;
        ob_end_clean();
    } else {
        $pageSlug = '404';
        $pageTitle = "404 - Page Not Found";
    }
}

// Capture additional data
$ip = $_SERVER['REMOTE_ADDR'];
$referrer = $_SERVER['HTTP_REFERER'] ?? 'Direct';
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$date = date("Y-m-d");

// Load existing analytics data
$analytics = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];

// Ensure the page entry exists in the analytics log
if (!isset($analytics[$pageSlug])) {
    $analytics[$pageSlug] = [
        'title' => $pageTitle,
        'views' => [],
        'unique_ips' => [],
        'referrers' => [],
        'user_agents' => []
    ];
}

// Record the visit
$analytics[$pageSlug]['views'][$date] = ($analytics[$pageSlug]['views'][$date] ?? 0) + 1;
$analytics[$pageSlug]['unique_ips'][$date][$ip] = true;
$analytics[$pageSlug]['referrers'][$date][$referrer] = ($analytics[$pageSlug]['referrers'][$date][$referrer] ?? 0) + 1;
$analytics[$pageSlug]['user_agents'][$date][$userAgent] = ($analytics[$pageSlug]['user_agents'][$date][$userAgent] ?? 0) + 1;

// Save back to file
file_put_contents($logFile, json_encode($analytics, JSON_PRETTY_PRINT | LOCK_EX));
?>
