<?php

class APF_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'apf_widget',
            __( 'AJAX Product Filter', 'text_domain' ),
            array( 'description' => __( 'A widget to filter WooCommerce products using AJAX.', 'text_domain' ) )
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        // Widget content
        echo '<div id="apf-filter">';
        echo '<h3>' . __( 'Filter Products', 'text_domain' ) . '</h3>';

        // Category filter
        echo '<select id="apf-category">';
        echo '<option value="">' . __( 'Select Category', 'text_domain' ) . '</option>';
        $categories = get_terms( 'product_cat' );
        foreach ( $categories as $category ) {
            echo '<option value="' . $category->slug . '">' . $category->name . '</option>';
        }
        echo '</select>';

        // Brand filter
        echo '<select id="apf-brand">';
        echo '<option value="">' . __( 'Select Brand', 'text_domain' ) . '</option>';
        $brands = get_terms( 'brand' ); // Assuming 'brand' is the custom taxonomy
        foreach ( $brands as $brand ) {
            echo '<option value="' . $brand->slug . '">' . $brand->name . '</option>';
        }
        echo '</select>';

        echo '<button id="apf-apply-filter">' . __( 'Apply Filter', 'text_domain' ) . '</button>';
        echo '</div>';
        echo '<div id="apf-results"></div>';

        echo $args['after_widget'];
    }

    public function form( $instance ) {
        // Widget admin form (optional).
    }

    public function update( $new_instance, $old_instance ) {
        // Save widget options (optional).
    }
}

function register_apf_widget() {
    register_widget( 'APF_Widget' );
}
add_action( 'widgets_init', 'register_apf_widget' );
