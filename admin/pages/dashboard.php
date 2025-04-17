<?php
// pages/dashboard.php

$pageTitle = 'Dashboard';

// Function to count the files in a directory
function count_files_in_directory($dir) {
    $files = array_diff(scandir($dir), array('..', '.')); // Ignore "." and ".."
    return count($files);
}

// Get the number of pages and blog posts by counting files in the respective directories
$total_pages = count_files_in_directory('../pages');  // Adjust the path if necessary
$total_posts = count_files_in_directory('../blog_posts');  // Adjust the path if necessary

// Set the main directory to monitor (relative path from admin folder)
$dir = '../'; // Relative path to the main directory

// Function to get the total size of a directory (including subdirectories)
function getDirectorySize($path) {
    $size = 0;
    try {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        foreach ($iterator as $file) {
            $size += $file->getSize();
        }
    } catch (Exception $e) {
        // Handle exceptions, such as "Directory not found" or "Permission denied"
        error_log("Error calculating directory size: " . $e->getMessage());
        return 0; // Return 0 size in case of error to avoid breaking the dashboard
    }
    return $size;
}

// Get directory size in bytes
$dirSize = getDirectorySize($dir);

// Convert size to KB or MB
if ($dirSize >= 1024) {
    $sizeInKB = $dirSize / 1024;
    if ($sizeInKB >= 1024) {
        $sizeInMB = $sizeInKB / 1024;
        $formattedSize = round($sizeInMB, 2) . ' MB';
    } else {
        $formattedSize = round($sizeInKB, 2) . ' KB';
    }
} else {
    $formattedSize = $dirSize . ' bytes';
}

// ----- NEW: Calculate "Views Today" as Unique Views -----
// Define path to the analytics JSON file (adjust the path if needed)
$analyticsFile = __DIR__ . '/../../config/data/analytics.json';
$uniqueIPsToday = [];
$views_today = 0; // Initialize to 0

if (file_exists($analyticsFile)) {
    $analytics = json_decode(file_get_contents($analyticsFile), true);
    $today = date("Y-m-d");

    // Check if $analytics is valid
    if (is_array($analytics)) { // Added check
        // Loop through each page/post entry and collect unique IPs for today
        foreach ($analytics as $pageSlug => $data) {
            if (isset($data['unique_ips'][$today]) && is_array($data['unique_ips'][$today])) { // Added is_array check
                $uniqueIPsToday = array_merge($uniqueIPsToday, array_keys($data['unique_ips'][$today]));
            }
        }
        $views_today = count(array_unique($uniqueIPsToday)); // count unique IPs
    } else {
        error_log("Warning: Analytics data is not a valid array.");
        $views_today = "Invalid data"; // Set a message,  do not use 0 to indicate error
    }
} else {
    error_log("Warning: Analytics file not found at " . $analyticsFile);
    $views_today = "No data";
}

// --- Get Latest Updates ---
function getLatestUpdates($numUpdates = 5) {
    $logFile = __DIR__ . '/../activity.log';
    $lines = @file($logFile, FILE_IGNORE_NEW_LINES);
    $updates = [];

    if ($lines) {
        foreach ($lines as $line) {
            if (preg_match('/^\[(.*?)\] - (.*?) - (.*?) - IP: (.*)$/', $line, $matches)) {
                $timestamp = $matches[1];
                $activity = $matches[2];
                $detail = $matches[3];
                $ip = $matches[4];

                // Exclude login attempts and 404 errors.
                if (stripos($activity, 'Login attempt') !== false || stripos($activity, '404 Not Found') !== false || stripos($activity, 'Admin login') !== false) {
                    continue; // Skip this iteration
                }

                $updates[] = [
                    'timestamp' => $timestamp,
                    'activity' => $activity,
                    'detail' => $detail,
                    'ip' => $ip
                ];
            }
        }
    }
    // Sort by timestamp (most recent first)
    usort($updates, function($a, $b) {
        return strtotime($b['timestamp']) - strtotime($a['timestamp']);
    });
    return array_slice($updates, 0, $numUpdates);
}

$latestUpdates = getLatestUpdates();

