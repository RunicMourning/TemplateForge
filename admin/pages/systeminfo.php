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

// Store extensions as an array
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
    'Loaded PHP Extensions' => get_loaded_extensions(), // <-- ARRAY
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

// Extension-specific icon mapping
$extensionIcons = [
    'Core' => 'bi-gear-fill',
    'PDO' => 'bi-database',
    'Phar' => 'bi-box-seam',
    'Reflection' => 'bi-search',
    'SPL' => 'bi-diagram-3',
    'SimpleXML' => 'bi-code-slash',
    'bcmath' => 'bi-calculator',
    'bz2' => 'bi-file-zip',
    'calendar' => 'bi-calendar-event',
    'cgi-fcgi' => 'bi-terminal',
    'ctype' => 'bi-fonts',
    'curl' => 'bi-arrow-left-right',
    'date' => 'bi-calendar3',
    'dba' => 'bi-collection',
    'dom' => 'bi-diagram-3',
    'exif' => 'bi-image',
    'fileinfo' => 'bi-file-earmark-text',
    'filter' => 'bi-funnel',
    'ftp' => 'bi-cloud-arrow-up',
    'gd' => 'bi-image',
    'gettext' => 'bi-translate',
    'gmp' => 'bi-calculator',
    'hash' => 'bi-shield-lock',
    'iconv' => 'bi-emoji-smile',
    'imap' => 'bi-envelope',
    'intl' => 'bi-globe',
    'json' => 'bi-braces',
    'libxml' => 'bi-file-code',
    'mbstring' => 'bi-text-paragraph',
    'mysqli' => 'bi-database-check',
    'mysqlnd' => 'bi-database-check',
    'openssl' => 'bi-lock',
    'pcre' => 'bi-slash-square',
    'pdo_mysql' => 'bi-database-check',
    'pdo_sqlite' => 'bi-database-check',
    'posix' => 'bi-terminal',
    'session' => 'bi-box-arrow-in-right',
    'shmop' => 'bi-memory',
    'soap' => 'bi-droplet',
    'sodium' => 'bi-shield-check',
    'sqlite3' => 'bi-database-fill',
    'standard' => 'bi-gear',
    'tidy' => 'bi-magic',
    'tokenizer' => 'bi-scissors',
    'xml' => 'bi-file-code',
    'xmlreader' => 'bi-journal-text',
    'xmlwriter' => 'bi-pencil-square',
    'xsl' => 'bi-diagram-3',
    'zip' => 'bi-file-zip',
    'zlib' => 'bi-file-zip'
];?>

