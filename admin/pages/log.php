<?php
// pages/log.php

$pageTitle = 'Activity Log';
include_once __DIR__ . '/../logger.php';

$logFile = __DIR__ . '/../activity.log';
if (!file_exists($logFile)) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Log file not found.</div></div>";
    return;
}

$lines = file($logFile, FILE_IGNORE_NEW_LINES);
$resultsPerPage = 50;
$totalLines     = count($lines);
$totalPages     = ceil($totalLines / $resultsPerPage);
$page           = max(1, (int)($_GET['page'] ?? 1));
$start          = ($page - 1) * $resultsPerPage;
$entries        = array_slice($lines, $start, $resultsPerPage);

function formatTimestamp($ts) {
    return date("F j, Y, g:i a", strtotime($ts));
}

function getActivityData($type) {
    $icons = [
        '404 not found'      => ['bi-exclamation-triangle-fill text-danger',      '404 Not Found'],
        'page created'       => ['bi-file-earmark-plus-fill text-success',        'Page Created'],
        'page edited'        => ['bi-pencil-square text-primary',                'Page Edited'],
        'page deleted'       => ['bi-file-earmark-x-fill text-danger',           'Page Deleted'],
        'post created'       => ['bi-file-plus-fill text-success',               'Post Created'],
        'post edited'        => ['bi-pencil-fill text-warning',                  'Post Edited'],
        'post deleted'       => ['bi-file-earmark-x-fill text-danger',           'Post Deleted'],
        'image uploaded'     => ['bi-file-earmark-image-fill text-success',      'Image Uploaded'],
        'image deleted'      => ['bi-file-earmark-x-fill text-danger',           'Image Deleted'],
        'admin login'        => ['bi-person-check-fill text-primary',            'Admin Login'],
        'failed login'       => ['bi-person-x-fill text-danger',                  'Failed Login'],
        'site configuration' => ['bi-gear-fill text-info',                       'Site Configured'],
        'settings updated'   => ['bi-tools text-info',                           'Settings Updated'],
    ];
    $lower = strtolower($type);
    foreach ($icons as $key => [$icon, $label]) {
        if (strpos($lower, $key) !== false) {
            return ['icon' => $icon, 'label' => $label];
        }
    }
    return ['icon' => 'bi-info-circle-fill text-secondary', 'label' => $type];
}
?>
<div class="container my-5">
  <div class="card shadow-sm">
    <div class="card-header bg-white d-flex align-items-center">
      <i class="bi bi-list-check me-2 fs-4"></i>
      <h3 class="mb-0">Activity Log</h3>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover table-striped align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Activity</th>
              <th>Date</th>
              <th>IP Address</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($entries as $line): ?>
              <?php if (preg_match('/^\[(.*?)\]\s*-\s*(.*)/', $line, $m)): ?>
                <?php
                  $ts = $m[1];
                  $rest = $m[2];
                  list($actPart, $ipPart) = array_pad(explode(' - IP:', $rest, 2), 2, '');
                  list($type, $detail) = array_pad(explode(' - ', $actPart, 2), 2, '');
                  $data = getActivityData($type);
                ?>
                <tr>
                  <td>
                    <span data-bs-toggle="tooltip" title="<?= htmlspecialchars($data['label']) ?>">
                      <i class="bi <?= $data['icon'] ?>"></i>
                    </span>
                    <?= ' ' . htmlspecialchars($data['label']) ?>
                    <?php if ($detail): ?>
                      <br><small class="text-muted"><?= htmlspecialchars($detail) ?></small>
                    <?php endif; ?>
                  </td>
                  <td><?= htmlspecialchars(formatTimestamp($ts)) ?></td>
                  <td><?= htmlspecialchars($ipPart) ?></td>
                </tr>
              <?php endif; ?>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer bg-white">
      <nav>
        <ul class="pagination justify-content-center mb-0">
          <?php if ($page > 1): ?>
            <li class="page-item"><a class="page-link" href="?p=log&page=<?= $page - 1 ?>">Previous</a></li>
          <?php endif; ?>
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
              <a class="page-link" href="?p=log&page=<?= $i ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
          <?php if ($page < $totalPages): ?>
            <li class="page-item"><a class="page-link" href="?p=log&page=<?= $page + 1 ?>">Next</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </div>
</div>
<script>
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
</script>