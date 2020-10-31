<?php
/**
 * Hooks for template header
 *
 * @package Supro
 */

/**
 * Enqueue scripts and styles.
 *
 * @since 1.0
 */
function supro_enqueue_scripts() {
	/**
	 * Register and enqueue styles
	 */
	wp_register_style( 'supro-fonts', supro_fonts_url(), array(), '20180307' );
	wp_register_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '3.3.7' );
	wp_register_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array(), '4.6.3' );
	wp_register_style( 'eleganticons', get_template_directory_uri() . '/css/eleganticons.min.css', array(), '1.0.0' );
	wp_register_style( 'linearicons', get_template_directory_uri() . '/css/linearicons.min.css', array(), '1.0.0' );
	wp_register_style( 'ionicons', get_template_directory_uri() . '/css/ionicons.min.css', array(), '2.0.0' );
	wp_register_style( 'photoswipe', get_template_directory_uri() . '/css/photoswipe.css', array(), '4.1.1' );

	wp_enqueue_style(
		'supro', get_template_directory_uri() . '/style.css', array(
		'supro-fonts',
		'bootstrap',
		'font-awesome',
		'eleganticons',
		'linearicons',
		'ionicons',
		'photoswipe',
	), '20161025'
	);

	wp_add_inline_style( 'supro', supro_get_inline_style() );

	/**
	 * Register and enqueue scripts
	 */
	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_script( 'html5shiv', get_template_directory_uri() . '/js/plugins/html5shiv.min.js', array(), '3.7.2' );
	wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'respond', get_template_directory_uri() . '/js/plugins/respond.min.js', array(), '1.4.2' );
	wp_script_add_data( 'respond', 'conditional', 'lt IE 9' );

	wp_register_script( 'photoswipe', get_template_directory_uri() . '/js/plugins/photoswipe.min.js', array(), '4.1.1', true );
	wp_register_script( 'photoswipe-ui', get_template_directory_uri() . '/js/plugins/photoswipe-ui.min.js', array( 'photoswipe' ), '4.1.1', true );

	$lightbox = 'no';
	if ( is_singular() ) {

		wp_enqueue_style( 'photoswipe' );
		wp_enqueue_script( 'photoswipe-ui' );

		$photoswipe_skin = 'photoswipe-default-skin';
		if ( wp_style_is( $photoswipe_skin, 'registered' ) && ! wp_style_is( $photoswipe_skin, 'enqueued' ) ) {
			wp_enqueue_style( $photoswipe_skin );
			$lightbox = 'yes';
		}
	}

	wp_register_script( 'slick', get_template_directory_uri() . '/js/plugins/slick.min.js', array( 'jquery' ), '1.6.0', true );
	wp_register_script( 'isotope', get_template_directory_uri() . '/js/plugins/isotope.pkgd.min.js', array( 'jquery' ), '2.2.2', true );
	wp_register_script( 'parallax', get_template_directory_uri() . '/js/plugins/jquery.parallax.min.js', array(), '1.0', true );
	wp_register_script( 'flipclock', get_template_directory_uri() . '/js/plugins/flipclock.min.js', array(), '1.0', true );
	wp_register_script( 'sticky-kit', get_template_directory_uri() . '/js/plugins/sticky-kit.min.js', array( 'jquery' ), '1.1.3', true );
	wp_register_script( 'tabs', get_template_directory_uri() . '/js/plugins/jquery.tabs.js', array(), '1.0', true );
	wp_register_script( 'notify', get_template_directory_uri() . '/js/plugins/notify.min.js', array(), '1.0.0', true );
	wp_register_script( 'viewport', get_template_directory_uri() . '/js/plugins/isInViewport.min.js', array(), '1.0', true );
	wp_register_script( 'nprogress', get_template_directory_uri() . '/js/plugins/nprogress.js', array(), '1.0.0', true );
	wp_register_script( 'swiper', get_template_directory_uri() . '/js/plugins/swiper.min.js', array(), '4.3.2', true );

	$script_name = 'wc-add-to-cart-variation';
	if ( wp_script_is( $script_name, 'registered' ) && ! wp_script_is( $script_name, 'enqueued' ) ) {
		wp_enqueue_script( $script_name );
	}

	wp_enqueue_script(
		'supro', get_template_directory_uri() . "/js/scripts$min.js", array(
		'jquery',
		'jquery-ui-tooltip',
		'slick',
		'imagesloaded',
		'isotope',
		'parallax',
		'flipclock',
		'sticky-kit',
		'tabs',
		'notify',
		'viewport',
		'swiper',
		'nprogress'
	), '20180307', true
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	$product_thumb_slider     = 0;
	$product_thumb_vertical   = 0;
	$product_gallery_carousel = 0;
	if ( in_array( supro_get_option( 'single_product_layout' ), array( '1', '2' ) ) ) {
		$product_thumb_slider = 1;

		if ( supro_get_option( 'single_product_layout' ) == '2' ) {
			$product_thumb_vertical = 1;
		}
	} elseif ( in_array( supro_get_option( 'single_product_layout' ), array( '5', '6' ) ) ) {
		$product_gallery_carousel = 1;
	}

	$supro_data = [
		'ajax_url'              => admin_url( 'admin-ajax.php' ),
		'nonce'                 => wp_create_nonce( '_supro_nonce' ),
		'menu_animation'        => supro_get_option( 'menu_animation' ),
		'ajax_search'           => intval( supro_get_option( 'header_ajax_search' ) ),
		'search_content_type'   => supro_get_option( 'search_content_type' ),
		'shop_nav_type'         => supro_get_option( 'shop_nav_type' ),
		'add_to_cart_ajax'      => intval( supro_get_option( 'product_add_to_cart_ajax' ) ),
		'add_to_cart_action'    => supro_get_option( 'add_to_cart_action' ),
		'login_popup'           => intval( supro_get_option( 'my_account' ) ),
		'menu_mobile_behaviour' => supro_get_option( 'menu_mobile_behaviour' ),
		'product'               => array(
			'thumb_slider'     => $product_thumb_slider,
			'thumb_vertical'   => $product_thumb_vertical,
			'gallery_carousel' => $product_gallery_carousel,
			'lightbox'         => $lightbox,
		),
		'l10n'                  => array(
			'added_to_cart_notice'  => intval( supro_get_option( 'added_to_cart_notice' ) ),
			'notice_text'           => esc_html__( 'has been added to your cart.', 'supro' ),
			'notice_texts'          => esc_html__( 'have been added to your cart.', 'supro' ),
			'cart_text'             => esc_html__( 'View Cart', 'supro' ),
			'cart_link'             => function_exists( 'wc_get_cart_url' ) ? esc_url( wc_get_cart_url() ) : '',
			'cart_notice_auto_hide' => intval( supro_get_option( 'cart_notice_auto_hide' ) ) > 0 ? intval( supro_get_option( 'cart_notice_auto_hide' ) ) * 1000 : 0,
		),
		'isRTL'                 => is_rtl(),
	];

	if ( intval( supro_get_option( 'added_to_wishlist_notice' ) ) && defined( 'YITH_WCWL' ) ) {
		$supro_data['added_to_wishlist_notice'] = array(
			'added_to_wishlist_text'    => esc_html__( 'has been added to your wishlist.', 'supro' ),
			'added_to_wishlist_texts'   => esc_html__( 'have been added to your wishlist.', 'supro' ),
			'wishlist_view_text'        => esc_html__( 'View Wishlist', 'supro' ),
			'wishlist_view_link'        => esc_url( get_permalink( get_option( 'yith_wcwl_wishlist_page_id' ) ) ),
			'wishlist_notice_auto_hide' => intval( supro_get_option( 'wishlist_notice_auto_hide' ) ) > 0 ? intval( supro_get_option( 'wishlist_notice_auto_hide' ) ) * 1000 : 0,
		);
	}

	wp_localize_script( 'supro', 'suproData', $supro_data );
}

