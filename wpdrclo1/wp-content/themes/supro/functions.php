<?php
/**
 * Supro functions and definitions
 *
 * @package Supro
 */


/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since  1.0
 *
 * @return void
 */
function supro_setup() {
	// Sets the content width in pixels, based on the theme's design and stylesheet.
	$GLOBALS['content_width'] = apply_filters( 'supro_content_width', 840 );

	// Make theme available for translation.
	load_theme_textdomain( 'supro', get_template_directory() . '/lang' );

	// Supports WooCommerce plugin.
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Theme supports
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5', array(
			'comment-list',
			'search-form',
			'comment-form',
			'gallery',
		)
	);

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors.
 	 */
	add_theme_support( 'customize-selective-refresh-widgets' );


	if( supro_fonts_url() ) {
		add_editor_style( array( 'css/editor-style.css', supro_fonts_url(), get_template_directory_uri() . '/css/eleganticons.min.css' ) );
	} else {
		add_editor_style( 'css/editor-style.css' );
	}

	// Load regular editor styles into the new block-based editor.
	add_theme_support( 'editor-styles' );

	// Load default block styles.
	add_theme_support( 'wp-block-styles' );

	add_theme_support( 'align-wide' );

	add_theme_support( 'align-full' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	add_image_size( 'supro-blog-grid', 666, 540, true );
	add_image_size( 'supro-blog-grid-2', 555, 375, true );
	add_image_size( 'supro-blog-list', 1170, 500, true );
	add_image_size( 'supro-blog-masonry-1', 450, 450, true );
	add_image_size( 'supro-blog-masonry-2', 450, 300, true );
	add_image_size( 'supro-blog-masonry-3', 450, 600, true );
	add_image_size( 'supro-product-masonry-normal', 300, 221, true );
	add_image_size( 'supro-product-masonry-long', 300, 441, true );

	// Register theme nav menu
	register_nav_menus(
		array(
			'primary'     => esc_html__( 'Primary Menu', 'supro' ),
			'user_logged' => esc_html__( 'User Logged Menu', 'supro' ),
		)
	);

	if ( is_admin() ) {
		new Supro_Meta_Box_Product_Data;
	}

}

add_action( 'after_setup_theme', 'supro_setup', 100 );

function supro_init() {
	global $supro_woocommerce;
	$supro_woocommerce = new Supro_WooCommerce;
}

add_action( 'wp_loaded', 'supro_init' );

/**
 * Register widgetized area and update sidebar with default widgets.
 *
 * @since 1.0
 *
 * @return void
 */
function supro_register_sidebar() {
	$sidebars = array(
		'topbar-left'     => esc_html__( 'Topbar Left', 'supro' ),
		'topbar-right'    => esc_html__( 'Topbar Right', 'supro' ),
		'topbar-mobile'   => esc_html__( 'Mobile Topbar', 'supro' ),
		'blog-sidebar'    => esc_html__( 'Blog Sidebar', 'supro' ),
		'menu-sidebar'    => esc_html__( 'Menu Sidebar', 'supro' ),
		'catalog-sidebar' => esc_html__( 'Catalog Sidebar', 'supro' ),
		'product-sidebar' => esc_html__( 'Product Sidebar', 'supro' ),
		'catalog-filter'  => esc_html__( 'Catalog Filter', 'supro' ),
	);

	// Register sidebars
	foreach ( $sidebars as $id => $name ) {
		register_sidebar(
			array(
				'name'          => $name,
				'id'            => $id,
				'description'   => esc_html__( 'Add widgets here in order to display on pages', 'supro' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
	}

	register_sidebar(
		array(
			'name'          => esc_html__( 'Mobile Menu Sidebar', 'supro' ),
			'id'            => 'mobile-menu-sidebar',
			'description'   => esc_html__( 'Add widgets here in order to display menu sidebar on mobile', 'supro' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);

	// Register footer sidebars
	for ( $i = 1; $i <= 5; $i ++ ) {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer Widget', 'supro' ) . " $i",
				'id'            => "footer-sidebar-$i",
				'description'   => esc_html__( 'Add widgets here in order to display on footer', 'supro' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
	}

	// Register footer sidebars
	for ( $i = 1; $i <= 3; $i ++ ) {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer Copyright', 'supro' ) . " $i",
				'id'            => "footer-copyright-$i",
				'description'   => esc_html__( 'Add widgets here in order to display on footer', 'supro' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
	}
}

add_action( 'widgets_init', 'supro_register_sidebar' );

/**
 * Load theme
 */

// Frontend functions and shortcodes
require get_template_directory() . '/inc/functions/media.php';
require get_template_directory() . '/inc/functions/nav.php';
require get_template_directory() . '/inc/functions/entry.php';
require get_template_directory() . '/inc/functions/header.php';
require get_template_directory() . '/inc/functions/comments.php';
require get_template_directory() . '/inc/functions/breadcrumbs.php';
require get_template_directory() . '/inc/functions/footer.php';
require get_template_directory() . '/inc/functions/layout.php';
require get_template_directory() . '/inc/functions/style.php';

// Frontend hooks
require get_template_directory() . '/inc/frontend/layout.php';
require get_template_directory() . '/inc/frontend/header.php';
require get_template_directory() . '/inc/frontend/footer.php';
require get_template_directory() . '/inc/frontend/nav.php';
require get_template_directory() . '/inc/frontend/entry.php';
require get_template_directory() . '/inc/mega-menu/class-mega-menu-walker.php';
require get_template_directory() . '/inc/frontend/maintenance.php';

// Customizer
require get_template_directory() . '/inc/backend/customizer.php';

// Woocommerce hooks
require get_template_directory() . '/inc/frontend/woocommerce.php';

if ( is_admin() ) {
	require get_template_directory() . '/inc/libs/class-tgm-plugin-activation.php';
	require get_template_directory() . '/inc/backend/plugins.php';
	require get_template_directory() . '/inc/backend/meta-boxes.php';
	require get_template_directory() . '/inc/mega-menu/class-mega-menu.php';
	require get_template_directory() . '/inc/backend/editor.php';
	require get_template_directory() . '/inc/backend/product-meta-box-data.php';
	require get_template_directory() . '/inc/backend/ajax.php';
}