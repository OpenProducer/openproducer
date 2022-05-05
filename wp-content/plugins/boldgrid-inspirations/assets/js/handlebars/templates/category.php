<?php
/**
 * category.php
 *
 * This file included by author plugin.
 *
 * @link https://github.com/BoldGrid/boldgrid-author/blob/dev/pages/author.php#L30
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<script id="category-template" type="text/x-handlebars-template">
	<select id='boldgrid_cat_id'>
		<option disabled selected>Choose a category</option>
		{{#each categories}}
			<option value='{{id}}'>{{name}}</option>
		{{/each}}
	</select>
</script>
