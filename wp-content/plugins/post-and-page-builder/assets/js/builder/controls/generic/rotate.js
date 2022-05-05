window.BOLDGRID = window.BOLDGRID || {};
BOLDGRID.EDITOR = BOLDGRID.EDITOR || {};
BOLDGRID.EDITOR.CONTROLS = BOLDGRID.EDITOR.CONTROLS || {};
BOLDGRID.EDITOR.CONTROLS.GENERIC = BOLDGRID.EDITOR.CONTROLS.GENERIC || {};

( function( $ ) {
	'use strict';

	var self,
		BG = BOLDGRID.EDITOR;

	BOLDGRID.EDITOR.CONTROLS.GENERIC.Rotate = {
		classes: [ 'fa-rotate-90', 'fa-rotate-180', 'fa-rotate-270' ],
		getDefault: function() {
			var $el = BG.Menu.getCurrentTarget(),
				value = 0;

			if ( $el.hasClass( 'fa-rotate-90' ) ) {
				value = 90;
			} else if ( $el.hasClass( 'fa-rotate-180' ) ) {
				value = 180;
			} else if ( $el.hasClass( 'fa-rotate-270' ) ) {
				value = 270;
			}

			return value;
		},
		template: wp.template( 'boldgrid-editor-rotate' ),
		render: function() {
			var $control = $( this.template() );

			BG.Panel.$element
				.find( '.panel-body .customize' )
				.find( '.section.rotate-control' )
				.remove();
			BG.Panel.$element.find( '.panel-body .customize' ).append( $control );

			return $control;
		},
		bind: function() {
			var defaultSize = this.getDefault(),
				$el = BG.Menu.getCurrentTarget();

			BG.Panel.$element.find( '.section.rotate-control .value' ).html( defaultSize );
			BG.Panel.$element.find( '.section.rotate-control .slider' ).slider( {
				min: 0,
				step: 90,
				max: 270,
				value: defaultSize,
				range: 'max',
				slide: function( event, ui ) {

					// Remove Classes.
					$el.removeClass( self.classes.join( ' ' ) );
					if ( ui.value ) {
						$el.addClass( 'fa-rotate-' + ui.value );
					}
				}
			} );
		}
	};

	self = BOLDGRID.EDITOR.CONTROLS.GENERIC.Rotate;
} )( jQuery );
