<?php
// Prevent direct calls
if ( ! defined( 'WPINC' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}
?>
<script type="text/html" id="tmpl-editor-gallery-boldgrid-owlautowidth">
 		<# if ( data.attachments ) { #>
			<div class="gallery one-line-imhwpb">
				<# _.each( data.attachments, function( attachment, index ) { #>
					<div class="gallery-item" style="margin-right:{{IMHWPBGallery.gutter_width}}px">
						<dt class="gallery-icon">
							<# if ( attachment.thumbnail ) { #>
								<img src="{{ attachment.thumbnail.url }}" width="{{ attachment.thumbnail.width }}" height="{{ attachment.thumbnail.height }}" />
							<# } else { #>
								<img src="{{ attachment.url }}" />
							<# } #>
						</dt>
						<# if ( attachment.caption ) { #>
							<dd class="wp-caption-text gallery-caption">
								{{ attachment.caption }}
							</dd>
						<# } #>
					</div>
				<# }); #>
			</div>
		<# } else { #>
			<div class="wpview-error">
				<div class="dashicons dashicons-format-gallery"></div><p><?php _e( 'No items found.' ); ?></p>
			</div>
		<# } #>
</script>
