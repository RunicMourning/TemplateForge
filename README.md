# TemplateForge README

## Overview
This project is a modular, flat-file-based website built with PHP. It supports dynamic content loading, a blog system, and a search feature without requiring a database.

## Key Features

### Flat-File Content Management
- **Individual Content Pages:** Each page is stored as a separate PHP file in the `/pages/` directory.
- **Blog System:** Blog posts are stored in the `/blog_posts/` directory as individual PHP files.
- **Output Buffering:** Content is captured using output buffering for seamless template integration.

### Centralized Template System
- **Unified Layout:** A single template file (`main_template.php`) defines the site layout.
- **Dynamic Content Loading:** Content from individual pages and blog posts is injected into the template.
- **Sidebar Modules:** The site supports dynamic sidebars located in `/sidebars/`.

### Modular Add-On Support
- **Auto-Included Modules:** The `config.php` file scans and includes all PHP files in the `/config/addons/` directory.
- **Navigation System:** Navigation is generated dynamically and supports active class highlighting.

### Search Functionality
- **Site-Wide Search:** Users can search both blog posts and static pages.
- **Results Display:** Search results are shown in a dedicated template (`search_template.php`).

## Directory Structure
```
/index.php
/config/
 └──config.php
 └──/addons/
	 └──Addon files here. 
/templates/
 └──Page templates, search template, etc.
/pages/
 └──home.php
 └──404.php
/blog_posts/
 └──Blog posts here
/css/
 └──Style.css
/sidebars/
 └──Sidebars here
```

## .htaccess Explanation
The `.htaccess` file enables URL rewriting for user-friendly links.

## Installation and Setup
1. Ensure your web server supports PHP 7 or later.
2. Upload the project files to your web server.
3. Modify `config/config.php` to set your site’s title, navigation preferences, and other configurations.
4. Add or update content files in the `/pages/` directory.

## 404 Handling
If a requested page does not exist, the script automatically loads `404.php` from the `/pages/` directory and sends the appropriate HTTP 404 header.

## Future Enhancements
- Improved admin panel for content management.
- User authentication for restricted content.
- Performance optimization with caching mechanisms.

## Conclusion
This site architecture provides a robust, flexible solution for dynamic PHP websites without requiring a database. It is designed for easy expansion and customization.


## .htaccess Explanation
The `.htaccess` file enables URL rewriting for user-friendly links.

## Installation and Setup
1. Ensure your web server supports PHP 7 or later.
2. Upload the project files to your web server.
3. Modify `config/config.php` to set your site’s title, navigation preferences, and other configurations.
4. Add or update content files in the `/pages/` directory.

## 404 Handling
If a requested page does not exist, the script automatically loads `404.php` from the `/pages/` directory and sends the appropriate HTTP 404 header.

## Future Enhancements
- Improved admin panel for content management.
- User authentication for restricted content.
- Performance optimization with caching mechanisms.

## Conclusion
This site architecture provides a robust, flexible solution for dynamic PHP websites without requiring a database. It is designed for easy expansion and customization.
