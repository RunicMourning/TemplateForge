<?php
$current_page = basename($_SERVER['PHP_SELF']);  // Get the current page name
?>

<li class="nav-item">
  <a href="index.php" class="nav-link py-3 border-bottom rounded-0 <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" title="Home">
    <i class="bi bi-house"></i><br>Home
  </a>
</li>
<li>
  <a href="create_page.php" class="nav-link py-3 border-bottom rounded-0 <?php echo ($current_page == 'create_page.php') ? 'active' : ''; ?>" title="Add page">
    <i class="bi bi-file-earmark-plus"></i><br>Add page
  </a>
</li>
<li>
  <a href="pages.php" class="nav-link py-3 border-bottom rounded-0 <?php echo ($current_page == 'pages.php') ? 'active' : ''; ?>" title="Edit page">
    <i class="bi bi-pencil-square"></i><br>Edit page
  </a>
</li>
<li>
  <a href="create_post.php" class="nav-link py-3 border-bottom rounded-0 <?php echo ($current_page == 'create_post.php') ? 'active' : ''; ?>" title="Add Post">
    <i class="bi bi-file-text"></i><br>Add Post
  </a>
</li>
<li>
  <a href="posts.php" class="nav-link py-3 border-bottom rounded-0 <?php echo ($current_page == 'posts.php') ? 'active' : ''; ?>" title="Edit Post">
    <i class="bi bi-pencil"></i><br>Edit Post
  </a>
</li>
<li>
  <a href="uploads.php" class="nav-link py-3 border-bottom rounded-0 <?php echo ($current_page == 'uploads.php') ? 'active' : ''; ?>" title="Upload Images">
    <i class="bi bi-upload"></i><br>Upload Images
  </a>
</li>
