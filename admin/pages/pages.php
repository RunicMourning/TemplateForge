<?php
// admin/pages.php
$pageTitle = 'Manage Pages';

$pagesDir = realpath(__DIR__ . '/../../pages');
$pages = glob($pagesDir . '/*.php');

// Array to store pages with their titles
$pagesWithTitles = [];

// Extract filename and title for each page
foreach ($pages as $pagePath) {
    $filename = basename($pagePath);
    
    // Read the content of the page without including it
    $fileContent = file_get_contents($pagePath);

    // Extract page title
    $title = 'Untitled Page'; // Default title if not found
    if (preg_match('/\$pageTitle\s*=\s*(["\'])(.*?)\1/', $fileContent, $titleMatches)) {
        $title = $titleMatches[2];
    }

    // Store the details
    $pagesWithTitles[] = [
        'filename' => $filename,
        'title' => $title
    ];
}
?>

<div class="container mt-4">
    <!-- Create Page Button -->
    <div class="row mb-4">
        <div class="col">
            <a href="index.php?p=create_page" class="btn btn-primary btn-lg w-100">
                Create New Page
            </a>
        </div>
    </div>

    <div class="row">
        <?php foreach ($pagesWithTitles as $page): ?>
            <div class="col-md-4 col-sm-6">
                <div class="card mb-2">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($page['title']); ?></h5>
                    </div>
                    <div class="card-footer btn-group" role="group" aria-label="Page Actions">
                        <a href="index.php?p=edit_page&page=<?php echo urlencode($page['filename']); ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_page.php?page=<?php echo urlencode($page['filename']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this page?');">Delete</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
