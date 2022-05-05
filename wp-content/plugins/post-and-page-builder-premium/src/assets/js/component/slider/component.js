import './style.scss';
import sampleSection from './sample-section.html';
import sampleRow from './sample-row.html';
import { Plugin as SliderPlugin } from './plugin';
import { Control } from './control';

let $ = jQuery,
	BG = BOLDGRID.EDITOR;

export class Component {
	constructor() {
		this.sectionConfig = {
			name: 'section-slider',
			title: 'Section Slider',
			type: 'design',
			icon: '<span class="dashicons dashicons-images-alt"></span>',
			insertType: 'insert',
			getDragElement: () => this.getSampleSection( sampleSection ),
			onClick: component => this.prepend( component ),
			priority: 90
		};

		this.rowConfig = {
			name: 'content-slider',
			title: 'Content Slider',
			type: 'design',
			icon: '<span class="dashicons dashicons-images-alt"></span>',
			getDragElement: () => this.getSampleRow( sampleRow ),
			onClick: component => this.prepend( component ),
			priority: 90
		};

		this.sliderPlugin = new SliderPlugin();
		this.control = new Control( this.sliderPlugin );
	}

	/**
	 * Get a sample slider.
	 *
	 * @since 1.0.0
	 *
	 * @param  {string} html HTMl.
	 * @return {jQuery}      Slider.
	 */
	getSampleSection( html ) {
		let $slider = $( html ),
			defaults = new SliderPlugin().defaults;

		$slider.attr( 'data-config', JSON.stringify( defaults ) );

		return $slider;
	}

	/**
	 * Get a sample row component.
	 *
	 * @since 1.0.0
	 *
	 * @param  {string} html HTML to insert.
	 * @param  {jQuery} $slider Sample Slider
	 */
	getSampleRow( html ) {
		let $slider = $( html ),
			defaults = new SliderPlugin().defaults;

		defaults.arrows = false;
		defaults.dots = true;

		$slider.attr( 'data-config', JSON.stringify( defaults ) );

		return $slider;
	}

	/**
	 * Open the customizer for a slider component.
	 *
	 * @since 1.0.0
	 *
	 * @param  {jQuery} $inserted Inserted component.
	 */
	openCustomization( $inserted ) {
		let control = BG.Controls.get( 'slider' );
		BG.Controls.$menu.targetData.slider = $inserted;
		$inserted.click();
		control.onMenuClick();
	}

	/**
	 * Initialize editor sliders.
	 *
	 * @since 1.0.0
	 */
	initSliders() {
		this.sliderPlugin.initSlider( BG.Controls.$container.find( '.boldgrid-slider' ) );
	}

	/**
	 * Find all sliders in the container and update their size.
	 *
	 * @since 1.0.0
	 */
	resizeAllSliders() {
		this.sliderPlugin.resizeSliders( BG.Controls.$container );
	}

	/**
	 * Init the class, binding events.
	 *
	 * @since 1.0.0
	 */
	init() {
		this._setupCustomization();

		BG.$window.on( 'boldgrid_editor_loaded', () => {
			this.initSliders();

			// On Undo and redo make sure sliders are reintialized.
			BG.mce.on( 'undo redo', () => {
				IMHWPB['tinymce_undo_disabled'] = true;
				this.initSliders();
				setTimeout( () => {
					IMHWPB['tinymce_undo_disabled'] = false;
				} );
			} );

			// User goes from text to visual.
			BG.mce.on( 'show', () => this.initSliders() );
			BG.mce.on( 'SetContent', this.resizeAllSliders() );
			BG.mce.on( 'Change', _.throttle( () => this.resizeAllSliders(), 100 ) );
		} );

		BG.Service.event.on( 'cleanup', $markup => {
			$markup.find( '.boldgrid-slider' ).each( ( index, el ) => {
				this.sliderPlugin.revertHTML( $( el ) );
			} );
		} );

		// Bind Service events.
		BG.Service.event
			.on( 'cloneSection', $markup => this.onClone( $markup ) )
			.on( 'cloneContent', $markup => this.onClone( $markup ) )
			.on( 'modifyContent', () => this.resizeAllSliders() )
			.on( 'endTyping', () => this.resizeAllSliders() )
			.on( 'mceResize', () => this.resizeAllSliders() )
			.on( 'rowResize', _.throttle( () => this.resizeAllSliders(), 100 ) )
			.on( 'dragDrop', $markup => this.onClone( $markup ) );

		// Within the add gridblocks screen, when a block is rendered, init all sliders.
		BG.Service.event.on( 'blockRendered', block => {
			this.sliderPlugin.initSlider( block.$iframeContents.find( '.boldgrid-slider' ) );
		} );

		// Reinitialize any sliders within context.
		BG.Service.event.on( 'blockAdded', $block => {
			this.sliderPlugin.initSlider( $block.find( '.boldgrid-slider' ) );
		} );

		// Reinitialize any sliders within context.
		BG.Service.event.on( 'blockDragEnter', $block => {
			if ( $block.hasClass( 'boldgrid-slider' ) ) {
				this.sliderPlugin.initSlider( $block );
			} else {
				this.sliderPlugin.initSlider( $block.find( '.boldgrid-slider' ) );
			}
		} );

		// When the slider is initalized, prevent edit of slider elements.
		this.sliderPlugin.event.on( 'initialized', $slider => {
			$slider
				.find( '.slick-dots, .slick-arrow' )
				.attr( 'contenteditable', 'false' )
				.attr( 'unselectable', 'on' )
				.attr( 'data-mce-bogus', 'all' );
		} );

		// Updates iframe and fourpan.
		this.sliderPlugin.event.on( 'slideChange', () => $( window ).trigger( 'resize' ) );
	}

	/**
	 * When a slider is cloned, init the new slider.
	 *
	 * @since 1.0.0
	 *
	 * @param  {jQuery} $markup Slider HTMl.
	 */
	onClone( $markup ) {
		if ( $markup.hasClass( 'boldgrid-slider' ) ) {
			this.sliderPlugin.initSlider( $markup );
		}
	}

	/**
	 * Insert a slider to the content.
	 *
	 * @since 1.0.0
	 *
	 * @param  {Object} component Component.
	 */
	prepend( component ) {
		let $inserted,
			$html = component.getDragElement();

		BG.Service.component.prependContent( $html );

		BG.Service.component.scrollToElement( $html, 200 );
		this.openCustomization( $html );
		this.sliderPlugin.initSlider( $html );
		setTimeout( () => $( window ).trigger( 'resize' ) );
	}

	/**
	 * Setup the Controls and the Component.
	 *
	 * @since 1.0.0
	 */
	_setupCustomization() {
		if ( ! BoldgridEditor.plugin_configs.premium.is_premium ) {
			return;
		}

		// Setup the ability for a user to customizer a component.
		this.control.init();

		BG.$window.on( 'boldgrid_editor_loaded', () => {
			BG.Service.component.register( this.sectionConfig );
			BG.Service.component.register( this.rowConfig );
		} );
	}
}
