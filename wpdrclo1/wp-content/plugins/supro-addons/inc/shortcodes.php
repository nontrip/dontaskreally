<?php

/**
 * Define theme shortcodes
 *
 * @package Supro
 */
class Supro_Shortcodes {

	/**
	 * Store variables for js
	 *
	 * @var array
	 */
	public $l10n = array();

	/**
	 * Store variables for maps
	 *
	 * @var array
	 */
	public $maps = array();
	public $api_key = '';

	/**
	 * Check if WooCommerce plugin is actived or not
	 *
	 * @var bool
	 */
	private $wc_actived = false;

	/**
	 * Construction
	 *
	 * @return Supro_Shortcodes
	 */
	function __construct() {
		$this->wc_actived = function_exists( 'is_woocommerce' );

		$shortcodes = array(
			'supro_empty_space',
			'supro_button',
			'supro_single_image',
			'supro_icons_box',
			'supro_images_box',
			'supro_latest_post',
			'supro_sliders',
			'supro_faqs',
			'supro_partner',
			'supro_counter',
			'supro_video',
			'supro_members',
			'supro_products',
			'supro_products_carousel',
			'supro_banner',
			'supro_banner2',
			'supro_banner3',
			'supro_banner4',
			'supro_product_banner',
			'supro_product_banner2',
			'supro_product_banner3',
			'supro_images',
			'supro_sale_product',
			'supro_instagram',
			'supro_instagram_2',
			'supro_testimonial',
			'supro_contact_box',
			'supro_socials',
			'supro_newsletter',
			'supro_coming_soon',
			'supro_gmap',
		);

		foreach ( $shortcodes as $shortcode ) {
			add_shortcode( $shortcode, array( $this, $shortcode ) );
		}

		add_action( 'wp_footer', array( $this, 'footer' ) );

		add_action( 'wp_ajax_nopriv_supro_load_products', array( $this, 'ajax_load_products' ) );
		add_action( 'wp_ajax_supro_load_products', array( $this, 'ajax_load_products' ) );
	}

