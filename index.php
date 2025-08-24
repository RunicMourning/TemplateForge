<?php
// index.php
// Include the configuration file.


if (session_status() === PHP_SESSION_NONE) session_start();
include __DIR__.'/admin/logger.php';

// Get common context
$requested_url = $_SERVER['REQUEST_URI'] ?? 'Unknown URL';
$referrer = $_SERVER['HTTP_REFERER'] ?? 'None';
$client_ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
$user_agent_full = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
$logged_in_user = isset($_SESSION['loggedin'], $_SESSION['username']) && $_SESSION['loggedin'] 
    ? $_SESSION['username'] 
    : 'Anonymous';

// Simplify User Agent
$user_agent_display = 'Unknown Browser/OS';
$agents = ['Firefox','Edge','Chrome','Safari','Opera','Bot/Crawler','Internet Explorer'];
foreach ($agents as $agent) {
    if (strpos($user_agent_full, $agent) !== false) {
        $user_agent_display = $agent;
        break;
    }
}

// Custom PHP error handler
set_error_handler(function($errno, $errstr, $errfile, $errline) use ($requested_url, $client_ip, $user_agent_full, $logged_in_user) {
    $message = "PHP Error [$errno] - Message: $errstr | File: $errfile on line $errline | URL: $requested_url | IP: $client_ip | UA: $user_agent_full | User: $logged_in_user";
    log_activity('PHP Error', $message);
    return false; // allow default handling
});

// Capture fatal errors
register_shutdown_function(function() use ($requested_url, $client_ip, $user_agent_full, $logged_in_user) {
    $error = error_get_last();
    if ($error !== null) {
        $message = "Fatal Error [{$error['type']}] - Message: {$error['message']} | File: {$error['file']} on line {$error['line']} | URL: $requested_url | IP: $client_ip | UA: $user_agent_full | User: $logged_in_user";
        log_activity('Fatal Error', $message);
    }
});

require_once __DIR__ . '/config/config.php';
include __DIR__ . '/track.php';


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
