var $ = jQuery,
	BGGB = BOLDGRID.EDITOR.GRIDBLOCK,
	BG = BOLDGRID.EDITOR;

/**
 * Used for previewing blocks.
 *
 * @since 1.7.0
 */
export class Preview {

	/**
	 * Define class defaults.
	 *
	 * @since 1.7.0
	 */
	constructor() {

		// Order matters, list sizes descending.
		this.scaleConfigs = [
			{ minSize: 1600, scale: 0.59 },
			{ minSize: 1359, scale: 0.5 },
			{ minSize: 0, scale: 0.45 }
		];

		// Create templates.
		this.placeholderHtml = {};
		this.placeholderHtml.before = wp.template( 'gridblock-redacted-before' )();
		this.placeholderHtml.after = wp.template( 'gridblock-redacted-after' )();
	}

	/**
	 * Initialize the class, binding all events.
	 *
	 * @since 1.7.0
	 */
	init() {
		this._bindResize();
	}

	/**
	 * Change height to match content.
	 *
	 * @since 1.7.0
	 *
	 * @param  {$} $iframe    iframe element.
	 * @param  {$} $gridblock groidblock UI element.
	 */
	adjustHeight( $iframe, $gridblock ) {
		let $contents = $iframe.contents(),
			scale = this._getScale(),

			// 400 offset for redacted placeholder.
			height = $contents.find( 'body > div' ).height() - 400,
			bodyHeight = height * scale;

		if ( 0 < height ) {
			$iframe.height( height );
			$gridblock.css( 'height', bodyHeight );
		}
	}

	/**
	 * Create the iframe content. Updated from content set html to allow js load events to fire.
	 *
	 * @since 1.7.0
	 *
	 * @param  {$} $iframe Iframe element.
	 * @param  {object} content Content for iframe.
	 */
	createIframe( $iframe, content ) {
		const iframeDocument = $iframe[0].contentWindow.document;

		iframeDocument.open();
		iframeDocument.write(
			`<!DOCTYPE html>
			<html>
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					${content.head}
				</head>
				<body>
					<div>
						${this.placeholderHtml.before}
						<span class="content-placeholder"></span>
						${content.body}
						${this.placeholderHtml.after}
					</div>
				</body>
			</html>`
		);

		iframeDocument.close();
	}

	/**
	 * Get the currently used scale based on screen size.
	 *
	 * @since 1.7.0
	 *
	 * @return {number} Scale used.
	 */
	_getScale() {

		// default scale.
		let scale = 0.59;

		for ( let config of this.scaleConfigs ) {
			if ( config.minSize <= window.innerWidth ) {
				scale = config.scale;
				break;
			}
		}

		return scale;
	}

	/**
	 * On resize, find all gridblocks and resize them.
	 *
	 * @since 1.7.0
	 */
	_bindResize() {
		let resizeCb = _.debounce( () => {
			if ( BG.CONTROLS.Section.sectionDragEnabled ) {
				BGGB.View.$gridblocks.addClass( 'resizing-gridblocks' );

				BGGB.View.$gridblockSection.find( '.gridblock:not(.gridblock-loading)' ).each( ( index, el ) => {
					let $gridblock = $( el );

					this.adjustHeight( $gridblock.find( 'iframe' ), $gridblock );
				} );

				BGGB.View.$gridblocks.removeClass( 'resizing-gridblocks' );
			}
		}, 1000 );

		$( window ).resize( resizeCb );
	}
}
