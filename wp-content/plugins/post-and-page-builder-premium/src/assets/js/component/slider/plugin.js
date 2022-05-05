import 'slick-carousel';
import { EventEmitter } from 'eventemitter3';

let $ = jQuery;

export class Plugin {
	constructor() {
		this.defaults = {
			arrows: true,
			autoplay: false,
			autoplaySpeed: 10,
			adaptiveHeight: true,
			dots: false,
			swipe: true,
			infinite: true,
			colors: {
				dotsColor: {
					type: 'color',
					value: '#000000'
				},
				arrowsBG: {
					type: 'color',
					value: '#1a1a1a',
					text: '#FFFFFF'
				}
			},
			bgOptions: {
				arrowsPos: 'standard',
				arrowsSize: '30',
				arrowsDesign: 'square',
				arrowsOverlay: true,
				arrowsIcon: 'angle-double',
				arrowsBgColor: '#1a1a1a',
				dotsPos: 'bottom',
				dotsSize: '50',
				dotsOverlay: false,
				dotsColor: '#000000'
			}
		};

		this.settings = [
			{
				name: 'arrowsBG',
				selector: '.slick-arrow',
				type: 'background-color'
			},
			{
				name: 'dotsColor',
				selector: '.slick-dots',
				type: 'color'
			}
		];

		this.event = new EventEmitter();
	}

	isEditor() {
		return 'undefined' !== typeof BoldgridEditor;
	}

	getArrowHtml( direction, icon ) {
		let slickClass = 'left' === direction ? 'prev' : 'next';
		return `<button type="button" class="slick-${slickClass} slick-arrow">
			<span class="fa fa-${icon}-${direction}" aria-hidden="true"></span></button>`;
	}

	/**
	 * Instantiate all sliders on the page.
	 *
	 * @since 1.0.0
	 */
	initPageSliders() {
		this._setupWowJs();
		this.initSlider( $( '.boldgrid-slider' ) );
	}

	/**
	 * Update the presentation of sliders, bound to resize events.
	 *
	 * @since 1.0.0
	 *
	 * @param  {jQuery} $context Context to look for sliders within.
	 */
	resizeSliders( $context ) {
		$context.find( '.boldgrid-slider' ).each( ( index, el ) => {
			let $slider = $( el );
			if ( el.slick ) {
				$slider.slick( 'setPosition' );
			} else {
				this.initSlider( $slider );
			}
		} );
	}

	/**
	 * Instantiate a slider.
	 *
	 * @since 1.0.0
	 *
	 * @param  {jQuery} $html HTML.
	 */
	initSlider( $html ) {
		$html.each( ( index, slider ) => {
			let $slider = $( slider ),
				config;

			if ( $slider.hasClass( 'slick-initialized' ) ) {
				this.revertHTML( $slider );
			}

			config = this.parseConfig( $slider );
			config.autoplaySpeed = config.autoplaySpeed ?
				parseInt( config.autoplaySpeed, 10 ) * 1000 :
				10000;
			config = { ...this.defaults, ...config };

			config.autoplay = this.isEditor() ? false : config.autoplay;
			config.swipe = this.isEditor() ? false : config.swipe;

			// Arrows.
			$slider.toggleClass( 'slick-arrow-overlay', config.bgOptions.arrowsOverlay );
			$slider.attr( 'arrow-location', config.arrows ? config.bgOptions.arrowsPos : '' );

			// Dots.
			$slider.toggleClass( 'slick-dots-overlay', config.bgOptions.dotsOverlay );
			$slider.attr( 'dots-location', config.dots ? config.bgOptions.dotsPos : '' );

			config.prevArrow = this.getArrowHtml( 'left', config.bgOptions.arrowsIcon );
			config.nextArrow = this.getArrowHtml( 'right', config.bgOptions.arrowsIcon );

			$slider.slick( config );

			$slider.find( '.slick-arrow' ).css( 'font-size', `${config.bgOptions.arrowsSize}px` );
			$slider.find( '.slick-dots' ).css( 'font-size', `${config.bgOptions.dotsSize}px` );
			$slider.find( '.slick-dots button' ).empty();

			// When a slider is inserted a resize and insert event happen back to back, prevent double binding.
			$slider.off( 'afterChange.ppbp' );
			$slider.on( 'afterChange.ppbp', () => this.event.emit( 'slideChange', $slider ) );

			this._applyColors( $slider, config );
			this._calcMargin( $slider, config );
			this._calcPadding( $slider, config );
			this.event.emit( 'initialized', $slider );
		} );
	}

	/**
	 * Get configurations from a slider.
	 *
	 * @since 1.0.0
	 *
	 * @param  {jQuery} $slider Slider Object.
	 * @return {object}         Slider Values.
	 */
	parseConfig( $slider ) {
		return JSON.parse( $slider.attr( 'data-config' ) || '{}' ) || this.defaults;
	}

	/**
	 * Given a slider jQuery element. Revert to the element to the base HTML.
	 *
	 * @since 1.0.0
	 *
	 * @param  {jQuery} $slider Slider.
	 */
	revertHTML( $slider ) {
		let html = '',
			className = this.getSlideClass( $slider );

		if ( $slider.hasClass( 'slick-initialized' ) ) {
			$slider.find( `.slick-slide:not(.slick-cloned) .${className}` ).each( ( index, el ) => {
				html += el.outerHTML;
			} );

			$slider.html( html );
		}

		$slider.removeAttr( 'arrow-location' ).removeAttr( 'dots-location' );
		$slider.removeClass( 'slick-initialized slick-slider slick-arrow-overlay slick-dotted' );
	}

