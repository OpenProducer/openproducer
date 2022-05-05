<?php //phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * BoldGrid Library Plugin Page Notice.
 *
 * Library package uses different naming convention.
 * phpcs:disable WordPress.NamingConventions.ValidVariableName
 * phpcs:disable WordPress.NamingConventions.ValidFunctionName
 *
 * @package Boldgrid\Plugin
 *
 * @since 2.12.0
 * @author BoldGrid <wpb@boldgrid.com>
 */
namespace Boldgrid\Library\Library\Plugin;

/**
 * Notice class for Plugin\Page.
 *
 * This class is a specific Notice.
 * used by the Boldgrid\Library\Library\Plugin\Page.
 * and Boldgrid\Library\Library\Plugin\Plugin classes.
 *
 * @since 2.12.0
 */
class Notice {

	/**
	 * Notice ID.
	 *
	 * @since 2.12.0
	 * @var string
	 * @access protected
	 */
	protected $id;

	/**
	 * Notice Page slug.
	 *
	 * @since 2.12.0
	 * @var string
	 * @access protected
	 */
	protected $pageSlug;

	/**
	 * Notice Version.
	 *
	 * Version of plugin this notice was added on.
	 *
	 * @since 2.12.0
	 * @var string
	 * @access protected
	 */
	protected $version;

	/**
	 * Plugin Object.
	 *
	 * Plugin Object this belongs to.
	 *
	 * @since 2.12.0
	 * @var Plugin
	 * @access protected
	 */
	protected $plugin;

	/**
	 * Notice Is Unread.
	 *
	 * Specifies if the Notice is Unread or not.
	 *
	 * @since 2.12.0
	 * @var bool
	 * @access protected
	 */
	protected $isUnread;

	/**
	 * Constructor.
	 *
	 * @since 2.12.0
	 *
	 * @param Plugin $plugin Plugin instance that this Notice belongs to.
	 * @param array  $notice {
	 *     An array of notice values.
	 *
	 *     @type string $id notice ID.
	 *     @type string $page slug of the page this notice is on.
	 *     @type string $version version of plugin this notice was released on.
	 * }
	 */
	public function __construct( Plugin $plugin, array $notice ) {
		$this->setId( $notice['id'] );

		if ( $this->alreadyExists() ) {
			$originalNotice = $this->getFromOptions( $notice['id'] )[0];
			$this->id       = $originalNotice['id'];
			$this->pageSlug = $originalNotice['page'];
			$this->plugin   = $plugin;
			$this->noticeVersionChanged( $originalNotice, $notice );
			$this->updateNoticeOption( $this );
		} else {
			$this->id       = $notice['id'];
			$this->pageSlug = $notice['page'];
			$this->version  = $notice['version'];
			$this->isUnread = true;
			$this->plugin   = $plugin;
		}
	}

	/**
	 * Get notice ID.
	 *
	 * @since 2.12.0
	 *
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set notice ID.
	 *
	 * @since 2.12.0
	 *
	 * @param string $id Notice ID.
	 */
	public function setId( $id ) {
		$this->id = $id;
	}

	/**
	 * Get slug of the page this feature is on.
	 *
	 * @since 2.12.0
	 *
	 * @return string
	 */
	public function getPageSlug() {
		return $this->pageSlug;
	}

	/**
	 * Sets the pageSlug.
	 *
	 * @since 2.12.0
	 *
	 * @param string $pageSlug Notice Page.
	 */
	public function setPageSlug( $pageSlug ) {
		$this->pageSlug = $pageSlug;
	}

	/**
	 * Get version of plugin this notice was added on.
	 *
	 * @since 2.12.0
	 *
	 * @return string
	 */
	public function getVersion() {
		return $this->version;
	}

	/**
	 * Set version of plugin this notice was added on.
	 *
	 * @since 2.12.0
	 *
	 * @param string $version Version of plugin this notice was added on.
	 */
	public function setVersion( $version ) {
		$this->version = $version;
	}

