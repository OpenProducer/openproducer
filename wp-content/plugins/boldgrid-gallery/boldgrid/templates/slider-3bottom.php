<?php
// Prevent direct calls
if ( ! defined( 'WPINC' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}
?>
<script type="text/html" id="tmpl-editor-gallery-boldgrid-slider3">
 		<# if ( data.attachments ) { #>
			<div class="gallery">
				<# _.each( data.attachments, function( attachment, index ) { #>

					<# if ( data.attachments.length > 3 && index == 0 ) { #>
						<div class='one-line-imhwpb'>
							<dl class="gallery-item single-image-imhwpb">
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
							</dl>
						</div>
					<# } #>

				<# }); #>

				<# if ( data.attachments.length >= 1 ) { #>
						<# if ( data.attachments.length === 1 ) { #>
						<div class="gallery-item slider3bottomlinks-imhwpb" >
							<div class="gallery-icon">
								Missing Attachment
							</div>
						</div>
						<div class="gallery-item slider3bottomlinks-imhwpb" >
							<div class="gallery-icon">
								Missing Attachment
							</div>
						</div>
						<# } else if ( data.attachments.length === 2 ) { #>
						<div class="gallery-item slider3bottomlinks-imhwpb" >
							<div class="gallery-icon">
								Missing Attachment
							</div>
						</div>
						<# } #>
				<# _.each( data.attachments, function( attachment, index ) { #>
					<# if ( data.attachments.length - index <= 3 ) { #>
						<div class="gallery-item slider3bottomlinks-imhwpb" >
							<dt class="gallery-icon">
								<# if ( attachment.thumbnail ) { #>
									<img src="{{ attachment.thumbnail.url }}"  height="100%" />
								<# } else if (  attachment.url ) { #>
									<img src="{{ attachment.url }}" />
								<# } #>
							</dt>
							<# if ( attachment.caption ) { #>
								<dd class="wp-caption-text gallery-caption">
									{{ attachment.caption }}
								</dd>
							<# } #>
						</div>
					<# } #>
				<# }); #>
				<# } #>

			</div>
		<# } else { #>
			<div class="wpview-error">
				<div class="dashicons dashicons-format-gallery"></div><p><?php _e( 'No items found.' ); ?></p>
			</div>
		<# } #>
</script>
