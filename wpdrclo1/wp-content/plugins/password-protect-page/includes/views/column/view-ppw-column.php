<?php
$service      = new PPW_Password_Services();
$is_protected = $service->is_protected_content( $post_id );
$icon_class   = $is_protected ? 'dashicons-lock' : 'dashicons-unlock';
$color_class  = $is_protected ? 'ppw_protected_color' : 'ppw_unprotected_color';
$status       = $is_protected ? 'protected' : 'unprotected';
$post         = get_post( $post_id );
?>

<div class="ppw-column">
	<span id="ppw-badge-protection_<?php echo esc_attr( $post_id ); ?>"
	      class="ppw-badge-protection <?php echo esc_attr( $color_class ); ?>">
		<i class="dashicons <?php echo esc_attr( $icon_class ); ?>"></i> <?php echo esc_html( $status, 'password-protect-page' ); ?>
	</span>
</div>
