<?php
// Prevent direct calls.
require BOLDGRID_BASE_DIR . '/pages/templates/restrict-direct-access.php';

// Wrap the entire page within a '.wrap'.
echo '<div class="wrap">';

// get all the data we need to print the page
$args = array (
	'process_checked_in_cart_attribute' => false,
);

$data = $this->get_all_data_of_assets_needing_purchase( $args );

$have_assets_needing_purchase = isset( $data['assets_needing_purchase']['by_page_id'] );

// Get the user's current copyright coin balance.
$current_copyright_coin_balance = $this->get_current_copyright_coin_balance();

// Validate balance.
if ( ! is_numeric( $current_copyright_coin_balance ) ) {
	$current_copyright_coin_balance = '?';
}

// Include the navigation:
include BOLDGRID_BASE_DIR . '/pages/includes/cart_header.php';

/**
 * ****************************************************************************
 * Define a few templates.
 * ****************************************************************************
 */

/*
 * Template: Page header.
 * This is the header that includes the page title, "edit | view" links, etc.
 */
$page_header_template = '
<div class="container-fluid" data-page-id="%s">
	<div class="plugin-card boldgrid-plugin-card-full-width">
		<div class="plugin-card-top">
			<div class="row">
				<div class="col-md-12">
					<a href="%s" class="row-title">%s</a>
					<div class="row-actions">
						<a href="%s">' . esc_html( 'Edit', 'boldgrid-inspirations' ) . '</a>  | <a href="%s">' . esc_html( 'View', 'boldgrid-inspirations' ) . '</a>
					</div>
				</div>
			</div>
';

$page_header_template_end = '
		</div>
		<div class="plugin-card-bottom">
			<div class="column-updated">
				' . esc_html__( 'Page coin cost', 'boldgrid-inspirations' ) . ': <span class="total-page-cost-%s" data-total-page-cost="%s">%u
			</div>
		</div>
	</div>
</div>
';

/*
 * Template: imagin container
 * This is the container of the image, which holds the thumbnail / dimensions / checkbox / etc.
 */
$image_template = '
<div class="col-md-3 %s">
	<div>
		<img src="%s" class="img-responsive image-thumbnail" />
		<div class="image-info">
			<div class="image-dimensions">%u x %u</div>
			<div class="coin-bg-s">%u ' . esc_html__( 'Coins', 'boldgrid-inspirations' ) . '</div>
		</div>
		<input type="checkbox" class="image-select" data-coin-cost="%u" data-asset-id="%u" %s>
		<div class="clear:both;"></div>
	</div>
</div>
';

/**
 * ****************************************************************************
 * If we have items that need to be purchased:
 * ****************************************************************************
 */
