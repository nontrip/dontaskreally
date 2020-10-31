<?php
/**
 * The template part for displaying related posts
 *
 * @package Sober
 */

// Only support posts
if ( 'post' != get_post_type() ) {
	return;
}

if ( ! intval( supro_get_option( 'related_posts' ) ) ) {
	return;
}

if ( get_post_meta( get_the_ID(), 'custom_page_layout', true ) ) {
	$layout = get_post_meta( get_the_ID(), 'layout', true );
} else {
	$layout = supro_get_option( 'single_post_layout' );
}

if ( $layout == 'full-content' ) {
	$css_class = 'col-md-4 col-xs-6 col-sm-6';
} else {
	$css_class = 'col-md-6 col-xs-6 col-sm-6';
}

$numbers = intval( supro_get_option( 'related_posts_numbers' ) );

$related_posts = new WP_Query(
	array(
		'posts_per_page'         => $numbers,
		'ignore_sticky_posts'    => 1,
		'category__in'           => wp_get_post_categories( get_the_ID() ),
		'post__not_in'           => array( get_the_ID() ),
		'no_found_rows'          => true,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
	)
);

$size = 'supro-blog-grid-2';

if ( ! $related_posts->have_posts() ) {
	return;
}

?>
	<div class="supro-related-posts blog-grid">
		<div class="related-section-title">
			<?php
			if ( $titles = supro_get_option( 'related_posts_title' ) ) {

				echo sprintf( '<h2 class="related-title">%s</h2>', $titles );
			}
			?>
		</div>
		<div class="list-post row">
			<?php while ( $related_posts->have_posts() ) : $related_posts->the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( $css_class ); ?>>
					<div class="blog-wrapper">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="entry-thumbnail">
								<a class="blog-thumb" href="<?php the_permalink() ?>"><?php the_post_thumbnail( $size ) ?></a>
							</div>
						<?php endif; ?>

						<div class="entry-summary">
							<div class="entry-header">
								<h2 class="entry-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
								<?php supro_entry_meta() ?>
							</div>
						</div><!-- .entry-content -->
					</div>
				</article><!-- #post-## -->

			<?php endwhile; ?>
		</div>
	</div>

<?php
wp_reset_postdata();