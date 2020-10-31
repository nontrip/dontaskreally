<?php
/**
 * Load and register widgets
 *
 * @package Supro
 */

require_once SUPRO_ADDONS_DIR . '/inc/widgets/popular-posts.php';
require_once SUPRO_ADDONS_DIR . '/inc/widgets/socials.php';
require_once SUPRO_ADDONS_DIR . '/inc/widgets/language-currency.php';
require_once SUPRO_ADDONS_DIR . '/inc/widgets/product-sort-by.php';
require_once SUPRO_ADDONS_DIR . '/inc/widgets/filter-price-list.php';

/**
 * Register widgets
 *
 * @since  1.0
 *
 * @return void
 */
if ( ! function_exists( 'supro_register_widgets' ) ) :

	function supro_register_widgets() {
		if ( class_exists( 'WC_Widget' ) ) {
			require_once SUPRO_ADDONS_DIR . '/inc/widgets/woo-attributes-filter.php';
			require_once SUPRO_ADDONS_DIR . '/inc/widgets/product-tag.php';
			require_once SUPRO_ADDONS_DIR . '/inc/widgets/product-cat.php';
			require_once SUPRO_ADDONS_DIR . '/inc/widgets/widget-layered-nav-filters.php';

			register_widget( 'Supro_Widget_Attributes_Filter' );
			register_widget( 'Supro_Widget_Product_Tag_Cloud' );
			register_widget( 'Supro_Widget_Product_Cat' );
			register_widget( 'Supro_Widget_Layered_Nav_Filters' );
		}

		register_widget( 'Supro_Product_SortBy_Widget' );
		register_widget( 'Supro_Price_Filter_List_Widget' );
		register_widget( 'Supro_PopularPost_Widget' );
		register_widget( 'Supro_Social_Links_Widget' );
		register_widget( 'Supro_Language_Currency_Widget' );
	}

	add_action( 'widgets_init', 'supro_register_widgets' );
endif;
/**
 * Change markup of archive and category widget to include .count for post count
 *
 * @param string $output
 *
 * @return string
 */
if ( ! function_exists( 'supro_widget_archive_count' ) ) :

	function supro_widget_archive_count( $output ) {
		$output = preg_replace( '|\((\d+)\)|', '<span class="count">(\\1)</span>', $output );

		return $output;
	}

	add_filter( 'wp_list_categories', 'supro_widget_archive_count' );
	add_filter( 'get_archives_link', 'supro_widget_archive_count' );
endif;