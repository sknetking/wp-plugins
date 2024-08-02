<?php 

/**
 * Plugin Name:       SK Post Grid
 * Plugin URI:         https://www.sknetking.online
 * Description:       Post Grid is a clean way to generate Post Grid data to your WordPress, And show post in grid with category 
 * Version:           0.6.6
 * Author:            Sk netking
 * Author URI:        https://www.sknetking.online
 * Text Domain:       sk-post-grid
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/sknetking/wp-plugins/edit/main/sk-post-grid/post-grid.php
 */

function enqueue_custom_scripts() {
    wp_enqueue_script('custom-ajax',  plugin_dir_url( __FILE__ ). '/custom-ajax.js', array('jquery'), null, true);

    // Localize script to pass AJAX URL
    wp_localize_script('custom-ajax', 'ajax_params', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

function load_more_posts() {
    $paged = $_POST['page'];
    $posts_per_page = 3;

    $query = new WP_Query(array(
        'posts_per_page' => $posts_per_page,
        'paged' => $paged
    ));
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            $post_categories = get_the_category();
            $post_category_slugs = array_map(function($cat) { return $cat->slug; }, $post_categories);
            $post_category_slugs_string = implode(' ', $post_category_slugs);
            ?>
            <div class="col-md-4 mb-4 post-item" data-category="<?php echo $post_category_slugs_string; ?>">
                <div class="card">
                    <?php if(has_post_thumbnail()) : ?>
                        <img src="<?php the_post_thumbnail_url('medium'); ?>" class="card-img-top" alt="<?php the_title(); ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php the_title(); ?></h5>
                        <p class="card-text"><?php the_excerpt(); ?></p>
                        <a href="<?php the_permalink(); ?>" class="btn btn-primary">Read More</a>
                    </div>
                </div>
            </div>
        <?php
        endwhile;
        wp_reset_postdata();
        wp_die();
    else :
        echo '0';
        wp_die();
    endif;

    
}
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts');
add_action('wp_ajax_load_more_posts', 'load_more_posts');

include_once("post-grid-shortcode.php");
