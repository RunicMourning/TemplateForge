<div class="mt-4">
  <?php foreach ($pagedPosts as $post): ?>
    <article class="pb-4 mb-4 border-bottom">
      <h2 class="h5 mb-1">
        <a href="blog-<?php echo urlencode($post['slug']); ?>.html" class="text-decoration-underline text-dark">
          <?php echo htmlspecialchars($post['title']); ?>
        </a>
      </h2>
      <small class="text-muted">
        <?php
        $date = new DateTime($post['timestamp']);
        echo $date->format('F j, Y');
        ?>
      </small>
      <p class="mt-2 text-secondary small">
        <?php echo htmlspecialchars($post['excerpt']); ?>
      </p>
      <a href="blog-<?php echo urlencode($post['slug']); ?>.html" class="text-primary fw-semibold small">
        Continue Reading <i class="bi bi-arrow-right"></i>
      </a>
    </article>
  <?php endforeach; ?>
</div>
