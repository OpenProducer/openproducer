var BOLDGRID = BOLDGRID || {};
BOLDGRID.LIBRARY = BOLDGRID.LIBRARY || {};

BOLDGRID.LIBRARY.Notice = function( $ ) {
	var $notices,
		self = this;

	$( function() {
		$notices = $( '.boldgrid-notice.is-dismissible' );

		/** Dismissible action **/
		$notices.on( 'click', '.notice-dismiss', self.dismiss );
	} );

	/**
	 * Handle click of the notice-dismiss icon in the notice.
	 *
	 * @since 2.1.0
	 */
	this.dismiss = function() {
		var $notice = $( this ).closest( '.boldgrid-notice' );

		$.post( ajaxurl, {
			action: 'dismissBoldgridNotice',
			notice: $notice.data( 'notice-id' ),
			set_key_auth: $( '#set_key_auth', $notice ).val(),
			_wp_http_referer: $( '[name="_wp_http_referer"]', $notice ).val()
		} );
	};
};

new BOLDGRID.LIBRARY.Notice( jQuery );
