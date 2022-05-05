var BOLDGRID = BOLDGRID || {};
BOLDGRID.LIBRARY = BOLDGRID.LIBRARY || {};

( function ( $ ) {

	"use strict";

	var api, self;

	api = BOLDGRID.LIBRARY;

	api.PluginInstaller = {

		/**
		 * Is a process currently loading?
		 *
		 * @type {Boolean} Whether or not a process is currently loading.
		 */
		loading : false,

		/**
		 * Localized data.
		 *
		 * @type {Object} Data that has been localized through WordPress.
		 */
		i18n : _bglibPluginInstaller,

		/**
		 * Initialize the PluginInstaller.
		 *
		 * @since 0.1.0
		 */
		init : function () {
			$( document ).ready( self.onReady );
		},

		/**
		 * Sets up event listeners on document ready.
		 *
		 * @since 0.1.0
		 */
		onReady : function() {
			self.status();
			self._installs();
			self._updates();
			self._processing();
			self._search();
		},

		/**
		 * Check that data has been updated when user immediately updates
		 * release channels.
		 *
		 * @since 0.2.0
		 */
		status : function() {
			if ( ! _bglibPluginInstaller.status && ~ document.referrer.indexOf( 'boldgrid-settings' ) ) {
				window.location.reload( true );
			}
		},

		/**
		 * Install event handlers.
		 *
		 * @since 0.2.0
		 */
		_installs : function() {
			self._buttons();
			self._installSuccess();
			self._installError();
		},

		/**
		 * Upgrade success event handler.
		 *
		 * @since 0.2.0
		 */
		_installSuccess : function() {
			$( document).on ( 'wp-plugin-install-success', function( event, response ) {
				var button, notice, card = $( '.plugin-card-' + response.slug );
					notice = card.find( '.installer-messages' );
					button = card.find( '.install.button, .installing.button' );

				notice.removeClass( 'installing' );

				// Update the button to activation button.
				button.attr( 'class', 'activate button button-primary' );
				button.text( wp.updates.l10n ? wp.updates.l10n.activatePlugin : wp.i18n.__( 'Activate', 'boldgrid-plugin-installer' ) );

				// Update success message.
				notice.attr( 'class', 'notice updated-message notice-success notice-alt' )
					.find( 'p' )
					.text( wp.updates.l10n ? wp.updates.l10n.installedMsg : wp.i18n.__( 'Installation completed successfully.', 'boldgrid-plugin-installer' ) );

				// Clear loading process indicator.
				self.loading = false;
			} );
		},

		/**
		 * Update error event handler.
		 *
		 * @since 0.2.0
		 */
		_installError : function() {
			$( document).on( 'wp-plugin-install-error', function() {
				var card = $('.plugin-card-update-failed' );
				card.find( '.installer-messages')
					.replaceWith( card.find( '.notice.update-message.notice-error.notice-alt.is-dismissible' ) );
				card.find( '.notice' ).addClass( 'installer-messages' ).show();

				// Clear loading process indicator.
				self.loading = false;
			} );
		},

		/**
		 * Update event handlers.
		 *
		 * @since 0.2.0
		 */
		_updates : function() {
			self._updateLinks();
			self._updateSuccess();
			self._updateError();
		},

		/**
		 * Update processing event handler.
		 *
		 * @since 0.2.0
		 */
		_processing: function() {
			$( window ).on( 'message', function( event ) {
				var originalEvent  = event.originalEvent,
					expectedOrigin = document.location.protocol + '//' + document.location.hostname,
					message,
					el;

				if ( originalEvent.origin !== expectedOrigin ) {
					return;
				}

				try {
					message = $.parseJSON( originalEvent.data );
				} catch ( e ) {
					return;
				}

				if ( message && message.action ) {

					// Update plugin events.
					if ( message.action === 'update-plugin' ) {
						window.tb_remove();

						// Ensure plugin doesn't have process running and button is enabled.
						if ( self.loading ) {
							return false;
						}
						el = $( '.bglib-plugin-installer .plugin-card-' + message.data.slug + ' .installer-messages' );

						// Add processing message.
						el.addClass( 'updating-message' )
							.find( 'p' )
							.text( wp.updates.l10n ? wp.updates.l10n.updatingMsg : wp.i18n.__( 'Updating...', 'boldgrid-plugin-installer' ) );

						// Add loading button.
						$( '.plugin-card-' + message.data.slug + ' .install.button' )
							.html( wp.updates.l10n ? wp.updates.l10n.installing : wp.i18n.__( 'Installing...', 'boldgrid-plugin-installer' ) )
							.attr( 'class', 'installing button disabled' );
					}

					// Install plugin events.
					if ( message.action === 'install-plugin' ) {
						window.tb_remove();

						// Ensure plugin doesn't have process running and button is enabled.
						if ( self.loading ) {
							return false;
						}
						el = $( '.bglib-plugin-installer .plugin-card-' + message.data.slug + ' .installer-messages' );

						// Add processing message.
						el.addClass( 'installer-messages updating-message notice inline notice-warning notice-alt' )
							.find( 'p' )
							.text( wp.updates.l10n ? wp.updates.l10n.installingMsg : wp.i18n.__( 'Installing...', 'boldgrid-plugin-installer' ) );

						// Add loading button.
						$( '.plugin-card-' + message.data.slug + ' .install.button' )
							.html( wp.updates.l10n ? wp.updates.l10n.installingMsg : wp.i18n.__( 'Installing...', 'boldgrid-plugin-installer' ) )
							.attr( 'class', 'installing button disabled' );
					}
				}
			} );
		},

		/**
		 * Upgrade success event handler.
		 *
		 * @since 0.2.0
		 */
		_updateSuccess : function() {
			$( document ).on( 'wp-plugin-update-success', function( event, response ) {
				var el = $( '.plugin-card-' + response.slug ).find( '.installer-messages' );

				// Disable the install/activate button while performing upgrade.
				el.closest( 'a.button' ).attr( 'class', 'installed button disabled' );

				// Update success message.
				el.attr( 'class', 'installer-messages update-message updated-message notice inline notice-success notice-alt' )
					.find( 'p' )
					.text( wp.updates.l10n ? wp.updates.l10n.updatedMsg : wp.i18n.__( 'Update completed successfully.', 'boldgrid-plugin-installer' ) );

				// Clear loading process indicator.
				self.loading = false;
			} );
		},

		/**
		 * Update error event handler.
		 *
		 * @since 0.2.0
		 */
		_updateError : function() {
			$( document ).on ( 'wp-plugin-update-error', function() {
				var card = $( '.plugin-card-update-failed' );
				card.find( '.installer-messages')
					.replaceWith( card.find( '.notice.update-message.notice-error.notice-alt.is-dismissible' ) );
				card.find( '.notice' ).addClass( 'installer-messages' ).show();

				// Clear loading process indicator.
				self.loading = false;
			} );
		},

		/**
		 * Upgrade Links event handler.
		 *
		 *  @since 0.1.0
		 */
		_updateLinks : function() {
			$( '.update-link' ).on( 'click', function( e ) {
				var el, plugin, slug, title;

				el = $( this );
				plugin = el.data( 'plugin' );
				slug = el.data( 'slug' );

				e.preventDefault();

				// Ensure plugin doesn't have process running and button is enabled.
				if ( self.loading ) {
					return false;
				}

				el = el.closest( '.installer-messages' );
				title = el.next().find( '.plugin-title' ).text();

				// Remove any current messages displayed.
				el.addClass( 'updating-message' )
					.find( 'p' )
					.html( wp.updates.l10n ? wp.updates.l10n.updatingMsg : wp.i18n.__( 'Updating... please wait.', 'boldgrid-plugin-installer' ) );

				// Send ajax request to upgrade plugin.
				wp.updates.updatePlugin( {
					plugin : plugin,
					slug : slug
				} );
			} );
		},

		/**
		 * Activate a WordPress plugin.
		 *
		 * @since 0.1.0
		 *
		 * @param {Object} el     The activate button element.
		 * @param {string} plugin The plugin slug to activate.
		 */
		activate : function( el, plugin ) {
			$.ajax( {
				type: 'POST',
				url: self.i18n.ajaxUrl,
				data: {
					action: 'activation',
					plugin: plugin,
					nonce: self.i18n.nonce,
					dataType: 'json'
				},
				success: function( response ) {
					el.attr( 'class', 'installed button disabled' );
					el.html( self.i18n.installed );
					el.removeClass( 'installing' );
					el.closest( '.plugin' )
						.find( '.installer-messages' )
						.addClass( 'installer-messages notice updated-message notice-success notice-alt' )
						.find( 'p' )
						.html( response.data.message );

					// Clear loading process indicator.
					self.loading = false;
				},
				error: function( xhr, status, error ) {
					el.removeClass( 'installing' );
					el.closest( '.plugin' )
						.find( '.installer-messages' )
						.addClass( 'installer-messages notice update-message notice-error notice-alt is-dismissible' )
						.find( 'p' )
						.text( error );

					// Clear loading process indicator.
					self.loading = false;
				}
			} );
		},

		/**
		 * Install/Activate buttons event handler.
		 *
		 *  @since 0.1.0
		 */
		_buttons : function() {
			$( '.bglib-plugin-installer' ).on( 'click', 'a.button:not(.get-premium)', function( e ) {
				var el, slug;

				el = $( this );
				slug = el.data( 'slug' );

				e.preventDefault();

				// Ensure plugin doesn't have process running and button is enabled.
				if ( el.hasClass( 'disabled' ) || self.loading ) {
					return false;
				}

				// Remove any current messages displayed.
				el.closest( '.plugin' )
					.find( '.notice' )
					.attr( 'class', 'installer-messages' )
					.find( 'p' )
					.text( '' );

				// Installation of plugins.
				if ( el.hasClass( 'install' ) ) {
					el.html( wp.updates.l10n ? wp.updates.l10n.installing : wp.i18n.__( 'Installing...', 'boldgrid-plugin-installer' ) );
					el.attr( 'class', 'installing button disabled' );

					// Send ajax request to upgrade plugin.
					wp.updates.installPlugin( {
						slug : slug
					} );
				}

				// Activation of plugins.
				if ( el.hasClass( 'activate' ) ) {
					el.html( self.i18n.activating );
					el.attr( 'class', 'installing button disabled' );
					self.activate( el, slug );
				}
			} );
		},

		/**
		 * Search event handler.
		 *
		 *  @since 0.2.0
		 */
		_search : function() {
			var $pluginFilter        = $( '#plugin-filter' ),
				$pluginInstallSearch = $( '.plugin-install-php .wp-filter-search' );

			// Unbind existing events attached to search form.
			$pluginInstallSearch.unbind();

			/**
			 * Handles changes to the plugin search box for our tab.
			 *
			 * @since 0.2.0
			 */
			$pluginInstallSearch.on( 'keyup input', _.debounce( function( event, eventtype ) {
				var $searchTab = $( '.plugin-install-search' ), data, searchLocation;

				// Query data.
				data = {
					s:           event.target.value,
					tab:         'search',
					type:        $( '#typeselector' ).val(),
				};

				// Build the new browser location search query.
				searchLocation = location.href.split( '?' )[ 0 ] + '?' + $.param(  data );

				// Clear on escape.
				if ( 'keyup' === event.type && 27 === event.which ) {
					event.target.value = '';
				}

				// Listen for search type input dropdown change and empty search field if type changes.
				if ( wp.updates.searchTerm === data.s && 'typechange' !== eventtype ) {
					return;
				} else {
					$pluginFilter.empty();
					wp.updates.searchTerm = data.s;
				}

				// Update the URL in the browser.
				if ( window.history && window.history.replaceState ) {
					window.history.replaceState( null, '', searchLocation );
				}

				// Abort if not defined.
				if ( ! _.isUndefined( wp.updates.searchRequest ) ) {
					wp.updates.searchRequest.abort();
				}

				// Reload the page with the added search query.
				window.location.reload( true );

			}, 500 ) );
		}
	};

	self = api.PluginInstaller;

})( jQuery );

BOLDGRID.LIBRARY.PluginInstaller.init();