	public function footer() {
		// Load Google maps only when needed
		if ( isset( $this->l10n['map'] ) ) {
			echo '<script>if ( typeof google !== "object" || typeof google.maps !== "object" )
				document.write(\'<script src="//maps.google.com/maps/api/js?sensor=false&key=' . $this->api_key . '"><\/script>\')</script>';
		}

		wp_enqueue_script(
			'shortcodes', SUPRO_ADDONS_URL . 'assets/js/frontend.js', array(
			'jquery',
			'imagesloaded',
			'wp-util',
		), '20171018', true
		);

		$this->l10n['days']    = esc_html__( 'days', 'supro' );
		$this->l10n['hours']   = esc_html__( 'hours', 'supro' );
		$this->l10n['minutes'] = esc_html__( 'minutes', 'supro' );
		$this->l10n['seconds'] = esc_html__( 'seconds', 'supro' );

		$this->l10n['isRTL'] = is_rtl();

		wp_localize_script( 'shortcodes', 'suproShortCode', $this->l10n );
	}

	/**
	 * Ajax load products
	 */
	function ajax_load_products() {
		check_ajax_referer( 'supro_get_products', 'nonce' );

		$attr = $_POST['attr'];

		$attr['load_more'] = isset( $_POST['load_more'] ) ? $_POST['load_more'] : true;
		$attr['page']      = isset( $_POST['page'] ) ? $_POST['page'] : 1;

		$type = isset( $_POST['type'] ) ? $_POST['type'] : '';

		$products = $this->get_wc_products( $attr, $type );

		wp_send_json_success( $products );
	}

	/**
	 * Get empty space
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function supro_empty_space( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'height'        => '',
				'height_mobile' => '',
				'height_tablet' => '',
				'bg_color'      => '',
				'el_class'      => '',
			), $atts
		);

		$css_class = array(
			'supro-empty-space',
			$atts['el_class'],
		);

		$style = '';

		if ( $atts['bg_color'] ) {
			$style = 'background-color:' . $atts['bg_color'] . ';';
		}

		$height = $atts['height'] ? (float) $atts['height'] : 0;

		if ( ! empty( $atts['height_tablet'] ) || $atts['height_tablet'] == '0' ) {
			$height_tablet = (float) $atts['height_tablet'];
		} else {
			$height_tablet = $height;
		}

		if ( ! empty( $atts['height_mobile'] ) || $atts['height_mobile'] == '0' ) {
			$height_mobile = (float) $atts['height_mobile'];
		} else {
			$height_mobile = $height_tablet;
		}

		$inline_css        = $height >= 0.0 ? ' style="height: ' . esc_attr( $height ) . 'px"' : '';
		$inline_css_tablet = $height_tablet >= 0.0 ? ' style="height: ' . esc_attr( $height_tablet ) . 'px"' : '';
		$inline_css_mobile = $height_mobile >= 0.0 ? ' style="height: ' . esc_attr( $height_mobile ) . 'px"' : '';

		return sprintf(
			'<div class="%s" style="%s">' .
			'<div class="supro_empty_space_lg" %s></div>' .
			'<div class="supro_empty_space_md" %s></div>' .
			'<div class="supro_empty_space_xs" %s></div>' .
			'</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$style,
			$inline_css,
			$inline_css_tablet,
			$inline_css_mobile
		);
	}


	/**
	 * Button
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	function supro_button( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'align'         => 'left',
				'style'         => 'flat',
				'color'         => 'dark',
				'link'          => '',
				'font_size'     => '',
				'line_height'   => '',
				'css_animation' => '',
				'el_class'      => '',
				't_font_size'   => '',
				'm_font_size'   => '',
				't_line_height' => '',
				'm_line_height' => '',
				't_align'       => 'inherit',
				'm_align'       => 'inherit',
			), $atts
		);

		return $this->supro_addons_btn( $atts );
	}

	/**
	 * Shortcode to display single image
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function supro_single_image( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'image'         => '',
				'image_size'    => 'full',
				'align'         => 'left',
				'css_animation' => '',
				'el_class'      => '',
			), $atts
		);

		$css_class[] = $atts['el_class'];
		$css_class[] = $this->get_css_animation( $atts['css_animation'] );
		$css_class[] = 'text-' . $atts['align'];

		$image_id = intval( $atts['image'] );
		if ( ! $atts['image'] ) {
			return;
		}

		$image_full = wp_get_attachment_image_src( $atts['image'], 'full' );

		if ( ! $image_full ) {
			return;
		}

		$image_size = $atts['image_size'];
		$image_src  = '';
		if ( function_exists( 'wpb_getImageBySize' ) ) {
			$image = wpb_getImageBySize(
				array(
					'attach_id'  => $image_id,
					'thumb_size' => $image_size,
				)
			);

			if ( $image['thumbnail'] ) {
				$image_src = $image['thumbnail'];
			} elseif ( $image['p_img_large'] ) {
				$image_src = sprintf( '<img src="%s">', esc_url( $image['p_img_large'][0] ) );
			}

		}

		if ( empty( $image_src ) ) {
			$image_src = wp_get_attachment_image( $image_id, $image_size );
		}

		return sprintf(
			'<div class="supro-single-image supro-photo-swipe %s">' .
			'<a href="%s" class="photoswipe" data-width="%s" data-height="%s">' .
			'%s' .
			'</a>' .
			'</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_url( $image_full[0] ),
			esc_attr( $image_full[1] ),
			esc_attr( $image_full[2] ),
			$image_src
		);
	}

	/**
	 * Shortcode to display banner
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function supro_banner( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'style'                => '1',
				'btn_style'            => '1',
				'text_align'           => 'left',
				'image'                => '',
				'title'                => '',
				'subtitle'             => '',
				'line_height'          => '',
				'color'                => '',
				'subtitle_color'       => '',
				'link'                 => '',
				'btn_color'            => '',
				'btn_background_color' => '',
				'position'             => 'top-left',
				'top'                  => '',
				'right'                => '',
				'bottom'               => '',
				'left'                 => '',
				'content_color'        => '',
				'css_animation'        => '',
				'el_class'             => '',
			), $atts
		);

		$class_name = 'supro-banner__' . $this->get_id_number( __FUNCTION__ );

		$css_class = array(
			'supro-banner-grid',
			'position-' . $atts['position'],
			'style-' . $atts['style'],
			'btn-style-' . $atts['btn_style'],
			'text-' . $atts['text_align'],
			$class_name,
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class']
		);

		$css = '';

		$output = $attributes = array();

		$link = vc_build_link( $atts['link'] );

		if ( ! empty( $link['url'] ) ) {
			$attributes['href'] = $link['url'];
		}

		$label               = $link['title'];
		$attributes['class'] = 'banner-url';

		if ( ! $label ) {
			$attributes['title'] = $label;
		}

		if ( ! empty( $link['target'] ) ) {
			$attributes['target'] = $link['target'];
		}

		if ( ! empty( $link['rel'] ) ) {
			$attributes['rel'] = $link['rel'];
		}

		$attr = array();

		foreach ( $attributes as $name => $v ) {
			$attr[] = $name . '="' . esc_attr( $v ) . '"';
		}

		$image_id = intval( $atts['image'] );

		$image_src = $this->supro_vc_get_image( $image_id, 'full' );

		$output[] = sprintf( '<%1$s %2$s>', empty( $attributes['href'] ) ? 'span' : 'a', implode( ' ', $attr ) );
		$output[] = sprintf( '%s', $image_src );
		$output[] = '<span class="banner-content">';
		if ( $atts['subtitle'] ) {
			$output[] = sprintf( '<span class="subtitle">%s</span>', $atts['subtitle'] );
		}

		if ( $atts['title'] ) {
			$output[] = sprintf( '<span class="title">%s</span>', $atts['title'] );
		}

		if ( $content && $atts['style'] == '2' ) {
			$output[] = sprintf( '<span class="desc">%s</span>', $content );
		}

		if ( $label ) {
			$output[] = sprintf( '<span class="banner-btn">%s</span>', $label );
		}

		$output[] = '</span>';
		$output[] = sprintf( '</%1$s>', empty( $attributes['href'] ) ? 'span' : 'a' );

		$position     = array( 'top', 'bottom', 'left', 'right' );
		$title_css    = array( 'color' );
		$subtitle_css = array( 'subtitle_color' );
		$content_css  = array( 'content_color' );

		foreach ( $position as $p ) {
			if ( $atts[ $p ] ) {
				$css .= ".$class_name .banner-content { " . $p . " : " . $atts[ $p ] . " }";
			}
		}

		foreach ( $title_css as $t ) {
			if ( $atts[ $t ] ) {
				$attr = str_replace( '_', '-', $t );
				$css  .= ".$class_name .title { " . $attr . " : " . $atts[ $t ] . " }";
			}
		}

		foreach ( $subtitle_css as $t ) {
			if ( $atts[ $t ] ) {
				$attr = str_replace( 'subtitle_', '', $t );
				$attr = str_replace( '_', '-', $attr );
				$css  .= ".$class_name .subtitle { " . $attr . " : " . $atts[ $t ] . " }";
			}
		}

		foreach ( $content_css as $t ) {
			if ( $atts[ $t ] ) {
				$attr = str_replace( 'content_', '', $t );
				$attr = str_replace( '_', '-', $attr );
				$css  .= ".$class_name .desc { " . $attr . " : " . $atts[ $t ] . " }";
			}
		}

		if ( $atts['btn_color'] ) {
			$css .= "
				.$class_name .banner-btn { color: " . $atts['btn_color'] . " }
				.$class_name .banner-btn { border-color: " . $atts['btn_color'] . " }
			";
		}

		if ( $atts['btn_background_color'] ) {
			$css .= ".$class_name.btn-style-2 .banner-btn { background-color: " . $atts['btn_background_color'] . " }";
		}

		return sprintf(
			'<style type="text/css">%s</style><div class="%s">%s</div>',
			$css,
			esc_attr( implode( ' ', $css_class ) ),
			implode( '', $output )
		);
	}

	/**
	 * Shortcode to display banner
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function supro_banner2( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'height'          => 820,
				'gap'             => '10',
				'image1'          => '',
				'image2'          => '',
				'image3'          => '',
				'image4'          => '',
				'title1'          => '',
				'title2'          => '',
				'title3'          => '',
				'title4'          => '',
				'subtitle1'       => '',
				'subtitle2'       => '',
				'subtitle3'       => '',
				'subtitle4'       => '',
				'subtitle_color1' => '',
				'subtitle_color2' => '',
				'subtitle_color3' => '',
				'subtitle_color4' => '',
				'link1'           => '',
				'link2'           => '',
				'link3'           => '',
				'link4'           => '',
				'position1'       => 'center',
				'position2'       => 'center',
				'position3'       => 'center',
				'position4'       => 'center',
				'color_scheme1'   => 'dark',
				'color_scheme2'   => 'dark',
				'color_scheme3'   => 'dark',
				'color_scheme4'   => 'dark',
				'css_animation'   => '',
				'el_class'        => '',
			), $atts
		);

		$class_name = 'supro-banner-2__' . $this->get_id_number( __FUNCTION__ );

		$css_class = array(
			'supro-banner-grid-2',
			'supro-banner-grid__gap-' . $atts['gap'],
			$atts['el_class'],
			$class_name,
			$this->get_css_animation( $atts['css_animation'] )
		);

		$css = '';

		$image1 = wp_get_attachment_image_src( $atts['image1'], 'full' );
		$image2 = wp_get_attachment_image_src( $atts['image2'], 'full' );
		$image3 = wp_get_attachment_image_src( $atts['image3'], 'full' );
		$image4 = wp_get_attachment_image_src( $atts['image4'], 'full' );
		$link1  = vc_build_link( $atts['link1'] );
		$link2  = vc_build_link( $atts['link2'] );
		$link3  = vc_build_link( $atts['link3'] );
		$link4  = vc_build_link( $atts['link4'] );

		$css_class1 = array(
			'banner-grid__banner',
			'banner-grid__banner1',
			'text-position-' . $atts['position1'],
			$atts['color_scheme1'],
		);

		$css .= "
		.$class_name .banner-grid__banner1 .banner-subtitle { color: " . $atts['subtitle_color1'] . " }
		.$class_name .banner-grid__banner1 .banner-image { background-image: url( " . $image1[0] . " ) }
		";

		$banner1 = sprintf(
			'<div class="%s">
				<a href="%s" target="%s" rel="%s" title="%s" class="banner-grid__link">
					<span class="banner-image"></span>
					<span class="banner-content">
						<span class="banner-subtitle">%s</span>
						<span class="text-%s banner-title">%s</span>
					</span>
				</a>
			</div>',
			esc_attr( implode( ' ', $css_class1 ) ),
			esc_url( $link1['url'] ),
			esc_attr( $link1['target'] ),
			esc_attr( $link1['rel'] ),
			esc_attr( $link1['title'] ),
			$atts['subtitle1'],
			$atts['color_scheme1'],
			$atts['title1']
		);

		$css_class2 = array(
			'banner-grid__banner',
			'banner-grid__banner2',
			'text-position-' . $atts['position2'],
			$atts['color_scheme2'],
		);

		$css .= "
		.$class_name .banner-grid__banner2 .banner-subtitle { color: " . $atts['subtitle_color2'] . " }
		.$class_name .banner-grid__banner2 .banner-image { background-image: url( " . $image2[0] . " ) }
		";

		$banner2    = sprintf(
			'<div class="%s">
				<a href="%s" target="%s" rel="%s" title="%s" class="banner-grid__link">
					<span class="banner-image"></span>
					<span class="banner-content">
						<span class="banner-subtitle">%s</span>
						<span class="text-%s banner-title">%s</span>
					</span>
				</a>
			</div>',
			esc_attr( implode( ' ', $css_class2 ) ),
			esc_url( $link2['url'] ),
			esc_attr( $link2['target'] ),
			esc_attr( $link2['rel'] ),
			esc_attr( $link2['title'] ),
			$atts['subtitle2'],
			$atts['color_scheme2'],
			$atts['title2']
		);
		$css_class3 = array(
			'banner-grid__banner',
			'banner-grid__banner3',
			'text-position-' . $atts['position3'],
			$atts['color_scheme3'],
		);

		$css .= "
		.$class_name .banner-grid__banner3 .banner-subtitle { color: " . $atts['subtitle_color3'] . " }
		.$class_name .banner-grid__banner3 .banner-image { background-image: url( " . $image3[0] . " ) }
		";

		$banner3    = sprintf(
			'<div class="%s">
				<a href="%s" target="%s" rel="%s" title="%s" class="banner-grid__link">
					<span class="banner-image"></span>
					<span class="banner-content">
						<span class="banner-subtitle">%s</span>
						<span class="text-%s banner-title">%s</span>
					</span>
				</a>
			</div>',
			esc_attr( implode( ' ', $css_class3 ) ),
			esc_url( $link3['url'] ),
			esc_attr( $link3['target'] ),
			esc_attr( $link3['rel'] ),
			esc_attr( $link3['title'] ),
			$atts['subtitle3'],
			$atts['color_scheme3'],
			$atts['title3']
		);
		$css_class4 = array(
			'banner-grid__banner',
			'banner-grid__banner4',
			'text-position-' . $atts['position4'],
			$atts['color_scheme4'],
		);

		$css .= "
		.$class_name .banner-grid__banner4 .banner-subtitle { color: " . $atts['subtitle_color4'] . " }
		.$class_name .banner-grid__banner4 .banner-image { background-image: url( " . $image4[0] . " ) }
		";

		$banner4 = sprintf(
			'<div class="%s">
				<a href="%s" target="%s" rel="%s" title="%s" class="banner-grid__link">
					<span class="banner-image"></span>
					<span class="banner-content">
						<span class="banner-subtitle">%s</span>
						<span class="text-%s banner-title">%s</span>
					</span>
				</a>
			</div>',
			esc_attr( implode( ' ', $css_class4 ) ),
			esc_url( $link4['url'] ),
			esc_attr( $link4['target'] ),
			esc_attr( $link4['rel'] ),
			esc_attr( $link4['title'] ),
			$atts['subtitle4'],
			$atts['color_scheme4'],
			$atts['title4']
		);

		$css .= "
			.$class_name { height: " . floatval( $atts['height'] ) . "px }
			@media (max-width: 991px) { .$class_name { height: " . floatval( $atts['height'] ) * 0.8 . "px } }
			@media (max-width: 767px) { .$class_name { height: " . floatval( $atts['height'] ) * 0.8 * 2 . "px } }
			";

		return sprintf(
			'<style type="text/css">%s</style><div class="%s">%s</div>',
			$css,
			esc_attr( implode( ' ', $css_class ) ),
			$banner1 . $banner2 . $banner3 . $banner4
		);
	}

	/**
	 * Grid Banner 3
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	function supro_banner3( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'banners'       => '',
				'css_animation' => '',
				'el_class'      => '',
			), $atts
		);

		$css_class = array(
			'supro-banner-grid-3',
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		$output = array();

		$banners = vc_param_group_parse_atts( $atts['banners'] );
		$i       = 1;

		if ( $banners ) {
			foreach ( $banners as $banner ) {
				$style = array();
				$src   = '';
				$css   = 'banner-grid__banner';
				if ( isset( $banner['image'] ) && $banner['image'] ) {
					$image = wp_get_attachment_image_src( $banner['image'], 'full' );
					if ( $image ) {
						$src = $image[0];
					}

					$style[] = 'background-image:url(' . $src . ');';
				}

				$link = vc_build_link( $banner['link'] );

				$mod = $i % 8;

				if ( in_array( $mod, array( '1', '5', '7', '0' ) ) ) {
					$css .= ' banner-w';
				}

				$css .= ' banner-' . $banner['position'];

				$output[] = sprintf(
					'<div class="%s">
						<a href="%s" target="%s" rel="%s" title="%s" class="banner-grid__link">
							<span class="banner-image" style="%s"></span>
							<span class="banner-content">
								<span class="banner-subtitle">%s</span>
								<span class="banner-title">%s</span>
							</span>
						</a>
					</div>',
					esc_attr( $css ),
					esc_url( $link['url'] ),
					esc_attr( $link['target'] ),
					esc_attr( $link['rel'] ),
					esc_attr( $link['title'] ),
					esc_attr( implode( '', $style ) ),
					isset( $banner['subtitle'] ) && $banner['subtitle'] ? $banner['subtitle'] : '',
					isset( $banner['title'] ) && $banner['title'] ? $banner['title'] : ''
				);

				$i ++;
			}
		}

		return sprintf(
			'<div class="%s"><div class="banner-group">%s</div></div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( ' ', $output )
		);
	}

	/**
	 * Grid Banner 4
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	function supro_banner4( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'banners'       => '',
				'css_animation' => '',
				'el_class'      => '',
			), $atts
		);

		$css_class = array(
			'supro-banner-grid-4',
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		$output = array();

		$banners = vc_param_group_parse_atts( $atts['banners'] );
		$i       = 1;

		if ( $banners ) {
			foreach ( $banners as $banner ) {
				$image = '';
				$css   = 'banner-grid__banner';
				if ( isset( $banner['image'] ) && $banner['image'] ) {
					$image = wp_get_attachment_image( $banner['image'], 'full' );
				}

				$link = vc_build_link( $banner['link'] );

				$mod = $i % 6;

				if ( $mod == 2 || $mod == 3 ) {
					$css .= ' banner-s col-md-4 col-sm-12 col-xs-12';
				} elseif ( $mod == 1 ) {
					$css .= ' banner-l col-md-8 col-sm-12 col-xs-12';
				} elseif ( $mod == 4 ) {
					$css .= ' banner-f col-md-12 col-sm-12 col-xs-12';
				} else {
					$css .= ' banner-m col-md-6 col-sm-12 col-xs-12';
				}

				$output[] = sprintf(
					'<div class="%s">
						<a href="%s" target="%s" rel="%s" title="%s" class="banner-grid__link">
							<span class="banner-image">%s</span>
							<span class="banner-content">
								<span class="banner-title">%s</span>
								<span class="banner-subtitle">%s</span>
							</span>
						</a>
					</div>',
					esc_attr( $css ),
					esc_url( $link['url'] ),
					esc_attr( $link['target'] ),
					esc_attr( $link['rel'] ),
					esc_attr( $link['title'] ),
					$image,
					isset( $banner['title'] ) && $banner['title'] ? $banner['title'] : '',
					isset( $banner['subtitle'] ) && $banner['subtitle'] ? $banner['subtitle'] : ''
				);

				$i ++;
			}
		}

		return sprintf(
			'<div class="%s"><div class="banner-group">%s</div></div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( ' ', $output )
		);
	}

	/**
	 * Icon Box
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	function supro_icons_box( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'icons'         => '',
				'box_shadow'    => '',
				'space'         => '',
				'css_animation' => '',
				'el_class'      => '',
			), $atts
		);

		$css_class = array(
			'supro-icons-box',
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		if ( intval( $atts['box_shadow'] ) ) {
			$css_class[] = 'box-shadow';
		}

		if ( intval( $atts['space'] ) ) {
			$css_class[] = 'no-space';
		}

		$output = array();

		$icons = vc_param_group_parse_atts( $atts['icons'] );

		if ( $icons ) {
			foreach ( $icons as $icon ) {
				$ic = '';
				if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
					vc_icon_element_fonts_enqueue( $icon['icon_type'] );
				}

				if ( isset( $icon['icon_type'] ) && $icon['icon_type'] ) {
					$ic = '<i class="' . esc_attr( $icon[ 'icon_' . $icon['icon_type'] ] ) . '"></i>';
				}

				$link_atts = array(
					'link' => isset( $icon['link'] ) ? $icon['link'] : '',
				);

				$title = '';

				if ( isset( $icon['title'] ) && $icon['title'] ) {
					$title = sprintf( '<h4 class="box-title">%s</h4>', $this->get_vc_link( $link_atts, $icon['title'] ) );
				}

				$output[] = sprintf(
					'<li class="supro-icon-box">' .
					'<div class="box-icon">' .
					'%s' .
					'</div>' .
					'<div class="box-wrapper">' .
					'%s' .
					'<div class="desc">%s</div>' .
					'</div>' .
					'</li>',
					$ic,
					$title,
					$icon['desc']
				);
			}
		}

		return sprintf(
			'<div class="%s"><ul>%s</ul></div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( ' ', $output )
		);
	}

	/**
	 * Images Box
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	function supro_images_box( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'type'          => 'carousel',
				'height'        => '300',
				'columns'       => '4',
				'nav'           => '',
				'dots'          => '',
				'autoplay'      => '',
				'speed'         => '2000',
				'infinite'      => false,
				'box_option'    => '',
				'css_animation' => '',
				'el_class'      => '',
			), $atts
		);

		$css_class = array(
			'supro-images-box',
			'supro-images-box--' . $atts['type'],
			'supro-images-box--' . $atts['columns'] . '-columns',
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		$speed   = intval( $atts['speed'] );
		$columns = intval( $atts['columns'] );

		if ( $atts['autoplay'] ) {
			$autoplay = true;
		} else {
			$autoplay = false;
		}

		if ( $atts['nav'] ) {
			$nav         = true;
			$css_class[] = 'nav-enable';
		} else {
			$nav = false;
		}

		if ( $atts['infinite'] ) {
			$loop = true;
		} else {
			$loop = false;
		}

		if ( $atts['dots'] ) {
			$dot         = true;
			$css_class[] = 'dots-enable';
		} else {
			$dot = false;
		}

		$id = uniqid( 'supro-images-box-' );

		$this->l10n['images'][ $id ] = array(
			'nav'            => $nav,
			'dot'            => $dot,
			'autoplay'       => $autoplay,
			'autoplay_speed' => $speed,
			'infinite'       => $loop,
			'columns'        => $columns,
		);

		$style = '';

		if ( $atts['height'] && $atts['type'] == 'carousel' ) {
			$style = 'min-height: ' . $atts['height'] . 'px;';
		}

		$output = array();

		$options = vc_param_group_parse_atts( $atts['box_option'] );

		if ( $options ) {
			foreach ( $options as $option ) {
				$item_css = array(
					'supro-box',
					'col-sm-6 col-xs-6 col-md-' . 12 / $columns
				);
				$size     = 'full';
				$title    = $subtitle = $image = '';
				if ( isset( $option['image_size'] ) ) {
					$size = $option['image_size'];
				}

				if ( isset( $option['image'] ) && $option['image'] ) {
					$image = $this->supro_vc_get_image( $option['image'], $size );
					$image = $this->get_vc_link( $option, $image );
				}

				if ( isset( $option['title'] ) && $option['title'] ) {
					$title = sprintf( '<h3>%s</h3>', $this->get_vc_link( $option, $option['title'] ) );
				}

				if ( isset( $option['subtitle'] ) && $option['subtitle'] ) {
					$subtitle = sprintf( '<div class="subtitle">%s</div>', $option['subtitle'] );
				}

				$output[] = sprintf(
					'<div class="%s"><div class="box-content" style="%s">%s%s%s</div></div>',
					esc_attr( implode( ' ', $item_css ) ),
					esc_attr( $style ),
					$image,
					$subtitle,
					$title
				);
			}
		}

		$dir = $id_attr = '';

		if ( is_rtl() ) {
			$dir = 'dir="rtl"';
		}

		if ( $atts['type'] == 'carousel' ) {
			$id_attr = 'id="' . esc_attr( $id ) . '"';
		}

		return sprintf(
			'<div class="%s"><div %s class="box-list row" %s>%s</div></div>',
			esc_attr( implode( ' ', $css_class ) ),
			$dir,
			$id_attr,
			implode( '', $output )
		);
	}

	/**
	 * Shortcode to display product banner
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function supro_product_banner( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'option'        => '',
				'css_animation' => '',
				'el_class'      => '',
			), $atts
		);

		$css_class = array(
			'supro-product-banner',
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class']
		);

		$output = $attributes = array();

		$options = vc_param_group_parse_atts( $atts['option'] );
		$count   = count( $options );
		$i       = 0;

		if ( $options ) {
			foreach ( $options as $option ) {

				$link = vc_build_link( $option['link'] );

				if ( ! empty( $link['url'] ) ) {
					$attributes['href'] = $link['url'];
				}

				$label               = $link['title'];
				$attributes['class'] = 'banner-url';

				if ( $i % 2 == 0 ) {
					$attributes['class'] .= ' odd-banner';
				} else {
					$attributes['class'] .= ' even-banner';
				}

				if ( ! $label ) {
					$attributes['title'] = $label;
				}

				if ( ! empty( $link['target'] ) ) {
					$attributes['target'] = $link['target'];
				}

				if ( ! empty( $link['rel'] ) ) {
					$attributes['rel'] = $link['rel'];
				}

				$attr = array();

				foreach ( $attributes as $name => $v ) {
					$attr[] = $name . '="' . esc_attr( $v ) . '"';
				}

				$image_id   = isset( $option['image'] ) && $option['image'] ? intval( $option['image'] ) : '';
				$image_size = isset( $option['image_size'] ) && $option['image_size'] ? $option['image_size'] : 'full';
				$image_src  = '';
				if ( function_exists( 'wpb_getImageBySize' ) ) {
					$image = wpb_getImageBySize(
						array(
							'attach_id'  => $image_id,
							'thumb_size' => $image_size,
						)
					);

					if ( $image['thumbnail'] ) {
						$image_src = $image['thumbnail'];
					} elseif ( $image['p_img_large'] ) {
						$image_src = sprintf( '<img src="%s">', esc_url( $image['p_img_large'][0] ) );
					}

				}

				if ( empty( $image_src ) ) {
					$image_src = wp_get_attachment_image( $image_id, $image_size );
				}

				if ( $i % 2 == 0 ) {
					$output[] = '<div class="banner-group clearfix">';
				}

				$output[] = sprintf( '<%1$s %2$s>', empty( $attributes['href'] ) ? 'span' : 'a', implode( ' ', $attr ) );
				$output[] = sprintf( '%s', $image_src );
				$output[] = '<span class="banner-content">';

				if ( isset( $option['title'] ) && $option['title'] ) {
					$output[] = sprintf( '<span class="title">%s</span>', $option['title'] );
				}

				if ( isset( $option['price'] ) && $option['price'] ) {
					$output[] = sprintf( '<span class="price">%s</span>', $option['price'] );
				}

				if ( $label ) {
					$output[] = sprintf( '<span class="banner-btn">%s</span>', $label );
				}

				$output[] = '</span>';
				$output[] = sprintf( '</%1$s>', empty( $attributes['href'] ) ? 'span' : 'a' );

				if ( $i % 2 == 1 ) {
					$output[] = '</div>';
				}

				$i ++;
			}
		}

		if ( $count % 2 == 1 ) {
			$output[]    = '</div>';
			$css_class[] = 'odd-banner-finished';
		}

		return sprintf(
			'<div class="%s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( '', $output )
		);
	}

	/**
	 * Shortcode to display product banner
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function supro_product_banner2( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'style'         => '1',
				'image'         => '',
				'image_size'    => 'thumbnail',
				'title'         => '',
				'price'         => '',
				'link'          => '',
				'alignment'     => 'left',
				'css_animation' => '',
				'el_class'      => '',
			), $atts
		);

		$css_class = array(
			'supro-product-banner2',
			'style-' . $atts['style'],
			$atts['style'] == '1' ? 'text-align-' . $atts['alignment'] : 'text-center',
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class']
		);

		$output = $attributes = array();

		$link = vc_build_link( $atts['link'] );

		if ( ! empty( $link['url'] ) ) {
			$attributes['href'] = $link['url'];
		}

		$label               = $link['title'];
		$attributes['class'] = 'banner-url';

		if ( ! $label ) {
			$attributes['title'] = $label;
		}

		if ( ! empty( $link['target'] ) ) {
			$attributes['target'] = $link['target'];
		}

		if ( ! empty( $link['rel'] ) ) {
			$attributes['rel'] = $link['rel'];
		}

		$attr = array();

		foreach ( $attributes as $name => $v ) {
			$attr[] = $name . '="' . esc_attr( $v ) . '"';
		}

		$image_id   = intval( $atts['image'] );
		$image_size = $atts['image_size'];
		$image_src  = '';
		if ( function_exists( 'wpb_getImageBySize' ) ) {
			$image = wpb_getImageBySize(
				array(
					'attach_id'  => $image_id,
					'thumb_size' => $image_size,
				)
			);

			if ( $image['thumbnail'] ) {
				$image_src = $image['thumbnail'];
			} elseif ( $image['p_img_large'] ) {
				$image_src = sprintf( '<img src="%s">', esc_url( $image['p_img_large'][0] ) );
			}
		}

		if ( empty( $image_src ) ) {
			$image_src = wp_get_attachment_image( $image_id, $image_size );
		}
		$output[] = '<div class="banner-wrapper">';
		$output[] = sprintf( '<%1$s %2$s>', empty( $attributes['href'] ) ? 'span' : 'a', implode( ' ', $attr ) );
		$output[] = sprintf( '%s', $image_src );
		$output[] = sprintf( '</%1$s>', empty( $attributes['href'] ) ? 'span' : 'a' );
		$output[] = '<div class="banner-content">';

		if ( $atts['title'] ) {
			$output[] = sprintf( '<h3 class="title">%s</h3>', $this->get_vc_link( $atts, $atts['title'] ) );
		}

		if ( $atts['price'] ) {
			$output[] = sprintf( '<div class="price">%s</div>', $atts['price'] );
		}

		$output[] = '</div>';
		$output[] = '</div>';

		return sprintf(
			'<div class="%s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( '', $output )
		);
	}

	/**
	 * Shortcode to display product banner
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function supro_product_banner3( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'image'         => '',
				'image_size'    => 'thumbnail',
				'title'         => '',
				'subtitle'      => '',
				'link'          => '',
				'css_animation' => '',
				'el_class'      => '',
			), $atts
		);

		$css_class = array(
			'supro-product-banner3',
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class']
		);

		$output = $attributes = array();

		$link = vc_build_link( $atts['link'] );

		if ( ! empty( $link['url'] ) ) {
			$attributes['href'] = $link['url'];
		}

		$label               = $link['title'];
		$attributes['class'] = 'banner-image';

		if ( ! $label ) {
			$attributes['title'] = $label;
		}

		if ( ! empty( $link['target'] ) ) {
			$attributes['target'] = $link['target'];
		}

		if ( ! empty( $link['rel'] ) ) {
			$attributes['rel'] = $link['rel'];
		}

		$attr = array();

		foreach ( $attributes as $name => $v ) {
			$attr[] = $name . '="' . esc_attr( $v ) . '"';
		}

		$image_id   = intval( $atts['image'] );
		$image_size = $atts['image_size'];
		$image_src  = '';
		if ( function_exists( 'wpb_getImageBySize' ) ) {
			$image = wpb_getImageBySize(
				array(
					'attach_id'  => $image_id,
					'thumb_size' => $image_size,
				)
			);

			if ( $image['thumbnail'] ) {
				$image_src = $image['thumbnail'];
			} elseif ( $image['p_img_large'] ) {
				$image_src = sprintf( '<img src="%s">', esc_url( $image['p_img_large'][0] ) );
			}

		}

		if ( empty( $image_src ) ) {
			$image_src = wp_get_attachment_image( $image_id, $image_size );
		}
		$output[] = '<div class="banner-wrapper">';
		$output[] = sprintf( '<%1$s %2$s>', empty( $attributes['href'] ) ? 'span' : 'a', implode( ' ', $attr ) );
		$output[] = sprintf( '%s', $image_src );
		$output[] = sprintf( '</%1$s>', empty( $attributes['href'] ) ? 'span' : 'a' );
		$output[] = '<div class="banner-content">';

		if ( $atts['title'] ) {
			$output[] = sprintf( '<h3 class="banner-title">%s</h3>', $this->get_vc_link( $atts, $atts['title'] ) );
		}

		if ( $atts['subtitle'] ) {
			$output[] = sprintf( '<div class="banner-subtitle">%s</div>', $atts['subtitle'] );
		}

		$output[] = '</div>';
		$output[] = '</div>';

		return sprintf(
			'<div class="%s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( '', $output )
		);
	}

	/**
	 * Shortcode to display images
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function supro_images( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'type'           => 'grid',
				'columns'        => '2',
				'images'         => '',
				'image_size'     => '',
				'nav'            => '',
				'dots'           => '',
				'infinite'       => false,
				'autoplay'       => '',
				'autoplay_speed' => 1600,
				'speed'          => 800,
				'css_animation'  => '',
				'el_class'       => '',
			), $atts
		);

		$css_class = array(
			'supro-images supro-photo-swipe',
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		$css     = 'images-' . $atts['type'];
		$columns = intval( $atts['columns'] );
		$css     .= ' row';
		$col     = 'col-sm-6 col-xs-12 col-md-' . ( 12 / $columns );

		$output = array();
		$images = $atts['images'] ? explode( ',', $atts['images'] ) : '';

		if ( $images ) {
			foreach ( $images as $attachment_id ) {
				$item = '';

				$image_full = wp_get_attachment_image_src( $attachment_id, 'full' );

				if ( function_exists( 'wpb_getImageBySize' ) ) {
					$image = wpb_getImageBySize(
						array(
							'attach_id'  => $attachment_id,
							'thumb_size' => $atts['image_size'],
						)
					);

					$item = $image['thumbnail'];
				} else {
					$image = wp_get_attachment_image_src( $attachment_id, $atts['image_size'] );
					if ( $image ) {
						$item = sprintf(
							'<img alt="%s" src="%s">',
							esc_attr( $atts['image_size'] ),
							esc_url( $image[0] )
						);
					}
				}

				$output[] = sprintf(
					'<div class="image-item %s">
						<a href="%s" class="photoswipe" data-width="%s" data-height="%s">%s</a>
					</div>',
					esc_attr( $col ),
					esc_url( $image_full[0] ),
					esc_attr( $image_full[1] ),
					esc_attr( $image_full[2] ),
					$item
				);
			}
		}

		$autoplay_speed = intval( $atts['autoplay_speed'] );
		$speed          = intval( $atts['speed'] );

		if ( $atts['autoplay'] ) {
			$autoplay = true;
		} else {
			$autoplay = false;
		}

		if ( $atts['infinite'] ) {
			$loop = true;
		} else {
			$loop = false;
		}

		if ( $atts['nav'] ) {
			$nav         = true;
			$css_class[] = 'nav-enable';
		} else {
			$nav = false;
		}

		if ( $atts['dots'] ) {
			$dot         = true;
			$css_class[] = 'dots-enable';
		} else {
			$dot = false;
		}

		$id = uniqid( 'supro-images-' );

		$this->l10n['imagesSlides'][ $id ] = array(
			'nav'           => $nav,
			'dot'           => $dot,
			'autoplay'      => $autoplay,
			'autoplaySpeed' => $autoplay_speed,
			'speed'         => $speed,
			'columns'       => $columns,
			'infinite'      => $loop
		);

		$id = 'id="' . esc_attr( $id ) . '"';

		$dir = '';

		if ( is_rtl() ) {
			$dir = 'dir="rtl"';
		}

		return sprintf(
			'<div class="%s">
				<div %s class="group-item %s" %s>%s</div>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$atts['type'] == 'slides' ? $dir : '',
			esc_attr( $css ),
			$atts['type'] == 'slides' ? $id : '',
			implode( '', $output )
		);
	}

	function supro_sale_product( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'style'             => '1',
				'image'             => '',
				'parallax'          => '',
				'name'              => '',
				'title'             => esc_html__( 'Deal of the day', 'supro' ),
				'regular_price'     => '',
				'sale_price'        => '',
				'currency'          => '$',
				'currency_position' => '1',
				'link'              => '',
				'date'              => '',
				'css_animation'     => '',
				'el_class'          => '',
			), $atts
		);

		$css_class = array(
			'supro-sale-product supro-time-format',
			'style-' . $atts['style'],
			$atts['parallax'] ? 'parallax' : '',
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		$price      = $atts['regular_price'];
		$sale_price = $atts['sale_price'];

		if ( $sale_price >= $price ) {
			return esc_html__( 'Sale price must less than Regular price.', 'supro' );
		}

		$percentage = $save = $sale = $button = $name = $title = '';
		$price_html = $sale_html = $src = '';
		$style      = array();

		$currency        = $atts['currency'] ? $atts['currency'] : '';
		$currency_before = $atts['currency_position'] == '1' ? $currency : '';
		$currency_after  = $atts['currency_position'] == '2' ? $currency : '';

		if ( $atts['title'] ) {
			$title = sprintf( '<div class="title">%s</div>', $atts['title'] );
		}

		if ( $atts['name'] ) {
			$name = sprintf( '<h3 class="product-name">%s</h3>', $this->get_vc_link( $atts, $atts['name'] ) );
		}

		if ( $price ) {
			$price_html = sprintf( '<span class="regular-price">%s%s%s</span>', $currency_before, $price, $currency_after );
		}

		if ( $sale_price ) {
			$percentage = '<span class="percentage">' . round( ( ( $price - $sale_price ) / $price ) * 100 ) . '</span>';
			$save       = esc_html__( 'Save ', 'supro' );
			$sale_html  = sprintf( '<span class="sale-price">%s%s%s</span>', $currency_before, $sale_price,$currency_after );
			$sale       = '<div class="onsale"><span>' . $save . $percentage . '%' . '</span></div>';
		}

		$second = 0;
		if ( $atts['date'] ) {
			$second_current = strtotime( date_i18n( 'Y/m/d H:i:s' ) );
			$date           = new DateTime( $atts['date'] );
			if ( $date ) {
				$second_discount = strtotime( date_i18n( 'Y/m/d H:i:s', $date->getTimestamp() ) );
				if ( $second_discount > $second_current ) {
					$second = $second_discount - $second_current;
				}
			}
		}

		$link  = vc_build_link( $atts['link'] );
		$label = $link['title'];

		if ( $link ) {
			$button = sprintf( '%s', $this->get_vc_link( $atts, $label ) );
		}

		$time_html = sprintf( '<div class="supro-countdown-wrapper"><div class="supro-time-countdown supro-countdown">%s</div></div>', $second );

		if ( $atts['image'] ) {
			$image = wp_get_attachment_image_src( $atts['image'], 'full' );
			if ( $image ) {
				$src = $image[0];
			}

			$style[] = 'background-image:url(' . $src . ');';
		}

		$desc = '';

		if ( $atts['style'] == '2' ) {
			$desc = sprintf( '<div class="product-desc">%s</div>', $content );
		}

		return sprintf(
			'<div class="%s" style="%s">
				<div class="container">
					%s%s
					<div class="price">%s%s</div>
					%s%s%s
					<div class="button-section">%s</div>
				</div>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( implode( ' ', $style ) ),
			$title,
			$name,
			$price_html,
			$sale_html,
			$sale,
			$desc,
			$time_html,
			$button
		);
	}

	/**
	 * Shortcode to display latest post
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function supro_latest_post( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'style'          => '1',
				'number'         => '3',
				'btn_text'       => esc_html__( 'READ MORE', 'supro' ),
				'excerpt_length' => '10',
				'css_animation'  => '',
				'el_class'       => '',
			), $atts
		);

		$css_class = array(
			'supro-latest-post blog-grid',
			'style-' . $atts['style'],
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		$output = array();

		$query_args = array(
			'posts_per_page'      => $atts['number'],
			'post_type'           => 'post',
			'ignore_sticky_posts' => true,
		);

		$query = new WP_Query( $query_args );

		while ( $query->have_posts() ) : $query->the_post();

			$size = 'supro-blog-grid-2';

			$category = get_the_terms( get_the_ID(), 'category' );

			$cat_html = $meta = $read_more = '';

			if ( ! is_wp_error( $category ) && $category ) {
				$cat_html = sprintf( '<a href="%s" class="entry-meta entry-cat">%s</a>', esc_url( get_term_link( $category[0], 'category' ) ), esc_html( $category[0]->name ) );
			}

			$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

			$time_string = sprintf(
				$time_string,
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date() )
			);

			$archive_year  = get_the_time( 'Y' );
			$archive_month = get_the_time( 'm' );
			$archive_day   = get_the_time( 'd' );

			$time_string = sprintf(
				'<span class="entry-meta entry-date">' .
				'<a href="%s">%s</a></span>',
				esc_url( get_day_link( $archive_year, $archive_month, $archive_day ) ),
				$time_string
			);

			if ( ! empty( $cat_html ) && ! empty( $time_string ) ) {
				$meta = sprintf(
					'<div class="entry-metas">%s%s</div>',
					$cat_html,
					$time_string
				);
			}

			if ( $atts['btn_text'] ) {
				$read_more = sprintf( '<a class="read-more" href="%s">%s</a>', get_the_permalink(), $atts['btn_text'] );
			}

			$excerpt = $this->supro_addons_content_limit( get_the_excerpt(), intval( $atts['excerpt_length'] ), '' );

			$output[] = sprintf(
				'<div class="col-md-4 col-xs-12 col-sm-6">
					<div class="blog-wrapper">
						<div class="entry-thumbnail">
							<a class="blog-thumb" href="%s">%s</a>
						</div>
						<div class="entry-summary">
							<div class="entry-header">
								%s
								<h2 class="entry-title"><a href="%s">%s</a></h2>
								%s
							</div>
							%s
						</div>
					</div>
				</div>',
				get_the_permalink(),
				get_the_post_thumbnail( get_the_ID(), $size ),
				$atts['style'] == '2' ? $meta : '',
				get_the_permalink(),
				get_the_title(),
				$atts['style'] == '1' ? $meta : '',
				$atts['style'] == '1' ? $read_more : $excerpt
			);

		endwhile;
		wp_reset_postdata();

		return sprintf(
			'<div class="%s">
                <div class="post-list row">%s</div>
            </div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( '', $output )
		);
	}

	/**
	 * Product Grid
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	function supro_products( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'            => '',
				'subtitle'         => '',
				'style'            => '1',
				'limit'            => 6,
				'columns'          => 3,
				'm_columns'        => 1,
				'orderby'          => 'title',
				'order'            => 'ASC',
				'filter'           => 'category',
				'show_all_product' => '1',
				'all_product_text' => esc_html__( 'All Products', 'supro' ),
				'tabs'             => '',
				'category'         => '',
				'link'             => '',
				'load_more'        => false,
				'btn_style'        => 'border-bottom',
				'css_animation'    => '',
				'el_class'         => '',
			), $atts
		);

		if ( ! $this->wc_actived ) {
			return;
		}

		$css_class = array(
			'supro-products-grid supro-products',
			'style-' . $atts['style'],
			$atts['btn_style'],
			$this->get_css_animation( $atts['css_animation'] ),
			'mobile-columns-' . $atts['m_columns'],
			$atts['el_class']
		);

		if ( $atts['load_more'] ) {
			$css_class[] = 'load-more-enabled';
		}

		$output = array();
		$title  = '';
		$filter = array();
		$type   = 'products';
		$attr   = array();

		$product_args = array(
			'limit'     => $atts['limit'],
			'columns'   => $atts['columns'],
			'page'      => 1,
			'orderby'   => $atts['orderby'],
			'order'     => $atts['order'],
			'category'  => '',
			'load_more' => $atts['load_more'],
		);

		$tabs = vc_param_group_parse_atts( $atts['tabs'] );

		if ( $atts['title'] ) {
			$title = sprintf( '<h3 class="title">%s</h3>', $atts['title'] );
		}

		if ( $atts['subtitle'] ) {
			$title .= sprintf( '<div class="subtitle">%s</div>', $atts['subtitle'] );
		}

		if ( $title ) {
			$title = '<div class="section-title">' . $title . '</div>';
		}

		if ( $atts['filter'] == 'category' ) {
			$attr = array(
				'limit'   => intval( $atts['limit'] ),
				'columns' => intval( $atts['columns'] ),
				'orderby' => $atts['orderby'],
				'order'   => $atts['order'],
			);

			if ( intval( $atts['show_all_product'] ) ) {
				$filter[] = sprintf(
					'<li class="active" data-filter="%s">%s</li>',
					$atts['category'] ? $atts['category'] : '',
					$atts['all_product_text']
				);
			} else {
				if ( $atts['category'] ) {
					$cats                     = explode( ',', $atts['category'] );
					$product_args['category'] = $cats[0];

				} else {
					$terms                    = get_terms( 'product_cat' );
					$empty_terms              = array();
					$new_terms                = array_merge( $terms, $empty_terms );
					$product_args['category'] = $new_terms[0]->slug;
				}
			}

			if ( $atts['category'] ) {
				$cats = explode( ',', $atts['category'] );

				foreach ( $cats as $cat ) {
					$cat      = get_term_by( 'slug', $cat, 'product_cat' );
					$filter[] = sprintf( '<li class="" data-filter="%s">%s</li>', esc_attr( $cat->slug ), esc_html( $cat->name ) );
				}
			} else {
				$terms = get_terms( 'product_cat' );

				foreach ( $terms as $term ) {
					$filter[] = sprintf( '<li class="" data-filter="%s">%s</li>', esc_attr( $term->slug ), esc_html( $term->name ) );
				}
			}

		} else {
			$product_args['category'] = $atts['category'];
			$css_class[]              = 'filter-by-group';

			if ( $tabs ) {
				$type = $tabs[0]['products'];

				$i = 0;
				foreach ( $tabs as $tab ) {
					$tab_attr = array(
						'limit'   => intval( $atts['limit'] ),
						'columns' => intval( $atts['columns'] ),
						'orderby' => isset( $tab['orderby'] ) ? $tab['orderby'] : $atts['orderby'],
						'order'   => isset( $tab['order'] ) ? $tab['order'] : $atts['order'],
					);
					if ( isset( $tab['title'] ) ) {
						$filter[] = sprintf( '<li class="" data-filter="%s" data-attr="%s">%s</li>', esc_attr( $tab['products'] ), esc_attr( json_encode( $tab_attr ) ), esc_html( $tab['title'] ) );
					}

					if ( $i == 0 ) {
						$atts['orderby'] = isset( $tab['orderby'] ) ? $tab['orderby'] : $atts['orderby'];
						$atts['order']   = isset( $tab['order'] ) ? $tab['order'] : $atts['order'];
					}

					$i ++;
				}
			}
		}

		$link = '';

		if ( $atts['link'] && $atts['style'] == '3' ) {
			$link = '<div class="product-btn">' . $this->get_vc_link( $atts, '' ) . '</div>';
		}

		$filter_html = '<ul class="nav-filter filter">' . implode( "\n", $filter ) . '</ul>';

		$output[] = $atts['style'] == '3' ? $title : '';
		$output[] = '<div class="product-header">';
		$output[] = $atts['style'] != '3' ? $title : '';
		$output[] = $filter_html;
		$output[] = $link;
		$output[] = '</div>';
		$output[] = '<div class="product-wrapper">';
		$output[] = '<div class="product-loading"><span class="supro-loader"></span></div>';
		$output[] = $this->get_wc_products( $product_args, $type );
		$output[] = '</div>';

		return sprintf(
			'<div class="%s" data-attr="%s" data-load_more="%s" data-nonce="%s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( json_encode( $attr ) ),
			esc_attr( $atts['load_more'] ),
			esc_attr( wp_create_nonce( 'supro_get_products' ) ),
			implode( '', $output )
		);
	}

	/**
	 * Product Carousel
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	function supro_products_carousel( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'            => '',
				'subtitle'         => '',
				'style'            => '1',
				'limit'            => 6,
				'm_columns'        => 1,
				'orderby'          => 'title',
				'order'            => 'ASC',
				'filter'           => 'category',
				'show_all_product' => '1',
				'all_product_text' => esc_html__( 'All Products', 'supro' ),
				'tabs'             => '',
				'category'         => '',
				'nav'              => '',
				'dots'             => '',
				'infinite'         => false,
				'autoplay'         => '',
				'autoplay_speed'   => 2000,
				'speed'            => 1000,
				'slide_to_show'    => 4,
				'slide_to_scroll'  => 1,
				'css_animation'    => '',
				'el_class'         => '',
			), $atts
		);

		if ( ! $this->wc_actived ) {
			return;
		}

		$css_class = array(
			'supro-products-carousel supro-products',
			'style-' . $atts['style'],
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class']
		);

		$output = array();
		$title  = '';
		$filter = array();
		$type   = 'products';
		$attr   = array();

		$product_args = array(
			'limit'    => $atts['limit'],
			'columns'  => 4,
			'page'     => 1,
			'orderby'  => $atts['orderby'],
			'order'    => $atts['order'],
			'category' => '',
		);

		$autoplay_speed = intval( $atts['autoplay_speed'] );
		$speed          = intval( $atts['speed'] );
		$show           = intval( $atts['slide_to_show'] );
		$scroll         = intval( $atts['slide_to_scroll'] );

		if ( $atts['autoplay'] ) {
			$autoplay = true;
		} else {
			$autoplay = false;
		}

		if ( $atts['infinite'] ) {
			$loop = true;
		} else {
			$loop = false;
		}

		if ( $atts['nav'] ) {
			$nav         = true;
			$css_class[] = 'nav-enable';
		} else {
			$nav = false;
		}

		if ( $atts['dots'] ) {
			$dot         = true;
			$css_class[] = 'dots-enable';
		} else {
			$dot = false;
		}

		$id = uniqid( 'supro-product-carousel-' );

		$this->l10n['productsCarousel'][ $id ] = array(
			'nav'           => $nav,
			'dot'           => $dot,
			'autoplay'      => $autoplay,
			'autoplaySpeed' => $autoplay_speed,
			'speed'         => $speed,
			'show'          => $show,
			'scroll'        => $scroll,
			'infinite'      => $loop,
			'show_mobile'   => absint( $atts['m_columns'] )
		);

		$tabs = vc_param_group_parse_atts( $atts['tabs'] );

		if ( $atts['title'] ) {
			$title = sprintf( '<h3 class="title">%s</h3>', $atts['title'] );
		}

		if ( $atts['subtitle'] ) {
			$title .= sprintf( '<div class="subtitle">%s</div>', $atts['subtitle'] );
		}

		if ( $title ) {
			$title = '<div class="section-title">' . $title . '</div>';
		}

		if ( $atts['filter'] == 'category' ) {
			$attr = array(
				'limit'   => intval( $atts['limit'] ),
				'columns' => 4,
				'orderby' => $atts['orderby'],
				'order'   => $atts['order'],
			);

			if ( intval( $atts['show_all_product'] ) ) {
				$filter[] = sprintf(
					'<li class="active" data-filter="%s">%s</li>',
					$atts['category'] ? $atts['category'] : '',
					$atts['all_product_text']
				);
			} else {
				if ( $atts['category'] ) {
					$cats                     = explode( ',', $atts['category'] );
					$product_args['category'] = $cats[0];

				} else {
					$terms                    = get_terms( 'product_cat' );
					$empty_terms              = array();
					$new_terms                = array_merge( $terms, $empty_terms );
					$product_args['category'] = $new_terms[0]->slug;
				}
			}

			if ( $atts['category'] ) {
				$cats = explode( ',', $atts['category'] );

				foreach ( $cats as $cat ) {
					$cat      = get_term_by( 'slug', $cat, 'product_cat' );
					$filter[] = sprintf( '<li class="" data-filter="%s">%s</li>', esc_attr( $cat->slug ), esc_html( $cat->name ) );
				}
			} else {
				$terms = get_terms( 'product_cat' );

				foreach ( $terms as $term ) {
					$filter[] = sprintf( '<li class="" data-filter="%s">%s</li>', esc_attr( $term->slug ), esc_html( $term->name ) );
				}
			}

		} else {
			$product_args['category'] = $atts['category'];
			$css_class[]              = 'filter-by-group';

			if ( $tabs ) {
				$type = $tabs[0]['products'];

				$i = 0;
				foreach ( $tabs as $tab ) {
					$tab_attr = array(
						'limit'   => intval( $atts['limit'] ),
						'columns' => 4,
						'orderby' => isset( $tab['orderby'] ) ? $tab['orderby'] : $atts['orderby'],
						'order'   => isset( $tab['order'] ) ? $tab['order'] : $atts['order'],
					);

					if ( isset( $tab['title'] ) ) {
						$filter[] = sprintf( '<li class="" data-filter="%s" data-attr="%s">%s</li>', esc_attr( $tab['products'] ), esc_attr( json_encode( $tab_attr ) ), esc_html( $tab['title'] ) );
					}

					if ( $i == 0 ) {
						$atts['orderby'] = isset( $tab['orderby'] ) ? $tab['orderby'] : $atts['orderby'];
						$atts['order']   = isset( $tab['order'] ) ? $tab['order'] : $atts['order'];
					}

					$i ++;
				}
			}
		}

		$filter_html = '<ul class="nav-filter filter">' . implode( "\n", $filter ) . '</ul>';

		$output[] = '<div class="product-header">';
		$output[] = $title;
		$output[] = $filter_html;
		$output[] = '</div>';
		$output[] = '<div class="product-wrapper" id="' . esc_attr( $id ) . '">';
		$output[] = '<div class="product-loading"><span class="supro-loader"></span></div>';
		$output[] = $this->get_wc_products( $product_args, $type );
		$output[] = '</div>';

		return sprintf(
			'<div class="%s" data-attr="%s" data-load_more="0" data-nonce="%s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( json_encode( $attr ) ),
			esc_attr( wp_create_nonce( 'supro_get_products' ) ),
			implode( '', $output )
		);
	}

	/**
	 * Shortcode to display sliders
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function supro_sliders( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'nav'            => '',
				'dots'           => '',
				'infinite'       => false,
				'autoplay'       => '',
				'autoplay_speed' => 2000,
				'initial_slide'  => 1,
				'speed'          => 1000,
				'slider_option'  => '',
				'css_animation'  => '',
				'el_class'       => '',
			), $atts
		);

		$css_class = array(
			'supro-sliders',
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		$autoplay_speed = intval( $atts['autoplay_speed'] );
		$initial        = intval( $atts['initial_slide'] );
		$speed          = intval( $atts['speed'] );

		if ( $atts['autoplay'] ) {
			$autoplay = true;
		} else {
			$autoplay = false;
		}

		if ( $atts['infinite'] ) {
			$loop = true;
		} else {
			$loop = false;
		}

		if ( $atts['nav'] ) {
			$nav         = true;
			$css_class[] = 'nav-enable';
		} else {
			$nav = false;
		}

		if ( $atts['dots'] ) {
			$dot         = true;
			$css_class[] = 'dots-enable';
		} else {
			$dot = false;
		}

		$id = uniqid( 'supro-slider-' );

		$this->l10n['slider'][ $id ] = array(
			'nav'           => $nav,
			'dot'           => $dot,
			'autoplay'      => $autoplay,
			'autoplaySpeed' => $autoplay_speed,
			'initial'       => $initial,
			'speed'         => $speed,
			'infinite'      => $loop
		);

		$options = vc_param_group_parse_atts( $atts['slider_option'] );
		$output  = array();

		foreach ( $options as $option ) {
			$size  = 'full';
			$image = $title = $subtitle = $desc = $button = $color = '';
			if ( isset( $option['color'] ) && $option['color'] ) {
				$color = $option['color'];
			}

			if ( isset( $option['image_size'] ) ) {
				$size = $option['image_size'];
			}

			if ( isset( $option['image'] ) && $option['image'] ) {
				$size_array = explode( 'x', $size );
				$size       = count( $size_array ) == 1 ? $size : $size_array;

				$img = wp_get_attachment_image_src( $option['image'], $size );

				$image = sprintf(
					'<img src="%s" alt=""/>',
					esc_attr( $img[0] )
				);
			}

			$link = isset( $option['link'] ) ? vc_build_link( $option['link'] ) : '';

			$label = $link ? $link['title'] : '';

			if ( isset( $option['title'] ) && $option['title'] ) {
				$title = sprintf( '<h2>%s</h2>', $this->get_vc_link( $option, $option['title'] ) );
			}

			if ( isset( $option['subtitle'] ) && $option['subtitle'] ) {
				$subtitle = sprintf( '<span class="subtitle" style="color:%s;">%s</span>', esc_attr( $color ), $option['subtitle'] );
			}

			if ( isset( $option['description'] ) && $option['description'] ) {
				$desc = $option['description'];
				$desc = sprintf( '<div class="desc">%s</div>', $desc );
			}

			$button = '<div class="slide-button">' . $this->get_vc_link( $option, $label ) . '</div>';

			$output[] = sprintf(
				'<div class="supro-slide">%s<div class="slide-content">%s%s%s%s</div></div>',
				$image,
				$subtitle,
				$title,
				$desc,
				$button
			);
		}

		$dir = '';

		if ( is_rtl() ) {
			$dir = 'dir="rtl"';
		}

		return sprintf(
			'<div class="%s">
				<div %s class="slider-wrapper" id="%s">%s</div>
				<div class="slider-arrows"><div class="container"></div></div>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$dir,
			esc_attr( $id ),
			implode( '', $output )
		);
	}

	/**
	 * Testimonial
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	function supro_testimonial( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'         => '',
				'name'          => '',
				'css_animation' => '',
				'el_class'      => '',
			), $atts
		);

		$css_class = array(
			'supro-testimonial',
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class']
		);

		$title = $name = '';

		if ( $atts['name'] ) {
			$name = sprintf( '<h4 class="name">%s</h4>', $atts['name'] );
		}

		if ( $atts['title'] ) {
			$title = sprintf( '<div class="title">%s</div>', $atts['title'] );
		}

		return sprintf(
			'<div class="%s">%s<div class="testi-content">%s</div>%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$title,
			$content,
			$name
		);
	}

	/**
	 * Shortcode to display counter
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function supro_counter( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'color_scheme'   => 'dark',
				'columns'        => '1',
				'counter_option' => '',
				'css_animation'  => '',
				'el_class'       => '',
			), $atts
		);

		$css_class = array(
			'supro-counter',
			'columns-' . $atts['columns'],
			$atts['color_scheme'] . '-color',
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		$option = vc_param_group_parse_atts( $atts['counter_option'] );
		$output = array();

		$columns = intval( $atts['columns'] );
		$col_css = 'col-xs-6 col-sm-12 col-md-' . 12 / $columns;
		$counter = '';

		foreach ( $option as $o ) {
			if ( isset( $o['value'] ) && $o['value'] ) {
				$counter = sprintf( '<div class="counter"><span class="value">%s</span></div>', $o['value'] );
			}

			if ( isset( $o['title'] ) && $o['title'] ) {
				$counter .= sprintf( '<h5 class="title">%s</h5>', $o['title'] );
			}

			$output[] = sprintf(
				'<div class="counter-content %s">%s</div>',
				esc_attr( $col_css ),
				$counter
			);
		}

		return sprintf(
			'<div class="%s"><div class="list-counter">%s</div></div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( '', $output )
		);
	}

	/**
	 * Get FAQs
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function supro_faqs( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'         => '',
				'faqs'          => '',
				'css_animation' => '',
				'el_class'      => '',
			), $atts
		);

		if ( ! $atts['faqs'] ) {
			return '';
		}

		$css_class = array(
			'supro-faq_group',
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		$faqs = (array) json_decode( urldecode( $atts['faqs'] ), true );

		$output = array();

		$output[] = sprintf( '<div class="col-gtitle col-md-3 col-sm-3 col-xs-12"><h2 class="g-title">%s</h2></div>', $atts['title'] );

		$output[] = '<div class="col-md-9 col-sm-9 col-xs-12">';
		$output[] = '<div class="row">';
		foreach ( $faqs as $faq ) {
			$output[] = '<div class="faq-item">';

			if ( isset( $faq['title'] ) && $faq['title'] ) {
				$output[] = sprintf( '<div class="col-title col-md-5 col-sm-5 col-xs-12"> <h3 class="title">%s</h3></div>', $faq['title'] );
			}

			if ( isset( $faq['desc'] ) && $faq['desc'] ) {
				$output[] = sprintf( '<div class="col-md-7 col-sm-7 col-xs-12"> <div class="desc">%s</div></div>', $faq['desc'] );
			}

			$output[] = '</div>';
		}

		$output[] = '</div>';
		$output[] = '</div>';

		return sprintf(
			'<div class="%s">' .
			'<div class="row">' .
			'%s' .
			'</div>' .
			'</div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( ' ', $output )
		);
	}

	/**
	 * Shortcode to display partner
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function supro_partner( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'images'              => '',
				'image_size'          => 'thumbnail',
				'custom_links'        => '',
				'custom_links_target' => '_self',
				'css_animation'       => '',
				'el_class'            => '',
			), $atts
		);

		$css_class = array(
			'supro-partner',
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		$output       = array();
		$custom_links = '';

		if ( function_exists( 'vc_value_from_safe' ) ) {
			$custom_links = vc_value_from_safe( $atts['custom_links'] );
			$custom_links = explode( ',', $custom_links );
		}

		$images = $atts['images'] ? explode( ',', $atts['images'] ) : '';

		if ( $images ) {
			$i = 0;
			foreach ( $images as $attachment_id ) {
				$image = wp_get_attachment_image( $attachment_id, $atts['image_size'] );
				if ( $image ) {
					$link = '';
					if ( $custom_links && isset( $custom_links[ $i ] ) ) {
						$link = preg_replace( '/<br \/>/iU', '', $custom_links[ $i ] );

						if ( $link ) {
							$link = 'href="' . esc_url( $link ) . '"';
						}
					}

					$output[] = sprintf(
						'<div class="partner-item">
							<a %s target="%s" >%s</a>
						</div>',
						$link,
						esc_attr( $atts['custom_links_target'] ),
						$image
					);
				}
				$i ++;
			}
		}

		return sprintf(
			'<div class="%s">
				<div class="list-item">%s</div>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( '', $output )
		);
	}

	/**
	 * Shortcode to display counter
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function supro_members( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'columns'       => '3',
				'member_option' => '',
				'css_animation' => '',
				'el_class'      => '',
			), $atts
		);

		$css_class = array(
			'supro-members',
			'columns-' . $atts['columns'],
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		$options = vc_param_group_parse_atts( $atts['member_option'] );
		$output  = array();

		$columns = intval( $atts['columns'] );
		$col_css = 'col-xs-6 col-sm-6 col-md-' . 12 / $columns;

		foreach ( $options as $option ) {
			$image_size = 'full';
			$image_id   = $name = $job = $image_html = '';

			if ( isset( $option['image'] ) && $option['image'] ) {
				$image_id = intval( $option['image'] );
			}

			if ( isset( $option['image_size'] ) && $option['image_size'] ) {
				$image_size = $option['image_size'];
			}

			if ( $image_id ) {
				$image_html = sprintf( '%s', $this->supro_vc_get_image( $image_id, $image_size ) );
			}

			if ( isset( $option['job'] ) && $option['job'] ) {
				$job = sprintf( '<div class="job">%s</div>', $option['job'] );
			}

			if ( isset( $option['name'] ) && $option['name'] ) {
				$name = sprintf( '<h4 class="name">%s</h4>', $option['name'] );
			}

			$output[] = sprintf(
				'<div class="member %s"><div class="member-wrapper">%s<div class="member-info">%s%s</div></div></div>',
				esc_attr( $col_css ),
				$image_html,
				$name,
				$job
			);
		}

		return sprintf(
			'<div class="%s"><div class="row">%s</div></div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( '', $output )
		);
	}

	/**
	 * Video banner shortcode
	 *
	 * @since  1.0
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function supro_video( $atts, $content = null ) {
		$atts = shortcode_atts(
			array(
				'video'               => '',
				'video_icon'          => '',
				'video_icon_size'     => '',
				'min_height'          => '500',
				'image'               => '',
				'image_size'          => '',
				'css_animation'       => '',
				'el_class'            => '',
				'video_bg_color'      => '',
				'video_bg_h'          => '',
				'video_bg_v'          => '',
				'video_bg_repeat'     => '',
				'video_bg_attachment' => '',
				'video_bg_size'       => '',
			), $atts
		);

		if ( empty( $atts['video'] ) ) {
			return '';
		}

		$css_class = array(
			'supro-video-banner text-center',
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		$min_height = intval( $atts['min_height'] );

		$video_html = $src = '';
		$style      = array();
		$video_url  = $atts['video'];
		$video_w    = '1024';
		$video_h    = '768';

		if ( $min_height ) {
			$style[] = 'min-height:' . $min_height . 'px;';
		}

		if ( $atts['video_icon'] ) {
			$video_icon = wp_get_attachment_image_src( $atts['video_icon'], $atts['video_icon_size'] );
			$icon       = $video_icon[0];

		} else {
			$icon = SUPRO_ADDONS_URL . 'images/play-icon.png';
		}

		if ( $atts['video_bg_color'] ) {
			$style[] = 'background-color:' . $atts['video_bg_color'] . ';';
		}

		if ( $atts['image'] ) {
			$image = wp_get_attachment_image_src( $atts['image'], $atts['image_size'] );
			if ( $image ) {
				$src = $image[0];
			}

			$style[] = 'background-image:url(' . $src . ');';
		}

		if ( $atts['video_bg_h'] || $atts['video_bg_v'] ) {
			$style[] = "background-position: {$atts['video_bg_h']} {$atts['video_bg_v']};";
		}

		if ( $atts['video_bg_repeat'] ) {
			$style[] = 'background-repeat:' . $atts['video_bg_repeat'] . ';';
		}

		if ( $atts['video_bg_attachment'] ) {
			$style[] = 'background-attachment:' . $atts['video_bg_attachment'] . ';';
		}

		if ( $atts['video_bg_size'] ) {
			$style[] = 'background-size:' . $atts['video_bg_size'] . ';';
		}

		if ( filter_var( $video_url, FILTER_VALIDATE_URL ) ) {
			$attr = array(
				'width'  => $video_w,
				'height' => $video_h
			);
			if ( $oembed = @wp_oembed_get( $video_url, $attr ) ) {
				$video_html = $oembed;
			}

			if ( $video_html ) {
				$video_html = sprintf( '<div class="mf-wrapper"><div class="mf-video-wrapper">%s</div></div>', $video_html );
			}
		}

		if ( function_exists( 'wpb_js_remove_wpautop' ) ) {
			$content = wpb_js_remove_wpautop( $content, true );
		}

		return sprintf(
			'<div class="%s" style="%s">
				<div class="supro-video-content">
					<div class="content">%s</div>
					<a href="#" data-href="%s" class="photoswipe"><span class="video-play"><img src="%s" alt=""></span></a>
				</div>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( implode( ' ', $style ) ),
			$content,
			esc_attr( $video_html ),
			esc_url( $icon )
		);
	}

	/**
	 * Testimonial
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	function supro_contact_box( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'         => '',
				'hide_label'    => '',
				'display'       => 'horizontal',
				'location'      => '',
				'phone'         => '',
				'email'         => '',
				'css_animation' => '',
				'el_class'      => '',
			), $atts
		);

		$css_class = array(
			'supro-contact-box',
			$atts['display'],
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class']
		);

		if ( intval( $atts['hide_label'] ) ) {
			$css_class[] = 'hide-label';
		}

		$output = array();
		$css    = '';

		if ( $atts['display'] == 'horizontal' ) {
			$css = 'row';
		}

		if ( $atts['title'] ) {
			$output[] = sprintf(
				'<div class="title"><h3>%s</h3></div>',
				$atts['title']
			);
		}

		if ( $atts['location'] ) {
			$output[] = sprintf(
				'<div class="contact-info localtion"><h4 class="box-label">%s</h4>%s</div>',
				esc_html__( 'LOCATION', 'supro' ),
				$atts['location']
			);
		}

		if ( $atts['phone'] ) {
			$output[] = sprintf(
				'<div class="contact-info phone"><h4 class="box-label">%s</h4>%s</div>',
				esc_html__( 'PHONE NUMBER', 'supro' ),
				$atts['phone']
			);
		}

		if ( $atts['email'] ) {
			$output[] = sprintf(
				'<div class="contact-info email"><h4 class="box-label">%s</h4>%s</div>',
				esc_html__( 'EMAIL', 'supro' ),
				$atts['email']
			);
		}

		return sprintf(
			'<div class="%s"><div class="box-wrapper %s">%s</div></div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $css ),
			implode( '', $output )
		);
	}

	/**
	 * Get member
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function supro_socials( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'         => '',
				'style'         => 'border',
				'color'         => 'dark',
				'socials'       => '',
				'css_animation' => '',
				'el_class'      => '',
			), $atts
		);

		$css_class = array(
			'supro-socials',
			'socials-' . $atts['style'],
			'text-' . $atts['color'],
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class']
		);

		$output = array();

		if ( $atts['title'] ) {
			$output[] = sprintf( '<h3 class="title">%s</h3>', $atts['title'] );
		}

		$socials = (array) json_decode( urldecode( $atts['socials'] ), true );

		$socials_html = '';
		if ( $socials ) {
			foreach ( $socials as $social ) {
				$text_html = '';
				if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
					vc_icon_element_fonts_enqueue( $social['icon_type'] );
				}

				if ( isset( $social['icon_type'] ) && $social['icon_type'] && ! empty( $social[ 'icon_' . $social['icon_type'] ] ) ) {
					$text_html = '<i class="' . esc_attr( $social[ 'icon_' . $social['icon_type'] ] ) . '"></i>';
				}

				$link_html = '';
				if ( isset( $social['link'] ) && $social['link'] ) {
					if ( function_exists( 'vc_build_link' ) ) {
						$link = array_filter( vc_build_link( $social['link'] ) );
						if ( ! empty( $link ) ) {
							$link_html = sprintf(
								'<a href="%s" class="link" %s%s>%s</a>',
								esc_url( $link['url'] ),
								! empty( $link['target'] ) ? ' target="' . esc_attr( $link['target'] ) . '"' : '',
								! empty( $link['rel'] ) ? ' rel="' . esc_attr( $link['rel'] ) . '"' : '',
								$text_html
							);
						}
					}
				}

				if ( empty ( $link_html ) ) {
					$link_html = sprintf(
						'<span class="text">%s</span>',
						$text_html
					);
				}

				$socials_html .= $link_html;
			}

		}

		if ( $socials_html ) {
			$output[] = sprintf( '<div class="socials">%s</div>', $socials_html );
		}

		return sprintf(
			'<div class="%s">
				%s
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( '', $output )
		);

	}

	/**
	 * Shortcode to display newsletter
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function supro_newsletter( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'style'         => 'space-between',
				'form'          => '',
				'title'         => '',
				'subtitle'      => '',
				'bg_color'      => '',
				'text_color'    => '',
				'shape'         => 'square',
				'css_animation' => '',
				'el_class'      => '',
			), $atts
		);

		if ( ! $atts['form'] ) {
			return;
		}

		$class_name = 'supro-newletter__' . $this->get_id_number( __FUNCTION__ );

		$css_class = array(
			'supro-newsletter',
			$atts['style'] . '-style',
			'form-' . $atts['shape'],
			$atts['el_class'],
			$class_name,
			$this->get_css_animation( $atts['css_animation'] )
		);

		$title = $subtitle = '';

		if ( $atts['title'] ) {
			$title = sprintf( '<h2 class="title">%s</h2>', $atts['title'] );
		}

		if ( $atts['subtitle'] ) {
			$subtitle = sprintf( '<div class="subtitle">%s</div>', $atts['subtitle'] );
		}

		if ( $atts['style'] == 'space-between' ) {
			$title_col = 'col-md-5 col-xs-12 col-sm-12';
			$form_col  = 'col-md-7 col-xs-12 col-sm-12';
		} else {
			$title_col = $form_col = 'col-md-12 col-xs-12 col-sm-12';
		}

		$css = '';

		if ( $atts['bg_color'] ) {
			$css_class[] = 'has-bg';
			$bg          = $atts['bg_color'];

			$css .= '
			.' . $class_name . ' .mc4wp-form .mc4wp-form-fields { background-color:' . esc_attr( $bg ) . '; }
			';
		}

		if ( $atts['text_color'] ) {
			$css .= '
			.' . $class_name . ' .mc4wp-form input[type="email"] { color:' . esc_attr( $atts['text_color'] ) . '; }
			.' . $class_name . ' .mc4wp-form input[type="submit"] { color:' . esc_attr( $atts['text_color'] ) . '; }
			.' . $class_name . ' .mc4wp-form .mc4wp-form-fields:after { color:' . esc_attr( $atts['text_color'] ) . '; }
			.' . $class_name . ' .mc4wp-form ::-webkit-input-placeholder { color:' . esc_attr( $atts['text_color'] ) . '; }
			.' . $class_name . ' .mc4wp-form :-moz-placeholder { color:' . esc_attr( $atts['text_color'] ) . '; }
			.' . $class_name . ' .mc4wp-form ::-moz-placeholder { color:' . esc_attr( $atts['text_color'] ) . '; }
			.' . $class_name . ' .mc4wp-form :-ms-input-placeholder { color:' . esc_attr( $atts['text_color'] ) . '; }
			';
		}

		return sprintf(
			'<style type="text/css">%s</style>
			<div class="%s">
				<div class="row form-row">
					<div class="title-area %s">%s%s</div>
					<div class="form-area %s">%s</div>
				</div>
			</div>',
			$css,
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $title_col ),
			$title,
			$subtitle,
			esc_attr( $form_col ),
			do_shortcode( '[mc4wp_form id="' . esc_attr( $atts['form'] ) . '"]' )
		);
	}

	/*
	 * Instagram
	 */
	function supro_instagram( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'methods'       => 'user',
				'user'          => '',
				'token'         => '',
				'number'        => 8,
				'columns'       => '8',
				'video'         => false,
				'size'          => 'small',
				'target'        => '_blank',
				'css_animation' => '',
				'el_class'      => '',
			), $atts
		);

