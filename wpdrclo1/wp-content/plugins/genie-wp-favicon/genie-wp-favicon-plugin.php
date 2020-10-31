<?php

/*
Plugin Name: Genie WP Favicon
Plugin URI: http://www.itechgenie.com/myblog/genie-wp-favicon
Description: The Genie WP Favicon will be helpful in adding a favicon to any Wordpress site with ease. 
Version: 0.5.2
Author: prakashm88
Author URI: http://www.itechgenie.com
License: GPLv2 or later
*/

/*  Copyright 2012-2015  prakashm88

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

// Loading only for admin users
/*
 if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}
*/
if ( ! defined( 'DS' ) )
	define('DS', DIRECTORY_SEPARATOR);
if ( ! defined( 'URL_S' ) )
	define('URL_S', '/') ;
define('GWPF_PLUGIN_NAME', basename( dirname( __FILE__ )) );

// Pre-2.6 compatibility
if ( ! defined( 'WP_CONTENT_URL' ) )
      define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . URL_S . 'wp-content' );

if (!defined('GWPF_CONTENT_URL'))
	define('GWPF_CONTENT_URL', WP_PLUGIN_URL . URL_S . GWPF_PLUGIN_NAME);
if (!defined('GWPF_ROOT'))
	define('GWPF_ROOT', dirname (__FILE__));

define('GWPF_FAVICON_DIR', WP_CONTENT_DIR . URL_S . 'uploads' . URL_S . 'gwpf_icon');
define('GWPF_FAVICON_URL', WP_CONTENT_URL . URL_S . 'uploads' . URL_S . 'gwpf_icon');

load_plugin_textdomain( 'genie-wp-favicon', false, basename( dirname( __FILE__ ) ) . '/i18n' );

global $genieWPFaviconController;
global $gwpf_setup_model;

require_once (GWPF_ROOT . DS . 'GenieWPFaviconController.php');
require_once (GWPF_ROOT . DS . 'GwpfSetupModel.php');

if (class_exists('GenieWPFaviconController') && !$genieWPFaviconController) {
	$genieWPFaviconController = new GenieWPFaviconController();
}

if(class_exists('GWPFSetupModel') && !$gwpf_setup_model) {
	$gwpf_setup_model = new GwpfSetupModel() ;
}

register_activation_hook(__FILE__, 'activate_GWPF_plugin');
register_deactivation_hook(__FILE__, 'deactivate_GWPF_plugin');

function activate_GWPF_plugin() {
	/*   */
}

function deactivate_GWPF_plugin() {
	global $gwpf_setup_model;
	if(class_exists('GwpfSetupModel') && !$gwpf_setup_model) {
		$gwpf_setup_model = new GWPFSetupModel() ;
	}
	$gwpf_setup_model->removeGWPFDetails();
}

//ini_set('display_errors', '1');
//ini_set('log_errors', 'On');
//error_reporting(E_ALL);

?>