<?php
// logger.php

if (!function_exists('log_activity')) {
    function log_activity($event, $details = '', $maxLines = 500) {
        $logFile = __DIR__.'/activity.log';
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown IP';
        $logEntry = "[$timestamp] - $event - $details - IP: $ip" . PHP_EOL;

        $logs = file_exists($logFile)
            ? file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
            : [];

        $logs[] = $logEntry;

        if (count($logs) > $maxLines) {
            $logs = array_slice($logs, -$maxLines);
        }

        file_put_contents($logFile, implode(PHP_EOL, $logs) . PHP_EOL);
    }
}
?>
