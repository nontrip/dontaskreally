<?php
/**
 * Hooks for importer
 *
 * @package Supro
 */


/**
 * Importer the demo content
 *
 * @since  1.0
 *
 */
function supro_vc_addons_importer() {
	return array(
		array(
			'name'       => 'Home Default',
			'preview'    => 'http://demo3.drfuri.com/soo-importer/supro/home-default/preview.jpg',
			'content'    => 'http://demo3.drfuri.com/soo-importer/supro/home-default/demo-content.xml',
			'customizer' => 'http://demo3.drfuri.com/soo-importer/supro/home-default/customizer.dat',
			'widgets'    => 'http://demo3.drfuri.com/soo-importer/supro/home-default/widgets.wie',
			'sliders'    => 'http://demo3.drfuri.com/soo-importer/supro/home-default/sliders.zip',
			'pages'      => array(
				'front_page' => 'Home Default',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 400,
					'height' => 400,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Minimal',
			'preview'    => 'http://demo3.drfuri.com/soo-importer/supro/home-minimal/preview.jpg',
			'content'    => 'http://demo3.drfuri.com/soo-importer/supro/home-minimal/demo-content.xml',
			'customizer' => 'http://demo3.drfuri.com/soo-importer/supro/home-minimal/customizer.dat',
			'widgets'    => 'http://demo3.drfuri.com/soo-importer/supro/home-minimal/widgets.wie',
			'sliders'    => 'http://demo3.drfuri.com/soo-importer/supro/home-minimal/sliders.zip',
			'pages'      => array(
				'front_page' => 'Home Minimal',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 400,
					'height' => 400,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Best Selling',
			'preview'    => 'http://demo3.drfuri.com/soo-importer/supro/home-best-selling/preview.jpg',
			'content'    => 'http://demo3.drfuri.com/soo-importer/supro/home-best-selling/demo-content.xml',
			'customizer' => 'http://demo3.drfuri.com/soo-importer/supro/home-best-selling/customizer.dat',
			'widgets'    => 'http://demo3.drfuri.com/soo-importer/supro/home-best-selling/widgets.wie',
			'pages'      => array(
				'front_page' => 'Home Best Selling',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 400,
					'height' => 400,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Modern',
			'preview'    => 'http://demo3.drfuri.com/soo-importer/supro/home-modern/preview.jpg',
			'content'    => 'http://demo3.drfuri.com/soo-importer/supro/home-modern/demo-content.xml',
			'customizer' => 'http://demo3.drfuri.com/soo-importer/supro/home-modern/customizer.dat',
			'widgets'    => 'http://demo3.drfuri.com/soo-importer/supro/home-modern/widgets.wie',
			'sliders'    => 'http://demo3.drfuri.com/soo-importer/supro/home-modern/sliders.zip',
			'pages'      => array(
				'front_page' => 'Home Modern',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 400,
					'height' => 400,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Carousel',
			'preview'    => 'http://demo3.drfuri.com/soo-importer/supro/home-carousel/preview.jpg',
			'content'    => 'http://demo3.drfuri.com/soo-importer/supro/home-carousel/demo-content.xml',
			'customizer' => 'http://demo3.drfuri.com/soo-importer/supro/home-carousel/customizer.dat',
			'widgets'    => 'http://demo3.drfuri.com/soo-importer/supro/home-carousel/widgets.wie',
			'pages'      => array(
				'front_page' => 'Home Carousel',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 400,
					'height' => 400,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Left Sidebar',
			'preview'    => 'http://demo3.drfuri.com/soo-importer/supro/home-left-sidebar/preview.jpg',
			'content'    => 'http://demo3.drfuri.com/soo-importer/supro/home-left-sidebar/demo-content.xml',
			'customizer' => 'http://demo3.drfuri.com/soo-importer/supro/home-left-sidebar/customizer.dat',
			'widgets'    => 'http://demo3.drfuri.com/soo-importer/supro/home-left-sidebar/widgets.wie',
			'sliders'    => 'http://demo3.drfuri.com/soo-importer/supro/home-left-sidebar/sliders.zip',
			'pages'      => array(
				'front_page' => 'Home Left Sidebar',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 400,
					'height' => 400,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Classic',
			'preview'    => 'http://demo3.drfuri.com/soo-importer/supro/home-classic/preview.jpg',
			'content'    => 'http://demo3.drfuri.com/soo-importer/supro/home-classic/demo-content.xml',
			'customizer' => 'http://demo3.drfuri.com/soo-importer/supro/home-classic/customizer.dat',
			'widgets'    => 'http://demo3.drfuri.com/soo-importer/supro/home-classic/widgets.wie',
			'sliders'    => 'http://demo3.drfuri.com/soo-importer/supro/home-classic/sliders.zip',
			'pages'      => array(
				'front_page' => 'Home Classic',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 400,
					'height' => 400,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Full Slider',
			'preview'    => 'http://demo3.drfuri.com/soo-importer/supro/home-full-slider/preview.jpg',
			'content'    => 'http://demo3.drfuri.com/soo-importer/supro/home-full-slider/demo-content.xml',
			'customizer' => 'http://demo3.drfuri.com/soo-importer/supro/home-full-slider/customizer.dat',
			'widgets'    => 'http://demo3.drfuri.com/soo-importer/supro/home-full-slider/widgets.wie',
			'sliders'    => 'http://demo3.drfuri.com/soo-importer/supro/home-full-slider/sliders.zip',
			'pages'      => array(
				'front_page' => 'Home Full Slider',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 400,
					'height' => 400,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Full Width',
			'preview'    => 'http://demo3.drfuri.com/soo-importer/supro/home-full-width/preview.jpg',
			'content'    => 'http://demo3.drfuri.com/soo-importer/supro/home-full-width/demo-content.xml',
			'customizer' => 'http://demo3.drfuri.com/soo-importer/supro/home-full-width/customizer.dat',
			'widgets'    => 'http://demo3.drfuri.com/soo-importer/supro/home-full-width/widgets.wie',
			'sliders'    => 'http://demo3.drfuri.com/soo-importer/supro/home-full-width/sliders.zip',
			'pages'      => array(
				'front_page' => 'Home Full Slider',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 400,
					'height' => 400,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Categories',
			'preview'    => 'http://demo3.drfuri.com/soo-importer/supro/home-categories/preview.jpg',
			'content'    => 'http://demo3.drfuri.com/soo-importer/supro/home-categories/demo-content.xml',
			'customizer' => 'http://demo3.drfuri.com/soo-importer/supro/home-categories/customizer.dat',
			'widgets'    => 'http://demo3.drfuri.com/soo-importer/supro/home-categories/widgets.wie',
			'pages'      => array(
				'front_page' => 'Home Categories',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 400,
					'height' => 400,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Instagram',
			'preview'    => 'http://demo3.drfuri.com/soo-importer/supro/home-instagram/preview.jpg',
			'content'    => 'http://demo3.drfuri.com/soo-importer/supro/home-instagram/demo-content.xml',
			'customizer' => 'http://demo3.drfuri.com/soo-importer/supro/home-instagram/customizer.dat',
			'widgets'    => 'http://demo3.drfuri.com/soo-importer/supro/home-instagram/widgets.wie',
			'pages'      => array(
				'front_page' => 'Home Instagram',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 400,
					'height' => 400,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Metro',
			'preview'    => 'http://demo3.drfuri.com/soo-importer/supro/home-metro/preview.jpg',
			'content'    => 'http://demo3.drfuri.com/soo-importer/supro/home-metro/demo-content.xml',
			'customizer' => 'http://demo3.drfuri.com/soo-importer/supro/home-metro/customizer.dat',
			'widgets'    => 'http://demo3.drfuri.com/soo-importer/supro/home-metro/widgets.wie',
			'pages'      => array(
				'front_page' => 'Home Metro',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 400,
					'height' => 400,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Masonry',
			'preview'    => 'http://demo3.drfuri.com/soo-importer/supro/home-masonry/preview.jpg',
			'content'    => 'http://demo3.drfuri.com/soo-importer/supro/home-masonry/demo-content.xml',
			'customizer' => 'http://demo3.drfuri.com/soo-importer/supro/home-masonry/customizer.dat',
			'widgets'    => 'http://demo3.drfuri.com/soo-importer/supro/home-masonry/widgets.wie',
			'pages'      => array(
				'front_page' => 'Home Masonry',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 400,
					'height' => 400,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Parallax',
			'preview'    => 'http://demo3.drfuri.com/soo-importer/supro/home-parallax/preview.jpg',
			'content'    => 'http://demo3.drfuri.com/soo-importer/supro/home-parallax/demo-content.xml',
			'customizer' => 'http://demo3.drfuri.com/soo-importer/supro/home-parallax/customizer.dat',
			'widgets'    => 'http://demo3.drfuri.com/soo-importer/supro/home-parallax/widgets.wie',
			'sliders'    => 'http://demo3.drfuri.com/soo-importer/supro/home-parallax/sliders.zip',
			'pages'      => array(
				'front_page' => 'Home Parallax',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 400,
					'height' => 400,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Shoppable Image',
			'preview'    => 'http://demo3.drfuri.com/soo-importer/supro/home-shoppable-image/preview.jpg',
			'content'    => 'http://demo3.drfuri.com/soo-importer/supro/home-shoppable-image/demo-content.xml',
			'customizer' => 'http://demo3.drfuri.com/soo-importer/supro/home-shoppable-image/customizer.dat',
			'widgets'    => 'http://demo3.drfuri.com/soo-importer/supro/home-shoppable-image/widgets.wie',
			'pages'      => array(
				'front_page' => 'Home Shoppable Image',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 400,
					'height' => 400,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Boxed',
			'preview'    => 'http://demo3.drfuri.com/soo-importer/supro/home-boxed/preview.jpg',
			'content'    => 'http://demo3.drfuri.com/soo-importer/supro/home-boxed/demo-content.xml',
			'customizer' => 'http://demo3.drfuri.com/soo-importer/supro/home-boxed/customizer.dat',
			'widgets'    => 'http://demo3.drfuri.com/soo-importer/supro/home-boxed/widgets.wie',
			'sliders'    => 'http://demo3.drfuri.com/soo-importer/supro/home-boxed/sliders.zip',
			'pages'      => array(
				'front_page' => 'Home Boxed',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 400,
					'height' => 400,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Simple',
			'preview'    => 'http://demo3.drfuri.com/soo-importer/supro/home-simple/preview.jpg',
			'content'    => 'http://demo3.drfuri.com/soo-importer/supro/home-simple/demo-content.xml',
			'customizer' => 'http://demo3.drfuri.com/soo-importer/supro/home-simple/customizer.dat',
			'widgets'    => 'http://demo3.drfuri.com/soo-importer/supro/home-simple/widgets.wie',
			'sliders'    => 'http://demo3.drfuri.com/soo-importer/supro/home-simple/sliders.zip',
			'pages'      => array(
				'front_page' => 'Home Simple',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 400,
					'height' => 400,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
	);
}

add_filter( 'soo_demo_packages', 'supro_vc_addons_importer', 20 );
