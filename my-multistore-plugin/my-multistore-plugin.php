<?php
/*
Plugin Name: WooCommerce Multi-Store Plugin
Description: -ShortCode use for show store switcher - [store-switcher] Manage multiple WooCommerce stores by location with GPS detection, price overrides, stock management, and more.
Version: 1.0.0
Author: Shyam
*/

if (!defined('ABSPATH')) {
    exit; // Prevent direct access.
} 

include_once("includes/plugin-settings.php");
include_once("includes/shortcode.php");


function enqueue_multistore_scripts() {
    wp_enqueue_script(
        'gps-detection',
        plugin_dir_url(__FILE__) . 'assets/js/gps-detection.js',
        array('jquery'),
        null,
        true
    );
    wp_enqueue_style('style',plugin_dir_url(__FILE__) . 'assets/css/style.css');
}
add_action('wp_enqueue_scripts', 'enqueue_multistore_scripts');




function store_switcher() {
    // Get the selected store from the cookie, defaulting to 'all'
    if(is_shop()){
         echo do_shortcode('[store-switcher]');
    }
}
add_action('wp_footer', 'store_switcher'); 
 // Add this to the footer


function filter_products_by_store($query) {
    if (!is_admin() && $query->is_main_query() && (is_shop() || is_product_category() || is_product_tag())) {
        $selected_store = isset($_COOKIE['selected_store']) ? $_COOKIE['selected_store'] : 'all';

        // Apply filtering only if a specific store is selected
        if ($selected_store !== 'all') {
            $query->set('tax_query', array(
                array(
                    'taxonomy' => 'store',
                    'field'    => 'slug',
                    'terms'    => $selected_store,
                ),
            ));
        }
    }
}
add_action('pre_get_posts', 'filter_products_by_store');