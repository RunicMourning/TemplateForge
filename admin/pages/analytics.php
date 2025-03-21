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

// Initialize arrays
$dailyUnique = [];
$weeklyUnique = [];
$monthlyUnique = [];
$uniqueIPsGlobal = [];
$uniqueIPsDaily = [];
$browserCounts = [];
$osCounts = [];
$referrerCounts = []; // Added for referrer tracking
$pageViews = []; //added to track total page views

// Helper functions
function detectBrowser($userAgent) {
    $ua = strtolower($userAgent);
    if (strpos($ua, 'opr/') !== false || strpos($ua, 'opera') !== false) {
        return 'Opera';
    } elseif (strpos($ua, 'edg/') !== false) {
        return 'Edge';
    } elseif (strpos($ua, 'chrome') !== false && strpos($ua, 'edg/') === false && strpos($ua, 'opr/') === false) {
        return 'Chrome';
    } elseif (strpos($ua, 'firefox') !== false) {
        return 'Firefox';
    } elseif (strpos($ua, 'safari') !== false && strpos($ua, 'chrome') === false && strpos($ua, 'opr/') === false && strpos($ua, 'edg/') === false) {
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

function getDomain($url) {
    $url = str_replace(['http://', 'https://', 'www.'], '', $url);
    $parts = explode('/', $url);
    return $parts[0];
}

// Process analytics data
foreach ($analytics as $pageSlug => $data) {
    // Initialize page views
    $pageViews[$pageSlug] = 0;

    if (isset($data['views']) && is_array($data['views'])) {
        foreach ($data['views'] as $date => $views) {
            if ($date >= $startDate && $date <= $endDate) {
                $pageViews[$pageSlug] += $views; // Track total views per page
            }
        }
    }

    if (isset($data['unique_ips']) && is_array($data['unique_ips'])) {
        foreach ($data['unique_ips'] as $date => $ips) {
            if ($date >= $startDate && $date <= $endDate) {
                if (!isset($uniqueIPsDaily[$date])) {
                    $uniqueIPsDaily[$date] = [];
                }
                foreach (array_keys($ips) as $ip) {
                    $uniqueIPsGlobal[$ip] = true;
                    $uniqueIPsDaily[$date][$ip] = true;
                }
            }
        }
    }

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

    if (isset($data['referrers']) && is_array($data['referrers'])) {
        foreach ($data['referrers'] as $date => $refs) {
            if ($date >= $startDate && $date <= $endDate) {
                foreach ($refs as $referrer => $count) {
                    $referrerCounts[$referrer] = ($referrerCounts[$referrer] ?? 0) + $count;
                }
            }
        }
    }
}

// Total unique views across all pages
$uniqueTotal = count($uniqueIPsGlobal);

// Sort browser, OS, and referrers
arsort($browserCounts);
arsort($osCounts);
arsort($referrerCounts); // Sort referrers by count

// Aggregate daily, weekly, and monthly data
foreach ($uniqueIPsDaily as $date => $ips) {
    $dailyUnique[$date] = count($ips);
    $weekStart = date('Y-m-d', strtotime('monday this week', strtotime($date)));
    $monthStart = date('Y-m-01', strtotime($date));

    if (!isset($weeklyUnique[$weekStart])) {
        $weeklyUnique[$weekStart] = 0;
    }
    $weeklyUnique[$weekStart] += count($ips);

    if (!isset($monthlyUnique[$monthStart])) {
        $monthlyUnique[$monthStart] = 0;
    }
    $monthlyUnique[$monthStart] += count($ips);
}

// Prepare data for charts
$dailyLabels = json_encode(array_keys($dailyUnique));
$dailyData = json_encode(array_values($dailyUnique));
$weeklyLabels = json_encode(array_keys($weeklyUnique));
$weeklyData = json_encode(array_values($weeklyUnique));
$monthlyLabels = json_encode(array_keys($monthlyUnique));
$monthlyData = json_encode(array_values($monthlyUnique));

// Browser and OS data for pie charts
$browserLabels = json_encode(array_keys($browserCounts));
$browserData = json_encode(array_values($browserCounts));
$osLabels = json_encode(array_keys($osCounts));
$osData = json_encode(array_values($osCounts));

// Referrer data for table/list
$referrerLabels = array_keys($referrerCounts);
$referrerData = array_values($referrerCounts);
?>
<h2>Analytics Overview (<?php echo $startDate; ?> to <?php echo $endDate; ?>)</h2>

<form method="GET" class="mb-4">
    <input type="hidden" name="p" value="analytics">
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

<div class="row g-3">
    <div class="col">
        <div class="card h-100">
            <div class="card-header">Daily Unique Views</div>
            <div class="card-body">
                <canvas id="dailyChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100">
            <div class="card-header">Weekly Unique Views</div>
            <div class="card-body">
                <canvas id="weeklyChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100">
            <div class="card-header">Monthly Unique Views</div>
            <div class="card-body">
                <canvas id="monthlyChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-4">
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-header">Browser Share</div>
            <div class="card-body">
                <canvas id="browserPieChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-header">Operating System Share</div>
            <div class="card-body">
                <canvas id="osPieChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
     <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">Top Referrers</div>
            <div class="card-body">
                <ul class="list-group">
                <?php
                $hasReferrers = false;
                $siteDomain = getDomain($_SERVER['HTTP_HOST']); // Get the current site's domain
                foreach ($referrerLabels as $index => $referrer) {
                    $referrerDomain = getDomain($referrer);
                    if ($referrerDomain !== $siteDomain && $referrer != 'Direct') { // Exclude 'Direct'
                        $hasReferrers = true;
                        ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($referrer); ?>
                            <span class="badge bg-primary rounded-pill"><?php echo $referrerData[$index]; ?></span>
                        </li>
                        <?php
                    }
                }
                if (!$hasReferrers) {
                    echo '<li class="list-group-item">No referrers found.</li>';
                }
                ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function renderChart(canvasId, labels, data, chartLabel) {
        const ctx = document.getElementById(canvasId).getContext('2d');
        try {
            new Chart(ctx, {
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
                            title: { display: true, text: 'Unique Views' },
                            beginAtZero: true
                        }
                    }
                }
            });
        } catch (error) {
            console.error(`Error rendering chart ${canvasId}:`, error);
            document.getElementById(canvasId).parentElement.innerHTML = `<div class="alert alert-danger">Error rendering chart: ${error.message}</div>`;
        }
    }

    // Render the charts, passing in the PHP-generated data
    try{
        renderChart("dailyChart", <?php echo $dailyLabels; ?>, <?php echo $dailyData; ?>, "Daily Unique Views");
        renderChart("weeklyChart", <?php echo $weeklyLabels; ?>, <?php echo $weeklyData; ?>, "Weekly Unique Views");
        renderChart("monthlyChart", <?php echo $monthlyLabels; ?>, <?php echo $monthlyData; ?>, "Monthly Unique Views");



        // Pie chart
        const pieColors = [
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(153, 102, 255, 0.7)',
            'rgba(255, 159, 64, 0.7)'
        ];

        const browserPieCtx = document.getElementById("browserPieChart").getContext('2d');
        new Chart(browserPieCtx, {
            type: 'pie',
            data: {
                labels: <?php echo $browserLabels; ?>,
                datasets: [{
                    data: <?php echo $browserData; ?>,
                    backgroundColor: pieColors.slice(0, <?php echo count(json_decode($browserLabels)); ?>)
                }]
            },
            options: { responsive: true }
        });

        const osPieCtx = document.getElementById("osPieChart").getContext('2d');
        new Chart(osPieCtx, {
            type: 'pie',
            data: {
                labels: <?php echo $osLabels; ?>,
                datasets: [{
                    data: <?php echo $osData; ?>,
                    backgroundColor: pieColors.slice(0, <?php echo count(json_decode($osLabels)); ?>)
                }]
            },
            options: { responsive: true }
        });
    } catch (e){
        console.log(e);
    }
</script>

<h3 class="mt-5">Detailed Page Analytics</h3>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Page/Post</th>
                <th>Title</th>
                <th>Total Views</th>
                <th>Total Unique Views</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($analytics as $slug => $data): ?>
                <?php
                    $title = $data['title'] ?? ucfirst($slug);
                    $uniqueTotal = 0;
                    if (isset($data['unique_ips'])) {
                        if(is_array($data['unique_ips'])){
                            foreach ($data['unique_ips'] as $ips) {
                                $uniqueTotal += count($ips);
                            }
                        }
                    }
                    $views = isset($data['views']['2025-03-20']) ? $data['views']['2025-03-20'] : 0;

                ?>
                <tr>
                    <td><?php echo htmlspecialchars($slug); ?></td>
                    <td><?php echo htmlspecialchars($title); ?></td>
                     <td><?php echo $views; ?></td>
                    <td><?php echo $uniqueTotal; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
