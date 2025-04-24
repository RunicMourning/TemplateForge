<?php
$pageTitle = 'Manage Posts';

$postsDir = realpath(__DIR__ . '/../../blog_posts');
$posts = glob($postsDir . '/*.php');

$postsWithDetails = [];

foreach ($posts as $postPath) {
    $filename = basename($postPath);
    $fileContent = file_get_contents($postPath);

    $title = 'Untitled Post';
    if (preg_match('/\$postTitle\s*=\s*(["\'])(.*?)\1/', $fileContent, $titleMatches)) {
        $title = $titleMatches[2];
    }

    if (preg_match('/\$postTimestamp\s*=\s*(["\'])(.*?)\1/', $fileContent, $timestampMatches)) {
        $timestampString = $timestampMatches[2];
        $timestamp = strtotime($timestampString);
        if ($timestamp !== false) {
            $postsWithDetails[] = [
                'filename' => $filename,
                'title' => $title,
                'timestamp' => $timestamp
            ];
        }
    }
}

usort($postsWithDetails, function ($a, $b) {
    return $b['timestamp'] - $a['timestamp'];
});
?>

<div class="container mt-5">
    <!-- Header and New Post Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Manage Posts</h2>
        <a href="index.php?p=create_post" class="btn btn-primary">+ New Post</a>
    </div>

    <!-- Posts Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle shadow-sm bg-white border rounded">
            <thead class="table-light">
                <tr>
                    <th scope="col">Post Title</th>
                    <th scope="col">Published</th>
                    <th scope="col" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($postsWithDetails as $post): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($post['title']); ?></td>
                        <td><?php echo date('F j, Y g:i A', $post['timestamp']); ?></td>
                        <td class="text-end">
                            <a href="index.php?p=edit_post&post=<?php echo urlencode($post['filename']); ?>" class="btn btn-outline-secondary btn-sm me-2">Edit</a>
                            <a href="delete_post.php?post=<?php echo urlencode($post['filename']); ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($postsWithDetails)): ?>
                    <tr>
                        <td colspan="3" class="text-muted text-center">No posts available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
