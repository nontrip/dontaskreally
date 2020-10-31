jQuery( document ).ready( function ( $ ) {
	"use strict";

	// Show/hide settings for post format when choose post format
	var $format = $( '#post-formats-select' ).find( 'input.post-format' ),
		$formatBox = $( '#post-format-settings' );

	$format.on( 'change', function () {
		var type = $( this ).filter( ':checked' ).val();
		postFormatSettings( type );
	} );
	$format.filter( ':checked' ).trigger( 'change' );

	$( document.body ).on( 'change', '.editor-post-format .components-select-control__input', function () {
		var type = $( this ).val();
		postFormatSettings( type );
	} );

	$( window ).load( function () {
		var $el = $( document.body ).find( '.editor-post-format .components-select-control__input' ),
			type = $el.val();
		postFormatSettings( type );
	} );

	function postFormatSettings( type ) {
		$formatBox.hide();
		if ( $formatBox.find( '.rwmb-field' ).hasClass( type ) ) {
			$formatBox.show();
		}

		$formatBox.find( '.rwmb-field' ).slideUp();
		$formatBox.find( '.' + type ).slideDown();
	}

	// Show/hide settings for custom layout settings
	$( '#custom_layout' ).on( 'change', function () {
		if ( $( this ).is( ':checked' ) ) {
			$( '.rwmb-field.custom-layout' ).slideDown();
		}
		else {
			$( '.rwmb-field.custom-layout' ).slideUp();
		}
	} ).trigger( 'change' );

	// Show/hide settings for page header
	$( '#page_header_device' ).on( 'change', function () {
		var device = $( this ).val();

		if ( 'desktop' === device ) {
			$( '.rwmb-field.page-header-bg' ).show();
			$( '.rwmb-field.page-header-background-position' ).show();
			$( '.rwmb-field.page-header-background-repeat' ).show();
			$( '.rwmb-field.page-header-background-size' ).show();
			$( '.rwmb-field.page-header-background-attachment' ).show();
		} else {
			$( '.rwmb-field.page-header-bg' ).hide();
			$( '.rwmb-field.page-header-background-position' ).hide();
			$( '.rwmb-field.page-header-background-repeat' ).hide();
			$( '.rwmb-field.page-header-background-size' ).hide();
			$( '.rwmb-field.page-header-background-attachment' ).hide();
		}

		if ( 'tablet' === device ) {
			$( '.rwmb-field.page-header-bg-tablet' ).show();
			$( '.rwmb-field.page-header-background-position-tablet' ).show();
			$( '.rwmb-field.page-header-background-repeat-tablet' ).show();
			$( '.rwmb-field.page-header-background-size-tablet' ).show();
		} else {
			$( '.rwmb-field.page-header-bg-tablet' ).hide();
			$( '.rwmb-field.page-header-background-position-tablet' ).hide();
			$( '.rwmb-field.page-header-background-repeat-tablet' ).hide();
			$( '.rwmb-field.page-header-background-size-tablet' ).hide();
		}

		if ( 'mobile' === device ) {
			$( '.rwmb-field.page-header-bg-mobile' ).show();
			$( '.rwmb-field.page-header-background-position-mobile' ).show();
			$( '.rwmb-field.page-header-background-repeat-mobile' ).show();
			$( '.rwmb-field.page-header-background-size-mobile' ).show();
		} else {
			$( '.rwmb-field.page-header-bg-mobile' ).hide();
			$( '.rwmb-field.page-header-background-position-mobile' ).hide();
			$( '.rwmb-field.page-header-background-repeat-mobile' ).hide();
			$( '.rwmb-field.page-header-background-size-mobile' ).hide();
		}
	} ).trigger( 'change' );

	// Show/hide settings for header settings
	$( '#custom_header' ).on( 'change', function () {
		if ( $( this ).is( ':checked' ) ) {
			$( '.rwmb-field.enable-header-transparent, .rwmb-field.header-text-color, .rwmb-field.header-color, .rwmb-field.header-border' ).slideDown();
		}
		else {
			$( '.rwmb-field.enable-header-transparent, .rwmb-field.header-text-color, .rwmb-field.header-color, .rwmb-field.header-border' ).slideUp();
		}
	} ).trigger( 'change' );

	$( '#header_text_color' ).on( 'change', function () {
		var headerTextColor = $( this ).val();

		if ( headerTextColor === 'custom' ) {
			$( '.rwmb-field.header-color' ).slideDown();
		} else {
			$( '.rwmb-field.header-color' ).slideUp();
		}

	} ).trigger( 'change' );

	// Show/hide settings for page template settings
	$( '#page_template' ).on( 'change', function () {
		pageSettings( $( this ) );
	} ).trigger( 'change' );

	$( document.body ).on( 'change', '.editor-page-attributes__template .components-select-control__input', function () {
		pageSettings( $( this ) );
	} );

	$( window ).load( function () {
		var $el = $( document.body ).find( '.editor-page-attributes__template .components-select-control__input' );
		pageSettings( $el );
	} );

	function pageSettings( $el ) {
		if ( $el.val() === 'template-homepage.php' ||
			$el.val() === 'template-home-boxed.php' ||
			$el.val() === 'template-home-left-sidebar.php' ||
			$el.val() === 'template-home-no-footer.php'
		) {
			$( '#page-header-settings' ).hide();
		} else {
			$( '#page-header-settings' ).show();
		}

		if ( $el.val() === 'template-home-left-sidebar.php' ) {
			$( '#home-left-sidebar-settings' ).show();
			$( '#header-settings' ).hide();
		} else {
			$( '#home-left-sidebar-settings' ).hide();
			$( '#header-settings' ).show();
		}

		if ( $el.val() === 'template-home-no-footer.php' ) {
			$( '#page-background-settings' ).hide();
			$( '#home-full-slider-settings' ).show();
		} else {
			$( '#page-background-settings' ).show();
			$( '#home-full-slider-settings' ).hide();
		}
	}
} );
