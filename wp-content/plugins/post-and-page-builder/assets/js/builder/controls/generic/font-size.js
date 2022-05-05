window.BOLDGRID = window.BOLDGRID || {};
BOLDGRID.EDITOR = BOLDGRID.EDITOR || {};
BOLDGRID.EDITOR.CONTROLS = BOLDGRID.EDITOR.CONTROLS || {};
BOLDGRID.EDITOR.CONTROLS.GENERIC = BOLDGRID.EDITOR.CONTROLS.GENERIC || {};

( function( $ ) {
	'use strict';

	var self,
		BG = BOLDGRID.EDITOR;

	BOLDGRID.EDITOR.CONTROLS.GENERIC.Fontsize = {
		template: wp.template( 'boldgrid-editor-font-size' ),
		render: function() {
			var $control = $( this.template() );

			BG.Panel.$element
				.find( '.panel-body .customize' )
				.find( '.section.size' )
				.remove();
			BG.Panel.$element.find( '.panel-body .customize' ).append( $control );

			return $control;
		},
		bind: function() {
			var $el = BG.Menu.getTarget( BG.Panel.currentControl ),
				elementSize = $el.css( 'font-size' ),
				defaultSize = elementSize ? parseInt( elementSize ) : 14;

			defaultSize = 5 <= defaultSize ? defaultSize : 14;

			BG.Panel.$element.find( '.section.size .value' ).html( defaultSize );
			BG.Panel.$element.find( '.section.size .slider' ).slider( {
				min: 5,
				max: 115,
				value: defaultSize,
				range: 'max',
				slide: function( event, ui ) {
					BG.Panel.$element.find( '.section.size .value' ).html( ui.value );
					BG.Controls.addStyle( $el, 'font-size', ui.value );
				}
			} );
		}
	};

	self = BOLDGRID.EDITOR.CONTROLS.GENERIC.Fontsize;
} )( jQuery );
