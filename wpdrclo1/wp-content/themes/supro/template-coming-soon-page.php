<?php
/**
 * Template Name: Coming Soon Page
 *
 * The template file for displaying Coming Soon page.
 *
 * @package Supro
 */

get_header(); ?>

<?php
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		the_content();
	endwhile;

endif;
?>
<div class="coming-soon-footer"><?php do_action( 'supro_coming_soon_page_content' ); ?></div>

<?php get_footer(); ?>
