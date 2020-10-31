<?php
/**
 * @package Supro
 */

global $wp_query;

$current = $wp_query->current_post + 1;

$size           = 'supro-blog-grid';
$blog_style     = supro_get_option( 'blog_style' );
$blog_layout    = supro_get_option( 'blog_layout' );
$excerpt_length = intval( supro_get_option( 'excerpt_length' ) );

$css_class = 'blog-wrapper';

if ( 'grid' == $blog_style ) {
	if ( 'full-content' == $blog_layout ) {
		$css_class .= ' col-md-4 col-sm-6 col-xs-6';
	} else {
		$css_class .= ' col-md-6 col-sm-6 col-xs-6';
	}

} elseif ( 'list' == $blog_style ) {
	$size = 'supro-blog-list';

} elseif ( 'masonry' == $blog_style ) {

	if ( $current % 9 == 1 || $current % 9 == 6 || $current % 9 == 8 ) {
		$size = 'supro-blog-masonry-1';
	} elseif ( $current % 9 == 3 || $current % 9 == 4 || $current % 9 == 7 ) {
		$size = 'supro-blog-masonry-3';
	} else {
		$size = 'supro-blog-masonry-2';
	}
}

?>
<?php if ( $blog_style == 'masonry' && $current == 1 )  : ?>
	<div class="blog-masonry-sizer"></div>
	<div class="blog-gutter-sizer"></div>
<?php endif; ?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $css_class ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="entry-thumbnail">
			<a class="blog-thumb" href="<?php the_permalink() ?>"><?php the_post_thumbnail( $size ); ?></a>
		</div>
	<?php endif; ?>

	<div class="entry-summary">
		<div class="entry-header">
			<h2 class="entry-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
			<?php supro_entry_meta(); ?>
		</div>
		<?php if ( 'list' == $blog_style ) : ?>
			<div class="entry-excerpt"><?php echo supro_content_limit( $excerpt_length, '' ); ?></div>
			<a href="<?php the_permalink() ?>" class="read-more">
				<?php echo apply_filters( 'supro_blog_read_more_text', esc_html__( 'READ MORE', 'supro' ) ); ?>
			</a>
		<?php endif ?>
	</div>
</article>
