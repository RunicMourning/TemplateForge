<?php
// admin/delete_post.php

require_once 'login_module.php';
if (!isset($_GET['post'])) {
    die("No post specified.");
}
$filename = basename($_GET['post']);
$filePath = realpath(__DIR__ . '/../blog_posts') . "/$filename";
if (file_exists($filePath)) {
    if (unlink($filePath)) {
include __DIR__.'/../logger.php'; // Adjust path as needed
log_activity('Post Deleted', 'Filename: ' . $filename);
        header("Location: index.php?p=posts&msg=Post deleted successfully");
        exit;
    } else {
        die("Error deleting post.");
    }
} else {
    die("Post not found.");
}
?>
