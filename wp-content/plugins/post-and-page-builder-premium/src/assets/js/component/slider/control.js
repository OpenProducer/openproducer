import './style.scss';
import { Carousel } from '@boldgrid/controls/src/controls/carousel';
import { Action } from './action';

let $ = jQuery,
	BG = BOLDGRID.EDITOR;

export class Control {
	constructor( sliderPlugin ) {
		this.name = 'slider';
		this.priority = 90;
		this.tooltip = 'Slider';
		this.iconClasses = 'dashicons dashicons-images-alt';
		this.selectors = [ '.boldgrid-slider' ];
		this.allowNested = true;
		this.sliderPlugin = sliderPlugin;

		this.panel = {
			title: 'Slider',
			height: '575px',
			width: '325px'
		};
	}

	/**
	 * Load up the control.
	 *
	 * @since 1.0.0
	 */
	init() {
		BG.$window.on( 'boldgrid_editor_preload', () => BG.Controls.registerControl( this ) );
	}

	/**
	 * Open the Panel with configuration form.
	 *
	 * @since 1.0.0
	 */
	openPanel() {
		BG.Panel.clear();
		BG.Panel.open( this );

		this._createUI();
		BG.Panel.$element.find( '.panel-body' ).html( this.$control );
	}

	/**
	 * Setup the change event.
	 *
	 * @since 1.0.0
	 */
	_bindChange() {
		let $colors = this.$control.find( '.color-preview + input' );

		let updateSlider = _.debounce( data => {
			let $target = BG.Menu.getCurrentTarget();

			this._saveColors( $colors, data );

			$target.attr( 'data-config', JSON.stringify( data ) );
			this.sliderPlugin.initSlider( $target );
		}, 50 );

		this.carousel.event.on( 'change', updateSlider );

		new Action( this, BG.Menu.getCurrentTarget() ).bind();
	}

	/**
	 * Create a UI for the form.
	 *
	 * @since 1.0.0
	 *
	 * @return {$} Jquery Form.
	 */
	_createUI() {
		let $slider = BG.Menu.getCurrentTarget(),
			attr = $slider.attr( 'data-config' ),
			configs = attr ? JSON.parse( attr ) : {};

		this.carousel = new Carousel( {
			title: '',
			preset: configs
		} );

		this.$control = this.carousel.render();
		this._presetColors();
		this._bindChange();
	}

	/**
	 * Open menu event, fires when the user clicks on the option from the drop tab.
	 *
	 * @since 1.0.0
	 */
	onMenuClick() {
		this.openPanel();
	}

	/**
	 * Save the color settings in the slider config.
	 *
	 * @since 1.0.0
	 *
	 * @param  {jQuery} $colors Element.
	 * @param  {object} data    Slider.
	 */
	_saveColors( $colors, data ) {
		data.colors = {};
		$colors.each( ( index, el ) => {
			let $el = $( el ),
				colorConfig = {
					type: $el.attr( 'data-type' ),
					value: $el.next().text()
				};

			// Calculate contrast.
			colorConfig.text =
				'color' === colorConfig.type ?
					BG.Service.colorCalculation.getContrast( colorConfig.value ) :
					'';

			data.colors[$el.attr( 'name' )] = colorConfig;
		} );
	}

	/**
	 * When the control is opened, preset all color controls.
	 *
	 * @since 1.0.0
	 */
	_presetColors() {
		let config = this.sliderPlugin.parseConfig( BG.Menu.getCurrentTarget() );

		for ( let control of this.sliderPlugin.settings ) {
			if ( ! _.isEmpty( config ) && config.colors[control.name] ) {
				this._presetColorControl( config, control );
			}
		}
	}

	/**
	 * Preset a color control.
	 *
	 * @since 1.0.0
	 *
	 * @param {object} config Saved Slider Configurations.
	 * @param {object} color  Color control configurations.
	 */
	_presetColorControl( config, color ) {
		let $slider = BG.Menu.getCurrentTarget(),
			calculatedValue = $slider.find( color.selector ).css( color.type ),
			$colorPreview = this.$control.find( `.color-preview[for="${color.name}"]` );

		$colorPreview.css( 'background-color', calculatedValue );

		$colorPreview
			.next( 'input' )
			.attr( 'data-type', config.colors[color.name].type )
			.attr( 'value', config.colors[color.name].value );
	}
}
