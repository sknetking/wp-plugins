 <?php
 
 function post_grid_method(){

 	ob_start();
// Get all categories
$categories = get_categories();

?>

<div class="container">
    <div class="row my-4">
        <div class="col-md-4 offset-md-8">
            <select id="category-filter" class="form-control">
                <option value="">All Categories</option>
                <?php foreach($categories as $category) : ?>
                    <option value="<?php echo $category->slug; ?>"><?php echo $category->name; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div id="post-grid" class="row">
        <?php
        // Query posts
        $query = new WP_Query(array('posts_per_page' => 3));

        if($query->have_posts()) :
            while($query->have_posts()) : $query->the_post();
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
            <?php endwhile;
            wp_reset_postdata();
        else :
            echo '<p>No posts found</p>';
        endif;
        ?>
    </div>

    <?php if ($query->max_num_pages > 1) : ?>
        <div class="row">
            <div class="col-md-12 text-center">
                <button id="load-more" class="btn btn-primary">Load More</button>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
jQuery(document).ready(function($) {
    // Show all posts by default
    $('.post-item').show();

    $('#category-filter').change(function() {
        var selectedCategory = $(this).val();
        if (selectedCategory === "") {
            // Show all posts if "All Categories" is selected
            $('.post-item').show();
        } else {
            // Show only the posts that match the selected category
            $('.post-item').each(function() {
                var itemCategories = $(this).data('category').split(' ');
                var showPost = itemCategories.includes(selectedCategory);
                if (showPost) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    });
});
</script>

<?php 

return ob_get_clean();
}

add_shortcode('show_post_gride', "post_grid_method");

?>
