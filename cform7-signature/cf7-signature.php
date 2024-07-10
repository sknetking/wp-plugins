<?php
/**
 * @wordpress-plugin
 * Plugin Name:       CForm7-Signature(SK)
 * Plugin URI:        https://sknetking.online/CForm7-Signature.html
 * Description:       Use this shortcode for show contact form on front end -<pre> [signature] </pre>
 * Version:           2.0
 * Requires at least: 6.2
 * Requires PHP:      7.2
 * Author:            sknetking
 * Author URI:        https://sknetking.online
 * Text Domain:       CForm7_Signature
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 *  Requires Plugins: Contact Form 7
 */


// Step 1: Register the Custom Form Tag
add_action('wpcf7_init', 'custom_add_form_tag_signature');

function custom_add_form_tag_signature() {
    wpcf7_add_form_tag('signature', 'custom_signature_form_tag_handler'); // "signature" is the type of the form-tag
}

function custom_signature_form_tag_handler($tag) {
    $html = '<div class="settings">
        <label for="bgColor">Bg Color:</label>
        <input type="color" id="bgColor" value="#ffffff">
        <label for="lineWidth">Line Width:</label>
        <input type="number" id="lineWidth" value="2" min="1" max="10">
        <label for="lineColor">Color:</label>
        <input type="color" id="lineColor" value="#000000">
    </div>
    <div id="canvas-container">
        <canvas id="canvas"></canvas>
    </div>
    <input type="hidden" name="signature" id="signature" value="">
    <div class="buttons">
        <button id="undo">Undo</button>
        <button id="redo">Redo</button>
      <button id="clear">Clear</button>
    </div>';
    return $html;
}

// Step 2: Enqueue the Custom Script
function wpcf7_enqueue_signature_script() {
    wp_enqueue_script('contact-form-script',plugins_url().'/cform7-signature/signature-pad.js', array('jquery'), null, true);
    wp_enqueue_style('style',plugins_url().'/cform7-signature/style.css');
}
add_action('wp_enqueue_scripts', 'wpcf7_enqueue_signature_script');

// Step 4: Customize the Email Content with a Filter
add_filter('wpcf7_mail_tag_replaced', 'custom_wpcf7_mail_tag_replaced', 10, 4);

function custom_wpcf7_mail_tag_replaced($replaced, $submitted, $html, $mail_tag) {
    if ('signature' == $mail_tag->field_name()) {
        if (!empty($submitted)) {
            $replaced = '<img src="' . $submitted . '" alt="Signature" style="border: 1px solid #000; max-width: 100%; height: auto;">';
        }
    }

    return $replaced;
}
