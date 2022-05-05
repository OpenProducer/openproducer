<div id="container_boldgrid_connection_notice" class="error">
	<h2 class="dashicons-before dashicons-admin-network">
		<?php esc_html_e( 'BoldGrid Connection Issue', 'boldgrid-library' ); ?>
	</h2>
	<p>
		<?php esc_html_e( 'There was an issue reaching the BoldGrid Connect server. Some BoldGrid features may be temporarily unavailable. Please try again in a moment.', 'boldgrid-library' ); ?>
	</p>
	<p>
		<?php printf(
			// translators: 1 The opening anchor tag to BoldGrid's status page, 2 its closing anchor tag.
			esc_html__( 'If the issue persists, then please feel free to check our %1$sBoldGrid Status%2$s page.', 'boldgrid-library' ),
			'<a target="_blank" href="https://www.boldgrid.com/">',
			'</a>'
		); ?>
	</p>
</div>
