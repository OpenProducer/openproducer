var IMHWPB = IMHWPB || {};

// Stash original_selections for use in other scripts.
IMHWPB.original_selections = IMHWPB.original_selections || [];

IMHWPB.PagenowPostnew = function( $ ) {
	var self = this;

	$( function() {

		// Store the selector context.
		var $selector = $( '.boldgrid-auto-add-to-menu' ),

			// The selected development group.
			selected_dev_group =
				'staging' == $( '#development_group_post_status:checked' ).val() ? 'staging' : 'publish';

		// Save the development group on page load.
		IMHWPB.loaded_dev_group =
			'staging' == $( '#development_group_post_status:checked' ).val() ? 'staging' : 'publish';

		// Handle the click of "Publish box > Add page to menu > Settings".
		$selector.find( '.edit-boldgrid-auto-add-to-menu' ).on( 'click', function() {

			// Show the list of menus.
			$selector.find( '#boldgrid-auto-add-to-menu-menu-listing' ).slideToggle();

			// Hide the 'Settings' link:
			$selector.find( '.edit-boldgrid-auto-add-to-menu' ).addClass( 'hidden' );

			return false;
		} );

		// Handle the click of "Publish box > Add page to menu > Cancel".
		$selector.find( '.button-cancel' ).on( 'click', function() {

			// Reset the selected menu names.
			self.resetMenuSelections();

			return false;
		} );

		// Handle the click of "Publish box > Add page to menu > OK".
		$selector.find( '.hide-boldgrid-auto-add-to-menu' ).on( 'click', function() {

			// Set the selected menu names.
			self.setSelectedMenuNames();

			// Hide the list of menus.
			$selector.find( '#boldgrid-auto-add-to-menu-menu-listing' ).slideToggle();

			// Show the 'Settings' link.
			$selector.find( '.edit-boldgrid-auto-add-to-menu' ).removeClass( 'hidden' );

			// Set the original selections for the development group.
			if ( 'publish' == selected_dev_group ) {

				// Active site.
				// Reset IMHWPB.original_selections_active.
				IMHWPB.original_selections_active = [];

				// Get the checked checkbox menu names from the document.
				$selector.find( 'input:checkbox:checked' ).each( function() {
					IMHWPB.original_selections_active.push( $( this ).attr( 'data-menu-name' ) );
				} );
			} else {

				// Staging site.
				// Reset IMHWPB.original_selections_staging.
				IMHWPB.original_selections_staging = [];

				// Get the checked checkbox menu names from the document.
				$selector.find( 'input:checkbox:checked' ).each( function() {
					IMHWPB.original_selections_staging.push( $( this ).attr( 'data-menu-name' ) );
				} );
			}

			return false;
		} );

		// Set the default value of "#selected-menu-names", which is a listing
		// of current menu names this page is assigned to.
		self.setSelectedMenuNames();
	} );

	/**
	 * Set selected menu names for summary display.
	 */
	self.setSelectedMenuNames = function() {

		// Initialize selected_menu_names and store the selector context.
		var selected_menu_names = [],

			// Store the selector context.
			$selector = $( '.boldgrid-auto-add-to-menu' );

		// Get the checked checkbox menu names from the document.
		$selector.find( 'input:checkbox:checked' ).each( function() {
			selected_menu_names.push( $( this ).attr( 'data-menu-name' ) );
		} );

		if ( 0 === selected_menu_names.length ) {
			selected_menu_names = 'None';
		} else {
			selected_menu_names = selected_menu_names.join( ', ' );
		}

		$selector.find( '#selected-menu-names' ).html( selected_menu_names );
	};

	/**
	 * Reset menu name selections back to the original values.
	 *
	 * @since 1.0.11
	 */
	self.resetMenuSelections = function() {

		// Store the selector context.
		var $selector = $( '#boldgrid-auto-add-to-menu-menu-listing' ),

			// Switch for which selections to restore.
			using_original_selections;

		// The selected development group.
		selected_dev_group =
			'staging' == $( '#development_group_post_status:checked' ).val() ? 'staging' : 'publish';

		// Get the checkbox menu names from the document.
		$selector.find( 'input:checkbox:checked' ).each( function() {

			// Uncheck the checkbox.
			$( this ).prop( 'checked', false );
		} );

		// Reset selections based on development group.
		if ( selected_dev_group == IMHWPB.loaded_dev_group ) {

			// Reset to the original loaded selections.
			using_original_selections = IMHWPB.original_selections;
		} else if (
			'staging' == selected_dev_group &&
			'undefined' != typeof IMHWPB.original_selections_staging
		) {

			// Staging site.
			using_original_selections = IMHWPB.original_selections_staging;
		} else {

			// Active site.
			if ( 'undefined' != typeof IMHWPB.original_selections_active ) {
				using_original_selections = IMHWPB.original_selections_active;
			} else {

				// Default to the selections on page load.
				using_original_selections = IMHWPB.original_selections;
			}
		}

		// Reset selections to the original values.
		using_original_selections.forEach( function( menu_name ) {
			$( '[data-menu-name=\'' + menu_name + '\']' ).prop( 'checked', true );
		} );

		// Set the selected menu names for summary display.
		self.setSelectedMenuNames();
	};
};

new IMHWPB.PagenowPostnew( jQuery );
