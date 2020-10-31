<?php
/**
 * Custom functions for entry.
 *
 * @package Supro
 */


/**
 * Prints HTML with meta information for the social share and tags.
 *
 * @since 1.0.0
 */
function supro_entry_footer() {
	if ( ! has_tag() && ! intval( supro_get_option( 'show_post_social_share' ) ) ) {
		return;
	}

	$layout = supro_get_option( 'single_post_layout' );
	$col    = 'col-md-12';

	if ( 'full-content' == $layout ) {
		$col = 'col-md-8 col-md-offset-2';
	}

	echo '<footer class="entry-footer">' .
		'<div class="row">' .
		'<div class="' . esc_attr( $col ) . ' col-xs-12 col-sm-12">' .
		'<div class="entry-footer-wrapper">';

	if ( has_tag() ) :
		the_tags( '<div class="tag-list"><span class="tag-title">' . esc_html__( 'Tags: ', 'supro' ) . '</span>', ', ', '</div>' );
	endif;

	if ( intval( supro_get_option( 'show_post_social_share' ) ) ) {
		echo '<div class="supro-single-post-socials-share">';
		echo supro_addons_share_link_socials( get_the_title(), get_the_permalink(), get_the_post_thumbnail_url() );
		echo '</div>';
	};

	echo '</div></div></div></footer>';

}


/**
 * Get or display limited words from given string.
 * Strips all tags and shortcodes from string.
 *
 * @since 1.0.0
 *
 * @param integer $num_words The maximum number of words
 * @param string  $more      More link.
 *
 * @return string|void Limited content.
 */
function supro_content_limit( $num_words, $more = "&hellip;" ) {
	$content = get_the_excerpt();

	// Strip tags and shortcodes so the content truncation count is done correctly
	$content = strip_tags(
		strip_shortcodes( $content ), apply_filters(
			'
	supro_content_limit_allowed_tags', '<script>,<style>'
		)
	);

	// Remove inline styles / scripts
	$content = trim( preg_replace( '#<(s(cript|tyle)).*?</\1>#si', '', $content ) );

	// Truncate $content to $max_char
	$content = wp_trim_words( $content, $num_words );

	if ( $more ) {
		$output = sprintf(
			'<p>%s <a href="%s" class="more-link" title="%s">%s</a></p>',
			$content,
			get_permalink(),
			sprintf( esc_html__( 'Continue reading &quot;%s&quot;', 'supro' ), the_title_attribute( 'echo=0' ) ),
			esc_html( $more )
		);
	} else {
		$output = sprintf( '<p>%s</p>', $content );
	}

	return $output;
}


/**
 * Show entry thumbnail base on its format
 *
 * @since  1.0
 */
function supro_entry_thumbnail( $size = 'thumbnail' ) {
	$html = '';

	$css_post = '';

	if ( $post_format = get_post_format() ) {
		$css_post = 'format-' . $post_format;
	}

	if ( get_post_format() != 'gallery' && get_post_format() != 'video' ) {
		$css_post = 'format-default';
	}

	switch ( get_post_format() ) {
		case 'gallery':
			$images = get_post_meta( get_the_ID(), 'images' );

			$gallery = array();
			if ( empty( $images ) ) {
				$thumb = get_the_post_thumbnail( get_the_ID(), $size );

				$html .= '<div class="single-image">' . $thumb . '</div>';
			} else {
				foreach ( $images as $image ) {
					$thumb = wp_get_attachment_image( $image, $size );
					if ( $thumb ) {
						$gallery[] = sprintf( '<div class="item-gallery">%s</div>', $thumb );
					}
				}

				$html .= implode( '', $gallery );
			}

			break;

		case 'video':
			$video = get_post_meta( get_the_ID(), 'video', true );
			if ( is_singular( 'post' ) ) {
				if ( ! $video ) {
					break;
				}

				// If URL: show oEmbed HTML
				if ( filter_var( $video, FILTER_VALIDATE_URL ) ) {
					if ( $oembed = @wp_oembed_get( $video, array( 'width' => 1170 ) ) ) {
						$html .= $oembed;
					} else {
						$atts = array(
							'src'   => $video,
							'width' => 1170,
						);

						if ( has_post_thumbnail() ) {
							$atts['poster'] = get_the_post_thumbnail_url( get_the_ID(), 'full' );
						}
						$html .= wp_video_shortcode( $atts );
					}
				} // If embed code: just display
				else {
					$html .= $video;
				}
			} else {
				$image_src = get_the_post_thumbnail( get_the_ID(), $size );
				if ( $video ) {
					$html = sprintf( '<a href="%s">%s</a>', esc_url( $video ), $image_src );
				} else {
					$html = $image_src;
				}
			}

			break;

		default:
			$html = get_the_post_thumbnail( get_the_ID(), $size );

			$thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), $size );

			if ( ! is_singular( 'post' ) ) {
				$html = sprintf( '<a href="%s">%s</a>', esc_url( get_the_permalink() ), $html );
			} else {
				if ( $thumbnail_url ) {
					$html = sprintf( '<div class="featured-image" style="background-image: url(%s)"></div>', esc_url( $thumbnail_url ) );
				}
			}

			break;
	}

	if ( $html ) {
		$html = sprintf( '<div  class="entry-format %s">%s</div>', esc_attr( $css_post ), $html );
	}

	echo apply_filters( __FUNCTION__, $html, get_post_format() );
}

