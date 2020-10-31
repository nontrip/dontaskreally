<?php
/**
 * Hooks for template archive
 *
 * @package Supro
 */


/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @since 1.0
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function supro_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}

add_action( 'wp', 'supro_setup_author' );

/**
 * Add CSS classes to posts
 *
 * @param array $classes
 *
 * @return array
 */
function supro_post_class( $classes ) {

	$classes[] = has_post_thumbnail() ? '' : 'no-thumb';

	return $classes;
}

add_filter( 'post_class', 'supro_post_class' );

/**
 * Open tag after start site content
 */
if ( ! function_exists( 'supro_site_content_open' ) ) :

	function supro_site_content_open() {
		$container       = 'container';
		$product_layout  = supro_get_option( 'single_product_layout' );
		$portfolio_style = supro_get_option( 'portfolio_layout' );

		if ( supro_is_page_template() ) {
			$container = 'container-fluid';
		}

		if ( function_exists( 'is_product' ) && is_product() ) {
			if ( in_array( $product_layout, array( '2', '5' ) ) ) {
				$container = 'container-fluid';
			}
		}

		if ( supro_is_catalog() && intval( supro_get_option( 'catalog_full_width' ) ) ) {
			$container = 'supro-catalog-container';
		}

		if ( is_singular( 'portfolio' ) ) {
			$container = 'container-fluid';
		}

		if ( supro_is_portfolio() && $portfolio_style == 'masonry' ) {
			$container = 'container-fluid';
		}

		echo '<div class="' . esc_attr( apply_filters( 'supro_site_content_container_class', $container ) ) . '">';
		echo '<div class="row">';
	}

endif;

add_action( 'supro_site_content_open', 'supro_site_content_open', 20 );

/**
 * Close tag before end site content
 */
if ( ! function_exists( 'supro_site_content_close' ) ) :

	function supro_site_content_close() {
		echo '</div>';
		echo '</div>';
	}

endif;

add_action( 'supro_site_content_close', 'supro_site_content_close', 100 );


/**
 * Add blog categories
 *
 * @since  1.0
 *
 *
 */
if ( ! function_exists( 'supro_blog_text_categories' ) ) :
	function supro_blog_text_categories() {
		$blog_style = supro_get_option( 'blog_style' );
		$cat_filter = intval( supro_get_option( 'blog_cat_filter' ) );

		if ( ! $cat_filter ) {
			return;
		}

		$cats_slug = supro_get_option( 'blog_categories' );

		$args = [
			'number'        => intval( supro_get_option( 'blog_categories_numbers' ) ),
			'custom'        => ! empty( $cats_slug ) ? true : false,
			'custom_values' => explode( ',', $cats_slug ),
		];

		$args = apply_filters( 'supro_blog_cats_filter_args', $args );

		if ( $blog_style == 'masonry' ) {
			echo '<div class="container">';
		}

		supro_taxs_list( $args );

		if ( $blog_style == 'masonry' ) {
			echo '</div>';
		}
	}
endif;

add_action( 'supro_before_post_list', 'supro_blog_text_categories' );
add_action( 'supro_before_archive_post_list', 'supro_blog_text_categories' );

/**
 * Add blog categories
 *
 * @since  1.0
 *
 *
 */
if ( ! function_exists( 'supro_portfolio_categories' ) ) :
	function supro_portfolio_categories() {
		$p_style    = supro_get_option( 'portfolio_layout' );
		$cat_filter = intval( supro_get_option( 'portfolio_category_filter' ) );

		if ( ! $cat_filter ) {
			return;
		}

		$args = apply_filters( 'supro_blog_cats_filter_args', [
			'taxonomy'   => 'portfolio_category',
			'number'     => 6,
			'button_url' => get_post_type_archive_link( 'portfolio' ),
		] );

		supro_taxs_list( $args );
	}

endif;

add_action( 'supro_before_portfolio_list', 'supro_portfolio_categories' );
add_action( 'supro_before_taxonomy_portfolio_list', 'supro_portfolio_categories' );


