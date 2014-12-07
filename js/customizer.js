/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );
	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					'clip': 'auto',
					'color': to,
					'position': 'relative'
				} );
			}
		} );
	} );
	wp.customize( '_n_header_style', function( value ) {
		value.bind(function(to) {
			if ('fixed' === to) {
				$('#masthead').css('position','fixed');
				$('#content').css('padding-top','100px');
			} else {
				$('#masthead').css('position','unset');
				$('#content').css('padding-top','0');
			}
		});
	});
	wp.customize( '_n_header_bg_color', function( value ) {
		value.bind(function(to) {
			$('.ha-header').css('background',to);
			$('.ha-header .sub-menu li').css('background',to);
		});
	});
	wp.customize( '_n_header_bg_opacity', function( value ) {
		value.bind(function(to) {
			$('.ha-header').css('opacity',to);
			$('.ha-header .sub-menu li').css('opacity',to);
		});
	});		

} )( jQuery );
