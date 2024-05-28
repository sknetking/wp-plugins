<?php
/*
Plugin Name: AJAX Product Filter
Description: A custom widget to filter WooCommerce products using AJAX.
Version: 1.0
Author:Shyam
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Register custom taxonomy 'brand' for products.
function apf_register_brand_taxonomy() {
    $labels = array(
        'name'              => _x( 'Brands', 'taxonomy general name', 'text_domain' ),
        'singular_name'     => _x( 'Brand', 'taxonomy singular name', 'text_domain' ),
        'search_items'      => __( 'Search Brands', 'text_domain' ),
        'all_items'         => __( 'All Brands', 'text_domain' ),
        'parent_item'       => __( 'Parent Brand', 'text_domain' ),
        'parent_item_colon' => __( 'Parent Brand:', 'text_domain' ),
        'edit_item'         => __( 'Edit Brand', 'text_domain' ),
        'update_item'       => __( 'Update Brand', 'text_domain' ),
        'add_new_item'      => __( 'Add New Brand', 'text_domain' ),
        'new_item_name'     => __( 'New Brand Name', 'text_domain' ),
        'menu_name'         => __( 'Brand', 'text_domain' ),
    );

    $args = array(
        'hierarchical'      => true, // Set to true to behave like categories, false for tags.
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'brand' ),
    );

    register_taxonomy( 'brand', array( 'product' ), $args );
}
add_action( 'init', 'apf_register_brand_taxonomy', 0 );

// Include necessary files.
include_once 'widget.php';

// Enqueue scripts and styles.
function apf_enqueue_scripts() {
    wp_enqueue_script( 'apf-ajax-script', plugin_dir_url( __FILE__ ) . 'js/apf-ajax.js', array('jquery'), null, true );
    wp_localize_script( 'apf-ajax-script', 'apf_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    wp_enqueue_style( 'apf-ajax-style', plugin_dir_url( __FILE__ ) . 'css/apf-ajax.css' );
}
add_action( 'wp_enqueue_scripts', 'apf_enqueue_scripts' );

// Handle the AJAX request.
function apf_filter_products() {
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $brand = isset($_POST['brand']) ? sanitize_text_field($_POST['brand']) : '';
    $page = isset($_POST['page']) ? absint($_POST['page']) : 1;
    $posts_per_page = 10; // Set the number of products per page

    $tax_query = array('relation' => 'AND');

    if (!empty($category)) {
        $tax_query[] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $category,
        );
    }

    if (!empty($brand)) {
        $tax_query[] = array(
            'taxonomy' => 'brand',
            'field'    => 'slug',
            'terms'    => $brand,
        );
    }

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $posts_per_page,
        'paged' => $page,
        'tax_query' => $tax_query,
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }

        // Pagination
        $total_pages = $query->max_num_pages;

        if ($total_pages > 1) {
            $current_page = max(1, $page);
            echo '<div class="apf-pagination">';
            echo paginate_links(array(
                'base' => esc_url(add_query_arg('page', '%#%')),
                'format' => '',
                'current' => $current_page,
                'total' => $total_pages,
                'prev_text' => __('&laquo; Prev', 'text_domain'),
                'next_text' => __('Next &raquo;', 'text_domain'),
                'type' => 'list',
                'end_size' => 1,
                'mid_size' => 2,
            ));
            echo '</div>';
        }
    } else {
        echo __( 'No products found', 'text_domain' );
    }

    wp_reset_postdata();
    wp_die();
}
add_action( 'wp_ajax_nopriv_apf_filter_products', 'apf_filter_products' );
add_action( 'wp_ajax_apf_filter_products', 'apf_filter_products' );
