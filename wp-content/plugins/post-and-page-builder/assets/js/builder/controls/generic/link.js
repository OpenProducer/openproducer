window.BOLDGRID = window.BOLDGRID || {};
BOLDGRID.EDITOR = BOLDGRID.EDITOR || {};
BOLDGRID.EDITOR.CONTROLS = BOLDGRID.EDITOR.CONTROLS || {};
BOLDGRID.EDITOR.CONTROLS.GENERIC = BOLDGRID.EDITOR.CONTROLS.GENERIC || {};

( function( $ ) {
	'use strict';

	var self,
		BG = BOLDGRID.EDITOR;

	BOLDGRID.EDITOR.CONTROLS.GENERIC.Link = {
		template: wp.template( 'boldgrid-editor-insert-link' ),
		render: function() {
			var $control = $( this.template() );

			BG.Panel.$element
				.find( '.panel-body .customize' )
				.find( '.section.insert-link' )
				.remove();
			BG.Panel.$element.find( '.panel-body .customize' ).append( $control );

			return $control;
		},
		bind: function() {
			BG.Panel.$element.find( '.section.insert-link' ).on( 'click', function() {
				var $el = BG.Menu.getTarget( BG.Panel.currentControl );

				BOLDGRID.EDITOR.mce.selection.select( $el[0] );
				BOLDGRID.EDITOR.mce.execCommand( 'WP_Link' );
			} );
		}
	};

	self = BOLDGRID.EDITOR.CONTROLS.GENERIC.Link;
} )( jQuery );