/**
 * Get author meta
 *
 * @since  1.0
 *
 */
function supro_author_box() {
	if ( supro_get_option( 'show_author_box' ) == 0 ) {
		return;
	}

	if ( ! get_the_author_meta( 'description' ) ) {
		return;
	}

	$layout = supro_get_option( 'single_post_layout' );
	$col    = 'col-md-12';

	if ( 'full-content' == $layout ) {
		$col = 'col-md-8 col-md-offset-2';
	}

	?>
	<div class="post-author">
		<div class="row">
			<div class="<?php echo esc_attr( $col ) ?> col-xs-12 col-sm-12">
				<div class="post-author-box clearfix">
					<div class="post-author-avatar">
						<?php echo get_avatar( get_the_author_meta( 'ID' ), 70 ); ?>
					</div>
					<div class="post-author-info">
						<h3 class="author-name"><?php the_author_meta( 'display_name' ); ?></h3>

						<p><?php the_author_meta( 'description' ); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Get blog entry meta
 *
 * @since  1.0
 *
 */
function supro_entry_meta() {
	$fields = (array) supro_get_option( 'blog_entry_meta' );

	if ( empty ( $fields ) ) {
		return;
	}

	echo '<div class="entry-metas">';

	foreach ( $fields as $field ) {
		switch ( $field ) {

			case 'cat':
				$category = get_the_terms( get_the_ID(), 'category' );

				if ( ! is_wp_error( $category ) && $category ) {
					echo sprintf( '<a href="%s" class="entry-meta entry-cat">%s</a>', esc_url( get_term_link( $category[0], 'category' ) ), esc_html( $category[0]->name ) );
				}

				break;

			case 'date':
				$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

				$time_string = sprintf(
					$time_string,
					esc_attr( get_the_date( 'c' ) ),
					esc_html( get_the_date() )
				);

				$archive_year  = get_the_time( 'Y' );
				$archive_month = get_the_time( 'm' );
				$archive_day   = get_the_time( 'd' );

				echo sprintf(
					'<span class="entry-meta entry-date">' .
					'<a href="%s">%s</a></span>',
					esc_url( get_day_link( $archive_year, $archive_month, $archive_day ) ),
					$time_string
				);

				break;
		}
	}

	echo '</div>';
}

/**
 * Get single entry meta
 *
 * @since  1.0
 *
 */
function supro_single_entry_meta() {
	$fields = (array) supro_get_option( 'post_entry_meta' );

	if ( empty ( $fields ) ) {
		return;
	}

	echo '<div class="entry-metas">';

	foreach ( $fields as $field ) {
		switch ( $field ) {

			case 'author':
				echo sprintf(
					'<span class="entry-meta entry-author">' .
					'<label>%s</label>' .
					'<a class="url fn n" href="%s">%s</a></span>',
					esc_html__( 'Author:', 'supro' ),
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					esc_html( get_the_author() )
				);

				break;

			case 'date':
				$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

				$time_string = sprintf(
					$time_string,
					esc_attr( get_the_date( 'c' ) ),
					esc_html( get_the_date() )
				);

				$archive_year  = get_the_time( 'Y' );
				$archive_month = get_the_time( 'm' );
				$archive_day   = get_the_time( 'd' );

				echo sprintf(
					'<span class="entry-meta entry-date">' .
					'<label>%s</label>' .
					'<a href="%s">%s</a></span>',
					esc_html__( 'Published in:', 'supro' ),
					esc_url( get_day_link( $archive_year, $archive_month, $archive_day ) ),
					$time_string
				);

				break;
		}
	}

	echo '</div>';
}

/**
 * Entry single portfolio
 */
function supro_entry_meta_single_portfolio() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	$time_string = sprintf(
		$time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$archive_year  = get_the_time( 'Y' );
	$archive_month = get_the_time( 'm' );
	$archive_day   = get_the_time( 'd' );

	echo '<div class="entry-metas">';

	echo sprintf(
		'<span class="entry-meta entry-date">' .
		'<a href="%s">%s</a></span>',
		esc_url( get_day_link( $archive_year, $archive_month, $archive_day ) ),
		$time_string
	);

	$category = get_the_terms( get_the_ID(), 'portfolio_category' );

	if ( ! is_wp_error( $category ) && $category ) {
		echo sprintf( '<a href="%s" class="entry-meta entry-cat">%s</a>', esc_url( get_term_link( $category[0], 'portfolio_category' ) ), esc_html( $category[0]->name ) );
	}

	echo '</div>';
}

/**
 * Get recently viewed products
 *
 * @return string
 */
if ( ! function_exists( 'supro_recently_viewed_products' ) ) :
	function supro_recently_viewed_products( $atts ) {

		$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();
		$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );

		$output = array();

		$output[] = '<div class="recently-header">';
		if ( isset( $atts['title'] ) && $atts['title'] ) {
			$output[] = sprintf( '<h2 class="title">%s</h2>', wp_kses_post( $atts['title'] ) );
		}

		$output[] = '</div>';

		if ( empty( $viewed_products ) ) {

			$output[] = sprintf(
				'<ul class="product-list no-products">' .
				'<li class="text-center">%s <br><a href="%s" class="btn-secondary">%s</a></li>' .
				'</ul>',
				esc_html__( 'Recently Viewed Products is a function which helps you keep track of your recent viewing history.', 'supro' ),
				esc_url( get_permalink( get_option( 'woocommerce_shop_page_id' ) ) ),
				esc_html__( 'Shop Now', 'supro' )
			);

		} else {
			if ( ! function_exists( 'wc_get_product' ) ) {
				$output[] = sprintf(
					'<ul class="product-list no-products">' .
					'<li class="text-center">%s <br><a href="%s" class="btn-secondary">%s</a></li>' .
					'</ul>',
					esc_html__( 'Recently Viewed Products is a function which helps you keep track of your recent viewing history.', 'supro' ),
					esc_url( get_permalink( get_option( 'woocommerce_shop_page_id' ) ) ),
					esc_html__( 'Shop Now', 'supro' )
				);
			}

			$output[] = '<ul class="product-list">';
			$number   = intval( $atts['numbers'] );
			$index    = 1;
			foreach ( $viewed_products as $product_id ) {
				if ( $index > $number ) {
					break;
				}

				$index ++;

				$product = wc_get_product( $product_id );

				if ( empty( $product ) ) {
					continue;
				}
				$output[] = sprintf(
					'<li class="item">' .
					'<a href="%s">' .
					'%s' .
					'<span class="title">%s</span>' .
					'</a>' .
					'</li>',
					esc_url( $product->get_permalink() ),
					$product->get_image( 'shop_catalog' ),
					$product->get_title()
				);
			}
			$output[] = '</ul>';
		}

		return sprintf( '<div class="container">%s</div>', implode( ' ', $output ) );
	}
