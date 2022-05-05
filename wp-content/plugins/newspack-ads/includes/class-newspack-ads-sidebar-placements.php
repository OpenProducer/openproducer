<?php
/**
 * Newspack Ads Sidebar Placements
 *
 * @package Newspack
 */

/**
 * Newspack Ads Sidebar Placements
 */
class Newspack_Ads_Sidebar_Placements {

	// Hook names to be used for ad placement.
	const SIDEBAR_BEFORE_HOOK_NAME = 'newspack_ads_sidebar_before_placement_%s';
	const SIDEBAR_AFTER_HOOK_NAME  = 'newspack_ads_sidebar_after_placement_%s';

	// Sidebars that should have a single ad unit instead of "before" and "after" hooks.
	const SINGLE_UNIT_SIDEBARS = [
		'footer-2',
	];

	// Sidebars to not create ad placement.
	const DISALLOWED_SIDEBARS = [
		'header-2',
		'header-3',
		'footer-3',
	];

	/**
	 * Initialize settings.
	 */
	public static function init() {
		add_action( 'dynamic_sidebar_before', [ __CLASS__, 'create_sidebar_before_action' ], 10, 2 );
		add_action( 'dynamic_sidebar_after', [ __CLASS__, 'create_sidebar_after_action' ], 10, 2 );
		add_filter( 'newspack_ads_placements', [ __CLASS__, 'add_sidebar_placements' ], 5, 1 );
	}

	/**
	 * Create a dynamic sidebar before action appropriate for ad unit insertion.
	 *
	 * @param int|string $index       Index, name, or ID of the dynamic sidebar.
	 * @param boolean    $has_widgets Whether the sidebar is populated with widgets. Default true.
	 */
	public static function create_sidebar_before_action( $index, $has_widgets ) {
		if ( $has_widgets ) {
			do_action( sprintf( self::SIDEBAR_BEFORE_HOOK_NAME, $index ) );
		}
	}
	
	/**
	 * Create a dynamic sidebar after action appropriate for ad unit insertion.
	 *
	 * @param int|string $index       Index, name, or ID of the dynamic sidebar.
	 * @param boolean    $has_widgets Whether the sidebar is populated with widgets. Default true.
	 */
	public static function create_sidebar_after_action( $index, $has_widgets ) {
		if ( $has_widgets ) {
			do_action( sprintf( self::SIDEBAR_AFTER_HOOK_NAME, $index ) );
		}
	}

	/**
	 * Register sidebars as ad placements.
	 *
	 * @param array $placements List of placements.
	 *
	 * @return array Updated list of placements.
	 */
	public static function add_sidebar_placements( $placements ) {
		$sidebars           = $GLOBALS['wp_registered_sidebars'];
		$sidebar_placements = [];

		$disallowed_sidebars  = apply_filters( 'newspack_ads_disallowed_sidebar_placements', self::DISALLOWED_SIDEBARS );
		$single_unit_sidebars = apply_filters( 'newspack_ads_single_unit_sidebar_placements', self::SINGLE_UNIT_SIDEBARS );

		foreach ( $sidebars as $sidebar ) {
			if ( isset( $sidebar['id'] ) ) {
				$placement_key = 'sidebar_' . $sidebar['id'];

				// Skip disallowed sidebar placements.
				$is_disallowed = in_array( $sidebar['id'], $disallowed_sidebars, true );

				// Disable SCAIP sidebars.
				if ( 'scaip' === substr( $sidebar['id'], 0, 5 ) ) {
					$is_disallowed = true;
				}

				if ( $is_disallowed ) {
					continue;
				}

				$placement_config = [
					'name'        => $sidebar['name'],
					// Translators: %s: The name of the sidebar.
					'description' => sprintf( __( 'Choose an ad unit to display in the "%s" widget area.', 'newspack-ads' ), $sidebar['name'] ),
				];
				
				if ( in_array( $sidebar['id'], $single_unit_sidebars, true ) ) {
					$placement_config['hook_name'] = sprintf( self::SIDEBAR_BEFORE_HOOK_NAME, $sidebar['id'] );
				} else {
					$placement_config['hooks'] = [
						'before' => [
							'name'      => __( 'Before widget area', 'newspack-ads' ),
							'hook_name' => sprintf( self::SIDEBAR_BEFORE_HOOK_NAME, $sidebar['id'] ),
						],
						'after'  => [
							'name'      => __( 'After widget area', 'newspack-ads' ),
							'hook_name' => sprintf( self::SIDEBAR_AFTER_HOOK_NAME, $sidebar['id'] ),
						],
					];
				}
				
				$sidebar_placements[ $placement_key ] = $placement_config;
			}
		}
		return array_merge( $placements, $sidebar_placements );
	}

}
Newspack_Ads_Sidebar_Placements::init();
