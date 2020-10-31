<?php
/**
* Custom functions for the maintenance mode.
*
* @package Konte
*/


/**
 * Redirect to the target page if the maintenance mode is enabled.
 */
function supro_maintenance_redirect() {
	if ( ! supro_get_option( 'maintenance_enable' ) ) {
		return;
	}

	if ( current_user_can( 'super admin' ) || current_user_can( 'administrator' ) ) {
		return;
	}

	$mode     = supro_get_option( 'maintenance_mode' );
	$page_id  = supro_get_option( 'maintenance_page' );
	$code     = 'maintenance' == $mode ? 503 : 200;
	$page_url = $page_id ? get_page_link( $page_id ):  '';

	// Use default message.
	if ( ! $page_id || ! $page_url ) {
		if ( 'coming_soon' == $mode ) {
			$message = sprintf( '<h1>%s</h1><p>%s</p>', esc_html__( 'Coming Soon', 'supro' ), esc_html__( 'Our website is under construction. We will be here soon with our new awesome site.', 'supro' ) );
		} else {
			$message = sprintf( '<h1>%s</h1><p>%s</p>', esc_html__( 'Website Under Maintenance', 'supro' ), esc_html__( 'Our website is currently undergoing scheduled maintenance. Please check back soon.', 'supro' ) );
		}

		wp_die( $message, get_bloginfo( 'name' ), array( 'response' => $code ) );
	}

	// Add body classes.
	add_filter( 'body_class', 'supro_maintenance_page_body_class' );

	// Redirect to the correct page.
	if ( ! is_page( $page_id ) ) {
		wp_redirect( $page_url );
		exit;
	} else {
		if ( ! headers_sent() ) {
			status_header( $code );
		}

		remove_action( 'supro_before_header', 'supro_topbar' );
		remove_action( 'supro_header', 'supro_header' );
		remove_action( 'supro_before_content_wrapper', 'supro_single_page_header' );

		if ( ! is_page_template() ) {
			add_filter( 'supro_inline_style', 'supro_maintenance_page_background' );
			add_action( 'supro_before_header', 'supro_maintenance_page_header', 1 );
		}
	}
}

add_action( 'template_redirect', 'supro_maintenance_redirect', 1 );

/**
 * Add classes for maintenance mode.
 *
 * @param array $classes
 * @return array
 */
function supro_maintenance_page_body_class( $classes ) {
	if ( ! supro_get_option( 'maintenance_enable' ) ) {
		return $classes;
	}

	if ( current_user_can( 'super admin' ) ) {
		return $classes;
	}

	$classes[] = 'maintenance-mode';

	if ( supro_is_maintenance_page() ) {
		$classes[] = 'maintenance-page';
		$classes[] = 'maintenance-layout-fullscreen';
	}

	return $classes;
}

/**
 * Set the background image for the maintenance page layout Fullscreen.
 *
 * @param string $css
 * @return string
 */
function supro_maintenance_page_background( $css ) {
	if ( has_post_thumbnail() ) {
		$css .= '.maintenance-page {background-image: url( ' . esc_url( get_the_post_thumbnail_url( null, 'full' ) ) . ' )}';
	}

	return $css;
}

/**
 * Konte
 *
 * @return void
 */
function supro_maintenance_page_header() {
	?>

	<div class="site-header maintenance-header transparent text-<?php echo esc_attr( supro_get_option( 'maintenance_textcolor' ) ) ?>">
		<div class="container">
			<div class="header-items">
				<?php get_template_part( 'parts/logo' ); ?>
			</div>
		</div>
	</div>

	<?php
}