endif;

/**
 * Print HTML of language switcher
 * It requires plugin WPML installed
 */

if ( ! function_exists( 'supro_language_switcher' ) ) :
	function supro_language_switcher( $show_name = false ) {
		$language_dd = '';
		if ( function_exists( 'icl_get_languages' ) ) {
			$languages = icl_get_languages();
			if ( $languages ) {
				$lang_list = array();
				$current   = '';
				foreach ( (array) $languages as $code => $language ) {
					if ( ! $language['active'] ) {
						$lang_list[] = sprintf(
							'<li class="%s"><a href="%s">%s</a></li>',
							esc_attr( $code ),
							esc_url( $language['url'] ),
							$show_name ? esc_html( $language['native_name'] ) : esc_html( $code )
						);
					} else {
						$current = $language;
						array_unshift(
							$lang_list, sprintf(
								'<li class="active %s"><a href="%s">%s</a></li>',
								esc_attr( $code ),
								esc_url( $language['url'] ),
								$show_name ? esc_html( $language['native_name'] ) : esc_html( $code )
							)
						);
					}
				}

				$language_dd = sprintf(
					'<span class="current">%s<span class="toggle-children i-icon arrow_carrot-down"></span></span>' .
					'<ul>%s</ul>',
					$show_name ? esc_html( $current['native_name'] ) : esc_html( $current['language_code'] ),
					implode( "\n\t", $lang_list )
				);
			}
		}

		return $language_dd;
		?>

		<?php
	}
endif;

/**
 * Print HTML of currency switcher
 * It requires plugin WooCommerce Currency Switcher installed
 */
if ( ! function_exists( 'supro_currency_switcher' ) ) :
	function supro_currency_switcher( $show_desc = false ) {
		$currency_dd = '';
		if ( class_exists( 'WOOCS' ) ) {
			global $WOOCS;

			$key_cur = 'name';
			if ( $show_desc ) {
				$key_cur = 'description';
			}

			$currencies    = $WOOCS->get_currencies();
			$currency_list = array();
			foreach ( $currencies as $key => $currency ) {
				if ( $WOOCS->current_currency == $key ) {
					array_unshift(
						$currency_list, sprintf(
							'<li class="actived"><a href="#" class="woocs_flag_view_item woocs_flag_view_item_current" data-currency="%s">%s</a></li>',
							esc_attr( $currency['name'] ),
							esc_html( $currency[$key_cur] )
						)
					);
				} else {
					$currency_list[] = sprintf(
						'<li><a href="#" class="woocs_flag_view_item" data-currency="%s">%s</a></li>',
						esc_attr( $currency['name'] ),
						esc_html( $currency[$key_cur] )
					);
				}
			}

			$currency_dd = sprintf(
				'<span class="current">%s<span class="toggle-children i-icon arrow_carrot-down"></span></span>' .
				'<ul>%s</ul>',
				$currencies[$WOOCS->current_currency][$key_cur],
				implode( "\n\t", $currency_list )
			);


		}

		return $currency_dd;
	}

