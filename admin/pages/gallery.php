<?php
$imagesDir = __DIR__ . '/../../images/';
$yearDirs = glob($imagesDir . '*', GLOB_ONLYDIR);

function getImages($dir) {
    return glob("$dir/*.{jpg,png,gif,jpeg,webp}", GLOB_BRACE);
}

// Deletion: Remove main image and its thumbnail (if applicable)
if (isset($_POST['delete'])) {
    $imagePath = $_POST['delete'];
    if (file_exists($imagePath)) {
        unlink($imagePath);
        // If the deleted file is not a thumbnail, remove its thumbnail version as well.
        $fileName = basename($imagePath);
        if (substr($fileName, 0, 3) !== 'tn_') {
            $thumbPath = dirname($imagePath) . '/tn_' . $fileName;
            if (file_exists($thumbPath)) {
                unlink($thumbPath);
            }
        }
    }
}

// Build a flat array of main images for the gallery.
$galleryImages = [];
foreach ($yearDirs as $yearDir) {
    $monthDirs = glob("$yearDir/*", GLOB_ONLYDIR);
    foreach ($monthDirs as $monthDir) {
        $images = getImages($monthDir);
        foreach ($images as $image) {
            $fileName = basename($image);
            // Skip thumbnails.
            if (substr($fileName, 0, 3) === 'tn_') continue;
            
            // Compute the relative path (e.g., '2025/03')
            $imageRelative = str_replace($imagesDir, '', $image);
            $relativePath = dirname($imageRelative);
            $imageUrl = "/images/" . $relativePath . "/" . $fileName;
            
            // Determine if a thumbnail exists.
            $thumbFileName = 'tn_' . $fileName;
            $thumbPath = $monthDir . '/' . $thumbFileName;
            if (file_exists($thumbPath)) {
                $thumbUrl = "/images/" . $relativePath . '/' . $thumbFileName;
            } else {
                $thumbUrl = $imageUrl;
            }
            
            // Save details for later display.
            $galleryImages[] = [
                'filePath'     => $image, 
                'fileName'     => $fileName,
                'relativePath' => $relativePath,
                'imageUrl'     => $imageUrl,
                'thumbUrl'     => $thumbUrl
            ];
        }
    }
}

// Pagination Variables
$imagesPerPage = 10;
$totalImages   = count($galleryImages);
$totalPages    = ceil($totalImages / $imagesPerPage);
$currentPage   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) { $currentPage = 1; }
if ($currentPage > $totalPages) { $currentPage = $totalPages; }
$offset       = ($currentPage - 1) * $imagesPerPage;
$imagesToShow = array_slice($galleryImages, $offset, $imagesPerPage);
?>

<h2>Media Library</h2>
<?php include 'upload_widget.php'; ?>
<div class="row">
    <?php foreach ($imagesToShow as $img): ?>
        <div class="col-md-2 mb-4">
            <div class="card">
                <img src="<?php echo $img['thumbUrl']; ?>" alt="Thumbnail" class="card-img-top">
                <div class="card-body text-center">
                    <p><strong><?php echo $img['fileName']; ?></strong></p>
                    <p class="text-muted">Path: <?php echo $img['relativePath']; ?></p>
                    <button class="btn btn-primary view-btn" data-bs-toggle="modal" data-bs-target="#imageModal" data-src="<?php echo $img['imageUrl']; ?>">View</button>
                    <form method="post" class="d-inline">
                        <input type="hidden" name="delete" value="<?php echo $img['filePath']; ?>">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Pagination Links -->
<nav>
    <ul class="pagination justify-content-center">
        <?php if ($currentPage > 1): ?>
            <li class="page-item">
                <a class="page-link" href="index.php?p=gallery&page=<?php echo $currentPage - 1; ?>">Previous</a>
            </li>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php if ($i == $currentPage) echo 'active'; ?>">
                <a class="page-link" href="index.php?p=gallery&page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>
        <?php if ($currentPage < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" href="index.php?p=gallery&page=<?php echo $currentPage + 1; ?>">Next</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalImage" class="img-fluid" alt="Preview">
            </div>
        </div>
    </div>
</div>

<script>
// Update the modal's image source using vanilla JavaScript.
document.addEventListener('DOMContentLoaded', function() {
    var viewButtons = document.querySelectorAll('.view-btn');
    viewButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var imgSrc = this.getAttribute('data-src');
            document.getElementById('modalImage').setAttribute('src', imgSrc);
        });
    });
});
</script>
