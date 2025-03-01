<?php
// pages/about.php

// Define the title for this page.
$pageTitle = "About Us";
?>
  <h2>Overview</h2>
    <p>This project is a modular, flat-file-based website built with PHP. It supports dynamic content loading, a blog system, and a search feature without requiring a database.</p>

    <h2>Key Features</h2>
    <h3>Flat-File Content Management</h3>
    <ul>
        <li><strong>Individual Content Pages:</strong> Each page is stored as a separate PHP file in the <code>/pages/</code> directory.</li>
        <li><strong>Blog System:</strong> Blog posts are stored in the <code>/blog_posts/</code> directory as individual PHP files.</li>
        <li><strong>Output Buffering:</strong> Content is captured using output buffering for seamless template integration.</li>
    </ul>

    <h3>Centralized Template System</h3>
    <ul>
        <li><strong>Unified Layout:</strong> A single template file (<code>main_template.php</code>) defines the site layout.</li>
        <li><strong>Dynamic Content Loading:</strong> Content from individual pages and blog posts is injected into the template.</li>
        <li><strong>Sidebar Modules:</strong> The site supports dynamic sidebars located in <code>/sidebars/</code>.</li>
    </ul>

    <h3>Modular Add-On Support</h3>
    <ul>
        <li><strong>Auto-Included Modules:</strong> The <code>config.php</code> file scans and includes all PHP files in the <code>/config/addons/</code> directory.</li>
        <li><strong>Navigation System:</strong> Navigation is generated dynamically and supports active class highlighting.</li>
    </ul>

    <h3>Search Functionality</h3>
    <ul>
        <li><strong>Site-Wide Search:</strong> Users can search both blog posts and static pages.</li>
        <li><strong>Results Display:</strong> Search results are shown in a dedicated template (<code>search_template.php</code>).</li>
    </ul>

    <h2>Directory Structure</h2>
    <pre style="border: 1px solid #ccc; padding: 16px; margin: auto; width: 50%; background: #eee; border-radius: 10px; overflow-x: auto;">
/index.php
/config/
    config.php
    /addons/
        Addon files here. 
/templates/
    Page templates, search template, etc.
/pages/
    home.php
    about.php
    404.php
/blog_posts/
    Blog posts here
/css/
    style.css
/sidebars/
    Sidebars here
/search.php
    </pre>

    <h2>.htaccess Explanation</h2>
    <p>The <code>.htaccess</code> file enables URL rewriting for user-friendly links.</p>

    <h2>Installation and Setup</h2>
    <ol>
        <li>Ensure your web server supports PHP 7 or later.</li>
        <li>Upload the project files to your web server.</li>
        <li>Modify <code>config/config.php</code> to set your site's title, navigation preferences, and other configurations.</li>
        <li>Add or update content files in the <code>/pages/</code> directory.</li>
    </ol>

    <h2>404 Handling</h2>
    <p>If a requested page does not exist, the script automatically loads <code>404.php</code> from the <code>/pages/</code> directory and sends the appropriate HTTP 404 header.</p>

    <h2>Future Enhancements</h2>
    <ul>
        <li>Improved admin panel for content management.</li>
        <li>User authentication for restricted content.</li>
        <li>Performance optimization with caching mechanisms.</li>
    </ul>

    <h2>Conclusion</h2>
    <p>This site architecture provides a robust, flexible solution for dynamic PHP websites without requiring a database. It is designed for easy expansion and customization.</p>