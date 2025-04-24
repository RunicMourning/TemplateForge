<?php
// pages/gallery.php

$pageTitle = 'Media Library';
include_once __DIR__ . '/../logger.php';

$imagesDir  = __DIR__ . '/../../images/';
$yearDirs   = glob($imagesDir . '*', GLOB_ONLYDIR);

function getImages($dir) {
    return glob("$dir/*.{jpg,png,gif,jpeg,webp}", GLOB_BRACE);
}

if (isset($_POST['delete'])) {
    $imagePath = $_POST['delete'];
    if (file_exists($imagePath)) {
        unlink($imagePath);
        $fileName = basename($imagePath);
        log_activity('Image Deleted', 'Filename: ' . $fileName);
        if (substr($fileName, 0, 3) !== 'tn_') {
            $thumb = dirname($imagePath) . '/tn_' . $fileName;
            if (file_exists($thumb)) unlink($thumb);
        }
    }
}

// Gather images
$gallery = [];
foreach ($yearDirs as $y) {
    foreach (glob("$y/*", GLOB_ONLYDIR) as $m) {
        foreach (getImages($m) as $img) {
            if (strpos(basename($img), 'tn_') === 0) continue;
            $rel = str_replace($imagesDir, '', $img);
            $parts = explode('/', $rel);
            $thumb = file_exists("$m/tn_{$parts[1]}") ? "/images/{$parts[0]}/tn_{$parts[1]}" : "/images/$rel";
            $gallery[] = ['url' => "/images/$rel", 'thumb' => $thumb, 'path' => $img, 'label' => $parts[1], 'folder' => $parts[0]];
        }
    }
}

// Pagination
$perPage    = 12;
$total      = count($gallery);
$pages      = ceil($total / $perPage);
$page       = max(1, min($pages, (int)($_GET['page'] ?? 1)));
$start      = ($page - 1) * $perPage;
$toDisplay  = array_slice($gallery, $start, $perPage);
?>

<div class="container my-5">
  <h2 class="mb-4"><i class="bi bi-images me-2 text-secondary"></i>Media Library</h2>
  <?php include __DIR__ . '/../upload_widget.php'; ?>

  <div class="row g-3 mt-3">
    <?php foreach ($toDisplay as $img): ?>
      <div class="col-6 col-md-4 col-lg-3">
        <div class="position-relative overflow-hidden rounded shadow-sm bg-white">
          <img src="<?= $img['thumb'] ?>" class="w-100" alt="<?= htmlspecialchars($img['label']) ?>">
          <div class="position-absolute top-0 end-0 m-2">
            <form method="post">
              <input type="hidden" name="delete" value="<?= $img['path'] ?>">
              <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this image?');"><i class="bi bi-trash-fill"></i></button>
            </form>
          </div>
        </div>
        <div class="mt-2 text-truncate"><small><?= htmlspecialchars($img['folder'] . '/' . $img['label']) ?></small></div>
      </div>
    <?php endforeach; ?>
  </div>

  <nav class="mt-4">
    <ul class="pagination justify-content-center">
      <?php if ($page>1): ?>
        <li class="page-item"><a class="page-link" href="?p=gallery&page=<?=$page-1?>">Previous</a></li>
      <?php endif; ?>
      <?php for($i=1;$i<=$pages;$i++): ?>
        <li class="page-item <?= $i===$page?'active':'' ?>"><a class="page-link" href="?p=gallery&page=<?=$i?>"><?= $i ?></a></li>
      <?php endfor; ?>
      <?php if ($page<$pages): ?>
        <li class="page-item"><a class="page-link" href="?p=gallery&page=<?=$page+1?>">Next</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</div>

<script>
document.querySelectorAll('.view-btn').forEach(btn=>btn.addEventListener('click',()=>{
  document.getElementById('modalImage').src=btn.dataset.src;
}));
</script>