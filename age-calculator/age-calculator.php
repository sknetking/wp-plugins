<?php
/*
Plugin Name: Age Calculator Widget
Description: Custom Elementor widget for calculating age.
Version: 1.0
*/

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// Include the main Elementor files
add_action('elementor/widgets/widgets_registered', function () {
    
});

function register_age_calculator_widget( $widgets_manager ) {

	require_once(__DIR__ . '/age-calculator-widget.php');
	
	$widgets_manager->register( new \Age_Calculator_Widget() );


}
add_action( 'elementor/widgets/register', 'register_age_calculator_widget' );



// Enqueue scripts and styles
function age_calculator_widget_scripts() {
    wp_enqueue_script('age-calculator-widget-script', plugins_url('/script.js', __FILE__), array('jquery'), '1.0', true);
    wp_enqueue_style('age-calculator-widget-style', plugins_url('/style.css', __FILE__), array(), '1.0');
}
add_action('elementor/frontend/after_enqueue_scripts', 'age_calculator_widget_scripts');