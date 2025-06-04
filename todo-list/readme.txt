\=== To-Do List for WordPress (LearnPress Ready) ===
Contributors: your-name
Tags: todo list, task manager, learnpress, profile tab, ajax, user meta, shortcode
Requires at least: 5.6
Tested up to: 6.5
Stable tag: 1.0.0
License: GPLv2 or later
License URI: [https://www.gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html)

A simple and personalized To-Do List plugin for WordPress. Works with or without LearnPress. Supports full CRUD (Create, Read, Update, Delete) and AJAX-powered interactions.

\== Description ==

This lightweight plugin adds a personalized to-do list app for your WordPress users. Each user manages their own private task list (stored in user meta). Users can add, edit, delete, and mark tasks as complete — all without page reloads thanks to AJAX.

**Two usage modes:**

1. **With LearnPress:** Automatically adds a "To-Do List" tab in the user's LearnPress profile.
2. **Without LearnPress:** Use the `[todo_list]` shortcode to display the to-do app anywhere — pages, posts, widgets, or Elementor.

\== Features ==

* Fully AJAX-powered (no page reloads)
* Each user sees only their own tasks
* Add, edit (inline), delete, mark as done/undone
* Works with LearnPress user profiles
* Or use `[todo_list]` anywhere
* Tasks saved in `user_meta` (fast and secure)
* Shift+Enter adds new line in editing; Enter saves
* Styled with basic CSS (can be customized)

\== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/todo-list/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. ✅ If LearnPress is active, a "To-Do List" tab appears in user profiles
4. ✅ Or use `[todo_list]` in any page/post to embed the to-do app

\== Usage ==

**1. With LearnPress:**

* Go to your LearnPress Profile (logged in as a user)
* Click on the "📝 To-Do List" tab
* Start adding your tasks!

**2. Without LearnPress:**

* Edit any post or page and add this shortcode:
