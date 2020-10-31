<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://passwordprotectwp.com?utm_source=user-website&utm_medium=pluginsite_link&utm_campaign=ppwp
 * @since             1.6.0
 * @package           Password_Protect_Page
 *
 * @wordpress-plugin
 * Plugin Name:       Password Protect WordPress Lite
 * Plugin URI:        https://passwordprotectwp.com?utm_source=user-website&utm_medium=pluginsite_link&utm_campaign=ppwp_lite
 * Description:       Password protect the entire WordPress site, unlimited pages and posts by user roles. This plugin is required for our Pro version to work properly.
 * Version:           1.6.0
 * Author:            BWPS
 * Author URI:        https://passwordprotectwp.com?utm_source=user-website&utm_medium=author_link&utm_campaign=ppwp_lite
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       password-protect-page
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.1.2 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PPW_VERSION', '1.6.0' );

if ( ! defined( 'PPW_DIR_PATH' ) ) {
	define( 'PPW_DIR_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'PPW_DIR_URL' ) ) {
	define( 'PPW_DIR_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'PPW_VIEW_URL' ) ) {
	define( 'PPW_VIEW_URL', plugin_dir_url( __FILE__ ) . 'includes/views/' );
}

if ( ! defined( 'PPW_PLUGIN_NAME' ) ) {
	define( 'PPW_PLUGIN_NAME', 'Password Protect WordPress Lite' );
}

if ( ! defined( 'PPW_PLUGIN_BASE_NAME' ) ) {
	define( 'PPW_PLUGIN_BASE_NAME', plugin_basename( __FILE__ ) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ppw-activator.php
 */
function activate_password_protect_page() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ppw-activator.php';
	PPW_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ppw-deactivator.php
 */
function deactivate_password_protect_page() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ppw-deactivator.php';
	PPW_Deactivator::deactivate();
}

/**
 * The code that runs when uninstall plugin.
 */
function uninstall_password_protect_page() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ppw-uninstall.php';
	PPW_Uninstall::uninstall();
}

register_activation_hook( __FILE__, 'activate_password_protect_page' );
register_deactivation_hook( __FILE__, 'deactivate_password_protect_page' );
register_uninstall_hook( __FILE__, 'uninstall_password_protect_page' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ppw.php';

/**
 * Begins execution of the plugin
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.1.2
 */
function run_password_protect_page() {
	$plugin = new Password_Protect_Page();
	$plugin->run();
}

do_action( 'ppw_free/loaded' );

if ( ! version_compare( PHP_VERSION, '5.6', '>=' ) ) {
	add_action( 'admin_notices', 'ppw_fail_php_version' );
} elseif ( ! version_compare( get_bloginfo( 'version' ), '4.7', '>=' ) ) {
	add_action( 'admin_notices', 'ppw_fail_wp_version' );
}

run_password_protect_page();


add_action( 'plugins_loaded', 'ppw_free_load_plugin' );

/**
 * Load migration service
 */
function ppw_free_load_plugin() {
	global $migration_free_service;
	$migration_free_service = new PPW_Default_PW_Manager_Services();
	global $password_recovery_service;
	$password_recovery_service = new PPW_Password_Recovery_Manager();
}

/**
 * Function to check when PHP version is not supported.
 */
function ppw_fail_php_version() {
	/* translators: %s: PHP version */
	$message      = sprintf( esc_html__( 'Password Protect WordPress requires PHP version %s+, plugin is currently NOT WORKING.', 'password-protect-page' ), '5.6' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

/**
 * Function to check when WP version is not supported.
 */
function ppw_fail_wp_version() {
	/* translators: %s: PHP version */
	$message      = sprintf( esc_html__( 'Password Protect WordPress requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT WORKING.', 'password-protect-page' ), '4.7' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

