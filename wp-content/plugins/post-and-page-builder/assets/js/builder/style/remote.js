window.BOLDGRID = window.BOLDGRID || {};
BOLDGRID.EDITOR = BOLDGRID.EDITOR || {};
BOLDGRID.EDITOR.STYLE = BOLDGRID.EDITOR.STYLE || {};

/**
 * Handles setting up the Gridblocks view.
 */
( function( $ ) {
	'use strict';

	var BG = BOLDGRID.EDITOR,
		self = {

			/**
			 * Fetch the from front end and apply them.
			 *
			 * @since 1.4
			 */
			getStyles: function( url ) {
				let onComplete = siteMarkup => {
					self.siteMarkup = siteMarkup;
					BG.GRIDBLOCK.View.headMarkup = self.getHeadElements( siteMarkup );
					BG.$window.trigger( 'boldgrid_page_html', self.siteMarkup );
					BG.$window.trigger( 'boldgrid_head_styles', self.headMarkup );
				};

				$.get( url )
					.success( markup => {
						onComplete( markup );
					} )
					.fail( event => {
						onComplete( event.responseText || '' );
					} );
			},

			/**
			 * Given markup for a site, get all of the stylesheets.
			 *
			 * @since 1.4
			 *
			 * @param string siteMarkup Markup for an Entire site.
			 * @return string Head markup that represents the styles.
			 */
			getHeadElements: function( siteMarkup ) {
				var $html, headMarkup;

				siteMarkup = siteMarkup.replace( /<body\b[^<]*(?:(?!<\/body>)<[^<]*)*<\/body>/, '' );
				$html = $( '<div>' ).html( siteMarkup );
				headMarkup = '';

				$html.find( 'link, style' ).each( function() {
					var $this = $( this ),
						markup = this.outerHTML,
						tagName = $this.prop( 'tagName' );

					if (
						'LINK' === tagName &&
						'stylesheet' !== $this.attr( 'rel' ) &&
						'boldgrid-custom-styles-css' !== $this.attr( 'id' )
					) {
						markup = '';
					}

					headMarkup += markup;
				} );

				headMarkup += wp.template( 'gridblock-iframe-styles' )();

				return headMarkup;
			}
		};

	BG.STYLE.Remote = self;
} )( jQuery );
