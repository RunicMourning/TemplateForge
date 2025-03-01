<?php
// templates/blog_list_template.php
// This template outputs content for the blog listing (inside the body)

?>

	  
    <?php foreach ($pagedPosts as $post): ?>
<article class="blog-post mt-3">
  <div class="card">
    <h5 class="card-header">
      <a href="blog-<?php echo urlencode($post['slug']); ?>.html">
        <?php echo htmlspecialchars($post['title']); ?>
      </a>
    </h5>
    <div class="card-body">
      <p class="card-text"><?php echo htmlspecialchars($post['excerpt']); ?></p>
    </div>
    <div class="card-footer d-flex justify-content-between align-items-center">
      <small class="text-body-secondary">
        <?php
        // Create DateTime object
        $date = new DateTime($post['timestamp']);
        // Convert to a different format
        echo $date->format('F j, Y g:i A'); // Example: December 3, 2025 11:25 AM
        ?>
      </small>
      <a class="btn btn-danger" href="blog-<?php echo urlencode($post['slug']); ?>.html">Read More</a>
    </div>
  </div>
</article>
    <?php endforeach; ?>