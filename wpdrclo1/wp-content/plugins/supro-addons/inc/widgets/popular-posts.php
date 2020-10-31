<?php

if ( ! class_exists( 'Supro_PopularPost_Widget' ) ) {

	class Supro_PopularPost_Widget extends WP_Widget {
		/**
		 * Holds widget settings defaults, populated in constructor.
		 *
		 * @var array
		 */
		protected $defaults;

		/**
		 * Class constructor
		 * Set up the widget
		 *
		 * @return Supro_PopularPost_Widget
		 */
		function __construct() {
			$this->defaults = array(
				'title' => '',
				'limit' => 3,
			);

			parent::__construct(
				'popular-posts-widget',
				esc_html__( 'Supro - PopularPost', 'supro' ),
				array(
					'classname'   => 'popular-posts-widget',
					'description' => esc_html__( 'Display most popular posts', 'supro' ),
				)
			);
		}

		/**
		 * Display widget
		 *
		 * @param array $args     Sidebar configuration
		 * @param array $instance Widget settings
		 *
		 * @return void
		 */
		function widget( $args, $instance ) {
			$instance = wp_parse_args( $instance, $this->defaults );
			extract( $args );

			$query_args = array(
				'posts_per_page'      => $instance['limit'],
				'post_type'           => 'post',
				'orderby'             => 'comment_count',
				'order'               => 'DESC',
				'ignore_sticky_posts' => true,
			);

			$query = new WP_Query( $query_args );

			if ( ! $query->have_posts() ) {
				return;
			}

			echo wp_kses_post( $before_widget );

			if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) {
				echo wp_kses_post( $before_title ) . $title . wp_kses_post( $after_title );
			}

			echo '<div class="widget-list-post">';

			while ( $query->have_posts() ) : $query->the_post();
				?>
				<div class="popular-post post clearfix <?php echo esc_attr( $class ); ?>">
					<div class="mini-widget-title">
						<h4><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h4>
					</div>
				</div>
				<?php
			endwhile;
			wp_reset_postdata();

			echo '</div>';

			echo wp_kses_post( $after_widget );

		}

		/**
		 * Update widget
		 *
		 * @param array $new_instance New widget settings
		 * @param array $old_instance Old widget settings
		 *
		 * @return array
		 */
		function update( $new_instance, $old_instance ) {
			$new_instance['title'] = strip_tags( $new_instance['title'] );
			$new_instance['limit'] = intval( $new_instance['limit'] );

			return $new_instance;
		}

		/**
		 * Display widget settings
		 *
		 * @param array $instance Widget settings
		 *
		 * @return void
		 */
		function form( $instance ) {
			$instance = wp_parse_args( $instance, $this->defaults );
			?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'supro' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
					   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
					   value="<?php echo esc_attr( $instance['title'] ); ?>">
			</p>

			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"
					   name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="text" size="2"
					   value="<?php echo intval( $instance['limit'] ); ?>">
				<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php esc_html_e( 'Number Of Posts', 'supro' ); ?></label>
			</p>

			<?php
		}
	}
}