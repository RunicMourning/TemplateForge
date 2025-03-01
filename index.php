<?php
// index.php
// Include the configuration file.
require_once __DIR__ . '/config/config.php';


// Retrieve the 'p' parameter from the URL; default to 'home' if not provided.
$page = isset($_GET['p']) ? $_GET['p'] : 'home';

// Sanitize the page parameter to allow only letters, numbers, underscores, and hyphens.
$page = preg_replace('/[^a-zA-Z0-9_-]/', '', $page);

// Determine the path to the content file.
$contentFile = __DIR__ . '/pages/' . $page . '.php';

// If the file does not exist, send a 404 header and load the 404 page.
if (!file_exists($contentFile)) {
    header("HTTP/1.0 404 Not Found");
    // Optionally update the page variable.
    $page = '404';
    $contentFile = __DIR__ . '/pages/404.php';
    
    // If the dedicated 404 file doesn't exist, exit with a default error message.
    if (!file_exists($contentFile)) {
        exit('404 - Page Not Found');
    }
}

// Start output buffering, include the content file, and capture its output.
ob_start();
include $contentFile;
$pageContent = ob_get_clean();


// If the content file has not defined a page title, use a default.
if (!isset($pageTitle) || empty($pageTitle)) {
    $pageTitle = 'Default Page Title';
}

// Include the main template, which will use $pageTitle and display $pageContent.
include __DIR__ . '/templates/main_template.php';
?>
