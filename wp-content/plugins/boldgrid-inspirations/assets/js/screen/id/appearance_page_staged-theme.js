var IMHWPB = IMHWPB || {};

IMHWPB.ScreenIdAppearancePageStagedTheme = function(configs) {
	var self = this;

	this.configs = configs;
	this.api_url = this.configs.asset_server;
	this.api_key = this.configs.api_key;

	this.api_param = 'key';
	this.api_key_query_str = this.api_param + "=" + this.api_key;
	
	// location.reload does not scroll to the top of the page, so let's do that now
	jQuery(window).scrollTop(0);

	jQuery(function() {
		// MODULE: Staging
		// set staging theme
		jQuery('a.stage').on('click', function() {
			self.set_staging_theme(this);
		});

		// MODULE: Staging
		// set staged theme as first theme in list
		jQuery("div.theme.active").prependTo('.themes');
	});

	// MODULE: Staging
	// set staging theme
	this.set_staging_theme = function(e) {
		var stylesheet = jQuery(e).data('stylesheet');

		var data = {
			'action' : 'set_staging_theme',
			'stylesheet' : stylesheet
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to
		// admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			if ('success' == response) {
				location.reload();
			} else {
				alert(response);
			}
		});
	}
};

new IMHWPB.ScreenIdAppearancePageStagedTheme(IMHWPB.configs);