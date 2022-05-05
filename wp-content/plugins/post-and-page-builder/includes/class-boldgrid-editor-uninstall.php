<?php
/**
* File: class-boldgrid-editor-uninstall.php
*
* Uninstall process.
*
* @since      1.6
* @package    Boldgrid_Editor
* @subpackage Boldgrid_Editor_Uninstall
* @author     BoldGrid <support@boldgrid.com>
* @link       https://boldgrid.com
*/

/**
 * Class: Boldgrid_Editor_Uninstall
*
* Uninstall process.
*
* @since      1.6
*/
class Boldgrid_Editor_Uninstall {

	/**
	 * Upon deleting the plugin. Delete saved modifications.
	 *
	 * @since 1.6
	 *
	 * @return
	 */
	public static function on_delete() {

		// Disabled this, it may cause undesirable effects.
		// Boldgrid_Editor_Option::clear_all();
	}
}
