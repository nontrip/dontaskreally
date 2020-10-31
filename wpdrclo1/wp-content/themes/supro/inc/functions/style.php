<?php
/**
 * Functions of stylesheets and CSS
 *
 * @package Helendo
 */

if ( ! function_exists( 'supro_get_inline_style' ) ) :
	/**
	 * Get inline style data
	 */
	function supro_get_inline_style() {
		$css = '';

		$css .= supro_get_page_custom_css();
		$css .= supro_get_page_header_custom_css();
		$css .= supro_header_css();

		// Logo
		$logo_size_width = intval( supro_get_option( 'logo_width' ) );
		$logo_css        = $logo_size_width ? 'width:' . $logo_size_width . 'px; ' : '';

		$logo_size_height = intval( supro_get_option( 'logo_height' ) );
		$logo_css .= $logo_size_height ? 'height:' . $logo_size_height . 'px; ' : '';

		$logo_margin = supro_get_option( 'logo_position' );
		$logo_css .= $logo_margin['top'] ? 'margin-top:' . $logo_margin['top'] . '; ' : '';
		$logo_css .= $logo_margin['right'] ? 'margin-right:' . $logo_margin['right'] . '; ' : '';
		$logo_css .= $logo_margin['bottom'] ? 'margin-bottom:' . $logo_margin['bottom'] . '; ' : '';
		$logo_css .= $logo_margin['left'] ? 'margin-left:' . $logo_margin['left'] . '; ' : '';

		if ( ! empty( $logo_css ) ) {
			$css .= '.site-header .logo img ' . ' {' . $logo_css . '}';
		}

		// Newsletter
		$n_color = supro_get_option( 'newsletter_text_color' );
		$n_bg    = supro_get_option( 'newsletter_background_color' );

		if ( $n_bg ) {
			$css .= '.footer-newsletter .mc4wp-form .mc4wp-form-fields { background-color:' . $n_bg . '; }';
		}

		if ( $n_color ) {
			$css .= '.footer-newsletter.supro-newsletter .mc4wp-form input[type="email"] { color:' . $n_color . '; }';
			$css .= '.footer-newsletter.supro-newsletter .mc4wp-form input[type="submit"] { color:' . $n_color . '; }';
			$css .= '.footer-newsletter.supro-newsletter .mc4wp-form .mc4wp-form-fields:after { color:' . $n_color . '; }';
			$css .= '.footer-newsletter.supro-newsletter .mc4wp-form ::-webkit-input-placeholder { color:' . $n_color . '; }';
			$css .= '.footer-newsletter.supro-newsletter .mc4wp-form :-moz-placeholder { color:' . $n_color . '; }';
			$css .= '.footer-newsletter.supro-newsletter .mc4wp-form ::-moz-placeholder { color:' . $n_color . '; }';
			$css .= '.footer-newsletter.supro-newsletter .mc4wp-form :-ms-input-placeholder { color:' . $n_color . '; }';
		}

		// Footer
		$footer_copyright_top_spacing    = supro_get_option( 'footer_copyright_top_spacing' );
		$footer_copyright_bottom_spacing = supro_get_option( 'footer_copyright_bottom_spacing' );
		$footer_copyright_css            = '';

		if ( $footer_copyright_top_spacing ) {
			$footer_copyright_css = 'padding-top:' . $footer_copyright_top_spacing . 'px;';
		}

		if ( $footer_copyright_bottom_spacing ) {
			$footer_copyright_css = 'padding-bottom:' . $footer_copyright_bottom_spacing . 'px;';
		}

		$css .= '.site-footer .footer-copyright {' . $footer_copyright_css . '}';

		// Single Product Background
		$single_product_bg = supro_get_option( 'single_product_background_color' );

		if ( $single_product_bg ) {
			$css .= '.woocommerce.single-product-layout-2 .site-header { background-color:' . $single_product_bg . '; }';
			$css .= '.woocommerce.single-product-layout-2 .product-toolbar { background-color:' . $single_product_bg . '; }';
			$css .= '.woocommerce.single-product-layout-2 div.product .supro-single-product-detail { background-color:' . $single_product_bg . '; }';
			$css .= '.woocommerce.single-product-layout-2 .su-header-minimized { background-color:' . $single_product_bg . '; }';
		}

		// Topbar
		$topbar_bg           = supro_get_option( 'topbar_background_color' );
		$topbar_color        = supro_get_option( 'topbar_color' );
		$topbar_custom_color = supro_get_option( 'topbar_custom_color' );

		if ( $topbar_bg ) {
			$css .= '.topbar { background-color:' . $topbar_bg . '; }';
		}

		if ( $topbar_color == 'custom' && $topbar_custom_color ) {
			$css .= '.topbar { color:' . $topbar_custom_color . '; }';
			$css .= '
		.topbar a,
		.topbar .widget_categories li a,
		.topbar .widget_categories li a:hover,
		.topbar .widget_recent_comments li a,
		.topbar .widget_recent_comments li a:hover,
		.topbar .widget_rss li a,
		.topbar .widget_rss li a:hover,
		.topbar .widget_pages li a,
		.topbar .widget_pages li a:hover,
		.topbar .widget_archive li a,
		.topbar .widget_archive li a:hover,
		.topbar .widget_nav_menu li a,
		.topbar .widget_nav_menu li a:hover,
		.topbar .widget_recent_entries li a,
		.topbar .widget_recent_entries li a:hover,
		.topbar .widget_meta li a,
		.topbar .widget_meta li a:hover,
		.topbar .widget-recent-comments li a,
		.topbar .widget-recent-comments li a:hover,
		.topbar .supro-social-links-widget .socials-list a,
		.topbar .supro-social-links-widget .socials-list a:hover,
		.topbar .widget_search .search-form:before,
		.topbar .widget_search .search-form label input { color:' . $topbar_custom_color . '; }
		';

			$css .= '.topbar .widget_search .search-form ::-webkit-input-placeholder { color:' . $topbar_custom_color . '; }';
			$css .= '.topbar .widget_search .search-form .mc4wp-form :-moz-placeholder { color:' . $topbar_custom_color . '; }';
			$css .= '.topbar .widget_search .search-form .mc4wp-form ::-moz-placeholder { color:' . $topbar_custom_color . '; }';
			$css .= '.topbar .widget_search .search-form .mc4wp-form :-ms-input-placeholder { color:' . $topbar_custom_color . '; }';

			$css .= '
		.topbar .widget_categories li a:after,
		.topbar .widget_recent_comments li a:after,
		.topbar .widget_rss li a:after,
		.topbar .widget_pages li a:after,
		.topbar .widget_archive li a:after,
		.topbar .widget_nav_menu li a:after,
		.topbar .widget_recent_entries li a:after,
		.topbar .widget_meta li a:after,
		.topbar .widget-recent-comments li a:after,
		.topbar .topbar-widgets .widget:after{ background-color:' . $topbar_custom_color . '; }
		';
		}

		$boxed_bg_color = supro_get_option( 'boxed_background_color' );
		$boxed_bg_image = supro_get_option( 'boxed_background_image' );
		$boxed_bg_h     = supro_get_option( 'boxed_background_horizontal' );
		$boxed_bg_v     = supro_get_option( 'boxed_background_vertical' );
		$boxed_bg_r     = supro_get_option( 'boxed_background_repeat' );
		$boxed_bg_a     = supro_get_option( 'boxed_background_attachment' );
		$boxed_bg_s     = supro_get_option( 'boxed_background_size' );

		$boxed_style = array(
			! empty( $boxed_bg_color ) ? 'background-color: ' . $boxed_bg_color . ';' : '',
			! empty( $boxed_bg_image ) ? 'background-image: url( ' . esc_url( $boxed_bg_image ) . ' );' : '',
			! empty( $boxed_bg_h ) ? 'background-position-x: ' . $boxed_bg_h . ';' : '',
			! empty( $boxed_bg_v ) ? 'background-position-y: ' . $boxed_bg_v . ';' : '',
			! empty( $boxed_bg_r ) ? 'background-repeat: ' . $boxed_bg_r . ';' : '',
			! empty( $boxed_bg_a ) ? 'background-attachment:' . $boxed_bg_a . ';' : '',
			! empty( $boxed_bg_s ) ? 'background-size: ' . $boxed_bg_s . ';' : '',
		);

		if ( ! empty( $boxed_style ) ) {
			$css .= '.supro-boxed-layout ' . ' {' . implode( '', $boxed_style ) . '}';
		}

		/* Color Scheme */
		$color_scheme_option = supro_get_option( 'color_scheme' );

		if ( intval( supro_get_option( 'custom_color_scheme' ) ) ) {
			$color_scheme_option = supro_get_option( 'custom_color' );
		}

		// Don't do anything if the default color scheme is selected.
		if ( $color_scheme_option ) {
			$css .= supro_get_color_scheme_css( $color_scheme_option );
		}

		/* Typography */
		$css .= supro_typography_css();

		// Badges
		$css .= supro_custom_badges_css();

		return apply_filters( 'supro_inline_style', $css );
	}
