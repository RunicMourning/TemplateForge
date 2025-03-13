
<?php
// Function to get system uptime (Linux only)
function getServerUptime() {
    if (PHP_OS_FAMILY === 'Linux') {
        return shell_exec('uptime -p');
    }
    return 'Uptime not available';
}

// Function to get system load (Linux only)
function getServerLoad() {
    if (function_exists('sys_getloadavg')) {
        $load = sys_getloadavg();
        return $load[0] . '% (1 min), ' . $load[1] . '% (5 min), ' . $load[2] . '% (15 min)';
    }
    return 'Load not available';
}

// Function to get disk space info
function getDiskUsage() {
    $total = disk_total_space('/');
    $free = disk_free_space('/');
    return round(($free / $total) * 100, 2) . '% free (' . round($free / 1073741824, 2) . ' GB / ' . round($total / 1073741824, 2) . ' GB)';
}

// Function to get recent logins (Linux only)
function getRecentLogins() {
    if (PHP_OS_FAMILY === 'Linux') {
        return shell_exec('who');
    }
    return 'Login data not available';
}

?>

<h2>System Information</h2>
<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse: collapse;">
    <tr><th>Parameter</th><th>Value</th></tr>
    <tr><td>Server Hostname</td><td><?php echo gethostname(); ?></td></tr>
    <tr><td>Server Software</td><td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td></tr>
    <tr><td>PHP Version</td><td><?php echo phpversion(); ?></td></tr>
    <tr><td>Operating System</td><td><?php echo php_uname(); ?></td></tr>
    <tr><td>Server IP & Port</td><td><?php echo $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT']; ?></td></tr>
    <tr><td>Client IP</td><td><?php echo $_SERVER['REMOTE_ADDR']; ?></td></tr>
    <tr><td>Memory Limit</td><td><?php echo ini_get('memory_limit'); ?></td></tr>
    <tr><td>Max Execution Time</td><td><?php echo ini_get('max_execution_time') . ' seconds'; ?></td></tr>
    <tr><td>Max Upload Size</td><td><?php echo ini_get('upload_max_filesize'); ?></td></tr>
    <tr><td>Max POST Size</td><td><?php echo ini_get('post_max_size'); ?></td></tr>
    <tr><td>Loaded PHP Extensions</td><td><?php echo implode(', ', get_loaded_extensions()); ?></td></tr>
    <tr><td>Disk Usage</td><td><?php echo getDiskUsage(); ?></td></tr>
    <tr><td>CPU Load</td><td><?php echo getServerLoad(); ?></td></tr>
    <tr><td>Server Uptime</td><td><?php echo getServerUptime(); ?></td></tr>
    <tr><td>Recent Logins</td><td><pre><?php echo getRecentLogins(); ?></pre></td></tr>
</table>
