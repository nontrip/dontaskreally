<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Supro
 */

get_header();

?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">

		<section class="error-404 not-found">
			<div class="page-content col-md-12 col-xs-12 col-sm-12">
				<span class="icon-confused error-icon"></span>
				<h1 class="page-title"><?php esc_html_e( 'ohh! page not found', 'supro' ); ?></h1>

				<p>
					<?php esc_html_e( "It seems we can't find what you're looking for. Perhaps searching can help or go back to ", 'supro' ); ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Homepage', 'supro' ); ?></a>
				</p>

				<?php get_search_form(); ?>

			</div>
			<!-- .page-content -->
		</section>
		<!-- .error-404 -->

	</main>
	<!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
