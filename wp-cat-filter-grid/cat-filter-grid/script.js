jQuery(document).ready(function($) {
    // Trigger filter when category is selected
    $('#product-category-filter li a').click(function(e) {
        e.preventDefault();

        // Remove 'active' class from all category links and add to the clicked one
        $('#product-category-filter li a').removeClass('active');
        $(this).addClass('active');

        // Get the category ID of the selected link
        var category_id = $(this).attr("data-category");
        var page = 1;

        // Update the URL with the selected category
        var url = window.location.protocol + "//" + window.location.host + window.location.pathname + '?category_id=' + category_id;
        history.pushState(null, null, url);

        // Trigger product filtering
        filter_products(category_id, page);
    });

    function filter_products(category_id, page) {
        $.ajax({
            url: miniCartParams.ajaxUrl, // Ensure this is localized in PHP
            type: 'POST',
            data: {
                action: 'filter_products',
                category_id: category_id,
                paged: page
            },
            success: function(response) {
                if (response.products) {
                    $('#product-grid-container').html(response.products);
                }
                if (response.pagination) {
                    $('#pagination-content').html(response.pagination); // Insert pagination
                } else {
                    $('#pagination-content').empty(); // Clear pagination if none exists
                }
            }
        });
    }

    // Handle pagination clicks
    $(document).on('click', '#pagination-content a', function(e) {
        e.preventDefault();

        // Get the page number from the pagination link
        var link = $(this).attr('href');
        var page = new URL(link).searchParams.get('paged') || 1;

        // Get the active category ID
        var category_id = $('#product-category-filter .active').attr("data-category") || '';

        // Trigger product filtering
        filter_products(category_id, page);
    });

    // Initial product load (check if category is in URL)
    function getQueryParam(param) {
        var urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    var initialCategory = getQueryParam('category_id') || '';
    var initialPage = getQueryParam('paged') || 1;

    // Load products based on initial parameters
    filter_products(0,1);
});
