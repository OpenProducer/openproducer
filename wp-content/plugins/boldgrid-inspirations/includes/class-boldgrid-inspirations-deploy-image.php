<?php
/**
 * BoldGrid Source Code
 *
 * @package   Boldgrid_Inspirations_Deploy_Image
 * @copyright BoldGrid.com
 * @version   $Id$
 * @author    BoldGrid.com <wpb@boldgrid.com>
 */

/**
 * Class: Boldgrid_Inspirations_Deploy_Image.
 *
 * @since 1.4.8
 */
class Boldgrid_Inspirations_Deploy_Image {

	/**
	 * Add hooks.
	 *
	 * @since 1.4.8
	 */
	public function add_hooks() {
		add_filter( 'boldgrid_deploy_post_process_image',  array( $this, 'post_process_image' ), 10, 3 );
	}

	/**
	 * Method for boldgrid_deploy_post_process_image filter.
	 *
	 * @since 1.4.8
	 *
	 * @param  int $id Attachment id.
	 * @param  int $width
	 * @param  int $height
	 * @return string Url to attachment.
	 */
	public function post_process_image( $id, $width, $height ) {
		$width = (int) $width;
		$height = (int) $height;

		if( 0 === $width || 0 === $height ) {
			return wp_get_attachment_url( $id );
		}

		Boldgrid_Inspirations_Attachment::resize( $id, $width, $height );

		$image_src = wp_get_attachment_image_src( $id, array( $width, $height ) );

		return $image_src[0];
	}
}
