<script type="text/html" id="tmpl-suggest-crop">
	<div class='container-crop'>

		<div class='left'>

			<img class='suggest-crop' src="{{{data.newContentSrc}}}" draggable='false' />

		</div>

		<div class='media-sidebar'>

			<div class='imgedit-group'>
				<div class='imgedit-group-top'>
					<h2>
						Image Crop
						<a href='#' class='dashicons dashicons-editor-help' onclick='imageEdit.toggleHelp(this);return false;'></a>
					</h2>
					<p class='imgedit-help'>To crop the image, drag to make your selection and then click "Crop Image".</p>
				</div>
				<p>The image you are replacing has a different aspect ratio than the new image you have chosen:</p>
				<img src='{{{data.oldImageSrc}}}' draggable='false' />
				<img src='{{{data.newImageSrc}}}' draggable='false' />
				<div class='clear'></div>
				<p>You can crop the image before inserting it into your page, or choose not to crop by clicking <em>Skip Cropping</em>.</p>
			</div>

			<div class='imgedit-group'>
				<div class='imgedit-group-top'>
					<h2>
						<input type='checkbox' name='force-aspect-ratio' checked>
						<span id='toggle-force'>Force Aspect Ratio</span>
						<a href='#' class='dashicons dashicons-editor-help' onclick='imageEdit.toggleHelp(this);return false;'></a>
					</h2>
					<p class='imgedit-help'>You can maintain consistency in your page's design by forcing the aspect ratio of the image you are replacing. This ensures your cropped image keeps the same shape as the image it's replacing.</p>
				</div>
			</div>

			<div class='imgedit-group imgedit-source'>
				<div class='imgedit-group-top'>
					<h2 id='source'>
						Source Image
						<a href='#' class='dashicons dashicons-editor-help' onclick='imageEdit.toggleHelp(this);return false;'></a>
					</h2>
					<p class='imgedit-help'>You can choose to crop any size available for this image. Choosing a larger image will yield a higher quality cropped image.</p>
				</div>
				<p></p>
			</div>

		</div>

		<div class='clear'></div>
	</div>
</script>
<script type="text/html" id="tmpl-suggest-crop-toolbar">
	<div class="media-toolbar">
		<div class="media-toolbar-primary search-form">
			<button type="button" class="button media-button button-secondary button-large media-button-skip">Skip Cropping</button>
			<button type="button" class="button media-button button-primary button-large media-button-select" data-default-text='Crop Image'>Crop Image</button>
		</div>
	</div>
</script>
<script type="text/html" id="tmpl-suggest-crop-compare">
	<div class='comparing'>
		<span class="spinner inline"></span> <strong>Reviewing aspect ratios<strong>...
	</div>
</script>
<script type="text/html" id="tmpl-suggest-crop-match">
	<div class='comparing'>
		<span class="dashicons dashicons-yes"></span> <strong>Aspect ratios match</strong>! Replacing image...
	</div>
</script>
<script type='text/html' id='tmpl-suggest-crop-invalid'>
	<p class='crop-invalid'>
		There was an error cropping your image. Please click <em>OK</em> to insert the uncropped image. <button class='button media-button button-primary button-large crop-fail'>OK</button>
	</p>
</script>
<script type='text/html' id='tmpl-suggest-crop-sizes'>
	<select id='suggest-crop-sizes'>
		<#	var newContentSrc = data.newContentSrc;
			_.forEach( data.sizes, function (u,i) {
			var optionValue = u.url;
			var optionText = i + ' - ' + u.width + ' × ' + u.height;
			var selected = ( newContentSrc == optionValue ? 'selected' : '' );#>
			<option value='{{{optionValue}}}' data-width='{{{u.width}}}' data-height='{{{u.height}}}' {{{selected}}}>{{{optionText}}}</option>
		<#}); #>
	</select>
</script>