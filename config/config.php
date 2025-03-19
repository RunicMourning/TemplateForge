<?php
// ==========================
// Configuration & Initialization
// ==========================

// Enable error reporting for debugging during development
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include site-wide settings
include_once 'settings.php';

// Initialize header and footer includes
$headerIncludes = [];
$footerIncludes = [];

// Default page if no specific page is requested
$page = 'home';

// ==========================
// Load Add-ons
// ==========================

$addonsPath = __DIR__ . '/addons';
if (is_dir($addonsPath)) {
    foreach (glob($addonsPath . '/*.php') as $addonFile) {
        include_once $addonFile;
    }
}

// Path to navigation file
$navFilePath = __DIR__ . '/navigation.txt';

// ==========================
// Sidebar Loader Function
// ==========================

/**
 * Loads and includes sidebar files from a given directory.
 * 
 * @param string|null $dir Path to the sidebar directory (default: /sidebars/)
 * @return bool True on success, false on failure.
 */
function loadSidebars($dir = null) {
    $dir = $dir ?? __DIR__ . '/../sidebars/';

    if (!is_dir($dir) || !is_readable($dir)) {
        error_log("Error: Sidebar directory '{$dir}' not found or not readable.");
        return false;
    }

    $files = [];
    try {
        // Iterate through sidebar files
        $iterator = new DirectoryIterator($dir);
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isFile() && strtolower($fileinfo->getExtension()) === 'php') {
                $files[] = $fileinfo->getFilename();
            }
        }

        // Log if no sidebar files are found
        if (empty($files)) {
            error_log("Notice: No PHP sidebar files found in '{$dir}'.");
            return false;
        }

        // Sort sidebar files naturally (e.g., 01_menu.php before 04_about.php)
        sort($files, SORT_NATURAL);

        // Include each sidebar file
        foreach ($files as $file) {
            include_once $dir . $file;
        }
    } catch (Exception $e) {
        error_log("Exception while loading sidebars from '{$dir}': " . $e->getMessage());
        return false;
    }

    return true;
}

// ==========================
// Breadcrumb Navigation
// ==========================

/**
 * Generates Bootstrap-compatible breadcrumb navigation.
 * 
 * @return string HTML output for breadcrumbs.
 */
function generateBreadcrumbs() {
    $breadcrumbs = [
        ["title" => "Home", "link" => "index.php"]
    ];

    // Capture the current page and generate breadcrumbs dynamically
    if (isset($_GET['p'])) {
        $page = $_GET['p'];
        handlePageBreadcrumbs($breadcrumbs, $page);
    }

    return buildBreadcrumbHtml($breadcrumbs);
}

/**
 * Determines and structures the breadcrumb path based on the requested page.
 * 
 * @param array &$breadcrumbs The breadcrumbs array to modify.
 * @param string $page The current page identifier.
 */
function handlePageBreadcrumbs(array &$breadcrumbs, string $page) {
    if ($page === 'blog') {
        $breadcrumbs[] = ["title" => "Blog", "link" => "index.php?p=blog"];
        
        if (isset($_GET['post']) && !empty($_GET['post'])) {
            $postSlug = htmlspecialchars($_GET['post']);

            // Retrieve the actual post title
            $postTitle = getPostTitle($postSlug);

            // Add post title (fallback to slug if title is missing)
            $breadcrumbs[] = ["title" => $postTitle ?: $postSlug, "link" => ""];
        }
    } elseif ($page === 'search') {
        $breadcrumbs[] = ["title" => "Search", "link" => "index.php?p=search"];
        
        if (isset($_GET['q']) && !empty($_GET['q'])) {
            $searchTerm = htmlspecialchars($_GET['q']);
            $breadcrumbs[] = ["title" => $searchTerm, "link" => ""];
        }
    } else {
        // Generic breadcrumb handling for other pages
        $breadcrumbs[] = ["title" => ucfirst(htmlspecialchars($page)), "link" => ""];
    }
}

/**
 * Builds the final breadcrumb HTML structure.
 * 
 * @param array $breadcrumbs The breadcrumbs array to convert into HTML.
 * @return string The formatted breadcrumb navigation HTML.
 */
function buildBreadcrumbHtml(array $breadcrumbs) {
    $html = '<nav aria-label="breadcrumb"><ol class="breadcrumb mt-3">';
    $lastIndex = count($breadcrumbs) - 1;

    foreach ($breadcrumbs as $index => $crumb) {
        if ($index === $lastIndex || empty($crumb["link"])) {
            $html .= '<li class="breadcrumb-item active" aria-current="page">' . $crumb["title"] . '</li>';
        } else {
            $html .= '<li class="breadcrumb-item"><a href="' . $crumb["link"] . '">' . $crumb["title"] . '</a></li>';
        }
    }

    $html .= '</ol></nav>';
    return $html;
}

// ==========================
// Post Title Retrieval
// ==========================

/**
 * Retrieves the actual title of a blog post using its slug.
 *
 * @param string $slug The post slug.
 * @return string|null The post title or null if unavailable.
 */
function getPostTitle(string $slug): ?string {
    $blogPostsDir = __DIR__ . '/../blog_posts/';
    $postFile = $blogPostsDir . $slug . '.php';

    // Ensure the post file exists
    if (!file_exists($postFile)) {
        return null;
    }

    // Capture post title from the blog post file
    ob_start();
    include $postFile;
    ob_end_clean();

    // Check if $postTitle is set within the included file
    return isset($postTitle) ? $postTitle : null;
}

// ==========================
// Site URL Construction
// ==========================

// Determine the protocol (HTTP or HTTPS)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';

// Construct the full site URL
$host = $_SERVER['HTTP_HOST'];
$siteUrl = $protocol . '://' . $host;

?>
