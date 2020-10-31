<?php

use Elementor\Plugin;

if ( ! class_exists( 'PPW_Shortcode_Advance_Widget' ) ) {
	/**
	 * Advance Elementor ShortCode Widget
	 *
	 * @since 1.4.5
	 *
	 * Class PPW_Shortcode_Advance_Widget
	 */
	class PPW_Shortcode_Advance_Widget {
		/**
		 * Register hooks.
		 */
		public function init() {
			add_filter( PPW_Constants::HOOK_SHORTCODE_ELEMENTOR_CONTENT, array( $this, 'handle_elementor_show_content_option' ), 10, 2 );
		}

		/**
		 * Handle elementor content with the new attribute "Show Content"
		 *
		 * @param string $content The current content.
		 * @param array  $settings The setting array includes:
		 *                 string  ppwp_protected_content.
		 *
		 * @return string
		 */
		public function handle_elementor_show_content_option( $content, $settings ) {
			if ( ! $this->is_show_content_enabled( $settings ) ) {
				return $content;
			}

			return apply_filters( PPW_Constants::HOOK_SHORTCODE_ELEMENTOR_PREVIEW_CONTENT, $settings['ppwp_protected_content'], $content, $settings );
		}

		/**
		 * Check whether the ppwp_show_content option is enabled.
		 *
		 * @param array $settings The Elementor widget settings (refer to handle_elementor_show_content_option function).
		 *
		 * @return bool True if the value equals to 'yes'.
		 */
		private function is_show_content_enabled( $settings ) {
			if ( ! Plugin::$instance->editor->is_edit_mode() ) {
				return false;
			}
			return isset( $settings['ppwp_show_content'] ) && 'yes' === $settings['ppwp_show_content'];
		}
	}
}
