/**
 * jQuery Tabs plugin 1.0.0
 *
 * @author Dr Furi
 */
( function( $ ) {
	$.fn.tabs = function() {
		return this.each( function() {
			var $element = $( this ),
				$nav     = $element.find( '.tabs-nav' ),
				$tabs    = $nav.find( 'a' ),
				$panels  = $element.find( '.tabs-panel' );

			$tabs.filter( ':first' ).addClass( 'active' );
			$panels.filter( ':first' ).addClass( 'active' );

			$tabs.on( 'click', function( e ) {
				e.preventDefault();

				var $tab = $( this ),
					index = $tab.parent().index();

				if ( $tab.hasClass( 'active' ) ) {
					return;
				}

				$tabs.removeClass( 'active' );
				$tab.addClass( 'active' );
				$panels.removeClass( 'active' );
				$panels.filter( ':eq(' + index + ')' ).addClass( 'active' );
			} );
		} );
	};

	/* Init tabs */
	$( function() {
		$( '.tabs' ).tabs();
	} );
} )( jQuery );
