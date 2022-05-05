<?php
/**
 * File: Connect.php
 *
 * @package    Boldgrid\Library
 * @subpackage Library\Views
 * @version    2.4.0
 * @author     BoldGrid <support@boldgrid.com>
 */

// Build markup container.
$sections = array(
	'sections' => array(
		array(
			'id'      => 'section_connect_key',
			'title'   => __( 'BoldGrid Connect Key', 'boldgrid-library' ),
			'content' => include __DIR__ . '/Connect/ConnectKey.php',
		),
	),
	'post_col_right' => '
	<div id="settings-notice" class="notice notice-success is-dismissible inline"></div>
	<p class="submit">
			<input name="submit" id="submit" class="button button-primary" value="' .
		esc_attr( 'Save Changes', 'boldgrid-library' ) . '" type="submit">
			<span class="spinner"></span>
		</p>
	',
);

array_push( $sections['sections'], array(
	'id'      => 'section_update_channels',
	'title'   => __( 'Update Channels', 'boldgrid-library' ),
	'content' => include __DIR__ . '/Connect/UpdateChannels.php',
) );

/**
 * Render sections into markup.
 *
 * @since 2.7.0
 *
 * @param array $sections
 *
 * phpcs:disable WordPress.NamingConventions.ValidHookName
 */
if ( ! has_filter( 'Boldgrid\Library\Ui\render_col_container' ) ) {
	$ui = new \Boldgrid\Library\Library\Ui();
	$ui->enqueue();
	add_filter( 'Boldgrid\Library\Ui\render_col_container' , array( $ui, 'render_col_container' ) );
}

$container = apply_filters( 'Boldgrid\Library\Ui\render_col_container', $sections );

if ( is_array( $container ) ) {
	$container = '<div class="notice notice-error inline">' .
		__( 'Unable to display settings page. Unknown BoldGrid Library error.', 'boldgrid-library' ) .
		'</div>';
} else {
	// Enqueue styles and scripts (registered in "\Boldgrid\Library\Ui::enqueue()").
	wp_enqueue_style( 'bglib-ui-css' );
	wp_enqueue_script( 'bglib-ui-js' );
	wp_enqueue_script( 'bglib-sticky' );
	wp_nonce_field( 'boldgrid_library_connect_settings_save' );
}

// Display page.
?>
<div class="wrap">
	<h1>BoldGrid Connect</h1>
<?php
echo $container; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
?>
</div>
