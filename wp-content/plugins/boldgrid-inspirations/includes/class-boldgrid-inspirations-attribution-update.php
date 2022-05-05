<?php
/**
 * BoldGrid Source Code
 *
 * @package Boldgrid_Inspirations_Attribution_Update
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

/**
 * The BoldGrid Attribution Update class.
 *
 * This file includes all functionality necessary to ensure our "Custom Post Type" attribution pages
 * are backwards compatible.
 *
 * @since 1.3.1
 */
class Boldgrid_Inspirations_Attribution_Update {

	/**
	 * Add hooks.
	 *
	 * @since 1.3.1
	 */
	public function add_hooks() {
		$this->upgrade_to_cpt();

		add_filter( 'pre_option_boldgrid_staging_boldgrid_attribution', array( $this, 'pre_option_boldgrid_attribution' ), 20 );
		add_filter( 'pre_option_boldgrid_attribution', array( $this, 'pre_option_boldgrid_attribution' ), 20 );
	}

	/**
	 * Return older format of boldgrid_attribution option.
	 *
	 * @since 1.3.1
	 */
	public function pre_option_boldgrid_attribution() {
		$return = array();

		$attribution_page = Boldgrid_Inspirations_Attribution_Page::get();

		$return['page']['id'] = $attribution_page->ID;

		return $return;
	}


	/**
	 * Upgrade the Attribution system to use custom post types.
	 *
	 * @since 1.3.1
	 */
	public function upgrade_to_cpt() {
		/*
		 * Do we need to perform this upgrade?
		 *
		 * This upgrade only needs to be ran once. If the option
		 * boldgrid_attribution_upgraded_to_cpt has a value, it means that we've already ran this
		 * method.
		 *
		 * Part of the upgrade process involves flush_rewrite_rules. It is when we run that, that we
		 * set boldgrid_attribution_upgraded_to_cpt to true. Please see:
		 * BoldGrid_Inspirations_Attribution_Page::register_post_type();
		 */
 		if( false !== get_option( 'boldgrid_attribution_upgraded_to_cpt' ) ) {
 			return;
 		}

 		$lang = Boldgrid_Inspirations_Attribution::get_lang();

		// These are the pages that we will find and delete.
 		$slugs = array( $lang['attribution'], $lang['attribution'] . '-staging' );

		foreach( $slugs as $slug ) {
			$attribution_page = get_page_by_path( $slug  );

			if( is_object( $attribution_page ) && isset( $attribution_page->ID ) ) {
				$attribution_page->post_type = $lang['post_type'];
				wp_delete_post( $attribution_page->ID, true );
			}
		}

		// Flag this option as true so that the next visit to Attribution triggers a rebuild.
		update_option( 'boldgrid_attribution_rebuild', true );
		update_option( 'boldgrid_staging_boldgrid_attribution_rebuild', true );

		// These options are no longer needed, delete them.
		delete_option( 'boldgrid_attribution' );
		delete_option( 'boldgrid_staging_boldgrid_attribution' );
	}
}