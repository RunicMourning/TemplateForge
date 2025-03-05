<?php
// rss.php - RSS Feed Generator

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start output buffering to prevent accidental whitespace before XML declaration
ob_start();

// Include configuration file
require_once __DIR__ . '/config/config.php';

// Define the directory where blog post files are stored
$blogPostsDir = __DIR__ . '/blog_posts/';

// Ensure the blog posts directory exists
if (!is_dir($blogPostsDir)) {
    die('Error: Blog post directory does not exist.');
}

// Scan the directory for blog post files
$postFiles = glob($blogPostsDir . '*.php');

// Ensure there are files to process
if (!$postFiles || empty($postFiles)) {
    die('Error: No blog posts found.');
}

// Sort posts by modification date (newest first)
usort($postFiles, function ($a, $b) {
    return filemtime($b) - filemtime($a);
});

// Clean the buffer before outputting XML
ob_end_clean();

// Set the content type to XML
header('Content-Type: application/rss+xml; charset=utf-8');

// Output XML declaration
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss version="2.0" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">
    <channel>
        <!-- Basic RSS Metadata -->
        <title><?php echo htmlspecialchars($siteTitle); ?></title>
        <link><?php echo htmlspecialchars($siteUrl); ?></link>
        <description><?php echo htmlspecialchars($podcastDescription); ?></description>
        <language>en-us</language>

        <?php
        // Conditionally include iTunes podcast metadata
        if ($ispodcast) {
            echo "<itunes:author>" . htmlspecialchars($podcastAuthor) . "</itunes:author>\n";
            echo "<itunes:explicit>" . ($podcastExplicit ? 'yes' : 'no') . "</itunes:explicit>\n";
            echo "<itunes:image href='" . htmlspecialchars($podcastImageUrl) . "' />\n";
            echo "<itunes:category text='" . htmlspecialchars($podcastCategory) . "'>\n";
            echo "    <itunes:subcategory text='" . htmlspecialchars($podcastSubcategory) . "' />\n";
            echo "</itunes:category>\n";
        }
        ?>

        <?php
        // Loop through blog posts and generate RSS items
        foreach ($postFiles as $file) {
            // Reset variables to avoid carry-over from previous files
            $postTitle = $postTimestamp = $postSlug = null;

            // Capture output and include the post file
            ob_start();
            include $file;
            $content = ob_get_clean();

            // Ensure required variables exist
            if (empty($postTitle) || empty($postTimestamp)) {
                error_log("Skipping invalid post file: $file (Missing required variables)");
                continue;
            }

            // Generate post slug from title if not already set
            if (empty($postSlug)) {
                $postSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $postTitle)));
            }

            $postUrl = $siteUrl . '/blog.php?post=' . urlencode($postSlug);
            $postDate = date(DATE_RSS, strtotime($postTimestamp));
            $postExcerpt = '';

            // Extract first paragraph as an excerpt
            if (preg_match('/<p>(.*?)<\/p>/s', $content, $matches)) {
                $postExcerpt = strip_tags($matches[1]);
            }

            // Output RSS item
            echo "<item>\n";
            echo "    <title>" . htmlspecialchars($postTitle) . "</title>\n";
            echo "    <link>" . htmlspecialchars($postUrl) . "</link>\n";
            echo "    <description>" . htmlspecialchars($postExcerpt) . "</description>\n";
            echo "    <pubDate>" . $postDate . "</pubDate>\n";
            echo "    <guid isPermaLink='true'>" . htmlspecialchars($postUrl) . "</guid>\n";

            // Output the post content (optional)
            echo "    <content:encoded>" . htmlspecialchars($content) . "</content:encoded>\n";

            echo "</item>\n";
        }
        ?> 
    </channel>
</rss>
