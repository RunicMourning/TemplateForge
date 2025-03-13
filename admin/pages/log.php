<?php
// pages/activitylog.php

$pageTitle = 'Activity Log';

// Path to the log file
$logFile = __DIR__ . '/../activity.log';
if (!file_exists($logFile)) {
    echo "<div class='alert alert-danger'>Log file not found.</div>";
    return;
}

// Read the log file line by line
$lines = file($logFile, FILE_IGNORE_NEW_LINES);

// Define results per page
$resultsPerPage = 50;
$totalLines = count($lines);
$totalPages = ceil($totalLines / $resultsPerPage);

// Get the current pagination page number; default to 1.
// (We use a separate "page" parameter while the log page is identified by "p=log")
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

// Calculate the range of lines for the current page
$start = ($page - 1) * $resultsPerPage;
$entries = array_slice($lines, $start, $resultsPerPage);

// Function to format the timestamp into a human-readable format.
function formatTimestamp($timestamp) {
    return date("F j, Y, g:i a", strtotime($timestamp));  // e.g., March 13, 2025, 12:27 pm
}

// Function to map the raw activity string to a standardized name and an icon.
function getActivityData($activityType) {
    $activityTypeLower = strtolower($activityType);
    // Default icon and name (if no match is found)
    $icon = '<i class="bi bi-info-circle-fill"></i>';
    $standardName = $activityType; // default to original
    
    if (strpos($activityTypeLower, '404 not found') !== false) {
        $icon = '<i class="bi bi-exclamation-triangle-fill text-danger"></i>';
        $standardName = '404 Not Found';
    } elseif (strpos($activityTypeLower, 'page created') !== false || strpos($activityTypeLower, 'file created') !== false) {
        $icon = '<i class="bi bi-file-earmark-plus-fill text-success"></i>';
        $standardName = 'Page Created';
    } elseif (strpos($activityTypeLower, 'page edited') !== false) {
        $icon = '<i class="bi bi-pencil-square text-primary"></i>';
        $standardName = 'Page Edited';
    } elseif (strpos($activityTypeLower, 'post created') !== false) {
        $icon = '<i class="bi bi-file-plus-fill text-success"></i>';
        $standardName = 'Post Created';
    } elseif (strpos($activityTypeLower, 'post edited') !== false) {
        $icon = '<i class="bi bi-pencil-fill text-warning"></i>';
        $standardName = 'Post Edited';
    }
    return ['icon' => $icon, 'name' => $standardName];
}
?>

<div class="container mt-5">
  <h2><?php echo htmlspecialchars($pageTitle); ?></h2>
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>Activity</th>
        <th>Date</th>
        <th>IP Address</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      foreach ($entries as $line):
          // Extract the timestamp and the rest of the log entry.
          if (preg_match('/^\[(.*?)\]\s*-\s*(.*)/', $line, $parts)) {
              $timestamp = $parts[1];
              $rest = $parts[2];
              // Split the rest by " - IP:" to isolate activity details and the IP.
              $split = explode(' - IP:', $rest);
              if (count($split) === 2) {
                  $activityAndFile = trim($split[0]);
                  $ip = trim($split[1]);
              } else {
                  $activityAndFile = trim($rest);
                  $ip = '';
              }
              // Further split the activity part into the main activity and file detail.
              $activityParts = explode(' - ', $activityAndFile, 2);
              $activityType = trim($activityParts[0]);
              $fileDetail = isset($activityParts[1]) ? trim($activityParts[1]) : '';
              
              // Get the standardized activity name and corresponding icon.
              $activityData = getActivityData($activityType);
              $activityIcon = $activityData['icon'];
              $activityName = $activityData['name'];
      ?>
        <tr>
          <td>
            <?php echo $activityIcon . ' ' . htmlspecialchars($activityName); ?>
            <?php if ($fileDetail): ?>
              <br><small class="text-muted"><?php echo htmlspecialchars($fileDetail); ?></small>
            <?php endif; ?>
          </td>
          <td><?php echo htmlspecialchars(formatTimestamp($timestamp)); ?></td>
          <td><?php echo htmlspecialchars($ip); ?></td>
        </tr>
      <?php 
          }
      endforeach; 
      ?>
    </tbody>
  </table>

  <!-- Pagination -->
  <nav>
    <ul class="pagination justify-content-center">
      <?php if ($page > 1): ?>
        <li class="page-item">
          <a class="page-link" href="index.php?p=log&page=<?php echo $page - 1; ?>">Previous</a>
        </li>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?php echo ($i === $page) ? 'active' : ''; ?>">
          <a class="page-link" href="index.php?p=log&page=<?php echo $i; ?>"><?php echo $i; ?></a>
        </li>
      <?php endfor; ?>

      <?php if ($page < $totalPages): ?>
        <li class="page-item">
          <a class="page-link" href="index.php?p=log&page=<?php echo $page + 1; ?>">Next</a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>
</div>
