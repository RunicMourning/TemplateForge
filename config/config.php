<?php
// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Include settings from the settings file
include_once 'settings.php';

// ==========================
// Add-ons & Includes
// ==========================
$headerIncludes = [];
$footerIncludes = [];

$page = 'home'; // Default page


$addonsPath = __DIR__ . '/addons';
if (is_dir($addonsPath)) {
    foreach (glob($addonsPath . '/*.php') as $addonFile) {
        include_once $addonFile;
    }
}

$navFilePath = __DIR__ . '/navigation.txt';

// ==========================
// Sidebar Loader Function
// ==========================
/**
 * Loads sidebar files from a given directory.
 * 
 * @param string|null $dir Path to the sidebar directory (default: /sidebars/)
 * @return bool True on success, false on failure.
 */
function loadSidebars($dir = null) {
    $dir = $dir ?? __DIR__ . '/../sidebars/';

    if (!is_dir($dir) || !is_readable($dir)) {
        error_log("Error: Directory '{$dir}' not found or not readable.");
        return false;
    }

    $files = [];
    try {
        $iterator = new DirectoryIterator($dir);
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isFile() && strtolower($fileinfo->getExtension()) === 'php') {
                $files[] = $fileinfo->getFilename();
            }
        }

        if (empty($files)) {
            error_log("Notice: No PHP sidebar files found in '{$dir}'.");
            return false;
        }

        // Sort the sidebar files naturally (e.g., 01_menu.php before 04_about.php)
        sort($files, SORT_NATURAL);

        foreach ($files as $file) {
            include_once $dir . $file;
        }
    } catch (Exception $e) {
        error_log("Exception while scanning directory '{$dir}': " . $e->getMessage());
        return false;
    }

    return true;
}

// ==========================
// Breadcrumb Generator Function
// ==========================
/**
 * Generates breadcrumb navigation HTML.
 * 
 * @return string The generated breadcrumb HTML (Bootstrap 5.3 compatible).
 */
function generateBreadcrumbs() {
    $breadcrumbs = [
        ["title" => "Home", "link" => "index.php"]
    ];

    // Capture the current page and append breadcrumbs accordingly
    if (isset($_GET['p'])) {
        $page = $_GET['p'];
        handlePageBreadcrumbs($breadcrumbs, $page);
    }

    return buildBreadcrumbHtml($breadcrumbs);
}

/**
 * Handles the breadcrumbs for specific pages.
 * 
 * @param array &$breadcrumbs The breadcrumbs array to modify.
 * @param string $page The current page identifier.
 */
function handlePageBreadcrumbs(array &$breadcrumbs, string $page) {
    if ($page === 'blog') {
        $breadcrumbs[] = ["title" => "Blog", "link" => "index.php?p=blog"];
        if (isset($_GET['post']) && !empty($_GET['post'])) {
            $postSlug = htmlspecialchars($_GET['post']);
            $breadcrumbs[] = ["title" => $postSlug, "link" => ""];
        }
    } elseif ($page === 'search') {
        $breadcrumbs[] = ["title" => "Search", "link" => "index.php?p=search"];
        if (isset($_GET['q']) && !empty($_GET['q'])) {
            $searchTerm = htmlspecialchars($_GET['q']);
            $breadcrumbs[] = ["title" => $searchTerm, "link" => ""];
        }
    } else {
        $breadcrumbs[] = ["title" => htmlspecialchars($page), "link" => ""];
    }
}

/**
 * Builds breadcrumb HTML from an array of breadcrumbs.
 * 
 * @param array $breadcrumbs The breadcrumbs array to build HTML from.
 * @return string The generated breadcrumb HTML.
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
// Site URL Construction
// ==========================
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$siteUrl = $protocol . '://' . $host;

?>
