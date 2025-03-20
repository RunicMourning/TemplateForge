<?php
// analytics.php

// Set the page title for the template
$pageTitle = 'Analytics Overview';

// Define path to the analytics JSON file
$logFile = __DIR__ . '/../../config/data/analytics.json';
if (!file_exists($logFile)) {
    die("No analytics data available.");
}

// Load analytics data from JSON
$analytics = json_decode(file_get_contents($logFile), true);

// Get date filtering from GET variables; default to last 7 days if not set
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date("Y-m-d", strtotime("-7 days"));
$endDate   = isset($_GET['end_date'])   ? $_GET['end_date']   : date("Y-m-d");

// Initialize arrays to store aggregated views
$dailyTraffic   = [];
$weeklyTraffic  = [];
$monthlyTraffic = [];

// Initialize arrays for Browser and OS counts
$browserCounts = [];
$osCounts = [];

/**
 * Simple helper functions to detect Browser and OS.
 */
function detectBrowser($userAgent) {
    $ua = strtolower($userAgent);
    if (strpos($ua, 'edge') !== false) {
        return 'Edge';
    } elseif (strpos($ua, 'chrome') !== false) {
        return 'Chrome';
    } elseif (strpos($ua, 'firefox') !== false) {
        return 'Firefox';
    } elseif (strpos($ua, 'safari') !== false && strpos($ua, 'chrome') === false) {
        return 'Safari';
    } elseif (strpos($ua, 'msie') !== false || strpos($ua, 'trident') !== false) {
        return 'Internet Explorer';
    } else {
        return 'Other';
    }
}

function detectOS($userAgent) {
    $ua = strtolower($userAgent);
    if (strpos($ua, 'windows') !== false) {
        return 'Windows';
    } elseif (strpos($ua, 'mac') !== false) {
        return 'MacOS';
    } elseif (strpos($ua, 'linux') !== false) {
        return 'Linux';
    } elseif (strpos($ua, 'android') !== false) {
        return 'Android';
    } elseif (strpos($ua, 'iphone') !== false || strpos($ua, 'ipad') !== false) {
        return 'iOS';
    } else {
        return 'Other';
    }
}

// Loop over each page/blog post in the analytics data
foreach ($analytics as $pageSlug => $data) {
    // Loop over each day in the views
    foreach ($data['views'] as $date => $count) {
        // Only include data within the date range
        if ($date >= $startDate && $date <= $endDate) {
            // Aggregate daily traffic
            $dailyTraffic[$date] = ($dailyTraffic[$date] ?? 0) + $count;
            
            // Aggregate weekly traffic; week format "YYYY-WW"
            $week = date("o-W", strtotime($date));
            $weeklyTraffic[$week] = ($weeklyTraffic[$week] ?? 0) + $count;
            
            // Aggregate monthly traffic; format "YYYY-MM"
            $month = date("Y-m", strtotime($date));
            $monthlyTraffic[$month] = ($monthlyTraffic[$month] ?? 0) + $count;
        }
    }
    
    // Process user agent data for browser and OS
    if (isset($data['user_agents']) && is_array($data['user_agents'])) {
        foreach ($data['user_agents'] as $date => $agents) {
            if ($date >= $startDate && $date <= $endDate) {
                foreach ($agents as $uaString => $uaCount) {
                    $browser = detectBrowser($uaString);
                    $os = detectOS($uaString);
                    $browserCounts[$browser] = ($browserCounts[$browser] ?? 0) + $uaCount;
                    $osCounts[$os] = ($osCounts[$os] ?? 0) + $uaCount;
                }
            }
        }
    }
}

// Sort the aggregated data by key (date)
ksort($dailyTraffic);
ksort($weeklyTraffic);
ksort($monthlyTraffic);
?>

<h2>Analytics Overview (<?php echo $startDate; ?> to <?php echo $endDate; ?>)</h2>

<!-- Filter Form -->
<form method="GET" class="mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-auto">
            <label for="start_date" class="form-label">Start Date:</label>
            <input type="date" id="start_date" name="start_date" class="form-control" value="<?php echo $startDate; ?>">
        </div>
        <div class="col-auto">
            <label for="end_date" class="form-label">End Date:</label>
            <input type="date" id="end_date" name="end_date" class="form-control" value="<?php echo $endDate; ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </div>
