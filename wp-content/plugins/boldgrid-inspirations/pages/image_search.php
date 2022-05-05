<?php
// Prevent direct calls.
require BOLDGRID_BASE_DIR . '/pages/templates/restrict-direct-access.php';

$boldgrid_admin_notices = new Boldgrid_Inspirations_Admin_Notices();

include BOLDGRID_BASE_DIR . '/pages/templates/image_search_results.php';
include BOLDGRID_BASE_DIR . '/pages/templates/attachment_details.php';
?>

<div class='media-frame imhwpb-media-frame mode-select wp-core-ui'>
	<div class="attachments-browser">
		<div class="media-toolbar">
			<div class="media-toolbar-secondary">
				<div>
					<strong><?php echo esc_html__( 'License filter', 'boldgrid-inspirations' ); ?>:</strong><br />
					<input type="checkbox" name='attribution' id='attribution' value='true' checked> <?php echo esc_html__( 'Attribution', 'boldgrid-inspirations' ); ?>
				</div>
			</div>
			<div class="media-toolbar-primary search-form">
				<form id='image_search'>
					<label class="screen-reader-text" for="media-search-input"><?php echo esc_html__( 'Search Media', 'boldgrid-inspirations' ); ?></label>
					<input class="search" id="media-search-input" placeholder="<?php echo esc_attr__( 'Search', 'boldgrid-inspirations' ); ?>" type="search" autofocus="autofocus">
					<input type='submit' class='button button-primary' value='<?php echo esc_attr__( 'Search', 'boldgrid-inspirations' ); ?>'  disabled />
					<input type='hidden' name='free' id='free' value="true" />
					<input type='hidden' name='paid' id='paid' value='true' />
					<input type='hidden' name='palette' id='palette' value='all' />
				</form>
			</div>
		</div>
		<ul id="search_results" class="attachments ui-sortable ui-sortable-disabled media-image-search-results" tabindex="-1">

			<?php
			/*
			 * Display a notice about possible explicit photos, only if the notice has not already
			 * been dismissed.
			 */
			if ( ! $boldgrid_admin_notices->has_been_dismissed( 'bgcs_license_info' ) ) {
			?>
			<div class="error notice is-dismissible boldgrid-admin-notice" data-admin-notice-id="bgcs_license_info">
				<ul class="fa-ul">
					<li>
						<p>
							<i class="fa-li fa fa-boldgrid" aria-hidden="true"></i>
							<?php echo esc_html__( 'Indicates a purchasable image that comes with a license.', 'boldgrid-inspirations' ); ?>
						</p>
					</li>
					<li>
						<p>
							<i class="fa-li fa fa-globe" aria-hidden="true"></i>
							<?php
								printf(
									wp_kses(
										// translators: 1 URL to https://creativecommons.org/about/.
										__( 'Indicates an image marked by the publisher as <a href="%1$s" target="_blank">Creative Commons</a>, but it is not a guarantee it is legally Creative Commons.  Those images may be subject to other copyrights.  You, as the website owner, are responsible for content on your site.', 'boldgrid-inspirations' ),
										array( 'a' => array( 'href' => array(), 'target' => array() ) )
									),
									'https://creativecommons.org/about/'
								);
							?>
						</p>
					</li>
				</ul>
				<hr />
				<p>
					<?php esc_html_e( 'While we\'ve tried our best to filter out any explicit images in search results, we cannot guarantee the content of all images in your search results.', 'boldgrid-inspirations' ); ?>
				</p>
			</div>
			<?php
			}
			?>

			<input type='hidden' id='currently_searching' value='0' />
		</ul>
		<div id='attachment_details' class="media-sidebar visible"></div>
	</div>
</div>
