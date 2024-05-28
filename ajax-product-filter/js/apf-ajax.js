jQuery(document).ready(function($) {
    function fetchProducts(page = 1) {
        var category = $('#apf-category').val();
        var brand = $('#apf-brand').val();

        $.ajax({
            url: apf_ajax.ajax_url,
            method: 'POST',
            data: {
                action: 'apf_filter_products',
                category: category,
                brand: brand,
                page: page
            },
            success: function(response) {
                $('.products ').html(response);
			$('.woocommerce-pagination').html('');
            }
        });
    }

    $('#apf-apply-filter').on('click', function(e) {
        e.preventDefault();
        fetchProducts();
    });

    // Handle pagination clicks
    $(document).on('click', '.apf-pagination a', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        fetchProducts(page);
    });
});
