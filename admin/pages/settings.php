<?php
// pages/settings.php

$pageTitle = 'Site Configuration';

// Include the settings file to get the current configuration values
include '../config/settings.php';
include_once __DIR__.'/../logger.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission and save changes to the settings file

    // General Site Configuration
    $siteTitle = $_POST['siteTitle'];

    // Navigation Configuration
    $enableHorizontalNav = isset($_POST['enableHorizontalNav']) ? true : false;
    $enableVerticalNav   = isset($_POST['enableVerticalNav']) ? true : false;
    $activeNavClass      = $_POST['activeNavClass'];
    $navItemClass        = $_POST['navItemClass'];
    $navLinkClass        = $_POST['navLinkClass'];
    $enableHorizontalNavbar = isset($_POST['enableHorizontalNavbar']) ? true : false;
    $activeNavbarClass   = $_POST['activeNavbarClass'];

    // Podcast Configuration
    $ispodcast = isset($_POST['ispodcast']) ? true : false;
    $podcastAuthor = $_POST['podcastAuthor'];
    $podcastDescription = $_POST['podcastDescription'];
    $podcastExplicit = isset($_POST['podcastExplicit']) ? true : false;

    // Build the new settings content
    $newSettings = "<?php\n\n";
    $newSettings .= "// =========================\n";
    $newSettings .= "// General Site Configuration\n";
    $newSettings .= "// =========================\n";
    $newSettings .= "\$siteTitle = \"$siteTitle\";\n\n";

    $newSettings .= "// ==========================\n";
    $newSettings .= "// Navigation Configuration\n";
    $newSettings .= "// ==========================\n";
    $newSettings .= "\$enableHorizontalNav = " . ($enableHorizontalNav ? 'true' : 'false') . ";\n";
    $newSettings .= "\$enableVerticalNav = " . ($enableVerticalNav ? 'true' : 'false') . ";\n";
    $newSettings .= "\$activeNavClass = \"$activeNavClass\";\n";
    $newSettings .= "\$navItemClass = \"$navItemClass\";\n";
    $newSettings .= "\$navLinkClass = \"$navLinkClass\";\n";
    $newSettings .= "\$enableHorizontalNavbar = " . ($enableHorizontalNavbar ? 'true' : 'false') . ";\n";
    $newSettings .= "\$activeNavbarClass = \"$activeNavbarClass\";\n\n";

    $newSettings .= "// ==========================\n";
    $newSettings .= "// Podcast Configuration\n";
    $newSettings .= "// ==========================\n";
    $newSettings .= "\$ispodcast = " . ($ispodcast ? 'true' : 'false') . ";\n";
    $newSettings .= "\$podcastAuthor = \"$podcastAuthor\";\n";
    $newSettings .= "\$podcastDescription = \"$podcastDescription\";\n";
    $newSettings .= "\$podcastExplicit = " . ($podcastExplicit ? 'true' : 'false') . ";\n";

    file_put_contents('../config/settings.php', $newSettings);

log_activity('Site Configuration', 'Settings Updated');


    // Display a success message
    echo "<div class='alert alert-success'>Settings updated successfully!</div>";
}
?>

<h1>Edit Site Settings</h1>
<form method="POST">
    <div class="row">
        <!-- General Site Configuration Card -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">General Site Configuration</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="siteTitle" class="form-label">Site Title</label>
                        <input type="text" class="form-control" id="siteTitle" name="siteTitle" value="<?= htmlspecialchars($siteTitle) ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Configuration Card -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Navigation Configuration</h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="enableHorizontalNav" name="enableHorizontalNav" <?= $enableHorizontalNav ? 'checked' : '' ?>>
                        <label class="form-check-label" for="enableHorizontalNav">Enable Horizontal Navigation</label>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="enableVerticalNav" name="enableVerticalNav" <?= $enableVerticalNav ? 'checked' : '' ?>>
                        <label class="form-check-label" for="enableVerticalNav">Enable Vertical Navigation</label>
                    </div>
                    <div class="mb-3">
                        <label for="activeNavClass" class="form-label">Active Nav Class</label>
                        <input type="text" class="form-control" id="activeNavClass" name="activeNavClass" value="<?= htmlspecialchars($activeNavClass) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="navItemClass" class="form-label">Nav Item Class</label>
                        <input type="text" class="form-control" id="navItemClass" name="navItemClass" value="<?= htmlspecialchars($navItemClass) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="navLinkClass" class="form-label">Nav Link Class</label>
                        <input type="text" class="form-control" id="navLinkClass" name="navLinkClass" value="<?= htmlspecialchars($navLinkClass) ?>" required>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="enableHorizontalNavbar" name="enableHorizontalNavbar" <?= $enableHorizontalNavbar ? 'checked' : '' ?>>
                        <label class="form-check-label" for="enableHorizontalNavbar">Enable Horizontal Navbar</label>
                    </div>
                    <div class="mb-3">
                        <label for="activeNavbarClass" class="form-label">Active Navbar Class</label>
                        <input type="text" class="form-control" id="activeNavbarClass" name="activeNavbarClass" value="<?= htmlspecialchars($activeNavbarClass) ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Podcast Configuration Card -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Podcast Configuration</h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="ispodcast" name="ispodcast" <?= $ispodcast ? 'checked' : '' ?>>
                        <label class="form-check-label" for="ispodcast">Is Podcast Enabled?</label>
                    </div>
                    <div class="mb-3">
                        <label for="podcastAuthor" class="form-label">Podcast Author</label>
                        <input type="text" class="form-control" id="podcastAuthor" name="podcastAuthor" value="<?= htmlspecialchars($podcastAuthor) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="podcastDescription" class="form-label">Podcast Description</label>
                        <textarea class="form-control" id="podcastDescription" name="podcastDescription" rows="3" required><?= htmlspecialchars($podcastDescription) ?></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="podcastExplicit" name="podcastExplicit" <?= $podcastExplicit ? 'checked' : '' ?>>
                        <label class="form-check-label" for="podcastExplicit">Mark Podcast as Explicit</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Save Changes</button>
</form>