endif;

/**
 * Get socials
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
function supro_get_socials() {
	$socials = array(
		'facebook'   => esc_html__( 'Facebook', 'supro' ),
		'twitter'    => esc_html__( 'Twitter', 'supro' ),
		'google'     => esc_html__( 'Google', 'supro' ),
		'tumblr'     => esc_html__( 'Tumblr', 'supro' ),
		'flickr'     => esc_html__( 'Flickr', 'supro' ),
		'vimeo'      => esc_html__( 'Vimeo', 'supro' ),
		'youtube'    => esc_html__( 'Youtube', 'supro' ),
		'linkedin'   => esc_html__( 'LinkedIn', 'supro' ),
		'pinterest'  => esc_html__( 'Pinterest', 'supro' ),
		'dribbble'   => esc_html__( 'Dribbble', 'supro' ),
		'spotify'    => esc_html__( 'Spotify', 'supro' ),
		'instagram'  => esc_html__( 'Instagram', 'supro' ),
		'tumbleupon' => esc_html__( 'Tumbleupon', 'supro' ),
		'wordpress'  => esc_html__( 'WordPress', 'supro' ),
		'rss'        => esc_html__( 'Rss', 'supro' ),
		'deviantart' => esc_html__( 'Deviantart', 'supro' ),
		'share'      => esc_html__( 'Share', 'supro' ),
		'skype'      => esc_html__( 'Skype', 'supro' ),
		'behance'    => esc_html__( 'Behance', 'supro' ),
		'apple'      => esc_html__( 'Apple', 'supro' ),
		'yelp'       => esc_html__( 'Yelp', 'supro' ),
	);

	$socials = apply_filters( 'supro_header_socials', $socials );

	return $socials;
}

/**
 * Check is blog
 *
 * @since  1.0
 */

if ( ! function_exists( 'supro_is_blog' ) ) :
	function supro_is_blog() {
		if ( ( is_archive() || is_author() || is_category() || is_home() || is_tag() ) && 'post' == get_post_type() ) {
			return true;
		}

		return false;
	}

endif;

/**
 * Check is catalog
 *
 * @return bool
 */
if ( ! function_exists( 'supro_is_catalog' ) ) :
	function supro_is_catalog() {

		if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() ) || ( taxonomy_exists( 'product_brand' ) && is_tax( 'product_brand' ) ) ) {
			return true;
		}

		return false;
	}
endif;

/**
 * Check is portfolio
 *
 * @since  1.0
 */

if ( ! function_exists( 'supro_is_portfolio' ) ) :
	function supro_is_portfolio() {
		if ( is_post_type_archive( 'portfolio' ) || is_tax( 'portfolio_category' ) ) {
			return true;
		}

		return false;
	}
endif;

/**
 * Check is homepage
 *
 * @since  1.0
 */

if ( ! function_exists( 'supro_is_home' ) ) :
	function supro_is_home() {
		if ( is_page_template( 'template-homepage.php' ) ||
			is_page_template( 'template-home-boxed.php' ) ||
			is_page_template( 'template-home-left-sidebar.php' ) ||
			is_page_template( 'template-home-no-footer.php' )
		) {
			return true;
		}

		return false;
	}

endif;

/**
 * Check is homepage
 *
 * @since  1.0
 */

if ( ! function_exists( 'supro_is_page_template' ) ) :
	function supro_is_page_template() {
		if ( is_page_template( 'template-fullwidth.php' ) ||
			supro_is_home()
		) {
			return true;
		}

		return false;
	}

endif;

/**
 * Conditional function to check if current page is the maintenance page.
 *
 * @return bool
 */
function supro_is_maintenance_page() {
	if ( ! supro_get_option( 'maintenance_enable' ) ) {
		return false;
	}

	if ( current_user_can( 'super admin' ) ) {
		return false;
	}

	$page_id = supro_get_option( 'maintenance_page' );

	if ( ! $page_id ) {
		return false;
	}

	return is_page( $page_id );
}

/**
 *  Check Header Transparent
 */

if ( ! function_exists( 'supro_header_transparent' ) ) :

	function supro_header_transparent() {
		$header_transparent = supro_get_option( 'header_transparent' );
		$catalog_layout     = supro_get_option( 'catalog_layout' );
		$custom_header      = supro_get_post_meta( 'custom_header' );
		$enable_transparent = supro_get_post_meta( 'enable_header_transparent' );

		if ( is_page_template( 'template-home-left-sidebar.php' ) || is_404() ) {
			return false;
		}

		if ( is_singular() && ! is_page() ) {
			return false;
		}

		if ( supro_is_catalog() && ( $catalog_layout == 'sidebar-content' || $catalog_layout == 'content-sidebar' ) ) {
			return false;
		}

		if ( intval( $header_transparent ) && supro_is_home() ) {
			return true;
		}

		if ( $custom_header && $enable_transparent ) {
			return true;
		}

		return false;
	}

