(function ( $, api )
{
	// Editor control.
	$( window ).load( function ()
	{
		$( 'textarea.wp-editor-area' ).each( function ()
		{
			var $this = $( this ),
				id = $this.attr( 'id' ),
				$input = $( 'input[data-customize-setting-link="' + id + '"]' ),
				editor = tinyMCE.get( id ),
				setChange,
				content;

			if ( editor )
			{
				editor.on( 'change', function ( e )
				{
					editor.save();
					content = editor.getContent();
					clearTimeout( setChange );
					setChange = setTimeout( function ()
					{
						$input.val( content ).trigger( 'change' );
					}, 500 );
				} );
			}

			$this.css( 'visibility', 'visible' ).on( 'keyup', function ()
			{
				content = $this.val();
				clearTimeout( setChange );
				setChange = setTimeout( function ()
				{
					$input.val( content ).trigger( 'change' );
				}, 500 );
			} );
		} );
	} );

})( jQuery, wp.customize );
