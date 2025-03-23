<?php
// /config/addons/cookiemodal.php

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
$footerIncludes[] = <<<HTML
<!-- Cookie Consent Banner -->
<div id="cookieConsentModal" style="position: fixed; bottom: 0; left: 0; width: 100%; background: rgba(0, 0, 0, 0.85); color: white; visibility: hidden; opacity: 0; transition: opacity 0.3s ease-in-out, visibility 0.3s; padding: 15px 20px; text-align: center; z-index: 1050;">
  <div style="max-width: 600px; margin: auto; display: flex; justify-content: space-between; align-items: center; gap: 15px;">
    <p class="mb-0" style="flex: 1;">
      We use essential cookies to ensure our site functions properly. With your consent, we also use non-essential cookies to enhance your experience and analyze website traffic. By clicking "Accept," you agree to our use of cookies as outlined in our <a href="privacy.html" class="text-white text-decoration-underline">privacy policy</a>.
    </p>
    <button id="acceptCookies" class="btn btn-primary btn-sm" aria-label="Accept Cookies" style="cursor: pointer;">Accept</button>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    if (!localStorage.getItem("cookieConsent")) {
        let modal = document.getElementById("cookieConsentModal");
        modal.style.visibility = "visible";
        modal.style.opacity = "1"; // Smooth fade-in effect

        document.getElementById("acceptCookies").addEventListener("click", function () {
            localStorage.setItem("cookieConsent", "true");
            modal.style.opacity = "0"; // Smooth fade-out effect
            setTimeout(() => modal.style.visibility = "hidden", 300);
        });
    }
});
</script>
HTML;


?>
