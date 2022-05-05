<?php
/**
 * themegroup.php
 *
 * This file included by author plugin.
 *
 * @link https://github.com/BoldGrid/boldgrid-author/blob/dev/pages/author.php#L32
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<script id="theme-group-template" type="text/x-handlebars-template">
	<select id='theme_group'>
		<option disabled selected>Choose a group</option>
		{{#each this}}
			<option value='{{id}}'>{{title}}</option>
		{{/each}}
	</select>
</script>
