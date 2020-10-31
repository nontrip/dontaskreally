<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Supro
 */

get_header();

$search_inherit = supro_get_option( 'search_layout_inherit' );
$blog_style     = supro_get_option( 'blog_style' );

?>

<section id="primary" class="content-area <?php supro_content_columns(); ?>">
	<main id="main" class="site-main">

		<?php do_action( 'supro_before_search_content' ); ?>

		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<div class="supro-search-content">
				<?php if ( 'grid' == $blog_style && intval( $search_inherit ) ) : ?>
				<div class="row">
					<?php endif ?>
					<div class="supro-post-list">
						<?php while ( have_posts() ) : the_post(); ?>

							<?php
							/**
							 * Run the loop for the search to output the results.
							 * If you want to overload this in a child theme then include a file
							 * called content-search.php and that will be used instead.
							 */
							get_template_part( 'parts/content', 'search' );
							?>

						<?php endwhile; ?>
					</div>
					<?php if ( 'grid' == $blog_style ) : ?>
				</div>
			<?php endif ?>

				<?php supro_numeric_pagination(); ?>
			</div>
		<?php else : ?>

			<?php get_template_part( 'parts/content', 'none' ); ?>

		<?php endif; ?>

	</main>
	<!-- #main -->
</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
