<?php
/**
 * Meta Injector Addon
 *
 * This addon generates meta tags for description and keywords based on the page content.
 * It assumes that:
 *   - $pageContent contains the full page content (HTML).
 *   - $headerIncludes is an array that will be output in the <head> section.
 *
 * Both variables should be set in your main index.php file.
 */

global $pageContent, $headerIncludes;

// If page content is not set or empty, there's nothing to do.
if (!isset($pageContent) || empty($pageContent)) {
    return;
}

// Ensure $headerIncludes is an array.
if (!isset($headerIncludes) || !is_array($headerIncludes)) {
    $headerIncludes = [];
}

// ----------------------------
// 1. Generate Meta Description
// ----------------------------

// Attempt to extract the first <p> tag as a description.
$description = '';
if (preg_match('/<p[^>]*>(.*?)<\/p>/is', $pageContent, $matches)) {
    $description = strip_tags($matches[1]);
} else {
    // Fallback: use a substring of the full plain-text content.
    $description = strip_tags($pageContent);
}

// Trim whitespace and limit the length to around 160 characters.
$description = trim($description);
if (strlen($description) > 160) {
    $description = substr($description, 0, 157) . '...';
}

// Create the meta description tag.
$metaDescriptionTag = '<meta name="description" content="' . htmlspecialchars($description, ENT_QUOTES, 'UTF-8') . '" />';


// ----------------------------
// 2. Generate Meta Keywords
// ----------------------------

// Define a set of common stopwords to exclude.
$stopwords = ['the','and','is','in','on','of','to','a','for','with','at','by','an','be','as','or','from','that','this','it','are','was','were','will','can','has','had','not','but','if','they','their','which','you','your','we','our','us','these','those'];

// Extract words from the content.
$words = str_word_count(strip_tags($pageContent), 1);

// Filter out stopwords and very short words.
$filteredWords = array_filter($words, function($word) use ($stopwords) {
    return !in_array(strtolower($word), $stopwords) && strlen($word) > 3;
});

// Count the frequency of each word.
$wordFrequencies = array_count_values($filteredWords);

// Sort the words by frequency (most common first).
arsort($wordFrequencies);

// Get the top 10 words as keywords.
$keywords = array_keys(array_slice($wordFrequencies, 0, 10));

// Create a comma-separated list of keywords.
$keywordsString = htmlspecialchars(implode(', ', $keywords), ENT_QUOTES, 'UTF-8');

// Create the meta keywords tag.
$metaKeywordsTag = '<meta name="keywords" content="' . $keywordsString . '" />';


// ----------------------------
// 3. Append Tags to Header Includes
// ----------------------------

$headerIncludes[] = $metaDescriptionTag;
$headerIncludes[] = $metaKeywordsTag;
?>
