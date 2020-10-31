<?php

if (!defined('ABSPATH')) exit;


// register styles and scripts
function instashow_lite_lib() {
	wp_register_script('instashow-lite', plugins_url('assets/instashow-lite/dist/jquery.instashow-lite.packaged.js', INSTASHOW_LITE_FILE), array(), INSTASHOW_LITE_VERSION);
	wp_print_scripts('instashow-lite');
}
add_action('wp_footer', 'instashow_lite_lib');

?>
