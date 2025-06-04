<?php
/**
 * Plugin Name: LearnPress To-Do List
 * Description: Adds a to-do list to LearnPress user profile.
 * Version: 1.0
 * Author: Your Name
 */

define('TODO_LIST_PATH', plugin_dir_path(__FILE__));
define('TODO_LIST_URL', plugin_dir_url(__FILE__));

// Include core functionality
require_once TODO_LIST_PATH . 'inc/functions.php';

// Enqueue Scripts
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('todo-custom-css', TODO_LIST_URL . 'assets/custom.css');
    wp_enqueue_script('todo-custom-js', TODO_LIST_URL . 'assets/custom.js', ['jquery'], null, true);
    wp_localize_script('todo-custom-js', 'todo_ajax_obj', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('todo_ajax_nonce')
    ]);
});
