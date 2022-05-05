/**
 * BoldGrid Library UI/UX.
 *
 * @summary JavaScript to handle UI/UX.
 *
 * @since 1.1.7
 */

/* global jQuery */

var BOLDGRID = BOLDGRID || {};
BOLDGRID.LIBRARY = BOLDGRID.LIBRARY || {};

BOLDGRID.LIBRARY.Ui = function( $ ) {
	var self = this,
		$sections,
		$sectionLinks;

	/**
	 * @summary Action to take when a user clicks on a navigation item.
	 *
	 * @since 1.1.7
	 */
	self.onClickSectionLink = function() {
		var $link = $( this ),
			sectionId = '#' + $link.attr( 'data-section-id' );

		$sectionLinks.removeClass( 'active' );
		$link.addClass( 'active' );

		$sections.hide();
		$( sectionId ).show();
	};

	/**
	 * @summary Determine stickiness of items.
	 *
	 * @since 1.1.7
	 */
	self.setSticky = function() {
		var width = document.body.clientWidth,
			$leftNav = $( '.bg-left-nav' );

		if ( 782 <= width ) {
			$leftNav.sticky( { topSpacing: 33 } );
		} else {
			$leftNav.unstick();
		}
	};

	/**
	 * @summary Take action when a form is submitted.
	 *
	 * @since 1.7.0
	 */
	self.onFormSubmit = function() {
		var $form = $( this ),
			activeSection,
			sectionInput = $( '<input type="hidden" name="section" />' );

		// If the form does not already have a section, add it.
		if ( ! $form.find( '[name="section"]' ).length ) {
			activeSection = $( '.bg-left-nav .active' ).attr( 'data-section-id' );
			sectionInput.val( activeSection );
			$form.append( sectionInput );
		}
	};

	/**
	 * @summary Init.
	 *
	 * @since 1.1.7
	 */
	$( function() {
		$( '.bg-left-nav' ).removeClass( 'bg-before-ready' );

		$sections = $( '.col-right-section' );
		$sectionLinks = $( '[data-section-id]' );

		$sectionLinks.on( 'click', self.onClickSectionLink );

		self.setSticky();

		$( window ).resize( self.setSticky );

		$( 'form' ).on( 'submit', self.onFormSubmit );
	} );
};

BOLDGRID.LIBRARY.Ui( jQuery );
