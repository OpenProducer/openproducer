/**
 * BoldGrid Library Connect.
 *
 * @summary JavaScript to handle UI/UX.
 *
 * @since 2.4.0
 */

/* global ajaxurl,jQuery */

var BOLDGRID = BOLDGRID || {};
BOLDGRID.LIBRARY = BOLDGRID.LIBRARY || {};

( function( $ ) {
	BOLDGRID.LIBRARY.Connect = {

		/**
		 * Constructor.
		 *
		 * @since 2.4.0
		 */
		init: function() {
			$( self._onLoad );
		},

		/**
		 * On DOM load.
		 *
		 * @since 2.4.0
		 */
		_onLoad: function() {
			self._repositionNotice();

			$( '#submit' ).on( 'click', self._submit );
		},

		/**
		 * Reposition the notice into the correct location.
		 *
		 * @since 2.4.0
		 */
		_repositionNotice: function() {
			var $connectKeySection = $( '.connect-key-prompt' );

			setTimeout( function() {
				$connectKeySection.after( $( '#container_boldgrid_api_key_notice' ) );
			} );
		},

		/**
		 * Handle form submission.
		 *
		 * @since 2.7.0
		 */
		_submit: function() {
			var $this = $( this ),
				$spinner = $this.next(),
				$notice = $( '#settings-notice' ),
				data = {
					action: 'boldgrid_library_connect_settings_save',
					_wpnonce: $( '[name="_wpnonce"]' ).val(),
					_wp_http_referer: $( '[name="_wp_http_referer"]' ).val(),
					plugin_release_channel: $( 'input[name="plugin_release_channel"]:checked' ).val(),
					theme_release_channel: $( 'input[name="theme_release_channel"]:checked' ).val()
				};

			$this.attr( 'disabled', 'disabled' );

			$spinner.addClass( 'inline' );

			$.post(
				ajaxurl,
				data,
				function( response ) {
					if ( response.success !== undefined && true === response.success ) {
						$notice
							.removeClass( 'notice-error' )
							.addClass( 'notice-success' )
							.html( BoldGridLibraryConnect.settingsSaved );
					} else if ( response.data !== undefined && response.data.error !== undefined ) {
						$notice
							.removeClass( 'notice-success' )
							.addClass( 'notice-error' )
							.html( response.data.error );
						$this.removeAttr( 'disabled' );
					} else {
						$notice
							.removeClass( 'notice-success' )
							.addClass( 'notice-error' )
							.html( BoldGridLibraryConnect.unknownError );
						$this.removeAttr( 'disabled' );
					}
				},
				'json'
			)
				.fail( function( jqXHR ) {
					$notice
						.removeClass( 'notice-success' )
						.addClass( 'notice-error' )
						.html( BoldGridLibraryConnect.ajaxError + jqXHR.status + ' (' + jqXHR.statusText + ')' );
				} )
				.always( function() {
					self._replaceNotice( $notice );

					$notice.wrapInner( '<p></p>' ).show();
					$spinner.removeClass( 'inline' );
					$this.removeAttr( 'disabled' );
					$( 'body' ).trigger( 'make_notices_dismissible' );
				} );
		},

		/**
		 * Replace the notice with a clone when removed by dismissal.
		 *
		 * @since 2.7.0
		 */
		_replaceNotice: function( $notice ) {
			var $noticeClone = $notice.clone(),
				$noticeNext = $notice.next();

			$notice.one( 'click.wp-dismiss-notice', '.notice-dismiss', function() {
				$noticeNext.before( $noticeClone );
				$notice = $noticeClone;
				$notice.hide();
			} );
		},

		/**
		 * Handle form submission.
		 *
		 * @since 2.7.0
		 */
		_toggleHelp: function( e ) {
			var id = $( this ).attr( 'data-id' );

			e.preventDefault();

			if ( id === undefined ) {
				return false;
			}

			$( '.help[data-id="' + id + '"]' ).slideToggle();

			return false;
		}
	};

	var self = BOLDGRID.LIBRARY.Connect;
	BOLDGRID.LIBRARY.Connect.init();
} )( jQuery );
