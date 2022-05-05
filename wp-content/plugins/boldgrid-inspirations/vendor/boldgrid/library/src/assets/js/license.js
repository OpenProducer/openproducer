/* global ajaxurl,jQuery */

var BOLDGRID = BOLDGRID || {};
BOLDGRID.LIBRARY = BOLDGRID.LIBRARY || {};

( function( $ ) {
	'use strict';

	BOLDGRID.LIBRARY.License = {

		/**
		 * @summary Make a call to clear the license data.
		 *
		 * @since 2.2.0
		 *
		 * @param string   plugin    Plugin name, such as 'boldgrid-backup'.
		 * @param function onSuccess
		 * @param function onError
		 */
		clear: function( plugin, onSuccess, onError ) {
			var data = {
				action: 'bg_clear_license',
				plugin: plugin
			};

			$.post( ajaxurl, data, function( response ) {
				onSuccess( response );
			} ).error( function() {
				onError();
			} );
		}
	};
} )( jQuery );
