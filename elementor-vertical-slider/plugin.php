<?php
/**
 * Plugin Name: Elementor Vertical Slider (Updated)
 * Description: Vertical slick slider with post type selection and Elementor template selection.
 * Version: 1.1.0
 * Text Domain: evs
 */

if (!defined('ABSPATH')) exit;

final class EVS_Plugin {
    const SLICK_CSS = 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css';
    const SLICK_JS  = 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js';

    public function __construct() {
        add_action('elementor/widgets/register', [ $this, 'register_widget' ]);
        add_action('wp_enqueue_scripts', [ $this, 'enqueue_assets' ]);
    }

    public function enqueue_assets() {
        // Enqueue slick from CDN
        wp_enqueue_style('evs-slick', self::SLICK_CSS, [], '1.9.0');
        // Plugin styles
        wp_enqueue_style('evs-style', plugin_dir_url(__FILE__) . 'assets/css/style.css', [], '1.0.0');

        // Enqueue jQuery dependency and slick script
        wp_enqueue_script('evs-slick', self::SLICK_JS, ['jquery'], '1.9.0', true);
    }

    public function register_widget($widgets_manager) {
        if (!defined('ELEMENTOR_PATH') || !class_exists('Elementor\Widget_Base')) {
            return;
        }
        require_once(__DIR__ . '/widgets/vertical-slider-widget.php');
        $widgets_manager->register(new \EVS_Vertical_Slider_Widget());
    }
}
new EVS_Plugin();