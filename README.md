# 🚀 TemplateForge

**TemplateForge** is a modular, flat-file-based website built with PHP, designed to provide dynamic content loading, a blog system, and search functionality—all without a database.

## 📌 Key Features

### 🗂 Flat-File Content Management
- **Modular Content:** Each page is stored as an individual PHP file in the `/pages/` directory.
- **Blog System:** Blog posts are stored in `/blog_posts/` for easy management.
- **Seamless Integration:** Output buffering captures content for smooth template rendering.

### 🎨 Centralized Template System
- **Unified Layout:** A single template file (`main_template.php`) defines the entire site layout.
- **Dynamic Content Injection:** Pages and blog posts integrate seamlessly into the template.
- **Sidebar Support:** Easily configurable sidebars located in `/sidebars/`.

### 🧩 Modular Add-On Support
- **Auto-Loaded Modules:** The `config.php` file automatically loads all PHP files in `/config/addons/`.
- **Smart Navigation:** Dynamically generated menus with active class highlighting.

### 🔍 Built-In Search Functionality
- **Site-Wide Search:** Users can find content across both static pages and blog posts.
- **Custom Search Results Page:** Results are displayed in a dedicated template (`search_template.php`).

### 🔑 Admin Area
- **Content Management:** Manage pages, blog posts, and site settings from a dedicated Admin Area.
- **User Authentication:** Login-protected Admin Area to securely manage content.
- **Intuitive Interface:** A simple interface for creating and editing pages, blog posts, and site settings.
- **Real-Time Previews:** Preview changes to content before publishing them live.

### 📊 Basic Analytics
- **Page Views Tracking:** Track the number of views for each page and blog post.
- **Custom Analytics Page:** A simple analytics dashboard that aggregates page views data, displaying which pages and posts are being accessed the most.
- **Configuration:** Analytics data is stored in a JSON file in `/config/data/`, and can be easily integrated with third-party tools or exported for further analysis.


## 📂 Directory Structure
```plaintext
/index.php                 # Main entry point
/config/                   # Configuration files
 └── config.php            # Main configuration settings
 └── /addons/              # Additional modules or add-ons
/templates/                # Template files
 └── main_template.php     # Primary template file
/pages/                    # Static pages
 └── home.php              # Homepage
 └── 404.php               # 404 error page
/blog_posts/               # Blog post files
 └── blog_post_1.php       # Example blog post
/css/                      # Stylesheets
 └── style.css             # Main stylesheet
/sidebars/                 # Sidebar content files
 └── sidebar_left.php      # Left sidebar
/admin/                    # Admin area files
 └── admin.php             # Main admin page
 └── login.php             # Login page
 └── /pages/               # Admin content management
     └── Pages Go here     # page functionality
```

## ⚙️ Installation & Setup
1. **Ensure PHP 7+** is installed on your web server.
2. **Upload** the project files to your web server.
3. **Modify** `config/config.php` to set up site settings like title, navigation, and preferences.
4. **Customize Content:** Add or edit content files in the `/pages/` directory.

## 🔄 URL Rewriting & .htaccess
TemplateForge utilizes `.htaccess` for user-friendly URLs.

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

This allows pages to be accessed via clean URLs like `/about` instead of `/pages/about.php`.

## 🚨 404 Handling
If a requested page is not found, the system automatically:
1. Loads `404.php` from the `/pages/` directory.
2. Sends an appropriate HTTP 404 response header.

## 🚀 Future Enhancements
- 🛠 ~~**Admin Panel:** Simplified content management.~~
- 🔐 ~~**User Authentication:** Restricted content support.~~
- ⚡ **Performance Optimization:** Caching mechanisms for faster load times.

## 🏁 Conclusion
TemplateForge provides a robust, flexible architecture for dynamic PHP websites—without the overhead of a database. Its modular design ensures easy expansion and customization. 🚀

> 💡 **Contributions & Feedback Welcome!** If you have suggestions or want to contribute, feel free to submit a pull request or open an issue! 🎉

