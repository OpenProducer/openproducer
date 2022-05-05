import { Palette as ColorPalette } from './color/palette';

window.BOLDGRID = window.BOLDGRID || {};
BOLDGRID.EDITOR = BOLDGRID.EDITOR || {};
BOLDGRID.EDITOR.CONTROLS = BOLDGRID.EDITOR.CONTROLS || {};

( function() {
	'use strict';

	var self,
		BG = BOLDGRID.EDITOR;

	BG.CONTROLS.Global = {
		$element: null,

		name: 'global-options',

		tooltip: 'Global Options',

		priority: 2,

		iconClasses: 'fa fa-globe',

		selectors: [ 'html' ],

		menuDropDown: {
			title: 'Global Options',
			options: [
				{
					name: 'Color Palette',
					class: 'action open-color-palette font-awesome fa-paint-brush'
				},

				/*
				{
					name: 'Post Settings',
					class: 'action edit-page-settings font-awesome fa-cogs'
				},
				*/

				{
					name: 'Delete Post Content',
					class: 'action delete-all-content font-awesome fa-trash'
				}
			]
		},

		init: function() {

			// @todo this needs better handling perhaps a filter.
			if ( BoldgridEditor.is_boldgrid_theme ) {
				this.menuDropDown.options.splice( 0, 1 );
			}

			BG.Controls.registerControl( this );
		},

		/**
		 * Delete the posts content.
		 *
		 * @since 1.6
		 */
		deleteContent() {
			BG.Controls.$container.find( 'body' ).html( '<p></p>' );
			BG.Controls.$container.validate_markup();
			IMHWPB.WP_MCE_Draggable.instance.add_tiny_mce_history();
		},

		/**
		 * Setup.
		 *
		 * @since 1.2.7
		 */
		setup: function() {
			BG.Menu.$element
				.find( '.bg-editor-menu-dropdown' )
				.on( 'click', '.action.open-color-palette', () => {
					BG.Controls.get( 'Palette' ).openPanel();
				} );
			BG.Menu.$element
				.find( '.bg-editor-menu-dropdown' )
				.on( 'click', '.action.delete-all-content,.action.edit-page-settings', () => {
					let response = confirm( 'Are you sure you want to clear this post\'s content?' );
					if ( response ) {
						this.deleteContent();
					}
				} );
		}
	};

	BOLDGRID.EDITOR.CONTROLS.Global.init();
	self = BOLDGRID.EDITOR.CONTROLS.Global;
} )( jQuery );
