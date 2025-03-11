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
        <button class="btn btn-success" type="submit" id="button-addon2"><i class="bi bi-search"></i></button>
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
<ul class="list-unstyled">
  <li>
    <a href="#" class="flex items-center gap-1 text-white text-decoration-none">
      <i class="bi bi-facebook me-2"></i>Facebook
    </a>
  </li>
  <li>
    <a href="#" class="flex items-center gap-1 text-white text-decoration-none" title="Follow me on Bluesky" target="_blank">
      <svg width="18" height="18" viewBox="0 0 600 530" xmlns="http://www.w3.org/2000/svg" class="me-2">
        <path d="m135.72 44.03c66.496 49.921 138.02 151.14 164.28 205.46 26.262-54.316 97.782-155.54 164.28-205.46 47.98-36.021 125.72-63.892 125.72 24.795 0 17.712-10.155 148.79-16.111 170.07-20.703 73.984-96.144 92.854-163.25 81.433 117.3 19.964 147.14 86.092 82.697 152.22-122.39 125.59-175.91-31.511-189.63-71.766-2.514-7.3797-3.6904-10.832-3.7077-7.8964-0.0174-2.9357-1.1937 0.51669-3.7077 7.8964-13.714 40.255-67.233 197.36-189.63 71.766-64.444-66.128-34.605-132.26 82.697-152.22-67.108 11.421-142.55-7.4491-163.25-81.433-5.9562-21.282-16.111-152.36-16.111-170.07 0-88.687 77.742-60.816 125.72-24.795z" fill="currentColor"/>
      </svg>Bluesky
    </a>
  </li>
  <li>
    <a href="#" class="flex items-center gap-1 text-white text-decoration-none">
      <i class="bi bi-instagram me-2"></i>Instagram
    </a>
  </li>
</ul>
            </div>
            
            <!-- Legal Links -->
            <div class="col-md-4 mb-3">
                <h5 class="text-uppercase mb-3">Legal</h5>
                <a href="#" class="d-block text-white mb-2 text-decoration-none">Terms & Conditions</a>
                <a href="privacy.html" class="d-block text-white mb-2 text-decoration-none">Privacy Policy</a>
            </div>
            
            <!-- Contact Information -->
            <div class="col-md-4 mb-3">
                <h5 class="text-uppercase mb-3">Contact Us</h5>
                <p class="mb-1"><strong>Email:</strong> <a href="mailto:contact@mywebsite.com" class="text-white text-decoration-none">contact@mywebsite.com</a></p>
                <p class="mb-0"><strong>Phone:</strong> <a href="tel:+11234567890" class="text-white text-decoration-none">(123) 456-7890</a></p>
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