<?php

/**
 * BoldGrid Source Code
 *
 * @package Boldgrid_Inspirations_Enable_Media_Replace
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

// Die if user cannot upload files.
if ( ! current_user_can( 'upload_files' ) )
	wp_die( esc_html__( 'You do not have permission to upload files.', 'boldgrid-inspirations' ) );

/**
 * The BoldGrid Enable Media Replace class.
 */
class Boldgrid_Inspirations_Enable_Media_Replace {
	/**
	 * The Boldgrid Inspirations Asset Manager class object.
	 *
	 * @var Boldgrid_Inspirations_Asset_Manager
	 */
	private $asset_manager;

	/**
	 * Replace image.
	 *
	 * After purchasing an unwatermarked image, we call this method and pass:
	 * # $attachment_id The local attachment id of the watermarked image.
	 * # $new_file_path The filepath to the unwatermarked image just downloaded.
	 *
	 * @param unknown $attachment_id
	 * @param unknown $new_file_path
	 * @return boolean
	 */
	public function replace_image( $attachment_id, $new_file_path ) {
		$file['userfile'] = array (
			'tmp_name' => $new_file_path,
			'name' => basename( $new_file_path ),
			'size' => filesize( $new_file_path )
		);

		// Define DB table names
		global $wpdb;

		$table_name = $wpdb->prefix . 'posts';

		$postmeta_table_name = $wpdb->prefix . 'postmeta';

		// Get old guid and filetype from DB
		$sql = "SELECT guid, post_mime_type FROM " . $table_name . " WHERE ID = '" .
			 ( int ) $attachment_id . "'";

		list ( $current_filename, $current_filetype ) = $wpdb->get_row( $sql, ARRAY_N );

		// Massage a bunch of vars
		$current_guid = $current_filename;
		$current_filename = substr( $current_filename, ( strrpos( $current_filename, '/' ) + 1 ) );

		$current_file = get_attached_file( ( int ) $attachment_id, true );
		$current_path = substr( $current_file, 0, ( strrpos( $current_file, '/' ) ) );
		$current_file = str_replace( '//', '/', $current_file );
		$current_filename = basename( $current_file );

		// We have two types: replace / replace_and_search; we only use replace.
		$replace_type = 'replace';

		/* @formatter:off */
		/**

		$_FILES = Array
		(
			[userfile] => Array
			(
				[name] => git.png
				[type] => image/png
				[tmp_name] => /tmp/phpxRNhKw
				[error] => 0
				[size] => 4588
			)

		)

		$filedata = Array
		(
    		[ext] => png
    		[type] => image/png
    		[proper_filename] =>
		)

		*/
		/* @formatter:on */

		// New method for validating that the uploaded file is allowed, using WP:s internal
		// wp_check_filetype_and_ext() function.
		$filedata = wp_check_filetype_and_ext( $file['userfile']['tmp_name'],
			$file['userfile']['name'] );

		if ( empty( $filedata['ext'] ) ) {
			esc_html_e( 'File type does not meet security guidelines. Try another.', 'boldgrid-inspirations' );

			return false;
		}

		$new_filename = $file['userfile']['name'];
		$new_filesize = $file['userfile']['size'];
		$new_filetype = $filedata['type'];

		// save original file permissions
		$original_file_perms = fileperms( $current_file ) & 0777;

		if ( 'replace' == $replace_type ) {
			// Drop-in replace and we don't even care if you uploaded something that is the wrong
			// file-type.
			// That's your own fault, because we warned you!

			// Delete the old image file and resized images:
			$this->emr_delete_current_files( $current_file, $attachment_id );

			// Move new file to old location/name
			// move_uploaded_file( $file["userfile"]["tmp_name"], $current_file );
			rename( $file['userfile']['tmp_name'], $current_file );

			// Chmod new file to original file permissions
			chmod( $current_file, $original_file_perms );

			// Delete the temp attachment:

			// Make thumb and/or update metadata
			wp_update_attachment_metadata( ( int ) $attachment_id,
				wp_generate_attachment_metadata( ( int ) $attachment_id, $current_file ) );

			// Trigger possible updates on CDN and other plugins
			update_attached_file( ( int ) $attachment_id, $current_file );

			// Update any cropped images.
			$this->update_crops( $attachment_id, $new_file_path );
		}

		// Execute hook actions - thanks rubious for the suggestion!
		if ( isset( $new_guid ) ) {
			do_action( 'enable-media-replace-upload-done',
				( $new_guid ? $new_guid : $current_guid ) );
		}
	}