add_action( 'wp_enqueue_scripts', 'supro_enqueue_scripts' );

/**
 * Display header
 */
function supro_show_header() {
	$header_layout = supro_get_option( 'header_layout' );

	if ( is_page_template( 'template-home-left-sidebar.php' ) ) {
		get_template_part( 'parts/headers/header-left-sidebar' );
	} else {
		get_template_part( 'parts/headers/header', $header_layout );
	}
}

add_action( 'supro_header', 'supro_show_header' );

/**
 * Display topbar on top of site
 *
 * @since 1.0.0
 */
function supro_show_topbar() {
	if ( ! intval( supro_get_option( 'topbar_enable' ) ) ) {
		return;
	}

	if ( is_active_sidebar( 'topbar-left' ) == false &&
		is_active_sidebar( 'topbar-right' ) == false
	) {
		return;
	}

	$layout = supro_get_option( 'topbar_layout' );
	$border = intval( supro_get_option( 'topbar_border_bottom' ) );

	$class = 'topbar-layout-' . $layout;

	$class .= $border ? ' has-border' : '';

	$container = $layout == '1' ? 'container' : 'supro-container';

	?>
	<div id="topbar" class="topbar hidden-md hidden-sm hidden-xs <?php echo esc_attr( $class ); ?>">
		<div class="<?php echo esc_attr( $container ); ?>">
			<div class="row-flex">
				<?php if ( is_active_sidebar( 'topbar-left' ) ) : ?>

					<div class="topbar-left topbar-widgets text-left row-flex">
						<?php
						ob_start();
						dynamic_sidebar( 'topbar-left' );
						$output = ob_get_clean();

						echo apply_filters( 'supro_topbar_left', $output );
						?>
					</div>
				<?php endif; ?>

				<?php if ( is_active_sidebar( 'topbar-right' ) ) : ?>
					<div class="topbar-right topbar-widgets text-right row-flex">
						<?php
						ob_start();
						dynamic_sidebar( 'topbar-right' );
						$output = ob_get_clean();

						echo apply_filters( 'supro_topbar_right', $output );
						?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<?php
}

