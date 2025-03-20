<?php
$pageTitle = 'System Information';


// Function to get system load (Linux only)
function getServerLoad() {
    if (function_exists('sys_getloadavg')) {
        $load = sys_getloadavg();
        // Note: In a real scenario, load averages are not percentages.
        // Here we assume they represent percentages for display purposes.
        return $load;
    }
    return null;
}

// Function to get disk space info
function getDiskUsage() {
    $total = disk_total_space('/');
    $free = disk_free_space('/');
    return round(($free / $total) * 100, 2) . '% free (' . round($free / 1073741824, 2) . ' GB / ' . round($total / 1073741824, 2) . ' GB)';
}

// Prepare system information in an associative array
$systemInfo = [
    'Server Hostname'       => gethostname(),
    'Server Software'       => $_SERVER['SERVER_SOFTWARE'],
    'PHP Version'           => phpversion(),
    'Operating System'      => php_uname(),
    'Server IP & Port'      => $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'],
    'Memory Limit'          => ini_get('memory_limit'),
    'Max Execution Time'    => ini_get('max_execution_time') . ' seconds',
    'Max Upload Size'       => ini_get('upload_max_filesize'),
    'Max POST Size'         => ini_get('post_max_size'),
    'Disk Usage'            => getDiskUsage(),
    'Loaded PHP Extensions' => implode(', ', get_loaded_extensions()),
    'CPU Load'              => '' // Special handling for CPU Load below
];

// Mapping of system parameters to Bootstrap Icons
$iconMapping = [
    'Server Hostname'       => 'bi bi-hdd-network',
    'Server Software'       => 'bi bi-server',
    'PHP Version'           => 'bi bi-code-slash',
    'Operating System'      => 'bi bi-cpu',
    'Server IP & Port'      => 'bi bi-globe',
    'Memory Limit'          => 'bi bi-memory',
    'Max Execution Time'    => 'bi bi-clock',
    'Max Upload Size'       => 'bi bi-upload',
    'Max POST Size'         => 'bi bi-box-arrow-up',
    'Disk Usage'            => 'bi bi-hdd',
    'Loaded PHP Extensions' => 'bi bi-puzzle',
    'CPU Load'              => 'bi bi-speedometer2'
];

// Array of Bootstrap colors to cycle through for other cards
$colors = ['primary', 'success', 'info', 'warning', 'danger', 'secondary'];
?>
<style>
    .my-card {
        position: absolute;
        left: 40%;
        top: -20px;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<h2 class="mb-4">System Information</h2>
<div class="container">
    <div class="row">
        <?php $i = 0; ?>
        <?php foreach ($systemInfo as $parameter => $value): ?>
            <?php 
                $color = $colors[$i % count($colors)];
                $icon = isset($iconMapping[$parameter]) ? $iconMapping[$parameter] : 'bi bi-info-circle';
            ?>
            <div class="col-md-3 mb-4">
                <div class="card border-<?php echo $color; ?> mx-1 p-3 position-relative">
                    <!-- Icon container with matching background and white icon -->
                    <div class="card bg-<?php echo $color; ?> shadow text-white p-3 my-card text-center">
                        <i class="<?php echo $icon; ?>" style="font-size: 2rem;"></i>
                    </div>
                    <div class="text-dark text-center mt-4">
                        <h4><?php echo $parameter; ?></h4>
                    </div>
                    <div class="text-dark text-center mt-2">
                        <?php if ($parameter === 'CPU Load'): ?>
                            <?php $load = getServerLoad(); ?>
                            <?php if ($load !== null): ?>
                                <div class="mb-2">1 min: <span class="fw-bold"><?php echo $load[0]; ?>%</span></div>
                                <div class="progress mb-3" style="height: 20px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $load[0]; ?>%;" aria-valuenow="<?php echo $load[0]; ?>" aria-valuemin="0" aria-valuemax="100">
                                        <?php echo $load[0]; ?>%
                                    </div>
                                </div>
                                <div class="mb-2">5 min: <span class="fw-bold"><?php echo $load[1]; ?>%</span></div>
                                <div class="progress mb-3" style="height: 20px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $load[1]; ?>%;" aria-valuenow="<?php echo $load[1]; ?>" aria-valuemin="0" aria-valuemax="100">
                                        <?php echo $load[1]; ?>%
                                    </div>
                                </div>
                                <div class="mb-2">15 min: <span class="fw-bold"><?php echo $load[2]; ?>%</span></div>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo $load[2]; ?>%;" aria-valuenow="<?php echo $load[2]; ?>" aria-valuemin="0" aria-valuemax="100">
                                        <?php echo $load[2]; ?>%
                                    </div>
                                </div>
                            <?php else: ?>
                                <p>Load not available</p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p><?php echo $value; ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php $i++; ?>
        <?php endforeach; ?>
    </div>
</div>
