<?php
/**
 * Hooks for share socials
 *
 * @package Supro
 */

if ( ! function_exists( 'supro_addons_share_link_socials' ) ) :
	function supro_addons_share_link_socials( $title, $link, $media ) {
		$socials      = array();
		$socials_html = '';
		if ( is_singular( 'post' ) ) {
			$socials      = supro_get_option( 'post_socials_share' );
			$socials_html = '<li class="share-text">' . esc_html__( 'Share on', 'supro' ) . '</li>';

		} elseif ( is_singular( 'portfolio' ) ) {
			$socials = supro_get_option( 'single_portfolio_socials_share' );

		} elseif ( is_product() ) {
			$socials = supro_get_option( 'single_product_socials_share' );

		} elseif ( is_page_template( 'template-coming-soon-page.php' ) ) {
			$socials = supro_get_option( 'coming_soon_socials_share' );
		}

		if ( $socials ) {
			if ( in_array( 'facebook', $socials ) ) {
				$socials_html .= sprintf(
					'<li><a class="share-facebook supro-facebook" title="%s" href="http://www.facebook.com/sharer.php?u=%s&t=%s" target="_blank"><i class="ion-social-facebook"></i></a></li>',
					esc_attr( $title ),
					urlencode( $link ),
					urlencode( $title )
				);
			}

			if ( in_array( 'twitter', $socials ) ) {
				$socials_html .= sprintf(
					'<li><a class="share-twitter supro-twitter" href="http://twitter.com/share?text=%s&url=%s" title="%s" target="_blank"><i class="ion-social-twitter"></i></a></li>',
					urlencode( $title ),
					urlencode( $link ),
					esc_attr( $title )
				);
			}

			if ( in_array( 'pinterest', $socials ) ) {
				$socials_html .= sprintf(
					'<li><a class="share-pinterest supro-pinterest" href="http://pinterest.com/pin/create/button?media=%s&url=%s&description=%s" title="%s" target="_blank"><i class="ion-social-pinterest"></i></a></li>',
					urlencode( $media ),
					urlencode( $link ),
					urlencode( $title ),
					esc_attr( $title )
				);
			}

			if ( in_array( 'google', $socials ) ) {
				$socials_html .= sprintf(
					'<li><a class="share-google-plus supro-google-plus" href="https://plus.google.com/share?url=%s&text=%s" title="%s" target="_blank"><i class="ion-social-googleplus"></i></a></li>',
					urlencode( $link ),
					esc_attr( $title ),
					urlencode( $title )
				);
			}

			if ( in_array( 'linkedin', $socials ) ) {
				$socials_html .= sprintf(
					'<li><a class="share-linkedin supro-linkedin" href="http://www.linkedin.com/shareArticle?url=%s&title=%s" title="%s" target="_blank"><i class="ion-social-linkedin"></i></a></li>',
					urlencode( $link ),
					esc_attr( $title ),
					urlencode( $title )
				);
			}

			if ( in_array( 'tumblr', $socials ) ) {
				$socials_html .= sprintf(
					'<li><a class="share-tumblr supro-tumblr" href="http://www.tumblr.com/share/link?url=%s&name=%s" title="%s" target="_blank"><i class="ion-social-tumblr"></i></a></li>',
					urlencode( $link ),
					urlencode( $title ),
					esc_attr( $title )
				);
			}
		}

		if ( $socials_html ) {
			return sprintf( '<ul class="supro-social-share socials-inline">%s</ul>', $socials_html );
		}
	}

endif;
