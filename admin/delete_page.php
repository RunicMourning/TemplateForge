<?php
// admin/delete_page.php

require_once 'login_module.php';
if (!isset($_GET['page'])) {
    die("No page specified.");
}
$filename = basename($_GET['page']);
$filePath = realpath(__DIR__ . '/../pages') . "/$filename";
if (file_exists($filePath)) {
    if (unlink($filePath)) {
        header("Location: pages.php?msg=Page deleted successfully");
        exit;
    } else {
        die("Error deleting page.");
    }
} else {
    die("Page not found.");
}
?>
