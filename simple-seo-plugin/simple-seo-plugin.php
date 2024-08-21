<?php
/*
Plugin Name: Simple SEO Plugin
Description: A simple SEO plugin for adding meta data.
Version: 1.0
Author: ShyamKaran Sahani
*/

// Create admin page
include_once "seo-settings.php";

function enqueue_bootstrap_cdn() {
    // Enqueue Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css', array(), '4.5.2');

    // Optionally, enqueue Popper.js (required for some Bootstrap components)
    wp_enqueue_script('popper-js', 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js', array(), '2.9.3', true);

    // Enqueue Bootstrap JS
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js', array('jquery', 'popper-js'), '4.5.2', true);
}
add_action('admin_enqueue_scripts', 'enqueue_bootstrap_cdn');


// Output meta data in post head
function output_post_seo_meta_data() {
    if ( is_single() ) {
        $og_url = get_post_meta( get_the_ID(), 'og_url', true );
        $og_type = get_post_meta( get_the_ID(), 'og_type', true );
        $og_title = get_post_meta( get_the_ID(), 'og_title', true );
        $og_description = get_post_meta( get_the_ID(), 'og_description', true );
        $meta_description = get_post_meta( get_the_ID(), 'meta_description', true );
        $meta_keywords = get_post_meta( get_the_ID(), 'meta_keywords', true );
        $meta_author = get_post_meta( get_the_ID(), 'meta_author', true );
        $meta_language = get_post_meta( get_the_ID(), 'meta_language', true );
        $meta_viewport = get_post_meta( get_the_ID(), 'meta_viewport', true );
		$index_allow =  get_post_meta(get_the_ID(), 'index_allow', true );
       ?>
       <?php if($index_allow == "true"):?>  
		<meta name="robots" content="index,follow">
		<?php else: ?>
		<meta name="robots" content="noindex,nofollow">
		<?php endif;  ?>
       <?php if($og_url!=''): ?> <meta property="og:url" content="<?php echo esc_attr( $og_url ); ?>"/> <?php endif; ?>
       <?php if($og_type!=''): ?>  <meta property="og:type" content="<?php echo esc_attr( $og_type ); ?>"/> <?php endif;?>
        <?php if($og_title!=''): ?> <meta property="og:title" content="<?php echo esc_attr( $og_title ); ?>"/>  <?php endif;?>
       <?php if($og_description!=''): ?>  <meta property="og:description" content="<?php echo esc_attr( $og_description ); ?>"><?php endif;?>
       <?php if($meta_description!=''): ?>  <meta name="description" content="<?php echo esc_attr( $meta_description ); ?>"/> <?php endif;?>
       <?php if($meta_keywords!=''): ?>  <meta name="keywords" content="<?php echo esc_attr( $meta_keywords ); ?>"/> <?php endif;?>
       <?php if($meta_author!=''): ?>  <meta name="author" content="<?php echo esc_attr( $meta_author ); ?>"/> <?php endif;?>
       <?php if($meta_language!=''): ?>  <meta name="language" content="<?php echo esc_attr( $meta_language ); ?>"/>  <?php endif;?>
       <?php if($meta_viewport!=''): ?>  <meta name="viewport" content="<?php echo esc_attr( $meta_viewport ); ?>"/>  <?php endif;?>
       <?php if(!empty(get_the_post_thumbnail_url())):?> <meta property="og:image" content="<?php echo esc_attr(get_the_post_thumbnail_url()); ?>"/> <meta property="twitter:image" content="<?php echo esc_attr(get_the_post_thumbnail_url()); ?>"/>  <?php endif;?>
     <?php
    }
}
add_action( 'wp_head', 'output_post_seo_meta_data',0);

// Add custom meta box
function add_post_seo_meta_box() {
    add_meta_box(
        'post_seo_meta_box',
        'SEO Meta Data',
        'display_post_seo_meta_box',
        'post', // Post type where the meta box will be displayed
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'add_post_seo_meta_box' );

// Display custom meta box content
function display_post_seo_meta_box( $post ) {
    $og_url = get_post_meta( $post->ID, 'og_url', true );
    $og_type = get_post_meta( $post->ID, 'og_type', true );
    $og_title = get_post_meta( $post->ID, 'og_title', true );
    $og_description = get_post_meta( $post->ID, 'og_description', true );
    $meta_description = get_post_meta( $post->ID, 'meta_description', true );
    $meta_keywords = get_post_meta( $post->ID, 'meta_keywords', true );
    $meta_author = get_post_meta( $post->ID, 'meta_author', true );
    $meta_language = get_post_meta( $post->ID, 'meta_language', true );
    $meta_viewport = get_post_meta( $post->ID, 'meta_viewport', true );
    $index_allow =  get_post_meta( $post->ID, 'index_allow', true );
	 ?>
  <label class="form-label" for="og_url" >Your Post URL:</label><br>
    <input type="text" id="og_url" name="og_url" class="form-control" value="<?php echo !empty(esc_attr( $og_url ))?esc_attr( $og_url ):the_permalink($post->ID); ?>"><br>
    <!-- Add more input fields for other meta data -->
    <label class="form-label" for="og_type">Your Content Type(article,meadia,post):</label><br>
    <input type="text" id="og_type" name="og_type" class="form-control" value="<?php echo !empty(esc_attr($og_type))?$og_type:'post'; ?>"><br>
    <label class="form-label" for="og_title">Meta Title:</label><br>
    <input type="text" id="og_title" name="og_title" class="form-control" value="<?php echo !empty(esc_attr($og_title))?$og_title:the_title(); ?>"><br>
    <label class="form-label" for="og_description">Meta Description:</label><br>
    <textarea type="text" rows="4" cols="50" id="og_description" class="form-control" name="og_description"> <?php echo !empty(esc_attr($og_description))?esc_html($og_description):the_excerpt(); ?> </textarea ><br>
    <label class="form-label" for="meta_keywords">Meta Kaywords With (,) Sepreated:</label><br>
    <textarea type="text" rows="4" cols="50" id="meta_keywords" class="form-control" name="meta_keywords"> <?php echo esc_attr($meta_keywords); ?> </textarea ><br>
<div class="form-check">
  <input class="form-check-input" type="checkbox" id="index_allow" name='index_allow' value='allow' checked='<?php echo $index_allow;?>'>
  <label class="form-check-label" for="index_allow" style="position:relative;top: -7px;font-size: 16px;">Allow Search Indexing.</label>
</div>
    <label class="form-label" for="meta_author">Meta Author:</label><br>
    <input type="text" id="meta_author" name="meta_author" class="form-control" value="<?php echo esc_attr($meta_author)?esc_attr($meta_author):get_the_author_meta('display_name'); ?>"><br>
    <label class="form-label" for="meta_language">Meta Language:</label><br>
    <input type="text" id="meta_language" name="meta_language" class="form-control" value="<?php echo esc_attr($meta_language); ?>"><br>
 </div>

  
    <?php
}

// Save custom meta box data
function save_post_seo_meta_data( $post_id ) {
    if ( isset( $_POST['og_url'] ) || $_POST['og_title']) {
         update_post_meta( $post_id, 'og_url', sanitize_text_field( $_POST['og_url'] ) );
        update_post_meta( $post_id, 'og_type', sanitize_text_field( $_POST['og_type'] ) );
        update_post_meta( $post_id, 'og_title', sanitize_text_field( $_POST['og_title'] ) );
        update_post_meta( $post_id, 'og_description', sanitize_text_field( $_POST['og_description'] ) );
        update_post_meta( $post_id, 'meta_keywords', sanitize_text_field( $_POST['meta_keywords'] ) );
        update_post_meta( $post_id, 'meta_language', sanitize_text_field( $_POST['meta_language'] ) );
        update_post_meta( $post_id, 'meta_author', sanitize_text_field( $_POST['meta_author'] ) );
        update_post_meta( $post_id, 'meta_language', sanitize_text_field( $_POST['meta_language'] ) );
        update_post_meta( $post_id, 'index_allow', sanitize_text_field( !empty($_POST['index_allow'])?'true':'false') );
    }
    // Add more update_post_meta() calls for other fields
}
add_action( 'save_post', 'save_post_seo_meta_data' );
// Display admin page