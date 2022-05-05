window.BOLDGRID = window.BOLDGRID || {};
BOLDGRID.EDITOR = BOLDGRID.EDITOR || {};
BOLDGRID.EDITOR.NOTICE = BOLDGRID.EDITOR.NOTICE || {};

import { Base as Notice } from './base';

( function( $ ) {
	'use strict';

	var self,
		BG = BOLDGRID.EDITOR;

	BG.NOTICE.Update = {
		title: 'New Release: Post and Page Builder',

		template: wp.template( 'boldgrid-upgrade-notice' ),

		init: function() {
			if ( BoldgridEditor.display_update_notice ) {
				this.notice = new Notice();

				self.displayPanel();

				// Delay event, make sure user sees modal.
				setTimeout( function() {
					self.bindEvents();
				}, 1000 );
			}
		},

		/**
		 * Bind all events.
		 *
		 * @since 1.3
		 */
		bindEvents: function() {
			this.notice.bindDismissButton();
			self.bodyClick();
		},

		/**
		 * Bind the click outside of the pnael to the okay button.
		 *
		 * @since 1.3
		 */
		bodyClick: function() {
			var stopProp = function( e ) {
				e.stopPropagation();
			};

			$( 'body' ).one( 'click', () => {
				this.notice.dismissPanel();
			} );

			BG.Panel.$element.on( 'click', stopProp );
		},

		/**
		 * Display update notification panel.
		 *
		 * @since 1.3
		 */
		displayPanel: function() {
			$( 'body' ).addClass( 'bg-editor-intro-1-3 bg-editor-intro' );
			self.initPanel();
			self.renderPanel();
		},

		/**
		 * Animate the panel when it appears.
		 *
		 * @since 1.3
		 */
		animatePanel: function() {
			BG.Panel.$element
				.addClass( 'animated bounceInDown' )
				.one(
					'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend',
					function() {
						$( '.bg-editor-loading' ).hide();
					}
				);
		},

		/**
		 * Positon the panel on the screen and display.
		 *
		 * @since 1.3
		 */
		renderPanel: function() {
			BG.Panel.$element.show();
			self.animatePanel();
		},

		/**
		 * Setup the parameters needed for the panel to be created.
		 *
		 * @since 1.3
		 */
		initPanel: function() {
			BG.Panel.setDimensions( 800, 400 );
			BG.Panel.setTitle( self.title );
			BG.Panel.setContent( self.template() );
		}
	};

	self = BG.NOTICE.Update;
} )( jQuery );
