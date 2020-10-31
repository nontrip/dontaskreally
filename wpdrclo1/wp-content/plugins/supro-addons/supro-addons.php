<?php
/**
 * Plugin Name: Supro Addons
 * Plugin URI: http://drfuri.com/plugins/supro-addons.zip
 * Description: Extra elements for Visual Composer. It was built for Supro theme.
 * Version: 1.1.0
 * Author: Drfuri
 * Author URI: http://drfuri.com/
 * License: GPL2+
 * Text Domain: supro
 * Domain Path: /lang/
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! defined( 'SUPRO_ADDONS_DIR' ) ) {
	define( 'SUPRO_ADDONS_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'SUPRO_ADDONS_URL' ) ) {
	define( 'SUPRO_ADDONS_URL', plugin_dir_url( __FILE__ ) );
}

require_once SUPRO_ADDONS_DIR . '/inc/visual-composer.php';
require_once SUPRO_ADDONS_DIR . '/inc/shortcodes.php';
require_once SUPRO_ADDONS_DIR . '/inc/portfolio.php';
require_once SUPRO_ADDONS_DIR . '/inc/socials.php';
require_once SUPRO_ADDONS_DIR . '/inc/widgets/widgets.php';

if ( is_admin() ) {
	require_once SUPRO_ADDONS_DIR . '/inc/importer.php';
}

/**
 * Init
 */
function supro_vc_addons_init() {
	load_plugin_textdomain( 'supro', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

	new Supro_VC;
	new Supro_Shortcodes;
	new Supro_Portfolio;
}

add_action( 'after_setup_theme', 'supro_vc_addons_init', 20 );
