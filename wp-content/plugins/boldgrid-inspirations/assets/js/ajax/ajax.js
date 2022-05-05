var IMHWPB = IMHWPB || {};

IMHWPB.Ajax = function( configs ) {
	var self = this;

	this.configs = configs;
	this.api_url = this.configs.asset_server;
	this.api_key = this.configs.api_key;
	this.site_hash = this.configs.site_hash;
	this.lang = BoldGridInspirationsAjax;

	/**
	 * Execute an AJAX call.
	 *
	 * @param array    data           Data to sent via POST.
	 * @param string   requestUrlKey  Configuration index for the URL.
	 * @param function successAction  The success/done callback function.
	 * @param function errorAction    The errorAction/fail callback function.
	 * @param function completeAction The completeAction/always callback function.
	 */
	this.ajaxCall = function( data, requestUrlKey, successAction, errorAction, completeAction ) {
		self.data = data || {};
		self.requestUrlKey = requestUrlKey;

		if ( 'function' !== typeof errorAction ) {
			errorAction = self.errorAction;
		}
		if ( 'function' !== typeof completeAction ) {
			completeAction = function() {
				/** No Default * */
			};
		}

		data.key = self.api_key;
		data.site_hash = self.site_hash;

		jQuery.ajax( {
			type : 'POST',
			url : self.api_url + self.configs.ajax_calls[requestUrlKey],
			data : data,
			timeout : 120000,
			requestHeaders : {
				Accept : 'application/json'
			},
			dataType : 'json',
		} )
			.done( successAction )
			.fail ( [
				errorAction,
				self.errorLogAction
			] )
			.always( completeAction );
	};

	/**
	 * Error handling.
	 */
	this.errorAction = function( jqXHR, textStatus, errorThrown ) {
		var message, $wpbody;

		switch ( textStatus ) {
			case 'timeout':
				message = self.lang.timeout;
				break;

			case 'parsererror':
				message = self.lang.unexpected;
				break;

			default:
				break;
		}

		if ( window.parent.jQuery( '#wpbody-content' ).length ) {
			$wpbody = window.parent.jQuery( '#wpbody-content' );
		} else {
			$wpbody = jQuery( '#wpbody-content' );
		}

		// Provide a friendly error for comm failure, if notice is not already displayed.
		if ( ! window.parent.jQuery( '#container_boldgrid_connection_notice' ).length  &&
			! jQuery( '#container_boldgrid_connection_notice' ).length ) {
				if ( message ) {
					message += '<br />';
				} else {
					message = '';
				}

				$wpbody
					.hide()
					.before(
						'<div id="container_boldgrid_connection_notice" class="error">' +
							'<h2 class="dashicons-before dashicons-admin-network">' +
								self.lang.connectionIssue +
							'</h2>' +
							'<p>' +
								self.lang.pleaseTryAgain +
							'</p>' +
							'<p>' +
								self.lang.checkStatusPage +
							'</p>' +
							'<p>' +
								message +
								'<button class="button button-primary" onclick="location.reload();">' +
									self.lang.tryAgain +
								'</button>' +
							'</p>' +
						'</div>'
					);
		}
	};

	/**
	 * Error log handling.
	 *
	 * @since 1.5.8
	 */
	this.errorLogAction = function( jqXHR, textStatus, errorThrown ) {
		var data = {
			action : 'check_asset_server',
			data : {
				request : {
					data : self.data,
					requestUrlKey : self.requestUrlKey
				},
				response : {
					textStatus : textStatus
				}
			}
		};

		jQuery.post( ajaxurl, data, null, 'json' );
	};

	/**
	 * Set the api key.
	 *
	 * @since 1.7.0
	 *
	 * @param string apiKey The api key to set.
	 */
	this.setApiKey = function( apiKey ) {
		self.api_key = apiKey;
	}

	/**
	 * Set the site hash.
	 *
	 * @since 1.7.0
	 *
	 * @param string siteHash the site hash to set.
	 */
	this.setSiteHash = function( siteHash ) {
		self.site_hash = siteHash;
	}
};
