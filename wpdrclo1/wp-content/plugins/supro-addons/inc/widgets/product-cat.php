<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Supro_Widget_Product_Cat' ) ) {

	/**
	 * Supro - Product Categories Widget.
	 *
	 */
	class Supro_Widget_Product_Cat extends WC_Widget {

		/**
		 * Category ancestors.
		 *
		 * @var array
		 */
		public $cat_ancestors;

		/**
		 * Current Category.
		 *
		 * @var bool
		 */
		public $current_cat;

		public function __construct() {
			$this->widget_cssclass    = 'woocommerce supro_widget_product_categories';
			$this->widget_description = esc_html__( 'A list of product categories.', 'supro' );
			$this->widget_id          = 'supro_product_categories';
			$this->widget_name        = esc_html__( 'Supro - Product Categories', 'supro' );
			$this->settings           = array(
				'title'              => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Product categories', 'supro' ),
					'label' => esc_html__( 'Title', 'supro' ),
				),
				'orderby'            => array(
					'type'    => 'select',
					'std'     => 'name',
					'label'   => esc_html__( 'Order by', 'supro' ),
					'options' => array(
						'order' => esc_html__( 'Category order', 'supro' ),
						'title' => esc_html__( 'Name', 'supro' ),
						'count' => esc_html__( 'Count', 'supro' ),
					),
				),
				'count'              => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Show product counts', 'supro' ),
				),
				'show_children_only' => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Only show current category and children of it', 'supro' ),
				),
				'hide_empty'         => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Hide empty categories', 'supro' ),
				),
				'max_depth'          => array(
					'type'  => 'text',
					'std'   => '',
					'label' => esc_html__( 'Maximum depth', 'supro' ),
				),
				'height' => array(
					'type'  => 'text',
					'std'   => '',
					'label' => esc_html__( 'Height', 'supro' )
				),
			);

			parent::__construct();
		}

		/**
		 * Output widget.
		 *
		 * @see WP_Widget
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Widget instance.
		 */
		public function widget( $args, $instance ) {
			global $wp_query, $post;

			$attr = '';
			if ( isset( $instance['height'] ) && $instance['height'] ) {
				$height = $instance['height'];

				$attr = 'data-height="' . intval( $height ) . '"';
			}

			$count              = isset( $instance['count'] ) ? $instance['count'] : $this->settings['count']['std'];
			$show_children_only = isset( $instance['show_children_only'] ) ? $instance['show_children_only'] : $this->settings['show_children_only']['std'];
			$orderby            = isset( $instance['orderby'] ) ? $instance['orderby'] : $this->settings['orderby']['std'];
			$hide_empty         = isset( $instance['hide_empty'] ) ? $instance['hide_empty'] : $this->settings['hide_empty']['std'];
			$dropdown_args      = array(
				'hide_empty' => $hide_empty,
			);
			$list_args          = array(
				'show_count'   => $count,
				'hierarchical' => 1,
				'taxonomy'     => 'product_cat',
				'hide_empty'   => $hide_empty,
			);
			$max_depth          = absint( isset( $instance['max_depth'] ) ? $instance['max_depth'] : $this->settings['max_depth']['std'] );

			$list_args['menu_order'] = false;
			$dropdown_args['depth']  = $max_depth;
			$list_args['depth']      = $max_depth;

			if ( 'order' === $orderby ) {
				$list_args['menu_order'] = 'asc';
			} else {
				$list_args['orderby'] = $orderby;
				if ( $orderby === 'count' ) {
					$atts['order'] = 'desc';
				}
			}

			$this->current_cat   = false;
			$this->cat_ancestors = array();

			if ( is_tax( 'product_cat' ) ) {
				$this->current_cat   = $wp_query->queried_object;
				$this->cat_ancestors = get_ancestors( $this->current_cat->term_id, 'product_cat' );

			} elseif ( is_singular( 'product' ) ) {
				$product_category = wc_get_product_terms(
					$post->ID, 'product_cat', apply_filters(
						'woocommerce_product_categories_widget_product_terms_args', array(
							'orderby' => 'parent',
						)
					)
				);


				if ( ! empty( $product_category ) ) {
					$current_term = '';
					foreach ( $product_category as $term ) {
						if ( $term->parent != 0 ) {
							$current_term = $term;
							break;
						}
					}
					$this->current_cat   = $current_term ? $current_term : $product_category[0];
					$this->cat_ancestors = get_ancestors( $this->current_cat->term_id, 'product_cat' );
				}

			}

			// Show Siblings and Children Only.
			if ( $show_children_only ) {
				$dropdown_args['child_of'] = 0;
				$list_args['child_of']     = 0;

				if ( $this->current_cat ) {
					// Direct children.
					$include = get_terms(
						'product_cat',
						array(
							'fields'       => 'ids',
							'parent'       => $this->current_cat->term_id,
							'hierarchical' => true,
							'hide_empty'   => false,
						)
					);

					$list_args['include']     = implode( ',', $include );
					$list_args['depth']       = 1;
					$dropdown_args['include'] = $list_args['include'];
					$dropdown_args['depth']   = 1;
				}
			}

			$this->widget_start( $args, $instance );

			$list_args['title_li']                   = '';
			$list_args['pad_counts']                 = 1;
			$list_args['show_option_none']           = esc_html__( 'No product categories exist.', 'supro' );
			$list_args['current_category']           = ( $this->current_cat ) ? $this->current_cat->term_id : '';
			$list_args['current_category_ancestors'] = $this->cat_ancestors;
			$list_args['max_depth']                  = $max_depth;

			$parent_term_id = 0;
			if ( is_tax( 'product_cat' ) || is_singular( 'product' ) ) {
				if ( count( $this->cat_ancestors ) > 0 ) {
					$parent_term_id = end( $this->cat_ancestors );
				}

				$children_terms = get_term_children( $parent_term_id, 'product_cat' );
				if ( count( $children_terms ) <= 0 ) {
					$parent_term_id = 0;
				}
			}

			$list_args['child_of'] = $parent_term_id;

			echo '<ul class="product-categories" ' . $attr . ' >';
			if ( $parent_term_id ) {
				$parent_term = get_term_by( 'id', $parent_term_id, 'product_cat' );
				echo '<li class="current-cat-parent supro-current-cat-parent"><a href="' . esc_url( get_term_link( $parent_term_id, 'product_cat' ) ) . '">' . $parent_term->name . '</a>';
				echo '<ul class="children">';
			}
			wp_list_categories( apply_filters( 'woocommerce_product_categories_widget_args', $list_args ) );
			if ( $parent_term_id ) {
				echo '</ul>';
				echo '</li>';
			}
			echo '</ul>';

			$this->widget_end( $args );
		}
	}
}