<?php //phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * BoldGrid Library Plugin Page.
 *
 * Library package uses different naming convention
 * phpcs:disable WordPress.NamingConventions.ValidVariableName
 * phpcs:disable WordPress.NamingConventions.ValidFunctionName
 *
 * @package Boldgrid\Plugin
 *
 * @since 2.12.0
 *
 * @author BoldGrid <wpb@boldgrid.com>
 */
namespace Boldgrid\Library\Library\Plugin;

use Boldgrid\Library\Library\Plugin\Notice;

/**
 * Generic page class.
 *
 * This class represents a specific page used by the
 * Boldgrid\Library\Library\Plugin\Plugin class.
 *
 * @since 2.12.0
 */
class Page {

	/**
	 * Plugin
	 *
	 * The Boldgrid\Library\Library\Plugin\Plugin Object
	 * that this page belongs to.
	 *
	 * @since 2.12.0
	 * @var Plugin
	 * @access protected
	 */
	protected $plugin;

	/**
	 * Page Slug
	 *
	 * @since 2.12.0
	 * @var string
	 * @access protected
	 */
	protected $slug;

	/**
	 * Plugin Config
	 *
	 * The Config array passed to this page.
	 *
	 * @since 2.12.0
	 * @var array
	 */
	protected $pluginConfig = array();

	/**
	 * Page Notice
	 *
	 * An array of Notices for this page.
	 *
	 * @since 2.12.0
	 * @var array
	 */
	protected $notices;

	/**
	 * Constructor.
	 *
	 * @since 2.12.0
	 * @param Plugin $plugin object that this page belongs to.
	 * @param string $slug For example: "plugin" from plugin/plugin.php.
	 */
	public function __construct( Plugin $plugin, $slug ) {

		$this->setPlugin( $plugin );

		$this->setPluginConfig();

		$this->setNotices();

		$this->setSlug( $slug );
	}

	/**
	 * Get Plugin.
	 *
	 * @since 2.12.0
	 *
	 * @return Plugin
	 */
	public function getPlugin() {
		return $this->plugin;
	}

	/**
	 * Set Plugin.
	 *
	 * @since 2.12.0
	 *
	 * @param Plugin $plugin Plugin Object.
	 */
	private function setPlugin( $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Get plugin Config.
	 *
	 * @since 2.12.0
	 *
	 * @return array
	 */
	public function getPluginConfig() {
		return $this->pluginConfig;
	}

	/**
	 * Set plugin Config.
	 *
	 * @since 2.12.0
	 *
	 * @access private
	 */
	private function setPluginConfig() {
		$pluginConfig       = $this->getPlugin()->getPluginConfig();
		$this->pluginConfig = $pluginConfig;
	}

	/**
	 * Get an array of Notice Counts for this page.
	 *
	 * @since 2.12.0
	 *
	 * @return array
	 */
	public function getNotices() {
		$notices = array();
		foreach ( $this->notices as $notice ) {
			if ( $notice->getPageSlug() === $this->slug ) {
				$notices[] = $notice;
			}
		}
		return $notices;
	}

	/**
	 * Get Notice by ID.
	 *
	 * @since 2.12.0
	 *
	 * @param string $id ID of the notice.
	 * @return NoticeCount
	 */
	public function getNoticeById( $id ) {
		foreach ( $this->getNotices() as $notice ) {
			if ( $notice->getId() === $id ) {
				return $notice;
			}
		}
	}

	/**
	 * Set an array of Notice Counts for this page.
	 *
	 * @since 2.12.0
	 */
	public function setNotices() {
		$notices = array();
		if ( isset( $this->getPluginConfig()['page_notices'] ) ) {
			foreach ( $this->getPluginConfig()['page_notices'] as $notice ) {
				$notices[] = new Notice( $this->plugin, $notice );
			}
		}
		$this->notices = $notices;
	}

	/**
	 * Set Page Notices to Read.
	 *
	 * @since 2.12.0
	 *
	 * @param bool $setToUnread If true, then this sets the notice to Unread.
	 */
	public function setAllNoticesRead( $setToUnread = false ) {
		foreach ( $this->getNotices() as $notice ) {
			$notice->setIsUnread( $setToUnread );
		}
	}

	/**
	 * Get Unread Count.
	 *
	 * @since 2.12.0
	 *
	 * @return int
	 */
	public function getUnreadCount() {
		$unreadCount = 0;
		foreach ( $this->getNotices() as $notice ) {
			if ( $notice->getIsUnread() && $notice->getPageSlug() === $this->getSlug() ) {
				$unreadCount++;
			}
		}
		return $unreadCount;
	}

	/**
	 * Get Unread Markup.
	 *
	 * Returns UnreadCount with html markup.
	 *
	 * @since 2.12.0
	 *
	 * @return string
	 */
	public function getUnreadMarkup() {
		$count = $this->getUnreadCount();
		if ( $count > 0 ) {
			return '<span class="bglib-unread-notice-count">' . esc_html( $count ) . '</span>';
		} else {
			return '<span class="bglib-unread-notice-count hidden"></span>';
		}
	}

	/**
	 * Get page Slug.
	 *
	 * @since 2.12.0
	 *
	 * @return string
	 */
	public function getSlug() {
		return $this->slug;
	}

	/**
	 * Set page Slug.
	 *
	 * @since 2.12.0
	 *
	 * @param string $slug Page Slug.
	 */
	public function setSlug( $slug ) {
		$this->slug = $slug;
	}
}
