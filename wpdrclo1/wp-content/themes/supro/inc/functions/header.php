<?php
/**
 * Custom functions for header.
 *
 * @package Supro
 */

/**
 * Get nav menu
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'supro_nav_menu' ) ) :
	function supro_nav_menu() {
		$color   = supro_get_option( 'menu_hover_color' );
		$class   = array( 'menu', $color );
		$classes = implode( ' ', $class );

		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'walker'         => new Supro_Mega_Menu_Walker(),
					'menu_class'     => $classes,
				)
			);
		}
	}
endif;

/**
 * Get Menu extra cart
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'supro_extra_cart' ) ) :
	function supro_extra_cart() {
		$extras = supro_get_menu_extras();

		if ( empty( $extras ) || ! in_array( 'cart', $extras ) ) {
			return '';
		}

		if ( ! function_exists( 'woocommerce_mini_cart' ) ) {
			return '';
		}
		global $woocommerce;

		$icon_cart = '<i class="t-icon icon-cart"></i>';
		$icon_cart = apply_filters( 'supro_icon_cart', $icon_cart );

		$cart_html = esc_html( get_post_meta( get_the_ID(), 'header_cart_text', true ) );

		$cart_html = $cart_html ? $cart_html : esc_html__( 'Shopping Cart', 'supro' );

		printf(
			'<li class="menu-item-cart extra-menu-item">
				<a class="cart-contents" id="icon-cart-contents" href="%s">
					%s
					<span class="label-item cart-label">%s</span>
					<span class="mini-cart-counter">%s</span>
				</a>
			</li>',
			esc_url( wc_get_cart_url() ),
			wp_kses_post( $icon_cart ),
			wp_kses( $cart_html, wp_kses_allowed_html( 'post' ) ),
			intval( $woocommerce->cart->cart_contents_count )
		);

	}
endif;

/**
 * Get Menu extra search
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'supro_extra_search' ) ) :
	function supro_extra_search() {
		$extras = supro_get_menu_extras();
		$layout = supro_get_option( 'header_layout' );

		if ( empty( $extras ) ) {
			return;
		}

		if ( ! in_array( 'search', $extras ) ) {
			return;
		}

		$items          = '<a href="#" class="menu-extra-search"><i class="t-icon icon-magnifier"></i></a>';
		$css_class      = '';
		$post_type_html = '';

		if ( supro_get_option( 'search_content_type' ) == 'products' && function_exists( 'is_woocommerce' ) ) {
			$post_type_html = '<input type="hidden" name="post_type" value="product">';
		}

		if ( $layout != '3' && $layout != '5' && $layout != '6' ) {
			$css_class = 'search-modal';
		}

		$search_text = apply_filters( 'supro_search_text', esc_html__( 'Start Searching', 'supro' ) );

		$items .= sprintf(
			'<form method="get" class="instance-search" action="%s">' .
			'<input type="text" name="s" placeholder="%s..." class="search-field" autocomplete="off">' .
			'%s' .
			'<i class="t-icon icon-magnifier"></i>' .
			'</form>' .
			'<div class="loading">' .
			'<span class="supro-loader"></span>' .
			'</div>' .
			'<div class="search-results">' .
			'<div class="woocommerce"></div>' .
			'</div>',
			esc_url( home_url( '/' ) ),
			$search_text,
			$post_type_html
		);

		echo sprintf(
			'<li class="extra-menu-item menu-item-search %s">%s</li>',
			esc_attr( $css_class ),
			$items
		);

	}

endif;

/**
 * Get Menu extra Account
 *
 * @since  1.0.0
 *
 * @return string
 */
if ( ! function_exists( 'supro_extra_account' ) ) :
	function supro_extra_account() {
		$items  = '';
		if ( ! class_exists( 'WooCommerce' ) ) {
			return $items;
		}

		$extras = supro_get_menu_extras();


		if ( empty( $extras ) || ! in_array( 'account', $extras ) ) {
			return $items;
		}

		$wishlist = '';
		if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
			$wishlist = sprintf(
				'<li>
					<a href="%s">%s</a>
				</li>',
				esc_url( get_permalink( get_option( 'yith_wcwl_wishlist_page_id' ) ) ),
				esc_html__( 'My Wishlist', 'supro' )
			);
		}

		$login_id = '';

		if ( ! is_user_logged_in() ) {
			$login_id = 'menu-extra-login';
		}

		$acc_label = esc_html( get_post_meta( get_the_ID(), 'header_account_text', true ) );

		$acc_label = $acc_label ? $acc_label : esc_html__( 'My Account', 'supro' );

		$icon_acc = '<i class="t-icon icon-user"></i>';
		$icon_acc = apply_filters( 'supro_icon_account', $icon_acc );

		$acc_html = sprintf(
			'<a id="%s" href="%s">%s<span class="label-item acc-label">%s</span></a>',
			esc_attr( $login_id ),
			esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ),
			$icon_acc,
			esc_html( $acc_label )
		);

		if ( is_user_logged_in() ) {
			$orders  = get_option( 'woocommerce_myaccount_orders_endpoint', 'orders' );
			$account = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );

			if ( $orders ) {
				$account .= $orders;
			}

			if ( has_nav_menu( 'user_logged' ) ) {
				$user_menu = wp_nav_menu(
					array(
						'theme_location' => 'user_logged',
						'container'      => false,
						'echo'           => 0,
					)
				);
			} else {
				$user_menu = sprintf(
					'<ul>
						%s
						<li>
							<a href="%s">%s</a>
						</li>
						<li>
							<a href="%s">%s</a>
						</li>
					</ul>',
					$wishlist,
					esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ),
					esc_html__( 'Account Settings', 'supro' ),
					esc_url( $account ),
					esc_html__( 'Orders History', 'supro' )
				);
			}

			echo sprintf(
				'<li class="extra-menu-item menu-item-account logined">
					%s
					<div class="wrapper dropdown-submenu">
						%s
						<ul>
							<li>
								<a href="%s">%s</a>
							</li>
						</ul>
					</div>
				</li>',
				$acc_html,
				$user_menu,
				esc_url( wp_logout_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) ),
				esc_html__( 'Logout', 'supro' )
			);
		} else {

			echo sprintf(
				'<li class="extra-menu-item menu-item-account">
					%s
				</li>',
				$acc_html
			);
		}
	}

