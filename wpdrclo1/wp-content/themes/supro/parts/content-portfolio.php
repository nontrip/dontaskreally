<?php
/**
 * @package Supro
 */

global $wp_query;

$current = $wp_query->current_post + 1;

$p_style   = supro_get_option( 'portfolio_layout' );
$size      = 'supro-portfolio-grid';
$css       = 'portfolio-wrapper';

$thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );

$gallery = get_post_meta( get_the_ID(), 'images', false );

if ( $p_style == 'grid' ) {
	$css .= ' col-md-4 col-sm-6 col-xs-12';

} elseif ( $p_style == 'masonry' ) {
	$css .= ' col-md-3 col-sm-4 col-xs-6';

	if ( $current % 9 == 2 ) {
		$size = 'supro-portfolio-masonry-s';
	} elseif ( $current % 9 == 4 || $current % 9 == 6 || $current % 9 == 7 ) {
		$size = 'supro-portfolio-masonry-t';
	} else {
		$size = 'supro-portfolio-grid';
	}

} elseif ( $p_style == 'carousel' ) {
	$size = 'supro-portfolio-carousel';
	$css .= ' swiper-slide color-' . supro_get_option( 'portfolio_text_color' );
}

?>
<div id="post-<?php the_ID(); ?>" <?php post_class( $css ); ?>>
	<div class="portfolio-inner">
		<a href="<?php the_permalink() ?>" class="port-link"><?php echo 'masonry' == $p_style  ? '<i class="icon-arrow-right"></i>' : '' ?></a>
		<div class="entry-thumbnail">
			<a href="<?php the_permalink() ?>">
				<?php the_post_thumbnail( $size ); ?>
			</a>
		</div>

		<div class="entry-summary">

			<h2 class="entry-title"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
			<?php
			$category = get_the_terms( get_the_ID(), 'portfolio_category' );

			if ( ! is_wp_error( $category ) && $category ) {
				echo sprintf(
					'<a href="%s" class="portfolio-cat">%s</a>',
					esc_url( get_term_link( $category[0], 'category' ) ),
					esc_html( $category[0]->name )
				);
			}
			?>

		</div>

	</div>
</div><!-- #project-## -->
