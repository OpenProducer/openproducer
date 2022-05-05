var $ = window.jQuery,
	BG = BOLDGRID.EDITOR;

export class Loading {
	constructor() {
		this.failsafeTimeout = 3000;
		this.$element = $( '.bg-editor-loading-main' );

		return this;
	}

	/**
	 * Initialize the class.
	 *
	 * @since 1.6
	 *
	 * @return {Loading} Instance.
	 */
	init() {
		this._setupFailsafe();

		return this;
	}

	/**
	 * Hide the loading graphic
	 *
	 * @since 1.6
	 */
	hide() {
		this.$element.removeClass( 'active' );

		// After animation is complete remove loading elements.
		setTimeout( () => {
			this.$element.addClass( 'disabled' );
		}, 500 );
	}

	/**
	 * Display the loading graphic.
	 *
	 * @since 1.6
	 */
	show() {
		this.$element.removeClass( 'disabled' );

		// Set timeout is needed because removing the disabled class allows pseudo elements to appear, takes time.
		setTimeout( () => {
			this.$element.addClass( 'active' );
		} );
	}

	/**
	 * Hide the loading graphic after timeout just incase a failure occurs.
	 *
	 * @since 1.6
	 */
	_setupFailsafe() {
		setTimeout( () => {
			this.hide();
		}, this.failsafeTimeout );
	}
}

export { Loading as default };
