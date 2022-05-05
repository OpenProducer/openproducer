<?php
/**
 * BoldGrid Source Code
 *
 * Partial for unauthorized requests.
 *
 * @package Boldgrid_Inspirations
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

?>
<div id="boldgrid-unauthorized-request" class="notice notice-error is-dismissible">
	<p>
		<?php
		esc_html_e(
			'Unauthorized request.  You may not have the privileges/capabilities to perform this operation.',
			'boldgrid-inspirations'
		);
		?>
	</p>
</div>
