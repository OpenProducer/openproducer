<?php
// Prevent direct calls
if ( ! defined( 'WPINC' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}
?>
<script type="text/html" id="tmpl-editor-gallery-boldgrid-masonry">
 		<# if ( data.attachments ) { #>
			<div class='wc-gallery'>
			<div data-columns="{{ data.columns }}"  data-gutter-width="{{IMHWPBGallery.gutter_width}}"
				class="gallery gallery-masonry gallery-columns-{{ data.columns }} gallery-size-full wc-gallery-bottomspace-default wc-gallery-clear">
				<# _.each( data.attachments, function( attachment, index ) { #>
					<div class="gallery-item gallery-item-position-{{ index }} masonry-brick"
						>
						<div class="gallery-icon landscape">
							<img class="attachment-full"
								src="{{ attachment.thumbnail.url }}"
								width="{{ attachment.thumbnail.width }}"
								height="{{ attachment.thumbnail.height }}">
						</div>
					</div>
				<# }); #>
			</div>
			</div>
		<# } else { #>
			<div class="wpview-error">
				<div class="dashicons dashicons-format-gallery"></div><p><?php _e( 'No items found.' ); ?></p>
			</div>
		<# } #>
   </script>
