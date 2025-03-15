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
                <li class="list-group-item"><a href="index.php" class="link-underline-light"><i class="bi bi-gear"></i> Settings</a></li>
                <li class="list-group-item"><a href="index.php?p=systeminfo" class="link-underline-light"><i class="bi bi-server"></i> System Info</a></li>
            </ul>
			
<?php
// Define the directory containing sidebar files
$sidebarDir = 'sidebars';

// Scan the directory while ignoring the default "." and ".." entries
$sidebarFiles = array_diff(scandir($sidebarDir), array('.', '..'));

// Check if any files were found
if (!empty($sidebarFiles)) {
    // Loop through each file in the sidebar directory
    foreach ($sidebarFiles as $file) {
        // Optionally, you can filter by file type (e.g., only include PHP files)
        if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
            continue;
        }
        
        // Build the file path
        $filePath = $sidebarDir . '/' . $file;
        
        // Include the sidebar file; error handling can be added as needed
        include $filePath;
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
                <div class="mt-4">
                    <h5><i class="bi bi-list-check"></i> <a href="index.php?p=log" class="link-underline-light link-dark">Recent Activity</a></h5>
                    <?php
                    // Assuming the log data is stored in a file
                    $logFile = __DIR__.'/../activity.log';
                    $lines = file($logFile, FILE_IGNORE_NEW_LINES);  // Read log file line by line

                    // Define the activities you want to track (excluding 404 errors)
                    $activitiesToTrack = ['Page Created', 'Page Edited', 'Post Created', 'Post Edited', 'Post Deleted', 'Page Deleted'];

                    // Function to format timestamp
                    function formatTimestamp($timestamp) {
                        return date("F j, Y, g:i a", strtotime($timestamp));  // E.g., March 13, 2025, 12:27 pm
                    }

                    // Filter and display the selected activities
                    $activityMessages = [];
                    foreach ($lines as $line) {
                        preg_match('/^\[(.*?)\] - (.*?) - Filename: (.*?) - IP: (.*)$/', $line, $matches);
                        if (count($matches) === 5) {
                            $timestamp = $matches[1];
                            $activity = $matches[2];
                            $filename = $matches[3];
                            
                            // Ignore 404 errors and only process desired activities
                            if (in_array($activity, $activitiesToTrack)) {
                                // Adjust the activity messages as required
                                $message = '';
                                if ($activity === 'Page Created') {
                                    $message = "Page \"<strong>$filename</strong>\" was Created.";
                                } elseif ($activity === 'Page Edited') {
                                    $message = "Page \"<strong>$filename</strong>\" was Edited.";
                                } elseif ($activity === 'Post Created') {
                                    $message = "Post \"<strong>$filename</strong>\" was Created.";
                                } elseif ($activity === 'Post Edited') {
                                    $message = "Post \"<strong>$filename</strong>\" was Edited.";
                                } elseif ($activity === 'Post Deleted') {
                                    $message = "Post \"<strong>$filename</strong>\" was Deleted.";
                                } elseif ($activity === 'Page Deleted') {
                                    $message = "Page \"<strong>$filename</strong>\" was Deleted.";
                                }

                                // Format the message and add to the list
                                $activityMessages[] = [
                                    'message' => $message,
                                    'timestamp' => formatTimestamp($timestamp)
                                ];
                            }
                        }
                    }

                    // Limit to the most recent 5 items
                    $activityMessages = array_slice($activityMessages, 0, 5);

                    if (count($activityMessages) > 0):
                    ?>
                    <ul class="list-group">
                        <?php foreach ($activityMessages as $entry): ?>
                            <li class="list-group-item">
                                <?php echo $entry['message']; ?> <small class="text-muted"><?php echo $entry['timestamp']; ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                        <p>No recent activities to display.</p>
                    <?php endif; ?>
                </div>

                <!-- Quick Actions -->
                <!-- <div class="mt-4">
                    <h5>Quick Actions</h5>
                    <button class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New Page</button>
                    <button class="btn btn-success"><i class="bi bi-pencil-square"></i> Add New Post</button>
                    <button class="btn btn-warning"><i class="bi bi-upload"></i> Upload Media</button>
                </div> -->

            </div>
        </div>
    </div>
</div>
