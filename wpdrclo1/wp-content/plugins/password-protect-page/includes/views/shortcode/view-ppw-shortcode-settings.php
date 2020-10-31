<?php
$html_link         = sprintf(
	'<a target="_blank" rel="noopener" href="%s">lock parts of your content</a>',
	'https://passwordprotectwp.com/docs/password-protect-wordpress-content-sections/'
);
$desc              = sprintf(
// translators: %s: Link to documentation.
	esc_html__( 'Use the following shortcode to %s. Set as many passwords as you’d like to.', 'password-protect-page' ),
	$html_link
);
$link_shortcode    = sprintf(
	'<a target="_blank" rel="noopener" href="%s">Use our built-in block</a>',
	'https://passwordprotectwp.com/docs/protect-partial-content-page-builders/?utm_source=user-website&amp;utm_medium=plugin-settings&amp;utm_content=shortcodes'
);
$message_shortcode = sprintf(
// translators: %s: Link to documentation.
	__( '%s instead if you\'re using popular page builders, e.g. Elementor and Beaver Builder.', 'password-protect-page' ),
	$link_shortcode
);

$page = isset( $_GET['page'] ) ? $_GET['page'] : null;
$tab  = isset( $_GET['tab'] ) ? $_GET['tab'] : null;



$message_shortcode_desc = '';
// Only show this message when user has never installed and activated Pro version. Because we are having this kind of message in PCP Pro tab.
if ( ! is_pro_active_and_valid_license() ) {
	$link_pcp = sprintf(
		'<a target="_blank" rel="noopener" href="%s">%s</a>',
		'https://passwordprotectwp.com/docs/manage-shortcode-global-passwords/',
		__( 'PCP global passwords', 'password-protect-page' )
	);

	$link_stats_addon = sprintf(
		'<a target="_blank" rel="noopener" href="%s">%s</a>',
		'https://passwordprotectwp.com/extensions/password-statistics/',
		__( 'Statistics addon', 'password-protect-page' )
	);

	$message_shortcode_desc = sprintf(
		/* translators: %1$s: Statistics link*/
		__( 'To track Partial Content Protection (PCP) password usage, please get %1$s and use %2$s instead.', 'password-protect-page' ),
		$link_stats_addon,
		$link_pcp
	);
}


$message = 'Great! You’ve successfully copied the shortcode to clipboard.';
$use_shortcode_page_builder = ppw_core_get_setting_type_bool_by_option_name( PPW_Constants::USE_SHORTCODE_PAGE_BUILDER, PPW_Constants::SHORTCODE_OPTIONS ) ? 'checked' : '';

?>
<div class="ppw_main_container" id="ppw_shortcodes_form">
	<form id="wpp_shortcode_form" method="post">
		<table class="ppwp_settings_table" cellpadding="4">
			<?php do_action( PPW_Constants::HOOK_SHORTCODE_SETTINGS_EXTENDS ); ?>
		</table>
	</form>
</div>
