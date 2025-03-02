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

## 📂 Directory Structure
```plaintext
/index.php
/config/
 └── config.php
 └── /addons/
     └── (Addon files here)
/templates/
 └── (Page templates, search template, etc.)
/pages/
 └── home.php
 └── 404.php
/blog_posts/
 └── (Blog posts here)
/css/
 └── style.css
/sidebars/
 └── (Sidebar files here)
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
- 🛠 **Admin Panel:** Simplified content management.
- 🔐 **User Authentication:** Restricted content support.
- ⚡ **Performance Optimization:** Caching mechanisms for faster load times.

## 🏁 Conclusion
TemplateForge provides a robust, flexible architecture for dynamic PHP websites—without the overhead of a database. Its modular design ensures easy expansion and customization. 🚀

> 💡 **Contributions & Feedback Welcome!** If you have suggestions or want to contribute, feel free to submit a pull request or open an issue! 🎉

