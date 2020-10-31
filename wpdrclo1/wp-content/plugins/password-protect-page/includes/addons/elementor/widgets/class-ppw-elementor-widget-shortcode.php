<?php

if ( ! class_exists( 'PPW_Shortcode_Widget' ) ) {
	class PPW_Shortcode_Widget extends \Elementor\Widget_Base {

		/**
		 * Get element name.
		 *
		 * Retrieve the element name.
		 *
		 * @return string The name.
		 * @since  1.4.0
		 * @access public
		 */
		public function get_name() {
			return 'ppwp';
		}

		/**
		 * Get skin title.
		 *
		 * Retrieve the skin title.
		 *
		 * @since  1.0.0
		 * @access public
		 * @abstract
		 */
		public function get_title() {
			return __( 'Password Protection (PPWP)', 'password-protect-page' );
		}

		/**
		 * Get widget icon.
		 *
		 * Retrieve the widget icon.
		 *
		 * @return string Widget icon.
		 * @since  1.0.0
		 * @access public
		 */
		public function get_icon() {
			return 'fas fa-shield-alt';
		}

		/**
		 * Get widget categories.
		 *
		 * Retrieve the widget categories.
		 *
		 * @return array Widget categories.
		 * @since  1.0.10
		 * @access public
		 */
		public function get_categories() {
			return array( 'general' );
		}

		/**
		 * Add Controls to Widgets
		 */
		protected function _register_controls() {    //phpcs:ignore
			$is_gold_activate     = defined( 'PDA_GOLD_V3_VERSION' );
			$is_ppwp_pro_activate = is_pro_active_and_valid_license();
			$roles                = [];
			$raw_roles            = apply_filters(
				'ppw_supported_white_list_roles',
				array(
					'administrator',
					'editor',
					'author',
					'contributor',
					'subscriber',
				)
			);
			foreach ( $raw_roles as $value ) {
				$roles[ $value ] = $value;
			}
			$this->start_controls_section(
				'ppwp_section',
				array(
					'label' => __( 'PPWP Shortcode', 'password-protect-page' ),
				)
			);

			$controls = [
				[
					'key'   => 'ppwp_protect_options',
					'value' => [
						'label'     => __( 'Partial Content Protection', 'password-protect-page' ),
						'type'      => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					],
				],
				[
					'key'   => 'ppwp_passwords',
					'value' => [
						'label'       => __( 'Passwords', 'password-protect-page' ),
						'type'        => \Elementor\Controls_Manager::TEXT,
						'placeholder' => __( 'Enter your password, e.g. password1 password2', 'password-protect-page' ),
						'default'     => 'password1 password2',
						'description' => 'Multiple passwords are separated by space, case-sensitivity, no more than 100 characters and don’t contain [, ], “, ‘',
						'label_block' => true,
					],
				],
				[
					'key'   => 'ppwp_whitelisted_roles',
					'value' => [
						'label'       => __( 'Whitelisted Roles', 'password-protect-page' ),
						'type'        => \Elementor\Controls_Manager::SELECT2,
						'placeholder' => __( 'Select whitelisted roles', 'password-protect-page' ),
						'multiple'    => true,
						'options'     => $roles,
						'description' => 'Select user roles who can access protected area without having to enter passwords',
						'label_block' => true,
					],
				],
				[
					'key'   => 'ppwp_protected_content_headline',
					'value' => [
						'label'     => __( 'Protected Content', 'password-protect-page' ),
						'type'      => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					],
				],
				[
					'key'   => 'ppwp_protected_content',
					'value' => [
						'type'    => \Elementor\Controls_Manager::WYSIWYG,
						'default' => __( 'This is your protected content.', 'password-protect-page' ),
					],
				],
				[
					'key'   => 'ppwp_show_content',
					'value' => [
						'label'        => __( 'Show Content', 'password-protect-page' ),
						'type'         => \Elementor\Controls_Manager::SWITCHER,
						'default'      => 'no',
						'label_on'     => __( 'Show', 'essential-addons-elementor' ),
						'label_off'    => __( 'Hide', 'essential-addons-elementor' ),
						'return_value' => 'yes',
						'description'  => 'You can force show content in order to style them properly.',
					],
				],
				[
					'key'   => 'ppwp_protect_instruction',
					'value' => [
						'label'     => __( 'Password Form', 'password-protect-page' ),
						'type'      => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					],
				],
				[
					'key'   => 'ppwp_headline',
					'value' => [
						'label'       => __( 'Headline', 'password-protect-page' ),
						'type'        => \Elementor\Controls_Manager::TEXT,
						'default'     => 'Restricted Content',
						'label_block' => true,
					],
				],
				[
					'key'   => 'ppwp_placeholder',
					'value' => [
						'label'       => __( 'Placeholder', 'password-protect-page' ),
						'type'        => \Elementor\Controls_Manager::TEXT,
						'default'     => '',
						'label_block' => true,
					],
				],
				[
					'key'   => 'ppwp_button',
					'value' => [
						'label'       => __( 'Button', 'password-protect-page' ),
						'type'        => \Elementor\Controls_Manager::TEXT,
						'default'     => 'Enter',
						'label_block' => true,
					],
				],
				[
					'key'   => 'ppwp_description',
					'value' => [
						'label'       => __( 'Description', 'password-protect-page' ),
						'type'        => \Elementor\Controls_Manager::WYSIWYG,
						'default'     => 'To view this protected content, enter the password below:',
						'label_block' => true,
					],
				],
			];

			$controls = apply_filters( PPW_Constants::HOOK_SHORTCODE_ELEMENTOR_CONTROLS, $controls );


			foreach ( $controls as $control ) {
				if ( ! isset( $control['is_hide'] ) || true !== $control['is_hide'] ) {
					$this->add_control( $control['key'], $control['value'] );
				}
			}

			$this->end_controls_section();
		}

		/**
		 * Render content.
		 */
		protected function render() {
			$shortcode = do_shortcode( ( $this->generate_shortcode() ) );
			?>
			<div class="elementor-shortcode"><?php echo $shortcode; ?></div>
			<?php
		}

		/**
		 * Render shortcode widget as plain content.
		 *
		 * Override the default behavior by printing the shortcode instead of rendering it.
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function render_plain_content() {
			// In plain mode, render without shortcode.

			echo $this->generate_shortcode();
		}

		/**
		 * Generate PPWP shortcode.
		 *
		 * @return string PPWP Shortcode
		 */
		public function generate_shortcode() {
			$settings = $this->get_settings_for_display();

			$content = apply_filters( PPW_Constants::HOOK_SHORTCODE_ELEMENTOR_CONTENT, '', $settings );
			if ( ! empty( $content ) ) {
				return $content;
			}

			$passwords         = isset( $settings['ppwp_passwords'] ) ? $settings['ppwp_passwords'] : '';
			$whitelisted_roles = $this->transform_whitelisted_roles_to_string( $settings );
			$download_limit    = isset( $settings['ppwp_download_limit'] ) ? $settings['ppwp_download_limit'] : '';
			$cookie            = isset( $settings['ppwp_cookie'] ) ? $settings['ppwp_cookie'] : '';
			$headline          = isset( $settings['ppwp_headline'] ) ? $settings['ppwp_headline'] : '';
			$description       = isset( $settings['ppwp_description'] ) ? $settings['ppwp_description'] : '';
			$placeholder       = isset( $settings['ppwp_placeholder'] ) ? $settings['ppwp_placeholder'] : '';
			$button            = isset( $settings['ppwp_button'] ) ? $settings['ppwp_button'] : '';
			$shortcode         = sprintf(
				'[ppwp id="" class="" passwords="%1$s" cookie="%2$s" download_limit="%3$s" whitelisted_roles="%4$s" headline="%5$s" description="%6$s" placeholder="%7$s" button="%8$s" ',
				$passwords,
				$cookie,
				$download_limit,
				$whitelisted_roles,
				esc_html__( $headline ),
				esc_html__( $description ),
				esc_html__( $placeholder ),
				esc_html__( $button )
			);

			$shortcode = apply_filters( PPW_Constants::HOOK_SHORTCODE_ELEMENTOR_ATTRIBUTES, $shortcode, $settings );

			$shortcode .= ']';

			return $shortcode . $settings['ppwp_protected_content'] . '[/ppwp]';
		}

		/**
		 * Get whitelisted roles from Settings.
		 *
		 * @param array $settings The settings.
		 *
		 * @return string
		 */
		private function transform_whitelisted_roles_to_string( $settings ) {
			if (
				! isset( $settings['ppwp_whitelisted_roles'] ) ||
				! is_array( $settings['ppwp_whitelisted_roles'] ) ||
				count( $settings['ppwp_whitelisted_roles'] ) === 0
			) {
				return '';
			}

			return implode( ',', $settings['ppwp_whitelisted_roles'] );
		}
	}
}
