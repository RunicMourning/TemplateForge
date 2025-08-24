<?php
// pages/dashboard.php

$pageTitle = 'Dashboard';

function count_files_in_directory($dir) {
    $files = array_diff(scandir($dir), ['..', '.']);
    return count($files);
}

$total_pages = count_files_in_directory('../pages');
$total_posts = count_files_in_directory('../blog_posts');

$dir = '../';

function getDirectorySize($path) {
    $size = 0;
    try {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        foreach ($iterator as $file) {
            $size += $file->getSize();
        }
    } catch (Exception $e) {
        error_log("Error calculating directory size: " . $e->getMessage());
        return 0;
    }
    return $size;
}

$dirSize = getDirectorySize($dir);
$formattedSize = $dirSize >= 1024 ? ($dirSize / 1024 >= 1024 ? round($dirSize / 1048576, 2) . ' MB' : round($dirSize / 1024, 2) . ' KB') : $dirSize . ' bytes';

$analyticsFile = __DIR__ . '/../../config/data/analytics.json';
$views_today = 0;
$uniqueIPsToday = [];

if (file_exists($analyticsFile)) {
    $analytics = json_decode(file_get_contents($analyticsFile), true);
    $today = date("Y-m-d");
    if (is_array($analytics)) {
        foreach ($analytics as $page => $data) {
            if (!empty($data['unique_ips'][$today])) {
                $uniqueIPsToday = array_merge($uniqueIPsToday, array_keys($data['unique_ips'][$today]));
            }
        }
        $views_today = count(array_unique($uniqueIPsToday));
    } else {
        error_log("Invalid analytics structure");
        $views_today = "Invalid data";
    }
} else {
    error_log("Analytics file missing");
    $views_today = "No data";
}

function getLatestUpdates($num = 5) {
    $log = __DIR__ . '/../activity.log';
    $lines = @file($log, FILE_IGNORE_NEW_LINES);
    $updates = [];
    if ($lines) {
        foreach ($lines as $line) {
            if (preg_match('/^\[(.*?)\] - (.*?) - (.*?) - IP: (.*)$/', $line, $match)) {
                [$all, $ts, $act, $detail, $ip] = $match;

                // Combine act + detail for filtering
                $text = strtolower($act . ' ' . $detail);

                // Skip unwanted log types
                if (
                    str_contains($text, '404') ||
                    str_contains($text, 'fatal error') ||
                    str_contains($text, 'php error') ||
                    str_contains($text, 'parse error') ||
                    str_contains($text, 'warning') ||
                    str_contains($text, 'notice') ||
                    str_contains($text, 'deprecated')
                ) {
                    continue;
                }

                $updates[] = [
                    'timestamp' => $ts,
                    'activity'  => $act,
                    'detail'    => $detail,
                    'ip'        => $ip
                ];
            }
        }
    }
    usort($updates, fn($a, $b) => strtotime($b['timestamp']) - strtotime($a['timestamp']));
    return array_slice($updates, 0, $num);
}

$latestUpdates = getLatestUpdates();

function getSystemInfo() {
    return [
        'os' => php_uname('s') . ' ' . php_uname('r'),
        'php_version' => PHP_VERSION,
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
        'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'N/A',
    ];
}

$systemInfo = getSystemInfo();

