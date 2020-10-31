<?php
/**
 * Widget API: WP_Widget_Text class
 *
 * @package    WordPress
 * @subpackage Widgets
 * @since      4.4.0
 */

if ( ! class_exists( 'Supro_Language_Currency_Widget' ) ) {

	/**
	 * Core class used to implement a Text widget.
	 *
	 * @since 2.8.0
	 *
	 * @see   WP_Widget
	 */
	class Supro_Language_Currency_Widget extends WP_Widget {

		/**
		 * Sets up a new Text widget instance.
		 *
		 * @since  2.8.0
		 * @access public
		 */
		public function __construct() {
			$widget_ops  = array(
				'classname'   => 'widget_text supro-language-currency',
				'description' => esc_html__( 'Shows language list by WPML plugin and currency list by WooCommerce Currency Switcher plugin', 'supro' ),
			);
			$control_ops = array( 'width' => 400, 'height' => 350 );
			parent::__construct( 'supro-language-currency', esc_html__( 'Supro Language & Currency', 'supro' ), $widget_ops, $control_ops );
		}

		/**
		 * Outputs the content for the current Text widget instance.
		 *
		 * @since  2.8.0
		 * @access public
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title',
		 *                        'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current Text widget instance.
		 */
		public function widget( $args, $instance ) {


			echo wp_kses_post( $args['before_widget'] );
			if ( ! empty( $title ) ) {
				echo wp_kses_post( $args['before_title'] ) . $title . wp_kses_post( $args['after_title'] );
			}

			?>
			<?php if ( $instance['show'] == '' || $instance['show'] == 'language' ) : ?>
				<div class="widget-language widget-lan-cur">
					<?php
					if ( $instance['language'] ) {
						echo '<h4 class="widget-title">' . $instance['language'] . '</h4>';
					}

					$show_name = 'code';
					if ( $instance['type'] == 'desc' ) {
						$show_name = 'name';
					}
					echo apply_filters( 'supro_language_switcher_widget', supro_language_switcher( $show_name ), $show_name );
					?>
				</div>
			<?php endif; ?>
			<?php if ( $instance['show'] == '' || $instance['show'] == 'currency' ) : ?>
				<div class="widget-currency widget-lan-cur ">
					<?php
					if ( $instance['currency'] ) {
						echo '<h4 class="widget-title">' . $instance['currency'] . '</h4>';
					}
					$show_desc = false;
					if ( $instance['type'] == 'desc' ) {
						$show_desc = true;
					}
					echo supro_currency_switcher( $show_desc );
					?>
				</div>
			<?php endif; ?>
			<?php
			echo wp_kses_post( $args['after_widget'] );
		}

		/**
		 * Handles updating settings for the current Text widget instance.
		 *
		 * @since  2.8.0
		 * @access public
		 *
		 * @param array $new_instance New settings for this instance as input by the user via
		 *                            WP_Widget::form().
		 * @param array $old_instance Old settings for this instance.
		 *
		 * @return array Settings to save or bool false to cancel saving.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance             = $old_instance;
			$instance['language'] = sanitize_text_field( $new_instance['language'] );
			$instance['type']     = sanitize_text_field( $new_instance['type'] );
			$instance['show']     = sanitize_text_field( $new_instance['show'] );
			$instance['currency'] = sanitize_text_field( $new_instance['currency'] );

			return $instance;
		}

		/**
		 * Outputs the Text widget settings form.
		 *
		 * @since  2.8.0
		 * @access public
		 *
		 * @param array $instance Current settings.
		 */
		public function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array( 'show' => '', 'type' => '', 'language' => '', 'currency' => '' ) );
			$show     = sanitize_title( $instance['show'] );
			$type     = sanitize_title( $instance['type'] );
			$language = sanitize_text_field( $instance['language'] );
			$currency = sanitize_text_field( $instance['currency'] );
			?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'language' ) ); ?>"><?php esc_html_e( 'Language Text:', 'supro' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'language' ) ); ?>"
					   name="<?php echo esc_attr( $this->get_field_name( 'language' ) ); ?>" type="text"
					   value="<?php echo esc_attr( $language ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'currency' ) ); ?>"><?php esc_html_e( 'Currency Text:', 'supro' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'currency' ) ); ?>"
					   name="<?php echo esc_attr( $this->get_field_name( 'currency' ) ); ?>" type="text"
					   value="<?php echo esc_attr( $currency ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php esc_html_e( 'Type:', 'supro' ); ?></label>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>">
					<option value="code" <?php echo ( 'code' == $type ) ? 'selected' : '' ?>><?php esc_html_e( 'Code', 'supro' ); ?></option>
					<option value="desc" <?php echo ( 'desc' == $type ) ? 'selected' : '' ?>><?php esc_html_e( 'Description', 'supro' ); ?></option>
				</select>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'show' ) ); ?>"><?php esc_html_e( 'Show:', 'supro' ); ?></label>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'show' ) ); ?>">
					<option value="" <?php echo ( '' == $show ) ? 'selected' : '' ?>><?php esc_html_e( 'Default', 'supro' ); ?></option>
					<option value="currency" <?php echo ( 'currency' == $show ) ? 'selected' : '' ?>><?php esc_html_e( 'Only Currency', 'supro' ); ?></option>
					<option value="language" <?php echo ( 'language' == $show ) ? 'selected' : '' ?>><?php esc_html_e( 'Only Language', 'supro' ); ?></option>
				</select>
			</p>

			<?php
		}
	}
}