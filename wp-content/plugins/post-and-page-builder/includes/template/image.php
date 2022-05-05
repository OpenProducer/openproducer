<script type="text/html" id="tmpl-boldgrid-editor-image">
	<div class='choices image-design'>
		<# if ( data.myPresets.length ) { #>
			<div class='title'>
				<h4>My Designs</h4>
			</div>
			<div class='presets my-designs'>
				<ul>
				<# _.each( data.myPresets, function ( preset ) { #>
					<li data-preset="{{preset.classes}}" class="panel-selection">
						<img class='{{preset.classes}}' src="{{data.src}}">
					</li>
				<# }); #>
				</ul>
			</div>
		<# } #>
		<div class='title'>
			<h4>Sample Designs</h4>
		</div>
		<div class='presets supports-customization'>
			<ul>
			<# _.each( data.presets, function ( preset ) { #>
				<li data-preset="{{preset}}" class="panel-selection">
					<img class='{{preset}}' src="{{data.src}}">
				</li>
			<# }); #>
			</ul>
		</div>
	</div>
</script>
