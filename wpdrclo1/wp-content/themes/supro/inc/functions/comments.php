<?php
/**
 * Custom functions for displaying comments
 *
 * @package Supro
 */

/**
 * Comment callback function
 *
 * @param object $comment
 * @param array  $args
 * @param int    $depth
 */
function supro_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	extract( $args, EXTR_SKIP );

	if ( 'div' == $args['style'] ) {
		$add_below = 'comment';
	} else {
		$add_below = 'div-comment';
	}
	?>

	<li <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
		<article id="div-comment-<?php comment_ID() ?>" class="comment-body clearfix">
	<?php endif; ?>

	<div class="comment-author vcard">
		<?php echo get_avatar( $comment, 70 );?>
	</div>
	<div class="comment-meta commentmetadata clearfix">
		<div class="comment-header">
		<?php printf( '<cite class="author-name">%s</cite>', get_comment_author_link() ); ?>

		<?php
		$time_string = '<time class="entry-date published updated">%1$s</time>';

		$time_string = sprintf(
			$time_string,
			esc_html( get_comment_date() )
		);

		echo '<div class="comment-date">' . $time_string . '</div>';
		?>

		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'supro' ); ?></em>
		<?php endif; ?>
		</div>
		<div class="comment-content">
			<?php comment_text(); ?>
		</div>

		<?php
		comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => esc_html__( 'Reply', 'supro' ) ) ) );
		edit_comment_link( esc_html__( 'Edit', 'supro' ), '  ', '' );
		?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
		</article>
	<?php endif; ?>
    </li>
	<?php
}

/*
 *  Custom comment form
 */
function supro_comment_form( $fields ) {
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );

	$fields['author'] = '<p class="comment-form-author">
						<input id="author" placeholder="' . esc_attr__( 'Name', 'supro' ) . '" required name="author" type="text" value="' .
		esc_attr( $commenter['comment_author'] ) . '" size="30" />'.
		'</p>';
	$fields['email'] = '<p class="comment-form-email">
						<input id="email" placeholder="' . esc_attr__( 'Email', 'supro' ) . '" required name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) .
		'" size="30" />'  .
		'</p>';
	$fields['url'] = '<p class="comment-form-url">
					 <input id="url" name="url" placeholder="' . esc_attr__( 'Website', 'supro' ) . '" type="text" size="30" /> ' .
		'</p>';
	$fields['clear'] = '<div class="clearfix"></div>';

	return $fields;
}

add_filter('comment_form_default_fields','supro_comment_form');
