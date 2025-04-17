<?php
// pages/home.php

$pageTitle = 'Home';
?>
<!-- Hero Section -->
<header class="bg-dark text-white text-center py-5 mt-3 rounded-5">
    <div class="container">
        <h1>Welcome to <?php echo htmlspecialchars($siteTitle); ?></h1>
        <p class="lead">A simple, clean homepage</p>
        <a href="#" class="btn btn-light btn-lg">Get Started</a>
    </div>
</header>


<!-- About Section -->
<section class="container py-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h2>About Us</h2>
            <p>We provide high-quality content, web solutions, and digital expertise to help you achieve your goals.</p>
            <p>Our mission is to create engaging, user-friendly experiences that make an impact.</p>
            <a href="#" class="btn btn-primary">Learn More</a>
        </div>
        <div class="col-md-6 text-center">
            <img src="https://picsum.photos/500/300?random=1" class="img-fluid rounded" alt="About Image">
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="bg-light py-5">
    <div class="container text-center">
        <h2>Why Choose Us?</h2>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="https://picsum.photos/500/250?random=2" class="card-img-top" alt="Feature 1">
                    <div class="card-body">
                        <h4 class="card-title">Quality Content</h4>
                        <p class="card-text">We provide in-depth analysis and engaging content that keeps visitors coming back.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="https://picsum.photos/500/250?random=3" class="card-img-top" alt="Feature 2">
                    <div class="card-body">
                        <h4 class="card-title">Responsive Design</h4>
                        <p class="card-text">Our websites are built to work seamlessly across all devices and screen sizes.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="https://picsum.photos/500/250?random=4" class="card-img-top" alt="Feature 3">
                    <div class="card-body">
                        <h4 class="card-title">Fast & Secure</h4>
                        <p class="card-text">We prioritize speed and security to provide the best user experience possible.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div id="fourthwall-embed">
  <script src="https://thevintagegamers-shop.fourthwall.com/embed.js"></script>
</div>
