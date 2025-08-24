<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$pageTitle = "Oops! This Page Doesn't Exist";
$headerIncludes[] = "";
$footerIncludes[] = "";

include __DIR__.'/../admin/logger.php';

$requested_url = $_SERVER['REQUEST_URI'] ?? 'Unknown URL';
$referrer = $_SERVER['HTTP_REFERER'] ?? 'None';
$client_ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
$user_agent_full = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
$request_method = $_SERVER['REQUEST_METHOD'] ?? 'Unknown';
$server_protocol = $_SERVER['SERVER_PROTOCOL'] ?? 'Unknown';
$php_version = PHP_VERSION;
$server_software = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';
$logged_in_user = isset($_SESSION['loggedin'], $_SESSION['username']) && $_SESSION['loggedin'] ? $_SESSION['username'] : 'Anonymous';

// Simplify User Agent
$user_agent_display = 'Unknown Browser/OS';
$agents = ['Firefox','Edge','Chrome','Safari','Opera','Bot/Crawler','Internet Explorer'];
foreach ($agents as $agent) {
    if (strpos($user_agent_full, $agent) !== false) {
        $user_agent_display = $agent;
        break;
    }
}

// Handler
$handler = stripos($server_software,'apache')!==false?'mod_rewrite':(stripos($server_software,'nginx')!==false?'PHP-FPM':(stripos($server_software,'iis')!==false?'ISAPI':'Unknown'));

// Log activity
log_activity(
    '404 Not Found', 
    'URL: '.$requested_url.' | Referrer: '.$referrer.' | IP: '.$client_ip.' | UA: '.$user_agent_full.' | User: '.$logged_in_user
);

// Requested name
$page_id = $_GET['p'] ?? null;
$requested_name = mb_strtolower($page_id ?: pathinfo($requested_url, PATHINFO_FILENAME));

// --- Gather suggestions ---
if (!function_exists('getAllFilesRecursive')) {
    function getAllFilesRecursive($dir){
        $files=[];
        foreach (scandir($dir) as $item){
            if ($item=='.'||$item=='..') continue;
            $path=$dir.'/'.$item;
            if (is_dir($path)) $files=array_merge($files,getAllFilesRecursive($path));
            elseif(is_file($path) && pathinfo($path,PATHINFO_EXTENSION)==='php') $files[]=$path;
        }
        return $files;
    }
}

$all_site_files = array_merge(
    getAllFilesRecursive(__DIR__),
    getAllFilesRecursive(__DIR__.'/../blog_posts')
);

$suggestions=[];
foreach($all_site_files as $file){
    $slug = mb_strtolower(pathinfo($file, PATHINFO_FILENAME));
    $url_slug = str_contains($file,'/blog_posts/') ? 'blog-'.$slug : $slug;
    similar_text($requested_name,$url_slug,$percent);
    if($percent>30 && $url_slug!=$requested_name) $suggestions[$url_slug]=$percent;
}
arsort($suggestions);
$suggestions=array_slice($suggestions,0,5,true);

$request_id = substr(md5($requested_url.microtime()),0,10);
?>

<div class="container py-5">
    <!-- Hero Section -->
    <div class="text-center mb-5">
        <h1 class="display-1 fw-bold text-danger">404</h1>
        <h2 class="h3 mb-3">Page Not Found</h2>
        <p class="lead text-muted">
            <?php if(str_starts_with($requested_name,'blog-')): ?>
                The blog post <strong><?php echo htmlspecialchars(substr($requested_name,5)); ?></strong> could not be found.
            <?php else: ?>
                We couldn't find the page you were looking for.
            <?php endif; ?>
        </p>
        <div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">
            <a href="index.html" class="btn btn-primary btn-lg rounded-pill" aria-label="Return Home">Return Home</a>
            <a href="sitemap.html" class="btn btn-outline-primary btn-lg rounded-pill" aria-label="View Sitemap">View Sitemap</a>
        </div>

        <!-- Search Bar -->
        <form class="input-group mt-4 mx-auto" action="search.html" style="max-width:500px;" aria-label="Search for a page">
            <input type="text" class="form-control rounded-start" name="q" placeholder="Search for a page..." aria-label="Search Query">
            <button class="btn btn-danger rounded-end" type="submit">Go</button>
        </form>
    </div>

    <!-- Suggestions Section -->
    <?php if(!empty($suggestions)): ?>
        <div class="mb-5">
            <h4 class="mb-3">Did you mean:</h4>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                <?php foreach($suggestions as $slug=>$percent): ?>
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <a href="<?php echo htmlspecialchars($slug); ?>.html" class="text-decoration-none fw-semibold">
                                    <?php echo str_starts_with($slug,'blog-') 
                                        ? ucfirst(str_replace('-',' ',substr($slug,5))) 
                                        : ucfirst(str_replace('-',' ',$slug)); ?>
                                </a>
                                <span class="badge bg-secondary"><?php echo round($percent); ?>% similar</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Technical Details Accordion -->
    <div class="accordion" id="errorDetailsAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingErrorDetails">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseErrorDetails" aria-expanded="false">
                    <i class="bi bi-info-circle me-2"></i> Detailed Error Information
                </button>
            </h2>
            <div id="collapseErrorDetails" class="accordion-collapse collapse" aria-labelledby="headingErrorDetails">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless mb-0 small">
                                <tr><th class="text-muted">Error Code</th><td>HTTP 404</td></tr>
                                <tr><th class="text-muted">Module</th><td><?php echo htmlspecialchars($server_software); ?></td></tr>
                                <tr><th class="text-muted">Handler</th><td><?php echo htmlspecialchars($handler); ?></td></tr>
                                <tr><th class="text-muted">Server Protocol</th><td><?php echo htmlspecialchars($server_protocol); ?></td></tr>
                                <tr><th class="text-muted">PHP Version</th><td><?php echo htmlspecialchars($php_version); ?></td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless mb-0 small">
                                <tr><th class="text-muted">Requested URL</th><td><?php echo htmlspecialchars($requested_url); ?></td></tr>
                                <tr><th class="text-muted">Referrer</th><td><?php echo htmlspecialchars($referrer); ?></td></tr>
                                <tr><th class="text-muted">Request Method</th><td><?php echo htmlspecialchars($request_method); ?></td></tr>
                                <tr><th class="text-muted">User Agent</th><td><?php echo htmlspecialchars($user_agent_display); ?></td></tr>
                                <tr><th class="text-muted">Timestamp</th><td><?php echo date('Y-m-d H:i:s T'); ?></td></tr>
                                <tr><th class="text-muted">Logon User</th><td><?php echo htmlspecialchars($logged_in_user); ?></td></tr>
                                <tr><th class="text-muted">Request ID</th><td><?php echo htmlspecialchars($request_id); ?></td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
