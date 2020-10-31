<?php
/**
 * Custom functions that act in the footer.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Supro
 */

/**
 * Show footer
 */

if ( ! function_exists( 'supro_show_footer' ) ) :
	function supro_show_footer() {
		$footer_layout = 1;
		get_template_part( 'parts/footers/layout', $footer_layout );
	}

endif;

add_action( 'supro_footer', 'supro_show_footer', 20 );

/**
 *  Display footer newsletter
 */
function supro_footer_newsletter() {
	if ( ! intval( supro_get_option( 'footer_newsletter' ) ) ) {
		return;
	}

	if ( intval( supro_get_option( 'footer_newsletter_home' ) ) && ! is_front_page() ) {
		return;
	}

	if ( is_page_template( 'template-home-no-footer.php' ) ) {
		return;
	}

	if ( ! supro_get_option( 'newsletter_form' ) ) {
		return;
	}

	if ( supro_is_maintenance_page() ) {
		return;
	}

	$style = supro_get_option( 'newsletter_style' );
	$shape = supro_get_option( 'newsletter_shape' );
	$title = supro_get_option( 'newsletter_title' );
	$desc  = supro_get_option( 'newsletter_desc' );
	$bg    = supro_get_option( 'newsletter_background_color' );
	$form  = do_shortcode( wp_kses( supro_get_option( 'newsletter_form' ), wp_kses_allowed_html( 'post' ) ) );

	if ( $style == 'space-between' ) {
		$title_col = 'col-md-5 col-xs-12 col-sm-12';
		$form_col  = 'col-md-7 col-xs-12 col-sm-12';
	} else {
		$title_col = $form_col = 'col-md-12 col-xs-12 col-sm-12';
	}

	$classes = array(
		'footer-newsletter supro-newsletter',
		$style . '-style',
		'form-' . $shape,
		$bg ? 'has-bg' : ''
	);

	?>
    <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
        <div class="container">
            <div class="row form-row">
                <div class="title-area <?php echo esc_attr( $title_col ); ?>">
					<?php
					if ( $title ) {
						echo sprintf( '<h2 class="title">%s</h2>', $title );
					}
					?>

					<?php
					if ( $desc ) {
						echo sprintf( '<div class="subtitle">%s</div>', $desc );
					}
					?>
                </div>
                <div class="form-area <?php echo esc_attr( $form_col ); ?>">
					<?php echo '' . $form; ?>
                </div>
            </div>
        </div>
    </div>
	<?php
}

add_action( 'supro_before_footer', 'supro_footer_newsletter', 10 );

/**
 *  Display Newsletter Form on Home Full Slider
 */

function supro_footer_newsletter_full_slider() {

	if ( ! is_page_template( 'template-home-no-footer.php' ) ) {
		return;
	}

	if ( intval( get_post_meta( get_the_ID(), 'hide_newsletter', true ) ) ) {
		return;
	}

	if ( ! get_post_meta( get_the_ID(), 'form', true ) ) {
		return;
	}

	$title = get_post_meta( get_the_ID(), 'form_title', true );
	$desc  = get_post_meta( get_the_ID(), 'form_subtitle', true );

	?>
    <div class="footer-newsletter">
        <div class="container form-row">
            <div class="title-area">
				<?php
				if ( $title ) {
					echo sprintf( '<h2 class="title">%s</h2>', $title );
				}
				?>

				<?php
				if ( $desc ) {
					echo sprintf( '<div class="subtitle">%s</div>', $desc );
				}
				?>
            </div>
            <div class="form-area">
				<?php echo do_shortcode( wp_kses( get_post_meta( get_the_ID(), 'form', true ), wp_kses_allowed_html( 'post' ) ) ); ?>
            </div>
        </div>
    </div>
	<?php
}

add_action( 'supro_before_footer', 'supro_footer_newsletter_full_slider', 20 );

/**
 * Display back to top
 *
 * @since 1.0.0
 */
function supro_back_to_top() {
	if ( ! intval( supro_get_option( 'back_to_top' ) ) ) {
		return;
	}
	?>
    <a id="scroll-top" class="backtotop" href="#">
        <i class="icon-arrow-up"></i>
    </a>
	<?php
}

