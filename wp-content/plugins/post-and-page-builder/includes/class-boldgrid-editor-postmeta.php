<?php
/**
 * Class: Boldgrid_Editor_Postmeta
*
* Save post meta for previews, in lue of post meta revisions.
*
* @since      1.6
* @package    Boldgrid_Editor
* @subpackage Boldgrid_Editor_Postmeta
* @author     BoldGrid <support@boldgrid.com>
* @link       https://boldgrid.com
*/

/**
 * Class: Boldgrid_Editor_Postmeta
*
* Save post meta for previews, in lue of post meta revisions.
*
* @since      1.6
*/
class Boldgrid_Editor_Postmeta {

	/**
	 * Init the class.
	 *
	 * @since 1.6
	 */
	public function init() {
		add_action( 'save_post', array( $this, 'save' ), 10, 2 );
	}

	/**
	 * When saving posts save post post meta associated with the post.
	 *
	 * @since 1.6
	 */
	public function save() {
		if ( ! empty( $_POST['wp-preview'] ) ) {
			Boldgrid_Editor_Option::update( 'preview_meta', array(
				'template' => isset( $_POST['page_template'] ) ? sanitize_text_field( $_POST['page_template'] ) : null,
				'boldgrid_hide_page_title' => isset( $_POST['boldgrid-display-post-title'] ) ?
					intval( $_POST['boldgrid-display-post-title'] ) : null,
			) );
		}
	}
}
