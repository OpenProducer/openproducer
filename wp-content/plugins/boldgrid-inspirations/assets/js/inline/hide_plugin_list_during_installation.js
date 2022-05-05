// Hide the existing plugins because we want the user to refresh the page to see the new plugins
jQuery("form#bulk-action-form").parent("div.wrap").addClass("hidden");

// Hide the activate buttons
jQuery("div.wrap p a[href*=\'plugins.php\']").parent("p").addClass("hidden");