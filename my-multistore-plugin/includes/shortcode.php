<?php

function show_store_switcher(){
  

    $selected_store = isset($_COOKIE['selected_store']) ? $_COOKIE['selected_store'] : 'all'; 
    
    // Retrieve all stores from the 'store' taxonomy
    $stores = get_terms(array(
        'taxonomy' => 'store',
        'hide_empty' => false,
    ));
   ob_get_contents();
    ?>
  <select id="store-selector">
        <option value="all" <?php selected($selected_store, 'all'); ?>>Select a Store</option>
        <?php foreach ($stores as $store) : 
            // Get latitude and longitude for each store
            $store_lat = get_term_meta($store->term_id, 'store_lat', true);
            $store_lon = get_term_meta($store->term_id, 'store_lon', true);
            ?>
            <option value="<?php echo esc_attr($store->slug); ?>" 
                <?php selected($selected_store, $store->slug); ?> 
                data-location="<?php echo esc_attr($store_lat . ',' . $store_lon); ?>">
                <?php echo esc_html($store->name); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <span id='nearest_store' title='Get Nearest Store'> <img src="<?php echo plugin_dir_url(__DIR__);?>assets/current-location.png"> </span>
    <script>
        
    document.getElementById('store-selector').addEventListener('change', function() {
        document.cookie = "selected_store=" + this.value + "; path=/";
        location.reload(); // Reload the page to apply the filter
    });
    </script>
    <?php

    return ob_get_clean();
}
add_shortcode('store-switcher','show_store_switcher');