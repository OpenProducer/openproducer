<?php
/**
 * BoldGrid Premium Default Configurations.
 */

defined( 'WPFORMS_SHAREASALE_ID' ) || define( 'WPFORMS_SHAREASALE_ID', '1581233' );

return array(
	// libraryVersion is used to put the version number on js/css files.
	'libraryVersion' => '2.13.2',
	'api' => 'https://api.boldgrid.com',
	'option' => 'license',
	'key' => get_site_option( 'boldgrid_api_key', null ),
	'apiData' => get_site_transient( 'boldgrid_api_data' ),
	'themeData' => get_site_transient( 'boldgrid_theme_data' ),

	// When the user needs a Connect Key, the "Get it now" button links here (filters get applied).
	'getNewKey' => 'https://www.boldgrid.com/central/account/new-key',

	// Click here for the benefits of a Premium Key.
	'learnMore' => 'https://www.boldgrid.com/connect-keys?source=library-prompt',

	// Enable key validation in library.
	'keyValidate' => true,

	// Enable license activation/deactivation in library.
	'licenseActivate' => false,

	// Library's Plugin Installer for "Plugins > Add New" in WordPress Dashboard.
	'pluginInstaller' => array(

		// Enabled the plugin installer feature in library.
		'enabled' => true,

		// Default Premium Link.
		'defaultLink' => 'https://www.boldgrid.com/connect-keys/',

		// Installable plugins.
		'plugins' => array(
			'boldgrid-inspirations' => array(
				'key' => 'core',
				'file' => 'boldgrid-inspirations/boldgrid-inspirations.php',
				'priority' => 20,
			),
			'boldgrid-gallery' => array(
				'key' => 'gallery-wc-canvas',
				'file' => 'boldgrid-gallery/wc-gallery.php',
				'priority' => 70,
			),
		),

		// WordPress.org recommended plugins.
		'wporgPlugins' => array(
			'post-and-page-builder' => array(
				'slug' => 'post-and-page-builder',
				'link' => '//wordpress.org/plugins/post-and-page-builder/',
				'priority' => 10,
			),
			'boldgrid-easy-seo' => array(
				'slug' => 'boldgrid-easy-seo',
				'link' => '//wordpress.org/plugins/boldgrid-easy-seo/',
				'priority' => 30,
			),
			'boldgrid-backup' => array(
				'slug' => 'boldgrid-backup',
				'link' => '//wordpress.org/plugins/boldgrid-backup/',
				'priority' => 40,
			),
			'weforms' => array(
				'slug' => 'weforms',
				'link' => '//wordpress.org/plugins/weforms',
				'priority' => 80,
				'hide_premium' => true,
			),
			'w3-total-cache' => array(
				'slug' => 'w3-total-cache',
				'link' => '//wordpress.org/plugins/w3-total-cache/',
				'priority' => 80,
				'hide_premium' => true,
			),
		),
	),
	'api_calls' => array(
		'get_theme_data' => '/api/open/get-theme-data',
		'get_asset'      => '/api/open/get-asset',
	),

	/*
	 * A list of BoldGrid (and related) plugins.
	 *
	 * @since 2.10.0
	 *
	 * @todo Above, there are 2 additional arrays of plugins. They should all be combined into 1 and
	 *       use attributes to indicated which group they belong to, such as:
	 *       # inNotificationsWidget
	 *       # isInstallable
	 *       # isWpOrg
	 *       We can then use Boldgrid\Library\Library\Configs( $filters ) to get our plugins, such as:
	 *       Configs::getPlugins( array( 'inNotificationsWidget' => true ) )
	 */
	'plugins' => array(
		array(
			'file'                  => 'boldgrid-inspirations/boldgrid-inspirations.php',
			'inNotificationsWidget' => true,
		),
		array(
			'file'                  => 'boldgrid-backup/boldgrid-backup.php',
			'inNotificationsWidget' => true,
			'childPlugins'          => [
				'boldgrid-backup-premium/boldgrid-backup-premium.php',
			],
		),
		array(
			'file'                  => 'boldgrid-easy-seo/boldgrid-easy-seo.php',
			'inNotificationsWidget' => true,
		),
		array(
			'file'                  => 'post-and-page-builder/post-and-page-builder.php',
			'inNotificationsWidget' => true,
			'childPlugins'          => [
				'post-and-page-builder-premium/post-and-page-builder-premium.php',
			]
		),
	),

	// An array of dashboard widgets that are placed at the top of the dashboard.
	'dashboardWidgetOrder' => array(
		'boldgrid-notifications'   => array(
			'container' => 'normal',
			'priority'  => 'core',
		),
		'boldgrid_feedback_widget' => array(
			'container' => 'side',
			'priority'  => 'core',
		),
		'boldgrid_news_widget'     => array(
			'container' => 'side',
			'priority'  => 'core',
		),
	),


	// Google Analytics ID, used by the Usage class.
	'gaId' => 'UA-1501988-42',
);
