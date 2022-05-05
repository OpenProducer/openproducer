window.BOLDGRID = window.BOLDGRID || {};
BOLDGRID.EDITOR = BOLDGRID.EDITOR || {};
BOLDGRID.EDITOR.CONTROLS = BOLDGRID.EDITOR.CONTROLS || {};
BOLDGRID.EDITOR.CONTROLS.MEDIA = BOLDGRID.EDITOR.CONTROLS.MEDIA || {};

( function() {
	'use strict';

	var self,
		BG = BOLDGRID.EDITOR;

	BOLDGRID.EDITOR.CONTROLS.MEDIA.Edit = {
		name: 'edit-media',

		tooltip: 'Edit',

		priority: 85,

		iconClasses: 'dashicons dashicons-edit',

		selectors: [ '[data-wpview-type="gallery"]', '[data-wpview-type="ninja_forms"]' ],

		init: function() {
			BG.Controls.registerControl( this );
		},

		/**
		 * Open the media modal for edit gallery & form.
		 *
		 * @since 1.2.9
		 */
		openModal: function() {
			var target = BG.Menu.getTarget( self ).get( 0 );

			if ( target ) {
				wp.mce.views.edit( BOLDGRID.EDITOR.mce, target );
			}
		},

		onMenuClick: function() {
			self.openModal();
		}
	};

	BOLDGRID.EDITOR.CONTROLS.MEDIA.Edit.init();
	self = BOLDGRID.EDITOR.CONTROLS.MEDIA.Edit;
} )( jQuery );
