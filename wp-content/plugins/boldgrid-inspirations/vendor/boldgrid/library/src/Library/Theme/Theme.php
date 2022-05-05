<?php //phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * BoldGrid Library Theme Theme.
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
 * Theme Class.
 *
 * Stores and retrieves various data for the themes on the site.
 *
 * Boldgrid\Library\Library\Theme\Theme class.
 *
 * @since 2.12.0
 */
class Theme {
	/**
	 * WP_Theme Object.
	 *
	 * @since 2.12.2
	 * @var WP_Theme
	 */
	public $wp_theme;

	/**
	 * Stylesheet.
	 *
	 * @since 2.12.2
	 * @var string
	 */
	public $stylesheet;

	/**
	 * Version.
	 *
	 * @since 2.12.2
	 * @var string
	 */
	public $version;

	/**
	 * Parent Theme.
	 *
	 * @since 2.12.2
	 * @var string
	 */
	public $parent;

	/**
	 * Is Active.
	 *
	 * @since 2.12.2
	 * @var bool
	 */
	public $isActive;

	/**
	 * Has Update.
	 *
	 * @since 2.12.2
	 * @var bool
	 */
	public $hasUpdate;

	/**
	 * UpdateData.
	 *
	 * @since 2.12.2
	 * @var UpdateData
	 */
	public $updateData;

	/**
	 * Constructor.
	 *
	 * @since 2.12.2
	 *
	 * @param \WP_Theme $wp_theme The WP_Theme object for this theme.
	 */
	public function __construct( \WP_Theme $wp_theme ) {
		$this->wp_theme   = $wp_theme;
		$this->stylesheet = $this->wp_theme->__get( 'stylesheet' );
		$this->version    = $this->wp_theme->__get( 'version' );
		$this->getParent();
		$this->setHasUpdate();
		$this->setIsActive();
	}

	/**
	 * Set UpdateData.
	 *
	 * @since 2.12.2
	 *
	 * @param bool $force Whether or not to force fetching from API.
	 */
	public function setUpdateData( $force = false ) {
		$this->updateData = new UpdateData( $this, null, $force );
	}

	/**
	 * Determines the parent theme if this theme is a child theme.
	 *
	 * @since 2.12.2
	 *
	 * @return string
	 */
	public function getParent() {
		$this->parent = $this->wp_theme->parent();
		return $this->parent;
	}

	/**
	 * Determine if this is the active theme or not.
	 *
	 * @since 2.12.2
	 */
	private function setIsActive() {
		$active_theme = wp_get_theme();
		if ( $active_theme->__get( 'stylesheet' ) === $this->stylesheet ) {
			$this->isActive = true;
		} else {
			$this->isActive = false;
		}
	}

	/**
	 * Determine if this theme has an update available.
	 *
	 * @since 2.12.2
	 */
	public function setHasUpdate() {
		$transient = get_site_transient( 'update_themes' );
		if ( $transient && isset( $transient->response ) ) {
			$transient = get_site_transient( 'update_themes' )->response;
		} else {
			$transient = null;
		}

		$transient = null !== $transient ? $transient : array();

		if ( array_key_exists( $this->stylesheet, $transient ) ) {
			$this->hasUpdate = true;
		} else {
			$this->hasUpdate = false;
		}
	}
}
