<h1 class="mb-4">Search Results for "<?php echo htmlspecialchars($query); ?>"</h1>

<?php if (empty($results)): ?>
    <div class="alert alert-warning" role="alert">
        No results found.
    </div>
<?php else: ?>
    <div class="list-group">
        <?php foreach ($results as $result): ?>
            <div class="list-group-item">
                <h5 class="mb-1">
                    <a href="<?php echo htmlspecialchars($result['link']); ?>" class="text-decoration-none">
                        <?php echo htmlspecialchars($result['title']); ?>
                    </a>
                </h5>
                <p class="mb-1"><?php echo htmlspecialchars($result['snippet']); ?></p>
                <small class="text-muted">Type: <?php echo htmlspecialchars($result['type']); ?></small>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
