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
<!-- Cookie Consent Modal -->
<div id="cookieConsentModal" class="modal fade show text-dark" tabindex="-1" style="display: none; position: fixed; bottom: 0; width: 100%; background: rgba(0, 0, 0, 0.85); color: white;">
  <div class="modal-dialog modal-sm" style="margin: auto; max-width: 350px;">
    <div class="modal-content text-center p-3">
      <p class="mb-2">
        This site uses cookies to enhance your experience. 
        <a href="privacy.html" class="text-dark text-decoration-underline">Learn more</a>.
      </p>
      <button id="acceptCookies" class="btn btn-primary btn-sm">Got it!</button>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    if (!localStorage.getItem("cookieConsent")) {
        let modal = document.getElementById("cookieConsentModal");
        modal.style.display = "block"; // Show the modal

        document.getElementById("acceptCookies").addEventListener("click", function () {
            localStorage.setItem("cookieConsent", "true");
            modal.style.display = "none"; // Hide the modal
        });
    }
});
</script>
HTML;
?>