add_action( 'supro_before_header', 'supro_show_topbar' );

/**
 * Display topbar on top of site
 *
 * @since 1.0.0
 */
function supro_show_topbar_mobile() {
	if ( ! intval( supro_get_option( 'topbar_enable' ) ) ) {
		return;
	}

	if ( is_active_sidebar( 'topbar-mobile' ) == false ) {
		return;
	}

	$layout = supro_get_option( 'topbar_layout' );
	$border = intval( supro_get_option( 'topbar_border_bottom' ) );

	$class = 'topbar-layout-' . $layout;

	$class .= $border ? ' has-border' : '';

	$container = $layout == '1' ? 'container' : 'supro-container';

	$topbar_flex = supro_get_option( 'topbar_mobile_content' );

	$style_wrapper = 'justify-content:' . $topbar_flex . ';';

	?>
	<div class="topbar topbar-mobile hidden-lg <?php echo esc_attr( $class ); ?>">
		<div class="<?php echo esc_attr( $container ) ?>">
			<div class="topbar-widgets row-flex" style="<?php echo esc_attr( $style_wrapper ) ?>">
				<?php
				ob_start();
				dynamic_sidebar( 'topbar-mobile' );
				$output = ob_get_clean();

				echo apply_filters( 'supro_topbar_mobile', $output );
				?>
			</div>
		</div>
	</div>
	<?php
}

add_action( 'supro_before_header', 'supro_show_topbar_mobile' );

/**
 * Display the header minimized
 *
 * @since 1.0.0
 */
function supro_header_minimized() {
	if ( supro_get_option( 'header_sticky' ) == false ) {
		return;
	}

	if ( is_page_template( 'template-home-left-sidebar.php' ) ) {
		return;
	}

	if ( supro_is_maintenance_page() ) {
		return;
	}

	$css_class = 'su-header-' . supro_get_option( 'header_layout' );

	printf( '<div id="su-header-minimized" class="su-header-minimized %s"></div>', esc_attr( $css_class ) );

}

add_action( 'supro_before_header', 'supro_header_minimized' );

/**
 * Show page header
 *
 * @since 1.0.0
 */
function supro_show_page_header() {
	$page_header = supro_get_page_header();

	if ( ! $page_header ) {
		return;
	}

	$layout = 1;

	if ( $page_header && isset( $page_header['layout'] ) ) {
		$layout = $page_header['layout'];
	}

	if ( supro_is_blog() || ( is_search() && intval( supro_get_option( 'search_layout_inherit' ) ) ) ) {
		get_template_part( 'parts/page-headers/blog', $layout );
	} elseif ( supro_is_portfolio() ) {
		get_template_part( 'parts/page-headers/portfolio' );
	} else {
		get_template_part( 'parts/page-headers/default' );
	}
}

add_action( 'supro_after_header', 'supro_show_page_header', 20 );


