<?php
/**
 * language.php
 *
 * This file included by author plugin.
 *
 * @link https://github.com/BoldGrid/boldgrid-author/blob/dev/pages/author.php#L34
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<script id="language-template" type="text/x-handlebars-template">
	<select id='language_id'>
			<option disabled selected>Choose a language</option>
			{{#each this}}
				<option value='{{language_id}}'>{{name}}</option>
			{{/each}}
	</select>
</script>
