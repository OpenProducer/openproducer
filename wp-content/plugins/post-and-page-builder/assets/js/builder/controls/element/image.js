window.BOLDGRID = window.BOLDGRID || {};
BOLDGRID.EDITOR = BOLDGRID.EDITOR || {};
BOLDGRID.EDITOR.CONTROLS = BOLDGRID.EDITOR.CONTROLS || {};
BOLDGRID.EDITOR.CONTROLS.IMAGE = BOLDGRID.EDITOR.CONTROLS.IMAGE || {};

( function( $ ) {
	'use strict';

	var self,
		BG = BOLDGRID.EDITOR;

	BOLDGRID.EDITOR.CONTROLS.IMAGE.Design = {
		classes: BoldgridEditor.builder_config.image,

		name: 'image',

		tooltip: 'Image Design',

		priority: 80,

		iconClasses: 'fa fa-cog',

		selectors: [ 'img' ],

		init: function() {
			BOLDGRID.EDITOR.Controls.registerControl( this );
		},

		panel: {
			title: 'Image Design',
			height: '550px',
			width: '325px',
			includeFooter: true,
			customizeCallback: true,
			customizeLeaveCallback: true,
			customizeSupport: [
				'background-color',
				'margin',
				'border-radius',
				'padding',
				'border',
				'animation',
				'box-shadow',
				'device-visibility',
				'customClasses'
			]
		},

		/**
		 * When the user clicks on the menu item, open panel.
		 *
		 * @since 1.2.7
		 */
		onMenuClick: function() {
			self.openPanel();
		},

		/**
		 * When the user clicks on an image, if the panel is open, set panel content.
		 *
		 * @since 1.2.7
		 */
		elementClick: function() {
			if ( BOLDGRID.EDITOR.Panel.isOpenControl( this ) ) {
				self.openPanel();
			}
		},

		/**
		 * Bind Handlers.
		 *
		 * @since 1.2.7
		 */
		setup: function() {
			self.validateComponentsUsed();
			self._setupPanelClick();
			self._setupCustomizeHandlers();
		},

		/**
		 * Bind Event: When customization exits.
		 *
		 * @since 1.2.8
		 */
		_setupCustomizeHandlers: function() {
			var panel = BOLDGRID.EDITOR.Panel;

			panel.$element.on( 'bg-customize-exit', function() {
				if ( panel.currentControl === self ) {
					BG.Panel.showFooter();
				}
			} );
		},

		/**
		 * Remove duplicates from the list of image components used.
		 *
		 * @since 1.2.7
		 */
		validateComponentsUsed: function() {
			var config = BoldgridEditor.builder_config.components_used;

			$.each( config.image, function() {
				var $temp = $( '<div>' ).attr( 'class', this.classes );
				self.removeImageClass( $temp );
				this.classes = $temp.attr( 'class' );
			} );

			config.image = _.uniq( config.image, function( item ) {
				return item.a;
			} );
		},

		/**
		 * Remove the wp-image class added to the image by WordPress.
		 *
		 * This is only done to temporary objects.
		 *
		 * @since 1.2.8
		 * @param jQuery $el Element to manipulate.
		 * @retrurn jQuery $el.
		 */
		removeImageClass: function( $el ) {
			$el.removeClass( function( index, css ) {
				return ( css.match( /(^|\s)wp-image-\S+/g ) || [] ).join( ' ' );
			} );

			return $el;
		},

		/**
		 * Bind event: When a user clicks on selections in the panel.
		 *
		 * @since 1.2.7
		 */
		_setupPanelClick: function() {
			var panel = BOLDGRID.EDITOR.Panel;

			panel.$element.on( 'click', '.image-design .panel-selection', function() {
				var $this = $( this ),
					preset = $this.data( 'preset' ),
					$target = BOLDGRID.EDITOR.Menu.getTarget( self );

				BG.Controls.addStyle( $target, 'border', '' );
				BG.Controls.addStyle( $target, 'border-radius', '' );

				// Remove Classes.
				$target.removeClass( function( index, css ) {
					return ( css.match( /(^|\s)bg-img-\S+/g ) || [] ).join( ' ' );
				} );

				self.removeModClasses( $target );
				$target.removeClass( function( index, css ) {
					return ( css.match( /(^|\s)img-\S+/g ) || [] ).join( ' ' );
				} );

				BOLDGRID.EDITOR.mce.selection.collapse( false );

				if ( $this.hasClass( 'selected' ) ) {
					panel.clearSelected();
				} else {
					panel.clearSelected();
					$target.addClass( preset );
					$this.addClass( 'selected' );
				}
			} );
		},

		/**
		 * Remove image classes.
		 *
		 * @since 1.2.7
		 * @param jQuery $target.
		 */
		removeModClasses: function( $target ) {
			$target.parent( '[class^="mod-img"]' ).removeClass( function( index, css ) {
				return ( css.match( /(^|\s)mod-img-\S+/g ) || [] ).join( ' ' );
			} );
		},

		/**
		 * Preselect image style that is currently being used.
		 *
		 * @since 1.2.7
		 */
		preselectImage: function() {
			var $target = BG.Menu.getTarget( self ),
				imageClasses = $target.attr( 'class' ),
				bgImageClasses = [];

			imageClasses = imageClasses ? imageClasses.split( ' ' ) : [];

			$.each( imageClasses, function() {
				if ( 0 === this.indexOf( 'bg-img' ) ) {
					bgImageClasses.push( this );
				}
			} );

			bgImageClasses = bgImageClasses.join( ' ' );

			if ( bgImageClasses ) {
				BG.Panel.$element.find( '[data-preset="' + bgImageClasses + '"]:first' ).addClass( 'selected' );
				return false;
			}
		},

		/**
		 * Add images that exist on the page to list of used components. This will populate "My Designs".
		 *
		 * @since 1.2.7
		 */
		_updateMyDesigns: function() {
			self.usedComponents = BoldgridEditor.builder_config.components_used.image.slice( 0 );

			BG.Controls.$container.$body.find( '.bg-img' ).each( function() {
				var classes,
					savedComponents,
					savedIndex,
					findIndex,
					$this = $( this ),
					$clone = $this.clone().removeClass( 'bg-control-element' );

				$clone = self.removeImageClass( $clone );

				classes = $clone.attr( 'class' );
				savedComponents = self.usedComponents;

				findIndex = function( item ) {
					return item.classes === classes;
				};

				savedIndex = _.findIndex( savedComponents, findIndex );

				if ( -1 === savedIndex ) {
					savedComponents.push( {
						style: $clone.attr( 'style' ),
						classes: classes
					} );
				}
			} );
		},

		/**
		 * Open the panel for this control.
		 *
		 * @since 1.2.7
		 */
		openPanel: function() {
			var panel = BOLDGRID.EDITOR.Panel,
				$target = BOLDGRID.EDITOR.Menu.getTarget( self ),
				template = wp.template( 'boldgrid-editor-image' );

			self._updateMyDesigns();

			// Remove all content from the panel.
			panel.clear();

			// Set markup for panel.
			panel.$element.find( '.panel-body' ).html(
				template( {
					src: $target.attr( 'src' ),
					presets: self.classes,
					myPresets: self.usedComponents
				} )
			);

			self.preselectImage();

			// Open Panel.
			panel.open( self );
		}
	};

	BOLDGRID.EDITOR.CONTROLS.IMAGE.Design.init();
	self = BOLDGRID.EDITOR.CONTROLS.IMAGE.Design;
} )( jQuery );