		$css_class = array(
			'supro-instagram-shortcode',
			'supro-instagram-get-access-' . $atts['methods'],
			'columns-' . intval( $atts['columns'] ),
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		$output = $button = '';

		if ( $atts['methods'] == 'user' ) {
			$media = $this->scrape_instagram( $atts['user'] );
		} else {
			$media = $this->instagram_get_photos_by_token( $atts['token'] );
		}

		if ( is_wp_error( $media ) ) {
			echo wp_kses_post( $media->get_error_message() );
		} else {
			$list = array();

			if ( ! $atts['video'] ) {
				$media = array_filter( $media, array( $this, 'image_only_filter' ) );
			}

			$media = array_slice( $media, 0, $atts['number'] );

			foreach ( $media as $item ) {
				$list[] = sprintf(
					'<a href="%s" target="%s" class="supro-instagram"><i class="social_instagram"></i><img src="%s" alt="%s"></a>',
					esc_url( $item['link'] ),
					esc_attr( $atts['target'] ),
					esc_url( $item[ $atts['size'] ] ),
					esc_attr( $item['description'] )
				);
			}

			if ( $list ) {
				$output = sprintf(
					'<div class="instagram-size-%s clearfix">%s</div>',
					esc_attr( $atts['size'] ),
					implode( "\n\t", $list )
				);
			}
		}

		return sprintf(
			'<div class="%s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$output
		);
	}

