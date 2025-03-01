<?php
// templates/blog_post_template.php
// This template outputs content for a single blog post (inside the body)
?>
<article class="card shadow-sm">
  <div class="card-body">
    <!-- Post Title -->
    <h1 class="card-title"><?php echo htmlspecialchars($postTitle); ?></h1>

    <!-- Post Metadata -->
    <p class="text-muted">
      Published on 
      <?php 
        $date = new DateTime($postTimestamp); 
        echo $date->format('F j, Y g:i A'); 
      ?>
    </p>

    <!-- Post Content -->
    <div class="post-content">
      <?php echo $postContent; ?>
    </div>

    <!-- comments -->
  </div>
</article>
