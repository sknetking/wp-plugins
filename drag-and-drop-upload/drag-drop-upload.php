<?php
/*
Plugin Name: Drag & Drop Upload
Description: Add drag and drop file upload functionality shortcode - [drag_drop_upload]
Version: 1.0
Author: Shyam
*/

function enwuee_script_css(){
	   wp_enqueue_script('drag-drop-upload', plugin_dir_url(__FILE__) . 'drag-drop-upload.js', ['jquery'], '1.0', true);
    wp_enqueue_style('drag-drop-upload', plugin_dir_url(__FILE__) . 'drag-drop-upload.css');
    
    // Localize script with AJAX URL and nonce
    wp_localize_script('drag-drop-upload', 'ddu_vars', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('drag_drop_upload_nonce')
    ]);
}
add_action('init','enwuee_script_css');
// Register shortcode
add_shortcode('drag_drop_upload', 'drag_drop_upload_shortcode');

function drag_drop_upload_shortcode() {
    // Enqueue scripts and styles
    $user_id = get_current_user_id();
	$uploaded = get_user_meta($user_id, 'ddu_uploaded_file', true)??'';
    // Return HTML
    return '
    <div class="ddu-upload-container">
        <div class="ddu-dropzone">
            <div class="ddu-dropzone-content">
                <svg class="ddu-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                <p>Drag & drop files here or click to select</p>
            </div>
            <input type="file" class="ddu-file-input" style="display:none;">
        </div>
        <div class="ddu-progress" style="display:none;">
            <div class="ddu-progress-bar"></div>
        </div>
        <div class="ddu-results">
		Your uploaded media <img src="'.$uploaded['url'].'">
		</div>
    </div>';
}

// Handle AJAX upload
add_action('wp_ajax_drag_drop_upload', 'handle_drag_drop_upload');
add_action('wp_ajax_nopriv_drag_drop_upload', 'handle_drag_drop_upload');

function handle_drag_drop_upload() {
    // Verify nonce
    check_ajax_referer('drag_drop_upload_nonce', 'nonce');

    if (!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }

    $uploadedfile = $_FILES['file'];
    $upload_overrides = ['test_form' => false];
    $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

    if ($movefile && !isset($movefile['error'])) {
        $file_path = $movefile['file'];
        $file_name = basename($file_path);
        $file_type = wp_check_filetype($file_name, null);

        $attachment = [
            'post_mime_type' => $file_type['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', $file_name),
            'post_content' => '',
            'post_status' => 'inherit'
        ];

        $attachment_id = wp_insert_attachment($attachment, $file_path);

        if (!is_wp_error($attachment_id)) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attachment_data = wp_generate_attachment_metadata($attachment_id, $file_path);
            wp_update_attachment_metadata($attachment_id, $attachment_data);

            $current_user_id = get_current_user_id();

            if ($current_user_id) {
                // ✅ Option 1: Save as one meta (array)
                update_user_meta($current_user_id, 'ddu_uploaded_file', [
                    'id'  => $attachment_id,
                    'url' => $movefile['url'],
                ]);

                // ✅ Option 2: (optional) Save individually
                update_user_meta($current_user_id, 'ddu_file_id', $attachment_id);
                update_user_meta($current_user_id, 'ddu_file_url', $movefile['url']);
            }

            wp_send_json_success([
                'id' => $attachment_id,
                'url' => $movefile['url'],
                'thumbnail' => wp_get_attachment_image_url($attachment_id, 'thumbnail')
            ]);
        }
    }

    wp_send_json_error($movefile['error'] ?? 'Upload failed');
}