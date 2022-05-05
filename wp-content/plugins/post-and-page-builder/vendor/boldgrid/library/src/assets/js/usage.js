/**
 * Usage class.
 *
 * @summary This file handles usage statistics.
 *
 * @since 2.11.0
 */

/* global jQuery,gtag */

var BOLDGRID = BOLDGRID || {};

BOLDGRID.LIBRARY = BOLDGRID.LIBRARY || {};

( function( $ ) {
	'use strict';

	var self;

	/**
	 * Total Upkeep Usage.
	 *
	 * @since 2.11.0
	 */
	BOLDGRID.LIBRARY.Usage = {

		/**
		 * Get the page path we will use to track the pageview.
		 *
		 * @since 2.11.0
		 *
		 * @return string
		 */
		getPagePath: function() {
			return '/user-domain/wp-admin/admin.php?page=' + self.i18n.page;
		},

		/**
		 * i18n.
		 *
		 * @since 2.11.0
		 *
		 * @type object
		 */
		i18n: window.BglibUsage || {},

		/**
		 * Init.
		 *
		 * @since 2.11.0
		 */
		init: function() {
			self._onReady();
		},

		/**
		 * Actions to take when a user clicks a nav item of the bglib UI class.
		 *
		 * @since 2.11.0
		 */
		onNavClick: function() {
			var pageviewParams = {
				page_path: self.getPagePath() + '&section=' + $( this ).attr( 'data-section-id' )
			};

			self.triggerPageview( pageviewParams );
		},

		/**
		 * Trigger a pageview.
		 *
		 * @since 2.11.0
		 *
		 * @param object params An object containing params for the gtag call.
		 */
		triggerPageview: function( params ) {

			/*
			 * Allow this method to be called without passing in params. If no params are passed in,
			 * by default we'll only add the page path.
			 */
			if ( params === undefined ) {
				params = {
					page_path: self.getPagePath()
				};
			}

			// Configure license.
			params.license = self.i18n.license;
			params.custom_map = {
				dimension7: 'license'
			};

			// Configure linker. This will add client id, on click, to all boldgrid.com links.
			params.linker = { domains: [ 'boldgrid.com' ] };

			gtag( 'config', self.i18n.ga_id, params );
		},

		/**
		 * On ready.
		 *
		 * @since 1.7.0
		 */
		_onReady: function() {
			$( function() {

				// Log the pageview.
				self.triggerPageview();

				// Listen to clicks on the bglib UI's nav.
				$( '.bg-left-nav li' ).on( 'click', self.onNavClick );
			} );
		}
	};

	self = BOLDGRID.LIBRARY.Usage;
} )( jQuery );

BOLDGRID.LIBRARY.Usage.init();