$iconMapping = [
    'Page Created' => 'bi bi-file-earmark-check',
    'Page Edited' => 'bi bi-file-earmark-font',
    'Page Deleted' => 'bi bi-file-earmark-excel',
    'Post Created' => 'bi bi-file-earmark-plus',
    'Post Edited' => 'bi bi-file-earmark-text',
    'Post Deleted' => 'bi bi-file-earmark-x',
    'Image Uploaded' => 'bi bi-image',
    'Image Deleted' => 'bi bi-image-fill',
    'Site Configuration' => 'bi bi-gear',
];
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
                <h3 class="mb-4">Dashboard</h3>
                <?php if (isset($_SESSION['username']) && empty($_SESSION['welcome_shown'])): 
                    $_SESSION['welcome_shown'] = true; 
                    $username = ucfirst(strtolower(htmlspecialchars($_SESSION['username']))); ?>
                    <div class="alert alert-success d-flex align-items-center mb-4">
                        <i class="bi bi-person-circle fs-3 me-3"></i>
                        <div>Welcome back, <strong><?= $username ?></strong>! Glad to have you here.</div>
                    </div>
                <?php endif; ?>

                <div class="row g-4 mb-4">
                    <?php
                    $cards = [
                        ['label' => 'Total Pages', 'value' => $total_pages, 'color' => 'primary', 'icon' => 'bi-file-earmark-text-fill'],
                        ['label' => 'Total Posts', 'value' => $total_posts, 'color' => 'success', 'icon' => 'bi-stickies-fill'],
                        ['label' => 'Views Today', 'value' => $views_today, 'color' => 'info', 'icon' => 'bi-eye-fill'],
                        ['label' => 'Directory Size', 'value' => $formattedSize, 'color' => 'warning text-dark', 'icon' => 'bi-folder-fill'],
                    ];
                    foreach ($cards as $card): ?>
                        <div class="col-md-6 col-xl-3">
                            <div class="card text-white bg-<?= $card['color'] ?> shadow-sm h-100">
                                <div class="card-body d-flex align-items-center">
                                    <i class="bi <?= $card['icon'] ?> fs-1 me-3"></i>
                                    <div>
                                        <h6 class="mb-0"><?= $card['label'] ?></h6>
                                        <h2 class="mb-0 fw-bold"><?= $card['value'] ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mb-4">
                    <h5><i class="bi bi-bar-chart-line me-2"></i> Website Traffic Overview</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title">Total Page Views</h6>
                                    <p class="card-text fs-4 fw-semibold">
                                        <?php
                                        $totalPageViews = 0;
                                        if (file_exists($analyticsFile)) {
                                            $analyticsData = json_decode(file_get_contents($analyticsFile), true);
                                            foreach ($analyticsData as $page) {
                                                if (!empty($page['views'])) {
                                                    $totalPageViews += array_sum($page['views']);
                                                }
                                            }
                                        }
                                        echo $totalPageViews;
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title">Total Unique Visitors</h6>
                                    <p class="card-text fs-4 fw-semibold">
                                        <?php
                                        $totalUniqueVisitors = 0;
                                        if (file_exists($analyticsFile)) {
                                            $analyticsData = json_decode(file_get_contents($analyticsFile), true);
                                            $uniqueVisitors = [];
                                            foreach ($analyticsData as $page) {
                                                if (!empty($page['unique_ips'])) {
                                                    foreach ($page['unique_ips'] as $ips) {
                                                        foreach ($ips as $ip => $v) {
                                                            $uniqueVisitors[$ip] = true;
                                                        }
                                                    }
                                                }
                                            }
                                            $totalUniqueVisitors = count($uniqueVisitors);
                                        }
                                        echo $totalUniqueVisitors;
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h5><i class="bi bi-list-check me-2"></i> <a href="index.php?p=log" class="text-decoration-none">Recent Activity</a></h5>
                    <?php if (empty($latestUpdates)): ?>
                        <div class="alert alert-info">No recent activity.</div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($latestUpdates as $u): 
                                $timestamp = date("F j, Y, g:i a", strtotime($u['timestamp']));
                                $icon = $iconMapping[$u['activity']] ?? 'bi bi-arrow-right-circle'; ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center" title="IP Address: <?= $u['ip'] ?>">
                                    <p class="mb-0"><i class="<?= $icon ?> me-2"></i> <?= $u['activity'] ?> - <?= $u['detail'] ?></p>
                                    <span class="text-muted small"><?= $timestamp ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-0">
                    <h5><i class="bi bi-server me-2"></i> System Information</h5>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Operating System:</strong> <?= htmlspecialchars($systemInfo['os']) ?></li>
                        <li class="list-group-item"><strong>PHP Version:</strong> <?= htmlspecialchars($systemInfo['php_version']) ?></li>
                        <li class="list-group-item"><strong>Server Software:</strong> <?= htmlspecialchars($systemInfo['server_software']) ?></li>
                        <li class="list-group-item"><strong>Document Root:</strong> <?= htmlspecialchars($systemInfo['document_root']) ?></li>
                    </ul>
                </div>
        </div>
    </div>
</div>
