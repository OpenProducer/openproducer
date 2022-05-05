<!-- User has free key entered and should claim their free key -->
<div class="envato-claim-message">
	<p>
		<?php esc_html_e( 'Thank you for your Envato Market purchase. You currently have a Free Connect Key entered, but your Envato purchase entitles you to a Premium Connect Key.', 'boldgrid-library' ); ?>
	</p>
	<p>
		<?php printf(
			// translators: 1 The opening anchor tag linking to BoldGrid Central's Envato page, 2 its closing anchor tag.
			__( 'Please visit %1$sBoldGrid Central%2$s to link your accounts and claim your Premium Connect Key.', 'boldgrid-library' ),
			'<a target="_blank" href="https://www.boldgrid.com/central/code/envato">',
			'</a>'
		); ?>
	</p>
</div>