endif;

/**
 * Get Page Header Css
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'supro_get_page_header_custom_css' ) ) :
	function supro_get_page_header_custom_css() {
		$css = '';

		$page_header = supro_get_page_header();

		if ( empty( $page_header['selector'] ) ) {
			return $css;
		}

		/*=========================
		 * Handle */
		$custom_css = array();

		$styles = array(
			'desktop' => array(
				'background-image'      => $page_header['bg_image'],
				'background-position'   => $page_header['bg_position'],
				'background-size'       => $page_header['bg_size'],
				'background-repeat'     => $page_header['bg_repeat'],
				'background-attachment' => $page_header['bg_attachment']
			),
			'tablet'  => array(
				'background-image'      => $page_header['tablet']['bg_image'],
				'background-position'   => $page_header['tablet']['bg_position'],
				'background-size'       => $page_header['tablet']['bg_size'],
				'background-repeat'     => $page_header['tablet']['bg_repeat'],
				'background-attachment' => $page_header['tablet']['bg_attachment']
			),
			'mobile'  => array(
				'background-image'      => $page_header['mobile']['bg_image'],
				'background-position'   => $page_header['mobile']['bg_position'],
				'background-size'       => $page_header['mobile']['bg_size'],
				'background-repeat'     => $page_header['mobile']['bg_repeat'],
				'background-attachment' => $page_header['mobile']['bg_attachment']
			)
		);

		foreach ( $styles as $device => $attr ) {
			if ( $device == 'desktop' ) {
				foreach ( $attr as $key => $value ) {
					if ( $key == 'background-image' && $value ) {
						$value = 'url(' . esc_url( $value ) . ')';
					}

					if ( $value ) {
						$custom_css[] = $page_header['selector'] . " .feature-image { " . $key . " : " . $value . ";}";
					}
				}
			}

			if ( $device == 'tablet' ) {
				$custom_css[] = '@media( max-width: 1024px ){';

				foreach ( $attr as $key => $value ) {
					if ( $key == 'background-image' && $value ) {
						$value = 'url(' . esc_url( $value ) . ')';
					}

					if ( $value ) {
						$custom_css[] = $page_header['selector'] . " .feature-image { " . $key . " : " . $value . ";}";
					}
				}

				$custom_css[] = '}';
			}

			if ( $device == 'mobile' ) {
				$custom_css[] = '@media( max-width: 767px ){';

				foreach ( $attr as $key => $value ) {
					if ( $key == 'background-image' && $value ) {
						$value = 'url(' . esc_url( $value ) . ')';
					}

					if ( $value ) {
						$custom_css[] = $page_header['selector'] . " .feature-image { " . $key . " : " . $value . ";}";
					}
				}

				$custom_css[] = '}';
			}
		}

		$css .= implode( "\n", $custom_css );

		return $css;
	}

