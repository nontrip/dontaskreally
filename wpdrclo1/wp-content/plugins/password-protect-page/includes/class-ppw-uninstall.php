<?php

if ( ! class_exists( 'PPW_Uninstall' ) ) {
	class PPW_Uninstall {
		/**
		 * Uninstall plugin
		 */
		public static function uninstall() {
			self::handle_uninstall_plugin();
		}

		/**
		 * Handle uninstall plugin
		 */
		private static function handle_uninstall_plugin() {
			if ( is_multisite() ) {
				foreach ( get_sites() as $site ) {
					$blog_id = $site->blog_id;
					if ( ppw_core_get_setting_type_bool( PPW_Constants::REMOVE_DATA, $blog_id ) ) {
						global $wpdb;
						self::delete_general_option( $blog_id );
						self::delete_entire_site_option( $blog_id );
						$wp_prefix = $wpdb->get_blog_prefix( $blog_id );
						ppw_core_delete_data_in_post_meta_by_meta_key( PPW_Constants::POST_PROTECTION_ROLES, $wp_prefix );
						ppw_core_delete_data_in_post_meta_by_meta_key( PPW_Constants::GLOBAL_PASSWORDS, $wp_prefix );
					}
				}
			} else {
				if ( ppw_core_get_setting_type_bool( PPW_Constants::REMOVE_DATA ) ) {
					self::delete_general_option();
					self::delete_entire_site_option();
					ppw_core_delete_data_in_post_meta_by_meta_key( PPW_Constants::POST_PROTECTION_ROLES );
					ppw_core_delete_data_in_post_meta_by_meta_key( PPW_Constants::GLOBAL_PASSWORDS );
				}
			}
		}

		/**
		 * Handle delete general option
		 *
		 * @param $site_id
		 */
		private static function delete_general_option( $site_id = false ) {
			$settings = ! $site_id ? get_option( PPW_Constants::GENERAL_OPTIONS ) : get_blog_option( $site_id, PPW_Constants::GENERAL_OPTIONS );
			if ( ! $settings ) {
				return;
			}

			$options = json_decode( $settings );
			if ( ! is_object( $options ) ) {
				return;
			}

			$new_options = (array) $options;
			unset( $new_options[ PPW_Constants::COOKIE_EXPIRED ] );
			unset( $new_options[ PPW_Constants::REMOVE_DATA ] );
			if ( ! $site_id ) {
				update_option( PPW_Constants::GENERAL_OPTIONS, wp_json_encode( $new_options ) );
			} else {
				update_blog_option( $site_id, PPW_Constants::GENERAL_OPTIONS, wp_json_encode( $new_options ) );
			}
		}

		/**
		 * Handle delete entire site option
		 *
		 * @param $site_id
		 */
		private static function delete_entire_site_option( $site_id = false ) {
			$options = ! $site_id ? get_option( PPW_Constants::ENTIRE_SITE_OPTIONS ) : get_blog_option( $site_id, PPW_Constants::ENTIRE_SITE_OPTIONS );
			if ( ! $options ) {
				return;
			}

			if ( ! is_array( $options ) ) {
				return;
			}

			unset( $options[ PPW_Constants::IS_PROTECT_ENTIRE_SITE ] );
			unset( $options[ PPW_Constants::PASSWORD_ENTIRE_SITE ] );
			if ( ! $site_id ) {
				update_option( PPW_Constants::ENTIRE_SITE_OPTIONS, $options );
			} else {
				update_blog_option( $site_id, PPW_Constants::ENTIRE_SITE_OPTIONS, $options );
			}
		}
	}
}
