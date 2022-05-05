<?php

// @todo Determine best approach for clearing shared options.
return;

// Abort if uninstall is not called from WordPress.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

// Remove the API key and other plugin data from the database:
delete_option( 'boldgrid_api_key' );
delete_option( 'imhwpb_api_key' );

delete_option( 'boldgrid_attribution' );
delete_option( 'imhwpb_attribution' );

delete_option( 'boldgrid_install_options' );
delete_option( 'imhwpb_install_options' );

delete_option( 'boldgrid_installed_page_ids' );
delete_option( 'imhwpb_installed_page_ids' );

delete_option( 'boldgrid_installed_pages_metadata' );
delete_option( 'imhwpb_installed_pages_metadata' );

delete_option( 'boldgrid_todo' );
delete_option( 'imhwpb_todo' );

delete_option( 'boldgrid_asset' );
delete_option( 'imhwpb_asset' );

delete_option( 'boldgrid_has_built_site' );
delete_option( 'imhwpb_has_built_site_with_imhwpb' );

delete_option( 'boldgrid_show_tip_start_editing' );
delete_option( 'imhwpb_show_tip_start_editing' );

delete_option( 'boldgrid_built_as_preview_site' );
delete_option( 'imhwpb_built_as_preview_site' );

delete_option( 'boldgrid.css' );
delete_option( 'imhwpbgrid.css' );

delete_option( 'boldgrid_reseller' );
delete_option( 'imhwpb_reseller' );

delete_option( 'boldgrid_settings' );
delete_option( 'imhwpb_settings' );

delete_transient( 'boldgrid_valid_api_key' );
delete_transient( 'imhwpb_valid_api_key' );

delete_site_option( 'boldgrid_settings' );
delete_site_transient( 'boldgrid_api_data' );
delete_site_transient( 'imhwpb_api_data' );

if ( is_multisite() ) {
	delete_site_option( 'boldgrid_we_are_currently_installing_a_theme' );
	delete_site_option( 'imhwpb_we_are_currently_installing_a_theme' );

	delete_site_option( 'boldgrid_we_are_currently_installing_this_theme' );
	delete_site_option( 'imhwpb_we_are_currently_installing_this_theme' );
}
