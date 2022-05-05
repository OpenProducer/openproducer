<?php
/**
 * BoldGrid Source Code
 *
 * @package   Boldgrid_Inspirations_Admin_Menu
 * @copyright BoldGrid.com
 * @version   $Id$
 * @author    BoldGrid.com <wpb@boldgrid.com>
 */

/**
 * BoldGrid Inspirations Admin Menu.
 *
 * Methods for managing dashboard menus.
 *
 * @since 1.4.1
 */
class Boldgrid_Inspirations_Admin_Menu {

	/**
	 * Remove a submenu item.
	 *
	 * This function already exists natively in WordPress, remove_submenu_page().
	 *
	 * Natively, this function can be easy to use.
	 * Remove "Themes":
	 * remove_submenu_page( 'themes.php', 'themes.php' );
	 *
	 * However, this function can be a little confusing too.
	 * Remove "Header":
	 * remove_submenu_page( 'themes.php', 'customize.php?return=%2Fwp-admin%2Findex.php&#038;autofocus%5Bcontrol%5D=header_image' );
	 *
	 * To make this function easier to use, we'll remove submenu pages by their Title instead of
	 * their slug.
	 *
	 * @since 1.4.1
	 *
	 * @global array $submenu
	 *
	 * @param  string $parent Parent menu slug, such as themes.php.
	 * @param  string $title  Title of menu item to remove, such as "Background".
	 * @return bool           Returns true when a submenu item has been removed.
	 */
	public static function remove_submenu_page( $parent, $title ) {
		global $submenu;

		if ( empty( $submenu[ $parent ] ) ) {
			return false;
		}

		foreach ( $submenu[ $parent ] as $key => $item ) {
			if ( $title === $item[0] ) {
				unset( $submenu[ $parent ][ $key ] );
				return true;
			}
		}

		return false;
	}
}
