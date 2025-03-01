<?php
// config/addons/navbar.php

/**
 * Build horizontal navbar from a text file.
 *
 * @return string HTML for the horizontal navbar.
 */
function buildNavbar() {
    // Access the navbar configuration variables.
    global $enableHorizontalNavbar, $activeNavbarClass, $navbarFilePath, $page;
    
    // Check if horizontal navbar is enabled.
    if (!$enableHorizontalNavbar) {
        return '';  // Return an empty string if disabled.
    }
    
    // Ensure the navbar file exists.
    if (!file_exists($navbarFilePath)) {
        return '';  // File not found.
    }
    
    // Read the file into an array of lines.
    $lines = file($navbarFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!$lines) {
        return '';
    }
    
    // Define the CSS class for the unordered list.
    $navbarClass = 'mainNavbar';
    $html = "<div class=\"$navbarClass\">\n";
    
    // Left section for nav links
    $html .= "<ul class=\"navbar-nav-left\">\n";
    $customElements = ''; // To store custom elements (e.g., search, social media)
    
    // Process each line from the file.
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) {
            continue;
        }

        // Handle special custom elements (e.g., search or social media).
        if (strpos($line, '@search') === 0) {
            $customElements .= '<li class="navbar-search"><form action="search.html" method="GET"><input type="text" name="q" placeholder="Search..."><button type="submit">Go</button></form></li>';
            continue; // Skip this line, as it is custom HTML.
        }
        
        if (strpos($line, '@social') === 0) {
            // Expect the format: @social|name|url|color|icon
            $parts = explode('|', $line);
            if (count($parts) == 5) {
                list($prefix, $name, $url, $color, $icon) = $parts;
                $customElements .= "<li class=\"navbar-social\"><a href=\"$url\" style=\"color: $color;\">$icon $name</a></li>";
            }
            continue; // Skip this line as it is custom HTML.
        }

        // Standard link processing
        if (strpos($line, '|') === false) {
            continue;
        }

        // Split the line into title and URL.
        list($linkTitle, $linkUrl) = explode('|', $line, 2);
        $linkTitle = trim($linkTitle);
        $linkUrl   = trim($linkUrl);
        
        // Determine if this link corresponds to the current page.
        // Here, we assume $page holds the current page identifier (e.g., 'home', 'about').
        $activeClass = ($linkUrl === $page) ? ' ' . $activeNavbarClass : '';
        
        // Build the list item with the active class if applicable.
        $html .= '<li' . ($activeClass ? ' class="' . htmlspecialchars(trim($activeClass)) . '"' : '') . '>';
        $html .= '<a href="' . htmlspecialchars($linkUrl) . '">';
        $html .= htmlspecialchars($linkTitle);
        $html .= '</a>';
        $html .= "</li>\n";
    }

    $html .= "</ul>\n";
    
    // Right section for custom elements (search, social media)
    if (!empty($customElements)) {
        $html .= "<ul class=\"navbar-custom-elements\">\n";
        $html .= $customElements;
        $html .= "</ul>\n";
    }

    $html .= "</div>\n";
    
    return $html;
}
?>
