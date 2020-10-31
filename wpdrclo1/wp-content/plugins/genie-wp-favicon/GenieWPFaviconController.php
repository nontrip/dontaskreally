<?php

/*
 * Created on Jan 5, 2013
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class GenieWPFaviconController {
    function __construct() {
		add_action('init', array (
			& $this,
			'gwpf_init'
		));
		add_action('admin_menu', array (
			& $this,
			'gwpf_admin_action'
		));
		add_action('wp_head', array (
			& $this,
			'gwpf_wp_head'
		));
		add_action('admin_head', array (
			& $this,
			'gwpf_admin_head'
		));
	}
	function gwpf_init() {
		
	}
	function gwpf_admin_action() {
		if (current_user_can('edit_users')) {
			add_submenu_page('themes.php', 'Add Favicon', 'Genie WP Favicon', 'activate_plugins', 'gpwmf', array (
				& $this,
				'gwpf_admin_page'
			));
		}
	}
	function gwpf_admin_page() {
		include (GWPF_ROOT . DS . 'gwpf_admin_page.php');
	}
	function gwpf_wp_head() {
		$faviconName = stripslashes(get_option('gwpf_favicon'));
		$apple_faviconName = stripslashes(get_option('gwpf_favicon_preserve_on_apple'));
		$showWebId = stripslashes(get_option('gwpf_show_webid'));
		echo "<!-- Start Genie WP Favicon -->\n" ;
		if (isset($faviconName) && $faviconName != null) {
			echo '<link rel="shortcut icon" href="' . GWPF_FAVICON_URL . URL_S . $faviconName . '" />' . PHP_EOL  ;
			if(isset($apple_faviconName) && $apple_faviconName != null && $apple_faviconName != "")
				echo '<link rel="apple-touch-icon-precomposed" href="' . GWPF_FAVICON_URL . URL_S . $apple_faviconName . '" />' . PHP_EOL  ;
			else 
				echo '<link rel="apple-touch-icon" href="' . GWPF_FAVICON_URL . URL_S . $faviconName . '" />' . PHP_EOL ;
			if(isset($showWebId) && $showWebId != null && $showWebId != "") {
				echo '<!-- Plugin provided by ITechgenie.com - http://itechgenie.com/myblog/genie-wp-favicon/  -->' . PHP_EOL ;
			}
		} else {
			echo "<!-- Favicon not updated - Raise support ticket @ http://wordpress.org/support/plugin/genie-wp-favicon -->\n";
		}
		echo "<!-- End Genie WP Favicon -->\n";
	}
	function gwpf_admin_head() {
		$faviconName = stripslashes(get_option('gwpf_favicon'));
		$apple_faviconName = stripslashes(get_option('gwpf_favicon_preserve_on_apple'));
		echo "<!-- Start Genie WP Favicon admin -->\n" ;
		if (isset($faviconName) && $faviconName != null) {
			echo '<link rel="shortcut icon" href="' . GWPF_FAVICON_URL . URL_S . $faviconName . '" />'. PHP_EOL ;
			if(isset($apple_faviconName) && $apple_faviconName != null && $apple_faviconName != "")
				echo '<link rel="apple-touch-icon-precomposed" href="' . GWPF_FAVICON_URL . URL_S . $apple_faviconName . '" />' . PHP_EOL  ;
			else 
				echo '<link rel="apple-touch-icon" href="' . GWPF_FAVICON_URL . URL_S . $faviconName . '" />' . PHP_EOL ;
		} else {
			echo "<!-- Favicon not updated-->\n";
		}
		echo "<!-- End Genie WP Favicon admin -->\n";
	}
}