<?php
// pages/analytics.php

$pageTitle = 'Analytics Overview';
include_once __DIR__ . '/../logger.php';

$logFile = __DIR__ . '/../../config/data/analytics.json';
if (!file_exists($logFile)) {
    echo '<div class="container mt-5"><div class="alert alert-warning">No analytics data available.</div></div>';
    return;
}

$analytics = json_decode(file_get_contents($logFile), true);

// Date filters
$startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-7 days'));
$endDate   = $_GET['end_date']   ?? date('Y-m-d');

// Initialize
$pageViews = [];
$uniqueIPsGlobal = [];
$uniqueIPsDaily = [];
$uniqueIPsWeekly = [];
$uniqueIPsMonthly = [];
$browserCounts = [];
$osCounts = [];
$referrerCounts = [];

// Helper functions omitted for brevity (detectBrowser, detectOS, getDomain)...
function detectBrowser($ua) { /* ... */ }
function detectOS($ua) { /* ... */ }
function getDomain($url) { /* ... */ }

// Process analytics data
foreach ($analytics as $slug => $data) {
    // total views in range
    $pageViews[$slug] = 0;
    foreach ($data['views'] ?? [] as $date => $cnt) {
        if ($date >= $startDate && $date <= $endDate) {
            $pageViews[$slug] += $cnt;
        }
    }
    // unique IPs daily
    foreach ($data['unique_ips'] ?? [] as $date => $ips) {
        if ($date >= $startDate && $date <= $endDate) {
            foreach (array_keys($ips) as $ip) {
                $uniqueIPsGlobal[$ip] = true;
                $uniqueIPsDaily[$date][$ip] = true;
                $week = date('o-\WW', strtotime($date));
                $uniqueIPsWeekly[$week][$ip] = true;
                $month = date('Y-m', strtotime($date));
                $uniqueIPsMonthly[$month][$ip] = true;
            }
        }
    }
    // browsers and OS
    foreach ($data['user_agents'] ?? [] as $date => $agents) {
        if ($date >= $startDate && $date <= $endDate) {
            foreach ($agents as $ua => $cnt) {
                $br = detectBrowser($ua);
                $os = detectOS($ua);
                $browserCounts[$br] = ($browserCounts[$br] ?? 0) + $cnt;
                $osCounts[$os] = ($osCounts[$os] ?? 0) + $cnt;
            }
        }
    }
    // referrers
    foreach ($data['referrers'] ?? [] as $date => $refs) {
        if ($date >= $startDate && $date <= $endDate) {
            foreach ($refs as $ref => $cnt) {
                $referrerCounts[$ref] = ($referrerCounts[$ref] ?? 0) + $cnt;
            }
        }
    }
}

// aggregate totals
$uniqueTotal = count($uniqueIPsGlobal);

// prepare chart data
$dailyLabels = json_encode(array_keys($uniqueIPsDaily));
$dailyData = json_encode(array_map('count', $uniqueIPsDaily));
$weeklyLabels = json_encode(array_keys($uniqueIPsWeekly));
$weeklyData = json_encode(array_map('count', $uniqueIPsWeekly));
$monthlyLabels = json_encode(array_keys($uniqueIPsMonthly));
$monthlyData = json_encode(array_map('count', $uniqueIPsMonthly));

// sort browser/os/referrers
arsort($browserCounts);
arsort($osCounts);
arsort($referrerCounts);
?>

<div class="container my-5">
  <h2 class="mb-4"><i class="bi bi-graph-up me-2 text-success"></i>Analytics Overview</h2>

  <!-- Date Filter -->
  <form method="GET" class="row g-3 mb-5">
    <input type="hidden" name="p" value="analytics">
    <div class="col-auto">
      <label class="form-label">Start Date</label>
      <input type="date" name="start_date" class="form-control" value="<?= $startDate ?>">
    </div>
    <div class="col-auto">
      <label class="form-label">End Date</label>
      <input type="date" name="end_date" class="form-control" value="<?= $endDate ?>">
    </div>
    <div class="col-auto align-self-end">
      <button type="submit" class="btn btn-primary"><i class="bi bi-filter-circle me-1"></i>Filter</button>
    </div>
  </form>

  <!-- Key Metrics -->
  <div class="row g-4 mb-5">
    <div class="col-md-3">
      <div class="bg-light p-4 rounded shadow-sm text-center">
        <i class="bi bi-people-fill fs-1 text-primary mb-2"></i>
        <h6>Unique Visitors</h6>
        <h3><?= $uniqueTotal ?></h3>
      </div>
    </div>
    <div class="col-md-3">
      <div class="bg-light p-4 rounded shadow-sm text-center">
        <i class="bi bi-eye-fill fs-1 text-success mb-2"></i>
        <h6>Total Page Views</h6>
        <h3><?= array_sum($pageViews) ?></h3>
      </div>
    </div>
    <div class="col-md-3">
      <div class="bg-light p-4 rounded shadow-sm text-center">
        <i class="bi bi-browser-chrome fs-1 text-info mb-2"></i>
        <h6>Top Browser</h6>
        <h5><?= array_key_first($browserCounts) ?: 'N/A' ?></h5>
      </div>
    </div>
    <div class="col-md-3">
      <div class="bg-light p-4 rounded shadow-sm text-center">
        <i class="bi bi-cpu fs-1 text-warning mb-2"></i>
        <h6>Top OS</h6>
        <h5><?= array_key_first($osCounts) ?: 'N/A' ?></h5>
      </div>
    </div>
  </div>

  <!-- Charts -->
  <div class="row g-4 mb-5">
    <div class="col-lg-4">
      <div class="card h-100">
        <div class="card-header bg-white"><i class="bi bi-calendar-day me-2"></i>Daily Unique Views</div>
        <div class="card-body"><canvas id="dailyChart"></canvas></div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card h-100">
        <div class="card-header bg-white"><i class="bi bi-calendar-week me-2"></i>Weekly Unique Views</div>
        <div class="card-body"><canvas id="weeklyChart"></canvas></div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card h-100">
        <div class="card-header bg-white"><i class="bi bi-calendar-month me-2"></i>Monthly Unique Views</div>
        <div class="card-body"><canvas id="monthlyChart"></canvas></div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  renderChart('dailyChart', <?= $dailyLabels ?>, <?= $dailyData ?>, 'Daily Unique Views');
  renderChart('weeklyChart', <?= $weeklyLabels ?>, <?= $weeklyData ?>, 'Weekly Unique Views');
  renderChart('monthlyChart', <?= $monthlyLabels ?>, <?= $monthlyData ?>, 'Monthly Unique Views');

  function renderChart(id, labels, data, label) {
    const ctx = document.getElementById(id).getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: { labels, datasets: [{ label, data, fill: true, tension: 0.1 }] },
      options: { responsive: true }
    });
  }
</script>