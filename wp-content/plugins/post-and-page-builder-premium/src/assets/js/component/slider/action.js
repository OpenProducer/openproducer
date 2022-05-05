let $ = jQuery,
	BG = BOLDGRID.EDITOR;

export class Action {

	/**
	 * Init the actions.
	 *
	 * @since 1.0.0
	 *
	 * @param {object} control The control element.
	 * @param {jQuery} $target Current Slider.
	 */
	constructor( control, $target ) {
		this.control = control;
		this.$target = $target;
	}

	/**
	 * Setup the change event.
	 *
	 * @since 1.0.0
	 */
	bind() {
		this.control.carousel.event
			.on( 'action-copy', () => this.copySlide() )
			.on( 'action-delete', () => this.deleteSlide() )
			.on( 'action-forward', () => this.moveForward() )
			.on( 'action-back', () => this.moveBack() )
			.on( 'action-previous', () => this.showPrevious() )
			.on( 'action-next', () => this.showNext() );
	}

	/**
	 * Does this slider have multiple slides.
	 *
	 * @since 1.0.0
	 *
	 * @return {Boolean} Whether or not there are multiple slides.
	 */
	hasMultipleSlides() {
		return !! this.$target.find( '.slick-slide:not(.slick-current):not(.slick-cloned)' ).length;
	}

	/**
	 * Find the current slide.
	 *
	 * @since 1.0.0
	 *
	 * @return {jQuery} Slide Element.
	 */
	getCurrentSlide() {
		return this.$target.find( '.slick-current' );
	}

	/**
	 * Copy's the current slide.
	 *
	 * @since 1.0.0
	 *
	 */
	copySlide() {
		let $current = this.getCurrentSlide().find( '> div' ),
			newIndex = this.$target.slick( 'slickCurrentSlide' ) + 1;

		$current.after( $current.html() );
		this.control.sliderPlugin.initSlider( this.$target );
		this.$target.slick( 'slickGoTo', newIndex, true );
	}

	/**
	 * Show the next slide.
	 *
	 * @since 1.0.0
	 */
	showNext() {
		this.$target.slick( 'slickNext' );
		this.$target.click();
	}

	/**
	 * Show the next slide.
	 *
	 * @since 1.0.0
	 */
	showPrevious() {
		this.$target.slick( 'slickPrev' );
		this.$target.click();
	}

	/**
	 * Shift a slides location by 1 forward.
	 *
	 * @since 1.0.0
	 */
	moveForward() {
		this._slideShift( 1 );
	}

	/**
	 * Shift a slides location by 1 backward.
	 *
	 * @since 1.0.0
	 */
	moveBack() {
		this._slideShift( -1 );
	}

	/**
	 * Delete a slide.
	 *
	 * @since 1.0.0
	 */
	deleteSlide() {
		if ( this.hasMultipleSlides() ) {
			this.getCurrentSlide().remove();
			this.control.sliderPlugin.initSlider( this.$target );
		}
	}

	/**
	 * Shift a slide in a given direction by X paces.
	 *
	 * @since 1.0.0
	 *
	 * @param  {integer} shiftCount How many places to shift.
	 */
	_slideShift( shiftCount ) {
		if ( this.hasMultipleSlides() ) {
			let action,
				$current = this.getCurrentSlide(),
				newIndex = $current.data( 'slick-index' ) + shiftCount,
				finalIndex = this.$target.find( '.slick-slide:not(.slick-cloned)' ).length - 1;

			if ( newIndex > finalIndex ) {
				newIndex = 0;
			}

			if ( 0 > newIndex ) {
				newIndex = finalIndex;
			}

			if ( 0 > shiftCount ) {
				action = finalIndex === newIndex ? 'append' : 'prepend';
			} else {
				action = 0 === newIndex ? 'prepend' : 'append';
			}

			this.$target
				.find( `.slick-slide[data-slick-index="${newIndex}"]` )
				[action]( $current.find( '> div' ) );
			this.control.sliderPlugin.initSlider( this.$target );
			this.$target.slick( 'slickGoTo', newIndex, true );
		}
	}
}