endif;

/**
 * Get Page Css
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'supro_get_page_custom_css' ) ) :
	function supro_get_page_custom_css() {
		$id            = get_post_meta( get_the_ID(), 'image', true );
		$bg_color      = get_post_meta( get_the_ID(), 'color', true );
		$bg_horizontal = get_post_meta( get_the_ID(), 'background_horizontal', true );
		$bg_vertical   = get_post_meta( get_the_ID(), 'background_vertical', true );
		$bg_repeat     = get_post_meta( get_the_ID(), 'background_repeat', true );
		$bg_attachment = get_post_meta( get_the_ID(), 'background_attachment', true );
		$bg_size       = get_post_meta( get_the_ID(), 'background_size', true );

		$url = wp_get_attachment_image_src( $id, 'full' );

		$class = '.page-id-' . get_the_ID();

		$bg_css = ! empty( $bg_color ) ? "background-color: {$bg_color};" : '';
		$bg_css .= ! empty( $url ) ? "background-image: url( " . esc_url( $url[0] ) . " );" : '';

		$bg_css .= ! empty( $bg_repeat ) ? "background-repeat: {$bg_repeat};" : '';

		if ( ! empty( $bg_horizontal ) || ! empty( $bg_vertical ) ) {
			$bg_css .= "background-position: {$bg_horizontal} {$bg_vertical};";
		}

		$bg_css .= ! empty( $bg_attachment ) ? "background-attachment: {$bg_attachment};" : '';

		$bg_css .= ! empty( $bg_size ) ? "background-size: {$bg_size};" : '';

		if ( $bg_css ) {
			$bg_css = $class . '{' . $bg_css . '}';
		}

		return $bg_css;
	}

endif;

/**
 * Get Header Css
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'supro_header_css' ) ) :
	function supro_header_css() {
		$css = '';

		// Header Color
		$header_color = supro_get_option( 'header_text_color' );
		$h_color      = '';

		if ( get_post_meta( get_the_ID(), 'custom_header', true ) ) {
			$header_color = get_post_meta( get_the_ID(), 'header_text_color', true );
		}

		if ( $header_color == 'custom' ) {
			$h_color = supro_get_option( 'header_text_custom_color' );

			if ( get_post_meta( get_the_ID(), 'custom_header', true ) ) {
				$h_color = get_post_meta( get_the_ID(), 'header_color', true );
			}
		}

		if ( $h_color ) {
			$css .= '.header-color-custom .nav ul.menu > li > a,
			 		.header-color-custom .menu-extra ul > li > a,
			 		.header-color-custom .site-header .menu-extra .menu-item-search .t-icon,
			 		.header-color-custom .site-header .socials a { color: ' . $h_color . '; }';
			$css .= '.header-color-custom .nav ul.menu > li > a:after { background-color: ' . $h_color . '; }';
		}

		// Menu Hover Custom Color
		$menu_color = supro_get_option( 'menu_hover_color' );
		$m_color    = '';

		if ( $menu_color == 'custom-color' ) {
			$m_color = supro_get_option( 'menu_hover_custom_color' );
		}

		if ( $m_color ) {
			$css .= '.nav ul.menu.custom-color > li:hover > a,
				.nav ul.menu.custom-color > li.current-menu-item > a,
				.nav ul.menu.custom-color > li.current_page_item > a,
				.nav ul.menu.custom-color > li.current-menu-ancestor > a,
				.nav ul.menu.custom-color > li.current-menu-parent > a,
				.nav ul.menu.custom-color > li.active > a { color: ' . $m_color . '; }';
			$css .= '.nav ul.menu.custom-color > li:hover > a:after,
				.nav ul.menu.custom-color > li.current-menu-item > a:after,
				.nav ul.menu.custom-color > li.current_page_item > a:after,
				.nav ul.menu.custom-color > li.current-menu-ancestor > a:after,
				.nav ul.menu.custom-color > li.current-menu-parent > a:after,
				.nav ul.menu.custom-color > li.active > a:after { background-color: ' . $m_color . '; }';
		}

		return $css;
	}

endif;

/**
 * Returns CSS for the color schemes.
 *
 *
 * @param array $colors Color scheme colors.
 *
 * @return string Color scheme CSS.
 */
