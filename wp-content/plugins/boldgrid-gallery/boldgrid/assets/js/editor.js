/**
 * BoldGrid Source Code
 * @author BoldGrid <wpb@boldgrid.com>
 */
var IMHWPBGallery = IMHWPBGallery  || {};

( function( window, views, $ ) {
	var postID = $( '#post_ID' ).val() || 0,
		media, gallery;

	var $window = $(window);

	var hide_settings = function ( $context, settings ) {
		$.each( settings, function () {
			$context.find( '[data-setting="' + this + '"]' ).closest('.setting').hide();
		});
	};

	$(function () {
		$(document).on('change', '[data-setting="display"]', IMHWPBGallery.gallery_update_visible );
	});

	media = {
		state: [],

		edit: function( text, update ) {
			var media = wp.media[ this.type ],
				frame = media.edit( text );

			/* jshint -W030 */
			this.pausePlayers && this.pausePlayers();

			_.each( this.state, function( state ) {
				frame.state( state ).on( 'update', function( selection ) {
					update( media.shortcode( selection ).string() );
					IMHWPBGallery.init_gallery( $( tinymce.activeEditor.iframeElement ).contents() );
				} );
			} );

			frame.on( 'close', function() {
				frame.detach();
			} );

			frame.open();
		}
	};

	gallery = _.extend( {}, media, {
		state: [ 'gallery-edit' ],
		template: wp.media.template( 'editor-gallery' ),

		initialize: function() {
			this.tiny_mce_iframe = jQuery(tinymce.activeEditor.iframeElement).contents();
			var type = '';

			if ( typeof this.shortcode.attrs.named.display != 'undefined' ) {
				type = this.shortcode.attrs.named.display;
			}

			var single_image_slider = [ 'slider', 'slider2', 'sliderauto', 'sliderfadeauto' ]
			    .indexOf( type );

			if ( single_image_slider >= 0 ) {
				type = 'slider';
			}

			IMHWPBGallery.gutter_width = '0';
			if (this.shortcode.attrs.named.gutterwidth) {
				IMHWPBGallery.gutter_width = this.shortcode.attrs.named.gutterwidth;
			}


			// For different types of galleries use different templates.
			// DO NOT CHANGE THIS EQUALS SIGN.
			if ( false == type ) {
				this.template = wp.media.template( 'editor-gallery-boldgrid-masonry' );
			} else {
				switch ( type ) {
					case 'owlcolumns':
						this.template = wp.media.template( 'editor-gallery-boldgrid-owlcolumns' );
						break;
					case 'slider':
						this.template = wp.media.template( 'editor-gallery-boldgrid-slider' );
						break;
					case 'owlautowidth':
						this.template = wp.media.template( 'editor-gallery-boldgrid-owlautowidth' );
						break;
					case 'carousel':
						this.template = wp.media.template( 'editor-gallery-boldgrid-carousel' );
						break;
					case 'slider3bottomlinks':
						this.template = wp.media.template( 'editor-gallery-boldgrid-slider3' );
						break;
					case 'slider4bottomlinks':
						this.template = wp.media.template( 'editor-gallery-boldgrid-slider4' );
						break;
					default:
				}
			}

			var attachments = wp.media.gallery.attachments( this.shortcode, postID ),
			attrs = this.shortcode.attrs.named,
			self = this;

			attachments.more()
			.done( function() {
				attachments = attachments.toJSON();

				_.each( attachments, function( attachment ) {
					if ( attachment.sizes ) {
						if ( attrs.size && attachment.sizes[ attrs.size ] ) {
							attachment.thumbnail = attachment.sizes[ attrs.size ];
						} else if ( !attrs.size ) { //The thumbnail size if undefined, not set
							attachment.thumbnail = attachment.sizes.thumbnail;
						} else if ( attachment.sizes.full ) { //Default to large images otherwise
							attachment.thumbnail = attachment.sizes.full;
						} else if ( attachment.sizes.thumbnail ) {
							attachment.thumbnail = attachment.sizes.thumbnail;
						}
					}
				} );
				self.render( self.template( {
					attachments: attachments,
					columns: attrs.columns ? parseInt( attrs.columns, 10 ) : wp.media.galleryDefaults.columns
				} ) );

				//After the markup is renderd, initalize all of the galleries.
				IMHWPBGallery.init_gallery( self.tiny_mce_iframe );
			} )
			.fail( function( jqXHR, textStatus ) {
				self.setError( textStatus );
			} );
		}
	} );

	views.register( 'gallery', _.extend( {}, gallery ) );

	/**
	 * Procedure to be done when rezing is complete
	 * This function is BoldGrid only
	 */
	var on_screen_resize = function ( $container ) {
		var resize_done = function (){
			if ($container.is(':visible')) {
				IMHWPBGallery.runMasonry(0, $container);
			}
		};

		//Wait 100 MS before triggering the resize event
		var timeout;
		$window.on('resize.boldgrid-gallery', function(){
		  clearTimeout(timeout);
		  timeout = setTimeout(resize_done , 100);
		});
	};

	/**********************************************************************************
	 * The code below this line has been copied and modified from
	 * includes/js/gallery.js
	 * When these functions are updated in that file, we need to update them here.
	 * This approach was taken so that we would only be modifying content within ./boldgrid/
	 *
	 * Code that was added to these functions is commented with --Bold Grid--
	 *********************************************************************************/

	var calculateGrid = function($container) {
		var columns = parseInt( $container.data('columns') );
		var gutterWidth = $container.data('gutterWidth');
		var containerWidth = Math.floor($container[0].getBoundingClientRect().width);

		if ( isNaN( gutterWidth ) ) {
			gutterWidth = 5;
		}
		else if ( gutterWidth > 30 || gutterWidth < 0 ) {
			gutterWidth = 5;
		}

		if ( columns > 1 ) {
			if ( containerWidth < 568 ) {
				columns -= 2;
				if ( columns > 4 ) {
					columns = 4;
				}
			}
			/* else if ( containerWidth < 768 ) {
				columns -= 1;
			} */

			if ( columns < 2 ) {
				columns = 2;
			}
		}

		gutterWidth = parseInt( gutterWidth );

		var allGutters = gutterWidth * ( columns - 1 );
		var contentWidth = containerWidth - allGutters;

		var columnWidth = Math.floor( contentWidth / columns );
		return {columnWidth: columnWidth, gutterWidth: gutterWidth, columns: columns};
	};

	/**
	 * Update Visible Gallery Items
	 */
	IMHWPBGallery.gallery_update_visible = function () {
		var $this = $('.media-sidebar [data-setting="display"]');
		var value = $this.val();
		var $context = $this.closest('.media-sidebar');
		if ( BOLDGRIDGallery[ value ] ) {
			$context.find('.setting').show();
			hide_settings ( $context, BOLDGRIDGallery[ value ] );
		} else {
			$context.find('.setting').show();
			hide_settings ( $context, BOLDGRIDGallery[ 'default' ] );
		}
	};

	IMHWPBGallery.runMasonry = function( duration, $container) {
		var $postBox = $container.children('.gallery-item');

		var o = calculateGrid($container);
		$postBox.css({'width':o.columnWidth+'px', 'margin-bottom':o.gutterWidth+'px', 'padding':'0'});

		$container.masonry( {
			itemSelector: '.gallery-item',
			columnWidth: o.columnWidth,
			gutter: o.gutterWidth,
			transitionDuration: duration
		} );
	};

	IMHWPBGallery.init_gallery = function ( $master_container ) {
		//--Bold Grid--
		//Instead of searching the entire DOM for galleries, only search the iframe
		$(window).off('.boldgrid-gallery');
		$master_container.find('.gallery-masonry').each( function() {
			var $container = jQuery(this);
			var $posts = $container.children( '.gallery-item' ).show().css( 'visibility', 'visible' );

			$container.imagesLoaded( function() {
				IMHWPBGallery.runMasonry( 0, $container );
				$container.show().css( 'visibility', 'visible' );

				//--Bold Grid--
				//This function call was added to prevent resize from being called repeatedly times
				//when dragging window resize
				on_screen_resize( $container );
			});
		});

	};

} )( window, window.wp.mce.views, window.jQuery );
