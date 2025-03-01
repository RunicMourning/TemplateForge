<?php
// /config/addons/sampleaddon.php

/**
 * Sample Addon
 *
 * This addon demonstrates how to add custom content into the header and footer sections.
 * - Content in $headerIncludes will be inserted between the <head> tags.
 * - Content in $footerIncludes will be inserted just before the closing </body> tag.
 * These variables can also be used in pages if there's javascript or style sheets that are only for that page. 
 */

// Ensure the global arrays are available.
global $headerIncludes, $footerIncludes;

// Initialize the arrays if they are not already set.
if (!isset($headerIncludes) || !is_array($headerIncludes)) {
    $headerIncludes = [];
}
if (!isset($footerIncludes) || !is_array($footerIncludes)) {
    $footerIncludes = [];
}

// Add sample content to the header and footer.
$headerIncludes[] = '<!-- Sample Addon: This is a sample addon. Content added to the &lt;head&gt; section via sampleaddon.php -->';
$footerIncludes[] = '<!-- Sample Addon: This is a sample addon. Content added to the footer section (before &lt;/body&gt;) via sampleaddon.php -->';
?>
