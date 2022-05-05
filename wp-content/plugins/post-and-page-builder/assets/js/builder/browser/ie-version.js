/**
 * Get IE Version.
 *
 * Thanks To: http://stackoverflow.com/questions/19999388/check-if-user-is-using-ie-with-jquery.
 */
export default function() {
	var trident,
		edge,
		msie,
		rv,
		ua = window.navigator.userAgent;

	msie = ua.indexOf( 'MSIE ' );
	if ( 0 < msie ) {

		// IE 10 or older => return version number.
		return parseInt( ua.substring( msie + 5, ua.indexOf( '.', msie ) ), 10 );
	}

	trident = ua.indexOf( 'Trident/' );
	if ( 0 < trident ) {

		// IE 11 => return version number.
		rv = ua.indexOf( 'rv:' );
		return parseInt( ua.substring( rv + 3, ua.indexOf( '.', rv ) ), 10 );
	}

	edge = ua.indexOf( 'Edge/' );
	if ( 0 < edge ) {

		// Edge (IE 12+) => return version number.
		return parseInt( ua.substring( edge + 5, ua.indexOf( '.', edge ) ), 10 );
	}

	// other browser
	return;
}
