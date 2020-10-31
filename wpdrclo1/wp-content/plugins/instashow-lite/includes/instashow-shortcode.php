<?php

if (!defined('ABSPATH')) exit;


// shortcode [instashow]
function instashow_lite_shortcode($atts) {
	global $instashow_lite_defaults;
	
	foreach ($instashow_lite_defaults as $name => $value) {
		if (isset($atts[$name]) && is_bool($value)) {
			$atts[$name] = !empty($atts[$name]) && $atts[$name] != 'false';
		}
	}

	$options = shortcode_atts($defaults = $instashow_lite_defaults, $atts, 'instashow');

	$access_token = get_option('instashow_instagram_access_token', '');

	$result = '<div data-is';
	$result .= (!empty($access_token) ? ' data-is-access-token="' . esc_attr($access_token) . '"' : '');

	foreach ($options as $name => $value) {
		if ($value !== $instashow_lite_defaults[$name]) {

			// boolean
			if (is_bool($value)) {
				$value = $value ? 'true' : 'false';
			}

			$result .= sprintf(' data-is-%s="%s"', str_replace('_', '-', $name), esc_attr($value));
		}
	}
	$result .= '></div>';

	return $result;
}
add_shortcode('instashow', 'instashow_lite_shortcode');

?>