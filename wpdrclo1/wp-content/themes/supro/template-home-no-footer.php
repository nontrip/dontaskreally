<?php
/**
 * Template Name: Home No Footer
 *
 * The template file for displaying Home page - Home No Footer.
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

<?php get_footer(); ?>
