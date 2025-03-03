<?php
// pages/404.php

$pageTitle = '404';
?>
<div class="card shadow-sm mt-3">

                <!-- Card Header with Search Form -->
                <div class="card-header text-center">
                    <form class="input-group" action="search.php" method="GET">
                        <input type="text" class="form-control" name="q" placeholder="Search for the page..." aria-label="Search" aria-describedby="button-addon2">
                        <button class="btn btn-danger" type="submit" id="button-addon2">Go</button>
                    </form>
                </div>

                <!-- Card Body with Error Information -->
                <div class="card-body">
                    <div class="row">
                        <!-- Left Column: Error Heading & Message -->
                        <div class="col-lg-8">
<div class="error-code display-1 text-primary mb-4">Oops!</div>

<div class="error-message h3 mb-4">
    We couldn't find the page you were looking for.
</div>

<p class="text-muted mb-4">
    Please make sure the address is typed correctly, or try one of the following options:
</p>

<ul class="list-unstyled">
    <li><a href="sitemap.html" class="text-decoration-none text-primary">Explore our Sitemap</a></li>
    <li><a href="index.html" class="text-decoration-none text-primary">Return to Homepage</a></li>
</ul>
                        </div>

                        <!-- Right Column: Nerd Information -->
                        <div class="col-lg-4">
<div class="nerd-info">
    <h5>Technical Details (For Internal Use):</h5>
    <ul class="list-unstyled">
        <li><strong>URL:</strong> <?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?></li>
        <li><strong>File:</strong> [hidden for security reasons]</li>
        <li><strong>Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></li>
    </ul>
</div>
                        </div>
                    </div>
                </div> <!-- End of card-body -->

            </div> <!-- End of card -->
