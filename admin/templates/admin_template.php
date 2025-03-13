<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($siteTitle); ?> - <?php echo htmlspecialchars($pageTitle); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>
    <header class="bg-primary text-white text-center py-3">
        <h1><?php echo htmlspecialchars($siteTitle); ?> Admin Panel</h1>
    </header>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php include __DIR__ . '/../includes/adminnav.inc'; ?>
            </div>
			          <div class="d-lg-flex col-lg-3 justify-content-lg-end">
            <a href="/index.html" class="btn btn-primary btn-sm" target="_blank"><i class="bi bi-browser-chrome"></i> View Site</a>
          </div>
        </div>
    </nav>
    
    <main class="container-fluid my-4">
        <?php echo $pageContent; ?>
    </main>
    
    <footer class="bg-light text-center py-3 mt-4 border-top">
        <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($siteTitle); ?> Admin Panel</p>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
