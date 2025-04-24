<?php
// /config/addons/google_adsense.php

/**
 * Google AdSense Addon
 *
 * Conditionally injects Google AdSense based on settings.php.
 */

// Global include arrays
global $headerIncludes, $footerIncludes;

// Initialize if not already set
if (!isset($headerIncludes) || !is_array($headerIncludes)) $headerIncludes = [];
if (!isset($footerIncludes) || !is_array($footerIncludes)) $footerIncludes = [];

// Load settings
$settingsPath = dirname(__FILE__, 2) . '/settings.php';
if (file_exists($settingsPath)) {
    include_once $settingsPath;

    // Only proceed if AdSense is enabled and a valid ID is provided
    if (!empty($googleadsenseenabled) && $googleadsenseenabled === true &&
        isset($googleAdsenseID) && !empty($googleAdsenseID)) {

        $adsenseID = htmlspecialchars($googleAdsenseID);

        // Header: AdSense script
        $headerIncludes[] = <<<HTML
<!-- Google AdSense -->
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={$adsenseID}"
     crossorigin="anonymous"></script>
HTML;
    }
}
?>
