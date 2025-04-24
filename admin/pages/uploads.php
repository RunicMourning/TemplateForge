<?php
// pages/uploads.php

$pageTitle = 'Uploads';
?>

<div class="container mt-5">
  <h2 class="mb-4"><i class="bi bi-cloud-upload-fill me-2 text-info"></i>Uploads</h2>
  <div class="bg-white border rounded shadow-sm p-4">
    <?php include __DIR__ . '/../upload_widget.php'; ?>
  </div>
</div>