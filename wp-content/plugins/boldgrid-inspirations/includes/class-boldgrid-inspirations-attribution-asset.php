<?php
/**
 * BoldGrid Source Code
 *
 * @package Boldgrid_Inspirations_Attribution_Asset
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

/**
 * The BoldGrid Attribution Asset class.
 *
 * This class includes methods that help process assets during the creating of the Attribution
 * page.
 *
 * @since 1.3.1
 */
class Boldgrid_Inspirations_Attribution_Asset {

	/**
	 * Create an array of possible file names of an asset.
	 *
	 * For example, the same asset might have been resized into several different files /
	 * thumbnails, and we need to check for all of them.
	 *
	 * @since 1.3.1
	 *
	 * @param  array $asset An asset.
	 * @return array An array of filenames for an asset.
	 */
	public function get_names( $asset ) {
		$array_file_names_to_query = array();

		// Get _wp_attachment_metadata.
		$wp_attachment_metadata = get_post_meta( $asset['attachment_id'], '_wp_attachment_metadata', true );

		if ( ! empty( $wp_attachment_metadata['sizes'] ) ) {
			foreach ( $wp_attachment_metadata['sizes'] as $image_size ) {
				$array_file_names_to_query[] = $image_size['file'];
			}
		}

		// Get _wp_attached_file.
		$wp_attached_file = get_post_meta( $asset['attachment_id'], '_wp_attached_file', true );

		if ( ! empty( $wp_attached_file ) ) {
			$array_file_names_to_query[] = $wp_attached_file;
		}

		return $array_file_names_to_query;
	}

	/**
	 * Determine if an asset is in a gallery.
	 *
	 * @since 1.3.1
	 *
	 * @param  array  $asset
	 * @param  string $post_status_to_search
	 * @return boolean
	 */
	public function is_in_gallery( $asset, $post_status) {
		global $wpdb;

		// @todo Use a regular expression to find a match, rather than this excessive LIKE statement.
		$gallery_like_statement = '%[gallery%ids%' . $wpdb->esc_like( $asset['attachment_id'] ) .
		'%data-imhwpb-assets%' . $wpdb->esc_like( $asset['asset_id'] ) . '%]%';

		$asset_in_gallery = $wpdb->get_var( $wpdb->prepare("
			SELECT	`post_title`
			FROM	$wpdb->posts
			WHERE	`post_content` LIKE %s AND
					`post_type` IN ('page','post') AND
					`post_status` IN ($post_status)",
			$gallery_like_statement
		));

		// If we found results, then the image is being used in a page/post.
		return ( ! empty( $asset_in_gallery ) );
	}

	/**
	 * Determine if a string is found in a page.
	 *
	 * @since 1.3.1
	 *
	 * @param  string $file_name   A string to search for.
	 * @param  string $post_status A post_status to search within.
	 * @return bool
	 */
	public function is_in_page( $file_name, $post_status ) {
		global $wpdb;

		$asset_in_page = $wpdb->get_var( $wpdb->prepare("
			SELECT	`ID`
			FROM	$wpdb->posts
			WHERE	`post_content`	LIKE %s AND
					`post_type`		IN ('page','post') AND
					`post_status`	IN ($post_status)",
			'%' . $wpdb->esc_like( $file_name ) . '%'
		));

		// If we found results, then the image is being used in a page/post.
		return ( ! empty( $asset_in_page ) );
	}

	/**
	 * Determine if an asset is a theme mod.
	 *
	 * @since 1.3.1
	 *
	 * @param  array $array_file_names_to_query
	 * @return bool
	 */
	public function is_theme_mod( $array_file_names_to_query ) {
		$theme_mods = get_theme_mods();

		if( false === $theme_mods ) {
			return;
		}

		foreach ( $theme_mods as $mod_key => $mod_value ) {
			if ( is_string( $mod_value ) && 'http' === substr( $mod_value, 0, 4 ) ) {
				// Loop through each possible filename.
				foreach ( $array_file_names_to_query as $file_name_to_query ) {
					// If the mod_value ends in the filename, return true.
					$length_of_filename = strlen( $file_name_to_query );
					if ( $file_name_to_query === substr( $mod_value, - 1 * $length_of_filename ) ) {
						return true;
					}
				}
			}
		}

		return false;
	}

	/**
	 * Determine if an asset is a featured image.
	 *
	 * @since 1.3.1
	 *
	 * @param  array  $asset
	 * @param  string $post_status A post_status to search within.
	 * @return bool
	 */
	public function is_featured_image( $asset, $post_status ) {
		global $wpdb;

		// If we don't have an attachment id for this $asset, return false.
		if( empty ( $asset['attachment_id'] ) ) {
			return false;
		}

		$is_featured_image = $wpdb->get_var( $wpdb->prepare("
			SELECT	`post_id`
			FROM	$wpdb->postmeta,
					$wpdb->posts
			WHERE	$wpdb->postmeta.meta_key = '_thumbnail_id' AND
					$wpdb->postmeta.meta_value = %s AND
					$wpdb->postmeta.post_id = $wpdb->posts.ID AND
					$wpdb->posts.post_status IN ( $post_status ) AND
					$wpdb->posts.post_type IN ('page','post')",
			$asset['attachment_id']
		));

		// If we found results, then the image is being used in a page/post.
		return ( ! empty( $is_featured_image ) );
	}

	/**
	 * Determine if a passed in asset needs attribution.
	 *
	 * We'll do this by checking to see if the asset is used within a page/post, or,
	 * it is set as a featured image.
	 *
	 * @since 1.0
	 *
	 * @param  array $asset An asset.
	 * @retun  bool
	 */
	public function needs_attribution( $asset ) {

		// If there's no attribution_license, we can't attribute the asset; return false.
		if ( empty( $asset['attribution'] ) ) {
			return false;
		}

		/*
		 * By default, when looking through pages and posts for images, look for those with a status
		 * of 'publish'. We don't want to attribute images that are not published. We want to allow
		 * other plugins to change this too however, such as the BoldGrid staging plugin.
		 */
		$post_status = "'publish'";

		$post_status = apply_filters( 'boldgrid_attribution_post_status_to_search', $post_status );

		if( true === $this->is_featured_image( $asset, $post_status ) ) {
			return true;
		}

		$asset_file_names = $this->get_names( $asset );


		// Is this asset used in a page?
		if ( ! ( empty( $asset_file_names ) ) ) {
			foreach ( $asset_file_names as $asset_file_name ) {
				if( true === $this->is_in_page( $asset_file_name, $post_status ) ) {
					return true;
				}
			}
		}

		// Is this asset a theme mod?
		if( true === $this->is_theme_mod( $asset_file_names ) ) {
			return true;
		}

		// Is this asset used within a gallery?
		if( true === $this->is_in_gallery( $asset, $post_status ) ) {
			return true;
		}

		/*
		 * If we weren't able to find the asset being used in a page/post or as a featured image,
		 * then return false for asset_needs_attribution.
		 */
		return false;
	}
}
