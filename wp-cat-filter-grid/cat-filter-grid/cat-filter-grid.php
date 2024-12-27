<?php
/**
 * Plugin Name: WooCommerce Product Gride
 * Description: Adds a WooCommerce mini cart to the WordPress menu with off-canvas functionality.
 * Version: 1.0
 * Author: Shyam
 * Text Domain: wc-pro-grid
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Validate

add_action('wp_enqueue_scripts', 'wc_mini_cart_enqueue_scripts');
function wc_mini_cart_enqueue_scripts() {
    // Enqueue styles
    wp_enqueue_style('wc-mini-cart-style', plugin_dir_url(__FILE__) . 'style.css');
  
	wp_enqueue_style( 'woocommerce-general' );
        wp_enqueue_style( 'woocommerce-layout' );
        wp_enqueue_style( 'woocommerce-smallscreen' );
  
    // Enqueue scripts
    wp_enqueue_script('wc-mini-cart-script', plugin_dir_url(__FILE__) . 'script.js', array('jquery'), '1.0', true);

    // Localize AJAX URL
    wp_localize_script('wc-mini-cart-script', 'miniCartParams', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
    ));
}

// Display product short description after title on the shop page
add_action('woocommerce_after_shop_loop_item_title', 'add_short_description_to_shop_page', 5);

function add_short_description_to_shop_page() {
    global $product;

    if (is_shop() || is_product_category() || is_product_tag()) {
        echo '<div class="woocommerce-product-short-description">' . wp_kses_post($product->get_description()) . '</div>';
    }
}

function filter_products_ajax() {
      $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : '';
    $paged = isset($_POST['paged']) ? $_POST['paged'] : 1;

    // Define arguments for WP_Query
    $args = array(
        'post_type' => 'product', // Use 'product' if you're using WooCommerce
        'posts_per_page' => 4, // Limit to 10 products per page
        'paged' => $paged,
    );

    if (!empty($category_id)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'id',
                'terms'    => $category_id,
                'operator' => 'IN',
            ),
        );
    }

    $query = new WP_Query($args);

    // Query WooCommerce products
    $query = new WP_Query($args);

    ob_start(); // Start buffering product content
    if ($query->have_posts()) {
        echo '<ul class="products columns-4">';
        while ($query->have_posts()) {
            $query->the_post();
            // Use WooCommerce template part for rendering products
            wc_get_template_part('content', 'product');
        }
        echo '</ul>';
    } else {
        echo '<p>No products found.</p>';
    }
    $product_content = ob_get_clean(); // Get product content
	
    // Generate pagination
$pagination = paginate_links(array(
            'base' => home_url('/') . '%_%', // Base URL for the pagination
            'format' => '?paged=%#%', // Pagination query parameter
            'total' => $query->max_num_pages,
            'current' => $paged,
            'add_args' => array('category_id' => $category_id), // Add category_id to query parameters
            'prev_text' => '&laquo;',
            'next_text' => '&raquo;',
        ));

    // Prepare JSON response
    $response = array(
        'products'   => $product_content, // HTML for products
        'pagination' => $pagination,      // HTML for pagination
    );

    wp_reset_postdata(); // Always reset post data

    wp_send_json($response); // Send JSON response
}
add_action('wp_ajax_filter_products', 'filter_products_ajax');
add_action('wp_ajax_nopriv_filter_products', 'filter_products_ajax');



add_shortcode('show_gride',"callback_function");

 function callback_function(){
	ob_start();
	 
$categories = get_terms( array(
    'taxonomy' => 'product_cat',
    'orderby'  => 'name',
    'order'    => 'ASC',
    'hide_empty' => true,
	'exclude'                  =>array(15)
) );

// Create category filter dropdown
echo '<ul id="product-category-filter" class="nav-tabs">';
echo '<li class="js-filter-item"><a href="#" class="filter-item  active" data-category="0">All</a></li>';
foreach ( $categories as $category ) {
    echo '<li class="js-filter-item">';
	echo '<a href="#" class="filter-item" data-category="'. esc_attr( $category->term_id ).'">'.esc_html( $category->name ) .'</a> </li>';;
}
echo '</ul>';
// Initial product grid display
?>
<div id="product-grid-container" class="woocommerce">
    <!-- AJAX-loaded products will appear here -->
</div>

<div id="pagination-content" class="pagination-wrapper">
    <!-- AJAX-loaded pagination will appear here -->
</div>

	<?php
	return ob_get_clean();
}
