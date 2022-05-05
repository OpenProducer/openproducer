<?php

if ( ! defined( 'WPINC' ) ) {
	die();
}
?>
<div class="media-sidebar media-sidebar-boldgrid visible">
	<div class='hidden'>
		<h3>Details</h3>
		<div class="boldgrid-markup-container"></div>
		<div id='instance-options-boldgrid'>
			<div class='editform-link'>
				<a target="_parent" href="#">Edit Form</a>&nbsp;&nbsp;&nbsp; <a
					target="_parent"
					href="<?php echo get_admin_url() . 'admin.php?page=wpforms-builder'; ?>">New
					Form</a>
			</div>
			<div class="inputgroup-boldgrid">
				<input type="checkbox" checked="" id="title-toggle-boldgrid"
					value="true" name="title"> <label for="title-toggle-boldgrid">Display
					form title</label>
			</div>
			<div class="inputgroup-boldgrid">
				<input type="checkbox" checked="" value="true"
					id="description-enable-boldgrid" name="description"> <label
					for="description-enable-boldgrid">Display form description</label>
			</div>
			<div class="inputgroup-boldgrid ajax-enable-boldgrid">
				<input type="checkbox" value="true" id="ajax-enable-boldgrid"
					name="ajax"> <label for="ajax-enable-boldgrid">Enable AJAX </label>
			</div>
		</div>
		<div id="advanced-options">
			<a href="#" title="advanced-options">Advanced Options</a>
			<div id='tabindex-wrapper-boldgrid' class='hidden'>
				<div class="inputgroup-boldgrid">
					<div class="field-block">
						<label>Tabindex</label> <br> <input type="number" value=""
							name="tabindex">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
