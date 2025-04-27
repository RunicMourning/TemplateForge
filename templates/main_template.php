<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($pageTitle); ?> - <?php echo htmlspecialchars($siteTitle); ?></title>
  
  <!-- SEO -->
  <link rel="alternate" type="application/rss+xml" href="/rss.php" title="<?php echo htmlspecialchars($siteTitle); ?> RSS Feed" />

  <!-- Styles -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cairo+Play:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">

  <?php if (!empty($headerIncludes)) echo implode("\n", $headerIncludes); ?>
</head>

<body class="bg-light text-body">
<div class="container-fluid px-0">
  
  <!-- Header -->
  <header class="py-4 bg-white border-bottom shadow-sm">
    <div class="container d-flex align-items-center">
      <a href="/" class="text-decoration-none d-flex align-items-center gap-3">
        <span class="fs-3 fw-bold text-dark"><?php echo htmlspecialchars($siteTitle); ?></span>
      </a>
    </div>
  </header>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
    <div class="container">
      <a class="navbar-brand d-lg-none" href="/"><?php echo htmlspecialchars($siteTitle); ?></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarMain">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 text-light">
          <?php echo buildNavigation(); ?>
        </ul>
        <form class="d-flex" action="search.html" method="GET">
          <input class="form-control me-2" type="search" name="q" placeholder="Search..." aria-label="Search">
          <button class="btn btn-light" type="submit"><i class="bi bi-search"></i></button>
        </form>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="container my-5">
    <div class="row">
      <aside class="col-lg-3 order-2 order-lg-1">
        <?php loadSidebars(); ?>
      </aside>

      <section class="col-lg-9 order-1 order-lg-2">
        <?php echo $pageContent; ?>
      </section>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-dark text-light pt-5">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4">
          <h6 class="text-uppercase">Follow Us</h6>
          <ul class="list-unstyled">
            <li><a href="#" class="text-decoration-none text-light"><i class="bi bi-linkedin me-2"></i>LinkedIn</a></li>
            <li><a href="#" class="text-decoration-none text-light"><i class="bi bi-bluesky me-2"></i>Bluesky</a></li>
            <li><a href="#" class="text-decoration-none text-light"><i class="bi bi-instagram me-2"></i>Instagram</a></li>
          </ul>
        </div>

        <div class="col-md-4">
          <h6 class="text-uppercase">Legal</h6>
          <ul class="list-unstyled">
            <li><a href="#" class="text-decoration-none text-light"><i class="bi bi-file-earmark-text me-2"></i>Terms & Conditions</a></li>
            <li><a href="privacy.html" class="text-decoration-none text-light"><i class="bi bi-shield-lock me-2"></i>Privacy Policy</a></li>
          </ul>
        </div>

        <div class="col-md-4">
          <h6 class="text-uppercase">Contact</h6>
          <ul class="list-unstyled">
            <li><i class="bi bi-envelope-at-fill me-2"></i><a href="mailto:contact@mywebsite.com" class="text-decoration-none text-light">contact@mywebsite.com</a></li>
            <li><i class="bi bi-telephone me-2"></i><a href="tel:+11234567890" class="text-decoration-none text-light">(123) 456-7890</a></li>
            <li><i class="bi bi-geo-alt-fill me-2"></i>123 Business Ave, City, State</li>
          </ul>
        </div>
      </div>

      <div class="text-center small mt-4 border-top pt-3">
        &copy; 2025 <strong><?php echo htmlspecialchars($siteTitle); ?></strong>. All rights reserved.
      </div>
    </div>
  </footer>

</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php if (!empty($footerIncludes)) echo implode("\n", $footerIncludes); ?>

</body>
</html>
