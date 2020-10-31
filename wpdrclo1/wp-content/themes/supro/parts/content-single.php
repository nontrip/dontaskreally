<?php
/**
 * @package Supro
 */

$category = '';
$fields   = (array) supro_get_option( 'post_entry_meta' );
if ( in_array( 'scat', $fields ) ) {
	$category = get_the_terms( get_the_ID(), 'category' );
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-wrapper single-post-wrapper' ); ?>>
	<header class="entry-header">
		<?php
		if ( ! is_wp_error( $category ) && $category ) {
			echo sprintf( '<a href="%s" class="entry-cat">%s</a>', esc_url( get_term_link( $category[0], 'category' ) ), esc_html( $category[0]->name ) );
		} ?>
		<h1 class="entry-title"><?php the_title() ?></h1>
		<?php supro_single_entry_meta(); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'supro' ),
			'after'  => '</div>',
		) );
		?>
	</div><!-- .entry-content -->

	<?php supro_entry_footer(); ?>

</article><!-- #post-## -->
