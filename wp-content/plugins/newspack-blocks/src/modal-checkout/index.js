/**
 * Style dependencies
 */
import './checkout.scss';

/**
 * Specify a function to execute when the DOM is fully loaded.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/packages/dom-ready/
 *
 * @param {Function} callback A function to execute after the DOM is ready.
 * @return {void}
 */
function domReady( callback ) {
	if ( typeof document === 'undefined' ) {
		return;
	}
	if (
		document.readyState === 'complete' || // DOMContentLoaded + Images/Styles/etc loaded, so we call directly.
		document.readyState === 'interactive' // DOMContentLoaded fires at this point, so we call directly.
	) {
		return void callback();
	}
	// DOMContentLoaded has not fired yet, delay callback until then.
	document.addEventListener( 'DOMContentLoaded', callback );
}

domReady( () => {
	const continueButton = document.querySelector( '.modal-continue' );
	if ( continueButton ) {
		continueButton.removeAttribute( 'disabled' );
		continueButton.addEventListener( 'click', () => {
			const form = document.querySelector( 'form.checkout' );
			form.submit();
		} );
	}
} );
