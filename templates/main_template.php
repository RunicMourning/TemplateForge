<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($pageTitle); ?> - <?php echo htmlspecialchars($siteTitle); ?></title>
    <link rel="alternate" type="application/rss+xml" href="/rss.php" title="<?php echo htmlspecialchars($siteTitle); ?> RSS Feed" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cairo+Play:wght@200..1000&family=Nabla&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap">
    <link rel="stylesheet" href="css/style.css">

<!-- Output header includes -->
<?php
if (!empty($headerIncludes)) {
    echo implode("\n", $headerIncludes);
}
?>
</head>
<body>
    <div class="container-fluid mt-4">
    <!-- Header -->
    <header class="bg-light py-3 text-start p-2">
<svg width="350" height="90" viewBox="0 0 300 100" xmlns="http://www.w3.org/2000/svg">
  <g transform="scale(1.5) translate(10,10)">
    <path d="M28.394 7.296l-4.254-0.64 1.241-1.64-5.146-3.928-1.308 1.728c-2.13-1.258-4.321-1.802-5.939-1.636l0 0.001c-0.231 0.022-0.45 0.078-0.656 0.129-0.646 0.162-1.148 0.459-1.465 0.877l5.412 4.129-1.207 1.595 1.017 0.776c-5.472 6.732-11.742 12.501-15.269 20.272l3.1 2.346c6.645-5.773 10.208-12.942 15.337-20.201l0.962 0.734 1.241-1.64 1.773 3.919 2.659 2.011c3.267-1.373 4.722-3.803 5.163-6.824l-2.659-2.012z" fill="#B22222"/>
  </g>
  <text x="50" y="70" font-size="40" font-family="Cairo Play, sans-serif" font-weight="bold" fill="#808080"><?php echo htmlspecialchars($siteTitle); ?></text>
</svg>
    </header>
    
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php echo buildNavigation(); ?>
                </ul>
<div class="w-auto mx-2">
    <form class="input-group" action="search.html" method="GET">
        <input type="text" class="form-control" name="q" placeholder="Search..." aria-label="Search" aria-describedby="button-addon2">
        <button class="btn btn-success" type="submit" id="button-addon2"><i class="bi bi-search"></i></button>
    </form></div>
            </div>
        </div>
    </nav>
    
    <!-- Main Layout -->
        <div class="row">
            <!-- Sidebar -->
            <aside class="col-md-3 order-1 order-md-0">
<?php loadSidebars(); ?>
            </aside>
            
            <!-- Main Content -->
            <main class="col-md-9 order-0 order-md-1">
<?php echo $pageContent; ?>
            </main>
        </div>
    
<!-- Footer -->
<footer class="py-5 mt-4 p-4 border-top bg-dark text-white">
    <div class="container">
        <div class="row text-center text-md-start mb-3">
            <!-- Social Links -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Follow Us
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li>
                                <a href="#" class="d-flex align-items-center gap-2 text-decoration-none">
                                    <i class="bi bi-linkedin me-2"></i>LinkedIn
                                </a>
                            </li>
                            <li>
                                <a href="#" class="d-flex align-items-center gap-2 text-decoration-none" title="Follow me on Bluesky" target="_blank">
                                    <i class="bi bi-bluesky me-2"></i>Bluesky
                                </a>
                            </li>
                            <li>
                                <a href="#" class="d-flex align-items-center gap-2 text-decoration-none">
                                    <i class="bi bi-instagram me-2"></i>Instagram
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Legal Links -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Legal
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li>
                                <a href="#" class="d-block mb-2 text-decoration-none">
                                    <i class="bi bi-file-earmark-text me-2"></i>Terms & Conditions
                                </a>
                            </li>
                            <li>
                                <a href="privacy.html" class="d-block mb-2 text-decoration-none">
                                    <i class="bi bi-shield-lock me-2"></i>Privacy Policy
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Contact Us
                    </div>
                    <div class="card-body">
<address>
                        <ul class="list-unstyled">
                            <li class="mb-1">
                                <i class="bi bi-envelope-at-fill me-2"></i><a href="mailto:contact@mywebsite.com" class="text-decoration-none">contact@mywebsite.com</a>
                            </li>
                            <li class="mb-1">
                                <i class="bi bi-telephone me-2"></i><a href="tel:+11234567890" class="text-decoration-none">(123) 456-7890</a>
                            </li>
                            <li class="mb-0">
                                <i class="bi bi-car-front-fill me-2"></i>123 Business Ave, Suite 456, City, State, 78901</a>
                            </li>
                        </ul>
</address>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Copyright -->
        <div class="text-start mt-4">
            <p>&copy; 2025 <span class="fw-bold"><?php echo htmlspecialchars($siteTitle); ?></span>. All rights reserved.</p>
        </div>
    </div>
</footer>


    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>





<!-- Output header includes -->
<?php
if (!empty($footerIncludes)) {
    echo implode("\n", $footerIncludes);
}
?>
</body>
</html>