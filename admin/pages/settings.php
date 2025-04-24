<?php
// pages/settings.php

$pageTitle = 'Site Configuration';
include '../config/settings.php';
include_once __DIR__ . '/../logger.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ... (saving logic unchanged) ...
}

// Build settings sections for display with associated icons
$sections = [
    'General'    => [
        ['label' => 'Site Title', 'type' => 'text', 'name' => 'siteTitle', 'value' => $siteTitle]
    ],
    'Navigation' => [
        ['label' => 'Enable Horizontal Nav', 'type' => 'checkbox', 'name' => 'enableHorizontalNav', 'checked' => $enableHorizontalNav],
        ['label' => 'Enable Vertical Nav', 'type' => 'checkbox', 'name' => 'enableVerticalNav', 'checked' => $enableVerticalNav],
        ['label' => 'Active Nav Class', 'type' => 'text', 'name' => 'activeNavClass', 'value' => $activeNavClass],
        ['label' => 'Nav Item Class', 'type' => 'text', 'name' => 'navItemClass', 'value' => $navItemClass],
        ['label' => 'Nav Link Class', 'type' => 'text', 'name' => 'navLinkClass', 'value' => $navLinkClass]
    ],
    'Podcast'    => [
        ['label' => 'Podcast Enabled', 'type' => 'checkbox', 'name' => 'ispodcast', 'checked' => $ispodcast],
        ['label' => 'Podcast Author', 'type' => 'text', 'name' => 'podcastAuthor', 'value' => $podcastAuthor],
        ['label' => 'Podcast Description', 'type' => 'textarea', 'name' => 'podcastDescription', 'value' => $podcastDescription],
        ['label' => 'Explicit', 'type' => 'checkbox', 'name' => 'podcastExplicit', 'checked' => $podcastExplicit]
    ],
    'AdSense'    => [
        ['label' => 'Enable AdSense', 'type' => 'checkbox', 'name' => 'googleadsenseenabled', 'checked' => $googleadsenseenabled],
        ['label' => 'Publisher ID', 'type' => 'text', 'name' => 'googleAdsenseID', 'value' => $googleAdsenseID]
    ]
];

// Icons for each section
$sectionIcons = [
    'General'    => 'bi bi-gear-fill',
    'Navigation' => 'bi bi-list-ul',
    'Podcast'    => 'bi bi-mic-fill',
    'AdSense'    => 'bi bi-currency-dollar'
];
?>

<div class="container mt-5">
  <h2 class="mb-4"><i class="bi bi-sliders me-2"></i>Site Configuration</h2>
  <form method="POST">
    <div class="accordion" id="settingsAccordion">
      <?php $i = 0; foreach ($sections as $section => $fields): $i++; ?>
        <div class="accordion-item">
          <h2 class="accordion-header" id="heading<?= $i ?>">
            <button class="accordion-button <?= $i > 1 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $i ?>" aria-expanded="<?= $i === 1 ? 'true' : 'false' ?>" aria-controls="collapse<?= $i ?>">
              <i class="<?= $sectionIcons[$section] ?> me-2 text-primary"></i><?= $section ?> Settings
            </button>
          </h2>
          <div id="collapse<?= $i ?>" class="accordion-collapse collapse <?= $i === 1 ? 'show' : '' ?>" aria-labelledby="heading<?= $i ?>" data-bs-parent="#settingsAccordion">
            <div class="accordion-body">
              <?php foreach ($fields as $field): ?>
                <div class="mb-3 row align-items-center">
                  <label class="col-sm-4 col-form-label"><?= $field['label'] ?></label>
                  <div class="col-sm-8">
                    <?php if ($field['type'] === 'text'): ?>
                      <input type="text" class="form-control" name="<?= $field['name'] ?>" value="<?= htmlspecialchars($field['value']) ?>">
                    <?php elseif ($field['type'] === 'textarea'): ?>
                      <textarea class="form-control" name="<?= $field['name'] ?>" rows="3"><?= htmlspecialchars($field['value']) ?></textarea>
                    <?php elseif ($field['type'] === 'checkbox'): ?>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="<?= $field['name'] ?>" <?= !empty($field['checked']) ? 'checked' : '' ?>>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <button type="submit" class="btn btn-primary mt-4"><i class="bi bi-save me-2"></i>Save Changes</button>
  </form>
</div>