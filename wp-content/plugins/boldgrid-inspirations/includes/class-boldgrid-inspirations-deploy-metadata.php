<?php
/**
 * BoldGrid Source Code
 *
 * @package Boldgrid_Inspirations_Deploy_Metadata
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

/**
 * BoldGrid Inspirations Deploy Metadata class.
 *
 * This class is designated for working with metadata surrounding a new site deployment.
 *
 * @since 1.3.9
 */
class Boldgrid_Inspirations_Deploy_Metadata{

	/**
	 * Get private posts.
	 *
	 * If you installed a Staging site via Inspirations, and that site included posts, those posts
	 * were set to private. This method will return an array of those private post ids.
	 *
	 * @since 1.3.9
	 *
	 * @return array
	 */
	public static function get_private_posts() {
		$posts = array();

		$metadata = get_option( 'boldgrid_staging_boldgrid_installed_pages_metadata', array() );

		foreach( $metadata as $post_id => $post_data ) {
			if( 'private' === $post_data['post_status'] && 'post' === $post_data['post_type'] ) {
				$posts[] = $post_id;
			}
		}

		return $posts;
	}
}

?>