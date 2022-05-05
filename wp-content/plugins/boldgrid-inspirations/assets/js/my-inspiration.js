// When the user clicks one of the support boxes, navigate to the url of that box's button.
jQuery( '.support-boxes li' ).on( 'click', function() {
	window.open(
		jQuery( this )
			.find( 'a' )
			.attr( 'href' ),
		'_blank'
	);
	return false;
} );

/*
 * Add the active class to "Inspirations" in the left nav.
 *
 * Even though "My Inspiration" does not have a menu item, we still want "Inspirations" to look active.
 */
jQuery( 'li.toplevel_page_boldgrid-inspirations' ).addClass( 'current' );
