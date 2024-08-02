jQuery(document).ready(function($) {
    var page = 1;
    var posts_per_page = 3;

    function load_more_posts() {
        page++;
        $.ajax({
            url: ajax_params.ajax_url,
            type: 'post',
            data: {
                action: 'load_more_posts',
                page: page
            },
            success: function(response) {
                if(response == 0) {
                    $('#load-more').hide();
                } else {
                    $('#post-grid').append(response);
                }
            }
        });
    }

    $('#load-more').on('click', function() {
      
        load_more_posts();
    });
});
