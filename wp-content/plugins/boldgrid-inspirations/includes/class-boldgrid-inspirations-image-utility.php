<?php
/**
 * BoldGrid Source Code
 *
 * @package   Boldgrid_Inspirations_Image_Utility
 * @copyright BoldGrid.com
 * @version   $Id$
 * @author    BoldGrid.com <wpb@boldgrid.com>
 */

/**
 * An image utility class.
 *
 * @since 1.4.7
 */
class Boldgrid_Inspirations_Image_Utility {

	/**
	 * Crop an image to a specific aspect ratio.
	 *
	 * If you have an original image 302px by 300px and you request to crop
	 * it to an aspect ratio of 1:1, this method will return an image resource
	 * 300px by 300px.
	 *
	 * @since 1.4.7
	 *
	 * @param  string $filepath Path to the image.
	 * @param  int    $width    Width.
	 * @param  int    $height   Height.
	 * @return bool   True on success.
	 */
	public static function crop_to_aspect_ratio( $filepath, $width, $height ) {
		$width = (int) $width;
		$height = (int) $height;

		$image = wp_get_image_editor( $filepath );
		if ( is_wp_error( $image ) ) {
			return false;
		}

		$image_size = $image->get_size();

		// Abort if aspect ratios alrady match.
		if ( $width / $height === $image_size['width'] / $image_size['height'] ) {
			return true;
		}

		/*
		 * Calculate largest area we can crop the existing image by while
		 * keeping aspect ratio of requested width and height.
		 *
		 * Start off by using the image's width at the max width. If that
		 * results in an image height too large, then use the image's height as
		 * the max height.
		 */
		$new_width = $image_size['width'];
		$new_height = round( ( $height * $new_width ) / $width );
		if ( $new_height > $image_size['height'] ) {
			$new_height = $image_size['height'];
			$new_width = round( ( $width * $new_height ) / $height );
		}

		// Calculate new coordinates.
		$x0 = round( ( $image_size['width'] / 2 ) - ( $new_width / 2 ) );
		$y0 = round( ( $image_size['height'] / 2 ) - ( $new_height / 2 ) );

		$is_cropped = $image->crop( $x0, $y0, $new_width, $new_height );
		if ( is_wp_error( $is_cropped ) ) {
			return false;
		}

		$is_saved = $image->save( $filepath );
		if ( is_wp_error( $is_saved ) ) {
			return false;
		}

		return true;
	}
}


