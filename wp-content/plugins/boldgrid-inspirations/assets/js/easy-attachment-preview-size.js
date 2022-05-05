var IMHWPB = IMHWPB || {};

IMHWPB.EasyAttachmentPreviewSize = function() {
	var self = this;

	/**
	 * ************************************************************************
	 * On dom load.
	 * ************************************************************************
	 */
	jQuery( function() {
		self.baseAdmin = new IMHWPB.BaseAdmin();

		/**
		 * When the <select> is clicked:
		 */
		jQuery( document.body ).on( 'mousedown', 'select.size', function() {
			var $select = jQuery( this );
			var option_count = parseInt( $select.children( 'option' ).length );
			var size = parseInt( $select.attr( 'size' ) );

			if ( 1 === size || isNaN( size ) ) {
				$select.attr( 'size', option_count );
				$select.height( 'auto' );
				$select.addClass( 'easy-attachment-preview-size' );
			}
		} );

		/**
		 * When an <option> is selected:
		 */
		jQuery( document.body ).on( 'click', 'select.size option', function() {
			jQuery( this )
				.parent()
				.attr( 'size', 1 );

			self.hide_preview();
		} );

		/**
		 * When the <select> loses focus:
		 */
		jQuery( document.body ).on( 'focusout', 'select.size', function() {
			jQuery( this ).attr( 'size', 1 );

			self.hide_preview();
		} );

		/**
		 * When an <option> is hovered:
		 */
		jQuery( document ).on( 'mouseenter', 'select.size option', function( e ) {
			self.show_preview( e );
		} );
	} );

	/**
	 * ************************************************************************
	 * Function delcarations.
	 * ************************************************************************
	 */

	/**
	 * Get dimensions from a string like this:
	 *
	 * Full Size – 1600 × 1067
	 */
	self.get_dimensions = function( text ) {

		// Example of 'text': "Full Size – 1600 × 1067"
		var initial_split = text.split( ' × ' );

		// Validate our data up to this point.
		if ( 2 != initial_split.length ) {
			return false;
		}

		// Example of 'split_left': "Full Size – 1600 "
		var split_left = initial_split[0].trim();

		// Example of 'split_right': " 1067";
		var split_right = initial_split[1].trim();

		// Get the width:
		var left_split = split_left.split( ' ' );
		var width = parseInt( left_split[left_split.length - 1] );

		var right_split = split_right.split( ' ' );
		var height = parseInt( right_split[0] );

		// Validate our data up to this point.
		// Make sure we're working with numbers and their size isn't too large,
		// like 10,000px.
		var validWidth = 1 < width && 10000 > width;
		var validHeight = 1 < height && 10000 > height;

		if ( validWidth && validHeight ) {
			return {
				width: width,
				height: height
			};
		} else {
			return false;
		}
	};

	/**
	 *
	 */
	self.hide_preview = function() {
		$preview = jQuery( 'div#easy_attachment_preview_size' );
		$preview.addClass( 'hidden' );

		jQuery( '.easy-attachment-preview-size-hidden' ).removeClass(
			'easy-attachment-preview-size-hidden'
		);
	};

	/**
	 *
	 */
	self.show_preview = function( e ) {

		// Get the dimensions of the attachment.
		var $target = jQuery( e.target );
		var innerHTML = $target.text();
		var dimensions = self.get_dimensions( innerHTML );

		// If we don't have valid dimensions, abort.
		if ( ! dimensions ) {
			return false;
		}

		// Add the preview, after the <select>, if it doesn't exist yet.
		if ( ! jQuery( 'div#easy_attachment_preview_size' ).length ) {
			jQuery( '<div id="easy_attachment_preview_size"></div>' ).insertAfter(
				'select.easy-attachment-preview-size'
			);
		}

		// z-indexing use to be easy... Hide a few things that overlap when we
		// don't want them to.
		jQuery(
			'.media-toolbar select, a.media-menu-item,.media-modal-content .media-frame .media-frame-menu,#media-search-input,.media-toolbar'
		).addClass( 'easy-attachment-preview-size-hidden' );

		// Show the preview.
		$preview = jQuery( 'div#easy_attachment_preview_size' );

		// Reset the Preview.
		$preview
			.removeClass( 'easy_attachment_preview_size_100' )
			.removeClass( 'easy_attachment_preview_size_iframed' );

		// Adust the dimensions of the preview font size.
		$preview
			.width( dimensions.width )
			.height( dimensions.height )
			.removeClass( 'hidden' )
			.html( '<em>Image size preview</em>:<br /><br />' + innerHTML );

		// Adjust the positioning if need be.
		if ( 'undefined' != typeof self.baseAdmin.GetURLParameter( 'ref' ) ) {
			$preview.addClass( 'easy_attachment_preview_size_iframed' );
		}

		// Adjust the preview text size if necessary
		if ( 100 > dimensions.width || 100 > dimensions.height ) {
			$preview.addClass( 'easy_attachment_preview_size_100' );
		}
	};
};

new IMHWPB.EasyAttachmentPreviewSize();
