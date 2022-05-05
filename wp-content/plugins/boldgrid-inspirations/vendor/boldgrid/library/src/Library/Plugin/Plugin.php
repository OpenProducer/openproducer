<?php //phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * BoldGrid Library Plugin Plugin.
 *
 * Library package uses different naming convention.
 * phpcs:disable WordPress.NamingConventions.ValidVariableName
 * phpcs:disable WordPress.NamingConventions.ValidFunctionName
 *
 * @package Boldgrid\Plugin
 *
 * @since 2.7.7
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library\Plugin;

use Boldgrid\Library\Library\Configs;
use Boldgrid\Library\Library\Settings;
use Boldgrid\Library\Library\Plugin\Page;

/**
 * Generic plugin class.
 *
 * This class represents a specific plugin.
 *
 * @since 2.7.7
 */
class Plugin {
	/**
	 * An array of children plugins.
	 *
	 * Elements of this array are of this class, Plugin (recursion).
	 *
	 * @since 2.10.0
	 * @var array
	 * @access protected
	 */
	protected $childPlugins = array();

	/**
	 * Plugin file.
	 *
	 * For example: plugin/plugin.php.
	 *
	 * @since 2.9.0
	 * @var string
	 * @access protected
	 */
	protected $file;

	/**
	 * Plugin specific config array.
	 *
	 * @since 2.12.0
	 *
	 * @var array
	 */
	public $pluginConfig = array();

	/**
	 * Whether or not this plugin is installed.
	 *
	 * @since 2.10.0
	 * @var bool
	 * @access protected
	 */
	protected $isInstalled;

	/**
	 * Path to the plugin.
	 *
	 * For example, ABSPATH/wp-content/plugins/plugin/plugin.php.
	 *
	 * @since 2.10.0
	 * @var string
	 */
	protected $path;

	/**
	 * Plugin pages.
	 *
	 * This is an array of Boldgrid\Library\Library\Plugin\Page.
	 * objects based on the 'pages' list in the plugin Config.
	 * If no plugin config is passed during instantiation,
	 * this will be an empty array.
	 *
	 * @since 2.12.0
	 * @var array
	 * @access protected
	 */
	protected $pages = array();

	/**
	 * Plugin data, as retrieved from get_plugin_data().
	 *
	 * @since 2.9.0
	 * @var array
	 * @access protected
	 */
	public $pluginData = array();

	/**
	 * Update plugin data, as retrieved from 'update_plugins' site transient.
	 *
	 * @since 2.9.0
	 * @var array
	 * @access protected
	 */
	protected $updatePlugins;

	/**
	 * Plugin slug.
	 *
	 * Examples: boldgrid-backup, boldgrid-backup-premium, etc.
	 *
	 * @since 2.7.7
	 *
	 * @var string
	 * @access protected
	 */
	protected $slug;

	/**
	 * Plugin Update Data.
	 *
	 * @since 2.12.2
	 *
	 * @var Response
	 */
	public $updateData;

