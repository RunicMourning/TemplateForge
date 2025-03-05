<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($pageTitle); ?> - <?php echo htmlspecialchars($siteTitle); ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cairo+Play:wght@200..1000&family=Nabla&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- Output header includes -->
<?php
if (!empty($headerIncludes)) {
    echo implode("\n", $headerIncludes);
}
?>
<link rel="alternate" type="application/rss+xml" href="/rss.php" title="<?php echo htmlspecialchars($siteTitle); ?> RSS Feed" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid mt-4">
    <!-- Header -->
    <header class="bg-light py-3 text-start p-2">
        <h1><?php echo htmlspecialchars($siteTitle); ?></h1>
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
        <button class="btn btn-success" type="submit" id="button-addon2">Go</button>
    </form></div>
            </div>
        </div>
    </nav>
    
    <!-- Main Layout -->
        <div class="row">
            <!-- Sidebar -->
            <aside class="col-md-3">
<?php loadSidebars(); ?>
            </aside>
            
            <!-- Main Content -->
            <main class="col-md-9">
<?php echo $pageContent; ?>
            </main>
        </div>
    
    <!-- Footer -->
<footer class="py-5 mt-4 p-4 border-top bg-dark text-white">
    <div class="container">
        <div class="row text-center text-md-start">
            <!-- Social Links -->
            <div class="col-md-4 mb-3">
                <h5 class="text-uppercase mb-3">Follow Us</h5>
                <a href="#" class="d-block text-white mb-2">
                    <i class="bi bi-facebook me-2"></i>Facebook
                </a>
                <a href="#" class="d-block text-white mb-2">
                    <i class="bi bi-twitter me-2"></i>Bluesky
                </a>
                <a href="#" class="d-block text-white mb-2">
                    <i class="bi bi-instagram me-2"></i>Instagram
                </a>
            </div>
            
            <!-- Legal Links -->
            <div class="col-md-4 mb-3">
                <h5 class="text-uppercase mb-3">Legal</h5>
                <a href="#" class="d-block text-white mb-2">Terms & Conditions</a>
                <a href="privacy.html" class="d-block text-white mb-2">Privacy Policy</a>
            </div>
            
            <!-- Contact Information -->
            <div class="col-md-4 mb-3">
                <h5 class="text-uppercase mb-3">Contact Us</h5>
                <p class="mb-1"><strong>Email:</strong> <a href="mailto:contact@mywebsite.com" class="text-white">contact@mywebsite.com</a></p>
                <p class="mb-0"><strong>Phone:</strong> <a href="tel:+11234567890" class="text-white">(123) 456-7890</a></p>
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