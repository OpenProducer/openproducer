<div class="envato-claim-message">
	<p>
		<?php esc_html_e( 'Thank you for your Envato Market purchase', 'boldgrid-library' ); ?>
	</p>
	<p>
		<?php printf( esc_html__(
				// translators: 1 The opening anchor tag linking to BoldGrid Central's Envato page, 2 its closing anchor tag.
				'Please visit %1$sBoldGrid Central%2$s to link your accounts and claim your Premium Connect Key.',
				'boldgrid-library'
			),
			'<a target="_blank" href="https://www.boldgrid.com/central/code/envato">',
			'</a>'
		); ?>
	</p>
</div>
