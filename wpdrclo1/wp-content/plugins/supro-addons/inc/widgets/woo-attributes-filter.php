<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Supro_Widget_Attributes_Filter' ) ) {

	/**
	 * Layered Navigation Widget.
	 *
	 * @author   WooThemes
	 * @category Widgets
	 * @package  WooCommerce/Widgets
	 * @version  2.6.0
	 * @extends  WC_Widget
	 */
	class Supro_Widget_Attributes_Filter extends WC_Widget {

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->widget_cssclass    = 'woocommerce supro_attributes_filter widget_layered_nav';
			$this->widget_description = esc_html__( 'Shows a custom attribute in a widget which lets you narrow down the list of products when viewing product categories.', 'supro' );
			$this->widget_id          = 'supro_attributes_filter';
			$this->widget_name        = esc_html__( 'Supro Attributes Filter', 'supro' );
			parent::__construct();
		}

		/**
		 * Updates a particular instance of a widget.
		 *
		 * @see WP_Widget->update
		 *
		 * @param array $new_instance
		 * @param array $old_instance
		 *
		 * @return array
		 */
		public function update( $new_instance, $old_instance ) {
			$this->init_settings();

			return parent::update( $new_instance, $old_instance );
		}

		/**
		 * Outputs the settings update form.
		 *
		 * @see WP_Widget->form
		 *
		 * @param array $instance
		 */
		public function form( $instance ) {
			$this->init_settings();
			parent::form( $instance );
		}

		/**
		 * Init settings after post types are registered.
		 */
		public function init_settings() {
			$attribute_array      = array();
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			if ( ! empty( $attribute_taxonomies ) ) {
				foreach ( $attribute_taxonomies as $tax ) {
					if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
						$attribute_array[$tax->attribute_name] = $tax->attribute_name;
					}
				}
			}

			$this->settings = array(
				'title'      => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Filter by', 'supro' ),
					'label' => esc_html__( 'Title', 'supro' )
				),
				'attribute'  => array(
					'type'    => 'select',
					'std'     => '',
					'label'   => esc_html__( 'Attribute', 'supro' ),
					'options' => $attribute_array
				),
				'query_type' => array(
					'type'    => 'select',
					'std'     => 'and',
					'label'   => esc_html__( 'Query type', 'supro' ),
					'options' => array(
						'and' => esc_html__( 'AND', 'supro' ),
						'or'  => esc_html__( 'OR', 'supro' )
					)
				),
				'height'     => array(
					'type'  => 'text',
					'std'   => '',
					'label' => esc_html__( 'Height', 'supro' )
				),
			);
		}

		/**
		 * Output widget.
		 *
		 * @see WP_Widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {
				return;
			}

			$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
			$taxonomy           = isset( $instance['attribute'] ) ? wc_attribute_taxonomy_name( $instance['attribute'] ) : $this->settings['attribute']['std'];
			$query_type         = isset( $instance['query_type'] ) ? $instance['query_type'] : $this->settings['query_type']['std'];
			$height             = isset( $instance['height'] ) ? intval( $instance['height'] ) : '';

			if ( ! taxonomy_exists( $taxonomy ) ) {
				return;
			}

			$get_terms_args = array( 'hide_empty' => '1' );

			$orderby = wc_attribute_orderby( $taxonomy );

			switch ( $orderby ) {
				case 'name' :
					$get_terms_args['orderby']    = 'name';
					$get_terms_args['menu_order'] = false;
					break;
				case 'id' :
					$get_terms_args['orderby']    = 'id';
					$get_terms_args['order']      = 'ASC';
					$get_terms_args['menu_order'] = false;
					break;
				case 'menu_order' :
					$get_terms_args['menu_order'] = 'ASC';
					break;
			}

			$terms = get_terms( $taxonomy, $get_terms_args );

			if ( 0 === sizeof( $terms ) ) {
				return;
			}

			switch ( $orderby ) {
				case 'name_num' :
					usort( $terms, '_wc_get_product_terms_name_num_usort_callback' );
					break;
				case 'parent' :
					usort( $terms, '_wc_get_product_terms_parent_usort_callback' );
					break;
			}

			ob_start();

			$this->widget_start( $args, $instance );

			// Use select by default if plugin Soo Product Attribute Swatches is not installed
			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}

			$found = $this->layered_nav_list( $terms, $taxonomy, $query_type, $height );


			$this->widget_end( $args );

			// Force found when option is selected - do not force found on taxonomy attributes
			if ( ! is_tax() && is_array( $_chosen_attributes ) && array_key_exists( $taxonomy, $_chosen_attributes ) ) {
				$found = true;
			}

			if ( ! $found ) {
				ob_end_clean();
			} else {
				echo ob_get_clean();
			}
		}

		/**
		 * Return the currently viewed taxonomy name.
		 * @return string
		 */
		protected function get_current_taxonomy() {
			return is_tax() ? get_queried_object()->taxonomy : '';
		}

		/**
		 * Return the currently viewed term ID.
		 * @return int
		 */
		protected function get_current_term_id() {
			return absint( is_tax() ? get_queried_object()->term_id : 0 );
		}

		/**
		 * Return the currently viewed term slug.
		 * @return int
		 */
		protected function get_current_term_slug() {
			return absint( is_tax() ? get_queried_object()->slug : 0 );
		}


		/**
		 * Get current page URL for layered nav items.
		 * @return string
		 */
		protected function get_page_base_url( $taxonomy ) {
			if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
				$link = home_url();
			} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ) ) ) {
				$link = get_post_type_archive_link( 'product' );
			} elseif ( is_product_category() ) {
				$link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
			} elseif ( is_product_tag() ) {
				$link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
			} else {
				$queried_object = get_queried_object();
				$link           = get_term_link( $queried_object->slug, $queried_object->taxonomy );
			}

			// Min/Max
			if ( isset( $_GET['min_price'] ) ) {
				$link = add_query_arg( 'min_price', wc_clean( $_GET['min_price'] ), $link );
			}

			if ( isset( $_GET['max_price'] ) ) {
				$link = add_query_arg( 'max_price', wc_clean( $_GET['max_price'] ), $link );
			}

			// Orderby
			if ( isset( $_GET['orderby'] ) ) {
				$link = add_query_arg( 'orderby', wc_clean( $_GET['orderby'] ), $link );
			}

			/**
			 * Search Arg.
			 * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
			 */
			if ( get_search_query() ) {
				$link = add_query_arg( 's', rawurlencode( wp_specialchars_decode( get_search_query() ) ), $link );
			}

			// Post Type Arg
			if ( isset( $_GET['post_type'] ) ) {
				$link = add_query_arg( 'post_type', wc_clean( $_GET['post_type'] ), $link );
			}

			// Min Rating Arg
			if ( isset( $_GET['min_rating'] ) ) {
				$link = add_query_arg( 'min_rating', wc_clean( $_GET['min_rating'] ), $link );
			}

			// All current filters
			if ( $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes() ) {
				foreach ( $_chosen_attributes as $name => $data ) {
					if ( $name === $taxonomy ) {
						continue;
					}
					$filter_name = sanitize_title( str_replace( 'pa_', '', $name ) );
					if ( ! empty( $data['terms'] ) ) {
						$link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );
					}
					if ( 'or' == $data['query_type'] ) {
						$link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
					}
				}
			}

			return $link;
		}

		/**
		 * Count products within certain terms, taking the main WP query into consideration.
		 *
		 * @param  array  $term_ids
		 * @param  string $taxonomy
		 * @param  string $query_type
		 *
		 * @return array
		 */
		protected function get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type ) {
			global $wpdb;

			$tax_query  = WC_Query::get_main_tax_query();
			$meta_query = WC_Query::get_main_meta_query();

			if ( 'or' === $query_type ) {
				foreach ( $tax_query as $key => $query ) {
					if ( isset( $query['taxonomy'] ) && $taxonomy === $query['taxonomy'] ) {
						unset( $tax_query[$key] );
					}
				}
			}

			$meta_query     = new WP_Meta_Query( $meta_query );
			$tax_query      = new WP_Tax_Query( $tax_query );
			$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
			$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

			// Generate query
			$query           = array();
			$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, terms.term_id as term_count_id";
			$query['from']   = "FROM {$wpdb->posts}";
			$query['join']   = "
			INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
			INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
			INNER JOIN {$wpdb->terms} AS terms USING( term_id )
			" . $tax_query_sql['join'] . $meta_query_sql['join'];

			$query['where'] = "
			WHERE {$wpdb->posts}.post_type IN ( 'product' )
			AND {$wpdb->posts}.post_status = 'publish'
			" . $tax_query_sql['where'] . $meta_query_sql['where'] . "
			AND terms.term_id IN (" . implode( ',', array_map( 'absint', $term_ids ) ) . ")
		";

			if ( $search = WC_Query::get_main_search_query_sql() ) {
				$query['where'] .= ' AND ' . $search;
			}

			$query['group_by'] = "GROUP BY terms.term_id";
			$query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
			$query             = implode( ' ', $query );
			$results           = $wpdb->get_results( $query );

			return wp_list_pluck( $results, 'term_count', 'term_count_id' );
		}

		/**
		 * Show list based layered nav.
		 *
		 * @param  array  $terms
		 * @param  string $taxonomy
		 * @param  string $query_type
		 *
		 * @return bool Will nav display?
		 */
		protected function layered_nav_list( $terms, $taxonomy, $query_type, $height ) {
			$attr = '';

			if ( isset( $height ) && $height ) {
				$attr = 'data-height="' . $height . '"';
			}

			// List display
			echo '<ul ' . $attr . '>';

			$term_counts        = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
			$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
			$found              = false;

			foreach ( $terms as $term ) {
				$current_values = isset( $_chosen_attributes[$taxonomy]['terms'] ) ? $_chosen_attributes[$taxonomy]['terms'] : array();
				$option_is_set  = in_array( $term->slug, $current_values );
				$count          = isset( $term_counts[$term->term_id] ) ? $term_counts[$term->term_id] : 0;

				// skip the term for the current archive
				if ( $this->get_current_term_id() === $term->term_id ) {
					continue;
				}

				// Only show options with count > 0
				if ( 0 < $count ) {
					$found = true;
				} elseif ( 'and' === $query_type && 0 === $count && ! $option_is_set ) {
					continue;
				}

				$filter_name    = 'filter_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) );
				$current_filter = isset( $_GET[$filter_name] ) ? explode( ',', wc_clean( $_GET[$filter_name] ) ) : array();
				$current_filter = array_map( 'sanitize_title', $current_filter );

				if ( ! in_array( $term->slug, $current_filter ) ) {
					$current_filter[] = $term->slug;
				}

				$link = $this->get_page_base_url( $taxonomy );

				// Add current filters to URL.
				foreach ( $current_filter as $key => $value ) {
					// Exclude query arg for current term archive term
					if ( $value === $this->get_current_term_slug() ) {
						unset( $current_filter[$key] );
					}

					// Exclude self so filter can be unset on click.
					if ( $option_is_set && $value === $term->slug ) {
						unset( $current_filter[$key] );
					}
				}

				if ( ! empty( $current_filter ) ) {
					$link = add_query_arg( $filter_name, implode( ',', $current_filter ), $link );

					// Add Query type Arg to URL
					if ( $query_type === 'or' && ! ( 1 === sizeof( $current_filter ) && $option_is_set ) ) {
						$link = add_query_arg( 'query_type_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) ), 'or', $link );
					}
				}

				$css_class  = '';
				$color_html = '';

				if ( function_exists( 'TA_WCVS' ) ) {
					$attr = TA_WCVS()->get_tax_attribute( $taxonomy );
					switch ( $attr->attribute_type ) {
						case 'color':
							$color = get_term_meta( $term->term_id, 'color', true );
							list( $r, $g, $b ) = sscanf( $color, "#%02x%02x%02x" );
							$css_class = 'show-color';

							$color_html = sprintf(
								'<span class="swatch swatch-color" style="background-color:%s;color:%s;" title="%s"></span>',
								esc_attr( $color ),
								esc_attr( "rgba($r,$g,$b,0.5)" ),
								esc_attr( $term->name )
							);

							break;

						case 'image':
							$css_class = 'show-image';

							break;

						case 'label':
							$css_class = 'show-label';

							break;
					}
				}

				echo '<li class="wc-layered-nav-term ' . esc_attr( $css_class ) . ( $option_is_set ? ' chosen' : '' ) . '">';


				if ( $count > 0 || $option_is_set ) {
					echo '<a href="' . esc_url( apply_filters( 'woocommerce_layered_nav_link', $link ) ) . '">';
				} else {
					echo '<span>';
				}

				echo '' . $color_html;

				echo '<span class="nav-title">' . esc_html( $term->name ) . '</span>';

				if ( $count > 0 || $option_is_set ) {
					echo '</a> ';
				} else {
					echo '</span> ';
				}

				echo apply_filters( 'supro_color_filter_nav_count', '<span class="count">' . absint( $count ) . '</span>', $count, $term );

				echo '</li>';
			}

			echo '</ul>';

			return $found;
		}
	}
}