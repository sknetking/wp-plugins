<?php
/**
 * Plugin Name: AI Content Generator
 * Description: Automatically generate and publish content using Google Gemini API
 * Version: 1.0.0
 * Author: Shyam
 * License: GPL-2.0+
 */

defined('ABSPATH') or die('Direct access not allowed');

// Define plugin constants
define('AI_CONTENT_GENERATOR_VERSION', '1.0.0');
define('AI_CONTENT_GENERATOR_PATH', plugin_dir_path(__FILE__));
define('AI_CONTENT_GENERATOR_URL', plugin_dir_url(__FILE__));

// Include required files
require_once AI_CONTENT_GENERATOR_PATH . 'includes/class-settings.php';
require_once AI_CONTENT_GENERATOR_PATH . 'includes/class-content-generator.php';
require_once AI_CONTENT_GENERATOR_PATH . 'includes/class-cron.php';
require_once AI_CONTENT_GENERATOR_PATH . 'includes/class-yoast-integration.php';
require_once AI_CONTENT_GENERATOR_PATH . 'includes/class-ajaxhandler.php';

// Initialize plugin classes
function ai_content_generator_init() {
    new AI_Content_Generator_Settings();
    new AI_Content_Generator_Cron();
    new AI_Content_Generator_Yoast_Integration();
}
add_action('plugins_loaded', 'ai_content_generator_init');

// Register activation/deactivation hooks
register_activation_hook(__FILE__, array('AI_Content_Generator_Cron', 'activate'));
register_deactivation_hook(__FILE__, array('AI_Content_Generator_Cron', 'deactivate'));