	/**
	 * Constructor.
	 *
	 * @since 2.7.7
	 *
	 * @param array $pluginParams An array of parameters passed from Plugin Factory.
	 * @param array $pluginConfig Optionally passed array of Plugin Configuration Options.
	 */
	public function __construct( $pluginParams, $pluginConfig = null ) {

		// Backwards Compatability: Some plugins don't know they're supposed to use the Factory.
		// So this will direct them there. Once ALL plugins are using libary > 2.12.2 this can be removed.
		if ( is_string( $pluginParams ) ) {
			$plugin       = Factory::create( $pluginParams, $pluginConfig );
			$pluginParams = $plugin->getPluginParams();
		}

		foreach ( $pluginParams as $paramKey => $paramValue ) {
			$this->$paramKey = $paramValue;
		}

		$this->updateData = ! empty( $this->getUpdateData ) ? $this->setUpdateData() : false;

		$this->setPages();
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
	 * Version compare for the first version of this plugin.
	 *
	 * @since 2.11.0
	 *
	 * @param  string $version2 The second version number.
	 * @param  string $operator The relationship to test for.
	 * @return bool
	 */
	public function firstVersionCompare( $version2, $operator ) {
		// The first version of this plugin.
		$firstVersion = $this->getFirstVersion();

		return version_compare( $firstVersion, $version2, $operator );
	}

	/**
	 * Get the url to activate this plugin.
	 *
	 * @since 2.10.2
	 *
	 * @return string
	 */
	public function getActivateUrl() {
		return wp_nonce_url(
			self_admin_url( 'plugins.php?action=activate&plugin=' . $this->file ),
			'activate-plugin_' . $this->file
		);
	}

	/**
	 * Get an array of the plugin's icons.
	 *
	 * @since 2.9.0
	 *
	 * @return array
	 */
	public function getIcons() {
		$updates = $this->getUpdatePlugins();

		$icons = array();
		$icons = ! empty( $updates->response[ $this->file ]->icons ) ? $updates->response[ $this->file ]->icons : $icons;
		$icons = ! empty( $updates->no_update[ $this->file ]->icons ) ? $updates->no_update[ $this->file ]->icons : $icons;

		return $icons;
	}

	/**
	 * Get the child plugins array.
	 *
	 * @since 2.10.0
	 *
	 * @return array
	 */
	public function getChildPlugins() {
		return $this->childPlugins;
	}

	/**
	 * Get data via the get_plugin_data() function.
	 *
	 * If a $key is passed in, return that key, otherwise return all data.
	 *
	 * @since 2.9.0
	 *
	 * @param string $key The data key to return.
	 * @return mixed
	 */
	public function getData( $key = null ) {
		$data = $this->getPluginData();

		return empty( $key ) ? $data : $data[ $key ];
	}

	/**
	 * Return the download url for a plugin.
	 *
	 * @since 2.7.7
	 *
	 * @todo This method only works for plugins hosted at the BoldGrid api server.
	 *
	 * @return string
	 */
	public function getDownloadUrl() {
		$url = Configs::get( 'api' ) . '/v1/plugins/' . $this->slug . '/download';

		$url = add_query_arg( 'key', Configs::get( 'key' ), $url );

		return $url;
	}

	/**
	 * Get the url to install this plugin.
	 *
	 * @since 2.10.2
	 *
	 * @return string
	 */
	public function getInstallUrl() {
		return wp_nonce_url(
			self_admin_url( 'update.php?action=install-plugin&plugin=' . $this->slug ),
			'install-plugin_' . $this->slug
		);
	}

	/**
	 * Get isInstalled.
	 *
	 * @since 2.10.0
	 *
	 * @return bool
	 */
	public function getIsInstalled() {
		return $this->isInstalled;
	}

	/**
	 * Get file.
	 *
	 * @since 2.9.0
	 *
	 * @return string
	 */
	public function getFile() {
		return $this->file;
	}

	/**
	 * If a new version of a plugin is available, return the new version.
	 *
	 * @since 2.9.0
	 *
	 * @return string
	 */
	public function getNewVersion() {
		$updates = $this->getUpdatePlugins();

		return ! empty( $updates->response[ $this->file ]->new_version ) ? $updates->response[ $this->file ]->new_version : '0';
	}

	/**
	 * Get plugin specific config array.
	 *
	 * @return array
	 * @since 2.12.0
	 */
	public function getPluginConfig() {
		return $this->pluginConfig;
	}

	/**
	 * Get plugin data.
	 *
	 * @since 2.9.0
	 *
	 * @return array
	 */
	public function getPluginData() {
		if ( empty( $this->pluginData ) && $this->isInstalled ) {
			$this->pluginData = get_plugin_data( $this->path );
		}
		return $this->pluginData;
	}

	/**
	 * Get the plugins_checked data for this plugin.
	 *
	 * @since 2.11.0
	 *
	 * @return array
	 */
	public function getPluginsChecked() {
		$pluginsChecked = Settings::getKey( 'plugins_checked', array() );

		$pluginsChecked = ! empty( $pluginsChecked[ $this->file ] ) ? $pluginsChecked[ $this->file ] : array();

		return $pluginsChecked;
	}

	/**
	 * Get slug.
	 *
	 * @since 2.9.0
	 *
	 * @return string
	 */
	public function getSlug() {
		return $this->slug;
	}

	/**
	 * Create page objects based on $this->config parameter.
	 *
	 * @since 2.12.0
	 * @access private
	 */
	public function setPages() {
		$pages = array();
		if ( isset( $this->pluginConfig['pages'] ) ) {
			foreach ( $this->pluginConfig['pages'] as $page ) {
				$pages[] = new Page( $this, $page );
			}
		}
		$this->pages = $pages;
	}

	/**
	 * Set all plugin notices as read.
	 *
	 * @since 2.12.0
	 *
	 * @param bool $setToUnread if set to true, marks all items unread instead.
	 */
	public function setAllNoticesRead( $setToUnread = false ) {
		foreach ( $this->getPages() as $page ) {
			$page->setAllNoticesRead( $setToUnread );
		}
	}

	/**
	 * Get Plugin Pages.
	 *
	 * @since 2.12.0
	 *
	 * @return array
	 * @access public
	 */
	public function getPages() {
		return $this->pages;
	}

	/**
	 * Get Page by Slug.
	 *
	 * @since 2.12.0
	 *
	 * @param string $slug The Plugin slug.
	 * @return Page
	 */
	public function getPageBySlug( $slug ) {
		foreach ( $this->getPages() as $page ) {
			if ( $page->getSlug() === $slug ) {
				return $page;
			}
		}
	}

	/**
	 * Set PluginData.
	 *
	 * @since 2.12.2
	 *
	 * @param array $pluginData Plugin Data Array.
	 */
	public function setPluginData( $pluginData ) {
		$this->pluginData = $pluginData;
	}

	/**
	 * Get updatePlugins data.
	 *
	 * @since 2.9.0
	 *
	 * @return object
	 */
	public function getUpdatePlugins() {
		if ( empty( $this->updatePlugins ) ) {
			$this->updatePlugins = get_site_transient( 'update_plugins' );
		}

		return $this->updatePlugins;
	}

	/**
	 * Whether or not a plugin has an update available.
	 *
	 * @since 2.9.0
	 *
	 * @return bool
	 */
	public function hasUpdate() {
		$updates = $this->getUpdatePlugins();

		return ! empty( $updates->response[ $this->file ] );
	}

	/**
	 * Whether or not this plugin is active.
	 *
	 * @since 2.9.0
	 *
	 * @return bool
	 */
	public function isActive() {
		return is_plugin_active( $this->file );
	}

	/**
	 * Get the first version of this plugin installed.
	 *
	 * @since 2.11.0
	 *
	 * @return string
	 */
	public function getFirstVersion() {
		$pluginsChecked = $this->getPluginsChecked();

		if ( ! empty( $pluginsChecked ) ) {
			/*
			 * Get the first version of the plugin, which is the first key from plugins_checked.
			 *
			 * Ideal method is to use array_key_first, but that requires php 7+. Alternative is to use
			 * this polyfill.
			 *
			 * @link https://www.php.net/manual/en/function.array-key-first.php
			 */
			foreach ( $pluginsChecked as $key => $unused ) {
				$firstVersion = $key;
				break;
			}
		} else {
			// If the data is missing for some reason, return the plugin's current version.
			$firstVersion = $this->getData( 'Version' );
		}
		return $firstVersion;
	}

	/**
	 * Get Unread Count.
	 *
	 * Get unread count integer.
	 *
	 * @since 2.12.0
	 *
	 * @return int
	 */
	public function getUnreadCount() {
		$unreadCount = 0;
		foreach ( $this->getPages() as $page ) {
			$unreadCount += $page->getUnreadCount();
		}
		return $unreadCount;
	}

	/**
	 * Get Unread markup.
	 *
	 * Get unread count with html markup.
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
	 * Get Plugin Params.
	 *
	 * Gets an array of all plugin property parameters.
	 * This is useful for backwards compatibility with BoldGrid Plugins.
	 * That do not implement the Factory methods.
	 * Once ALL plugins are Library > 2.12.2 this can be removed.
	 *
	 * @since 2.12.2
	 *
	 * @return array
	 */
	public function getPluginParams() {
		return get_object_vars( $this );
	}
}
