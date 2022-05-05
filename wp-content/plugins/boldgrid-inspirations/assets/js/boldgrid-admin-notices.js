var IMHWPB = IMHWPB || {};

IMHWPB.BoldGridAdminNotices = function( configs ) {
	var self = this;

	jQuery( function() {

		// Allow users to dismiss a notice.
		jQuery( '.boldgrid-admin-notice .notice-dismiss' ).on( 'click', function() {
			self.dismiss_boldgrid_admin_notice( this );
		} );
	} );

	/**
	 * Allow users to dismiss a notice.
	 */
	self.dismiss_boldgrid_admin_notice = function( dismiss_button ) {

		// Get the id of the notice.
		var admin_notice_id = jQuery( dismiss_button )
			.parents( '.boldgrid-admin-notice' )
			.attr( 'data-admin-notice-id' );
		var data = {
			action: 'dismiss_boldgrid_admin_notice',
			id: admin_notice_id
		};

		jQuery.post( ajaxurl, data, function( response ) {} );
	};
};

new IMHWPB.BoldGridAdminNotices();
