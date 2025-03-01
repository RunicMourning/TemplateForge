<?php
// config/config.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Define the title of the website.
// This variable can be used in the HTML <title> tag or header sections to represent your site's title.
$siteTitle = "Nothing special";

// Define the name of the website.
// This value may be displayed in branding elements, such as logos or site headers.
$siteName  = "Your Website Name";

// ------------------------
// Navigation Configuration
// ------------------------

// Control whether the horizontal navigation menu is enabled.
// Set to false to disable the horizontal navigation bar across the site.
$enableHorizontalNav = true;

// Control whether the vertical navigation menu is enabled.
// Set to true to display a vertical navigation bar, typically used in sidebar menus.
$enableVerticalNav   = true;

// Specify the CSS class to be applied to the active navigation link.
// This class helps visually highlight the current page or section in the navigation.
$activeNavClass      = 'active';

$navItemClass = "nav-item";
$navLinkClass = "nav-link";

$enableHorizontalNavbar = true; // Enable the horizontal navbar
$navbarFilePath = __DIR__ . '/navbar.txt'; // Path to the text file containing the navbar links
$activeNavbarClass = 'active'; // Class for the active navbar link
$page = 'home'; // Current page (this should match the URLs in the navbar)
//---------------------------------------------------------------------------------------------------------------------------------

// Initialize arrays for header and body includes
$headerIncludes = [];
$footerIncludes = [];

// Define the path to the navigation text file.
// In this example, the file is located in the /config directory.
$navFilePath = __DIR__ . '/navigation.txt';

// Automatically include add-on files from the addons directory.
// This allows you to simply add new PHP files (e.g., nav.php) into the addons folder,
// and they will be automatically loaded without any additional configuration.
$addonsPath = __DIR__ . '/addons';

// Check if the addons directory exists and is readable.
if (is_dir($addonsPath)) {
    // Use glob to find all PHP files in the addons directory.
    foreach (glob($addonsPath . '/*.php') as $addonFile) {
        include_once $addonFile;
    }
}



function loadSidebars($dir = null) {
    // If no directory is provided, assume sidebars is in the project root
    if ($dir === null) {
        // __DIR__ returns the path to the config/ folder.
        // '/../sidebars/' navigates up one level and into the sidebars folder.
        $dir = __DIR__ . '/../sidebars/';
    }

    // Verify the directory exists and is readable.
    if (!is_dir($dir) || !is_readable($dir)) {
        error_log("Error: Directory '{$dir}' not found or not readable.");
        return false;
    }

    $files = [];

    try {
        $iterator = new DirectoryIterator($dir);
        foreach ($iterator as $fileinfo) {
            // Ensure the item is a file and has a '.php' extension.
            if ($fileinfo->isFile() && strtolower($fileinfo->getExtension()) === 'php') {
                $files[] = $fileinfo->getFilename();
            }
        }

        if (empty($files)) {
            error_log("Notice: No PHP sidebar files found in '{$dir}'.");
            return false;
        }

        // Sort the files naturally (e.g., 01zoo.php before 04about.php)
        sort($files, SORT_NATURAL);

        // Include each sidebar file in sorted order
        foreach ($files as $file) {
            include_once $dir . $file;
        }
    } catch (Exception $e) {
        error_log("Exception while scanning directory '{$dir}': " . $e->getMessage());
        return false;
    }

    return true;
}




function generateBreadcrumbs() {
    $breadcrumbs = array();

    // Always add the Home breadcrumb.
    $breadcrumbs[] = array("title" => "Home", "link" => "index.php");

    // Check if the main page parameter is set.
    if (isset($_GET['p'])) {
        $page = $_GET['p'];
        
        // For the blog page.
        if ($page === 'blog') {
            $breadcrumbs[] = array("title" => "Blog", "link" => "index.php?p=blog");
            if (isset($_GET['post']) && !empty($_GET['post'])) {
                // For an individual blog post, add the post slug (without a link for the active item).
                $postSlug = $_GET['post'];
                $breadcrumbs[] = array("title" => htmlspecialchars($postSlug), "link" => "");
            }
        }
        // For the search page.
        elseif ($page === 'search') {
            $breadcrumbs[] = array("title" => "Search", "link" => "index.php?p=search");
            if (isset($_GET['q']) && !empty($_GET['q'])) {
                $searchTerm = $_GET['q'];
                $breadcrumbs[] = array("title" => htmlspecialchars($searchTerm), "link" => "");
            }
        }
        // For any other page, simply add its name.
        else {
            $breadcrumbs[] = array("title" => htmlspecialchars($page), "link" => "");
        }
    }

    // Build the breadcrumb HTML using Bootstrap 5.3 markup.
    $html = '<nav aria-label="breadcrumb mt-3"><ol class="breadcrumb">';
    $lastIndex = count($breadcrumbs) - 1;
    foreach ($breadcrumbs as $index => $crumb) {
        if ($index === $lastIndex || empty($crumb["link"])) {
            // The last breadcrumb or an item without a link is active.
            $html .= '<li class="breadcrumb-item active" aria-current="page">' . $crumb["title"] . '</li>';
        } else {
            $html .= '<li class="breadcrumb-item"><a href="' . $crumb["link"] . '">' . $crumb["title"] . '</a></li>';
        }
    }
    $html .= '</ol></nav>';
    return $html;
}


?>