</form>

<!-- Graphs Section in Cards (Line Charts) -->
<div class="row g-3">
    <!-- Daily Traffic Card -->
    <div class="col">
        <div class="card h-100">
            <div class="card-header">Daily Traffic</div>
            <div class="card-body">
                <canvas id="dailyChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
    <!-- Weekly Traffic Card -->
    <div class="col">
        <div class="card h-100">
            <div class="card-header">Weekly Traffic</div>
            <div class="card-body">
                <canvas id="weeklyChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
    <!-- Monthly Traffic Card -->
    <div class="col">
        <div class="card h-100">
            <div class="card-header">Monthly Traffic</div>
            <div class="card-body">
                <canvas id="monthlyChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Pie Charts Section in Cards -->
<div class="row g-3 mt-4">
    <!-- Browser Share Pie Chart Card -->
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-header">Browser Share</div>
            <div class="card-body">
                <canvas id="browserPieChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
    <!-- OS Share Pie Chart Card -->
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-header">Operating System Share</div>
            <div class="card-body">
                <canvas id="osPieChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Function to render a line chart
    function renderChart(canvasId, labels, data, chartLabel) {
        new Chart(document.getElementById(canvasId), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: chartLabel,
                    data: data,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: { 
                        title: { display: true, text: 'Time' }
                    },
                    y: { 
                        title: { display: true, text: 'Views' },
                        beginAtZero: true 
                    }
                }
            }
        });
    }

    // Render Line Charts
    const dailyLabels = <?php echo json_encode(array_keys($dailyTraffic)); ?>;
    const dailyData   = <?php echo json_encode(array_values($dailyTraffic)); ?>;
    renderChart("dailyChart", dailyLabels, dailyData, "Daily Views");

    const weeklyLabels = <?php echo json_encode(array_keys($weeklyTraffic)); ?>;
    const weeklyData   = <?php echo json_encode(array_values($weeklyTraffic)); ?>;
    renderChart("weeklyChart", weeklyLabels, weeklyData, "Weekly Views");

    const monthlyLabels = <?php echo json_encode(array_keys($monthlyTraffic)); ?>;
    const monthlyData   = <?php echo json_encode(array_values($monthlyTraffic)); ?>;
    renderChart("monthlyChart", monthlyLabels, monthlyData, "Monthly Views");

    // Render Pie Charts
    // Define some color palettes for the pie charts
    const pieColors = [
        'rgba(255, 99, 132, 0.7)',
        'rgba(54, 162, 235, 0.7)',
        'rgba(255, 206, 86, 0.7)',
        'rgba(75, 192, 192, 0.7)',
        'rgba(153, 102, 255, 0.7)',
        'rgba(255, 159, 64, 0.7)'
    ];

    // Browser Pie Chart
    const browserLabels = <?php echo json_encode(array_keys($browserCounts)); ?>;
    const browserData = <?php echo json_encode(array_values($browserCounts)); ?>;
    new Chart(document.getElementById("browserPieChart"), {
        type: 'pie',
        data: {
            labels: browserLabels,
            datasets: [{
                data: browserData,
                backgroundColor: pieColors.slice(0, browserLabels.length)
            }]
        },
        options: { responsive: true }
    });

    // OS Pie Chart
    const osLabels = <?php echo json_encode(array_keys($osCounts)); ?>;
    const osData = <?php echo json_encode(array_values($osCounts)); ?>;
    new Chart(document.getElementById("osPieChart"), {
        type: 'pie',
        data: {
            labels: osLabels,
            datasets: [{
                data: osData,
                backgroundColor: pieColors.slice(0, osLabels.length)
            }]
        },
        options: { responsive: true }
    });
</script>

<!-- Table Section -->
<h3 class="mt-5">Detailed Page Analytics</h3>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Page/Post</th>
                <th>Title</th>
                <th>Total Views</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($analytics as $slug => $data): ?>
                <?php
                    $title = $data['title'] ?? ucfirst($slug);
                    $totalViews = array_sum($data['views']);
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($slug); ?></td>
                    <td><?php echo htmlspecialchars($title); ?></td>
                    <td><?php echo $totalViews; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
