<?php
/**
 * preview.php
 *
 * This file included by author plugin.
 *
 * @link https://github.com/BoldGrid/boldgrid-author/blob/dev/pages/preview.php#L63-L66
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- TEMPLATES: text author -->

<script id="sub-cat-id-template" type="text/x-handlebars-template">
	<select id='boldgrid_sub_cat_id'>
		<option disabled selected>Choose a sub-category</option>
		{{#each categories}}
			{{#each subcategories}}
				<option value='{{id}}'>{{../name}} - {{name}} {{cat}}</option>
			{{/each}}
		{{/each}}
	</select>
</script>

<script id="text-author-sub-cat-id-template"
	type="text/x-handlebars-template">
	<select id='boldgrid_sub_cat_id'>
		<option disabled selected>Choose a sub-category</option>
			{{#each subcategories}}
				<option value='{{id}}'>{{../name}} - {{name}} {{cat}}</option>
			{{/each}}

	</select>
</script>

<script id="all-active-themes-template"
	type="text/x-handlebars-template">
	<select id='boldgrid_theme_id'>
		<option disabled selected>Choose a theme</option>
		{{#each this}}
				<option value='{{Id}}'>{{GroupTitle}} - {{Name}}</option>
		{{/each}}
	</select>
</script>

<script id="text-author-page-set-template"
	type="text/x-handlebars-template">
	<select id='boldgrid_page_set_id'>
				<option value='{{Id}}'>{{CategoryName}} - {{PageSetName}}</option>
	</select>
</script>

<!-- TEMPLATES: theme author -->

<script id="theme-author-theme-template" type="text/x-handlebars-template">
	<select id='boldgrid_theme_id'>
		<option value='{{Id}}' selected>{{GroupTitle}} - {{Name}}</option>
	</select>
</script>

<script id="theme-author-page-sets-template" type="text/x-handlebars-template">
	<select id='boldgrid_page_set_id'>
		<option disabled selected>Choose a Page set</option>
		{{#each this}}
			<option value='{{Id}}'>{{CategoryName}} - {{PageSetName}}</option>
		{{/each}}
	</select>
</script>

<script id="theme-author-sub-cat-id-template" type="text/x-handlebars-template">
	<select id='boldgrid_sub_cat_id'>
		<option disabled selected>Choose a sub-category</option>
			{{#each this}}
				<option value='{{Id}}'>{{CategoryName}}</option>
			{{/each}}

	</select>
</script>

<!--  TEMPLATES: misc -->

<script id="preview-container-template" type="text/x-handlebars-template">
	<div style='float:left; margin:15px;' id='{{build_container_id}}'>
		<table class='imhwpb'>
			<tr>
				<th>Page set:</th>
				<td>{{selected_page_set_text}}</td>
			</tr>
			<tr>
				<th>Sub category:</th>
				<td>{{selected_sub_cat_text}}</td>
			</tr>
			<tr>
				<th>Theme:</th>
				<td>{{selected_theme_text}}</td>
			</tr>
		</table>
		<a target='_blank' id='{{build_anchor_id}}'>
			<img id='{{build_thumbnail_id}}' src='https://placehold.it/267x200&text=loading...' style='height:200px;' />
		</a>
	</div>
</script>

<script id="submit-for-approval-template" type="text/x-handlebars-template">
	<hr />
	<div id='container_submit_for_approval'>
		<p>By clicking the <em>Submit for approval</em> button below, your changes will be flagged as needing review by an Editor.</p>
		<p><button id='submit_for_approval'>Submit for approval</button></p>
	</div>
</script>
