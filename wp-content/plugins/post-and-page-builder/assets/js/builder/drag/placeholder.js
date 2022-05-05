export class Placeholder {

	/**
	 * Setup needed values.
	 *
	 * 2 Elements are needed here because the drag process uses a cloned element during the drag process.
	 * The iframes of the cloned elements are not yet rendered, therefore the heights are 0. Passing
	 * the original element in to get the heights of the iframes.
	 *
	 * @since 1.7.0
	 *
	 * @param  {$} $dragged Element the user started dragging.
	 * @param  {$} $clone Cloned Element.
	 */
	constructor( $dragged, $clone ) {
		this.iframesReplaced = [];

		this.$renderedIframes = [];
		this.$clone = $clone;
		this.$dragged = $dragged;
	}

	/**
	 * Replaces all iframes with placeholders while dragging.
	 *
	 * @since 1.7.0
	 */
	setContent() {
		this.$renderedIframes = this.$dragged.find( 'iframe' );

		this.$clone.find( 'iframe' ).each( ( index, el ) => {
			let $iframe = $( el ),
				$placeHolder = this.createPlaceholder( this.$renderedIframes.eq( index ) );

			this.iframesReplaced.push( {
				$element: $iframe,
				$placholder: $placeHolder
			} );

			$iframe.replaceWith( $placeHolder );
		} );
	}

	/**
	 * Replace the placeholder items with the iframes again.
	 *
	 * @since 1.7.0
	 */
	revertContent() {
		for ( let iframe of this.iframesReplaced ) {
			iframe.$placholder.replaceWith( iframe.$element );
		}
	}

	/**
	 * Create a placeholder for iframes while dragging.
	 *
	 * @since 1.7.0
	 *
	 * @param  {$} $iframe An Iframe element.
	 * @return {$}         Placeholder element.
	 */
	createPlaceholder( $iframe ) {
		let $placeHolder = $( this.getPlaceholderHtml() ),
			height = $iframe.height() || $iframe[0].height,
			width = $iframe.width() || $iframe[0].width;

		$placeHolder.css( {
			height: height,
			width: width
		} );

		return $placeHolder;
	}

	/**
	 * Get placeholder markup.
	 *
	 * @since 1.7.0
	 *
	 * @return {string} HTML.
	 */
	getPlaceholderHtml() {
		return `
			<div class="media-placeholder">
				<div>
					<div class="dashicons dashicons-admin-media"></div>
					<div>Media</div>
				</div>
			</div>
		`;
	}
}
