window.BOLDGRID = window.BOLDGRID || {};
BOLDGRID.EDITOR = BOLDGRID.EDITOR || {};

( function( $ ) {
	'use strict';

	var self,
		BG = BOLDGRID.EDITOR;

	BG.Util = {

		/**
		 * Convert Pixels to Ems.
		 *
		 * @since 1.2.7
		 * @return string ems;
		 */
		convertPxToEm: function( px, fontSize ) {
			var ems = 0;

			fontSize = fontSize ? parseInt( fontSize ) : 0;
			px = px ? parseInt( px ) : 0;

			if ( fontSize && px ) {
				ems = ( px / fontSize ).toFixed( 1 );
			}

			return ems;
		},

		/**
		 * Get classes from an element %like% keyword.
		 *
		 * @since 1.2.7
		 * @return string classes;
		 */
		getClassesLike: function( $element, namespace ) {
			var classString = $element.attr( 'class' ),
				allClasses = [],
				classes = [];

			allClasses = classString ? classString.split( ' ' ) : [];

			$.each( allClasses, function() {
				if ( 0 === this.indexOf( namespace ) ) {
					classes.push( this );
				}
			} );

			return classes;
		},

		/**
		 * Get all component classes.
		 *
		 * @since 1.5
		 *
		 * @param  {string} classes         Class string to test.
		 * @param  {string} componentPrefix Component class name.
		 * @return {array}                  Name of classes.
		 */
		getComponentClasses: function( classes, prefix ) {
			var $temp = $( '<div>' ).attr( 'class', classes ),
				componentClasses = self.getClassesLike( $temp, prefix );

			$temp.remove();

			return componentClasses;
		},

		/**
		 * Remove All component classes from an element.
		 *
		 * @since 1.5
		 *
		 * @param  {jQuery} $el    Element to modify.
		 * @param  {string} prefix Compnent class name.
		 */
		removeComponentClasses: function( $el, prefix ) {
			var pattern = '(^|\\s)' + prefix + '\\S+',
				rgxp = new RegExp( pattern, 'g' );

			$el.removeClass( function( index, css ) {
				return ( css.match( rgxp ) || [] ).join( ' ' );
			} );
		},

		/**
		 * Check the users browser.
		 *
		 * @since 1.4
		 *
		 * @return {string} User browser.
		 */
		checkBrowser: function() {
			var browser,
				chrome = navigator.userAgent.search( 'Chrome' ),
				firefox = navigator.userAgent.search( 'Firefox' ),
				ie8 = navigator.userAgent.search( 'MSIE 8.0' ),
				ie9 = navigator.userAgent.search( 'MSIE 9.0' );

			if ( -1 < chrome ) {
				browser = 'Chrome';
			} else if ( -1 < firefox ) {
				browser = 'Firefox';
			} else if ( -1 < ie9 ) {
				browser = 'MSIE 9.0';
			} else if ( -1 < ie8 ) {
				browser = 'MSIE 8.0';
			}
			return browser;
		}
	};

	self = BOLDGRID.EDITOR.Util;
} )( jQuery );