add_action( 'wp_footer', 'supro_back_to_top' );

/**
 * Adds photoSwipe dialog element
 */
function supro_gallery_images_lightbox() {

	if ( ! is_singular() ) {
		return;
	}

	?>
    <div id="pswp" class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

        <div class="pswp__bg"></div>

        <div class="pswp__scroll-wrap">

            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>

            <div class="pswp__ui pswp__ui--hidden">

                <div class="pswp__top-bar">


                    <div class="pswp__counter"></div>

                    <button class="pswp__button pswp__button--close"
                            title="<?php esc_attr_e( 'Close (Esc)', 'supro' ) ?>"></button>

                    <button class="pswp__button pswp__button--share"
                            title="<?php esc_attr_e( 'Share', 'supro' ) ?>"></button>

                    <button class="pswp__button pswp__button--fs"
                            title="<?php esc_attr_e( 'Toggle fullscreen', 'supro' ) ?>"></button>

                    <button class="pswp__button pswp__button--zoom"
                            title="<?php esc_attr_e( 'Zoom in/out', 'supro' ) ?>"></button>

                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>

                <button class="pswp__button pswp__button--arrow--left"
                        title="<?php esc_attr_e( 'Previous (arrow left)', 'supro' ) ?>">
                </button>

                <button class="pswp__button pswp__button--arrow--right"
                        title="<?php esc_attr_e( 'Next (arrow right)', 'supro' ) ?>">
                </button>

                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>

            </div>

        </div>

    </div>
	<?php
}

add_action( 'wp_footer', 'supro_gallery_images_lightbox' );


/**
 * Adds preloader container at the bottom of the site
 */
function supro_preloader() {
	if ( ! intval( supro_get_option( 'preloader' ) ) ) {
		return;
	}

	get_template_part( 'parts/preloader' );
}

add_action( 'supro_after_footer', 'supro_preloader' );

/**
 * Add off canvas shopping cart to footer
 *
 * @since 1.0.0
 */

if ( ! function_exists( 'supro_off_canvas_menu_sidebar' ) ) :
	function supro_off_canvas_menu_sidebar() {

		?>
        <div id="menu-sidebar-panel" class="menu-sidebar supro-off-canvas-panel">
            <div class="widget-canvas-content">
                <div class="widget-panel-header">
                    <a href="#" class="close-canvas-panel"><span aria-hidden="true" class="icon-cross2"></span></a>
                </div>
                <div class="widget-panel-content hidden-md hidden-sm hidden-xs">
					<?php
					$sidebar = 'menu-sidebar';
					if ( is_active_sidebar( $sidebar ) ) {
						dynamic_sidebar( $sidebar );
					}
					?>
                </div>

                <div class="widget-panel-content hidden-lg">
					<?php
					$sidebar = 'mobile-menu-sidebar';
                    if ( ! is_active_sidebar( $sidebar ) ) {
	                    $sidebar = 'menu-sidebar';
                    }
					if ( is_active_sidebar( $sidebar ) ) {
						dynamic_sidebar( $sidebar );
					} else {
						supro_nav_menu();
					}
					?>
                </div>
                <div class="widget-panel-footer">
                </div>
            </div>
        </div>
		<?php
	}

endif;

add_action( 'wp_footer', 'supro_off_canvas_menu_sidebar' );

/**
 * Display a layer to close canvas panel everywhere inside page
 *
 * @since 1.0.0
 */

if ( ! function_exists( 'supro_site_canvas_layer' ) ) :
	function supro_site_canvas_layer() {
		?>
        <div id="off-canvas-layer" class="supro-off-canvas-layer"></div>
		<?php
	}

endif;

add_action( 'wp_footer', 'supro_site_canvas_layer' );

/**
 * Add off canvas shopping cart to footer
 *
 * @since 1.0.0
 */

