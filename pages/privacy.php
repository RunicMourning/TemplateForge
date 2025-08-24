<?php
// pages/privacy.php

$pageTitle = 'Privacy Policy – Your Data & Security';
?>
<!-- Privacy Policy Header --> 
<section class="container py-5 text-center">
    <h1 class="display-3 fw-bold mb-4">Privacy Policy</h1>
    <p class="lead text-muted mb-5">Effective Date: March 3, 2025</p>
</section>
 
<!-- Privacy Policy Content Section -->
<section class="container pb-5">
    <p>At <strong><?php echo htmlspecialchars($siteTitle); ?></strong>, we are committed to protecting your privacy and ensuring the security of any information you choose to share with us. This Privacy Policy outlines our practices regarding the collection, use, and protection of your information.</p>

    <div class="mb-4">
        <h2 class="text-primary">1. Information We Collect</h2>
        <p>We collect personal information only when you voluntarily submit it through our contact form. This information may include:</p>
        <ul class="list-unstyled">
            <li><strong>Name</strong></li>
            <li><strong>Email Address</strong></li>
        </ul>
        <p>The information you provide is solely used to respond to your specific inquiries or requests submitted via the contact form. We do not share, sell, or otherwise distribute this information to external third parties.</p>
    </div>

    <div class="mb-4">
        <h2 class="text-primary">2. Use of Cookies</h2>
        <p>Our website utilizes cookies to enhance user experience. These small text files are stored on your device to help us remember your preferences and improve site functionality. You have the option to disable cookies through your browser settings, but please be aware that this may impact certain features and the overall experience of the website.</p>
    </div>

    <div class="mb-4">
        <h2 class="text-primary">3. Third-Party Services</h2>
        <?php if (!empty($googleadsenseenabled)) : ?>
            <p>This website uses Google AdSense, a third-party advertising service, to display advertisements. Google may utilize cookies or web beacons to collect non-personally identifiable information in the process of serving ads. These cookies enable Google and its advertising partners to deliver ads based on your browsing activity on this and other websites. You can manage your ad preferences or opt out of personalized advertising by visiting the <a href="https://www.google.com/settings/ads" target="_blank" rel="noopener noreferrer">Google Ad Settings</a> page.</p>
        <?php else : ?>
            <p>We do not currently employ third-party services, such as analytics or advertising platforms, that track or collect your personal data.</p>
        <?php endif; ?>
    </div>

    <div class="mb-4">
        <h2 class="text-primary">4. Data Retention</h2>
        <p>We retain the information submitted through our contact form only for as long as necessary to fulfill the purpose for which it was collected (i.e., to respond to your inquiries) and for a reasonable period thereafter for record-keeping purposes.</p>
    </div>

    <div class="mb-4">
        <h2 class="text-primary">5. Your Rights and Controls</h2>
        <p>Given the limited personal data we collect, your primary control is the decision of whether or not to provide information through the contact form. If you have submitted information through the contact form and wish to have it reviewed, modified, or removed from our records, please contact us using the information provided below.</p>
    </div>

    <div class="mb-4">
        <h2 class="text-primary">6. Data Security</h2>
        <p>While we do not store sensitive personal data beyond the information submitted through the contact form, we implement reasonable technical and organizational measures to protect the information you provide from unauthorized access, use, or disclosure.</p>
    </div>

    <div class="mb-4">
        <h2 class="text-primary">7. Compliance with Data Protection Principles</h2>
        <p>Although our data collection practices are minimal and may not fall directly under comprehensive data protection regulations like GDPR or CCPA, we are committed to upholding principles of data privacy and handling any information responsibly and with respect for your privacy.</p>
    </div>

    <div class="mb-4">
        <h2 class="text-primary">8. Contact Us</h2>
        <p>If you have any questions, concerns, or requests regarding this Privacy Policy or the information you have provided, please do not hesitate to contact us at <a href="mailto:[Your Email Address]">[Your Email Address]</a> or through our <a href="/contact.html">Contact Page</a>.</p>
    </div>

    <div class="mb-4">
        <h2 class="text-primary">9. Updates to This Privacy Policy</h2>
        <p>We may update this Privacy Policy from time to time. Any changes will be posted on this page with a revised effective date. We encourage you to review this Privacy Policy periodically to stay informed about our privacy practices.</p>
    </div>
</section>

<!-- Footer Section -->
<section class="bg-light py-4 text-center">
    <p class="text-muted">© <?php echo date('Y'); ?> <?php echo htmlspecialchars($siteTitle); ?>. All rights reserved.</p>
</section>
