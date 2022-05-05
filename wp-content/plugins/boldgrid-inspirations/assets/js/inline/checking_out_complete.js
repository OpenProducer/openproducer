$stop_and_explain = jQuery(".stop-and-explain");
$title_purchase = jQuery("h1.purchasing");
var ruler = "<hr class='clear' />";

// Hide the spinner.
$spinner.remove();

// Hide the loading graphic.
jQuery('.boldgrid-loading').remove();

// Move "stop and explain" to the top of the page.
$stop_and_explain.insertBefore($title_purchase).slideToggle(1000);

// Move the separator into place.
jQuery(ruler).insertBefore($title_purchase);

// Update the title of the page.
$title_purchase
	.html( BoldGridInspirationsPurchase.purchaseComplete )
	.prepend( "<span class='dashicons dashicons-yes'></span>" );

// Scroll the user to the top of the page.
jQuery("html").animate({
	scrollTop : 0
}, "slow");