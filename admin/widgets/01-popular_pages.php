<?php
// admin/widgets/popular_pages.php

$logFile = __DIR__ . '/../../config/data/analytics.json';

// Check analytics JSON
if (!file_exists($logFile)) {
    echo '<div class="alert alert-warning">Analytics data not found.</div>';
    return;
}

// Load analytics
$analyticsRaw = json_decode(file_get_contents($logFile), true);
if (!$analyticsRaw) {
    echo '<div class="alert alert-warning">Invalid analytics data.</div>';
    return;
}

// Calculate total views per page
$pageViews = [];
foreach ($analyticsRaw as $slug => $data) {
    if (in_array($slug, ['404', 'admin'])) continue;
    $pageViews[$slug] = array_sum($data['views'] ?? []);
}

// Sort descending
arsort($pageViews);

// Separate blog posts and pages
$blogPostsList = [];
$pagesList = [];
foreach ($pageViews as $slug => $views) {
    if (str_starts_with($slug, 'blog:')) {
        $postSlug = substr($slug, strlen('blog:'));
        $safeSlug = preg_replace('/[^a-z0-9\-]/i', '-', $postSlug);
        $blogPostsList[] = [
            'title' => $analyticsRaw[$slug]['title'] ?? ucfirst($postSlug),
            'views' => $views,
            'url' => '/blog-' . htmlspecialchars($safeSlug) . '.html'
        ];
    } else {
        $pagesList[] = [
            'title' => $analyticsRaw[$slug]['title'] ?? ucfirst($slug),
            'views' => $views,
            'url' => '/' . htmlspecialchars($slug) . '.html'
        ];
    }
}

// Limit top 5 each
$blogPostsList = array_slice($blogPostsList, 0, 5);
$pagesList = array_slice($pagesList, 0, 5);
?>

<div class="card mt-3 shadow-sm border border-light rounded-4">
  <div class="card-header bg-white d-flex justify-content-between align-items-center rounded-top-4 py-2">
    <div>
      <h6 class="mb-0 text-dark">
        <i class="bi bi-bar-chart-line me-2 text-success"></i> Popular Pages
      </h6>
      <small class="text-muted">Top viewed pages/posts</small>
    </div>
    <span class="badge bg-success-subtle text-success fw-semibold">Top 10</span>
  </div>
  <div class="card-body py-2">
    <?php if (!empty($blogPostsList) || !empty($pagesList)): ?>

      <!-- Blog Posts Section -->
      <?php if (!empty($blogPostsList)): ?>
      <h6 class="text-info mb-1 small"><i class="bi bi-file-text me-1"></i> Blog Posts</h6>
      <ul class="list-unstyled mb-2 ps-1">
        <?php foreach ($blogPostsList as $post): ?>
        <li class="d-flex justify-content-between align-items-center mb-1">
          <a href="<?= $post['url'] ?>" class="text-decoration-none">
            <?= htmlspecialchars($post['title']) ?>
          </a>
          <span class="badge bg-info-subtle text-info px-2 py-1 small"><?= $post['views'] ?> views</span>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>

      <!-- Pages Section -->
      <?php if (!empty($pagesList)): ?>
      <h6 class="text-primary mb-1 small"><i class="bi bi-file-earmark me-1"></i> Pages</h6>
      <ul class="list-unstyled mb-0 ps-1">
        <?php foreach ($pagesList as $page): ?>
        <li class="d-flex justify-content-between align-items-center mb-1">
          <a href="<?= $page['url'] ?>" class="text-decoration-none">
            <?= htmlspecialchars($page['title']) ?>
          </a>
          <span class="badge bg-primary-subtle text-primary px-2 py-1 small"><?= $page['views'] ?> views</span>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>

    <?php else: ?>
      <p class="text-muted mb-0">No page view data available.</p>
    <?php endif; ?>

    <a href="/admin/index.php?p=analytics" class="btn btn-sm btn-outline-success mt-2">
      <i class="bi bi-arrow-right-circle me-1"></i> View Full Analytics
    </a>
  </div>
</div>
