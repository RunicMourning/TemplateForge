<?php
// pages/dashboard.php

$pageTitle = 'Dashboard';

// Function to count the files in a directory
function count_files_in_directory($dir) {
    // Open the directory and count the number of files
    $files = array_diff(scandir($dir), array('..', '.')); // Ignore "." and ".."
    return count($files); // Return the number of files
}

// Get the number of pages and blog posts by counting files in the respective directories
$total_pages = count_files_in_directory('../pages');  // Adjust the path to point to the pages directory
$total_posts = count_files_in_directory('../blog_posts');  // Adjust the path to point to the blog_posts directory

// Set the main directory to monitor (relative path from admin folder)
$dir = '../'; // Relative path to the 'dir' folder from the 'admin' folder

// Function to get the total size of a directory (including subdirectories)
function getDirectorySize($path) {
    $size = 0;
    // Recursively iterate through all files and directories
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $file) {
        $size += $file->getSize();
    }
    return $size;
}

// Get directory size in bytes
$dirSize = getDirectorySize($dir);

// Convert size to KB and MB based on its size
if ($dirSize >= 1024) {
    $sizeInKB = $dirSize / 1024; // Convert to KB
    if ($sizeInKB >= 1024) {
        $sizeInMB = $sizeInKB / 1024; // Convert to MB
        $formattedSize = round($sizeInMB, 2) . ' MB';
    } else {
        $formattedSize = round($sizeInKB, 2) . ' KB';
    }
} else {
    $formattedSize = $dirSize . ' bytes';
}
?>
<style>
    .my-card {
        position: absolute;
        left: 40%;
        top: -20px;
        border-radius: 50%;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 bg-light p-3">
            <h5 class="text-center">Admin Menu</h5>
            <ul class="list-group">
                <li class="list-group-item"><a href="index.php?p=pages" class="link-underline-light"><i class="bi bi-file-earmark-text"></i> Pages</a></li>
                <li class="list-group-item"><a href="index.php?p=posts" class="link-underline-light"><i class="bi bi-stickies"></i> Blog Posts</a></li>
                <li class="list-group-item"><a href="index.php?p=gallery" class="link-underline-light"><i class="bi bi-images"></i> Media Library</a></li>
                <li class="list-group-item"><a href="index.php?p=settings" class="link-underline-light"><i class="bi bi-gear"></i> Settings</a></li>
                <li class="list-group-item"><a href="index.php?p=systeminfo" class="link-underline-light"><i class="bi bi-server"></i> System Info</a></li>
            </ul>
            
            <?php
            // Include sidebar files if present
            $sidebarDir = 'sidebars';
            $sidebarFiles = array_diff(scandir($sidebarDir), array('.', '..'));
            if (!empty($sidebarFiles)) {
                foreach ($sidebarFiles as $file) {
                    if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') continue;
                    include $sidebarDir . '/' . $file;
                }
            } else {
                echo '<p class="mt-3">No sidebar items found.</p>';
            }
            ?>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="bg-light p-4 rounded">
                <h3>Dashboard</h3>

                <!-- Stats Overview -->
                <div class="bg-light p-5 rounded">
                    <div class="row w-100">
                        <!-- Total Pages -->
                        <div class="col-md-3">
                            <div class="card border-primary mx-1 p-3">
                                <div class="card border-primary shadow text-light bg-primary p-3 my-card">
                                    <i class="bi bi-file-earmark-text-fill"></i>
                                </div>
                                <div class="text-primary text-center mt-3"><h4>Total Pages</h4></div>
                                <div class="text-primary text-center mt-2"><h1><?php echo $total_pages; ?></h1></div>
                            </div>
                        </div>
                        
                        <!-- Total Posts -->
                        <div class="col-md-3">
                            <div class="card border-success mx-1 p-3">
                                <div class="card border-success shadow text-light bg-success p-3 my-card">
                                    <i class="bi bi-stickies-fill"></i>
                                </div>
                                <div class="text-success text-center mt-3"><h4>Total Posts</h4></div>
                                <div class="text-success text-center mt-2"><h1><?php echo $total_posts; ?></h1></div>
                            </div>
                        </div>

                        <!-- PHP Version -->
                        <div class="col-md-3">
                            <div class="card border-danger mx-1 p-3">
                                <div class="card border-danger shadow text-light bg-danger p-3 my-card">
                                    <i class="bi bi-code-slash"></i>
                                </div>
                                <div class="text-danger text-center mt-3"><h4>PHP Version</h4></div>
                                <div class="text-danger text-center mt-2"><h1><?php echo phpversion(); ?></h1></div>
                            </div>
                        </div>

                        <!-- Entire Directory Size -->
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

                <!-- Recent Activity -->
                 <!-- Recent Activity -->
                <div class="mt-4">
                    <h5><i class="bi bi-list-check"></i> <a href="index.php?p=log" class="link-underline-light link-dark">Recent Activity</a></h5>

<?php
// Assuming the log data is stored in a file
$logFile = __DIR__ . '/../activity.log';
$lines = file($logFile, FILE_IGNORE_NEW_LINES);  // Read log file line by line

// Define the activities you want to track and their associated icons
$activitiesToTrack = [
    'Page Created' => 'bi-file-earmark-plus',
    'Page Edited' => 'bi-pencil',
    'Post Created' => 'bi-file-earmark-text',
    'Post Edited' => 'bi-pencil-square',
    'Post Deleted' => 'bi-trash',
    'Page Deleted' => 'bi-file-earmark-x',
    'Image Uploaded' => 'bi-cloud-upload',
    'Image Deleted' => 'bi-trash3',
    'Admin Login' => 'bi-person-lock',
    'User Login' => 'bi-person-check',
    'Site Configuration' => 'bi-gear-fill'
];

// Function to format timestamp
function formatTimestamp($timestamp) {
    // Use PHP's DateTime to ensure the timestamp is correctly formatted
    try {
        $dateTime = new DateTime($timestamp);
        return $dateTime->format("F j, Y, g:i a");  // E.g., March 13, 2025, 12:27 pm
    } catch (Exception $e) {
        return "Invalid timestamp";
    }
}

// Filter and display the selected activities
$activityMessages = [];
foreach ($lines as $line) {
    // Use regex to capture the necessary details
    if (preg_match('/^\[(.*?)\] - (.*?) - (.*?) - IP: (.*)$/', $line, $matches)) {
        $timestamp = $matches[1];
        $activity = $matches[2];
        $detail = $matches[3];
        $ip = $matches[4];

        // **Exclude any log entry related to 404 errors**
        if (stripos($activity, '404 Not Found') !== false) {
            continue;  // Skip this entry
        }

        // Only process desired activities
        if (array_key_exists($activity, $activitiesToTrack)) {
            $message = '';
            $filename = trim(str_replace("Filename:", "", $detail));
            $icon = $activitiesToTrack[$activity]; // Get the icon class based on activity

            switch ($activity) {
                case 'Page Created': 
                    $message = "Page \"<strong>$filename</strong>\" was Created."; 
                    break;
                case 'Page Edited': 
                    $message = "Page \"<strong>$filename</strong>\" was Edited."; 
                    break;
                case 'Post Created': 
                    $message = "Post \"<strong>$filename</strong>\" was Created."; 
                    break;
                case 'Post Edited': 
                    $message = "Post \"<strong>$filename</strong>\" was Edited."; 
                    break;
                case 'Post Deleted': 
                    $message = "Post \"<strong>$filename</strong>\" was Deleted."; 
                    break;
                case 'Page Deleted': 
                    $message = "Page \"<strong>$filename</strong>\" was Deleted."; 
                    break;
                case 'Image Uploaded': 
                    $message = "Image \"<strong>$filename</strong>\" was Uploaded."; 
                    break;
                case 'Image Deleted': 
                    $message = "Image \"<strong>$filename</strong>\" was Deleted."; 
                    break;
                case 'Admin Login': 
                case 'User Login':
                    $username = trim(str_replace("Username:", "", $detail));
                    $message = "$activity \"<strong>$username</strong>\" Logged in."; 
                    break;
                case 'Site Configuration': 
                    $message = "Site configuration settings were updated."; 
                    break;
            }

            // Store the message and the associated icon
            $activityMessages[] = [
                'message'   => $message,
                'timestamp' => $timestamp,  // Save raw timestamp for debugging
                'formattedTimestamp' => formatTimestamp($timestamp),  // Formatted timestamp
                'ip'        => $ip,
                'icon'      => $icon
            ];
        }
    }
}

// Sort activities in descending order by timestamp (newest first)
usort($activityMessages, function($a, $b) {
    // Try to convert raw timestamps to a comparable format
    $timeA = strtotime($a['timestamp']);
    $timeB = strtotime($b['timestamp']);
    
    return $timeB - $timeA;
});

// Display the last 5 activities (most recent first)
$recentActivities = array_slice($activityMessages, 0, 5);

echo '<div class="list-group">';
foreach ($recentActivities as $activity) {
    echo '<div class="list-group-item d-flex justify-content-between" title="IP Address: ' . $activity['ip'] . '">';
    echo '<p class="mb-1"><i class="bi ' . $activity['icon'] . '"></i> ' . $activity['message'] . '</p>';
    echo '<span class="text-muted">' . $activity['formattedTimestamp'] . '</span>';
    echo '</div>';
}
echo '</div>';
?>


                </div>
            </div>
        </div>