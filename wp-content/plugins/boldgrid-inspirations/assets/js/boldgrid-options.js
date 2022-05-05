/**
 *  Check for checked on acknowledgement checkbox, so the user knows
 *  that they will be deleting content from their BoldGrid install.
 *
 *  @since .21
 */
jQuery( document ).ready( function( $ ) {
	var $el =
		'#boldgrid_delete_forms, #boldgrid_delete_themes, #delete_pages, [name=\'start_over_active\'], [name=\'start_over_staging\']'; // Target these elements

	var $submit_button = $( '#start_over_button' );

	$( '#start_over' ).change( function( e ) {

		// Setup State Machine
		if ( ! e.currentTarget.checked ) {

			// If agree to start over gets deselected after user selects
			$( '#boldgrid-alert-remove' ).fadeOut(); // Then hide the alert warning.
			$( $el ).attr( 'disabled', 'disabled' ); // Change our elements to be disabled,
			$( $el ).attr( 'readonly', 'true' ); // and don't forget to make them readonly.

			$submit_button.attr( 'disabled', 'disabled' );
		} else {

			// Otherwise when agreement is checked
			$( '#boldgrid-alert-remove' ).fadeIn(); // Show another warning to make user aware.
			$( $el ).removeAttr( 'disabled' ); // Enable the additional options,
			$( $el ).removeAttr( 'readonly' ); // and make the elements able to submit value to form.

			$submit_button.removeAttr( 'disabled' );
		}
	} );
	$( '#start_over' ).trigger( 'change' );
} );
