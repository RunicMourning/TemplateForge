<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap 5.3 Template</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>
  <body>

<?php

// === CONFIG ===
$yourHandle = 'thevintagegamers.com'; // ?? YOUR bsky handle
$appPassword = 'MW2bLyy$hNY%;qe';       // ?? YOUR app password
$targetHandle = 'thevintagegamers.com';   // ?? Target handle

// === FUNCTIONS ===

function createSession($handle, $appPassword) {
    $url = "https://bsky.social/xrpc/com.atproto.server.createSession";

    $payload = json_encode([
        "identifier" => $handle,
        "password" => $appPassword
    ]);

    $headers = [
        "Content-Type: application/json",
        "Accept: application/json"
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => $payload
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

function getUserDID($handle) {
    $url = "https://bsky.social/xrpc/com.atproto.identity.resolveHandle?handle=" . urlencode($handle);
    $response = file_get_contents($url);
    return json_decode($response, true);
}

function getUserFeed($did, $authToken, $desiredCount = 5) {
    $url = "https://bsky.social/xrpc/app.bsky.feed.getAuthorFeed?actor={$did}&limit=25";

    $headers = [
        "Authorization: Bearer $authToken"
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $allPosts = json_decode($response, true);
    $filtered = [];

    foreach ($allPosts['feed'] as $item) {
        $record = $item['post']['record'];
        if (!isset($record['reply'])) {
            $filtered[] = [
                'text' => $record['text'],
                'time' => $record['createdAt']
            ];
        }
        if (count($filtered) >= $desiredCount) break;
    }

    return $filtered;
}

function linkifyMentions($text) {
    return preg_replace_callback(
        '/@([a-zA-Z0-9._-]+\.[a-z]{2,}|[a-zA-Z0-9_]+)/i',
        function ($matches) {
            $username = $matches[1];
            return '<a href="https://bsky.app/profile/' . htmlspecialchars($username) . '" target="_blank">@' . htmlspecialchars($username) . '</a>';
        },
        htmlspecialchars($text)
    );
}

// === MAIN LOGIC ===

$session = createSession($yourHandle, $appPassword);
if (!isset($session['accessJwt'])) {
    die("<div class='alert alert-danger'>? Failed to authenticate. Check your credentials.</div>");
}

$authToken = $session['accessJwt'];
$user = getUserDID($targetHandle);
if (!isset($user['did'])) {
    die("<div class='alert alert-warning'>? Could not resolve DID for user: $targetHandle</div>");
}
$did = $user['did'];

$feed = getUserFeed($did, $authToken, 5);

?>



<div class="container my-4">

<div class="card shadow-sm">
  <div class="card-header">
    Latest Posts from <strong><a href="https://bsky.app/profile/<?= htmlspecialchars($targetHandle) ?>">@<?= htmlspecialchars($targetHandle) ?></a></strong>
  </div>
  <div class="card-body p-0">
    <ul class="list-group list-group-flush">
      <?php foreach ($feed as $post): ?>
        <li class="list-group-item d-flex flex-column">
          <div class="mb-2"><?= nl2br(linkifyMentions($post['text'])) ?></div>
          <small class="text-muted align-self-end">
            <?= date("F j, Y g:i A", strtotime($post['time'])) ?>
          </small>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>

</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>