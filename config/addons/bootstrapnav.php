<?php
// config/addons/nav.php

/**
 * Build horizontal navigation menu.
 *
 * @return string HTML for the navigation menu.
 */
function buildNavigation() {
    // Access the navigation configuration variables.
    global $enableHorizontalNav, $activeNavClass, $navFilePath, $page, $navItemClass, $navLinkClass;
    
    // Check if horizontal navigation is enabled.
    if (!$enableHorizontalNav) {
        return '';  // Return an empty string if disabled.
    }
    
    // Ensure the navigation file exists.
    if (!file_exists($navFilePath)) {
        return '';  // File not found.
    }
    
    // Read the file into an array of lines.
    $lines = file($navFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!$lines) {
        return '';
    }
    
    $html = "\n";
    
    // Process each line from the file.
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '|') === false) {
            continue;
        }
        
        // Split the line into title and URL.
        list($linkTitle, $linkUrl) = explode('|', $line, 2);
        $linkTitle = trim($linkTitle);
        $linkUrl   = trim($linkUrl);
        
        // Determine if this link corresponds to the current page.
        $activeClass = ($linkUrl === $page) ? trim($activeNavClass) : '';
        // Combine the default navigation item class with the active class, if applicable.
        $combinedLiClass = trim($navItemClass . ' ' . $activeClass);
        $liClassAttribute = $combinedLiClass ? ' class="' . htmlspecialchars($combinedLiClass) . '"' : '';
        // Build the anchor tag with its configured class.
        $aClassAttribute = $navLinkClass ? ' class="' . htmlspecialchars($navLinkClass) . '"' : '';
        
        // Build the list item.
        $html .= "<li{$liClassAttribute}>";
        $html .= "<a href=\"" . htmlspecialchars($linkUrl) . "\"{$aClassAttribute}>";
        $html .= $linkTitle;
        $html .= "</a></li>\n";
    }
    
    $html .= "\n";
    
    return $html;
}
?>