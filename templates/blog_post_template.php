<article class="mt-5">
  <!-- Post Title -->
  <header class="mb-4">
    <h1 class="display-5 fw-bold mb-2">
      <?php echo htmlspecialchars($postTitle); ?>
    </h1>
    <p class="text-muted small">
      Published on 
      <?php 
        $date = new DateTime($postTimestamp); 
        echo $date->format('F j, Y g:i A'); 
      ?>
    </p>
  </header>

  <!-- Post Content -->
  <section class="post-content mb-5">
    <?php echo $postContent; ?>
  </section>

  <!-- Optional: Comments Section Placeholder -->
<!--  <footer class="border-top pt-4 mt-4">
    <h6 class="text-muted">Comments</h6>
	Comments Here
  </footer>-->
</article>
