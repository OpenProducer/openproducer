var IMHWPB = IMHWPB || {};

IMHWPB.ScreenIdPage = function(configs) {
	var self = this;

	jQuery(function() {
		self.baseAdmin = new IMHWPB.BaseAdmin();

		/**
		 * MODULE: Pointers
		 * 
		 * Pointer for "media search"
		 */
		jQuery(document.body).on('click', '#media-search-input', function() {
			self.baseAdmin.show_pointer(this, '#media-search-input');
		});
	});

};

new IMHWPB.ScreenIdPage();