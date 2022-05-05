<?php
/**
 * deploy.php
 *
 * This file renders the actual deploy page.
 */

// Prevent direct calls.
require BOLDGRID_BASE_DIR . '/pages/templates/restrict-direct-access.php';
?>

<script type="text/javascript">
	/**
	 * Scroll the user to the bottom of the page.
	 *
	 * @since 1.7.0
	 */
	function scrollToBottom() {
		jQuery( "html, body" )
			.stop()
			.animate( { scrollTop: jQuery( document ).height() }, 2000 );
	}
</script>

<style>
ul#deploy_log {
	list-style-position: inside;
	list-style-type: disc;
	font-family: "Courier New", Courier, monospace;
	line-height: 11px;
}

/* When deployment is installing a theme, it prints the details of the theme on the screen. This css
   hides those details. */
.wrap:not(.main) {
 	display: none;
}

#deploy_status .spinner {
	visibility: visible;
	float: none;
	margin: 0px 5px 0px 0px;
}

#deploy_text {
	font-style: italic;
}

#deploy_status .boldgrid-loading {
	display: inline-block;
	margin: 15px 0px 20px 0px;
}

h1 .dashicons.dashicons-yes {
	color: green;
	font-size: 30px;
	padding-right: 15px;
}
</style>

<div class="wrap main">

	<?php
	$active_menu_item = 'install';
	require_once BOLDGRID_BASE_DIR . '/pages/includes/boldgrid-inspirations/menu.php';
	?>

	<div name='deploy_status' id='deploy_status' class='screen-contained'>

		<div style="text-align:center;">
			<h1><?php echo esc_html__( 'Installing...', 'boldgrid-inspirations' ); ?> <span class='spinner'></span></h1>
		</div>

<?php
add_shortcode( 'imhwpb', array (
	'imhwpbDeploy',
	'dummy_shortcode_imhwpb'
) );

$new_deploy = new Boldgrid_Inspirations_Deploy( $this->get_configs() );
$new_deploy->do_deploy();