<style type="text/css">
/* PHP Extension Icons - Font Size & Color */
.bi-image { color: #f39c12; font-size: 1.2rem; }           /* GD / Exif */
.bi-file-zip { color: #e67e22; font-size: 1.2rem; }        /* bz2 / zip / zlib */
.bi-database, .bi-database-check { color: #27ae60; font-size: 1.2rem; } /* database */
.bi-lock, .bi-shield-check { color: #8e44ad; font-size: 1.2rem; }       /* crypto/security */
.bi-calculator { color: #16a085; font-size: 1.2rem; }      /* math */
.bi-braces { color: #2980b9; font-size: 1.2rem; }          /* JSON */
.bi-diagram-3, .bi-code-slash { color: #c0392b; font-size: 1.2rem; }   /* DOM/XML/SPL/XSL */
.bi-translate, .bi-globe { color: #d35400; font-size: 1.2rem; }        /* i18n / intl */
.bi-text-paragraph { color: #2c3e50; font-size: 1.2rem; }  /* mbstring */
.bi-gear-fill, .bi-gear { color: #7f8c8d; font-size: 1.2rem; }         /* Core / standard */
.bi-box-seam { color: #1abc9c; font-size: 1.2rem; }         /* Phar */
.bi-search { color: #34495e; font-size: 1.2rem; }           /* Reflection */
.bi-terminal { color: #95a5a6; font-size: 1.2rem; }         /* CGI / POSIX */
.bi-fonts { color: #f1c40f; font-size: 1.2rem; }            /* ctype */
.bi-arrow-left-right { color: #3498db; font-size: 1.2rem; } /* curl */
.bi-calendar-event, .bi-calendar3 { color: #e74c3c; font-size: 1.2rem; } /* calendar/date */
.bi-file-earmark-text { color: #d35400; font-size: 1.2rem; } /* fileinfo */
.bi-funnel { color: #9b59b6; font-size: 1.2rem; }           /* filter */
.bi-cloud-arrow-up { color: #1abc9c; font-size: 1.2rem; }   /* ftp */
.bi-emoji-smile { color: #f39c12; font-size: 1.2rem; }      /* iconv */
.bi-droplet { color: #3498db; font-size: 1.2rem; }          /* soap */
.bi-journal-text, .bi-pencil-square { color: #c0392b; font-size: 1.2rem; } /* xmlreader / xmlwriter */


.circular-progress .bg {
    stroke: #e9ecef; /* light gray background */
}

.circular-progress .progress {
    stroke-linecap: round;
    transform: rotate(-90deg);
    transform-origin: 50% 50%;
    transition: stroke-dashoffset 0.5s ease;
}

.circular-progress .progress-text {
    font-size: 1rem;
    font-weight: bold;
    fill: #333;
}

</style>



<div class="container my-5">
    <h2 class="mb-4">System Information</h2>



<!-- CPU Load (Circular Layout) -->
<div class="row mb-4">
    <div class="col">
        <div class=" bg-white border rounded shadow-sm p-4">
            <h5 class="mb-4">
                <i class="bi bi-speedometer2 me-2 text-warning"></i> CPU Load Averages
            </h5>

            <?php 
            $load = getServerLoad();
            $labels = ['1 min', '5 min', '15 min'];
            $colors = ['#0d6efd','#0dcaf0','#ffc107']; // Bootstrap primary, info, warning
            ?>

            <?php if ($load !== null): ?>
                <div class="d-flex justify-content-around flex-wrap">
                    <?php foreach ($load as $i => $val): 
                        $radius = 45;
                        $circumference = 2 * pi() * $radius;
                        $offset = $circumference - ($val/100) * $circumference;
                    ?>
                        <div class="text-center m-2">
                            <svg class="circular-progress" width="100" height="100">
                                <circle class="bg" cx="50" cy="50" r="<?= $radius ?>" stroke-width="10" fill="none"/>
                                <circle class="progress" cx="50" cy="50" r="<?= $radius ?>" stroke-width="10" fill="none"
                                        stroke-dasharray="<?= $circumference ?>"
                                        stroke-dashoffset="<?= $offset ?>"
                                        style="stroke: <?= $colors[$i] ?>;"
                                />
                                <text x="50%" y="50%" text-anchor="middle" dy=".3em" class="progress-text"><?= $val ?>%</text>
                            </svg>
                            <small class="text-muted mt-2 d-block"><?= $labels[$i] ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-muted">CPU load data is not available on this server.</p>
            <?php endif; ?>
        </div>
    </div>
</div>



    <!-- Regular system info cards -->
    <div class="row g-4">
        <?php foreach ($systemInfo as $parameter => $value): ?>
            <?php if ($parameter === 'CPU Load' || $parameter === 'Loaded PHP Extensions') continue; ?>
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

    <!-- Loaded PHP Extensions (Icon Grid Box Style) -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="bg-light border rounded shadow-sm p-4">
                <h5 class="mb-4"><i class="bi bi-puzzle me-2 text-success"></i> Loaded PHP Extensions</h5>
                <div class="row g-3">
                    <?php 
                    $extensions = $systemInfo['Loaded PHP Extensions'];
                    sort($extensions); // Alphabetical

                    foreach ($extensions as $ext):
                        $icon = $extensionIcons[$ext] ?? 'bi bi-puzzle';
                    ?>
                        <div class="col-6 col-sm-4 col-md-2 col-lg-1">
                            <div class="d-flex flex-column align-items-center justify-content-center bg-white border rounded shadow-sm p-2" style="height:75px;">
                                <i class="<?php echo $icon; ?> mb-1"></i>
                                <span class="small text-center"><?php echo htmlspecialchars($ext); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>