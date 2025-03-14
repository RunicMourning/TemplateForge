<?php
// upload_widget.php
$uploadDir = __DIR__ . '/../images/' . date("Y/m/"); // Change as needed

// Ensure directory exists
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];
    $fileName = basename($file['name']);
    $uploadPath = $uploadDir . $fileName;
    $thumbPath = $uploadDir . 'tn_' . $fileName;

    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        createThumbnail($uploadPath, $thumbPath);
        // Modify these to generate relative URLs
        $fullImageUrl = 'images/' . date("Y/m/") . $fileName; // Relative URL for full image
        $thumbImageUrl = 'images/' . date("Y/m/") . 'tn_' . $fileName; // Relative URL for thumbnail
        echo "<div class='alert alert-success'>Upload successful!</div>";
        echo "<p><strong>Full Image:</strong> <code>&lt;img src=\"$fullImageUrl\"&gt;</code></p>";
        echo "<p><strong>Thumbnail:</strong> <code>&lt;img src=\"$thumbImageUrl\"&gt;</code></p>";
    } else {
        echo "<div class='alert alert-danger'>Upload failed.</div>";
    }
}

function createThumbnail($sourcePath, $thumbPath, $maxSize = 150) {
    $imageInfo = getimagesize($sourcePath);
    if ($imageInfo === false) return false;

    list($origWidth, $origHeight) = $imageInfo;
    if ($origWidth > $origHeight) {
        $newWidth = $maxSize;
        $newHeight = intval(($origHeight / $origWidth) * $maxSize); // Explicit cast to int
    } else {
        $newHeight = $maxSize;
        $newWidth = intval(($origWidth / $origHeight) * $maxSize); // Explicit cast to int
    }

    $thumb = imagecreatetruecolor($newWidth, $newHeight);

    switch ($imageInfo['mime']) {
        case 'image/jpeg': $source = imagecreatefromjpeg($sourcePath); break;
        case 'image/png': $source = imagecreatefrompng($sourcePath); break;
        case 'image/gif': $source = imagecreatefromgif($sourcePath); break;
        case 'image/webp': $source = imagecreatefromwebp($sourcePath); break;
        default: return false;
    }

    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
    imagejpeg($thumb, $thumbPath, 80);

    imagedestroy($thumb);
    imagedestroy($source);

    return true;
}
?>

<form method="post" enctype="multipart/form-data" class="mb-5">
    <div class="mb-3">
        <label class="form-label">Upload Image</label>
        <input type="file" name="image" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Upload</button>
</form>
