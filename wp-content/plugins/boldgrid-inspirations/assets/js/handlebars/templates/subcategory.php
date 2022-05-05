<?php
/**
 * subcategory.php
 *
 * This file included by author plugin.
 *
 * @link https://github.com/BoldGrid/boldgrid-author/blob/dev/pages/author.php#L31
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<script id="subcategory-template" type="text/x-handlebars-template">
	<select id='boldgrid_sub_cat_id'>
			<option disabled selected>Choose a sub-category</option>
			{{#each subcategories}}
				<option value='{{id}}'>{{name}} {{cat}}</option>
			{{/each}}
	</select>
</script>
