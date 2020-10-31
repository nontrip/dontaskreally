(function ( $ ) {
	'use strict';

	var supro = supro || {};

	supro.init = function () {
		supro.$body = $( document.body ),
			supro.$window = $( window ),
			supro.$header = $( '#masthead' );

		this.imagesBox();
		this.sliders();
		this.videoLightBox();
		this.eventCountDown();
		this.productsCarousel();
		this.filterHandle();
		this.loadProducts();
		this.gmaps();
		this.tooltip();
		this.imagesSlides();
	};

	supro.imagesBox = function () {
		if ( suproShortCode.length === 0 || typeof suproShortCode.images === 'undefined' ) {
			return;
		}

		$.each( suproShortCode.images, function ( id, imagesData ) {
			var $sliders = $( document.getElementById( id ) );

			$sliders.not( '.slick-initialized' ).slick( {
				rtl           : suproShortCode.isRTL === '1',
				slidesToShow  : imagesData.columns,
				slidesToScroll: 1,
				infinite      : imagesData.infinite,
				arrows        : imagesData.nav,
				autoplay      : imagesData.autoplay,
				autoplaySpeed : imagesData.speed,
				dots          : imagesData.dot,
				prevArrow     : '<div class="supro-left-arrow"><i class="icon-arrow-left"></i></div>',
				nextArrow     : '<div class="supro-right-arrow"><i class="icon-arrow-right"></i></div>',
				responsive    : [
					{
						breakpoint: 1200,
						settings  : {
							arrows: false
						}
					},
					{
						breakpoint: 992,
						settings  : {
							arrows      : false,
							slidesToShow: parseInt( imagesData.columns ) > 3 ? 3 : imagesData.columns
						}
					},
					{
						breakpoint: 768,
						settings  : {
							arrows      : false,
							slidesToShow: 2
						}
					},
					{
						breakpoint: 481,
						settings  : {
							arrows      : false,
							slidesToShow: 1
						}
					}
				]
			} );
		} );
	};

	supro.sliders = function () {
		if ( suproShortCode.length === 0 || typeof suproShortCode.slider === 'undefined' ) {
			return;
		}

		$.each( suproShortCode.slider, function ( id, sliderData ) {
			var $sliders = $( document.getElementById( id ) ),
				$container = $sliders.siblings( '.slider-arrows' ).find( '.container' );

			$sliders.imagesLoaded().always( function () {
				$sliders.on( 'init', function () {
					$sliders.closest( '.supro-sliders' ).addClass( 'slider-loaded' );
				} );

				$sliders.not( '.slick-initialized' ).slick( {
					rtl           : suproShortCode.isRTL === '1',
					slidesToShow  : 1,
					slidesToScroll: 1,
					initialSlide  : sliderData.initial,
					infinite      : sliderData.infinite,
					arrows        : sliderData.nav,
					autoplay      : sliderData.autoplay,
					autoplaySpeed : sliderData.autoplay_speed,
					dots          : sliderData.dot,
					prevArrow     : '<div class="supro-left-arrow"><i class="icon-arrow-left"></i></div>',
					nextArrow     : '<div class="supro-right-arrow"><i class="icon-arrow-right"></i></div>',
					centerMode    : true,
					speed         : sliderData.speed,
					focusOnSelect : true,
					centerPadding : '16.4%',
					appendArrows  : $container,
					responsive    : [
						{
							breakpoint: 1366,
							settings  : {
								arrows: false
							}
						},
						{
							breakpoint: 768,
							settings  : {
								arrows      : false,
								centerMode  : false,
								initialSlide: 0
							}
						},
						{
							breakpoint: 481,
							settings  : {
								arrows      : false,
								centerMode  : false,
								initialSlide: 0
							}
						}
					]
				} );
			} );
		} );
	};

	supro.videoLightBox = function () {
		var $images = $( '.supro-video-banner' );

		if ( !$images.length ) {
			return;
		}

		var $links = $images.find( 'a.photoswipe' ),
			items = [];

		$links.each( function () {
			var $a = $( this );

			items.push( {
				html: $a.data( 'href' )
			} );

		} );

		$images.on( 'click', 'a.photoswipe', function ( e ) {
			e.preventDefault();

			var index = $links.index( $( this ) ),
				options = {
					index              : index,
					bgOpacity          : 0.85,
					showHideOpacity    : true,
					mainClass          : 'pswp--minimal-dark',
					barsSize           : { top: 0, bottom: 0 },
					captionEl          : false,
					fullscreenEl       : false,
					shareEl            : false,
					tapToClose         : true,
					tapToToggleControls: false
				};

			var lightBox = new PhotoSwipe( document.getElementById( 'pswp' ), window.PhotoSwipeUI_Default, items, options );
			lightBox.init();

			lightBox.listen( 'close', function () {
				$( '.mf-video-wrapper' ).find( 'iframe' ).each( function () {
					$( this ).attr( 'src', $( this ).attr( 'src' ) );
				} );
			} );
		} );
	};

	/**
	 * Event CountDown
	 */
	supro.eventCountDown = function () {

		if ( $( '.supro-time-format' ).length <= 0 ) {
			return;
		}

		$( '.supro-time-format' ).each( function () {
			var $eventDate = $( this );

			var diff = $( this ).find( '.supro-time-countdown' ).html();

			$eventDate.find( '.supro-time-countdown' ).FlipClock( diff, {
				clockFace: 'DailyCounter',
				countdown: true,
				labels   : [suproShortCode.days, suproShortCode.hours, suproShortCode.minutes, suproShortCode.seconds]
			} );
		} );
	};

	supro.productsCarousel = function () {
		if ( suproShortCode.length === 0 || typeof suproShortCode.productsCarousel === 'undefined' ) {
			return;
		}

		$.each( suproShortCode.productsCarousel, function ( id, sliderData ) {
			var $sliders = $( document.getElementById( id ) ),
				$container = $sliders.find( 'ul.products' );

			$container.not( '.slick-initialized' ).slick( {
				rtl           : suproShortCode.isRTL === '1',
				slidesToShow  : sliderData.show,
				slidesToScroll: sliderData.scroll,
				infinite      : sliderData.infinite,
				arrows        : sliderData.nav,
				autoplay      : sliderData.autoplay,
				autoplaySpeed : sliderData.autoplay_speed,
				dots          : sliderData.dot,
				prevArrow     : '<div class="supro-left-arrow"><i class="icon-chevron-left"></i></div>',
				nextArrow     : '<div class="supro-right-arrow"><i class="icon-chevron-right"></i></div>',
				speed         : sliderData.speed,
				focusOnSelect : true,
				responsive    : [
					{
						breakpoint: 1200,
						settings  : {
							arrows: false
						}
					},
					{
						breakpoint: 992,
						settings  : {
							arrows        : false,
							slidesToShow  : parseInt( sliderData.show ) > 3 ? 3 : parseInt( sliderData.show ),
							slidesToScroll: parseInt( sliderData.scroll ) > 3 ? 3 : parseInt( sliderData.scroll )
						}
					},
					{
						breakpoint: 768,
						settings  : {
							arrows        : false,
							slidesToShow  : parseInt( sliderData.show_mobile ),
							slidesToScroll: parseInt( sliderData.show_mobile )
						}
					},
					{
						breakpoint: 481,
						settings  : {
							arrows        : false,
							slidesToShow  : parseInt( sliderData.show_mobile ),
							slidesToScroll: 1
						}
					}
				]
			} );

		} );
	};

	supro.filterHandle = function () {
		var $parent = $( '.supro-products' );

		$parent.find( '.filter li:first' ).addClass( 'active' );

		$parent.on( 'click', '.filter li', function ( e ) {
			e.preventDefault();

			var $this = $( this ),
				$grid = $this.closest( '.supro-products' );

			if ( $this.hasClass( 'active' ) ) {
				return;
			}

			$this.addClass( 'active' ).siblings( '.active' ).removeClass( 'active' );

			var filter = $this.attr( 'data-filter' ),
				$container = $grid.find( '.product-wrapper' );

			var data = {
				nonce: $grid.data( 'nonce' )
			};

			if ( $grid.hasClass( 'filter-by-group' ) ) {
				data.type = filter;
				data.attr = $this.data( 'attr' );
			} else {
				data.attr = $grid.data( 'attr' );
				data.attr.category = filter;
			}

			if ( $grid.hasClass( 'supro-products-carousel' ) ) {
				data.load_more = $grid.data( 'load_more' );
			}

			$grid.addClass( 'loading' );

			wp.ajax.send( 'supro_load_products', {
				data   : data,
				success: function ( response ) {
					var css,
						$_products = $( response );

					if ( $grid.hasClass( 'supro-products-carousel' ) ) {
						css = 'suproFadeIn';
					} else {
						css = 'suproFadeInUp';
					}

					$grid.removeClass( 'loading' );

					$_products.find( 'ul.products > li' ).addClass( 'product suproAnimation ' + css );
					$container.children( 'div.woocommerce, .load-more' ).remove();
					$container.append( $_products );

					supro.productsCarousel();
					supro.tooltip();
				}
			} );
		} );
	};

	/**
	 * Products
	 */
	supro.loadProducts = function () {
		supro.$body.on( 'click', '.ajax-load-products', function ( e ) {
			e.preventDefault();

			var $el = $( this ),
				page = $el.data( 'page' );

			if ( $el.hasClass( 'loading' ) ) {
				return;
			}

			$el.addClass( 'loading' );

			wp.ajax.send( 'supro_load_products', {
				data: {
					page : page,
					type : $el.data( 'type' ),
					nonce: $el.data( 'nonce' ),
					attr : $el.data( 'attr' )
				},

				success: function ( data ) {
					$el.removeClass( 'loading' );
					var $data = $( data ),
						$products = $data.find( 'ul.products > li' ),
						$button = $data.find( '.ajax-load-products' ),
						$container = $el.closest( '.supro-products' ),
						$grid = $container.find( 'ul.products' );

					// If has products
					if ( $products.length ) {
						// Add classes before append products to grid
						$products.addClass( 'product' );

						for ( var index = 0; index < $products.length; index++ ) {
							$( $products[index] ).css( 'animation-delay', index * 100 + 100 + 'ms' );
						}
						$products.addClass( 'suproFadeInUp suproAnimation' );
						$grid.append( $products );

						if ( $button.length ) {
							$el.replaceWith( $button );
						} else {
							$el.slideUp();
						}
					}

					supro.tooltip();
				}
			} );
		} );
	};

	/**
	 * Init Google maps
	 */
	supro.gmaps = function () {

		if ( suproShortCode.length === 0 || typeof suproShortCode.map === 'undefined' ) {
			return;
		}

		var mapOptions = {
				scrollwheel       : false,
				draggable         : true,
				zoom              : 10,
				mapTypeId         : google.maps.MapTypeId.ROADMAP,
				panControl        : false,
				zoomControl       : true,
				zoomControlOptions: {
					style: google.maps.ZoomControlStyle.SMALL
				},
				scaleControl      : false,
				streetViewControl : false

			},
			customMap;

		var bounds = new google.maps.LatLngBounds();
		var infoWindow = new google.maps.InfoWindow();


		$.each( suproShortCode.map, function ( id, mapData ) {
			var styles =
				[
					{
						'featureType': 'water',
						'elementType': 'geometry',
						'stylers'    : [{ 'color': '#e9e9e9' }, { 'lightness': 17 }]
					},
					{
						'featureType': 'landscape',
						'elementType': 'geometry',
						'stylers'    : [{ 'color': '#f5f5f5' }, { 'lightness': 20 }]
					},
					{
						'featureType': 'road.highway',
						'elementType': 'geometry.fill',
						'stylers'    : [
							{ 'color': '#ffffff' }, { 'lightness': 17 }]
					},
					{
						'featureType': 'road.highway',
						'elementType': 'geometry.stroke',
						'stylers'    : [{ 'color': '#ffffff' }, { 'lightness': 29 }, { 'weight': 0.2 }]
					},
					{
						'featureType': 'road.arterial',
						'elementType': 'geometry',
						'stylers'    : [{ 'color': '#ffffff' }, { 'lightness': 18 }]
					},
					{
						'featureType': 'road.local',
						'elementType': 'geometry',
						'stylers'    : [{ 'color': '#ffffff' }, { 'lightness': 16 }]
					},
					{
						'featureType': 'poi',
						'elementType': 'geometry',
						'stylers'    : [{ 'color': '#f5f5f5' }, { 'lightness': 21 }]
					},
					{
						'featureType': 'poi.park',
						'elementType': 'geometry',
						'stylers'    : [{ 'color': '#dedede' }, { 'lightness': 21 }]
					},
					{
						'elementType': 'labels.text.stroke',
						'stylers'    : [{ 'visibility': 'on' }, { 'color': '#ffffff' }, { 'lightness': 16 }]
					},
					{
						'elementType': 'labels.text.fill',
						'stylers'    : [{ 'saturation': 36 }, { 'color': '#333333' }, { 'lightness': 40 }]
					},
					{
						'elementType': 'labels.icon',
						'stylers'    : [{ 'visibility': 'off' }]
					},
					{
						'featureType': 'transit',
						'elementType': 'geometry',
						'stylers'    : [{ 'color': '#f2f2f2' }, { 'lightness': 19 }]
					},
					{
						'featureType': 'administrative',
						'elementType': 'geometry.fill',
						'stylers'    : [{ 'color': '#fefefe' }, { 'lightness': 20 }]
					},
					{
						'featureType': 'administrative',
						'elementType': 'geometry.stroke',
						'stylers'    : [{ 'color': '#fefefe' }, { 'lightness': 17 }, { 'weight': 1.2 }]
					}
				];

			customMap = new google.maps.StyledMapType( styles,
				{ name: 'Styled Map' } );

			if ( mapData.number > 1 ) {
				mutiMaps( infoWindow, bounds, mapOptions, mapData, id, styles, customMap );
			} else {
				singleMap( mapOptions, mapData, id, styles, customMap );
			}

		} );
	};

	function singleMap( mapOptions, mapData, id, styles, customMap ) {
		var map,
			marker,
			location = new google.maps.LatLng( mapData.lat, mapData.lng );

		// Update map options
		mapOptions.zoom = parseInt( mapData.zoom, 10 );
		mapOptions.center = location;
		mapOptions.mapTypeControlOptions = {
			mapTypeIds: [google.maps.MapTypeId.ROADMAP]
		};

		// Init map
		map = new google.maps.Map( document.getElementById( id ), mapOptions );

		// Create marker options
		var markerOptions = {
			map     : map,
			position: location
		};
		if ( mapData.marker ) {
			markerOptions.icon = {
				url: mapData.marker
			};
		}

		map.mapTypes.set( 'map_style', customMap );
		map.setMapTypeId( 'map_style' );

		// Init marker
		marker = new google.maps.Marker( markerOptions );

		if ( mapData.info ) {
			var infoWindow = new google.maps.InfoWindow( {
				content : '<div class="info-box mf-map">' + mapData.info + '</div>',
				maxWidth: 600
			} );

			google.maps.event.addListener( marker, 'click', function () {
				infoWindow.open( map, marker );
			} );
		}
	}

	function mutiMaps( infoWindow, bounds, mapOptions, mapData, id, styles, customMap ) {

		// Display a map on the page
		mapOptions.zoom = parseInt( mapData.zoom, 10 );
		mapOptions.mapTypeControlOptions = {
			mapTypeIds: [google.maps.MapTypeId.ROADMAP]
		};

		var map = new google.maps.Map( document.getElementById( id ), mapOptions );
		map.mapTypes.set( 'map_style', customMap );
		map.setMapTypeId( 'map_style' );
		for ( var i = 0; i < mapData.number; i++ ) {
			var lats = mapData.lat,
				lng = mapData.lng,
				info = mapData.info;

			var position = new google.maps.LatLng( lats[i], lng[i] );
			bounds.extend( position );

			// Create marker options
			var markerOptions = {
				map     : map,
				position: position
			};
			if ( mapData.marker ) {
				markerOptions.icon = {
					url: mapData.marker
				};
			}

			// Init marker
			var marker = new google.maps.Marker( markerOptions );

			// Allow each marker to have an info window
			googleMaps( infoWindow, map, marker, info[i] );

			// Automatically center the map fitting all markers on the screen
			map.fitBounds( bounds );
		}
	}

	function googleMaps( infoWindow, map, marker, info ) {
		google.maps.event.addListener( marker, 'click', function () {
			infoWindow.setContent( info );
			infoWindow.open( map, marker );
		} );
	}

	/**
	 * Init tooltip
	 */
	supro.tooltip = function () {
		$( '[data-rel=tooltip]' ).tooltip( { offsetTop: -15 } );
	};

	/**
	 * Images Slides
	 */
	supro.imagesSlides = function () {
		if ( suproShortCode.length === 0 || typeof suproShortCode.imagesSlides === 'undefined' ) {
			return;
		}

		$.each( suproShortCode.imagesSlides, function ( id, sliderData ) {
			var $sliders = $( document.getElementById( id ) );


			$sliders.not( '.slick-initialized' ).slick( {
				rtl           : suproShortCode.isRTL === '1',
				slidesToShow  : sliderData.columns,
				slidesToScroll: 1,
				infinite      : false,
				arrows        : sliderData.nav,
				autoplay      : sliderData.autoplay,
				autoplaySpeed : sliderData.autoplay_speed,
				dots          : sliderData.dot,
				prevArrow     : '<div class="supro-left-arrow"><i class="icon-chevron-left"></i></div>',
				nextArrow     : '<div class="supro-right-arrow"><i class="icon-chevron-right"></i></div>',
				speed         : sliderData.speed,
				focusOnSelect : true,
				responsive    : [
					{
						breakpoint: 1200,
						settings  : {
							arrows: false
						}
					},
					{
						breakpoint: 992,
						settings  : {
							arrows      : false,
							slidesToShow: parseInt( sliderData.columns ) > 2 ? 2 : parseInt( sliderData.columns )
						}
					},
					{
						breakpoint: 481,
						settings  : {
							arrows      : false,
							slidesToShow: 1
						}
					}
				]
			} );

		} );
	};

	/**
	 * Document ready
	 */
	$( function () {
		supro.init();
	} );

})( jQuery );