endif;

/**
 * Check Header Sticky
 */

if ( ! function_exists( 'supro_header_sticky' ) ) :
	function supro_header_sticky() {
		$header_sticky = supro_get_option( 'header_sticky' );

		if ( is_page_template( 'template-home-left-sidebar.php' ) ) {
			return false;
		}

		if ( intval( $header_sticky ) ) {
			return true;
		}

		if ( supro_is_maintenance_page() ) {
			return false;
		}

		return false;
	}

endif;

/**
 * show taxonomy filter
 *
 * @return string
 */

if ( ! function_exists( 'supro_taxs_list' ) ) :
	function supro_taxs_list( $args ) {
		$args = wp_parse_args(
			$args, array(
				'taxonomy'      => 'category',
				'number'        => 6,
				'button'        => esc_html__( 'All Categories', 'supro' ),
				'button_url'    => 'posts' == get_option( 'show_on_front' ) ? home_url('/') : get_page_link( get_option( 'page_for_posts' ) ),
				'orderby'       => 'count',
				'order'         => 'DESC',
				'custom'        => false,
				'custom_values' => [],
			)
		);

		$cats   = '';
		$output = array();

		$term_args = array(
			'number'  => $args['number'],
			'orderby' => $args['orderby'],
			'order'   => $args['order'],
		);

		$term_id = 0;

		if ( is_tax( $args['taxonomy'] ) || is_category() ) {

			$queried_object = get_queried_object();
			if ( $queried_object ) {
				$term_id = $queried_object->term_id;
			}
		}

		$found       = false;

		if ( intval( $args['custom'] ) ) {
			$cats_slug = $args['custom_values'];
			foreach ( $cats_slug as $slug ) {
				$cat = get_term_by( 'name', $slug, $args['taxonomy'] );
				if ( ! is_wp_error( $cat ) && $cat ) {
					$css_class = '';
					if ( $cat->term_id == $term_id ) {
						$css_class = 'selected';
						$found     = true;
					}
					$cats .= sprintf( '<li><a class="%s" href="%s">%s</a></li>', esc_attr( $css_class ), esc_url( get_term_link( $cat ) ), esc_html( $cat->name ) );
				}
			}
		} else {
			$categories = get_terms( $args['taxonomy'], $term_args );
			if ( ! is_wp_error( $categories ) && $categories ) {
				foreach ( $categories as $cat ) {
					$cat_selected = '';
					if ( $cat->term_id == $term_id ) {
						$cat_selected = 'selected';
						$found        = true;
					}
					$cats .= sprintf( '<li><a href="%s" class="%s">%s</a></li>', esc_url( get_term_link( $cat ) ), esc_attr( $cat_selected ), esc_html( $cat->name ) );
				}
			}
		}

		$cat_selected = $found ? '' : 'selected';

		if ( $cats ) {
			$output[] = sprintf(
				'<ul>
					<li><a href="%s" class="%s">%s</a></li>
					 %s
				</ul>',
				esc_url( $args['button_url'] ),
				esc_attr( $cat_selected ),
				esc_html( $args['button'] ),
				$cats
			);
		}

		if ( $output ) {
			$output = apply_filters( 'supro_tax_html', implode( "\n", $output ) );
			echo '<div id="supro-taxs-list" class="supro-taxs-list">' . $output . '</div>';
		}
	}

endif;


/**
 * Get blog description
 *
 * @since  1.0
 */

if ( ! function_exists( 'supro_blog_description' ) ) :
	function supro_blog_description() {
		if ( ! supro_is_blog() ) {
			return;
		}

		$blog_text = supro_get_option( 'blog_page_header_subtitle' );

		if ( is_category() ) {
			$blog_text = category_description();
		}

		if ( is_tag() ) {
			$blog_text = tag_description();
		}

		if ( empty( $blog_text ) ) {
			return;
		}

		printf( '<h4>%s</h4>', wp_kses_post( $blog_text ) );
	}

endif;

/**
 * Get blog meta
 *
 * @since  1.0
 *
 * @return string
 */
function supro_get_post_meta( $meta ) {

	if ( is_home() && ! is_front_page() ) {
		$post_id = get_queried_object_id();

		return get_post_meta( $post_id, $meta, true );
	}

	if ( function_exists( 'is_shop' ) && is_shop() ) {
		$post_id = intval( get_option( 'woocommerce_shop_page_id' ) );

		return get_post_meta( $post_id, $meta, true );
	}

	if ( is_post_type_archive( 'portfolio_project' ) ) {
		$post_id = intval( get_option( 'drf_portfolio_page_id' ) );

		return get_post_meta( $post_id, $meta, true );
	}

	if ( ! is_singular() || is_singular( 'product' ) ) {
		return false;
	}

	return get_post_meta( get_the_ID(), $meta, true );

}

