jQuery(document)
		.ready(
				function($) {
					$
							.each(
									WPHelpPointer.pointers,
									function(i) {
										var open_on_page_load = (typeof WPHelpPointer.pointers[i].options.position.open_on_page_load == 'undefined' || true == WPHelpPointer.pointers[i].options.position.open_on_page_load) ? true
												: false;

										if (true == open_on_page_load) {
											wp_help_pointer_open(i);
										}
									});
				});

function wp_help_pointer_open(i) {
	pointer = WPHelpPointer.pointers[i];

	options = jQuery.extend(pointer.options, {
		// ajax request to add this pointer to dismiss-wp-pointer
		close : function() {
			var pointer_selector = jQuery(pointer.target).attr(
					'data-pointer-selector');
			var pointer_id = jQuery(this).attr('data-pointer-id');
			if (typeof pointer_id != 'undefined') {
				var pointer_index = WPHelpPointerIndex[pointer_selector];
			}

			jQuery.post(ajaxurl, {
				pointer : pointer_id,
				action : 'dismiss-wp-pointer'
			});

			// Dissmiss this pointer.
			jQuery(this).attr('data-pointer-is-dismissed', 'yes');
			if (typeof pointer_index != 'undefined') {
				WPHelpPointer.pointers[pointer_index]['is-dismissed'] = 'yes';
			}
		}
	});

	jQuery(pointer.target).pointer(options).pointer('open');

	// Add a data attribute tho this element, data-pointer-id.
	// When we need to dismiss this element's pointer, we'll use
	// this attribute to get the appropriate id.
	jQuery(pointer.target).attr('data-pointer-id', pointer.pointer_id);
	jQuery(pointer.target).attr('data-pointer-is-dismissed', 'no');
	jQuery(pointer.target).attr('data-pointer-selector', pointer.target);
	jQuery(pointer.target).attr('data-pointer-index', i);
}