	/**
	 * Maybe the notice count should show.
	 *
	 * @since 2.12.0
	 *
	 * @return bool
	 */
	public function maybeShow() {
		$pluginVersion     = $this->plugin->getPluginData()['Version'];
		$versionIsNotFirst = $this->plugin->firstVersionCompare( $pluginVersion, '<' );
		$featureIsNewer    = $this->plugin->firstVersionCompare( $this->version, '<' );

		return ( $featureIsNewer && $versionIsNotFirst );
	}

	/**
	 * Get isUnread Value.
	 *
	 * @since 2.12.0
	 *
	 * @return bool
	 */
	public function getIsUnread() {
		if ( $this->maybeShow() ) {
			return $this->isUnread;
		}
		return false;
	}

	/**
	 * Set the notice as read or unread.
	 *
	 * @since 2.12.0
	 *
	 * @param bool $isUnread Specifies if the Notice is Unread or not.
	 */
	public function setIsUnread( $isUnread ) {
		$this->isUnread = $isUnread;
		$this->updateNoticeOption();
	}

	/**
	 * Determine if Notice Exists in Options Table.
	 *
	 * @since 2.12.0
	 *
	 * @return bool true if noticeId exists.
	 */
	public function alreadyExists() {
		return ! empty( $this->getFromOptions( $this->id ) );
	}

	/**
	 * Get Notice instance from wp_options row.
	 *
	 * @since 2.12.0
	 *
	 * @param  string $noticeId ID of the specific notice.
	 * @return array
	 *     @type Notice Notice Instance.
	 *     @type int Index of Notice in Options array.
	 */
	public function getFromOptions( $noticeId ) {
		$option      = get_option( 'boldgrid_plugin_page_notices', array() );
		$optionCount = count( $option );

		$i = 0;
		foreach ( $option as $notice ) {
			if ( $option[ $i ]['id'] === $noticeId ) {
				return array( $option[ $i ], $i );
			}
			$i++;
		}
		return array();
	}

	/**
	 * Update Notice Option.
	 *
	 * Updates option row in wp_options table. If The NoticeId.
	 * already exists, then it's object is replaced with $this.
	 * Otherwise, $this is appended to the array.
	 *
	 * @since 2.12.0
	 */
	public function updateNoticeOption() {
		$option = get_option( 'boldgrid_plugin_page_notices', array() );
		if ( $this->alreadyExists() ) {
			$option[ $this->getFromOptions( $this->id )[1] ] = array(
				'id'       => $this->id,
				'isUnread' => $this->isUnread,
				'version'  => $this->version,
				'page'     => $this->pageSlug,
			);
		} else {
			$option[] = array(
				'id'       => $this->id,
				'isUnread' => $this->isUnread,
				'version'  => $this->version,
				'page'     => $this->pageSlug,
			);
		}
		update_option( 'boldgrid_plugin_page_notices', $option );
	}

	/**
	 * Get Plugin.
	 *
	 * Get plugin instance that this notice belongs to.
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
	 * Set plugin Instance that this notice belongs to.
	 *
	 * @since 2.12.0
	 *
	 * @param Plugin $plugin Plugin Object this belongs to.
	 */
	public function setPlugin( Plugin $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Notice Version Changed.
	 *
	 * Determines if an existing Notice's version number has changed.
	 * since it was placed in options table. If it has changed, then it marks.
	 * the notice unread again. This will help bring attention to old notices.
	 * that may have been revised.
	 *
	 * @since 2.12.0
	 *
	 * @param array $originalNotice The notice already in the DB.
	 * @param array $newNotice The new notice pulled from config.
	 */
	private function noticeVersionChanged( array $originalNotice, array $newNotice ) {
		if ( version_compare( $originalNotice['version'], $newNotice['version'], 'ne' ) ) {
			$this->version  = $newNotice['version'];
			$this->isUnread = true;
		} else {
			$this->version  = $originalNotice['version'];
			$this->isUnread = $originalNotice['isUnread'];
		}
	}
}
