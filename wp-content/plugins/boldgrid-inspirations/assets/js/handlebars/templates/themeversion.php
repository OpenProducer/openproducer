<?php
/**
 * themeversion.php
 *
 * This file included by author plugin.
 *
 * @link https://github.com/BoldGrid/boldgrid-author/blob/dev/pages/author.php#L35
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<script id="themeversion-template" type="text/x-handlebars-template">
	<select id='boldgrid_theme_version_type'>
		<option disabled selected>Choose a theme version</option>
		<option value='active'>Active</option>
		{{#if InProgressThemeRevisionId}}
				<option value='inprogress'>In Progress</option>
		{{/if}}
	</select>
</script>