/**
 * Filter to archive title and add page title for singular pages
 *
 * @param string $title
 *
 * @return string
 */
function supro_the_archive_title( $title ) {
	if ( is_search() ) {
		$title = esc_html__( 'Search Results', 'supro' );
	} elseif ( is_404() ) {
		$title = esc_html__( 'Page Not Found', 'supro' );

	} elseif ( is_page() ) {
		$title = get_the_title();
	} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
		$title = get_the_title( wc_get_page_id( 'shop' ) );
	} elseif ( function_exists( 'is_product' ) && is_product() ) {
		$cats = get_the_terms( get_the_ID(), 'product_cat' );
		if ( ! is_wp_error( $cats ) && $cats ) {
			$title = $cats[0]->name;
		} else {
			$title = get_the_title( get_option( 'woocommerce_shop_page_id' ) );
		}

	} elseif ( is_tax() || is_category() ) {
		$title = single_term_title( '', false );

	} elseif ( is_singular( 'post' ) ) {
		$terms = get_the_category();
		if ( $terms && ! is_wp_error( $terms ) ) {
			$title = $terms[0]->name;
		} else {
			$title = get_the_title( get_option( 'page_for_posts' ) );
		}
	} elseif ( supro_is_portfolio() ) {
		$title = wp_kses( supro_get_option( 'portfolio_page_header_title' ), wp_kses_allowed_html( 'post' ) );
		if ( ! $title ) {
			if ( get_option( 'supro_portfolio_page_id' ) ) {
				$title = get_the_title( get_option( 'supro_portfolio_page_id' ) );
			} else {
				$title = esc_html__( 'Portfolio', 'supro' );
			}
		}
	} elseif ( is_home() && is_front_page() ) {
		$title = esc_html__( 'The Latest Posts', 'supro' );

	} elseif ( is_home() && ! is_front_page() ) {
		$title = wp_kses_post( supro_get_option( 'blog_page_header_title' ) );

		if ( empty( $title ) ) {
			$title = get_the_title( get_option( 'page_for_posts' ) );
		}
	}

	if ( get_option( 'woocommerce_shop_page_id' ) ) {
		if ( is_front_page() && ( get_option( 'woocommerce_shop_page_id' ) == get_option( 'page_on_front' ) ) ) {
			$title = get_the_title( get_option( 'woocommerce_shop_page_id' ) );
		}
	}

	return $title;
}

add_filter( 'get_the_archive_title', 'supro_the_archive_title' );

/**
 * Open wrapper for catalog content sidebar
 */
function supro_open_wrapper_catalog_content_sidebar() {
	if ( ! supro_is_catalog() ) {
		return;
	}

	?>
	<div class="widget-canvas-content">
	<div class="widget-panel-header hidden-lg">
		<a href="#" class="close-canvas-panel"><span class="icon-cross2"></span></a>
	</div>
	<div class="widget-panel-content">
	<?php
}

add_action( 'supro_before_sidebar_content', 'supro_open_wrapper_catalog_content_sidebar', 10 );

/**
 * Close wrapper for catalog content sidebar
 */
function supro_close_wrapper_catalog_content_sidebar() {
	if ( ! supro_is_catalog() ) {
		return;
	}

	?>
	</div> <!-- .widget-panel-content -->
	</div> <!-- .widget-canvas-content -->
	<?php
}

add_action( 'supro_after_sidebar_content', 'supro_close_wrapper_catalog_content_sidebar', 100 );

/**
 * Footer Portfolio Carousel
 */
function supro_footer_portfolio_carousel() {
	$p_style = supro_get_option( 'portfolio_layout' );

	if ( 'carousel' != $p_style ) {
		return;
	}

	?>
	<!-- If we need navigation buttons -->
	<div class="swiper-button-prev"><span class="icon-chevron-left"></span></div>
	<div class="swiper-button-next"><span class="icon-chevron-right"></span></div>

	<!-- If we need scrollbar -->
	<div class="swiper-scrollbar container"></div>

	<?php
}

add_action( 'supro_after_portfolio_content', 'supro_footer_portfolio_carousel' );