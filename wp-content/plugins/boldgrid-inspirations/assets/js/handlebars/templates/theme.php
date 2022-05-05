<?php
/**
 * theme.php
 *
 * This file included by author plugin.
 *
 * @link https://github.com/BoldGrid/boldgrid-author/blob/dev/pages/author.php#L33
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<script id="theme-template" type="text/x-handlebars-template">
	<select id='boldgrid_theme_id'>
		<option disabled selected>Choose a theme</option>
		{{#each this}}
			<option value='{{id}}'>{{name}}</option>
		{{/each}}
	</select>
</script>