function supro_get_color_scheme_css( $colors ) {
	return <<<CSS

	/* Background Color */

	.slick-dots li:hover,.slick-dots li.slick-active,
	.owl-nav div:hover,
	.owl-dots .owl-dot.active span,.owl-dots .owl-dot:hover span,
	#nprogress .bar,
	.primary-background-color,
	.site-header .menu-extra .menu-item-cart .mini-cart-counter,.site-header .menu-extra .menu-item-wishlist .mini-cart-counter,
	.nav ul.menu.primary-color > li:hover > a:after,.nav ul.menu.primary-color > li.current-menu-item > a:after,.nav ul.menu.primary-color > li.current_page_item > a:after,.nav ul.menu.primary-color > li.current-menu-ancestor > a:after,.nav ul.menu.primary-color > li.current-menu-parent > a:after,.nav ul.menu.primary-color > li.active > a:after,
	.woocommerce div.product div.images .product-gallery-control .item-icon span,
	.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
	span.mb-siwc-tag,
	.supro-products-grid.style-2 a.ajax-load-products .button-text,
	.supro-banner-grid.btn-style-2 .banner-btn,
	.supro-socials.socials-border a:hover,
	.supro-socials.socials-border span:hover,
	.footer-layout.dark-skin .supro-social-links-widget .socials-list.style-2 a:hover,
	.blog-page-header h1:after{background-color: $colors}

	/* Border Color */

	.slick-dots li,
	.owl-nav div:hover,
	.owl-dots .owl-dot span,
	.supro-social-links-widget .socials-list.style-2 a:hover,
	.supro-socials.socials-border a:hover,
	.supro-socials.socials-border span:hover
	{border-color: $colors}

	/* Color */
	.search-modal .product-cats label span:hover,
	.search-modal .product-cats input:checked + span,
	.search-modal .search-results ul li .search-item:hover .title,
	blockquote cite,
	blockquote cite a,
	.primary-color,
	.nav ul.menu.primary-color > li:hover > a,.nav ul.menu.primary-color > li.current-menu-item > a,.nav ul.menu.primary-color > li.current_page_item > a,.nav ul.menu.primary-color > li.current-menu-ancestor > a,.nav ul.menu.primary-color > li.current-menu-parent > a,.nav ul.menu.primary-color > li.active > a,
	.nav .menu .is-mega-menu .dropdown-submenu .menu-item-mega > a:hover,
	.blog-wrapper .entry-metas .entry-cat,
	.blog-wrapper.sticky .entry-title:before,
	.single-post .entry-cat,
	.supro-related-posts .blog-wrapper .entry-cat,
	.error404 .error-404 .page-content a,
	.error404 .error-404 .page-content .error-icon,
	.list-portfolio .portfolio-wrapper .entry-title:hover,
	.list-portfolio .portfolio-wrapper .entry-title:hover a,
	.single-portfolio-entry-meta .supro-social-share a:hover,
	.widget-about a:hover,
	.supro-social-links-widget .socials-list a:hover,
	.supro-social-links-widget .socials-list.style-2 a:hover,
	.supro-language-currency .widget-lan-cur ul li.actived a,
	.shop-widget-info .w-icon,
	.woocommerce ul.products li.product.product-category:hover .woocommerce-loop-category__title,.woocommerce ul.products li.product.product-category:hover .count,
	.woocommerce div.product div.images .product-gallery-control .item-icon:hover:before,
	.woocommerce-checkout table.shop_table .order-total .woocommerce-Price-amount,
	.woocommerce-account .woocommerce .woocommerce-Addresses .woocommerce-Address .woocommerce-Address-edit .edit:hover,
	.woocommerce-account .customer-login .form-row-password .lost-password,
	.supro-icons-box i,
	.supro-banner-grid-4 .banner-grid__banner .banner-grid__link:hover .banner-title,
	.supro-product-banner .banner-url:hover .title,
	.supro-product-banner3 .banner-wrapper:hover .banner-title,
	.supro-sale-product.style-2 .flip-clock-wrapper .flip-wrapper .inn,
	.supro-faq_group .g-title,
	.wpcf7-form .require{color: $colors}

	/* Other */
	.supro-loader:after,
	.supro-sliders:after,
	.supro-sliders:after,
	.woocommerce .blockUI.blockOverlay:after { border-color: $colors $colors $colors transparent }

	.woocommerce div.product div.images .product-gallery-control .item-icon span:before { border-color: transparent transparent transparent $colors; }

	.woocommerce.single-product-layout-6 div.product div.images .product-gallery-control .item-icon span:before { border-color: transparent $colors transparent transparent; }

	#nprogress .peg {
		-webkit-box-shadow: 0 0 10px $colors, 0 0 5px $colors;
			  box-shadow: 0 0 10px $colors, 0 0 5px $colors;
	}
