var IMHWPB = IMHWPB || {};

IMHWPB.TransactionHistory = function( configs, $ ) {
	var self = this;

	this.configs = configs;
	this.api_url = this.configs.asset_server;
	this.api_key = this.configs.api_key;

	this.api_param = 'key';
	this.api_key_query_str = this.api_param + '=' + this.api_key;

	// Include additional submodules.
	self.ajax = new IMHWPB.Ajax( configs );
	self.baseAdmin = new IMHWPB.BaseAdmin();

	$c_wpbody = jQuery( '#wpbody' );

	self.pagination_per_page = 10;

	self.lang = BoldGridReceipts;

	/**
	 * "jQuery(function() {" is the shorthand for "$( document ).ready()".
	 *
	 * Code included inside $( document ).ready() will only run once the page.
	 * Document Object Model (DOM) is ready for JavaScript code to execute.
	 */
	jQuery( function() {

		// Declare vars.
		var $transactions, transaction_id, transaction;

		// Declare a context selector for transactions.
		$transactions = jQuery( '#transactions', $c_wpbody );

		// If the asset server is not available, then abort with an admin notice.
		if ( ! connectionInfo.assetServerAvailable ) {
			$transactions.html( connectionInfo.connectionErrorMessage );

			return;
		}

		// Get the user's transaction history.
		self.ajax.ajaxCall( {}, 'get_transaction_history', self.get_transaction_history_successAction );

		// A user has clicked "view" next to a transaction.
		$transactions.on( 'click', '.view', function() {
			transaction_id = jQuery( this ).data( 'transaction-id' );
			transaction = null;
			jQuery.each( transactions, function() {
				if ( this.transaction_id === transaction_id ) {
					transaction = this;
				}
			} );

			self.show_receipt( transaction );

			return false;
		} );

		// A user clicks on "Download Image", attempting to re-download-purchased-image.
		jQuery( document ).on( 'click', 'a.re-download-purchased-image', function() {
			self.process_click_re_download( this );
		} );

		// Sort the receipts table by date.
		jQuery( document ).on( 'click', '.sort-date', function() {
			self.baseAdmin.sort_table_column( this );

			jQuery( '#transactions table' ).style_wp_table();

			return false;
		} );
	} );

	/**
	 * Bind pagination links.
	 */
	this.bind_pagination_go_to_page_links = function( anchor ) {
		var page_to_toggle,
			$anchor = jQuery( anchor );

		// Remove current class from all siblings.
		$anchor
			.closest( 'span' )
			.find( 'a' )
			.removeClass( 'current' );

		$anchor.addClass( 'current' );

		page_to_toggle = $anchor.attr( 'data-page' );

		self.pagination_toggle_rows( page_to_toggle );
	};

	/**
	 * Get transaction history success action.
	 *
	 * After getting transaction history from server, display it in a table using handlebars.
	 */
	this.get_transaction_history_successAction = function( msg ) {

		// Declare var.
		var source, template, transaction_count;

		// If the asset server is not available, then abort with an admin notice.
		if ( ! msg.status || 200 !== msg.status ) {
			jQuery( '#transactions', $c_wpbody ).html( connectionInfo.connectionErrorMessage );

			return;
		}

		transactions = msg.result.data.transactions;

		// Determine which template to use based on the number of transactions.
		if ( ! Object.keys( transactions ).length ) {
			source = jQuery( '#no-transactions-template' ).html();
		} else {
			source = jQuery( '#transactions-template' ).html();
		}

		template = Handlebars.compile( source );
		jQuery( '#transactions', $c_wpbody ).html( template( msg.result.data ) );
		jQuery( '#transactions table' ).style_wp_table();

		// Update the div above / below the table.
		transaction_count = Object.keys( transactions ).length;
		source = jQuery( '#tablenav-top-template' ).html();
		template = Handlebars.compile( source );
		jQuery( 'div.tablenav.top', $c_wpbody ).html( template( transaction_count ) );
		jQuery( 'div.tablenav.bottom', $c_wpbody ).html( template( transaction_count ) );

		self.$table_trs = jQuery( 'table#receipts tbody tr' );
		self.$tablenav_pages = jQuery( '.tablenav-pages' );

		// Setup pagination.
		if ( transaction_count > self.pagination_per_page ) {
			self.setup_pagination();
		}
	};

	/**
	 * start = the page number to show.
	 */
	this.pagination_toggle_rows = function( show_page ) {

		// Define vars.
		var starting_index;

		// Define our transactions (each tr of table).
		$table_trs = jQuery( 'table#receipts tbody tr' );

		// Hide all tr's.
		$table_trs.addClass( 'hidden' );

		starting_index = self.pagination_per_page * show_page - self.pagination_per_page;

		// Then show only the one's we want.
		$table_trs
			.slice( starting_index, starting_index + self.pagination_per_page )
			.removeClass( 'hidden' );
	};

	/**
	 * Process user's click of "Download Image".
	 */
	this.process_click_re_download = function( link ) {

		// Declare vars.
		var spinner,
			id_from_provider,
			image_provider_id,
			user_transaction_item_id,
			deferred,
			fail,
			$td = $( link.closest( 'td' ) ),
			$this = jQuery( link );

		// Add a spinner to show this image is being redownloaded.
		spinner = '<span class=\'spinner\' style=\'visibility:visible; float:none;\'></span>';
		$this.replaceWith( spinner );

		// If there is already a download attempt, abort.
		if ( self.downloading ) {
			return;
		}

		self.downloading = true;

		// Download the image.
		id_from_provider = $this.data( 'id-from-provider' );
		image_provider_id = $this.data( 'image-provider-id' );
		user_transaction_item_id = $this.data( 'user-transaction-item-id' );
		deferred = self.re_download_purchased_image(
			image_provider_id,
			id_from_provider,
			user_transaction_item_id
		);

		// If the image download fails.
		fail = function() {
			$td.html( self.lang.notAvailable );
		};

		// If the image download is successful.
		deferred
			.done( function( response ) {

				// Decalre vars.
				var view_image_link,
					response = JSON.parse( response );

				if ( response.attachment_id ) {
					$td.html(
						'<a href=\'post.php?post=' +
							response.attachment_id +
							'&action=edit\'>' +
							self.lang.viewImage +
							'</a>'
					);
				} else {
					fail();
				}
			} )
			.fail( fail )
			.always( function() {
				self.downloading = false;
			} );
	};

	/**
	 *
	 */
	this.re_download_purchased_image = function(
		image_provider_id,
		id_from_provider,
		user_transaction_item_id
	) {
		var data = {
			action: 're_download_purchased_image',
			image_provider_id: image_provider_id,
			id_from_provider: id_from_provider,
			user_transaction_item_id: user_transaction_item_id
		};

		return jQuery.post( ajaxurl, data, function( response ) {

			// todo: What do we do when image has been re-downloaded
			// successfully?!
			try {
				response = JSON.parse( response );
			} catch ( e ) {
				console.log( 'fail' );

				// error parsing string as jquery
				return;
			}
		} );
	};

	/**
	 *
	 */
	this.setup_pagination = function() {

		// Show the first page
		self.pagination_toggle_rows( 1 );

		// Add the pagination selector.
		self.setup_pagination_selector();
	};

	/**
	 * Creating the pagination links, like:
	 *
	 * 1 | 2 | 3 | 4
	 */
	this.setup_pagination_selector = function() {

		// Calculate the number of pages
		var pagination_pages_count = Math.ceil(
			jQuery( self.$table_trs ).size() / self.pagination_per_page
		);

		// If we only have 1 page, abort, that's not pagination silly.
		if ( 1 == pagination_pages_count ) {
			return;
		}

		// Create the html.
		var pagination_html = '<span class=\'pagination-links\'>';
		var current_class = '';
		for ( i = 1; i <= pagination_pages_count; i++ ) {
			if ( 1 == i ) {
				current_class = 'current';
			} else {
				current_class = '';
			}

			var pagination_link =
				'<a class=\'pointer pagination-go-to-page ' +
				current_class +
				'\' data-page=\'' +
				i +
				'\'>' +
				i +
				'</a>';

			if ( 1 == i ) {
				pagination_html += pagination_link;
			} else {
				pagination_html += '|' + pagination_link;
			}
		}
		pagination_html += '</span>';

		// Put the html in place.
		var existing_html = self.$tablenav_pages.html();
		self.$tablenav_pages.html( existing_html + pagination_html );

		// bind the new pagination page selectors
		jQuery( '.pagination-go-to-page' ).on( 'click', function() {
			self.bind_pagination_go_to_page_links( this );
		} );
	};

	/**
	 * Pass a transaction object, and we will display it using handlebars. Our
	 * handlebars template will loop through each transaction_item and print it
	 * to a table.
	 */
	this.show_receipt = function( transaction ) {
		var source = jQuery( '#transaction-template' ).html();
		var template = Handlebars.compile( source );
		jQuery( '#transaction', $c_wpbody ).html( template( transaction ) );
		jQuery( '#transaction table', $c_wpbody ).style_wp_table();
		tb_show( 'Invoice', '#TB_inline?inlineId=transaction&modal=false', true );

		self.update_receipt_for_stock_photo_purchase();
	};

	/**
	 * Provide new jQuery instance methods
	 *
	 * @link http://api.jquery.com/jquery.fn.extend/
	 */
	jQuery.fn.extend( {

		/**
		 * Stylize a wordpress table.
		 *
		 * Currently only adds the 'alternate' css class to every other tr
		 */
		style_wp_table: function() {

			// Reset the table by removing the 'alternate' class from every
			// element.
			jQuery( this ).each( function() {
				jQuery( this )
					.children( 'tbody' )
					.children()
					.removeClass( 'alternate' );
			} );

			jQuery( this ).each( function() {
				jQuery( this )
					.children( 'tbody' )
					.children( ':even' )
					.addClass( 'alternate' );
			} );
			return this;
		}
	} );

	/**
	 * Update descriptions of "Stock Photo Purchase" to show details of the
	 * image.
	 */
	this.update_receipt_for_stock_photo_purchase = function() {

		/*
		 * Get all the td's where the description is "Stock Photo Purchase".
		 *
		 * String is not localized as it is coming from the API server, which currently does not
		 * have this support.
		 */
		var tds_of_stock_photo_purchase = jQuery(
			'div#TB_window div#TB_ajaxContent table tbody tr td:contains("Stock Photo Purchase")'
		);

		// loop through each of those td's
		jQuery( tds_of_stock_photo_purchase ).each( function( index ) {
			var this_td = jQuery( this ).get( 0 );

			// get the user-transaction-item-id
			var user_transaction_item_id = jQuery( this )
				.closest( 'tr' )
				.data( 'user-transaction-item-id' );

			// get the details of this image (like thumbnail
			// url)
			var data = {
				action: 'get_purchased_image_details',
				transaction_item_id: user_transaction_item_id
			};

			var success_action = function( response ) {

				// Get the td that will hold the thumbnail.
				$thumbnail_td = $( this_td )
					.closest( 'tr' )
					.find( '.thumbnail' );

				try {
					response = JSON.parse( response );
				} catch ( e ) {

					// Assume we have an error message, such as, "Unable to get image details".
					$thumbnail_td.text( response );
					return;
				}

				// Get the td that will hold the re-download
				// link.
				$redownload_td = jQuery( this_td )
					.closest( 'tr' )
					.find( '.redownload' );

				switch ( response.data_type ) {
					case 'local_data':
						var thumbnail_html = '<img src=\'' + response.sizes.thumbnail.url + '\' />';
						var view_in_gallery_link =
							'<a href=\'' + response.editLink + '\'>' + self.lang.viewImage + '</a>';
						$thumbnail_td.html( thumbnail_html );
						$redownload_td.html( view_in_gallery_link );
						break;
					case 'local_library_data':
						var thumbnail_html = '<img src=\'' + response.sizes.thumbnail.url + '\' />';
						var view_in_gallery_link =
							'<a href=\'post.php?post=' +
							response.attachment_id +
							'&action=edit\'>' +
							self.lang.viewImage +
							'</a>';
						$thumbnail_td.html( thumbnail_html );
						$redownload_td.html( view_in_gallery_link );
						break;
					case 'remote_data':
						var thumbnail_html = '<img src=\'' + response.thumbnail_url + '\' />';
						var download_image_link =
							'<a data-image-provider-id=\'' +
							response.image_provider_id +
							'\' data-id-from-provider=\'' +
							response.id_from_provider +
							'\' data-user-transaction-item-id=\'' +
							user_transaction_item_id +
							'\' class=\'re-download-purchased-image pointer\'>' +
							self.lang.download +
							'</a>';
						$thumbnail_td.html( thumbnail_html );
						$redownload_td.html( download_image_link );
						break;
				}
			};
			jQuery.post( ajaxurl, data, success_action );
		} );
	};
};

new IMHWPB.TransactionHistory( IMHWPB.configs, jQuery );