if ( $have_assets_needing_purchase ) {
	?>
<form method="post" name="purchase_for_publish"
	id="purchase_for_publish">
	<?php

	echo wp_nonce_field( 'purchase_for_publish' );

	/**
	 * ************************************************************************
	 * Loop through each page.
	 * ************************************************************************
	 */
	foreach ( $data['assets_needing_purchase']['by_page_id'] as $post_id => $assets ) {
		// Set the price it will cost to purchase all images for this page.
		$total_coin_cost_for_only_this_page = 0;

		// Get the link to edit this post.
		if ( is_numeric( $post_id ) ) {
			$link_edit_post = get_edit_post_link( $post_id );
		} else {
			$link_edit_post = get_admin_url( null, 'customize.php' );
		}

		// Get the link to view the post.
		if ( is_numeric( $post_id ) ) {
			$link_view_post = get_page_link( $post_id );
		} else {
			$link_view_post = get_site_url();
		}

		// How many columns are in the bootstrap row?
		$grid_column_count = 0;

		// Have we closed the bootstrap row?
		$grid_row_closed = true;

		// Print the header for this page.
		echo sprintf( $page_header_template,
			// post id
			( is_numeric( $post_id ) ) ? $post_id : str_replace( ' ', '-', $post_id ),
			// link to edit the post
			$link_edit_post,
			// post title
			$data['assets_needing_purchase']['page_data'][$post_id]['post_title'],
			// link to edit the post
			$link_edit_post,
			// link to view the post
			$link_view_post );

		/**
		 * ********************************************************************
		 * Loop through each individual asset and print it.
		 * ********************************************************************
		 */
		foreach ( $assets as $asset_key => $asset ) {
			// If this is the first column in the row, we need to start a new row.
			if ( 0 == $grid_column_count ) {
				?>
				<div class="row">
				<?php
				$grid_row_closed = false;
			}

			/**
			 * Has this image been previously 'checked' or unchecked?
			 */
			// Unchecked, don't buy it.
			if ( isset( $asset['checked_in_cart'] ) and ! $asset['checked_in_cart'] ) {
				$unselected_image = 'unselected-image';
				$checked = '';
			} else {
				$unselected_image = '';
				$checked = 'checked';
				$total_coin_cost_for_only_this_page += $asset['coin_cost'];
			}

			// Print the container holding the image, dimensions, etc.
			echo sprintf( $image_template,
				// css class for the image container
				$unselected_image,
				// thumbnail url to image
				$asset['thumbnail_url'],
				// width of the image
				$asset['attachment_metadata']['sizes']['full']['width'],
				// heigh of the image
				$asset['attachment_metadata']['sizes']['full']['height'],
				// coin cost of the image
				$asset['coin_cost'],
				// coin cost of the image (x2)
				$asset['coin_cost'],
				// asset id
				$asset['asset_id'],
				// should the checkbox be auto checked?
				$checked );

			// Increment the $grid_coloumn_count.
			// If we've printed all our columns, close the row
			$grid_column_count += 3;
			if ( $grid_column_count >= 12 ) {
				$grid_column_count = 0;
				$grid_row_closed = true;
				?>
				</div>
				<?php
			}
		}

		/**
		 * ********************************************************************
		 * Close the page container.
		 * ********************************************************************
		 */

		// If we haven't closed the grid row, do so now.
		if ( false == $grid_row_closed ) {
			?>
			</div>
			<?php
			$grid_row_closed = true;
		}

		// After printing all of the assets, print the total cost of the page.
		echo sprintf( $page_header_template_end,
			// post id
			( is_numeric( $post_id ) ) ? $post_id : str_replace( ' ', '-', $post_id ),
			// total cost for all the images on this page
			$total_coin_cost_for_only_this_page,
			// total cost for all the images on this page
			$total_coin_cost_for_only_this_page );
	}

	/**
	 * ************************************************************************
	 * We have finished looping through each page.
	 *
	 * Now it's time to print items under the pages, such as:
	 * * Balance
	 * * Insufficient funds notification
	 * * BoldGrid Connect Key input
	 * * Agreement of terms & conditions
	 * ************************************************************************
	 */
	?>

<hr />

	<div class="container-fluid cart-summary">
		<div class="row">
			<div class="col-md-6 col-md-offset-6">
				<div class="plugin-card boldgrid-plugin-card-full-width">
					<div class="plugin-card-top">
						<table style='width: 100%;'>
							<tr>
								<td><?php echo esc_html__( 'Your Copyright Coin balance', 'boldgrid-inspirations' ); ?>:</td>
								<td><span class='coin-bg-s .coin-balance'
									data-coin-balance='<?php echo $current_copyright_coin_balance; ?>'><?php echo $current_copyright_coin_balance; ?></span></td>
							</tr>
							<tr>
								<td><?php echo esc_html__( 'Total coin cost', 'boldgrid-inspirations' ); ?>:</td>
								<td><div class='coin-bg-s total_cost'
										data-total-cost='<?php echo $data['total_cost']; ?>'><?php echo $data['total_cost']; ?></div></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div
		class="container-fluid cart-summary insufficient-funds hidden text-right">
		<div class="row">
			<div class="col-md-6 col-md-offset-6">
				<div class="plugin-card boldgrid-plugin-card-full-width error inline">
					<div class="plugin-card-top">
						<?php printf(
							wp_kses(
								// translators: 1 opening anchor tag, a link to purchase more coins. 2 The closing anchor tag.
								__( 'Whoops! It looks like you\'ll need more Coins for this transaction. You can remove images or %1$sPurchase More Coins%2$s.', 'boldgrid-inspirations' ),
								array( 'a' => array( 'href' => array() ), )
							),
							'<a href="' . admin_url( 'admin.php?page=boldgrid-purchase-coins') . '">',
							'</a>'
						); ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid cart-summary text-right">
		<div class="row">
			<div class="col-md-6 col-md-offset-6">
				<span style="width:30%;">
					BoldGrid Connect Key:
				</span>
				<input style="width:68%;" maxlength="37" type="text" name='boldgrid_connect_key' id='boldgrid_connect_key' placeholder="XXXXXXXX - XXXXXXXX - XXXXXXXX - XXXXXXXX" autocomplete='off' />
				<div>
					<a href='https://www.boldgrid.com/support/where-to-get-a-boldgrid-connect-key/' target='_blank'><?php echo esc_html__( 'Lost your BoldGrid Connect Key?', 'boldgrid-inspirations' ); ?></a>
				</div>
				<br />
				<input type="checkbox" name="agree_to_tos" id="agree_to_tos" value="yes">
				<?php
					printf(
						wp_kses(
							// translators: 1 a link to the BoldGrid TOS, 2 a link to the 123RF TOS.
							__( 'I agree to the %1$s and %2$s Terms and Conditions.', 'boldgrid-inspirations' ),
							array( 'a' => array( 'href' => array(), 'target' => 'blank' ) )
						),
						'<a href="https://www.boldgrid.com/tos" target="_blank">BoldGrid</a>',
						'<a href="https://www.123rf.com/terms.php" target="_blank">123RF</a>'
					);
				?>
			</div>
		</div>
	</div>

	<div class="container-fluid cart-summary text-right">
		<div class="row">
			<div class="col-md-6 col-md-offset-6">
				<span name='purchase_error' id='purchase_error' style='color: red;'></span>
				<p>
					<button class='button purchase-more-coins' id='purchase-more-coins'><?php echo esc_html__( 'Purchase More Coins', 'boldgrid-inspirations' ); ?></button>
					<button class='button button-primary' id='purchase_all_for_publishing'
						<?php
	if ( ! is_numeric( $current_copyright_coin_balance ) ||
	$current_copyright_coin_balance < $data['total_cost'] ) {
		echo 'disabled="disabled"';
	}
	?>><?php echo esc_html__( 'Purchase for Publishing', 'boldgrid-inspirations' ); ?></button>
				</p>
			</div>
		</div>
	</div>

	<input type='hidden' name='task' value='purchase_all' />

</form>
<?php
/**
 * ****************************************************************************
 * If we do not have items that need to be purchased:
 * ****************************************************************************
 */
} else {
	?>
<p><?php echo esc_html__( 'There are currently no assets needing purchase.', 'boldgrid-inspirations' ); ?></p>
<?php
}

// The entire page is wrapped within a '.wrap'. Close that div now.
echo '</div>';