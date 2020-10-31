<?php

use W3TC\Dispatcher;

if ( ! class_exists( 'PPW_Cache_Services' ) ) {
	/**
	 * Class PPW_Free_Handle_Cache
	 */
	class PPW_Cache_Services {

		public function __construct() {
			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
		}

		/**
		 * Handle cache for page/post have password type is role with Super Cache plugin
		 *
		 * @param $new_role_password
		 * @param $id
		 * @param $current_roles_password
		 * @param $current_global_passwords
		 * @deprecated
		 */
		function handle_cache_for_password_type_role_with_super_cache( $new_role_password, $id, $current_roles_password, $current_global_passwords ) {
			if ( ! is_plugin_active( 'wp-super-cache/wp-cache.php' ) ) {
				return;
			}

			if ( ! empty( $new_role_password ) ) {
				$uri = $this->get_uri_by_post_id( $id );
				global $cache_rejected_uri;
				if ( ! in_array( '/' . $uri, $cache_rejected_uri ) ) {
					$this->add_page_post_to_list_not_cache_for_super_cache( $id, $uri, $cache_rejected_uri );
				}
			} else {
				if ( ! empty( $current_global_passwords ) ) {
					return;
				}

				$result = array_values( $current_roles_password );
				$result = array_filter( $result, 'strlen' );
				if ( empty( $result ) ) {
					$this->remove_page_post_to_list_not_cache_for_super_cache( $id );
				}
			}
		}

		/**
		 * Handle cache for page/post have password type is global with Super Cache plugin
		 *
		 * @param $new_global_passwords
		 * @param $id
		 * @param $current_roles_password
		 * @deprecated
		 */
		function handle_cache_for_password_type_global_with_super_cache( $new_global_passwords, $id, $current_roles_password ) {
			if ( ! is_plugin_active( 'wp-super-cache/wp-cache.php' ) ) {
				return;
			}

			if ( ! empty( $new_global_passwords ) ) {
				$uri = $this->get_uri_by_post_id( $id );
				global $cache_rejected_uri;
				if ( ! in_array( '/' . $uri, $cache_rejected_uri ) ) {
					$this->add_page_post_to_list_not_cache_for_super_cache( $id, $uri, $cache_rejected_uri );
				}
			} else {
				$result = array_values( $current_roles_password );
				$result = array_filter( $result, 'strlen' );
				if ( empty( $result ) ) {
					$this->remove_page_post_to_list_not_cache_for_super_cache( $id );
				}
			}
		}

		/**
		 * Add page or post to list not cache of WP Super Cache plugin
		 *
		 * @param $post_id
		 * @param $uri
		 * @param $cache_rejected_uri
		 * @deprecated
		 */
		function add_page_post_to_list_not_cache_for_super_cache( $post_id, $uri, $cache_rejected_uri ) {
			// 1. Add URI to list not cache
			global $wp_cache_config_file;
			array_push( $cache_rejected_uri, '/' . $uri );
			$all_uri = array_map( function ( $key, $cache ) {
				return " $key => '$cache'";
			}, array_keys( $cache_rejected_uri ), $cache_rejected_uri );
			$all_uri = implode( ',', $all_uri );
			$text    = "array($all_uri )";
			wp_cache_replace_line( '^ *\$cache_rejected_uri', "\$cache_rejected_uri = $text;", $wp_cache_config_file );

			// 3. Clear cache by post_id
			wp_cache_post_edit( $post_id );
		}

		/**
		 * Remove page or post in list not cache of WP Super Cache plugin
		 *
		 * @param $post_id
		 * @deprecated
		 */
		function remove_page_post_to_list_not_cache_for_super_cache( $post_id ) {
			// 1. Get URI in permalink by post_id
			$uri = $this->get_uri_by_post_id( $post_id );
			$uri = '/' . $uri;

			// 2. Remove URI in list not cache
			global $cache_rejected_uri, $wp_cache_config_file;
			$cache_rejected_uri = array_unique( $cache_rejected_uri );
			if ( ( $key = array_search( $uri, $cache_rejected_uri ) ) !== false ) {
				unset( $cache_rejected_uri[ $key ] );
				$all_uri = array_map( function ( $key, $cache ) {
					return " $key => '$cache'";
				}, array_keys( $cache_rejected_uri ), $cache_rejected_uri );
				$all_uri = implode( ',', $all_uri );
				$text    = "array($all_uri )";
				wp_cache_replace_line( '^ *\$cache_rejected_uri', "\$cache_rejected_uri = $text;", $wp_cache_config_file );
			}
		}

		/**
		 * get URI by post_id
		 *
		 * @param $post_id
		 *
		 * @return string
		 * @deprecated
		 */
		function get_uri_by_post_id( $post_id ) {
			$permalink = get_permalink( $post_id );
			$parse_url = parse_url( $permalink );
			$uri       = $parse_url['path'];
			$uri       = explode( '/', $uri );
			$uri       = ! empty( $uri[ count( $uri ) - 1 ] ) ? $uri[ count( $uri ) - 1 ] : $uri[ count( $uri ) - 2 ];

			return $uri;
		}

		/**
		 * Clear cache all page
		 * @deprecated
		 */
		function clear_cache_super_cache() {
			if ( ! is_plugin_active( 'wp-super-cache/wp-cache.php' ) ) {
				return;
			}

			if ( is_multisite() ) {
				wp_cache_clear_cache( get_current_blog_id() );
			} else {
				wp_cache_clear_cache();
			}
		}

		/**
		 * Check W3 Total Cache has config cookie group
		 *
		 * @return bool
		 */
		function check_has_config_w3_total_cache() {
			$config = Dispatcher::config();
			$groups = $config->get_array( 'pgcache.cookiegroups.groups' );
			if ( ! array_key_exists( 'password_protect_wordpress', $groups ) ) {
				return false;
			}

			if ( ! $groups['password_protect_wordpress']['enabled'] || $groups['password_protect_wordpress']['cache'] ) {
				return false;
			}

			$cookies = $groups['password_protect_wordpress']['cookies'];

			return in_array( 'pda_protect_password', $cookies ) && in_array( 'wp-postpass-role_*', $cookies );
		}

		/**
		 * Update cookie to option and modify htaccess file for WP Fastest Cache plugin
		 * @deprecated
		 */
		function custom_option_exclude_page_and_cookie_fastest_cache() {
			if ( ! is_plugin_active( 'wp-fastest-cache/wpFastestCache.php' ) ) {
				return;
			}

			$old_fastest   = get_option( "WpFastestCacheExclude" );
			$fastest_cache = new WpFastestCache();
			if ( $old_fastest ) {
				$old_fastest = json_decode( $old_fastest );
				$tmp1        = false;
				$tmp2_free   = false;
				foreach ( $old_fastest as $fast ) {
					if ( 'cookie' === $fast->type && 'contain' === $fast->prefix && 'pda_protect_password' === $fast->content ) {
						$tmp1 = true;
					}
					if ( 'cookie' === $fast->type && 'contain' === $fast->prefix && 'wp-postpass-role_' === $fast->content ) {
						$tmp2_free = true;
					}
				}
				if ( ! $tmp1 ) {
					array_push( $old_fastest, (object) array(
						'prefix'  => 'contain',
						'content' => 'pda_protect_password',
						'type'    => 'cookie'
					) );
				}
				if ( ! $tmp2_free ) {
					array_push( $old_fastest, (object) array(
						'prefix'  => 'contain',
						'content' => 'wp-goldpass-pagepost_',
						'type'    => 'cookie'
					) );
				}

				update_option( "WpFastestCacheExclude", json_encode( $old_fastest ) );
				$fastest_cache->modify_htaccess_for_exclude();
			} else {
				$ppwp_cookie = array(
					(object) array(
						'prefix'  => 'contain',
						'content' => 'pda_protect_password',
						'type'    => 'cookie'
					)
				);

				array_push( $ppwp_cookie, (object) array(
					'prefix'  => 'contain',
					'content' => 'wp-postpass-role_',
					'type'    => 'cookie'
				) );

				add_option( "WpFastestCacheExclude", json_encode( $ppwp_cookie ), null, "yes" );
				$fastest_cache->modify_htaccess_for_exclude();
			}
		}
	}
}
