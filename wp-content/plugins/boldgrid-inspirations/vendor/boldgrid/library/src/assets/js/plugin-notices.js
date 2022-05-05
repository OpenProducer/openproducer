/**
 * Plugin Notices.
 *
 * @summary This file handles plugin notices.
 *
 * @since 2.12.1
 */

/* global jQuery,ajaxurl */

var BOLDGRID = BOLDGRID || {};

BOLDGRID.LIBRARY = BOLDGRID.LIBRARY || {};

( function( $ ) {
	'use strict';

	var self;

	/**
	 * Plugin Notices.
	 *
	 * @since 2.12.1
	 */
	BOLDGRID.LIBRARY.PluginNotices = {

		/**
		 * Add notice counts to the menus.
		 *
		 * @since 2.12.1
		 */
		addNoticeCounts: function() {
			var i;

			for ( i = 0; i < self.i18n.counts.length; i++ ) {
				var $item;

				/*
				 * Get our item that needs to have a notice count added to it.
				 *
				 * It is either a top menu item, or a sub menu item.
				 */
				$item = $( '#adminmenu a[href="' + self.i18n.counts[i].href + '"] .wp-menu-name' );
				$item =
					0 < $item.length ? $item : $( '#adminmenu a[href="' + self.i18n.counts[i].href + '"]' );

				$item.append(
					'<span class="bglib-unread-notice-count">' + self.i18n.counts[i].count + '</span>'
				);
			}
		},

		/**
		 * i18n.
		 *
		 * @since 2.12.1
		 *
		 * @type object
		 */
		i18n: window.BglibPluginNotices || {},

		/**
		 * Init.
		 *
		 * @since 2.12.1
		 */
		init: function() {
			self._onReady();
		},

		/**
		 * On ready.
		 *
		 * @since 2.12.1
		 */
		_onReady: function() {
			$( function() {
				self.addNoticeCounts();
			} );
		}
	};

	self = BOLDGRID.LIBRARY.PluginNotices;
} )( jQuery );

BOLDGRID.LIBRARY.PluginNotices.init();