if ( ! function_exists( 'supro_off_canvas_cart' ) ) :
	function supro_off_canvas_cart() {

		if ( ! function_exists( 'woocommerce_mini_cart' ) ) {
			return;
		}

		$extras = supro_get_menu_extras();

		if ( empty( $extras ) || ! in_array( 'cart', $extras ) ) {
			return '';
		}

		?>
        <div id="cart-panel" class="cart-panel woocommerce mini-cart supro-off-canvas-panel">
            <div class="widget-canvas-content">
                <div class="widget-cart-header  widget-panel-header">
                    <a href="#" class="close-canvas-panel"><span aria-hidden="true" class="icon-cross2"></span></a>
                </div>
                <div class="widget_shopping_cart_content">
					<?php woocommerce_mini_cart(); ?>
                </div>
            </div>
            <div class="mini-cart-loading"><span class="supro-loader"></span></div>
        </div>
		<?php
	}

endif;

add_action( 'wp_footer', 'supro_off_canvas_cart' );

/**
 * Add search modal to footer
 */
if ( ! function_exists( 'supro_search_modal' ) ) :
	function supro_search_modal() {

		?>
        <div id="search-modal" class="search-modal supro-modal" tabindex="-1" role="dialog">
            <div class="modal-content">
                <h2 class="modal-title"><?php esc_html_e( 'Search', 'supro' ); ?></h2>

                <div class="container">
                    <form method="get" class="instance-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php
						$number = apply_filters( 'supro_product_cats_search_number', 4 );
						$cats   = '';
						if ( supro_get_option( 'search_content_type' ) == 'products' ) {
							$args = array(
								'number'       => $number,
								'orderby'      => 'count',
								'order'        => 'desc',
								'hierarchical' => false,
								'taxonomy'     => 'product_cat',
							);
							$cats = get_terms( $args );
						}
						?>
						<?php if ( $cats && ! is_wp_error( $cats ) ) : ?>
                            <div class="product-cats">
                                <label>
                                    <input type="radio" name="product_cat" value="" checked="checked">
                                    <span class="line-hover"><?php esc_html_e( 'All', 'supro' ) ?></span>
                                </label>

								<?php foreach ( $cats as $cat ) : ?>
                                    <label>
                                        <input type="radio" name="product_cat"
                                               value="<?php echo esc_attr( $cat->slug ); ?>">
                                        <span class="line-hover"><?php echo esc_html( $cat->name ); ?></span>
                                    </label>
								<?php endforeach; ?>
                            </div>
						<?php endif; ?>

                        <div class="search-fields">
                            <input type="text" name="s" placeholder="<?php esc_attr_e( 'Search', 'supro' ); ?>"
                                   class="search-field" autocomplete="off">
							<?php if ( supro_get_option( 'search_content_type' ) == 'products' ) { ?>
                                <input type="hidden" name="post_type" value="product">
							<?php } ?>
                            <input type="submit" class="btn-submit">
                            <span class="search-submit">
						</span>
                        </div>
                    </form>

                    <div class="search-results">
                        <div class="text-center loading">
                            <span class="supro-loader"></span>
                        </div>
                        <div class="woocommerce"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="close-modal">
                    <span class="hidden-md hidden-sm hidden-xs"><?php esc_html_e( 'Close', 'supro' ) ?></span>
                    <i class="hidden-lg icon-cross"></i>
                </a>
            </div>
        </div>
		<?php
	}

endif;

add_action( 'wp_footer', 'supro_search_modal' );

/**
 * Adds quick view modal to footer
 */
if ( ! function_exists( 'supro_quick_view_modal' ) ) :
	function supro_quick_view_modal() {

		?>

        <div id="quick-view-modal" class="quick-view-modal supro-modal woocommerce" tabindex="-1" role="dialog">
            <div class="modal-header">
                <a href="#" class="close-modal">
                    <i class="icon-cross"></i>
                </a>
            </div>

            <div class="modal-content">
                <div class="container">
                    <div class="supro-product-content">
                        <div class="product">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12 product-images-wrapper">
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12  product-summary">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="supro-loader"></div>
        </div>

		<?php
	}

endif;

add_action( 'wp_footer', 'supro_quick_view_modal' );


/**
 * Add login modal to footer
 */

