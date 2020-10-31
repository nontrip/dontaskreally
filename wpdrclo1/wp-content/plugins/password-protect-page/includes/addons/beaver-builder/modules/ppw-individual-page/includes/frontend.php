<?php


$shortcode = '[ppwp passwords="' . $settings->ppwp_passwords . '"';

if ( ! empty( $settings->ppwp_headline ) ) {
	$shortcode .= ' headline="' . esc_html__($settings->ppwp_headline) . '"';
}

if ( ! empty( $settings->ppwp_description ) ) {
	$shortcode .= ' description="' . esc_html__( $settings->ppwp_description ) . '"';
}

if ( ! empty( $settings->ppwp_placeholder ) ) {
	$shortcode .= ' placeholder="' . esc_html__( $settings->ppwp_placeholder ) . '"';
}

if ( ! empty( $settings->ppwp_button ) ) {
	$shortcode .= ' button="' . esc_html__( $settings->ppwp_button ) . '"';
}

if ( ! empty( $settings->ppwp_cookie ) ) {
	$shortcode .= ' cookie="' . absint( $settings->ppwp_cookie ) . '"';
}

if ( ! empty( $settings->ppwp_download_limit ) ) {
	$shortcode .= ' download_limit="' . absint( $settings->ppwp_download_limit ) . '"';
}

if ( is_array( $settings->ppwp_whitelisted_roles ) && count( $settings->ppwp_whitelisted_roles ) > 0 ) {
	$whitelisted_roles = implode( ',', $settings->ppwp_whitelisted_roles );
	$shortcode         .= ' whitelisted_roles="' . $whitelisted_roles . '"';
}

$shortcode = apply_filters( PPW_Constants::HOOK_SHORTCODE_BEAVER_BUILDER_ATTRIBUTES, $shortcode, $settings );

$shortcode .= ']';

echo '<div class="description">' . ( $shortcode . $settings->ppwp_protected_content . '[/ppwp]' ) . '</div>';


