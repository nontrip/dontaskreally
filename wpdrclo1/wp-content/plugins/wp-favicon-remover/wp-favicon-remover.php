<?php
/*
Plugin Name: WP Favicon Remover
Plugin URI: https://wpgogo.com/plugin/wp_favicon_remover.html
Description: This plugin adds the functionality to remove the WordPress default favicon since WordPress 5.4.
Author: Hiroaki Miyashita
Version: 1.0.2
Author URI: https://wpgogo.com/
Text Domain: wp-favicon-remover
Domain Path: /
*/

/*  Copyright 2020 Hiroaki Miyashita

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
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

add_action( 'do_faviconico', 'wp_favicon_remover');
function wp_favicon_remover() {
	exit;
}
?>