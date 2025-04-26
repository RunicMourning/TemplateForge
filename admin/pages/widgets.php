<?php
// pages/widgets.php

$pageTitle = 'Widgets';

$widgetDir = __DIR__ . '/../widgets';
$widgetFiles = array_diff(scandir($widgetDir), array('.', '..'));

echo '<div class="row">';

if (!empty($widgetFiles)) {
    foreach ($widgetFiles as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') continue;

        echo '<div class="col-md-6 mb-4">';
        try {
            include $widgetDir . '/' . $file;
        } catch (Exception $e) {
            error_log("Error including widget file: " . $e->getMessage() . " in " . $widgetDir . '/' . $file);
            echo '<div class="alert alert-danger">Error loading widget: ' . htmlspecialchars($file) . '</div>';
        }
        echo '</div>'; // Close col-md-6
    }
} else {
    echo '<div class="col-12"><p class="mt-3">No widgets found.</p></div>';
}

echo '</div>'; // Close row

?>