/**
 * Get page header layout
 *
 * @return array
 */

if ( ! function_exists( 'supro_get_page_header' ) ) :
	function supro_get_page_header() {
		if ( is_singular() && ! is_page() ) {
			return false;
		}

		if ( supro_is_home() || is_404() ) {
			return false;
		}

		if ( supro_is_catalog() ) {
			return false;
		}

		if ( supro_get_post_meta( 'hide_page_header' ) ) {
			return false;
		}

		$page_header = array(
			'layout'        => 1,
			'parallax'      => false,
			'text_color'    => 'dark',
			'selector'      => '',
			'bg_image'      => '',
			'bg_position'   => '',
			'bg_size'       => '',
			'bg_repeat'     => '',
			'bg_attachment' => '',
			'tablet'        => array(
				'bg_image'      => '',
				'bg_position'   => '',
				'bg_size'       => '',
				'bg_repeat'     => '',
				'bg_attachment' => '',
			),
			'mobile'        => array(
				'bg_image'      => '',
				'bg_position'   => '',
				'bg_size'       => '',
				'bg_repeat'     => '',
				'bg_attachment' => '',
			)
		);

		if ( supro_is_blog() ) {
			if ( ! intval( supro_get_option( 'blog_page_header' ) ) ) {
				return false;
			}

			$pg_layout             = supro_get_option( 'blog_page_header_layout' );
			$page_header['layout'] = $pg_layout;

			if ( in_array( $pg_layout, array( '2', '3' ) ) ) {
				$bg_image           = supro_get_option( 'blog_page_header_background' );
				$position_desktop   = supro_get_option( 'blog_page_header_background_position' );
				$size_desktop       = supro_get_option( 'blog_page_header_background_size' );
				$repeat_desktop     = supro_get_option( 'blog_page_header_background_repeat' );
				$attachment_desktop = supro_get_option( 'blog_page_header_background_attachment' );

				$bg_image_tablet = supro_get_option( 'blog_page_header_background_tablet' );
				$position_tablet = supro_get_option( 'blog_page_header_background_position_tablet' );
				$size_tablet     = supro_get_option( 'blog_page_header_background_size_tablet' );
				$repeat_tablet   = supro_get_option( 'blog_page_header_background_repeat_tablet' );

				$bg_image_mobile = supro_get_option( 'blog_page_header_background_mobile' );
				$position_mobile = supro_get_option( 'blog_page_header_background_position_mobile' );
				$size_mobile     = supro_get_option( 'blog_page_header_background_size_mobile' );
				$repeat_mobile   = supro_get_option( 'blog_page_header_background_repeat_mobile' );

				$page_header['parallax']   = intval( supro_get_option( 'blog_page_header_parallax' ) );
				$page_header['text_color'] = supro_get_option( 'blog_page_header_text_color' );
				$page_header['selector']   = '.blog-page-header';

				$page_header['bg_image']      = $bg_image;
				$page_header['bg_position']   = $position_desktop;
				$page_header['bg_size']       = $size_desktop;
				$page_header['bg_repeat']     = $repeat_desktop;
				$page_header['bg_attachment'] = $attachment_desktop;

				$page_header['tablet']['bg_image']      = $bg_image_tablet;
				$page_header['tablet']['bg_position']   = $position_tablet;
				$page_header['tablet']['bg_size']       = $size_tablet;
				$page_header['tablet']['bg_repeat']     = $repeat_tablet;
				$page_header['tablet']['bg_attachment'] = $attachment_desktop == 'fixed' ? 'scroll' : '';

				$page_header['mobile']['bg_image']      = $bg_image_mobile;
				$page_header['mobile']['bg_position']   = $position_mobile;
				$page_header['mobile']['bg_size']       = $size_mobile;
				$page_header['mobile']['bg_repeat']     = $repeat_mobile;
				$page_header['mobile']['bg_attachment'] = $attachment_desktop == 'fixed' ? 'scroll' : '';
			}

		} elseif ( is_page() ) {
			if ( ! intval( supro_get_option( 'page_header' ) ) ) {
				return false;
			}

			$bg_image           = supro_get_option( 'page_header_background' );
			$position_desktop   = supro_get_option( 'page_header_background_position' );
			$size_desktop       = supro_get_option( 'page_header_background_size' );
			$repeat_desktop     = supro_get_option( 'page_header_background_repeat' );
			$attachment_desktop = supro_get_option( 'page_header_background_attachment' );

			$bg_image_tablet = supro_get_option( 'page_header_background_tablet' );
			$position_tablet = supro_get_option( 'page_header_background_position_tablet' );
			$size_tablet     = supro_get_option( 'page_header_background_size_tablet' );
			$repeat_tablet   = supro_get_option( 'page_header_background_repeat_tablet' );

			$bg_image_mobile = supro_get_option( 'page_header_background_mobile' );
			$position_mobile = supro_get_option( 'page_header_background_position_mobile' );
			$size_mobile     = supro_get_option( 'page_header_background_size_mobile' );
			$repeat_mobile   = supro_get_option( 'page_header_background_repeat_mobile' );

			$parallax   = intval( supro_get_option( 'page_header_parallax' ) );
			$text_color = supro_get_option( 'page_header_text_color' );

			if ( get_post_meta( get_the_ID(), 'page_header_custom_layout', true ) ) {
				if ( $custom_bg = get_post_meta( get_the_ID(), 'page_header_bg', true ) ) {
					$bg_image = wp_get_attachment_url( $custom_bg );
				}

				if ( $custom_bg_image_tablet = get_post_meta( get_the_ID(), 'page_header_bg_tablet', true ) ) {
					$bg_image_tablet = wp_get_attachment_url( $custom_bg_image_tablet );
				}

				if ( $custom_bg_image_mobile = get_post_meta( get_the_ID(), 'page_header_bg_mobile', true ) ) {
					$bg_image_mobile = wp_get_attachment_url( $custom_bg_image_mobile );
				}

				$position_desktop   = get_post_meta( get_the_ID(), 'page_header_background_position', true );
				$size_desktop       = get_post_meta( get_the_ID(), 'page_header_background_size', true );
				$repeat_desktop     = get_post_meta( get_the_ID(), 'page_header_background_repeat', true );
				$attachment_desktop = get_post_meta( get_the_ID(), 'page_header_background_attachment', true );

				$position_tablet = get_post_meta( get_the_ID(), 'page_header_background_position_tablet', true );
				$size_tablet     = get_post_meta( get_the_ID(), 'page_header_background_size_tablet', true );
				$repeat_tablet   = get_post_meta( get_the_ID(), 'page_header_background_repeat_tablet', true );

				$position_mobile = get_post_meta( get_the_ID(), 'page_header_background_position_mobile', true );
				$size_mobile     = get_post_meta( get_the_ID(), 'page_header_background_size_mobile', true );
				$repeat_mobile   = get_post_meta( get_the_ID(), 'page_header_background_repeat_mobile', true );

				$text_color = get_post_meta( get_the_ID(), 'text_color', true );
				$parallax   = intval( get_post_meta( get_the_ID(), 'parallax', true ) );
			}

			if ( empty( $bg_image_tablet ) ) {
				$bg_image_tablet = $bg_image;
			}

			if ( empty( $bg_image_mobile ) ) {
				$bg_image_mobile = $bg_image_tablet;
			}

			$page_header['parallax']   = $parallax;
			$page_header['text_color'] = $text_color;
			$page_header['selector']   = '.page-header-default';

			$page_header['bg_image']      = $bg_image;
			$page_header['bg_position']   = $position_desktop;
			$page_header['bg_size']       = $size_desktop;
			$page_header['bg_repeat']     = $repeat_desktop;
			$page_header['bg_attachment'] = $attachment_desktop;

			$page_header['tablet']['bg_image']      = $bg_image_tablet;
			$page_header['tablet']['bg_position']   = $position_tablet;
			$page_header['tablet']['bg_size']       = $size_tablet;
			$page_header['tablet']['bg_repeat']     = $repeat_tablet;
			$page_header['tablet']['bg_attachment'] = $attachment_desktop == 'fixed' ? 'scroll' : '';

			$page_header['mobile']['bg_image']      = $bg_image_mobile;
			$page_header['mobile']['bg_position']   = $position_mobile;
			$page_header['mobile']['bg_size']       = $size_mobile;
			$page_header['mobile']['bg_repeat']     = $repeat_mobile;
			$page_header['mobile']['bg_attachment'] = $attachment_desktop == 'fixed' ? 'scroll' : '';

		} elseif ( supro_is_portfolio() ) {
			if ( ! intval( supro_get_option( 'portfolio_page_header' ) ) ) {
				return false;
			}

			$bg_image           = supro_get_option( 'portfolio_page_header_background' );
			$position_desktop   = supro_get_option( 'portfolio_page_header_background_position' );
			$size_desktop       = supro_get_option( 'portfolio_page_header_background_size' );
			$repeat_desktop     = supro_get_option( 'portfolio_page_header_background_repeat' );
			$attachment_desktop = supro_get_option( 'portfolio_page_header_background_attachment' );

			$bg_image_tablet = supro_get_option( 'portfolio_page_header_background_tablet' );
			$position_tablet = supro_get_option( 'portfolio_page_header_background_position_tablet' );
			$size_tablet     = supro_get_option( 'portfolio_page_header_background_size_tablet' );
			$repeat_tablet   = supro_get_option( 'portfolio_page_header_background_repeat_tablet' );

			$bg_image_mobile = supro_get_option( 'portfolio_page_header_background_mobile' );
			$position_mobile = supro_get_option( 'portfolio_page_header_background_position_mobile' );
			$size_mobile     = supro_get_option( 'portfolio_page_header_background_size_mobile' );
			$repeat_mobile   = supro_get_option( 'portfolio_page_header_background_repeat_mobile' );

			$page_header['parallax']   = intval( supro_get_option( 'portfolio_page_header_parallax' ) );
			$page_header['text_color'] = supro_get_option( 'portfolio_page_header_text_color' );
			$page_header['selector']   = '.page-header-portfolio';

			$page_header['bg_image']      = $bg_image;
			$page_header['bg_position']   = $position_desktop;
			$page_header['bg_size']       = $size_desktop;
			$page_header['bg_repeat']     = $repeat_desktop;
			$page_header['bg_attachment'] = $attachment_desktop;

			$page_header['tablet']['bg_image']      = $bg_image_tablet;
			$page_header['tablet']['bg_position']   = $position_tablet;
			$page_header['tablet']['bg_size']       = $size_tablet;
			$page_header['tablet']['bg_repeat']     = $repeat_tablet;
			$page_header['tablet']['bg_attachment'] = $attachment_desktop == 'fixed' ? 'scroll' : '';

			$page_header['mobile']['bg_image']      = $bg_image_mobile;
			$page_header['mobile']['bg_position']   = $position_mobile;
			$page_header['mobile']['bg_size']       = $size_mobile;
			$page_header['mobile']['bg_repeat']     = $repeat_mobile;
			$page_header['mobile']['bg_attachment'] = $attachment_desktop == 'fixed' ? 'scroll' : '';
		}

		return $page_header;
	}

