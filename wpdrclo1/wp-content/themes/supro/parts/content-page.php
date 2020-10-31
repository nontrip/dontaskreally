<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Supro
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<h2 class="entry-title hidden"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'supro' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
