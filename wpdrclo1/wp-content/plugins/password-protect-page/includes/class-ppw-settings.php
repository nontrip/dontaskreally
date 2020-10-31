<?php

if ( ! class_exists( "PPW_Settings" ) ) {
	class PPW_Settings {
		/**
		 * Render UI settings page
		 */
		public function render_ui() {
			$plugin_info = apply_filters( PPW_Constants::HOOK_PLUGIN_INFO, array(
				'name'    => 'Password Protect WordPress',
				'version' => PPW_VERSION
			) );
			?>
			<div class="wrap">
				<div id="icon-themes" class="icon32"></div>
				<h2>
					<?php _e( $plugin_info['name'], 'wp-protect-password-setting' ); ?>
					<span class="ppwp_version"><?php _e( $plugin_info['version'] ) ?></span>
				</h2>
				<?php
				$default_tab  = apply_filters( PPW_Constants::HOOK_DEFAULT_TAB, 'general' );
				$activate_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $default_tab;
				$this->render_tabs( $activate_tab );
				$this->render_content( $activate_tab );
				?>
			</div>
			<?php
		}

		/**
		 * Render tab for settings page
		 *
		 * @param string $active_tab Active tab.
		 */
		private function render_tabs( $active_tab ) {
			$tabs = apply_filters(
				PPW_Constants::HOOK_ADD_NEW_TAB,
				array(
					array(
						'tab'      => 'general',
						'tab_name' => 'General',
					),
					array(
						'tab'      => 'misc',
						'tab_name' => 'Advanced',
					),
					array(
						'tab'      => 'entire_site',
						'tab_name' => 'Sitewide',
					),
					array(
						'tab'      => 'shortcodes',
						'tab_name' => 'Shortcodes',
					),
					array(
						'tab'      => 'master_passwords',
						'tab_name' => 'Master Passwords',
					),
					array(
						'tab'      => 'troubleshooting',
						'tab_name' => 'Troubleshooting',
					)
				)
			);
			?>
			<h2 class="ppwp_wrap_tab_title nav-tab-wrapper">
				<?php
				if ( ! is_array( $tabs ) ) {
					return;
				}

				foreach ( $tabs as $tab ) {
					if ( ! is_array( $tab ) ) {
						continue;
					}

					if ( empty( $tab['tab'] ) || empty( $tab['tab_name'] ) ) {
						continue;
					}
					?>
					<a href="?page=<?php echo esc_html( PPW_Constants::MENU_NAME ); ?>&tab=<?php echo esc_attr( $tab['tab'] ); ?>"
					   class="nav-tab <?php echo $active_tab === $tab['tab'] ? 'nav-tab-active' : ''; ?>"><?php _e( $tab['tab_name'], PPW_Constants::DOMAIN ); ?></a>
				<?php } ?>
			</h2>
			<?php
		}

		/**
		 * Render content for settings page
		 *
		 * @param string $active_tab Active tab.
		 */
		private function render_content( $active_tab ) {
			$tabs = apply_filters( PPW_Constants::HOOK_CUSTOM_TAB, array( 'general', 'misc', 'entire_site', 'shortcodes', 'master_passwords', 'troubleshooting' ) );

			foreach ( $tabs as $tab ) {
				if ( $active_tab === $tab ) {
					do_action( PPW_Constants::HOOK_RENDER_CONTENT_FOR_TAB . $tab );
					break;
				}
			}
		}
	}
}