endif;

/**
 * Get Menu extra WishList
 *
 * @since  1.0.0
 *
 * @return string
 */
if ( ! function_exists( 'supro_extra_wishlist' ) ) :
	function supro_extra_wishlist() {
		$extras = supro_get_menu_extras();
		$items  = '';

		if ( empty( $extras ) || ! in_array( 'wishlist', $extras ) ) {
			return $items;
		}

		if ( ! function_exists( 'YITH_WCWL' ) ) {
			return '';
		}

		$wishlist_label = esc_html( get_post_meta( get_the_ID(), 'header_wishlist_text', true ) );

		$wishlist_label = $wishlist_label ? $wishlist_label : esc_html__( 'My Wishlist', 'supro' );

		$icon_wishlist = '<i class="t-icon icon-heart"></i>';
		$icon_wishlist = apply_filters( 'supro_icon_wishlist', $icon_wishlist );

		if ( ! shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
			return '';
		}

		echo sprintf(
			'<li class="extra-menu-item menu-item-wishlist">
					<a href="%s">
					%s
					<span class="label-item wishlist-label">%s</span>
					</a>
				</li>',
			esc_url( get_permalink( get_option( 'yith_wcwl_wishlist_page_id' ) ) ),
			$icon_wishlist,
			$wishlist_label
		);

	}

endif;


/**
 * Get Menu extra sidebar
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'supro_extra_sidebar' ) ) :
	function supro_extra_sidebar() {
		$extras = supro_get_menu_extras();

		if ( empty( $extras ) || ! in_array( 'sidebar', $extras ) ) {
			return '';
		}

		$icon  = '<i class="t-icon icon-menu"></i>';
		$menu_class = '';
		$icon  = apply_filters( 'supro_icon_menu', $icon );

		$menu_text = '';

		if ( ! is_page_template( 'template-home-left-sidebar.php' ) ) {
			$menu_text = esc_html( supro_get_option( 'header_menu_text' ) );
			if ( ! empty( $menu_text ) ) {
				$icon = '<span class="hidden-xs">' . $menu_text . '</span>' . $icon;
			}
		}

		printf(
			'<li class="extra-menu-item menu-item-sidebar %s hidden-md hidden-sm hidden-xs">
				<a class="menu-sidebar" id="icon-menu-sidebar" href="#">
					%s
				</a>
			</li>',
			esc_attr( $menu_class ),
			wp_kses_post( $icon )
		);

	}

endif;

/**
 * Get Menu extra sidebar
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'supro_menu_mobile' ) ) :
	function supro_menu_mobile() {
		$has_menu_mobile = false;
		$sidebar         = 'mobile-menu-sidebar';
		if ( is_active_sidebar( $sidebar ) ) {
			$has_menu_mobile = true;
		} else {
			$sidebar = 'menu-sidebar';
			if ( is_active_sidebar( $sidebar ) ) {
				$has_menu_mobile = true;
			} else {
				if ( has_nav_menu( 'primary' ) ) {
					$has_menu_mobile = true;
				}
			}
		}

		if ( ! $has_menu_mobile ) {
			return;
		}

		?>
		<li class="extra-menu-item menu-item-sidebar hidden-lg">
			<a class="menu-sidebar" id="icon-menu-mobile" href="#">
				<i class="t-icon icon-menu"></i>
			</a>
		</li>

		<?php

	}

endif;

/**
 * Display socials in footer
 *
 * @since 1.0.0
 */

if ( ! function_exists( 'supro_get_socials_html' ) ) :
	function supro_get_socials_html( $socials_options, $display = 'icon' ) {

		if ( ! $socials_options ) {
			return;
		}

		$socials = supro_get_socials();

		if ( $socials_options ) {

			printf( '<div class="socials">' );
			foreach ( $socials_options as $social ) {
				foreach ( $socials as $name => $label ) {
					if ( isset( $social['link_url'] ) ) {
						$link_url = $social['link_url'];
					} else {
						$link_url = $social;
					}

					if ( preg_match( '/' . $name . '/', $link_url ) ) {

						if ( $display == 'name' ) {
							if ( preg_match( '/' . $name . '/', $link_url ) ) {
								printf( '<a class="share-social share-%s" href="%s" target="_blank">%s</a>', esc_attr( $name ), esc_url( $link_url ), strtoupper( $name ) );
							}

						} else {
							if ( $name == 'google' ) {
								$name = 'googleplus';
							}

							printf( '<a class="share-social share-%s" href="%s" target="_blank"><i class="social social_%s"></i></a>', esc_attr( $name ), esc_url( $link_url ), esc_attr( $name ) );
						}

						break;

					}
				}
			}
		}
		printf( '</div>' );
	}

endif;

/**
 * Get Menu extra
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'supro_get_menu_extras' ) ) :
	function supro_get_menu_extras() {
		return supro_get_option( 'menu_extras' );
	}

endif;