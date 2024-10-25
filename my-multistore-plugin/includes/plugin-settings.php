<?php

// 1. Register 'store' Taxonomy for 'product' Post Type
function register_store_taxonomy() {
    register_taxonomy(
        'store',
        'product',
        array(
            'label'        => __('Stores'),
            'rewrite'      => array('slug' => 'store'),
            'hierarchical' => true,
        )
    );
}
add_action('init', 'register_store_taxonomy');

// 2. Add Latitude and Longitude Fields to 'Add New Store' Form
function add_store_lat_lon_fields($taxonomy) {
    ?>
    <div class="form-field term-group">
        <label for="store_lat"><?php _e('Latitude', 'textdomain'); ?></label>
        <input type="text" id="store_lat" name="store_lat" value="" placeholder="Enter latitude">
    </div>
    <div class="form-field term-group">
        <label for="store_lon"><?php _e('Longitude', 'textdomain'); ?></label>
        <input type="text" id="store_lon" name="store_lon" value="" placeholder="Enter longitude">
    </div>
    <?php
}
add_action('store_add_form_fields', 'add_store_lat_lon_fields');

// 3. Add Latitude and Longitude Fields to 'Edit Store' Form
function edit_store_lat_lon_fields($term) {
    $store_lat = get_term_meta($term->term_id, 'store_lat', true);
    $store_lon = get_term_meta($term->term_id, 'store_lon', true);
    ?>
    <tr class="form-field term-group-wrap">
        <th scope="row">
            <label for="store_lat"><?php _e('Latitude', 'textdomain'); ?></label>
        </th>
        <td>
            <input type="text" id="store_lat" name="store_lat" value="<?php echo esc_attr($store_lat); ?>" placeholder="Enter latitude">
        </td>
    </tr>
    <tr class="form-field term-group-wrap">
        <th scope="row">
            <label for="store_lon"><?php _e('Longitude', 'textdomain'); ?></label>
        </th>
        <td>
            <input type="text" id="store_lon" name="store_lon" value="<?php echo esc_attr($store_lon); ?>" placeholder="Enter longitude">
        </td>
    </tr>
    <?php
}
add_action('store_edit_form_fields', 'edit_store_lat_lon_fields');

// 4. Save Latitude and Longitude Fields
function save_store_lat_lon_fields($term_id) {
    if (isset($_POST['store_lat'])) {
        update_term_meta($term_id, 'store_lat', sanitize_text_field($_POST['store_lat']));
    }
    if (isset($_POST['store_lon'])) {
        update_term_meta($term_id, 'store_lon', sanitize_text_field($_POST['store_lon']));
    }
}
add_action('created_store', 'save_store_lat_lon_fields');
add_action('edited_store', 'save_store_lat_lon_fields');
