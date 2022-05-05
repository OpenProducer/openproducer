window.BOLDGRID = window.BOLDGRID || {};
BOLDGRID.EDITOR = BOLDGRID.EDITOR || {};

( function( $ ) {
	'use strict';

	var self,
		BG = BOLDGRID.EDITOR;

	BOLDGRID.EDITOR.Tooltip = {

		/**
		 * @var template.
		 * @since 1.2.7
		 */
		template: wp.template( 'boldgrid-editor-tooltip' ),

		/**
		 * Render help tooltips.
		 *
		 * @since 1.2.7
		 */
		renderTooltips() {
			self._configTooltips();
			self._inlineTooltips();
		},

		/**
		 * Add tooltips.
		 *
		 * @since 1.4
		 *
		 * @param {jQuery} $el     Element to apply tooltip to.
		 * @param {string} message Message for tooltip
		 */
		addTooltip( $el, message ) {
			if (
				false ===
				$el
					.children()
					.first()
					.hasClass( 'boldgrid-tooltip-wrap' )
			) {
				$el.prepend( self.template( { message: message } ) );
			}
		},

		/**
		 * Create tooltips defined inline.
		 *
		 * @since 1.4
		 */
		_inlineTooltips() {
			BG.Panel.$element.find( '[data-tooltip-inline]' ).each( ( index, el ) => {
				let $el = $( el );
				self.addTooltip( $el, $el.data( 'tooltip-inline' ) );
			} );
		},

		/**
		 * Create tooltips defined in configurations.
		 *
		 * @since 1.4
		 */
		_configTooltips() {
			_.each( BoldgridEditor.builder_config.helpTooltip, function( message, selector ) {
				BG.Panel.$element
					.add( BOLDGRID.EDITOR.CONTROLS.Color.$colorPanel )
					.find( selector )
					.each( function() {
						self.addTooltip( $( this ), message );
					} );
			} );
		}
	};

	self = BOLDGRID.EDITOR.Tooltip;
} )( jQuery );