	/**
	 * emr_delete_current_files
	 *
	 * @param unknown $current_file
	 *        	=
	 *        	/home/user/public_html/wp-content/uploads/sites/316/2015/04/4-FotoliaComp_78800821_jwmdPMmrLDqay9EtDkXISZ7kKwezB0Vk.jpg
	 * @param int $attachment_id
	 */
	public function emr_delete_current_files( $current_file, $attachment_id ) {
		// Delete old file

		// Find path of current file
		$current_path = substr( $current_file, 0, ( strrpos( $current_file, '/' ) ) );

		// Check if old file exists first
		if ( file_exists( $current_file ) ) {
			// Now check for correct file permissions for old file
			clearstatcache();

			if ( is_writable( $current_file ) ) {
				// Everything OK; delete the file
				unlink( $current_file );
			} else {
				// translators: 1 The name of the file that could not be deleted.
				printf( __( 'The file %1$s can not be deleted by the web server, most likely because the permissions on the file are wrong.', 'boldgrid-inspirations' ), esc_html__( $current_file ) );

				exit();
			}
		}

		// Delete old resized versions if this was an image
		$suffix = substr( $current_file, ( strlen( $current_file ) - 4 ) );
		$prefix = substr( $current_file, 0, ( strlen( $current_file ) - 4 ) );
		$imgAr = array (
			'.png',
			'.gif',
			'.jpg'
		);

		if ( in_array( $suffix, $imgAr ) ) {
			// It's a png/gif/jpg based on file name
			// Get thumbnail filenames from metadata
			$metadata = wp_get_attachment_metadata( $attachment_id );

			if ( is_array( $metadata ) ) { // Added fix for error messages when there is no metadata
			                               // (but WHY would there not be? I don't know…)
				foreach ( $metadata['sizes'] as $thissize ) {
					// Get all filenames and do an unlink() on each one;
					$thisfile = $thissize['file'];

					// Create array with all old sizes for replacing in posts later
					$oldfilesAr[] = $thisfile;

					// Look for files and delete them
					if ( strlen( $thisfile ) ) {
						$thisfile = $current_path . '/' . $thissize['file'];

						if ( file_exists( $thisfile ) ) {
							unlink( $thisfile );
						}
					}
				}
			}
		}
	}

	/**
	 * Array
	 * (
	 * ____[$attachment_id] => 22970
	 * ____[$new_file_path] =>
	 * ________/home/user/public_html/wp-content/uploads/2015/09/4-sandbox-FotoliaComp_74207248_ToiPhcx79N47nlGMSZg37kkUo4HXoL87.jpg
	 * ____[$asset] => Array
	 * ____(
	 * ________[asset_id] => 69223
	 * ________[coin_cost] => 6
	 * ________[name] =>
	 * ____________/home/user/public_html/wp-content/uploads/2015/09/4-FotoliaComp_74207248_ToiPhcx79N47nlGMSZg37kkUo4HXoL87.jpg
	 * ________[purchase_date] => 2015-09-18 17:43:49
	 * ________[download_date] => 2015-09-18 17:42:49
	 * ________[attribution] =>
	 * ________[attribution_license] =>
	 * ________[attachment_id] => 22970
	 * ________[width] => 700
	 * ________[height] => 467
	 * ________[image_provider_id] => 4
	 * ________[id_from_provider] => 74207248
	 * ________[orientation] =>
	 * ________[image_size] => M
	 * ________[transaction_item_id] => 17
	 * ________[transaction_id] => 17
	 * ________[crops] => Array
	 * ________(
	 * ____________[0] => Array
	 * ____________(
	 * ________________[cropDetails] => Array
	 * ________________(
	 * ____________________[x1] => 0
	 * ____________________[y1] => 165
	 * ____________________[x2] => 700
	 * ____________________[y2] => 266
	 * ____________________[width] => 700
	 * ____________________[height] => 101
	 * ________________)
	 * ________________[path] =>
	 * ____________________/home/bradm/public_html/single-site/wp-content/uploads/2015/09/cropped-4-FotoliaComp_74207248_ToiPhcx79N47nlGMSZg37kkUo4HXoL87.jpg
	 * ____________)
	 * ________)
	 * )
	 */
	public function update_crops( $attachment_id, $new_file_path ) {
		// Get the asset.
		require_once BOLDGRID_BASE_DIR . '/includes/class-boldgrid-inspirations-asset-manager.php';
		$this->asset_manager = new Boldgrid_Inspirations_Asset_Manager();

		$asset = $this->asset_manager->get_asset(
			array(
				'by' => 'attachment_id',
				'attachment_id' => $attachment_id,
			)
		);

		// If this is not an asset, abort.
		if ( false == $asset ) {
			return;
		}

		// Abort if there are no 'crops'.
		if ( ! isset( $asset['crops'] ) ) {
			return;
		}

		foreach ( $asset['crops'] as $crops_key => $crop_details ) {
			// crop the new unwatermarked image, $cropped.
			$data = array_map( 'absint', $crop_details['cropDetails'] );

			$cropped = wp_crop_image( $attachment_id, $data['x1'], $data['y1'], $data['width'],
				$data['height'], $data['dst_width'], $data['dst_height'] );

			/*
			 * Rename the watermarked crop.
			 * $crop_details['path'] is the path the cropped watermarked image.
			 * $cropped is the path to the cropped NON watermarked image.
			 */
			$rename_result = rename( $cropped, $crop_details['path'] );

			if ( false == $rename_result ) {
				error_log( 'Error replacing cropped watermark with cropped NON-watermark.' );
			}
		}
	}

	/**
	 * Retreive an attachment id from a URL address or absolute file path
	 *
	 * Note: The path must contain "/uploads/".
	 *
	 * @param string $path
	 *
	 * @return int or false
	 */
	public function get_attachement_id_from_path( $path ) {
		// Trim the path:
		$path = trim( $path );

		// Is the path a URL or file path?
		$is_url = ( bool ) strpos( $path, '://' );

		// Validate the input:
		if ( empty( $path ) || ( ! $is_url && ! realpath( $path ) ) ) {
			return false;
		}

		// Get the part of the GUI we need:
		preg_match( '#/uploads/.+$#', $path, $matches );
		$guid_part = $matches[0];

		// Check if we have valid search criteria:
		if ( empty( $guid_part ) ) {
			return false;
		}

		// Connect WordPress database:
		global $wpdb;

		// Get the table prefix:
		$table_prefix = $wpdb->prefix;

		// Retrieve the attachment id(s) from the matching posts:
		$id = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT ID FROM " . $table_prefix .
					 "posts WHERE post_type='attachment' AND guid LIKE '%%%s'", $guid_part ) );

		// Return the result(s):
		return $id;
	}
}
