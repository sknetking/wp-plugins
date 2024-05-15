<?php
function my_custom_settings_init() {
    $options = array(
        array(
            'section' => 'section_one',
            'title' => 'Section One',
            'fields' => array(
                array(
                    'id' => 'option_one_text',
                    'label' => 'Author Name',
                    'type' => 'text'
                ),
                array(
                    'id' => 'option_one_number',
                    'label' => 'Option Two (Number)',
                    'type' => 'number'
                ),
                array(
                    'id' => 'option_one_dropdown',
                    'label' => 'MetaBox Show in Post!',
                    'type' => 'dropdown',
                    'options' => array(
                        'post' => 'Post Only',
                        'page' => 'Page Only',
                        'post, page' => 'Post, Pages'
                    )
                )
            )
        ));

    foreach ($options as $option) {
        add_settings_section($option['section'], $option['title'], function () use ($option) {
            echo '<p>This is ' . $option['title'] . ' description.</p>';
        }, 'my_custom_options_page');

        foreach ($option['fields'] as $field) {
            add_settings_field($field['id'], $field['label'], function () use ($field) {
                $value = get_option($field['id']);
                if ($field['type'] === 'text' || $field['type'] === 'number') {
                    echo '<input type="' . $field['type'] . '" name="' . $field['id'] . '" value="' . esc_attr($value) . '" />';
                } elseif ($field['type'] === 'dropdown' && isset($field['options']) && is_array($field['options'])) {
                    echo '<select name="' . $field['id'] . '">';
                    foreach ($field['options'] as $option_value => $option_label) {
                        echo '<option value="' . esc_attr($option_value) . '" ' . selected($value, $option_value, false) . '>' . esc_html($option_label) . '</option>';
                    }
                    echo '</select>';
                }
            }, 'my_custom_options_page', $option['section']);

            register_setting('my_custom_options_group', $field['id']);
        }
    }
}

add_action('admin_init', 'my_custom_settings_init');

function my_custom_options_page_content() {
    ?>
    <div class="wrap">
        <h2>Custom Options</h2>
        <form method="post" action="options.php">
            <?php settings_fields('my_custom_options_group'); ?>
            <?php do_settings_sections('my_custom_options_page'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function my_custom_sanitize_callback($input) {
    return $input; // You can add sanitization logic here if needed
}

register_setting('my_custom_options_group', $field['id'], 'my_custom_sanitize_callback');


function my_custom_options_page() {
    add_options_page( 'Custom Options', 'Custom Options', 'manage_options', 'custom-options', 'my_custom_options_page_content' );
}
add_action( 'admin_menu', 'my_custom_options_page' );