endif;


/**
 * Get breadcrumbs
 *
 * @since  1.0.0
 *
 * @return string
 */

if ( ! function_exists( 'supro_get_breadcrumbs' ) ) :
	function supro_get_breadcrumbs() {
		if ( supro_is_blog() ) {
			if ( ! intval( supro_get_option( 'blog_page_header_breadcrumbs' ) ) ) {
				return;
			}

		} elseif ( supro_is_catalog() ) {
			if ( ! intval( supro_get_option( 'catalog_page_header_breadcrumbs' ) ) ) {
				return;
			}

		} elseif ( supro_is_portfolio() ) {
			if ( ! intval( supro_get_option( 'portfolio_breadcrumb' ) ) ) {
				return;
			}

		} else {
			if ( ! intval( supro_get_option( 'page_header_breadcrumbs' ) ) ) {
				return;
			}

			if ( get_post_meta( get_the_ID(), 'hide_breadcrumbs', true ) ) {
				return;
			}
		}

		ob_start();
		?>
		<nav class="breadcrumbs">
			<?php
			supro_breadcrumbs(
				array(
					'before'   => '',
					'taxonomy' => function_exists( 'is_woocommerce' ) && is_woocommerce() ? 'product_cat' : 'category',
				)
			);
			?>
		</nav>
		<?php
		echo ob_get_clean();
	}