	/**
	 * Get the current slider element class.
	 *
	 * @since 1.0.0
	 *
	 * @param  {jQuery} $slider Slider element.
	 * @return {string}         Slider class.
	 */
	getSlideClass( $slider ) {
		return $slider.hasClass( 'boldgrid-wrap-row' ) ? 'row' : 'boldgrid-section';
	}

	/**
	 * When a slide changess, reset wow js.
	 *
	 * @since 1.0.0
	 */
	_setupWowJs() {
		if ( ! BOLDGRID.PPB || ! BOLDGRID.PPB.wowJs ) {
			return;
		}

		/*
		 * @Todo: If an out of focus slide is auto played, it will be flagged as animated.
		 * and when it comes back around to play, it wont start
		 */

		// On init, set all current animations to animated = true.
		this.event.on( 'initialized', $slider => {
			$slider.find( '.slick-current .wow' ).each( ( index, el ) => {
				el.animated = true;
			} );
		} );

		// On slide change reanimmate.
		this.event.on( 'slideChange', $slider => {
			$slider.find( '.slick-current .wow' ).each( ( index, el ) => {
				if ( ! el.animated && BOLDGRID.PPB.wowJs.isVisible( el ) ) {
					el.animated = true;
					$slider
						.find( '.wow' )
						.css( { visibility: '', 'animation-name': 'none' } )
						.removeClass( 'animated' );

					BOLDGRID.PPB.wowJs.boxes.push( el );
					BOLDGRID.PPB.wowJs.scrolled = true;
					BOLDGRID.PPB.wowJs.scrollCallback();
				}
			} );
		} );
	}

	/**
	 * Set the color from configs on all.
	 *
	 * @since 1.0.0
	 *
	 * @param  {jQuery} $slider Slider element.
	 * @param  {object} config  Configs for slider.
	 */
	_applyColors( $slider, config ) {
		if ( ! config.colors ) {
			return;
		}

		for ( let control of this.settings ) {
			if ( config.colors[control.name] ) {
				let $element = $slider.find( control.selector );

				if ( 'background-color' === control.type ) {
					this._applyBGColor( config.colors[control.name], $element );
				} else {
					this._applyColor( config.colors[control.name], control.type, $element );
				}
			}
		}
	}

	/**
	 * Apply the background color.
	 *
	 * @since 1.0.0
	 *
	 * @param {jQuery} $element
	 */
	_applyBGColor( colors, $element ) {
		if ( 'class' === colors.type ) {
			$element.addClass(
				`color${colors.value}-background-color color-${colors.value}-text-contrast`
			);
		} else {
			$element.css( {
				'background-color': colors.value,
				color: colors.text
			} );
		}
	}

	/**
	 * Add the color.
	 *
	 * @since 1.0.0
	 *
	 * @param  {object} colors  Color Configs.
	 * @param  {string} cssProp Property.
	 */
	_applyColor( colors, cssProp, $element ) {
		if ( 'class' === colors.type ) {
			$element.addClass( `color${colors.value}-${cssProp}` );
		} else {
			$element.css( cssProp, colors.value );
		}
	}

	/**
	 * Add padding to the containers within the slider to prevent the arrows
	 * from coliding with the content.
	 *
	 * @since 1.0.0
	 *
	 * @param  {$} $slider     Slider Object.
	 * @param  {object} config Slider Configuration.
	 */
	_calcPadding( $slider, config ) {
		let $containers = $slider.find( '.container, .container-fluid' ),
			css = { paddingLeft: '', paddingRight: '' },

			// Add 1.2 times the widgt for padding.
			padding = 1.2;

		if ( config.arrows && config.bgOptions.arrowsOverlay ) {
			if ( -1 !== [ 'standard', 'top', 'bottom' ].indexOf( config.bgOptions.arrowsPos ) ) {
				let $arrow = $slider.slick( 'getSlick' ).$nextArrow,
					width = $arrow.outerWidth() * padding;

				css = { paddingLeft: width, paddingRight: width };
			}
		}

		$containers.css( css );
	}

	/**
	 * Calc and set the margin based on the nav button position and size.
	 *
	 * @since 1.0.0
	 *
	 * @param {jQuery} $slider Slider Selection.
	 * @param {object} config   Configuration.
	 */
	_calcMargin( $slider, config ) {
		$slider.css( 'margin-top', '' );
		$slider.css( 'margin-bottom', '' );

		if ( config.arrows && ! config.bgOptions.arrowsOverlay ) {
			let arrowPos = config.bgOptions.arrowsPos,
				padding = 20,
				margin;

			margin = parseInt( config.bgOptions.arrowsSize, 10 ) + padding;

			if ( 0 === arrowPos.indexOf( 'bottom' ) ) {
				$slider.css( 'margin-bottom', `${margin}px` );
			} else if ( 0 === arrowPos.indexOf( 'top' ) ) {
				$slider.css( 'margin-top', `${margin}px` );
			}
		}
	}
}
