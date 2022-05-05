window.BOLDGRID = window.BOLDGRID || {};
BOLDGRID.EDITOR = BOLDGRID.EDITOR || {};
BOLDGRID.EDITOR.CONTROLS = BOLDGRID.EDITOR.CONTROLS || {};
BOLDGRID.EDITOR.CONTROLS.IMAGE = BOLDGRID.EDITOR.CONTROLS.IMAGE || {};

( function() {
	'use strict';

	var self,
		BG = BOLDGRID.EDITOR;

	BOLDGRID.EDITOR.CONTROLS.IMAGE.Change = {
		name: 'image-change',

		tooltip: 'Change Image',

		priority: 85,

		iconClasses: 'genericon genericon-image',

		selectors: [ 'img' ],

		init: function() {
			BG.Controls.registerControl( this );
		},

		/**
		 * Open the media modal for replace image.
		 *
		 * @since 1.2.8
		 */
		openModal: function() {
			var node = BOLDGRID.EDITOR.mce.selection.getNode();

			/*
			 * Ensure the selected element is an image.
			 * @todo: This is a temporary fix.
			 */
			if (
				node &&
				'IMG' !== node.nodeName &&
				1 === node.childElementCount &&
				'IMG' === node.firstChild.nodeName
			) {
				BOLDGRID.EDITOR.mce.selection.select( node.firstChild );
			}

			// Mimic the click of the "Edit" button.
			BOLDGRID.EDITOR.mce.buttons.wp_img_edit.onclick();

			// Change the media modal to "Replace Image".
			wp.media.frame.setState( 'replace-image' );

			// When the image is replaced, run crop.onReplace().
			wp.media.frame.state( 'replace-image' ).on( 'replace', function( imageData ) {
				BG.CropInstance.onReplace( imageData );
			} );
		},

		onMenuClick: function() {
			self.openModal();
		}
	};

	BOLDGRID.EDITOR.CONTROLS.IMAGE.Change.init();
	self = BOLDGRID.EDITOR.CONTROLS.IMAGE.Change;
} )( jQuery );
