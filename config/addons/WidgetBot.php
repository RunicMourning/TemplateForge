<?php
// /config/addons/WidgetBot.php

/**
 * WidgetBot Addon
 *
 * This addon integrates the WidgetBot chat widget into the footer of your site.
 * - The addon dynamically injects a JavaScript snippet into the $footerIncludes array, which places the WidgetBot script just before the closing </body> tag of your HTML pages.
 * - It also generates random content, including the title of the latest blog post, which will be passed into the WidgetBot's notification feature.
 * - This functionality allows you to display an interactive chat widget while also providing engaging content such as recent post titles or random jokes.
 * 
 * Usage:
 * - The $footerIncludes variable should be defined and used in your template to inject this content into the footer.
 * - The random content displayed by the WidgetBot notification will vary, including a dynamic title from your most recent blog post.
 */

// Ensure the global arrays are available.
global $headerIncludes, $footerIncludes;

if (!isset($footerIncludes) || !is_array($footerIncludes)) {
    $footerIncludes = [];

}

// Function to generate a 4-digit number from the visitor's IP
function generateUsernameFromIP() {
    // Get the visitor's IP address
    $ip = $_SERVER['REMOTE_ADDR'];

    // Split the IP into its 4 parts
    $ipParts = explode('.', $ip);

    // Create a 4-digit number by concatenating the first number of each part
    if (count($ipParts) === 4) {
        return $ipParts[0] . $ipParts[1] . $ipParts[2] . $ipParts[3];
    }

    // Fallback if IP is invalid or not in correct format
    return '0000';
}

// Generate the username based on the visitor's IP
$username = 'Guest'.generateUsernameFromIP();

// Define an array with random content (including the latest post title)
$randomContent = [
    "Why don't skeletons fight each other? They don't have the guts.",
    "What do you call fake spaghetti? An impasta!",
    "I told my wife she was drawing her eyebrows too high. She looked surprised.",
    "Why don’t some couples go to the gym? Because some relationships don’t work out.",
    "I used to play piano by ear, but now I use my hands."
];

// Randomize the content and store the first item in a string
shuffle($randomContent);
$randomItem = $randomContent[0];

// Define the welcome message
$welcomeMessage = 'Welcome to our community! How can I assist you today?';

// Escape the random item for JavaScript compatibility (to avoid breaking the script)
$escapedRandomItem = addslashes($randomItem);

$footerIncludes[] = <<<HTML
<script src="https://cdn.jsdelivr.net/npm/@widgetbot/crate@3">
  const button = new Crate({
    server: '201789112133484553', // The Vintage Gamers
    channel: '1201038181584343110', // #community-chat
    welcomeMessage: '$welcomeMessage',
    username: '$username'
  })
  
  // Pass the random item into the notification, escaping any quotes
  crate.notify('$escapedRandomItem');
</script>
HTML;
?>