CSS;
}

if ( ! function_exists( 'supro_typography_css' ) ) :
	/**
	 * Get typography CSS base on settings
	 *
	 * @since 1.1.6
	 */
	function supro_typography_css() {
		$css        = '';
		$properties = array(
			'font-family'    => 'font-family',
			'font-size'      => 'font-size',
			'variant'        => 'font-weight',
			'line-height'    => 'line-height',
			'letter-spacing' => 'letter-spacing',
			'color'          => 'color',
			'text-transform' => 'text-transform',
			'text-align'     => 'text-align',
		);

		$settings = array(
			'body_typo'        => 'body',
			'heading1_typo'    => 'h1',
			'heading2_typo'    => 'h2',
			'heading3_typo'    => 'h3',
			'heading4_typo'    => 'h4',
			'heading5_typo'    => 'h5',
			'heading6_typo'    => 'h6',
			'menu_typo'        => '.nav a, .nav .menu .is-mega-menu .dropdown-submenu .menu-item-mega > a',
			'sub_menu_typo'    => '.nav li li a, .supro-language-currency .widget-lan-cur ul li a',
			'footer_text_typo' => '.site-footer',
		);

		foreach ( $settings as $setting => $selector ) {
			$typography = supro_get_option( $setting );
			$default    = (array) supro_get_option_default( $setting );
			$style      = '';

			foreach ( $properties as $key => $property ) {
				if ( isset( $typography[$key] ) && ! empty( $typography[$key] ) ) {
					if ( isset( $default[$key] ) && strtoupper( $default[$key] ) == strtoupper( $typography[$key] ) ) {
						continue;
					}

					$value = $typography[$key];

					if ( 'font-family' == $key ) {
						if (
							trim( $typography[$key] ) != '' &
							trim( $typography[$key] ) != ',' &
							strtolower( $typography[$key] ) !== 'cerebri sans'
						) {
							$value = '"' . rtrim( trim( $typography[$key] ), ',' ) . '"';

							$style .= 'font-family:' . $value . ', Arial, sans-serif;';
						}
					} else {
						$value = 'variant' == $key ? str_replace( 'regular', '400', $value ) : $value;

						if ( $value ) {
							$style .= $property . ': ' . $value . ';';
						}
					}
				}
			}

			if ( ! empty( $style ) ) {
				$css .= $selector . '{' . $style . '}';
			}
		}

		return $css;
	}