// --- Get System Information ---
function getSystemInfo() {
    $systemInfo = [
        'os' => php_uname('s') . ' ' . php_uname('r'),
        'php_version' => PHP_VERSION,
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
        'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'N/A',
    ];
    return $systemInfo;
}

$systemInfo = getSystemInfo();

// --- Define Icon Mapping ---
$iconMapping = [
    'Page Created' => 'bi bi-file-earmark-check',
    'Page Edited' => 'bi bi-file-earmark-font',
    'Page Deleted' => 'bi bi-file-earmark-excel',
    'Post Created' => 'bi bi-file-earmark-plus',
    'Post Edited' => 'bi bi-file-earmark-text',
    'Post Deleted' => 'bi bi-file-earmark-x',
    'Image Uploaded' => 'bi bi-image',
    'Image Deleted' => 'bi bi-image-fill',
    'Site Configuration' => 'bi bi-gear', // Corrected key
];
?>

<style>
    .my-card {
        position: absolute;
        left: 40%;
        top: -20px;
        border-radius: 50%;
    }
    .dashboard-section {
        margin-bottom: 20px;
    }
    .list-group-item i {
        margin-right: 5px;
    }
    .bi-file-earmark-check {
        color: green;
    }
    .bi-file-earmark-font {
        color: blue;
    }
    .bi-file-earmark-excel {
        color: red;
    }
    .bi-file-earmark-plus {
        color: green;
    }
    .bi-file-earmark-text {
        color: blue;
    }
    .bi-file-earmark-x {
        color: red;
    }
    .bi-image {
        color: purple;
    }
    .bi-image-fill {
        color: red; /* Corrected to red for consistency */
    }
    .bi-gear {
        color: orange;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 bg-light p-3">
            <h5 class="text-center">Admin Menu</h5>
            <ul class="list-group">
                <li class="list-group-item"><a href="index.php?p=pages" class="link-underline-light"><i class="bi bi-file-earmark-text"></i> Pages</a></li>
                <li class="list-group-item"><a href="index.php?p=posts" class="link-underline-light"><i class="bi bi-stickies"></i> Blog Posts</a></li>
                <li class="list-group-item"><a href="index.php?p=gallery" class="link-underline-light"><i class="bi bi-images"></i> Media Library</a></li>
                <li class="list-group-item"><a href="index.php?p=systeminfo" class="link-underline-light"><i class="bi bi-server"></i> System Info</a></li>
                <li class="list-group-item"><a href="index.php?p=manageusers" class="link-underline-light"><i class="bi bi-person-fill-check"></i> Users</a></li>
                <li class="list-group-item"><a href="index.php?p=settings" class="link-underline-light"><i class="bi bi-gear"></i> Settings</a></li>

            </ul>
            
            <?php
            // Include sidebar files if present
            $sidebarDir = 'sidebars';
            $sidebarFiles = array_diff(scandir($sidebarDir), array('.', '..'));
            if (!empty($sidebarFiles)) {
                foreach ($sidebarFiles as $file) {
                    if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') continue;
                    try {
                        include $sidebarDir . '/' . $file;
                    } catch (Exception $e) {
                        error_log("Error including sidebar file: " . $e->getMessage() . " in " . $sidebarDir . '/' . $file);
                        echo '<p class="mt-3 text-danger">Error loading sidebar item.</p>'; // Friendly message
                    }
                }
            } else {
                echo '<p class="mt-3">No sidebar items found.</p>';
            }
            ?>
        </div>

        <div class="col-md-9">
            <div class="bg-light p-4 rounded">
                <h3>Dashboard</h3>

                <div class="bg-light p-5 rounded dashboard-section">
                    <div class="row w-100">
                        <div class="col-md-3">
                            <div class="card border-primary mx-1 p-3">
                                <div class="card border-primary shadow text-light bg-primary p-3 my-card">
                                    <i class="bi bi-file-earmark-text-fill"></i>
                                </div>
                                <div class="text-primary text-center mt-3"><h4>Total Pages</h4></div>
                                <div class="text-primary text-center mt-2"><h1><?php echo $total_pages; ?></h1></div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card border-success mx-1 p-3">
                                <div class="card border-success shadow text-light bg-success p-3 my-card">
                                    <i class="bi bi-stickies-fill"></i>
                                </div>
                                <div class="text-success text-center mt-3"><h4>Total Posts</h4></div>
                                <div class="text-success text-center mt-2"><h1><?php echo $total_posts; ?></h1></div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card border-info mx-1 p-3">
                                <div class="card border-info shadow text-light bg-info p-3 my-card">
                                    <i class="bi bi-eye-fill"></i>
                                </div>
                                <div class="text-info text-center mt-3"><h4>Views Today</h4></div>
                                <div class="text-info text-center mt-2"><h1><?php echo $views_today; ?></h1></div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card border-warning mx-1 p-3">
                                <div class="card border-warning shadow text-light bg-warning p-3 my-card">
                                    <i class="bi bi-folder-fill"></i>
                                </div>
                                <div class="text-warning text-center mt-3"><h4>Directory Size</h4></div>
                                <div class="text-warning text-center mt-2"><h1><?php echo $formattedSize; ?></h1></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-section">
                    <h5><i class="bi bi-bar-chart-line"></i> Website Traffic Overview</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Total Page Views</h6>
                                    <p class="card-text">
                                        <?php
                                        $totalPageViews = 0;
                                        if (file_exists($analyticsFile) && is_array(json_decode(file_get_contents($analyticsFile), true))) {
                                            $analyticsData = json_decode(file_get_contents($analyticsFile), true);
                                            foreach ($analyticsData as $page) {
                                                if(isset($page['views']) && is_array($page['views'])){
                                                    foreach($page['views'] as $viewCount){
                                                        $totalPageViews += $viewCount;
                                                    }
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
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Total Unique Visitors</h6>
                                    <p class="card-text">
                                        <?php
                                         $totalUniqueVisitors = 0;
                                         if (file_exists($analyticsFile) && is_array(json_decode(file_get_contents($analyticsFile), true))){
                                                $analyticsData = json_decode(file_get_contents($analyticsFile), true);
                                                $uniqueVisitors = [];
                                                foreach($analyticsData as $page){
                                                     if(isset($page['unique_ips']) && is_array($page['unique_ips'])){
                                                         foreach($page['unique_ips'] as $date=>$ips){
                                                                 foreach($ips as $ip=>$val){
                                                                         $uniqueVisitors[$ip]=true;
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

                <div class="dashboard-section">
                    <h5><i class="bi bi-list-check"></i> <a href="index.php?p=log" class="link-underline-light link-dark">Recent Activity</a></h5>
                    <?php
                    if (empty($latestUpdates)) {
                        echo '<div class="alert alert-info">No recent activity.</div>';
                    } else {
                        echo '<div class="list-group">';
                        foreach ($latestUpdates as $update) {
                            $formattedTimestamp = date("F j, Y, g:i a", strtotime($update['timestamp']));
                            // Use the icon mapping
                            $icon = isset($iconMapping[$update['activity']]) ? $iconMapping[$update['activity']] : 'bi bi-arrow-right-circle'; // Default icon
                            echo '<div class="list-group-item d-flex justify-content-between" title="IP Address: ' . $update['ip'] . '">';
                            echo '<p class="mb-1"><i class="' . $icon . '"></i> ' . $update['activity'] . ' - ' . $update['detail'] . '</p>';
                            echo '<span class="text-muted">' . $formattedTimestamp . '</span>';
                            echo '</div>';
                        }
                        echo '</div>';
                    }
                    ?>
                </div>

                <div class="dashboard-section">
                    <h5><i class="bi bi-server"></i> System Information</h5>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Operating System:</strong> <?php echo htmlspecialchars($systemInfo['os']); ?></li>
                        <li class="list-group-item"><strong>PHP Version:</strong> <?php echo htmlspecialchars($systemInfo['php_version']); ?></li>
                        <li class="list-group-item"><strong>Server Software:</strong> <?php echo htmlspecialchars($systemInfo['server_software']); ?></li>
                        <li class="list-group-item"><strong>Document Root:</strong> <?php echo htmlspecialchars($systemInfo['document_root']); ?></li>
                    </ul>
                </div>

                
            </div>
        </div>
    </div>
</div>