endif;

/**
 * Get current page URL for layered nav items.
 * @return string
 */
if ( ! function_exists( 'supro_get_page_base_url' ) ) :
	function supro_get_page_base_url() {
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

		return $link;
	}
endif;

/**
 * Get product video featured
 * @return string
 */
if ( ! function_exists( 'supro_get_product_video' ) ) :
	function supro_get_product_video() {
		global $product;
		$video_width  = intval( get_post_meta( $product->get_id(), 'video_width', true ) );
		$video_height = intval( get_post_meta( $product->get_id(), 'video_height', true ) );
		$video_url    = get_post_meta( $product->get_id(), 'video_url', true );
		$video_html   = '';
		// If URL: show oEmbed HTML
		if ( filter_var( $video_url, FILTER_VALIDATE_URL ) ) {
			$atts = array(
				'width'  => $video_width,
				'height' => $video_height
			);
			if ( $oembed = wp_oembed_get( $video_url, $atts ) ) {
				$video_html = $oembed;
			} else {
				$atts = array(
					'src'    => $video_url,
					'width'  => $video_width,
					'height' => $video_height
				);

				$video_html = wp_video_shortcode( $atts );
			}
		}

		if ( $video_html ) {
			$video_html = '<div class="video-wrapper">' . $video_html . '</div>';

			$video_html = sprintf(
				' <a href="#" data-href="%s" class="video-item-icon item-icon"><span>%s</span></a>',
				esc_attr( $video_html ),
				esc_html__( 'Play Video', 'supro' )
			);

		}

		return $video_html;
	}
endif;


/**
 * Get product zoom
 * @return string
 */
if ( ! function_exists( 'supro_get_product_zoom' ) ) :
	function supro_get_product_zoom() {
		if ( in_array( supro_get_option( 'single_product_layout' ), array( '3', '4', '5', '6' ) ) ) {
			return;
		}

		if ( ! intval( supro_get_option( 'product_images_lightbox' ) ) ) {
			return;
		}

		return sprintf(
			'<a href="#" class="gallery-item-icon item-icon"><span>%s</span></a>',
			esc_html__( 'Click to enlarge', 'supro' )
		);
	}
endif;