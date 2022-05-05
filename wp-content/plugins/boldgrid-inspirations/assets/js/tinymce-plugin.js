function boldgrid_mce_image_left() {
	var left_image =
		'<img class=\'alignleft\' src=\'http://placehold.it/250x250\' alt=\'\' />' +
		'<p>This is a paragraph with an image aligned to the left. Replace this text with your own text. It\'s easy! This is a paragraph with an image aligned to the left. Replace this text with your own text. It\'s easy! This is a paragraph with an image aligned to the left. Replace this text with your own text. It\'s easy! This is a paragraph with an image aligned to the left. Replace this text with your own text. It\'s easy!<br /><br />This is a paragraph with an image aligned to the left. Replace this text with your own text. It\'s easy!</p>';
	return left_image;
}

tinymce.create( 'tinymce.plugins.imhwpb', {

	/**
	 * Initializes the plugin, this will be executed after the plugin has been
	 * created. This call is done before the editor instance has finished it's
	 * initialization so use the onInit event of the editor instance to
	 * intercept that event.
	 *
	 * @param {tinymce.Editor}
	 *            ed Editor instance that the plugin is initialized in.
	 * @param {string}
	 *            url Absolute URL to where the plugin is located.
	 */
	init: function( ed, url ) {
		ed.addButton( 'example', {
			text: 'Z',
			icon: false,
			title: 'TITLE GOES HERE',

			// image : url + '/../images/icon-toggle.gif',
			onclick: function() {

				// CustomButtonClick('learn_more');
				myText = boldgrid_mce_image_left();
				ed.execCommand( 'mceInsertContent', false, myText );
			}
		} );
	},

	/**
	 * Creates control instances based in the incomming name. This method is
	 * normally not needed since the addButton method of the tinymce.Editor
	 * class is a more easy way of adding buttons but you sometimes need to
	 * create more complex controls like listboxes, split buttons etc then this
	 * method can be used to create those.
	 *
	 * @param {String}
	 *            n Name of the control to create.
	 * @param {tinymce.ControlManager}
	 *            cm Control manager to use inorder to create new control.
	 * @return {tinymce.ui.Control} New control instance or null if no control
	 *         was created.
	 */
	createControl: function( n, cm ) {
		return null;
	}
} );

tinymce.PluginManager.add( 'boldgrid_example', tinymce.plugins.imhwpb );