	function supro_instagram_2( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'token'         => '',
				'number'        => 8,
				'columns'       => '8',
				't_columns'     => '8',
				'm_columns'     => '4',
				'size'          => 'small',
				'target'        => '_blank',
				'css_animation' => '',
				'el_class'      => '',
			), $atts
		);

		$css_class = array(
			'supro-instagram-2',
			'columns-' . intval( $atts['columns'] ),
			'tablet-columns-' . intval( $atts['t_columns'] ),
			'mobile-columns-' . intval( $atts['m_columns'] ),
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		$output = $button = '';

		$media = $this->supro_get_instagram_images( $atts['token'], $atts['number'] );

		if ( is_wp_error( $media ) ) {
			echo wp_kses_post( $media->get_error_message() );
		} else {
			$list  = array();
			$media = array_slice( $media, 0, $atts['number'] );

			foreach ( $media as $item ) {
				$srcset  = array(
					$item['images']['low_resolution']['url'] . ' 320w',
					$item['images']['standard_resolution']['url'] . ' 640w',
					$item['images']['standard_resolution']['url'] . ' 2x',
				);
				$sizes   = array(
					'(max-width: 1400px) 320px',
					'320px',
				);
				$caption = is_array( $item['caption'] ) && isset( $item['caption']['text'] ) ? $item['caption']['text'] : $item['caption'];

				$image = sprintf(
					'<img src="%s" alt="%s" srcset="%s" sizes="%s">',
					esc_url( $item['images']['low_resolution']['url'] ),
					esc_attr( $caption ),
					esc_attr( implode( ', ', $srcset ) ),
					esc_attr( implode( ', ', $sizes ) )
				);

				$style = 'style="background-image: url(' . esc_url( $item['images']['low_resolution']['url'] ) . ')"';

				$list[] = sprintf(
					'<a href="%s" target="%s" rel="nofollow" %s><span></span><i class="social_instagram"></i>%s</a>',
					esc_url( $item['link'] ),
					esc_attr( $atts['target'] ),
					$style,
					$image
				);
			}

			if ( $list ) {
				$output = sprintf(
					'<div class="instagram-wrapper">%s</div>',
					implode( "\n\t", $list )
				);
			}
		}

		return sprintf(
			'<div class="%s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$output
		);
	}

	/**
	 * Comming soon shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function supro_coming_soon( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'date'     => '',
				'color'    => 'dark',
				'el_class' => '',
			), $atts
		);

		$css_class = array(
			'supro-coming-soon supro-time-format',
			'text-' . $atts['color'],
			$atts['el_class'],
		);

		$second = 0;
		if ( $atts['date'] ) {
			$second_current = strtotime( date_i18n( 'Y/m/d H:i:s' ) );
			$date           = new DateTime( $atts['date'] );
			if ( $date ) {
				$second_discount = strtotime( date_i18n( 'Y/m/d H:i:s', $date->getTimestamp() ) );
				if ( $second_discount > $second_current ) {
					$second = $second_discount - $second_current;
				}
			}
		}

		$time_html = sprintf( '<div class="supro-time-countdown supro-countdown">%s</div>', $second );

		return sprintf(
			'<div class="%s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$time_html
		);
	}

	/*
	 * GG Maps shortcode
	 */
	function supro_gmap( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'api_key'       => '',
				'marker'        => '',
				'info'          => '',
				'width'         => '',
				'height'        => '400',
				'zoom'          => '16',
				'css_animation' => '',
				'el_class'      => '',
			), $atts
		);

		$class = array(
			'supro-map-shortcode',
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		$style = '';
		if ( $atts['width'] ) {
			$unit = 'px;';
			if ( strpos( $atts['width'], '%' ) ) {
				$unit = '%;';
			}

			$atts['width'] = intval( $atts['width'] );
			$style         .= 'width: ' . $atts['width'] . $unit;
		}
		if ( $atts['height'] ) {
			$unit = 'px;';
			if ( strpos( $atts['height'], '%' ) ) {
				$unit = '%;';
			}

			$atts['height'] = intval( $atts['height'] );
			$style          .= 'height: ' . $atts['height'] . $unit;
		}
		if ( $atts['zoom'] ) {
			$atts['zoom'] = intval( $atts['zoom'] );
		}

		$id   = uniqid( 'mf_map_' );
		$html = sprintf(
			'<div class="%s"><div id="%s" class="supro-map" style="%s"></div></div>',
			implode( ' ', $class ),
			$id,
			$style
		);

		$lats    = array();
		$lng     = array();
		$info    = array();
		$i       = 0;
		$fh_info = vc_param_group_parse_atts( $atts['info'] );

		foreach ( $fh_info as $key => $value ) {

			$lats[] = isset( $value['lat'] ) ? $value['lat'] : '';
			$lng[]  = isset( $value['lng'] ) ? $value['lng'] : '';
			$info[] = isset( $value['details'] ) ? $value['details'] : '';

			$i ++;
		}

		$marker = '';
		if ( $atts['marker'] ) {

			if ( filter_var( $atts['marker'], FILTER_VALIDATE_URL ) ) {
				$marker = $atts['marker'];
			} else {
				$attachment_image = wp_get_attachment_image_src( intval( $atts['marker'] ), 'full' );
				$marker           = $attachment_image ? $attachment_image[0] : '';
			}
		}

		$this->api_key = $atts['api_key'];

		$this->l10n['map'][ $id ] = array(
			'type'   => 'normal',
			'lat'    => $lats,
			'lng'    => $lng,
			'zoom'   => $atts['zoom'],
			'marker' => $marker,
			'height' => $atts['height'],
			'info'   => $info,
			'number' => $i,
		);

		return $html;

	}

	/**
	 * Helper function to get coordinates for map
	 *
	 * @since 1.0.0
	 *
	 * @param string $address
	 * @param bool $refresh
	 *
	 * @return array
	 */
	function get_coordinates( $address, $refresh = false ) {
		$address_hash = md5( $address );
		$coordinates  = get_transient( $address_hash );
		$results      = array( 'lat' => '', 'lng' => '' );

		if ( $refresh || $coordinates === false ) {
			$args     = array( 'address' => urlencode( $address ), 'sensor' => 'false' );
			$url      = add_query_arg( $args, 'http://maps.googleapis.com/maps/api/geocode/json' );
			$response = wp_remote_get( $url );

			if ( is_wp_error( $response ) ) {
				$results['error'] = esc_html__( 'Can not connect to Google Maps APIs', 'supro' );

				return $results;
			}

			$data = wp_remote_retrieve_body( $response );

			if ( is_wp_error( $data ) ) {
				$results['error'] = esc_html__( 'Can not connect to Google Maps APIs', 'supro' );

				return $results;
			}

			if ( $response['response']['code'] == 200 ) {
				$data = json_decode( $data );

				if ( $data->status === 'OK' ) {
					$coordinates = $data->results[0]->geometry->location;

					$results['lat']     = $coordinates->lat;
					$results['lng']     = $coordinates->lng;
					$results['address'] = (string) $data->results[0]->formatted_address;

					// cache coordinates for 3 months
					set_transient( $address_hash, $results, 3600 * 24 * 30 * 3 );
				} elseif ( $data->status === 'ZERO_RESULTS' ) {
					$results['error'] = esc_html__( 'No location found for the entered address.', 'supro' );
				} elseif ( $data->status === 'INVALID_REQUEST' ) {
					$results['error'] = esc_html__( 'Invalid request. Did you enter an address?', 'supro' );
				} else {
					$results['error'] = esc_html__( 'Something went wrong while retrieving your map, please ensure you have entered the short code correctly.', 'supro' );
				}
			} else {
				$results['error'] = esc_html__( 'Unable to contact Google API service.', 'supro' );
			}
		} else {
			$results = $coordinates; // return cached results
		}

		return $results;
	}

	/**
	 * Get images from Instagram profile page
	 *
	 * @since 2.0
	 *
	 * @param string $username
	 *
	 * @return array | WP_Error
	 */
	protected function scrape_instagram( $username ) {

		$username = trim( strtolower( $username ) );

		switch ( substr( $username, 0, 1 ) ) {
			case '#':
				$url              = 'https://instagram.com/explore/tags/' . str_replace( '#', '', $username );
				$transient_prefix = 'h';
				break;

			default:
				$url              = 'https://instagram.com/' . str_replace( '@', '', $username );
				$transient_prefix = 'u';
				break;
		}

		if ( false === ( $instagram = get_transient( 'insta-a10-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ) ) ) ) {

			$remote = wp_remote_get( $url );

			if ( is_wp_error( $remote ) ) {
				return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'supro' ) );
			}

			if ( 200 !== wp_remote_retrieve_response_code( $remote ) ) {
				return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'supro' ) );
			}

			$shards      = explode( 'window._sharedData = ', $remote['body'] );
			$insta_json  = explode( ';</script>', $shards[1] );
			$insta_array = json_decode( $insta_json[0], true );

			if ( ! $insta_array ) {
				return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'supro' ) );
			}

			if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
				$images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
			} elseif ( isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
				$images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
			} else {
				return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'supro' ) );
			}

			if ( ! is_array( $images ) ) {
				return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'supro' ) );
			}

			$instagram = array();

			foreach ( $images as $image ) {
				if ( true === $image['node']['is_video'] ) {
					$type = 'video';
				} else {
					$type = 'image';
				}

				$caption = __( 'Instagram Image', 'supro' );
				if ( ! empty( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
					$caption = wp_kses( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'], array() );
				}

				$instagram[] = array(
					'description' => $caption,
					'link'        => trailingslashit( '//instagram.com/p/' . $image['node']['shortcode'] ),
					'thumbnail'   => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][0]['src'] ),
					'small'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][2]['src'] ),
					'large'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] ),
					'type'        => $type,
				);
			} // End foreach().

			// do not set an empty transient - should help catch private or empty accounts.
			if ( ! empty( $instagram ) ) {
				$instagram = base64_encode( serialize( $instagram ) );
				set_transient( 'supro_instagram-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ), $instagram, HOUR_IN_SECONDS * 2 );
			}
		}

		if ( ! empty( $instagram ) ) {

			return unserialize( base64_decode( $instagram ) );

		} else {

			return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'supro' ) );

		}
	}

	/**
	 * Get instagram photo
	 *
	 */

	protected function instagram_get_photos_by_token( $instagram_access_token ) {
		if ( empty( $instagram_access_token ) ) {
			return new WP_Error( 'no_access_token', esc_html__( 'No access token', 'supro' ) );
		}

		$transient_prefix = 'token';
		$instagram        = get_transient( 'supro_instagram-' . $transient_prefix . '-' . sanitize_title_with_dashes( $instagram_access_token ) );
		if ( false === $instagram ) {

			$url = 'https://api.instagram.com/v1/users/self/media/recent?access_token=' . $instagram_access_token;

			$remote = wp_remote_get( $url );

			if ( is_wp_error( $remote ) ) {
				return new WP_Error( 'unable_communicate', esc_html__( 'Unable to communicate with Instagram.', 'supro' ) );

			}

			if ( 200 != wp_remote_retrieve_response_code( $remote ) ) {
				return new WP_Error( 'invalid_200', esc_html__( 'Instagram did not return a 200.', 'supro' ) );
			}

			$insta_array = json_decode( $remote['body'], true );

			if ( ! $insta_array ) {
				return new WP_Error( 'invalid_data', esc_html__( 'Instagram has returned invalid data.', 'supro' ) );
			}

			if ( isset( $insta_array['data'] ) ) {
				$images = $insta_array['data'];
			} else {
				return new WP_Error( 'invalid_data', esc_html__( 'Instagram has returned invalid data.', 'supro' ) );
			}

			if ( ! is_array( $images ) ) {
				return new WP_Error( 'invalid_data', esc_html__( 'Instagram has returned invalid data.', 'supro' ) );
			}

			$instagram = array();

			foreach ( $images as $image ) {
				$caption = esc_html__( 'Instagram Image', 'supro' );
				if ( ! empty( $image['caption'] ) ) {
					$caption = wp_kses( $image['caption']['text'], array() );
				}

				$instagram[] = array(
					'description' => $caption,
					'link'        => $image['link'],
					'thumbnail'   => $image['images']['thumbnail']['url'], // 150x150
					'small'       => $image['images']['low_resolution']['url'], // 320x320
					'large'       => $image['images']['standard_resolution']['url'], // 640x640
					'type'        => $image['type'],
				);

			}

			// do not set an empty transient - should help catch private or empty accounts.
			if ( ! empty( $instagram ) ) {
				$instagram = serialize( $instagram );
				set_transient( 'supro_instagram-' . $transient_prefix . '-' . sanitize_title_with_dashes( $instagram_access_token ), $instagram, HOUR_IN_SECONDS * 2 );
			}
		}

		if ( ! empty( $instagram ) ) {
			return unserialize( $instagram );

		} else {

			return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'supro' ) );

		}
	}

	/**
	 * Get Instagram images
	 *
	 * @param int $limit
	 *
	 * @return array|WP_Error
	 */
	protected function supro_get_instagram_images( $instagram_access_token, $limit = 12 ) {
		if ( empty( $instagram_access_token ) ) {
			return new WP_Error( 'instagram_no_access_token', esc_html__( 'No access token', 'supro' ) );
		}

		$user = $this->supro_get_instagram_user( $instagram_access_token );

		if ( ! $user || is_wp_error( $user ) ) {
			return $user;
		}

		$transient_key = 'supro_instagram_photos_' . sanitize_title_with_dashes( $user['username'] . '__' . $limit );
		$images        = get_transient( $transient_key );

		if ( false === $images || empty( $images ) ) {
			$url = add_query_arg(
				array(
					'count'        => $limit,
					'access_token' => $instagram_access_token,
				), 'https://api.instagram.com/v1/users/self/media/recent/'
			);

			$remote = wp_remote_retrieve_body( wp_remote_get( $url ) );
			$data   = json_decode( $remote, true );
			$images = array();

			if ( $data['meta']['code'] == 200 ) {
				foreach ( $data['data'] as $media ) {
					$images[] = array(
						'caption' => $media['caption'],
						'link'    => $media['link'],
						'images'  => $media['images']
					);
				}

				if ( ! empty( $images ) ) {
					set_transient( $transient_key, $images, 2 * 3600 );
				}
			} else {
				return new WP_Error( 'instagram_error', $data['meta']['error_message'] );
			}
		}

		if ( ! empty( $images ) ) {
			return $images;
		} else {
			return new WP_Error( 'instagram_no_images', esc_html__( 'Instagram did not return any images.', 'supro' ) );
		}
	}

	/**
	 * Get user data
	 *
	 * @return bool|WP_Error|array
	 */
	protected function supro_get_instagram_user( $instagram_access_token ) {
		if ( empty( $instagram_access_token ) ) {
			return new WP_Error( 'no_access_token', esc_html__( 'No access token', 'supro' ) );
		}

		$user = get_transient( 'supro_instagram_user' );

		if ( false === $user ) {
			$url  = add_query_arg( array( 'access_token' => $instagram_access_token ), 'https://api.instagram.com/v1/users/self/' );
			$data = wp_remote_get( $url );
			$data = wp_remote_retrieve_body( $data );

			if ( ! $data ) {
				return new WP_Error( 'no_user_data', esc_html__( 'No user data received', 'supro' ) );
			}

			$data = json_decode( $data, true );
			$user = isset( $data['data'] ) ? $data['data'] : false;

			set_transient( 'supro_instagram_user', $user );
		}

		return $user;
	}

	/**
	 * Filter images only
	 *
	 * @param array $item
	 *
	 * @return bool
	 */
	protected function image_only_filter( $item ) {
		return $item['type'] == 'image';
	}

	/**
	 * @param $atts
	 * @param $type
	 *
	 * @return string
	 */
	function get_wc_products( $atts, $type = 'products' ) {
		if ( ! class_exists( 'WC_Shortcode_Products' ) ) {
			return '';
		}

		$attr = array(
			'limit'    => intval( $atts['limit'] ),
			'columns'  => intval( $atts['columns'] ),
			'page'     => $atts['page'],
			'category' => $atts['category'],
			'paginate' => true,
			'orderby'  => $atts['orderby'],
			'order'    => $atts['order'],
		);

		$current_page = absint( empty( $_GET['product-page'] ) ? 1 : $_GET['product-page'] );

		if ( isset( $attr['page'] ) ) {
			$_GET['product-page'] = $attr['page'];
		}

		$shortcode = new WC_Shortcode_Products( $attr, $type );

		$args = $shortcode->get_query_args();
		$html = $shortcode->get_content();

		$products   = new WP_Query( $args );
		$total_page = $products->max_num_pages;

		if ( isset( $atts['load_more'] ) && $atts['load_more'] && $total_page > 1 ) {
			if ( $attr['page'] < $total_page ) {
				$html .= sprintf(
					'<div class="load-more text-center">
						<a href="#" class="ajax-load-products" data-page="%s" data-type="%s" data-attr="%s" data-nonce="%s" rel="nofollow">
							<span class="button-text">%s</span>
							<span class="loading-icon">
								<span class="loading-text">%s</span>
								<span class="icon_loading supro-spin su-icon"></span>
							</span>
						</a>
					</div>',
					esc_attr( $attr['page'] + 1 ),
					esc_attr( $type ),
					esc_attr( json_encode( $attr ) ),
					esc_attr( wp_create_nonce( 'supro_get_products' ) ),
					esc_html__( 'Discover More', 'supro' ),
					esc_html__( 'Loading', 'supro' )
				);
			}
		}

		if ( isset( $attr['page'] ) ) {
			$_GET['product-page'] = $current_page;
		}

		return $html;
	}

	/**
	 * Get limited words from given string.
	 * Strips all tags and shortcodes from string.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $num_words The maximum number of words
	 * @param string $more More link.
	 *
	 * @return string|void Limited content.
	 */
	protected function supro_addons_content_limit( $content, $num_words, $more = "&hellip;" ) {
		// Strip tags and shortcodes so the content truncation count is done correctly
		$content = strip_tags( strip_shortcodes( $content ), apply_filters( 'supro_content_limit_allowed_tags', '<script>,<style>' ) );

		// Remove inline styles / scripts
		$content = trim( preg_replace( '#<(s(cript|tyle)).*?</\1>#si', '', $content ) );

		// Truncate $content to $max_char
		$content = wp_trim_words( $content, $num_words );

		if ( $more ) {
			$output = sprintf(
				'<div class="excerpt">%s <a href="%s" class="more-link" title="%s">%s</a></div>',
				$content,
				get_permalink(),
				sprintf( esc_html__( 'Continue reading &quot;%s&quot;', 'supro' ), the_title_attribute( 'echo=0' ) ),
				esc_html( $more )
			);
		} else {
			$output = sprintf( '<div class="excerpt">%s</div>', $content );
		}

		return $output;
	}

	protected function supro_addons_btn( $atts ) {
		$class_name = 'supro-button__' . $this->get_id_number( __FUNCTION__ );

		$css_class = array(
			'supro-button',
			'text-' . $atts['align'],
			'color-' . $atts['color'],
			$atts['style'],
			$class_name,
			$this->get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		$css = array();

		$line_height = $font_size = $t_line_height = $m_line_height = $t_font_size = $m_font_size = '';
		$t_align     = $atts['t_align'];
		$m_align     = $atts['m_align'];;

		if ( $atts['line_height'] || $atts['t_line_height'] || $atts['m_line_height'] ) {
			$line_height   = preg_replace( '/\s+/', '', $atts['line_height'] );
			$t_line_height = preg_replace( '/\s+/', '', $atts['t_line_height'] );
			$m_line_height = preg_replace( '/\s+/', '', $atts['m_line_height'] );
		}

		if ( $atts['font_size'] ) {
			$font_size = $this->supro_font_size_handle( $atts['font_size'] );
			$css[]     = ".$class_name a.supro-link { font-size: " . $font_size . "}";
		}

		if ( $line_height ) {
			$css[] = ".$class_name a.supro-link { line-height: " . $line_height . "}";
		}

		$button = $this->get_vc_link( $atts, '' );

		if ( $atts['t_font_size'] ) {
			$t_font_size = $this->supro_font_size_handle( $atts['t_font_size'] );
		}

		if ( $atts['m_font_size'] ) {
			$t_font_size = $this->supro_font_size_handle( $atts['m_font_size'] );
		}

		if ( $atts['t_align'] == 'inherit' ) {
			$t_align = $atts['align'];
		}

		if ( $atts['m_align'] == 'inherit' ) {
			$m_align = $atts['align'];
		}

		$responsive = array(
			'1024' => array(
				'font-size'   => $t_font_size,
				'line-height' => $t_line_height,
				'text-align'  => $t_align,
			),
			'767'  => array(
				'font-size'   => $m_font_size,
				'line-height' => $m_line_height,
				'text-align'  => $m_align,
			)
		);


		foreach ( $responsive as $size => $attr ) {
			foreach ( $attr as $key => $value ) {
				if ( $value ) {
					$css[] = "@media( max-width: " . $size . "px ) {.$class_name { " . $key . " : " . $value . " !important; }}";
				}
			}
		}

		return sprintf(
			'<style type="text/css">%s</style><div class="%s">%s</div>',
			implode( "\n", $css ),
			esc_attr( implode( ' ', $css_class ) ),
			$button
		);
	}

	/**
	 * Get vc link
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	protected function get_vc_link( $atts, $content ) {
		$attributes = array(
			'class' => 'supro-link',
		);

		$link = vc_build_link( $atts['link'] );

		if ( ! empty( $link['url'] ) ) {
			$attributes['href'] = $link['url'];
		}

		if ( ! $content ) {
			$content             = $link['title'];
			$attributes['title'] = $content;
		}

		if ( ! empty( $link['target'] ) ) {
			$attributes['target'] = $link['target'];
		}

		if ( ! empty( $link['rel'] ) ) {
			$attributes['rel'] = $link['rel'];
		}

		$attr = array();

		foreach ( $attributes as $name => $v ) {
			$attr[] = $name . '="' . esc_attr( $v ) . '"';
		}

		$button = sprintf(
			'<%1$s %2$s>%3$s</%1$s>',
			empty( $attributes['href'] ) ? 'span' : 'a',
			implode( ' ', $attr ),
			$content
		);

		return $button;
	}

	/**
	 * Get ID number of a shortcode
	 *
	 * @param string $shortcode
	 *
	 * @return int
	 */
	protected function get_id_number( $shortcode ) {
		if ( isset( $this->ids[ $shortcode ] ) ) {
			$this->ids[ $shortcode ] ++;
		} else {
			$this->ids[ $shortcode ] = 1;
		}

		return $this->ids[ $shortcode ];
	}

	/**
	 * @param        $image
	 * @param string $size
	 *
	 * @return string
	 */

	protected function supro_vc_get_image( $image, $size = 'thumbnail' ) {
		$image_src = '';
		if ( function_exists( 'wpb_getImageBySize' ) ) {
			$image = wpb_getImageBySize(
				array(
					'attach_id'  => $image,
					'thumb_size' => $size,
				)
			);

			if ( $image['thumbnail'] ) {
				$image_src = $image['thumbnail'];
			} elseif ( $image['p_img_large'] ) {
				$image_src = sprintf( '<img src="%s">', esc_url( $image['p_img_large'][0] ) );
			}

		}

		if ( empty( $image_src ) ) {
			$image_src = wp_get_attachment_image( $image, $size );
		}

		return $image_src;
	}

	/**
	 * @param string $atts
	 *
	 * @return string
	 */
	protected function supro_font_size_handle( $atts ) {
		$atts = preg_replace( '/\s+/', '', $atts );

		$pattern = '/^(\d*(?:\.\d+)?)\s*(px|\%|in|cm|mm|em|rem|ex|pt|pc|vw|vh|vmin|vmax)?$/';
		// allowed metrics: http://www.w3schools.com/cssref/css_units.asp
		$regexr   = preg_match( $pattern, $atts, $matches );
		$value    = isset( $matches[1] ) ? (float) $matches[1] : (float) $atts;
		$unit     = isset( $matches[2] ) ? $matches[2] : 'px';
		$fontSize = $value . $unit;

		return $fontSize;
	}

	/**
	 * Get CSS classes for animation
	 *
	 * @param string $css_animation
	 *
	 * @return string
	 */
	function get_css_animation( $css_animation ) {
		$output = '';

		if ( '' !== $css_animation && 'none' !== $css_animation ) {
			wp_enqueue_script( 'waypoints' );
			wp_enqueue_style( 'animate-css' );
			$output = ' wpb_animate_when_almost_visible wpb_' . $css_animation . ' ' . $css_animation;
		}

		return $output;
	}
}