if ( ! function_exists( 'supro_login_modal' ) ) :
	function supro_login_modal() {

		if ( ! shortcode_exists( 'woocommerce_my_account' ) ) {
			return;
		}

		if ( is_user_logged_in() ) {
			return;
		}

		if ( intval( supro_get_option( 'my_account' ) ) ) {
			return;
		}

		?>

        <div id="login-modal" class="login-modal supro-modal woocommerce-account" tabindex="-1" role="dialog">
            <div class="modal-content">
                <div class="container">
					<?php echo do_shortcode( '[woocommerce_my_account]' ) ?>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="close-modal"><?php esc_html_e( 'Close', 'supro' ) ?></a>
            </div>
        </div>

		<?php
	}

endif;

add_action( 'wp_footer', 'supro_login_modal' );

/**
 * Add newsletter modal to footer
 */

if ( ! function_exists( 'supro_newsletter_modal' ) ) :
	function supro_newsletter_modal() {
		if ( ! is_page_template( 'template-home-no-footer.php' ) ) {
			return;
		}

		if ( intval( get_post_meta( get_the_ID(), 'hide_newsletter', true ) ) ) {
			return;
		}

		if ( ! get_post_meta( get_the_ID(), 'form', true ) ) {
			return;
		}

		?>
        <span id="supro-newsletter-icon" class="newsletter-icon icon-paper-plane hidden-lg"></span>
        <div id="footer-newsletter-modal" class="footer-newsletter-modal supro-modal">
            <div class="form-wrapper-modal">
				<?php supro_footer_newsletter_full_slider(); ?>
            </div>

            <a href="#" class="close-modal">
                <span><?php esc_html_e( 'Close', 'supro' ) ?></span>
                <i class="icon-cross"></i>
            </a>
        </div>
		<?php
	}
endif;

add_action( 'wp_footer', 'supro_newsletter_modal' );

function mobile_catalog_sorting_popup() {
	$elements = supro_get_option( 'shop_toolbar' );

	if ( empty( $elements ) ) {
		return;
	}

	if ( ! in_array( 'sort_by', $elements ) ) {
		return;
	}

	echo '<div class="supro-catalog-sorting-mobile" id="supro-catalog-sorting-mobile">';

	if ( function_exists( 'woocommerce_catalog_ordering' ) ) {
		woocommerce_catalog_ordering();
	}

	echo '</div>';
}
add_action( 'wp_footer', 'mobile_catalog_sorting_popup' );

/**
 * Display recently viewed products in footer
 *
 * @since 1.0.0
 *
 *  return string
 */
if ( ! function_exists( 'supro_footer_recently_viewed' ) ) :
	function supro_footer_recently_viewed() {
		if ( ! function_exists( 'is_woocommerce' ) ) {
			return;
		}

		if ( is_page_template( 'template-home-no-footer.php' ) ) {
			return;
		}

		if ( is_404() || is_page_template( 'template-coming-soon-page.php' ) ) {
			return;
		}

		$recently_viewed = apply_filters( 'supro_footer_recently_viewed', supro_get_option( 'footer_recently_viewed' ) );
		if ( ! intval( $recently_viewed ) ) {
			return;
		}

		$els = apply_filters( 'supro_footer_recently_viewed_els', supro_get_option( 'footer_recently_viewed_els' ) );
		if ( empty( $els ) ) {
			return;
		}

		if ( supro_is_home() ) {
			if ( ! in_array( 'homepage', $els ) ) {
				return;
			}
		} elseif ( is_page() ) {
			if ( ! in_array( 'page', $els ) ) {
				return;
			}
		} elseif ( is_singular( 'post' ) ) {
			if ( ! in_array( 'post', $els ) ) {
				return;
			}
		} elseif ( supro_is_blog() ) {
			if ( ! in_array( 'post', $els ) ) {
				return;
			}
		} elseif ( supro_is_catalog() ) {
			if ( ! in_array( 'catalog', $els ) ) {
				return;
			}
		} elseif ( is_singular( 'product' ) ) {
			if ( ! in_array( 'single_product', $els ) ) {
				return;
			}
		} else {
			if ( ! in_array( 'other', $els ) ) {
				return;
			}
		}

		$columns  = apply_filters( 'supro_footer_recently_viewed_columns', 5 );

		echo '<div class="mf-recently-products footer-recently-viewed load-ajax" data-columns = ' . $columns . ' id="footer-recently-viewed"></div>';
	}
endif;

add_action( 'supro_before_footer', 'supro_footer_recently_viewed', 30 );