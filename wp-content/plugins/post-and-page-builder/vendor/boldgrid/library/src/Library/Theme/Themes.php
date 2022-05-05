<?php //phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * BoldGrid Library Theme Themes.
 *
 * Library package uses different naming convention.
 * phpcs:disable WordPress.NamingConventions.ValidVariableName
 * phpcs:disable WordPress.NamingConventions.ValidFunctionName
 *
 * @package Boldgrid\Theme
 *
 * @since 2.12.2
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library\Theme;

/**
 * Themes Class.
 *
 * This class stores all Theme objects and helps retrieve them.
 * Boldgrid\Library\Library\Theme\Theme class.
 *
 * @since 2.12.0
 */
class Themes {
	/**
	 * Themes array.
	 *
	 * @since 2.12.2
	 * @var array
	 */
	public $themes;

	/**
	 * Constructor.
	 *
	 * @since 2.12.2
	 */
	public function __construct() {
		$this->themes = array();
		$wp_themes    = wp_get_themes();
		foreach ( $wp_themes as $wp_theme ) {
			$this->themes[] = new Theme( $wp_theme );
		}
	}

	/**
	 * Returns an array of Theme objects.
	 *
	 * @since 2.12.2
	 *
	 * @return array
	 */
	public function get() {
		return $this->themes;
	}

	/**
	 * Get Theme from Stylesheet.
	 *
	 * @since 2.12.2
	 *
	 * @param string $stylesheet Theme's Stylesheet.
	 * @return Theme
	 */
	public function getFromStylesheet( $stylesheet ) {
		foreach ( $this->get() as $theme ) {
			if ( $theme->stylesheet === $stylesheet ) {
				return $theme;
			}
		}
		return new \WP_Error( 'boldgrid_theme_not_found', sprintf( 'No theme could be found with the stylesheet %s.', $stylesheet ) );
	}

	/**
	 * Get the Active Theme.
	 *
	 * @since 2.12.2
	 *
	 * @return Theme
	 */
	public function getActive() {
		return new Theme( wp_get_theme() );
	}
}
