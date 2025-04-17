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
        
        // Ignore replies
        if (isset($record['reply'])) {
            continue;
        }

        $postData = [
            'text' => $record['text'],
            'time' => $record['createdAt'],
            'images' => [] // Default empty array for images
        ];

        // Check if the post has images
        if (isset($record['embed']['$type']) && $record['embed']['$type'] === 'app.bsky.embed.images') {
            foreach ($record['embed']['images'] as $img) {
                if (isset($img['image']['ref']['$link'])) {
                    $blobRef = $img['image']['ref']['$link'];
                    $postData['images'][] = [
                        'thumb' => "https://bsky.social/xrpc/com.atproto.sync.getBlob?did=$did&cid=$blobRef",
                        'fullsize' => "https://bsky.social/xrpc/com.atproto.sync.getBlob?did=$did&cid=$blobRef",
                        'alt' => $img['alt'] ?? 'Image'
                    ];
                }
            }
        }

        $filtered[] = $postData;

        if (count($filtered) >= $desiredCount) {
            break;
        }
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
    die("<div class='alert alert-warning'>?? Could not resolve DID for user: $targetHandle</div>");
}
$did = $user['did'];

$feed = getUserFeed($did, $authToken, 2);

?>

<div class="card mt-3">
    <div class="card-header">
        <h3 class="mb-0"><i class="bi bi-bluesky me-2"></i> Latest Posts</h3>
    </div>
    <div class="card-body p-0">
        <ul class="list-group list-group-flush">
            <?php foreach ($feed as $post): ?>
                <li class="list-group-item" style="font-size: 90%; overflow: hidden;">
                    <div>
                        <?php if (!empty($post['images'])): ?>
                            <?php foreach ($post['images'] as $img): ?>
                                <a href="<?= htmlspecialchars($img['fullsize']) ?>" target="_blank">
                                    <img src="<?= htmlspecialchars($img['thumb']) ?>" 
                                         alt="<?= htmlspecialchars($img['alt']) ?>" 
                                         class="img-thumbnail" 
                                         style="float: left; max-width: 75px; max-height: 75px; margin-right: 10px; margin-bottom: 10px;">
                                </a>
                                <?php break; // only float the first image ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?= nl2br(linkifyMentions($post['text'])) ?>
                    </div>

                    <small class="text-muted d-block mt-2">
                        <?= date("F j, Y g:i A", strtotime($post['time'])) ?>
                    </small>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="card-footer">
        <a href="https://bsky.app/profile/<?= htmlspecialchars($targetHandle) ?>" target="_blank">@<?= htmlspecialchars($targetHandle) ?></a>
    </div>
</div>
