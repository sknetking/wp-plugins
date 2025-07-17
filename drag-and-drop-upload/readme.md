# Drag & Drop User File Upload for WordPress

This lightweight WordPress plugin allows logged-in users to **upload media using a drag-and-drop interface**. The uploaded file is saved to the Media Library and also stored in **user meta** for later use — such as **user profile pictures**, **cover photos**, or **custom documents**.

## 🔧 Features

- AJAX-based drag-and-drop upload interface
- Progress bar for visual feedback
- File is saved in the WordPress Media Library
- Attachment ID and URL saved to the current user's meta
- Display uploaded image using a simple shortcode
- Customizable and extendable

---

## 🚀 Installation

1. Upload the plugin folder to `/wp-content/plugins/` or install via the WordPress admin.
2. Activate the plugin through **Plugins > Installed Plugins**.
3. Use the shortcode `[ddu_upload_form]` where you want to show the upload form.

---

## 🧑‍💻 Usage

### ✅ Upload Form Shortcode

To show the drag-and-drop upload form on any page or post, use:

 You can use this to show:

👤 User profile pictures

🖼️ Cover images for profile pages

📄 User-submitted documents

💡 Example Use Cases
Member profile builder

User dashboard with avatar uploads

Frontend media uploads linked to users

Online communities or job boards with profile customization

You can modify the plugin to allow multiple uploads per user, support more file types, or change where files are stored in user meta.

📂 Meta Keys Used
ddu_uploaded_file – Array with 'id' and 'url'

Optionally:

ddu_file_id

ddu_file_url
