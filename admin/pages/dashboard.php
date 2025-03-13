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
                <li class="list-group-item"><i class="bi bi-speedometer2"></i> Dashboard</li>
                <li class="list-group-item"><i class="bi bi-file-earmark-text"></i> Pages</li>
                <li class="list-group-item"><i class="bi bi-stickies"></i> Blog Posts</li>
                <li class="list-group-item"><i class="bi bi-images"></i> Media Library</li>
                <li class="list-group-item"><i class="bi bi-gear"></i> Settings</li>
                <li class="list-group-item"><i class="bi bi-server"></i> System Info</li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="bg-light p-4 rounded">
                <h3>Dashboard</h3>
                <p>Welcome to your website management dashboard.</p>

                <!-- Stats Overview -->

    <div class="bg-light p-5 rounded">
        <div class="row w-100">
            <!-- Total Pages -->
            <div class="col-md-3">
                <div class="card border-primary mx-1 p-3">
                    <div class="card border-primary shadow text-primary p-3 my-card">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div class="text-primary text-center mt-3"><h4>Total Pages</h4></div>
                    <div class="text-primary text-center mt-2"><h1><?php echo $total_pages; ?></h1></div>
                </div>
            </div>
            
            <!-- Total Posts -->
            <div class="col-md-3">
                <div class="card border-success mx-1 p-3">
                    <div class="card border-success shadow text-success p-3 my-card">
                        <i class="bi bi-stickies"></i>
                    </div>
                    <div class="text-success text-center mt-3"><h4>Total Posts</h4></div>
                    <div class="text-success text-center mt-2"><h1><?php echo $total_posts; ?></h1></div>
                </div>
            </div>

            <!-- PHP Version -->
            <div class="col-md-3">
                <div class="card border-danger mx-1 p-3">
                    <div class="card border-danger shadow text-danger p-3 my-card">
                        <i class="bi bi-code-slash"></i>
                    </div>
                    <div class="text-danger text-center mt-3"><h4>PHP Version</h4></div>
                    <div class="text-danger text-center mt-2"><h1><?php echo phpversion(); ?></h1></div>
                </div>
            </div>

            <!-- Entire Directory Size -->
            <div class="col-md-3">
                <div class="card border-warning mx-1 p-3">
                    <div class="card border-warning shadow text-warning p-3 my-card">
                        <i class="bi bi-folder2"></i>
                    </div>
                    <div class="text-warning text-center mt-3"><h4>Directory Size</h4></div>
                    <div class="text-warning text-center mt-2"><h1><?php echo $formattedSize; ?></h1></div>
                </div>
            </div>
        </div>
    </div>


                <!-- Recent Activity -->
                <div class="mt-4">
                    <h5>Recent Activity</h5>
                    <ul class="list-group">
                        <li class="list-group-item">Page "<strong>About Us</strong>" was updated.</li>
                        <li class="list-group-item">New blog post "<strong>How to Use Bootstrap</strong>" was published.</li>
                        <li class="list-group-item">User <strong>admin</strong> logged in.</li>
                    </ul>
                </div>

                <!-- Quick Actions -->
                <div class="mt-4">
                    <h5>Quick Actions</h5>
                    <button class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New Page</button>
                    <button class="btn btn-success"><i class="bi bi-pencil-square"></i> Add New Post</button>
                    <button class="btn btn-warning"><i class="bi bi-upload"></i> Upload Media</button>
                </div>

                <!-- System Health -->
                <div class="mt-4">
                    <h5>System Health</h5>
                    <p><i class="bi bi-check-circle text-success"></i> Everything is running smoothly.</p>
                </div>
            </div>
        </div>
    </div>
</div>
