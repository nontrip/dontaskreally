<?php

class PPWBB_Shortcode_Module extends FLBuilderModule {
	/**
	 * PPWBB_Individual_Content_Module constructor.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'        => __( 'Password Protect WordPress (PPWP)', 'password-protect-page' ),
				'description' => __( 'Password protected content', 'password-protect-page' ),
				'category'    => __( 'Partial Content Protection', 'password-protect-page' ),
				'dir'         => PPW_DIR_PATH . 'includes/addons/beaver-builder/modules/ppw-individual-page/',
				'url'         => PPW_DIR_URL . 'includes/addons/beaver-builder/modules/ppw-individual-page/',
				'icon'        => 'editor-code.svg'
			)
		);
	}

}

function ppwbb_load_individual_content_module() {
	$raw_roles = apply_filters(
		'ppw_supported_white_list_roles',
		array(
			'administrator',
			'editor',
			'author',
			'contributor',
			'subscriber',
		)
	);

	$role_options = array_reduce(
		$raw_roles,
		function ( $carry, $value ) {
			$carry[ $value ] = __( $value, 'password-protect-page' );

			return $carry;
		},
		array()
	);

	$general_fields = array(
		'ppwp_passwords'         => array(
			'type'        => 'text',
			'label'       => __( 'Passwords', 'password-protect-page' ),
			'placeholder' => __( 'Enter your password, e.g. password1 password2', 'password-protect-page' ),
			'description' => 'Multiple passwords are separated by space, case-sensitivity, no more than 100 characters and don’t contain [, ], “, ‘',
			'default'     => 'password1 password2',
		),
		'ppwp_whitelisted_roles' => array(
			'type'         => 'select',
			'label'        => __( 'Whitelisted Roles', 'password-protect-page' ),
			'description'  => 'Select user roles who can access protected area without having to enter passwords',
			'options'      => $role_options,
			'multi-select' => true,
		),
		'ppwp_protected_content' => array(
			'type'    => 'editor',
			'label'   => __( 'Protected Content', 'password-protect-page' ),
			'default' => __( 'This is your protected content.', 'password-protect-page' ),
			'rows'    => '6',
		),
	);

	$general_fields = apply_filters( PPW_Constants::HOOK_SHORTCODE_BEAVER_BUILDER_GENERAL_FIELDS, $general_fields );

	$instruction_fields = array(
		'ppwp_headline'    => array(
			'type'    => 'text',
			'label'   => __( 'Headline', 'password-protect-page' ),
			'default' => __( PPW_Constants::DEFAULT_SHORTCODE_HEADLINE, 'password-protect-page' ),

		),
		'ppwp_placeholder' => array(
			'type'    => 'text',
			'label'   => __( 'Placeholder', 'password-protect-page' ),
			'default' => __( '', 'password-protect-page' ),
		),
		'ppwp_button'      => array(
			'type'    => 'text',
			'label'   => __( 'Button', 'password-protect-page' ),
			'default' => __( PPW_Constants::DEFAULT_SHORTCODE_BUTTON, 'password-protect-page' ),
		),
		'ppwp_description' => array(
			'type'    => 'editor',
			'label'   => __( 'Description', 'password-protect-page' ),
			'default' => __( PPW_Constants::DEFAULT_SHORTCODE_DESCRIPTION, 'password-protect-page' ),
			'rows'    => '6',
		),
	);

	$instruction_fields = apply_filters( PPW_Constants::HOOK_SHORTCODE_BEAVER_BUILDER_INSTRUCTION_FIELDS, $instruction_fields );

	$form = array(
		'general' =>
			array(
				'title'    => __( 'Shortcode', 'password-protect-page' ),
				'sections' => array(
					'general'     => array(
						'title'  => __( 'Protection', 'password-protect-page' ),
						'fields' => $general_fields,
					),
					'instruction' => array(
						'title'  => __( 'Password Form', 'password-protect-page' ),
						'fields' => $instruction_fields,
					),
				),
			),
	);

	$form = apply_filters( PPW_Constants::HOOK_SHORTCODE_BEAVER_BUILDER_FIELDS, $form );

	FLBuilder::register_module(
		'PPWBB_Shortcode_Module',
		$form
	);
}

ppwbb_load_individual_content_module();
