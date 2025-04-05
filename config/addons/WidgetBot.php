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

// Define the welcome message
$welcomeMessage = 'Welcome to our community! How can I assist you today?';

$footerIncludes[] = <<<HTML
<script src="https://cdn.jsdelivr.net/npm/@widgetbot/crate@3"></script>
<script>
  const button = new Crate({
    server: '201789112133484553', // The Vintage Gamers
    channel: '1201038181584343110', // #community-chat
    welcomeMessage: '$welcomeMessage',
    username: '$username'
  });

  // Define an array of messages to simulate conversation or grab attention.
  const messages = [
    "Hello there, need assistance?",
    "Don't forget to check out our latest updates!",
    "Having fun? Join the chat now!",
    "Questions? I'm here to help!",
    "Let's get the conversation started!"
  ];

  // Function to get a random delay between two values (in milliseconds).
  function getRandomDelay(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
  }

  // Function to send a notification and schedule the next one.
  function randomNotify() {
    // Randomly select a message.
    const message = messages[Math.floor(Math.random() * messages.length)];
    // Trigger the notification.
    crate.notify(message);
    // Schedule the next notification with a random delay (e.g., between 30 and 60 seconds).
    const delay = getRandomDelay(30000, 60000);
    setTimeout(randomNotify, delay);
  }

  // Start the notifications after an initial random delay.
  setTimeout(randomNotify, getRandomDelay(30000, 60000));
</script>
HTML;
?>