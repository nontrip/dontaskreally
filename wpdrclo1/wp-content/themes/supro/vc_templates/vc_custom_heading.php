<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $source
 * @var $text
 * @var $link
 * @var $google_fonts
 * @var $font_container
 * @var $el_class
 * @var $el_id
 * @var $css
 * @var $css_animation
 * @var $font_container_data - returned from $this->getAttributes
 * @var $google_fonts_data   - returned from $this->getAttributes
 * Shortcode class
 * @var $this                WPBakeryShortCode_VC_Custom_heading
 */
$source         = $text = $link = $google_fonts = $font_container = $el_id = $el_class = $css = $css_animation = $font_container_data = $google_fonts_data = array();
$letter_spacing = $font_weight = $t_font_size = $t_line_height = $m_font_size = $m_line_height = '';
// This is needed to extract $font_container_data and $google_fonts_data
extract( $this->getAttributes( $atts ) );

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

if ( isset( $this->ids[__CLASS__] ) ) {
	$this->ids[__CLASS__] ++;
} else {
	$this->ids[__CLASS__] = 1;
}

$custom_css = $this->ids[__CLASS__];

$custom_css = 'supro-vc_custom_heading__' . $custom_css;

/**
 * @var $css_class
 */
extract( $this->getStyles( $el_class . $this->getCSSAnimation( $css_animation ) . ' ' . $custom_css, $css, $google_fonts_data, $font_container_data, $atts ) );

$settings = get_option( 'wpb_js_google_fonts_subsets' );
if ( is_array( $settings ) && ! empty( $settings ) ) {
	$subsets = '&subset=' . implode( ',', $settings );
} else {
	$subsets = '';
}

if ( ( ! isset( $atts['use_theme_fonts'] ) || 'yes' !== $atts['use_theme_fonts'] ) && isset( $google_fonts_data['values']['font_family'] ) ) {
	wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_fonts_data['values']['font_family'] ), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets );
}

if ( ! empty( $styles ) ) {

	if ( $letter_spacing ) {
		$styles[] = 'letter-spacing:' . $letter_spacing . ';';
	}

	if ( $font_weight && ( isset( $atts['use_theme_fonts'] ) || 'yes' == $atts['use_theme_fonts'] ) ) {
		$styles[] = 'font-weight:' . $font_weight . ';';
	}

	$style = 'style="' . esc_attr( implode( ';', $styles ) ) . '"';
} else {
	$style = '';
}

if ( 'post_title' === $source ) {
	$text = get_the_title( get_the_ID() );
}

if ( ! empty( $link ) ) {
	$link = vc_build_link( $link );
	$text = '<a href="' . esc_attr( $link['url'] ) . '"' . ( $link['target'] ? ' target="' . esc_attr( $link['target'] ) . '"' : '' ) . ( $link['rel'] ? ' rel="' . esc_attr( $link['rel'] ) . '"' : '' ) . ( $link['title'] ? ' title="' . esc_attr( $link['title'] ) . '"' : '' ) . '>' . $text . '</a>';
}
$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

// Responsive Handle

if ( $t_font_size || $t_line_height || $m_font_size || $m_line_height ) {
	$t_font_size   = preg_replace( '/\s+/', '', $t_font_size );
	$t_line_height = preg_replace( '/\s+/', '', $t_line_height );
	$m_font_size   = preg_replace( '/\s+/', '', $m_font_size );
	$m_line_height = preg_replace( '/\s+/', '', $m_line_height );
}

if ( $t_font_size ) {
	$pattern = '/^(\d*(?:\.\d+)?)\s*(px|\%|in|cm|mm|em|rem|ex|pt|pc|vw|vh|vmin|vmax)?$/';
	// allowed metrics: http://www.w3schools.com/cssref/css_units.asp
	$regexr = preg_match( $pattern, $t_font_size, $matches );
	$t_font_size = isset( $matches[1] ) ? (float) $matches[1] : (float) $t_font_size;
	$unit = isset( $matches[2] ) ? $matches[2] : 'px';
	$t_font_size = $t_font_size . $unit;
}

if ( $m_font_size ) {
	$pattern = '/^(\d*(?:\.\d+)?)\s*(px|\%|in|cm|mm|em|rem|ex|pt|pc|vw|vh|vmin|vmax)?$/';
	// allowed metrics: http://www.w3schools.com/cssref/css_units.asp
	$regexr = preg_match( $pattern, $m_font_size, $matches );
	$m_font_size = isset( $matches[1] ) ? (float) $matches[1] : (float) $m_font_size;
	$unit = isset( $matches[2] ) ? $matches[2] : 'px';
	$m_font_size = $m_font_size . $unit;
}

$responsive = array(
	'1024' => array(
		'font-size'   => $t_font_size,
		'line-height' => $t_line_height
	),
	'767'  => array(
		'font-size'   => $m_font_size,
		'line-height' => $m_line_height
	)
);

$responsive_css = array();

foreach ( $responsive as $size => $attr ) {
	foreach ( $attr as $key => $value ) {
		if ( $value ) {
			$responsive_css[] = "@media( max-width: " . $size . "px ) {.$custom_css { " . $key . " : " . $value . " !important; }}";
		}
	}
}

$inline_css = $responsive_css ? '<style type="text/css">' . implode( "\n", $responsive_css ) . '</style>' : '';

$output = '';
if ( apply_filters( 'vc_custom_heading_template_use_wrapper', false ) ) {
	$output .= '<div class="' . esc_attr( $css_class ) . '" ' . implode( ' ', $wrapper_attributes ) . '>';
	$output .= $inline_css;
	$output .= '<' . $font_container_data['values']['tag'] . ' ' . $style . ' >';
	$output .= $text;
	$output .= '</' . $font_container_data['values']['tag'] . '>';
	$output .= '</div>';
} else {
	$output .= $inline_css;
	$output .= '<' . $font_container_data['values']['tag'] . ' ' . $style . ' class="' . esc_attr( $css_class ) . '" ' . implode( ' ', $wrapper_attributes ) . '>';
	$output .= $text;
	$output .= '</' . $font_container_data['values']['tag'] . '>';
}

echo ! empty( $output ) ? $output : '';
