var IMHWPB = IMHWPB || {};

IMHWPB.ScreenIdToplevelPageBoldgridInspirations = function() {
	var self = this;

	jQuery(function() {
		// Event handler for clicking on the "Budget" button filter.
		jQuery('a.drawer-toggle').on('click', function() {
			self.toggle_budget();
		});

		// Handle the filter menus
		jQuery('ul.filter-links a').on('click', function() {
			self.toggle_filter(this);
		});

		// Handle the dismissing of notices.
		// todo: this needs to be handled by admin notices in the future.
		jQuery(document).on(
				'click',
				'div.updated.notice.is-dismissible button.notice-dismiss',
				function() {
					jQuery(this).parent('div.updated.notice.is-dismissible')
							.hide();
				});
	});

	/**
	 * I wasn't able to figure out WP's click handling of the "Feature Filter",
	 * so here's some code to handle the clicking of the "Budget" filter.
	 */
	this.toggle_budget = function() {
		jQuery('div.filter-drawer').toggle();
	}

	/**
	 * 
	 */
	this.toggle_filter = function(e) {
		// At this time, Only the 'themes' tab has multiple tabs.
		// If we're not on the themes tab, there's nothing to toggle,
		// so just return.
		if (!jQuery('a#nav-step-2').hasClass('nav-tab-active')) {
			return false;
		}

		// get the data-toggle value.
		var toggle = jQuery(e).data('toggle');

		// Remove 'current' class from all links.
		jQuery('ul.filter-links a').each(function(key, value) {
			jQuery(this).removeClass('current');
		});

		// Add 'current' class to the link clicked on.
		jQuery(e).addClass('current');

		// Hide all divs.
		jQuery('.themes-current-category').hide();
		jQuery('.themes-other-categories').each(function() {
			jQuery(this).hide();
		});

		// Show only the divs we've clicked on.
		jQuery('.' + toggle).show();

	}

};

new IMHWPB.ScreenIdToplevelPageBoldgridInspirations();