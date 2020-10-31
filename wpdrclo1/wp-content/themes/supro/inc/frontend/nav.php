<?php
/**
 * Hooks for template nav menus
 *
 * @package Supro
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since 1.0
 * @param array $args Configuration arguments.
 * @return array
 */
function supro_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'supro_page_menu_args' );


