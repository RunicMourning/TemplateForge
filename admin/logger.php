<?php
// logger.php

function log_activity($event, $details = '', $maxLines = 500) {
    $logFile = __DIR__.'/activity.log'; // Adjust path as needed
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown IP';
    $logEntry = "[$timestamp] - $event - $details - IP: $ip" . PHP_EOL;

    // Read the existing log file
    if (file_exists($logFile)) {
        $logs = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    } else {
        $logs = [];
    }

    // Append new log entry
    $logs[] = $logEntry;

    // Trim log file if it exceeds the max lines
    if (count($logs) > $maxLines) {
        $logs = array_slice($logs, -$maxLines); // Keep only the last $maxLines entries
    }

    // Write back to the file
    file_put_contents($logFile, implode(PHP_EOL, $logs) . PHP_EOL);
}
?>
