<?php
/**
 * Custom functions for nav menu
 *
 * @package Supro
 */


/**
 * Display numeric pagination
 *
 * @since 1.0
 * @return void
 */
function supro_numeric_pagination() {
	global $wp_query;

	if ( $wp_query->max_num_pages < 2 ) {
		return;
	}

	$type_nav  = supro_get_option( 'portfolio_nav_type' );
	$p_style   = supro_get_option( 'portfolio_layout' );
	$view_more = apply_filters( 'supro_portfolio_nav_text', esc_html__( 'Discover More', 'supro' ) );

	$next_html = sprintf(
		'<span id="supro-portfolio-ajax" class="nav-previous-ajax">
			<span class="nav-text">%s</span>
			<span class="loading-icon">
				<span class="loading-text">%s</span>
				<span class="icon_loading supro-spin su-icon"></span>
			</span>
		</span>',
		$view_more,
		esc_html__( 'Loading', 'supro' )
	);

	?>
	<nav class="navigation paging-navigation numeric-navigation">
		<?php
		add_filter( 'number_format_i18n', 'supro_paginate_links_prefix' );

		$big  = 999999999;
		$args = array(
			'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'total'     => $wp_query->max_num_pages,
			'current'   => max( 1, get_query_var( 'paged' ) ),
			'prev_text' => '<i class="icon-chevron-left"></i>',
			'next_text' => '<i class="icon-chevron-right"></i>',
			'type'      => 'plain'
		);

		if ( supro_is_portfolio() && $p_style != 'carousel' && $type_nav == 'ajax' ) {
			$args['prev_text'] = '';
			$args['next_text'] = $next_html;
		}

		echo paginate_links( $args );
		?>
	</nav>
	<?php
}

/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @since 1.0
 * @return void
 */
function supro_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	$css       = '';
	$type_nav  = supro_get_option( 'type_nav' );
	$style     = supro_get_option( 'view_more_style' );
	$view_more = wp_kses( supro_get_option( 'view_more_text' ), wp_kses_allowed_html( 'post' ) );

	if ( $type_nav == 'view_more' ) {
		$css .= ' blog-view-more style-' . $style;
	}

	?>
	<nav class="navigation paging-navigation <?php echo esc_attr( $css ); ?>">
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
				<?php if ( $type_nav == 'view_more' ) : ?>
					<div id="supro-blog-previous-ajax" class="nav-previous-ajax">
						<?php next_posts_link( sprintf( '%s', $view_more ) ); ?>
						<span class="loading-icon">
							<span class="bubble">
								<span class="dot"></span>
							</span>
							<span class="bubble">
								<span class="dot"></span>
							</span>
							<span class="bubble">
								<span class="dot"></span>
							</span>
						</span>
					</div>
				<?php else : ?>
					<div class="nav-previous"><?php next_posts_link( sprintf( '<span class="meta-nav"><i class="icon-arrow-left"></i> </span> %s', esc_html__( 'Older posts', 'supro' ) ) ); ?></div>
				<?php endif; ?>

			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
				<?php if ( $type_nav != 'view_more' ) : ?>
					<div class="nav-next"><?php previous_posts_link( sprintf( '%s <span class="meta-nav"><i class="icon-arrow-right"></i></span>', esc_html__( 'Newer posts', 'supro' ) ) ); ?></div>
				<?php endif; ?>
			<?php endif; ?>

		</div>
		<!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}

/* Filter function to be used with number_format_i18n filter hook */
if ( ! function_exists( 'supro_paginate_links_prefix' ) ) :
	function supro_paginate_links_prefix( $format ) {
		$number = intval( $format );
		if ( intval( $number / 10 ) > 0 ) {
			return $format;
		}

		return '0' . $format;
	}
endif;

function supro_single_portfolio_nav() {
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}

	$next_icon = '<span>' . esc_html__( 'Next', 'supro' ) . '</span><i class="icon-arrow-right"></i>';
	$prev_icon = '<i class="icon-arrow-left"></i><span>' . esc_html__( 'Prev', 'supro' ) . '</span>';

	?>
	<nav class="navigation portfolio-navigation">
		<div class="container">
			<div class="nav-links">
				<div class="nav-previous">
					<?php previous_post_link( '%link', $prev_icon ); ?>
				</div>

				<a class="portfolio-link" href="<?php echo esc_url( get_post_type_archive_link( 'portfolio' ) ); ?>"><i class="icon_grid-3x3"></i></a>

				<div class="nav-next">
					<?php next_post_link( '%link', $next_icon ); ?>
				</div>
			</div>
			<!-- .nav-links -->
		</div>
	</nav><!-- .navigation -->
	<?php
}
