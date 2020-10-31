<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.buildwps.com/
 * @since      1.0.0
 *
 * @package    Prevent_ur_pages
 * @subpackage Prevent_ur_pages/include
 */

/**
 *
 * Defines the Constants
 *
 * @package    Prevent_ur_pages
 * @subpackage Prevent_ur_pages/include
 * @author     Bwps <support@bwps.us>
 */

if ( ! class_exists( 'PPW_Constants' ) ) {
	/**
	 * Constants helper class
	 *
	 * Class PPW_Free_Constants
	 */
	class PPW_Constants {

		//phpcs:ignore
		#region Hook
		const HOOK_IS_PRO_ACTIVATE = 'ppw_is_pro_activate';

		const HOOK_CUSTOM_STYLE_FORM_ENTIRE_SITE = 'ppw_free_custom_style_form_entire_site';

		const HOOK_MESSAGE_ENTERING_WRONG_PASSWORD = 'ppwp_text_for_entering_wrong_password';

		const HOOK_MESSAGE_PASSWORD_FORM = 'ppwp_customize_password_form_message';

		const HOOK_CUSTOM_PASSWORD_FORM = 'ppwp_customize_password_form';

		const HOOK_CALLBACK_URL = 'ppwp_callback_url';

		const CALL_BACK_URL_PARAM = 'callback_url';

		const HOOK_SHOULD_RENDER_PASSWORD_FORM = 'ppwp_should_render_password_form';

		const HOOK_DEFAULT_TAB = 'ppw_default_tab';

		const HOOK_SITEWIDE_TAB = 'ppw_sitewide_tab';

		const HOOK_PCP_TAB = 'ppw_pcp_tab';

		const HOOK_ADD_NEW_TAB = 'ppw_add_new_tab';

		const HOOK_ADD_NEW_SITEWIDE_SUBMENU = 'ppw_sitewide_submenu_add_new_tab';

		const HOOK_ADD_NEW_PCP_SUBMENU = 'ppw_pcp_submenu_add_new_tab';

		const HOOK_RENDER_CONTENT_FOR_TAB = 'ppw_render_content_';

		const HOOK_CUSTOM_TAB = 'ppw_custom_tab';

		const HOOK_CUSTOM_SITEWIDE_TAB = 'ppw_custom_sitewide_tab';

		const HOOK_CUSTOM_PCP_TAB = 'ppw_custom_pcp_tab';

		const HOOK_RENDER_CONTENT_FOR_SITEWIDE_TAB = 'ppw_render_sitewide_content_';

		const HOOK_RENDER_CONTENT_FOR_PCP_TAB = 'ppw_render_pcp_content_';

		const HOOK_COOKIE_EXPIRED = 'post_password_expires';

		const HOOK_CHECK_PASSWORD_BEFORE_RENDER_CONTENT = 'ppw_check_password_before_render_content';

		const HOOK_FUNCTION_HANDLE_META_BOX = 'ppw_function_handle_meta_box';

		const HOOK_META_BOX_POSITION = 'ppw_meta_box_position';

		const HOOK_CHECK_PASSWORD_IS_VALID = 'ppw_check_password_is_valid';

		const HOOK_BEFORE_RENDER_FORM_ENTIRE_SITE = 'ppw_before_render_form_entire_site';

		const HOOK_HIDE_DEFAULT_PW_WP_POSITION = 'ppw_hide_default_pw_wp_position';

		const HOOK_PLUGIN_INFO = 'ppw_plugin_info';

		const HOOK_CUSTOM_HEADER_FORM_ENTIRE_SITE = 'ppw_custom_header_form_entire_site';

		const HOOK_CUSTOM_FOOTER_FORM_ENTIRE_SITE = 'ppw_custom_footer_form_entire_site';

		const HOOK_CUSTOM_TEXT_FEATURE_REMOVE_DATA = 'ppw_custom_text_feature_remove_data';

		const HOOK_POST_TYPES = 'ppw_post_types';

		const HOOK_MIGRATE_COMPLETE_MESSAGE = 'ppw_complete_message';

		const HOOK_PARAM_PASSWORD_SUCCESS = 'ppw_custom_param';

		const HOOK_SHORT_CODE_ATTRS = 'ppw_short_code_attributes';

		const HOOK_SUPPORTED_WHITELIST_ROLES = 'ppw_supported_white_list_roles';

		const HOOK_SUPPORTED_POST_TYPES = 'ppw_supported_post_types';

		const HOOK_SHORT_CODE_TEMPLATE = 'ppw_short_code_template';

		const HOOK_RESTRICT_CONTENT_ERROR_MESSAGE = 'ppw_restrict_content_custom_error_message';

		const HOOK_RESTRICT_CONTENT_BEFORE_CHECK_PWD = 'ppw_restrict_content_before_check_pw';

		const HOOK_RESTRICT_CONTENT_AFTER_VALID_PWD = 'ppw_restrict_content_after_valid_pw';

		const HOOK_SHORT_CODE_WHITELISTED_ROLES = 'ppw_restrict_content_whitelisted_roles';

		const HOOK_SHORT_CODE_ERROR_MESSAGE = 'ppw_restrict_content_error_message';

		const HOOK_SHORTCODE_NOT_SUPPORT_TYPE_ERROR_MESSAGE = 'ppw_restrict_content_not_support_post_type_error_message';

		const HOOK_SHORT_CODE_BEFORE_CHECK_PASSWORD = 'ppw_restrict_content_before_check_password';

		const HOOK_SHORT_CODE_AFTER_CHECK_PASSWORD = 'ppw_restrict_content_before_check_password';

		const HOOK_SHORT_CODE_VALID_POST_DATA = 'ppw_restrict_content_valid_post_data';

		const HOOK_HANDLE_BEFORE_RENDER_WOO_PRODUCT = 'ppw_handle_before_render_woo_product';

		const HOOK_SHORTCODE_ATTRIBUTES_VALIDATION = 'ppw_shortcode_attributes_validation';

		const HOOK_SHORTCODE_PASSWORDS = 'ppw_shortcode_passwords';

		const HOOK_SHORTCODE_RENDER_CONTENT = 'ppw_shortcode_render_content';

		const HOOK_SHORTCODE_CONTENT_SOURCE = 'ppw_content_shortcode_source';

		const HOOK_SHORTCODE_SETTINGS_EXTENDS = 'ppw_shortcode_settings_extend';

		const HOOK_MASTER_PASSWORDS_VALID_POST_TYPES = 'ppw_master_passwords_valid_post_types';

		const HOOK_SHORTCODE_BEAVER_BUILDER_FIELDS = 'ppw_shortcode_beaver_builder_fields';

		const HOOK_SHORTCODE_BEAVER_BUILDER_GENERAL_FIELDS = 'ppw_shortcode_beaver_builder_general_fields';

		const HOOK_SHORTCODE_BEAVER_BUILDER_INSTRUCTION_FIELDS = 'ppw_shortcode_beaver_builder_instruction_fields';

		const HOOK_SHORTCODE_BEAVER_BUILDER_ATTRIBUTES = 'ppw_shortcode_beaver_builder_attributes';

		const HOOK_SHORTCODE_ELEMENTOR_CONTROLS = 'ppw_shortcode_elementor_controls';

		const HOOK_SHORTCODE_ELEMENTOR_ATTRIBUTES = 'ppw_shortcode_elementor_attributes';

		const HOOK_SHORTCODE_ELEMENTOR_CONTENT = 'ppw_shortcode_elementor_content';

		const HOOK_SHORTCODE_ELEMENTOR_PREVIEW_CONTENT = 'ppw_shortcode_elementor_preview_content';

		const HOOK_SHORTCODE_BEFORE_RENDER_PASSWORD_FORM = 'ppw_shortcode_before_render_password_form';

		const HOOK_SIDEBAR_SHORTCODE = 'ppw_sidebar_shortcode';

		const HOOK_CHECK_CONTENT_IS_PROTECTED_BY_PRO = 'ppw_check_content_is_protected_by_pro';

		const HOOK_CUSTOM_OPTION_HIDE_PROTECT_CONTENT = 'ppw_custom_option_hide_protect_content';

		const HOOK_CUSTOM_POST_TYPE_HIDE_PROTECTED_POST = 'ppw_custom_post_type_for_hide_protected_post';

		const HOOK_CUSTOM_POST_TYPE_RECENT_POST = 'ppw_custom_post_type_for_recent_post';

		const HOOK_CUSTOM_POST_TYPE_NEXT_AND_PREVIOUS = 'ppw_custom_post_type_for_next_and_previous';

		const HOOK_CUSTOM_POST_ID_HIDE_PROTECTED_POST = 'ppw_custom_post_id_for_hide_protected_post';

		const HOOK_CUSTOM_DEFAULT_OPTIONS_HIDE_PROTECTED_POST = 'ppw_custom_default_options_for_hide_protected_post';

		const HOOK_CUSTOM_POSITIONS_HIDE_PROTECTED_POST = 'ppw_custom_positions_for_hide_protected_post';

		const HOOK_SHORTCODE_ALLOW_BYPASS_VALID_POST_TYPE = 'ppw_shortcode_allow_bypass_valid_post_type';

		const HOOK_ADVANCED_TAB_LOAD_ASSETS = 'ppw_misc_tab_load_assets';

		const HOOK_ADVANCED_VALID_INPUT_DATA = 'ppw_misc_valid_input_data';

		//phpcs:ignore #endregion

		//phpcs:ignore
		#region short code attribute
		const SHORT_CODE_FORM_HEADLINE = '[PPWP_FORM_HEADLINE]';

		const SHORT_CODE_FORM_INSTRUCT = '[PPWP_FORM_INSTRUCTIONS]';

		const SHORT_CODE_FORM_PLACEHOLDER = '[PPW_PLACEHOLDER]';

		const SHORT_CODE_FORM_AUTH = '[PPW_AUTH]';

		const SHORT_CODE_FORM_CURRENT_URL = '[PPW_CURRENT_URL]';

		const SHORT_CODE_FORM_ERROR_MESSAGE = '[PPW_ERROR_MESSAGE]';

		const SHORT_CODE_BUTTON = '[PPW_BUTTON_LABEL]';

		const SHORT_CODE_FORM_ID = '[PPW_FORM_ID]';

		const SHORT_CODE_FORM_CLASS = '[PPW_FORM_CLASS]';

		const SHORT_CODE_PASSWORD_LABEL = '[PPWP_FORM_PASSWORD_LABEL]';
		//phpcs:ignore #endregion

		//phpcs:ignore
		#region Default
		const DEFAULT_SUBMIT_LABEL = 'Enter';

		const DEFAULT_PASSWORD_LABEL = 'Password:';

		const DEFAULT_HEADLINE_TEXT = '';

		const DEFAULT_PLACEHOLDER = '';

		const DEFAULT_IS_SHOW_PASSWORD = 0;

		const DEFAULT_FORM_BACKGROUND_COLOR = '';

		const DEFAULT_FORM_PADDING = '';

		const DEFAULT_FORM_MARGIN = '';

		const DEFAULT_FORM_BORDER_RADIUS = '';

		const DEFAULT_HEADLINE_FONT_SIZE = '';

		const DEFAULT_HEADLINE_FONT_WEIGHT = '';

		const DEFAULT_HEADLINE_FONT_COLOR = '';

		const DEFAULT_TEXT_FONT_SIZE = '';

		const DEFAULT_TEXT_FONT_WEIGHT = '';

		const DEFAULT_TEXT_FONT_COLOR = '';

		const DEFAULT_ERROR_TEXT_FONT_SIZE = '';

		const DEFAULT_ERROR_TEXT_FONT_WEIGHT = '';

		const DEFAULT_ERROR_TEXT_FONT_COLOR = '#dc3232';

		const DEFAULT_ERROR_TEXT_BACKGROUND_COLOR = '';

		const DEFAULT_BUTTON_TEXT_FONT_COLOR = '';

		const DEFAULT_BUTTON_BACKGROUND_COLOR = '';

		const DEFAULT_BUTTON_TEXT_HOVER_COLOR = '';

		const DEFAULT_BUTTON_BACKGROUND_HOVER_COLOR = '';

		const DEFAULT_SHOW_PASSWORD_TEXT = 'Show password';

		const DEFAULT_SHOW_PASSWORD_TEXT_SIZE = '';

		const DEFAULT_PASSWORD_LABEL_FONT_COLOR = '';
		//phpcs:ignore #endregion

		//phpcs:ignore
		#region Message
		const DEFAULT_FORM_MESSAGE = 'This content is password protected. To view it please enter your password below:';

		const DEFAULT_WRONG_PASSWORD_MESSAGE = 'Please enter the correct password!';

		const BAD_REQUEST_MESSAGE = 'Our server cannot understand the data request!';

		const EMPTY_PASSWORD = 'Please fill out empty fields.';

		const DUPLICATE_PASSWORD = 'You can\'t create duplicate password. Please try again.';

		const SPACE_PASSWORD = 'Spaces not accepted in password. Please remove them and try again.';
		//phpcs:ignore #endregion

		//phpcs:ignore
		#region modules
		const MENU_NAME = 'wp_protect_password_options';

		const SITEWIDE_PAGE_PREFIX = 'ppwp-sitewide';

		const PCP_PAGE_PREFIX = 'ppwp-partial-protection';

		const META_BOX_MODULE = 'meta-box';

		const ENTIRE_SITE_MODULE = 'entire-site';

		const GENERAL_SETTINGS_MODULE = 'general';

		const MISC_SETTINGS_MODULE = 'misc';

		const TROUBLESHOOT_SETTINGS_MODULE = 'troubleshooting';

		const SHORTCODES_SETTINGS_MODULE = 'shortcodes';
		//phpcs:ignore #endregion

		const COOKIE_NAME = 'wp-postpass-role_';

		const ENTIRE_SITE_FORM_NONCE = 'ppw_entire_site_form_nonce';

		const GENERAL_FORM_NONCE = 'ppw_general_form_nonce';

		const ROW_ACTION_NONCE = 'ppw_row_action_nonce';

		const ENTIRE_SITE_OPTIONS = 'wp_protect_password_set_password_options';

		const GENERAL_OPTIONS = 'wp_protect_password_setting_options';

		const MISC_OPTIONS = 'wp_protect_password_misc_options';

		const SHORTCODE_OPTIONS = 'wp_protect_password_shortcode_options';

		const POST_PROTECTION_ROLES = 'post_protection_roles';

		const ENTIRE_SITE_COOKIE_NAME = 'pda_protect_password';

		const GLOBAL_PASSWORDS = 'wp_protect_password_multiple_passwords';

		const COOKIE_EXPIRED = 'wpp_password_cookie_expired';

		const REMOVE_DATA = 'wpp_remove_data';

		const USE_CUSTOM_FORM_ACTION = 'wpp_use_custom_form_action';

		const NO_RELOAD_PAGE = 'wpp_no_reload_page';

		const USE_SHORTCODE_PAGE_BUILDER = 'wpp_use_shortcode_page_builder';

		const PROTECT_EXCERPT = 'wpp_protect_excerpt';

		const MAX_COOKIE_EXPIRED = 8760;

		const MIN_COOKIE_EXPIRED = 0;

		const DOMAIN = 'password-protect-page';

		const WRONG_PASSWORD_PARAM = 'ppwp_enter_wrong_password';

		const PASSWORD_PARAM_NAME = 'ppwp';

		const PASSWORD_PARAM_VALUE = '1';

		const IS_PROTECT_ENTIRE_SITE = 'ppwp_apply_password_for_entire_site';

		const PASSWORD_ENTIRE_SITE = 'password_for_website';

		const SET_NEW_PASSWORD_ENTIRE_SITE = 'ppwp_set_new_password_for_entire_site';

		const META_BOX_NONCE = 'ppw_meta_box_nonce';

		const SUBSCRIBE_FORM_NONCE = 'ppw_subscribe_form_nonce';

		const USER_SUBSCRIBE = 'ppw_free_subscribe';

		const MIGRATED_DEFAULT_PW = 'migrated_default_pw';

		const MIGRATED_FREE_FLAG = 'migrated_free';

		const PRO_DIRECTORY = 'wp_protect_password/wp-protect-password.php';

		const PRO_ROOT_DIR = 'wp_protect_password';

		const DEV_PRO_DIRECTORY = 'password-protect-page-pro/wp-protect-password.php';

		const DEV_PRO_ROOT_DIR = 'password-protect-page-pro';

		const PPW_HOOK_SHORT_CODE_NAME = 'ppwp';

		const PPW_ERROR_MESSAGE_COLOR = '#dc3232';

		const WP_POST_PASS = 'wp-postpass_';

		const CUSTOM_POST_CLASS = 'ppwp-short-code-post';

		const CUSTOM_TABLE_COLUMN_NAME = 'ppw_password_protection';

		const CUSTOM_TABLE_COLUMN_TITLE = 'Password Protection';

		const DEFAULT_SHORTCODE_CLASS_NAME = 'ppw-restricted-content';

		const DEFAULT_SHORTCODE_HEADLINE = 'Restricted Content';

		const DEFAULT_SHORTCODE_DESCRIPTION = 'To view this protected content, enter the password below:';

		const DEFAULT_SHORTCODE_BUTTON = 'Enter';

		const DEFAULT_SHORTCODE_LABEL = 'Password:';

		const DEFAULT_SHORTCODE_ERROR_MSG = 'Please enter the correct password!';

		const TBL_NAME = 'pda_passwords';

		const TBL_VERSION = 'pda-pwd-tbl-version';

		//phpcs:ignore
		const DB_DATA_COLUMN_TABLE = array(
			array(
				'old_version' => '1.0',
				'new_version' => '1.1',
				'value'       => 'hits_count mediumint(9) NOT NULL',
			),
			array(
				'old_version' => '1.1',
				'new_version' => '1.2',
				'value'       => 'is_default tinyint(1) DEFAULT 0',
			),
			array(
				'old_version' => '1.2',
				'new_version' => '1.3',
				'value'       => 'expired_date BIGINT DEFAULT NULL',
			),
			array(
				'old_version' => '1.3',
				'new_version' => '1.4',
				'value'       => 'usage_limit mediumint(9)',
			),
		);

		//phpcs:ignore
		const DB_UPDATE_COLUMN_TABLE = array(
			array(
				'old_version' => '1.4',
				'new_version' => '1.5',
				'value'       => "campaign_app_type campaign_app_type text DEFAULT '' NULL",
			),
			array(
				'old_version' => '1.5',
				'new_version' => '1.6',
				'value'       => "password password varchar(255) DEFAULT '' NULL",
			),
		);

		const PPW_MASTER_GLOBAL = 'master_global';

		const PPW_MASTER_ROLE = 'master_role_';

		const MASTER_COOKIE_NAME = 'ppw_master-';

		//phpcs:ignore
		#region Const for feature "Hide Protected Content".
		//phpcs:ignore
		const DEFAULT_POST_TYPE = array( 'post', 'page' );

		const HIDE_PROTECTED = 'ppw_hide_protected_';

		const HIDE_SELECTED = 'ppw_hide_selected_';

		const FRONT_PAGE = 'ppw_front_page';

		const CATEGORY_PAGE = 'ppw_category_page';

		const TAG_PAGE = 'ppw_tag_page';

		const AUTHOR_PAGE = 'ppw_author_page';

		const ARCHIVES_PAGE = 'ppw_archives_page';

		const NEXT_PREVIOUS = 'ppw_next_previous';

		const RECENT_POST = 'ppw_recent_post';

		const SEARCH_RESULTS = 'ppw_search_results';

		const FEEDS = 'ppw_feeds';

		const EVERYWHERE_PAGE = 'ppw_everywhere_pages';

		const XML_YOAST_SEO_SITEMAPS = 'ppw_xml_yoast_seo_sitemaps';
		//phpcs:ignore #endregion

		//phpcs:ignore
		const PROTECTION_STATUS = array(
			'protect'   => 1,
			'unprotect' => 0,
		);

		const PROTECT_LABEL = 'Protect';

		const UNPROTECT_LABEL = 'Unprotect';
		//phpcs:ignore #endregion

		const CONTEXT_PASSWORD_FORM = 'PPF';

		const CONTEXT_PCP_PASSWORD_FORM = 'PCP';

		const CONTEXT_SITEWIDE_PASSWORD_FORM = 'SWP Lite';
	}
}
