<?php
$pageTitle = 'System Information';

function getServerLoad() {
    return function_exists('sys_getloadavg') ? sys_getloadavg() : null;
}

function getDiskUsage() {
    $total = disk_total_space('/');
    $free = disk_free_space('/');
    return round(($free / $total) * 100, 2) . '% free (' . round($free / 1073741824, 2) . ' GB / ' . round($total / 1073741824, 2) . ' GB)';
}

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
    'CPU Load'              => '' // Will handle separately
];

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
?>

<div class="container my-5">
    <h2 class="mb-4">System Information</h2>

    <div class="row g-4">
        <?php foreach ($systemInfo as $parameter => $value): ?>
            <?php if ($parameter === 'CPU Load') continue; ?>
            <div class="col-md-6">
                <div class="d-flex align-items-start bg-light border rounded p-3 shadow-sm h-100">
                    <i class="<?php echo $iconMapping[$parameter] ?? 'bi bi-info-circle'; ?> fs-3 me-3 text-primary"></i>
                    <div>
                        <h6 class="mb-1 fw-semibold"><?php echo $parameter; ?></h6>
                        <p class="mb-0 text-muted small"><?php echo nl2br(htmlspecialchars($value)); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- CPU Load (Special Layout) -->
    <div class="row mt-5">
        <div class="col">
            <div class="bg-white border rounded shadow-sm p-4">
                <h5 class="mb-4"><i class="bi bi-speedometer2 me-2 text-warning"></i> CPU Load Averages</h5>
                <?php $load = getServerLoad(); ?>
                <?php if ($load !== null): ?>
                    <?php $labels = ['1 min', '5 min', '15 min']; ?>
                    <?php foreach ($load as $i => $val): ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted"><?php echo $labels[$i]; ?></span>
                                <span class="fw-semibold"><?php echo $val; ?>%</span>
                            </div>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-<?php echo ['primary', 'info', 'warning'][$i]; ?>" role="progressbar" style="width: <?php echo $val; ?>%;" aria-valuenow="<?php echo $val; ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?php echo $val; ?>%
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">CPU load data is not available on this server.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
