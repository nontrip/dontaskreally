<?php
/**
 * Hooks for template logo
 *
 * @package Solo
 */

$logo  = supro_get_option( 'logo' );
$logo_light  = supro_get_option( 'logo_light' );

if ( ! $logo ) {
	$logo = get_template_directory_uri() . '/img/logo.svg';
}

if ( ! $logo_light ) {
	$logo_light = get_template_directory_uri() . '/img/logo-light.svg';
}

?>
	<a href="<?php echo esc_url( home_url() ) ?>" class="logo">
		<img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo get_bloginfo( 'name' ); ?>" class="logo logo-dark">
		<img src="<?php echo esc_url( $logo_light ); ?>" alt="<?php echo get_bloginfo( 'name' ); ?>" class="logo logo-light">
	</a>
<?php

printf(
	'<%1$s class="site-title"><a href="%2$s" rel="home">%3$s</a></%1$s>',
	is_home() || is_front_page() ? 'h1' : 'p',
	esc_url( home_url( '' ) ),
	get_bloginfo( 'name' )
);
?>
<?php if ( ( $description = get_bloginfo( 'description', 'display' ) ) || is_customize_preview() ) : ?>
	<p class="site-description"><?php echo wp_kses_post( $description ); /* WPCS: xss ok. */ ?></p>
<?php endif; ?>
