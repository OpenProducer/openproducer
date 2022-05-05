<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

return array (
	'tabs' => array (
		'google-map' => array (
			'tab-details' => array (
				'type' => 'api',
				'selection-type' => 'single-item',
				'base-url' => 'https://maps.google.com/maps',
				'default-location-setting' => array (
					'q' => 'New York, NY'
				)
			),
			'title' => 'Google Map',
			'zoom' => '14',
			'slug' => 'google_map',
			'attachments-template' => BOLDGRID_EDITOR_PATH . '/pages/standard-attachments.php',
			'sidebar-template' => BOLDGRID_EDITOR_PATH . '/pages/google-maps-sidebar.html',
			'route-tabs' => array (
				'map-view' => array (
					'name' => 'Map View',
					'content' => array (
						array (
							'image' => plugins_url( '/assets/image/maps/google/gm-roadmap.png',
								BOLDGRID_EDITOR_PATH . '/boldgrid-editor.php' ),
							'map-params' => array (
								// Type: Standard.
								't' => 'm',
								// Zoom.
								'z' => '16'
							)
						),
						array (
							'image' => plugins_url( '/assets/image/maps/google/gm-satellite.png',
								BOLDGRID_EDITOR_PATH . '/boldgrid-editor.php' ),
							'map-params' => array (
								// Satellite.
								't' => 'k',
								'z' => '16'
							)
						)
					)
				)
			)
		)
	)
);
