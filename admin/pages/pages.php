<?php
$pageTitle = 'Manage Pages';

$pagesDir = realpath(__DIR__ . '/../../pages');
$pages = glob($pagesDir . '/*.php');

$pagesWithTitles = [];

foreach ($pages as $pagePath) {
    $filename = basename($pagePath);
    $fileContent = file_get_contents($pagePath);

    $title = 'Untitled Page';
    if (preg_match('/\$pageTitle\s*=\s*(["\'])(.*?)\1/', $fileContent, $titleMatches)) {
        $title = $titleMatches[2];
    }

    $pagesWithTitles[] = [
        'filename' => $filename,
        'title' => $title
    ];
}
?>

<div class="container mt-5">
    <!-- Header and New Page Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Manage Pages</h2>
        <a href="index.php?p=create_page" class="btn btn-primary">
            + New Page
        </a>
    </div>

    <!-- Pages Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle shadow-sm bg-white border rounded">
            <thead class="table-light">
                <tr>
                    <th scope="col">Page Title</th>
                    <th scope="col" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pagesWithTitles as $page): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($page['title']); ?></td>
                        <td class="text-end">
                            <a href="index.php?p=edit_page&page=<?php echo urlencode($page['filename']); ?>" class="btn btn-outline-secondary btn-sm me-2">Edit</a>
                            <a href="delete_page.php?page=<?php echo urlencode($page['filename']); ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this page?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($pagesWithTitles)): ?>
                    <tr>
                        <td colspan="2" class="text-muted text-center">No pages found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
