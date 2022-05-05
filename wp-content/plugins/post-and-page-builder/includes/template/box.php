<script type="text/html" id="tmpl-boldgrid-editor-box">
	<div class='box-design'>
		<# if ( data.myPresets.length ) { #>
		<div class='title my-designs-title'>
			<h4>My Designs</h4>
		</div>
		<div class='my-designs presets grid'>
			<# _.each( data.myPresets, function ( preset, index ) { #>
				<div data-id='{{index}}' data-value='{{preset.classes}}' class='{{preset.classes}}'></div>
			<# }); #>
		</div>
		<# } #>
		<div class='title'>
			<h4>Sample Designs</h4>
		</div>
		<div class='presets grid'>
			{{{data.presets}}}
		</div>
		<div class='customize'>
			<div class='back'>
				<a class='panel-button' href="#"><i class="fa fa-chevron-left" aria-hidden="true"></i> Preset Designs</a>
			</div>
		</div>
	</div>
</script>
