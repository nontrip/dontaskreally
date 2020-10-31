<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Supro
 */

get_header();

$blog_style     = supro_get_option( 'blog_style' );

?>

<div id="primary" class="content-area <?php supro_content_columns(); ?>">
	<main id="main" class="site-main">

		<?php
		/* supro_blog_text_categories
		 *
		 */
		do_action( 'supro_before_archive_post_list' );
		?>

		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>

			<div class="supro-blog-content">
				<?php if ( 'grid' == $blog_style ) : ?>
					<div class="row">
				<?php endif ?>
					<div class="supro-post-list">
						<?php while ( have_posts() ) : the_post(); ?>

							<?php
							/* Include the Post-Format-specific template for the content.
							 * If you want to override this in a child theme, then include a file
							 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
							 */
							get_template_part( 'parts/content', get_post_format() );
							?>

						<?php endwhile; ?>
					</div>
				<?php if ( 'grid' == $blog_style ) : ?>
					</div>
				<?php endif ?>
				<!--.row-->

				<?php supro_numeric_pagination(); ?>

			</div><!--.ba-blog-content-->

		<?php else : ?>

			<?php get_template_part( 'parts/content', 'none' ); ?>

		<?php endif; ?>


	</main>
	<!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>


