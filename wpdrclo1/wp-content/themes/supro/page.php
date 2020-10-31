<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Supro
 */

get_header(); ?>

	<div id="primary" class="content-area <?php supro_content_columns(); ?>">
		<main id="main" class="site-main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php
				if ( supro_is_maintenance_page() ) {
					the_content();
				} else {
					get_template_part( 'parts/content', 'page' );
				}
				?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
