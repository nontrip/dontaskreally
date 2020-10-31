<?php

if ( ! class_exists( 'Supro_Product_SortBy_Widget' ) ) {

	class Supro_Product_SortBy_Widget extends WP_Widget {
		protected $defaults;

		function __construct() {
			$this->defaults = array(
				'title' => '',
			);

			parent::__construct(
				'product-sort-by',
				esc_html__( 'Supro - Product Sort By', 'supro' ),
				array(
					'classname'   => 'product-sort-by',
					'description' => esc_html__( 'Sort Product By', 'supro' ),
				)
			);
		}

		/**
		 * Outputs the HTML for this widget.
		 *
		 * @param array $args     An array of standard parameters for widgets in this theme
		 * @param array $instance An array of settings for this widget instance
		 *
		 * @return void Echoes it's output
		 */
		function widget( $args, $instance ) {
			$instance = wp_parse_args( $instance, $this->defaults );

			extract( $args );
			echo wp_kses_post( $before_widget );

			if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) {
				echo wp_kses_post( $before_title ) . $title . wp_kses_post( $after_title );
			}
			if ( function_exists( 'woocommerce_catalog_ordering' ) ) {
				woocommerce_catalog_ordering();
			}

			echo wp_kses_post( $after_widget );
		}

		/**
		 * Deals with the settings when they are saved by the admin.
		 *
		 * @param array $new_instance
		 * @param array $old_instance
		 *
		 * @return array
		 */
		function update( $new_instance, $old_instance ) {
			$instance          = array();
			$instance['title'] = strip_tags( $new_instance['title'] );

			return $instance;
		}

		/**
		 * Displays the form for this widget on the Widgets page of the WP Admin area.
		 *
		 * @param array $instance
		 *
		 * @return array
		 */
		function form( $instance ) {
			$instance = wp_parse_args( $instance, $this->defaults );
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'supro' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
			</p>
			<?php
		}
	}
}