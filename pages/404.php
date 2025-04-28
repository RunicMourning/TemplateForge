<?php
// pages/404.php

$pageTitle = 'Error 404: Page Not Found!';
$headerIncludes[] = <<<HTML

HTML;
$footerIncludes[] = "";

include __DIR__.'/../admin/logger.php'; // Adjust path as needed

$requested_url = $_SERVER['REQUEST_URI'] ?? 'Unknown URL';
log_activity('404 Not Found', 'Requested URL: ' . $requested_url);
?>

<div class="container py-3">
    <div class="card p-4 shadow-sm border-0 rounded-4">
        
        <!-- Search Form at Top -->
        <div class="card-header bg-transparent border-0 text-center mb-4">
            <form class="input-group input-group-lg" action="search.html" method="GET">
                <input type="text" class="form-control rounded-start" name="q" placeholder="Search for a page..." aria-label="Search" aria-describedby="button-addon2">
                <button class="btn btn-danger rounded-end" type="submit" id="button-addon2">Go</button>
            </form>
        </div>

        <!-- Card Body Content -->
        <div class="card-body">
            <div class="row g-5 align-items-center">

                <!-- Left Side: Main Error Message -->
                <div class="col-lg-7">
                    <h1 class="display-1 fw-bold text-danger mb-3">404</h1>
                    <h2 class="h3 mb-3">Page Not Found</h2>
                    <p class="text-muted mb-4">We couldn't find the page you were looking for. It might have been moved, deleted, or never existed.</p>

                    <div class="d-grid gap-2 d-md-flex">
                        <a href="index.html" class="btn btn-primary btn-lg rounded-pill">Return Home</a>
                        <a href="sitemap.html" class="btn btn-outline-primary btn-lg rounded-pill">View Sitemap</a>
                    </div>
                </div>

                <!-- Right Side: Technical Info -->
                <div class="col-lg-5">
                    <div class="bg-light rounded-4 p-4 nerd-info">
                        <h5 class="mb-3 text-muted">Technical Details</h5>
                        <ul class="list-unstyled small text-muted">
                            <li><strong>Requested URL:</strong> <?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?></li>
                            <li><strong>Timestamp:</strong> <?php echo date('Y-m-d H:i:s'); ?></li>
                            <li><strong>File:</strong> [Hidden for Security]</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
        
    </div>
</div>
