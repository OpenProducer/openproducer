/**
 * Usage Notice.
 *
 * @summary This file handles the notice to accept usage statistics.
 *
 * @since 2.11.0
 */

/* global jQuery,ajaxurl */

var BOLDGRID = BOLDGRID || {};

BOLDGRID.LIBRARY = BOLDGRID.LIBRARY || {};

( function( $ ) {
	'use strict';

	var self;

	/**
	 * Usage Notice.
	 *
	 * @since 2.11.0
	 */
	BOLDGRID.LIBRARY.UsageNotice = {

		/**
		 * i18n.
		 *
		 * @since 2.11.0
		 *
		 * @type object
		 */
		i18n: window.BglibUsageNotice || {},

		/**
		 * Init.
		 *
		 * @since 2.11.0
		 */
		init: function() {
			self._onReady();
		},

		/**
		 * Handle the click of a yes / no choice.
		 *
		 * @since 2.11.0
		 */
		onClick: function() {
			var $anchor = $( this ),
				choice = $anchor.attr( 'data-choice' ),
				data,
				$notice = $( '#bglib_usage_notice' );

			data = {
				action: 'bglib_usage_signup',
				choice: choice,
				nonce: self.i18n.nonce
			};

			$.post(
				ajaxurl,
				data,
				function( response ) {
					var fail = response.success === undefined || false === response.success;

					// @todo On success, we can do more than simply slide up the notice.
					fail ? self.onDismissError() : $notice.slideUp();
				},
				'json'
			).fail( function( jqXHR ) {
				self.onDismissError();
			} );

			return false;
		},

		/**
		 * Actions to take if a notice is not dismissed successfully.
		 *
		 * This should never really be the case, but, just in case.
		 *
		 * @since 2.11.0
		 */
		onDismissError: function() {
			$( '#bglib_usage_notice' )

				// Change the notice from info to alert.
				.removeClass( 'notice-info' )
				.addClass( 'notice-error' )

				// Empty the notice and add our generic error message.
				.empty()
				.html( '<p>' + self.i18n.error + '</p>' );
		},

		/**
		 * On ready.
		 *
		 * @since 2.11.0
		 */
		_onReady: function() {
			$( function() {
				$( '#bglib_usage_notice a' ).on( 'click', self.onClick );
			} );
		}
	};

	self = BOLDGRID.LIBRARY.UsageNotice;
} )( jQuery );

BOLDGRID.LIBRARY.UsageNotice.init();
