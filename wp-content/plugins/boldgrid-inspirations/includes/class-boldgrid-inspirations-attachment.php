<?php
/**
 * BoldGrid Source Code
 *
 * @package   Boldgrid_Inspirations_Attachment.
 * @copyright BoldGrid.com
 * @version   $Id$
 * @author    BoldGrid.com <wpb@boldgrid.com>
 */

/**
 * An attachment utility class.
 *
 * @since 1.4.8
 */
class Boldgrid_Inspirations_Attachment {

	/**
	 * Add a new size an attachment's metadata.
	 *
	 * @since 1.4.8
	 *
	 * @param int    $id       Attachment id.
	 * @param string $size     The label for the new size.
	 * @param string $filepath Filepath of the new image.
	 */
	public static function add_metadata_size( $id, $size, $filepath ) {
		$metadata = wp_get_attachment_metadata( $id );

		$mime_type = get_post_mime_type( $id );

		$image = wp_get_image_editor( $filepath );
		$dimensions = $image->get_size();

		/*
		 * Note: As of PHP 7.1.0, applying the empty index operator on a string throws a fatal
		 * error. Formerly, the string was silently converted to an array.
		 *
		 * @link http://php.net/manual/en/language.types.array.php
		 */
		if ( ! is_array( $metadata ) ) {
			$metadata = array();
		}

		$metadata['sizes'][$size] = array(
				'file' => basename( $filepath ),
				'width' => $dimensions['width'],
				'height' => $dimensions['height'],
				'mime-type' => $mime_type,
		);

		wp_update_attachment_metadata( $id, $metadata );
	}

	/**
	 * Check if a size exists for an attachment.
	 *
	 * @since 1.4.8
	 *
	 * @param  int $id     Attachment id.
	 * @param  int $width
	 * @param  int $height
	 * @return boolean
	 */
	public static function size_exists( $id, $width, $height ) {
		$width = (int) $width;
		$height = (int) $height;

		$src = wp_get_attachment_image_src( $id, array( $width, $height ) );

		return is_array( $src ) && $width === $src[1] && $height === $src[2];
	}

	/**
	 * Resize an attachment and add info to metadata.
	 *
	 * @since 1.4.8
	 *
	 * @param  int     $id     Attachment id.
	 * @param  int     $width
	 * @param  int     $height
	 * @return boolean True if on success.
	 */
	public static function resize( $id, $width, $height ) {
		$width = (int) $width;
		$height = (int) $height;

		$suffix = $width . 'x' . $height;

		if( self::size_exists( $id, $width, $height ) ) {
			return true;
		}

		$filepath = get_attached_file( $id );

		$image = wp_get_image_editor( $filepath );

		// Generate new filename.
		$pathinfo = pathinfo( $filepath );
		$new_filepath = $image->generate_filename( $suffix, $pathinfo['dirname'], $pathinfo['extension'], $pathinfo['filename'] );

		$is_resized = $image->resize( $width, $height, true );
		if ( is_wp_error( $is_resized ) ) {
			return false;
		}

		$is_saved = $image->save( $new_filepath );
		if ( is_wp_error( $is_saved ) ) {
			return false;
		}

		self::add_metadata_size( $id, 'boldgrid_deployment_resize', $new_filepath );

		return true;
	}
}