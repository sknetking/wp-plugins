// Step 1: Add additional note field to single product page
add_action('woocommerce_before_add_to_cart_button', 'add_additional_note_field');

function add_additional_note_field() {
    echo '<div class="additional-note">';
    woocommerce_form_field('additional_note', array(
        'type' => 'text',
        'class' => array('additional-note-class form-row-wide'),
        'label' => __('Special Note', 'woocommerce'),
        'placeholder' => __('Enter your Special note here', 'woocommerce'),
        'required' => false,
    ), '');
    echo '</div>';
}

// Step 2: Validate and save additional note field to cart item data
add_filter('woocommerce_add_cart_item_data', 'save_additional_note_to_cart', 10, 2);

function save_additional_note_to_cart($cart_item_data, $product_id) {
    if (isset($_POST['additional_note'])) {
        $cart_item_data['additional_note'] = sanitize_text_field($_POST['additional_note']);
    }
    return $cart_item_data;
}

// Step 3: Display additional note in cart and checkout
add_filter('woocommerce_get_item_data', 'display_additional_note_in_cart', 10, 2);

function display_additional_note_in_cart($item_data, $cart_item) {
    if (!empty($cart_item['additional_note'])) {
        $item_data[] = array(
            'key' => __('Special Note', 'woocommerce'),
            'value' => wc_clean($cart_item['additional_note']),
            'display' => ''
        );
    }
    return $item_data;
}

// Step 4: Save additional note to order item meta
add_action('woocommerce_checkout_create_order_line_item', 'save_additional_note_to_order_item_meta', 10, 4);

function save_additional_note_to_order_item_meta($item, $cart_item_key, $values, $order) {
    if (isset($values['additional_note'])) {
        $item->add_meta_data(__('Special Note', 'woocommerce'), $values['additional_note']);
    }
}



// Step 5: Display additional note in order details
add_action('woocommerce_order_item_meta_end', 'display_additional_note_in_order_details', 10, 4);

function display_additional_note_in_order_details($item_id, $item, $order, $plain_text = false) {
    $additional_note = $item->get_meta('_additional_note', true);

    if (!empty($additional_note)) {
        echo '<br><strong>' . __('Request Quote:', 'woocommerce') . '</strong> ' . wp_kses_post($additional_note);
    }
     echo '<br><strong>' . __('Hello world ', 'woocommerce') . '</strong> ';
}
