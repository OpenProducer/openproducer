var IMHWPB = IMHWPB || {};

IMHWPB.ScreenIdMediaPageBoldGridConnectSearch = function() {
	var self = this;

	jQuery(function() {
		// When the page loads, resize the iframe.
		self.resize_boldgrid_connect_search();

		// When the window size is changed, resize iframe.
		jQuery(window).resize(function() {
			self.resize_boldgrid_connect_search();
		});
	});

	/**
	 * 
	 */
	this.resize_boldgrid_connect_search = function() {
		var body_height = jQuery("body").height();
		jQuery(".wrap-boldgrid-connect-search").css("height",
				(body_height - 165) + "px");
	}
};

new IMHWPB.ScreenIdMediaPageBoldGridConnectSearch();