endif;

// Custom Badges
/**
 * Get inline style
 */
if ( ! function_exists( 'supro_custom_badges_css' ) ) :
	function supro_custom_badges_css() {
		$custom_badge = supro_get_option( 'custom_badge' );

		$inline_css = '';
		if ( intval( $custom_badge ) ) {
			$color    = supro_get_option( 'custom_badge_color' );
			$bg_color = supro_get_option( 'custom_badge_bg_color' );

			if ( $bg_color ) {
				$inline_css .= '.woocommerce .ribbons .ribbon {background-color:' . $bg_color . ' !important;}';
			}

			if ( $color ) {
				$inline_css .= '.woocommerce .ribbons .ribbon {color:' . $color . ' !important;}';
			}

		} else {
			// Hot
			$hot_bg_color = supro_get_option( 'hot_bg_color' );
			$hot_color    = supro_get_option( 'hot_color' );
			if ( ! empty( $hot_bg_color ) ) {
				$inline_css .= '.woocommerce .ribbons .ribbon.featured {background-color:' . $hot_bg_color . '}';
			}

			if ( ! empty( $hot_color ) ) {
				$inline_css .= '.woocommerce .ribbons .ribbon.featured {color:' . $hot_color . '}';
			}

			// Out of stock
			$outofstock_bg_color = supro_get_option( 'outofstock_bg_color' );
			$outofstock_color    = supro_get_option( 'outofstock_color' );

			if ( ! empty( $outofstock_bg_color ) ) {
				$inline_css .= '.woocommerce .ribbons .ribbon.out-of-stock {background-color:' . $outofstock_bg_color . '}';
			}

			if ( ! empty( $outofstock_color ) ) {
				$inline_css .= '.woocommerce .ribbons .ribbon.out-of-stock {color:' . $outofstock_color . '}';
			}

			// New
			$new_bg_color = supro_get_option( 'new_bg_color' );
			$new_color    = supro_get_option( 'new_color' );

			if ( ! empty( $new_bg_color ) ) {
				$inline_css .= '.woocommerce .ribbons .ribbon {background-color:' . $new_bg_color . '}';
			}

			if ( ! empty( $new_color ) ) {
				$inline_css .= '.woocommerce .ribbons .ribbon {color:' . $new_color . '}';
			}

			// Sale
			$sale_bg_color = supro_get_option( 'sale_bg_color' );
			$sale_color    = supro_get_option( 'sale_color' );

			if ( ! empty( $sale_bg_color ) ) {
				$inline_css .= '.woocommerce .ribbons .ribbon.onsale {background-color:' . $sale_bg_color . '}';
			}

			if ( ! empty( $sale_color ) ) {
				$inline_css .= '.woocommerce .ribbons .ribbon.onsale {color:' . $sale_color . '}';
			}
		}

		return $inline_css;
	}

endif;