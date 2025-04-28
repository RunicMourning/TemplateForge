<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($pageTitle); ?> - <?php echo htmlspecialchars($siteTitle); ?></title>
  
  <!-- SEO -->
  <link rel="alternate" type="application/rss+xml" href="/rss.php" title="<?php echo htmlspecialchars($siteTitle); ?> RSS Feed" />

  <!-- Styles -->
  <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/cosmo/bootstrap.min.css" rel="stylesheet">
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
<footer class="bg-dark text-light py-5 mt-5 border-top border-secondary">
    <div class="container">
        <div class="row align-items-center">
            <!-- Site Title and Tagline -->
            <div class="col-md-6 mb-4 mb-md-0">
                <h5 class="fw-bold"><?php echo htmlspecialchars($siteTitle); ?></h5>
                <p class="small mb-2">Flexible. Modular. Ready to Build.</p>
                <p class="small mb-0" style="color: #aaa;">Version 1.0 — &copy; <?php echo date('Y'); ?>.</p>
            </div>

            <!-- Footer Navigation -->
            <div class="col-md-6 text-md-end">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <a href="privacy.html" class="text-light text-decoration-none">Privacy</a>
                    </li>
                    <li class="list-inline-item">
                        <a href="https://github.com/RunicMourning/TemplateForge" class="text-light text-decoration-none" target="_blank" rel="noopener noreferrer">
                            <i class="bi bi-github"></i> GitHub
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="border-primary my-4">

        <div class="row">
            <div class="col-md-6 small">
                &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($siteTitle); ?>. Version 1.0. Built with TemplateForge.
            </div>

            <div class="col-md-6 text-md-end small" style="color: #aaa;">
                Made with <span class="text-danger">&hearts;</span> for developers.
            </div>
        </div>
    </div>
</footer>



</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php if (!empty($footerIncludes)) echo implode("\n", $footerIncludes); ?>

</body>
</html>
