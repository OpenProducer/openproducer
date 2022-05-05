<?php
// Prevent direct calls.
require BOLDGRID_BASE_DIR . '/pages/templates/restrict-direct-access.php';

$attribution_heading       = '<p>' . esc_html__( 'This site has been created with the help of many different people and companies.', 'boldgrid-inspirations' ) . '</p>';
$attribution_image_heading = '<p>' . esc_html__( 'In particular, a special thanks goes to the following for content running on this site:', 'boldgrid-inspirations' ) . '</p>';

// Create attribution for the web host reseller.
$reseller = get_option( 'boldgrid_reseller' );

if ( false !== $reseller && ! empty( $reseller['reseller_title'] ) ) {
	if ( ! empty( $reseller['reseller_website_url'] ) ) {
		$reseller_link = '<a href="' . esc_url( $reseller['reseller_website_url'] ) . '">' . esc_html( $reseller['reseller_title'] ) . '</a>.';
	} else {
		$reseller_link = $reseller['reseller_title'] . '.';
	}
	// translators: 1 a link to the resller.
	$reseller_attribution = ' ' . sprintf( __( 'Web hosting support is provided by %1$s', 'boldgrid-inspirations' ), $reseller_link );
} else {
	$reseller_attribution = '';
}

// This var used by includes/class-boldgrid-inspirations-attribution.php.
$attribution_wordpress_and_inspirations =
	'<p style="clear:both;">%s ' .
	sprintf(
		wp_kses(
			// translators: 1 a link to boldgrid.com, 2 a link to wordpress.org.
			__( 'site was built on a powerful, Inspirations based web builder called %1$s. It is running on %2$s, the most popular content management software online today.', 'boldgrid-inspirations' ),
			array( 'a' => array( 'href' => array() ), 'target' => array(), )
			),
		'<a href="http://www.boldgrid.com" target="_blank">BoldGrid</a>',
		'<a href="http://wordpress.org" target="_blank">WordPress</a>'
	) .
	$reseller_attribution .
	'</p>';

/*
 * Create attribution for plugins we install from 3rd Party sources.
 *
 * This var used by includes/class-boldgrid-inspirations-attribution.php
 */
$attribution_additional_plugins = '';
if ( function_exists( 'is_plugin_active' ) ) {
	// Check if some plugins are active.
	$is_boldgrid_ninja_forms_active = ( bool ) is_plugin_active( 'boldgrid-ninja-forms/ninja-forms.php' );
	$is_boldgrid_gallery_active     = ( bool ) is_plugin_active( 'boldgrid-gallery/wc-gallery.php' );

	if ( $is_boldgrid_ninja_forms_active || $is_boldgrid_gallery_active ) {
		$attribution_additional_plugins .= '
			<div class="boldgrid-attribution">
				<p>' . esc_html__( 'Additional functionality provided by', 'boldgrid-inspirations' ) . ':</p>
				<ul>';
		if ( $is_boldgrid_ninja_forms_active ) {
			$attribution_additional_plugins .= '<li><a href="https://ninjaforms.com/">Ninja Forms</a></li>';
		}
		if ( $is_boldgrid_gallery_active ) {
			$attribution_additional_plugins .= '<li><a href="https://wordpress.org/plugins/wc-gallery/">WP Canvas - Gallery</a></li>';
		}
		$attribution_additional_plugins .= '
				</ul>
			</div>
		';
	}
}
