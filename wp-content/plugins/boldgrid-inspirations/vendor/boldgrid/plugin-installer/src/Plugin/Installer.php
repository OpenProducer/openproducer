<?php
/**
 * BoldGrid Library Plugin Installer
 *
 * @package Boldgrid\Plugin
 *
 * @version 1.0.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Plugin;

use Boldgrid\Library\Library;
use Boldgrid\Library\Util;

/**
 * BoldGrid Library Plugin Installer Class.
 *
 * This class is responsible for installing BoldGrid plugins in the WordPress admin's
 * "Plugins > Add New" section.
 *
 * @since 1.0.0
 */
class Installer {
	/**
	 * @access protected
	 *
	 * @var array $configs   Configuration options for the plugin installer.
	 * @var array $transient Data from boldgrid_plugins transient.
	 */
	protected
		$configs,
		$releaseChannel,
		$forms,
		$transient;

	/**
	 * Updates.
	 *
	 * This property is set via self::setUpdates().
	 *
	 * Originally, self::setUpdates() was called within the constructor. This wasn't a problem until
	 * it was realized that the constructor was being triggered on every admin page load - and through
	 * mostly the self::setUpdates() call, was adding roughly .20 seconds to every admin page load.
	 *
	 * The $updates property is only used within the self::init() method, and so the self::setUpdates()
	 * call has been moved from the constructor and to the self::init() method.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var array $updates Plugin update info as retrieved from get_plugin_updates().
	 */
	protected $updates;

	/**
	 * Initialize class and set class properties.
	 *
	 * @todo Optimize the loading of this class. This constructor is being initialized on every admin
	 * page. Be careful what you add to it.
	 *
	 * @since 1.0.0
	 *
	 * @param array $configs Array of configuration options for the BG Library.
	 */
	public function __construct( $configs, Library\ReleaseChannel $releaseChannel ) {
		$this->setConfigs( $configs );
		$this->releaseChannel = $releaseChannel;

		if ( class_exists( 'Boldgrid\Library\Form\AddNew' ) ) {
			$this->forms = new \Boldgrid\Library\Form\AddNew();
		}

		if ( $this->configs['enabled'] && ! empty( $this->configs['plugins'] ) ) {
			$this->setPluginData( $this->configs['plugins'] );
			$this->setTransient();
			$license = new Library\License;
			$this->license = Library\Configs::get( 'licenseData' );
			$this->ajax();
			Library\Filter::add( $this );
		}
	}

	/**
	 * Set the configs class property.
	 *
	 * @since 1.0.0
	 *
	 * @param array $configs Array of configuration options for plugin installer.
	 *
	 * @return object $configs The configs class property.
	 */
	protected function setConfigs( $configs ) {
		return $this->configs = $configs;
	}

	/**
	 * Set the transient class property.
	 *
	 * @since 1.0.0
	 *
	 * @return object $transient The transient class property.
	 */
	protected function setTransient() {
		return $this->transient = get_site_transient( 'boldgrid_plugins', null ) ?
			get_site_transient( 'boldgrid_plugins' ) :
			$this->getPluginInformation( $this->configs['plugins'] );
	}

	/**
	 * Sets local plugin data for plugin configs to use in class.
	 *
	 * @since 1.0.0
	 *
	 * @param array $plugins Array of plugins to get data for.
	 */
	protected function setPluginData( $plugins ) {

		// Load plugin.php if necessary so method doesn't cause errors.
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		foreach( $plugins as $plugin => $details ) {
			$file = trailingslashit( WP_PLUGIN_DIR ) . $details['file'];

			// Default data to use if plugin isn't loaded locally.
			$data = array(
				'Author' => 'BoldGrid.com',
				'Version' => null,
			);

			if ( file_exists( $file ) && is_readable( $file ) ) {
				$data = get_plugin_data( $file, false );
			}

			$this->configs['plugins'][ $plugin ] = wp_parse_args( $data, $details );
		}
	}

	/**
	 * Set the updates class property.
	 *
	 * @since 1.0.0
	 *
	 * @param array $updates Array of configuration options for plugin installer.
	 *
	 * @return object $updates The configs class property.
	 */
	protected function setUpdates() {
		if ( ! function_exists( 'get_plugin_updates' ) ) {
            require_once ABSPATH . 'wp-admin/includes/update.php';
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return $this->updates = \get_plugin_updates();
	}

	/**
	 * Add "BoldGrid" tab to the plugins install filter bar.
	 *
	 * @since 1.0.0
	 *
	 * @param array $tabs Tabs in the WordPress "Plugins > Add New" filter bar.
	 *
	 * @hook: install_plugins_tabs
	 *
	 * @return array $tabs Tabs in the WordPress "Plugins > Add New" filter bar.
	 */
	public function addTab( $tabs ) {
		$boldgrid = array( 'boldgrid' => 'BoldGrid', );
		$tabs = array_merge( $boldgrid, $tabs );

		return $tabs;
	}

	/**
	 * Filter the Plugin API arguments.
	 *
	 * We use this filter to add the custom "boldgrid" field for the "browse" arg to the
	 * WordPress Plugin API.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $args   WordPress Plugins API arguments.
	 * @param string $action The type of information being requested from the Plugin Install API.
	 *
	 * @hook: plugins_api_args
	 *
	 * @return array $args WordPress Plugins API arguments.
	 */
	public function pluginsApiArgs( $args, $action ) {
		$boldgrid_plugins = get_site_transient( 'boldgrid_plugins' );
		if ( isset( $args->slug ) ) {
			$plugin = $args->slug;
			if ( isset( $boldgrid_plugins->$plugin ) ) {
				$args->browse = 'boldgrid';
			}
		}

		return $args;
	}

	/**
	 * Filter the WordPress Plugins API information before we display it.
	 *
	 * We use this filter to add data to the WordPress Plugins API containing
	 * the information necessary for our outside sources to be installed.
	 *
	 * @since 1.0.0
	 *
	 * @hook: plugins_api
	 *
	 * @param  mixed    $results The results object or array. Default is false false.
	 * @param  string   $action  The type of information being requested from the Plugin Install API.
	 * @param  array    $args    WordPress Plugins API arguments.
	 * @return StdClass $results
	 */
	public function pluginsApi( $results, $action, $args ) {
		$boldgrid_plugins = $this->getTransient() ? : array();

		// Check if we are hooked to query_plugins and browsing 'boldgrid' sorted plugins.
		if ( isset( $args->browse ) && 'boldgrid' === $args->browse ) {

			// Query plugins action.
			if ( 'query_plugins' === $action ) {
				$results = new \stdClass();

				// Set the results for query.
				$results->plugins = array_values( (array) $boldgrid_plugins );
				$results->info = array( 'results' => count( $results->plugins ) );

			// The plugin-information tab expects a different format.
			} elseif ( 'plugin_information' === $action ) {
				if ( ! empty( $boldgrid_plugins->{$args->slug} ) ) {
					$results = $boldgrid_plugins->{$args->slug};
				}
			}
		}

		return $results;
	}

	/**
	 * Filter the Plugin API results.
	 *
	 * We use this filter to add our plugins to the plugins_api results.
	 *
	 * @since 1.0.0
	 *
	 * @hook: plugins_api_result
	 *
	 * @param  mixed    $result Plugins API result.
	 * @param  array    $args   WordPress Plugins API arguments.
	 * @param  string   $action The type of information being requested from the Plugin Install API.
	 * @return StdClass $result WordPress Plugins API result.
	 */
	public function result( $result, $action, $args ) {
		$boldgrid_plugins = $this->getTransient() ? : array();

		// Add data for plugin info tabs in results.
		if ( 'plugin_information' === $action ) {
			if ( ! empty( $boldgrid_plugins->{$args->slug} ) ) {
				unset( $boldgrid_plugins->{$args->slug}->active_installs );
				$result = $boldgrid_plugins->{$args->slug};
			}

		// Allow BoldGrid ( + variations of ) as a search option to show all plugins.
		} else if ( 'query_plugins' === $action ) {
			if ( ! empty( $args->search ) && false !== strpos( strtolower( $args->search ), 'boldgrid' ) ) {

				// Add all boldgrid plugins.
				$result->plugins = array_merge( (array) $boldgrid_plugins, (array) $result->plugins );

				// Count results found.
				$result->info['results'] = count( (array) $result->plugins );
			} else if ( ! empty( $args->search ) ) {
				$found = array();
				foreach ( $boldgrid_plugins as $plugin ) {
					if ( false !== strpos( strtolower( implode( ' ', $plugin->tags ) ), trim( strtolower( $args->search ) ) ) ) {
						$found[] = $plugin;
					}
				}

				// Merge found results.
				$result->plugins = array_merge( $found, (array) $result->plugins );

				// Recount the results found.
				$result->info['results'] = ( $result->info['results'] + count( $found ) );
			}
		}

		return $result;
	}

	/**
	 * Initialize the display of the plugins.
	 *
	 * This code is triggerd on the "Plugins > Add New" page, and renders the content under the "BoldGrid"
	 * section.
	 *
	 * @since 1.0.0
	 *
	 * @hook: install_plugins_boldgrid
	 */
	public function init() {
		$plugins = $this->getTransient();

		// Abort if we don't have any plugins to list.
		if ( ! $plugins ) {
			return;
		}

		/*
		 * Initialize $this->updates.
		 *
		 * The $this->updates property is only used once within this file, and that's within this method.
		 * When we don't initilze the updates, nothing seems to break or trigger a warning.
		 *
		 * @todo Review comment above and possibly remove $this->updates all together.
		 */
		$this->setUpdates();

		?>
		<div class="bglib-plugin-installer">
		<?php
			/**
			 * Filter for plugin array manipulation.
			 *
			 * @since 1.0.0
			 *
			 * @param StdClass $plugins The plugin object for update information.
			 */
			$plugins = apply_filters( 'Boldgrid\Library\Plugin\Installer\init', $plugins );

			foreach ( $plugins as $api ) {
				if( ! isset( $api->name ) || empty( $api->name ) ) {
					continue;
				}

				$button_classes = 'install button';
				$button_text = esc_html__( 'Install Now', 'boldgrid-plugin-installer' );
					$file = Util\Plugin::getPluginFile( $api->slug );
					if ( ! empty( $file ) ) {

						// Has activation already occured? Disable button if so.
						if ( is_plugin_active( $file ) ) {
							$button_classes = 'button disabled';
							$button_text = esc_html__( 'Activated', 'boldgrid-plugin-installer' );

						// Otherwise allow activation button.
						} else {
							$button_classes = 'activate button button-primary';
							$button_text = esc_html__( 'Activate', 'boldgrid-plugin-installer' );
						}
					}

					$button_link = add_query_arg(
						array(
							'action' => 'install-plugin',
							'plugin' => $api->slug,
							'_wpnonce' => wp_create_nonce( "install-plugin_{$api->slug}" ),
						),
						self_admin_url( 'update.php' )
					);

					$button = array(
						'link' => $button_link,
						'classes' => $button_classes,
						'text' => $button_text,
					);

					$modal = add_query_arg(
						array(
							'tab' => 'plugin-information',
							'plugin' => $api->slug,
							'TB_iframe' => 'true',
						),
						self_admin_url( 'plugin-install.php' )
					);

					// Plugin Name ( Consists of name plus version number e.g. BoldGrid Inspirations 1.4 ).
					$name = "$api->name $api->version";

					$premiumSlug = $api->slug . '-premium';
					$pluginClasses = "plugin-card-{$api->slug}";

					$premiumLink = null;
					$premiumUrl  = $this->premiumUrl( $api->slug, $premiumLink );
					$offerPremium = empty( $this->configs['plugins'][ $api->slug ]['hide_premium'] ) &&
						empty( $this->configs['wporgPlugins'][ $api->slug ]['hide_premium'] );

					if ( isset( $this->license->{$premiumSlug} ) || isset( $this->license->{$api->slug} ) ) {
						$pluginClasses = "plugin-card-{$api->slug} premium";
					} elseif ( $offerPremium ) {
						$premiumLink = '
							<li>
								<a href="' . esc_url( $premiumUrl ) . '" class="button get-premium" target="_blank" aria-label="' . sprintf( /* translators: 1 The plugin's name. */ esc_html__( 'Upgrade %s to premium', 'boldgrid-plugin-installer' ), $api->name ) . '">' .
									sprintf( esc_html__( 'Get Premium!', 'boldgrid-plugin-installer' ) ) . '
								</a>
							</li>';
					}

					$messageClasses = 'installer-messages';
					$message = '';

					if ( ! empty( $file ) && ( ! empty( $this->configs['plugins'][ $api->slug ] ) &&
						( $this->configs['plugins'][ $api->slug ]['Version'] !== $api->new_version ) ) ||
						! empty( $this->updates[ $file ] ) ) {
							$messageClasses = "{$messageClasses} update-message notice inline notice-warning notice-alt";
							$updateUrl = add_query_arg(
								array(
									'action'   => 'upgrade-plugin',
									'plugin'   => urlencode( $file ),
									'slug'     => $api->slug,
									'_wpnonce' => wp_create_nonce( "upgrade-plugin_{$api->slug}" ),
								),
								self_admin_url( 'update.php' )
							);
							$updateLink = '<a href="' . $updateUrl .
								'" class="update-link" aria-label="' .
								sprintf(
									// translators: 1 The name of the plugin to update.
									esc_html__( 'Update %s now', 'boldgrid-plugin-installer' ),
									$api->name
								) .
								'" data-plugin="' . $file . '" data-slug="' . $api->slug . '"> ' .
								esc_html__( 'Update now', 'boldgrid-plugin-installer' ) . '</a>';

							$message = sprintf(
								// translators: 1 The link to update the plugin.
								esc_html__( 'New version available. %s', 'boldgrid-plugin-installer' ),
								$updateLink
							);
					}

					// Send plugin data to template.
					$this->renderTemplate(
						$pluginClasses,
						$message,
						$messageClasses,
						$api,
						$name,
						$button,
						$modal,
						$premiumLink
					);
				}
			?>
		</div>
		<?php
	}


	/**
	 * Adds ajax actions for plugin installer page.
	 *
	 * @since 1.0.0
	 */
	protected function ajax() {
		$activate = new Installer\Activate( $this->configs );
		$activate->init();
	}

	/**
	 * Filter boldgrid_backup notice_show configs.
	 *
	 * This will allow boldgrid backup to show the "backup site now" message on
	 * the BoldGrid tab.
	 *
	 * @since 1.0.2
	 *
	 * @hook: boldgrid_backup_notice_show_configs
	 *
	 * @param  array $configs
	 * @return array
	 */
	public function boldgrid_backup_notice_show_configs( $configs ) {

		if( empty( $_GET['tab'] ) || 'boldgrid' === $_GET['tab'] ) {
			$configs[] = array(
				'pagenow' => 'plugin-install.php',
				'check' => 'plugins',
			);
		}

		return $configs;
	}

	/**
	 * Get the premium URL for a users to login and upgrade with.
	 *
	 * This checks the 'boldgrid_reseller' option to see if an
	 * Account Management Panel link has been saved for a user's
	 * key, so they can upgrade that way.
	 *
	 * @since 1.0.0
	 *
	 * @nohook
	 *
	 * @param  string $plugin Plugin.
	 * @param  string $url    The upgrade URL address.
	 * @return string
	 */
	public function premiumUrl( $plugin, $url = null ) {
		if ( ! $url ) {

			// Set default URL.
			$url = $url ? $url : $this->configs['defaultLink'];

			// Check for reseller overrides.
			$option = get_site_option( 'boldgrid_reseller' );
			if ( $option && ! empty( $option['reseller_amp_url'] ) ) {
				$url = $option['reseller_amp_url'];
			}
		}

		// Append a source to the URL if it doesn't have one.
		parse_str( parse_url( $url, PHP_URL_QUERY ), $query );
		if ( empty( $query['source'] ) ) {
			$url = add_query_arg( 'source', 'add-new-' . urlencode( $plugin ), $url );
		}

		return apply_filters( 'Boldgrid\Library\Plugin\Installer\premiumUrl', $url, $plugin );
	}

	/**
	 * Renders template for each plugin card.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $plugin         Original data passed in for plugin list.
	 * @param string $pluginClasses  Classes to add for plugin card.
	 * @param string $message        The message to display if any.
	 * @param string $messageClasses Classes to apply to the message box.
	 * @param array  $api            Results from plugins_api
	 * @param string $name           Plugin name/version used for aria labels.
	 * @param array  $button         Contains button link, text and classes.
	 * @param string $modal          Modal link for thickbox plugin-information tabs.
	 */
	public function renderTemplate( $pluginClasses, $message, $messageClasses, $api, $name, $button, $modal, $premiumLink ) {
		include dirname( __FILE__ ) . '/Views/PluginInstaller.php';
	}

	/**
	 * Enqueue required CSS, JS, and localization for installer.
	 *
	 * @since 1.0.0
	 *
	 * @hook: admin_enqueue_scripts
	 */
	public function enqueue( $filter ) {
		$this->css( $filter );
		$this->js( $filter );
	}

	/**
	 * CSS to load for functionality.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $filter pagenow.
	 */
	public function css( $filter ) {
		if ( 'plugin-install.php' === $filter &&
			( ! isset( $_GET['tab'] ) || isset( $_GET['tab'] ) &&
			( 'boldgrid' === $_GET['tab'] || 'plugin-information' === $_GET['tab'] )
			) ) {
				wp_register_style(
					'bglib-plugin-installer',
					plugins_url( '/assets/css/plugin-installer.css', dirname( __FILE__ ) ),
					array( 'common' )
				);

				wp_enqueue_style( 'bglib-plugin-installer' );
		}
	}

	/**
	 * JS to load for functionality.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $filter pagenow.
	 */
	public function js( $filter ) {
		if ( 'plugin-install.php' === $filter &&
			( ! isset( $_GET['tab'] ) ||
			( isset( $_GET['tab'] ) && 'boldgrid' === $_GET['tab'] )
			) ) {
				// Enqueue Javascript.
				wp_register_script(
					'bglib-plugin-installer',
					plugins_url( '/assets/js/plugin-installer.js', dirname( __FILE__ ) ),
					array(
						'jquery',
						'plugin-install',
						'updates',
						'wp-i18n',
					)
				);

				// Add localized variables.
				wp_localize_script(
					'bglib-plugin-installer',
					'_bglibPluginInstaller',
					array(
						'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
						'nonce'      => wp_create_nonce( 'bglibPluginInstallerNonce' ),
						'status'     => ( bool ) $this->getTransient(),
						'install'    => esc_html__( 'Install Now', 'boldgrid-plugin-installer' ),
						'installing' => esc_html__( 'Installing', 'boldgrid-plugin-installer' ),
						'installed'  => esc_html__( 'Activated', 'boldgrid-plugin-installer' ),
						'activate'   => esc_html__( 'Activate', 'boldgrid-plugin-installer' ),
						'activating' => esc_html__( 'Activating', 'boldgrid-plugin-installer' ),
					)
				);

				// Send over update data.
				wp_localize_script(
					'updates',
					'_wpUpdatesItemCounts',
					array(
						'totals' => wp_get_update_data(),
					)
				);

				wp_enqueue_script( 'updates' );
				wp_enqueue_script( 'bglib-plugin-installer' );
		}
	}

	/**
	 * Hides inaccurate info on plugin search results loaded from
	 * external sources.
	 *
	 * @since 1.0.0
	 *
	 * @hook: admin_head-plugin-install.php
	 *
	 * @param  array $array An array.
	 * @return array
	 */
	public function hideInfo( $array ) {
		$css = '<style>';

		foreach ( $this->configs['plugins'] as $plugin => $details ) {
			// Feedback/rating details.
			$css .= ".plugin-card-{$plugin} .vers.column-rating{display:none;}";

			// Active install counts.
			$css .= ".plugin-card-{$plugin} .column-downloaded{display:none;}";
		}

		$css .= '</style>';

		echo $css;

		return $array;
	}

	/**
	 * Prepares the data for the WordPress Plugins API.
	 *
	 * @since 1.0.0
	 *
	 * @global $wp_version WordPress version.
	 *
	 * @return object
	 */
	public function getPluginInformation( $plugins ) {
		global $wp_version;

		// Get the API URL and Endpoint to call for requests.
		$api = Library\Configs::get( 'api' );
		$endpoint = '/api/open/getPluginVersion';

		// Each call will be stored in the $responses object before saving transient.
		$responses = new \stdClass();

		// Loop through plugins and save the data to transient.
		foreach ( $plugins as $plugin => $details ) {

			// Params to pass to remote API call.
			$params = array(
				'key' => $details['key'],
				'channel' => $this->releaseChannel->getPluginChannel(),
				'theme_channel' => $this->releaseChannel->getThemeChannel(),
				'installed_' . $details['key'] . '_version' => $details['Version'],
				'installed_wp_version' => $wp_version,
			);

			// Make API Call.
			$call = new Library\Api\Call( $api . $endpoint, $params );

			if ( ! $call->getError() ) {

				// Add the actual data to our $responses object.
				$responses->{$plugin} = $call->getResponse()->result->data;

				// Newest version of plugin (?).
				$responses->{$plugin}->new_version = $responses->{$plugin}->version;

				// Name is the expected value for the plugins_api, but we are returned title.
				$responses->{$plugin}->name = $responses->{$plugin}->title;

				// Slug is never returned from remote call, so we will create slug based on the title.
				$responses->{$plugin}->slug = sanitize_title( $responses->{$plugin}->title );

				// This was in the update class, but with/without it - it seemed to work.  Not sure if this
				// is really necessary to do as the data should be prepared on the asset server first.
				$responses->{$plugin}->sections = preg_replace(
					'/\s+/', ' ',
					trim( $responses->{$plugin}->sections )
				);

				$responses->{$plugin}->sections = json_decode( $responses->{$plugin}->sections, true );

				// Decode tags for searches.
				$responses->{$plugin}->tags = json_decode( $responses->{$plugin}->tags, true );

				// Loop through the decoded sections and then add to sections array.
				foreach ( $responses->{$plugin}->sections as $section => $section_data ) {
					$responses->{$plugin}->sections[ $section ] = html_entity_decode(
						preg_replace( '/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/', "\n", $section_data ), ENT_QUOTES
					);
				}

				// Set the short_description as the description before preparing.
				$responses->{$plugin}->short_description = $responses->{$plugin}->sections['description'];

				// Now generate the short_description from the standard description automatically.
				if ( mb_strlen( $responses->{$plugin}->short_description ) > 150 ) {
					$responses->{$plugin}->short_description = mb_substr( $responses->{$plugin}->short_description, 0, 150 ) . ' &hellip;';

					// If not a full sentence, and one ends within 20% of the end, trim it to that.
					if ( function_exists( 'mb_strrpos' ) ) {
						$pos = mb_strrpos( $responses->{$plugin}->short_description, '.' );
					} else {
						$pos = strrpos( $responses->{$plugin}->short_description, '.' );
					}
					if ( '.' !== mb_substr( $responses->{$plugin}->short_description, - 1 ) && $pos > ( 0.8 * 150 ) ) {
						$responses->{$plugin}->short_description = mb_substr( $responses->{$plugin}->short_description, 0, $pos + 1 );
					}
				}

				// Remove any remaining markup from short_description.
				$responses->{$plugin}->short_description = wp_strip_all_tags( $responses->{$plugin}->short_description );

				// Returned release date === last_updated (?).
				$responses->{$plugin}->last_updated = $responses->{$plugin}->release_date;

				// Create the author URL based on the siteurl and author name of plugin library is being ran in.
				$responses->{$plugin}->author = '<a href="' . $responses->{$plugin}->siteurl . '" target="_blank">' . $details['Author'] . '</a>';

				// This has to be json decoded since this array is json encoded for whatever reason.
				$responses->{$plugin}->banners = json_decode( $responses->{$plugin}->banners, true );

				// Just creating the links by having the file naming standardized.  WordPress also expects this same
				// format for these files.  The same approach can be taken for the banners as well.
				$responses->{$plugin}->icons = array(
					'1x' => "https://repo.boldgrid.com/assets/icon-{$responses->{$plugin}->slug}-128x128.png",
					'2x' => "https://repo.boldgrid.com/assets/icon-{$responses->{$plugin}->slug}-256x256.png",
					'svg' => "https://repo.boldgrid.com/assets/icon-{$responses->{$plugin}->slug}-128x128.svg",
				);

				// This seems hardcoded in based on looking at our plugins in the update class.
				$responses->{$plugin}->added = '2015-03-19';

				// Setting the returned siteurl as the expected url param.
				$responses->{$plugin}->url = $responses->{$plugin}->siteurl;
				$responses->{$plugin}->active_installs = true;

				// Build the URL for the plugin download from the asset server.
				$responses->{$plugin}->download_link = add_query_arg(
					array(
						'key' => Library\Configs::get( 'key' ),
						'id' => $responses->{$plugin}->asset_id,
						'installed_plugin_version' => $details['Version'],
						'installed_wp_version' => $wp_version,
					),
					$api . '/api/open/getAsset'
				);
			}
		}

		set_site_transient( 'boldgrid_plugins', $responses, 8 * HOUR_IN_SECONDS );

		return $responses;
	}


	/**
	 * Filters the WordPress Updates Available.
	 *
	 * This is set to priority 12 to override the individual plugin
	 * update classes that set priority at 11 in this filter.
	 *
	 * @since 1.0.0
	 *
	 * @hook: pre_set_site_transient_update_plugins
	 * @hook: site_transient_update_plugins
	 *
	 * @return StdClass $updates Updates available.
	 */
	public function filterUpdates( $updates ) {
		$updates = (object) $updates;
		$plugins = $this->getTransient() ? : array();

		foreach( $plugins as $plugin => $details ) {
			if ( empty( $this->configs['plugins'][ $plugin ] ) ) {
				continue;
			}

			$update = new \stdClass();
			$update->plugin = $this->configs['plugins'][ $plugin ]['file'];
			$update->slug = $details->slug;
			$update->new_version = $details->new_version;
			$update->url = $details->url;
			$update->package = $details->download_link;

			if ( isset( $updates ) && ( $this->configs['plugins'][ $plugin ]['Version'] !== $details->new_version ) && Util\Plugin::getPluginFile( $details->slug ) ) {
				$update->tested = $details->tested_wp_version;
				$update->compatibility = new \stdClass();
				$updates->response[ $update->plugin ] = $update;
			} elseif ( isset( $updates->no_update ) && isset( $updates->no_update[ $update->plugin ] ) ) {
				$updates->no_update[ $update->plugin ] = $update;
			}
		}

		return apply_filters( 'Boldgrid\Library\Plugin\Installer\filterUpdates', $updates );
	}

	/**
	 * Filters the WordPress Updates Available.
	 *
	 * This is set to priority 12 to override the individual plugin
	 * update classes that set priority at 11 in this filter.
	 *
	 * Tread carefully when updating this method. On a site with 55 plugins (54 of them inactive), this
	 * method was called 64 times within wp-admin/plugins.php.
	 *
	 * @since 1.0.0
	 *
	 * @global string $pagenow Current admin page.
	 *
	 * @hook: set_site_transient_update_plugins
	 * @hook: site_transient_update_plugins
	 *
	 * @param  StdClass $updates Updates available.
	 * @return StdClass
	 */
	public function externalUpdates( $updates ) {
		global $pagenow;

		/*
		 * This filter only needs to be ran on the Plugins >> Add New page.
		 *
		 * The boldgrid_wporg_plugins site transient is retrieved and possibly set below. This transient
		 * is only for the Plugins >> Add New page. It needs to fetch and store the info needed to
		 * display BoldGrid plugins for the Add new page.
		 */
		if ( 'plugin-install.php' !== $pagenow ) {
			return $updates;
		}

		if ( ! empty( $this->configs['wporgPlugins'] ) && ! get_site_transient( 'boldgrid_wporg_plugins', false ) ) {
			$plugins = $this->configs['wporgPlugins'];

			// Load required lib if not already available.
			if ( ! function_exists( 'install_plugin_information' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			}

			$responses = new \stdClass();

			foreach( $plugins as $plugin ) {
				$api = plugins_api( 'plugin_information',
					array(
						'slug' => $plugin['slug'],
						'fields' => array(
							'short_description' => true,
							'icons' => true,
						),
					)
				);

				if ( ! is_wp_error( $api ) && ! empty( $api->slug ) ) {
					$responses->{$plugin['slug']} = $api;
				}
			}

			// Cast to array before empty check, an empty( object ) evaluates to not empty.
			$responsesArr = (array) $responses;

			if ( ! empty( $responsesArr ) ) {
				set_site_transient( 'boldgrid_wporg_plugins', $responses, 8 * HOUR_IN_SECONDS );
			}
		}

		return $updates;
	}

	/**
	 * Modify Update Class Hooks.
	 *
	 * @since 1.0.0
	 *
	 * @hook: admin_init
	 */
	public function modifyUpdate() {
		$plugins = $this->getTransient() ? : array();

		foreach( $plugins as $plugin => $details ) {
			$p = explode( '-', $plugin );
			$p = array_map( 'ucfirst', $p );
			$p = implode( '_', $p );
			$class = $p . '_Update';

			if ( class_exists( $class ) ) {
				Library\Filter::removeHook( 'plugins_api', $class, 'custom_plugins_transient_update', 11 );
				Library\Filter::removeHook( 'custom_plugins_transient_update', $class, 'custom_plugins_transient_update', 11 );
				Library\Filter::removeHook( 'pre_set_site_transient_update_plugins', $class, 'custom_plugins_transient_update', 11 );
				Library\Filter::removeHook( 'site_transient_update_plugins', $class, 'site_transient_update_plugins', 11 );
				delete_site_transient( "{$p}_version_data" );
			}
		}
	}

	/**
	 * Gets configs class property.
	 *
	 * @since  1.0.0
	 *
	 * @return array $configs The configs class property.
	 */
	protected function getConfigs() {
		return $this->configs;
	}

	/**
	 * Gets the transient class property.
	 *
	 * @since  1.0.0
	 *
	 * @nohook
	 *
	 * @return mixed $transient The transient class property.
	 */
	public function getTransient() {
		return $this->transient;
	}

	/**
	 * Update the order of all plugins on install page.
	 *
	 * @since 1.0.0
	 *
	 * @hook: Boldgrid\Library\Plugin\Installer\init
	 * @priority: 20
	 *
	 * @param  StdClass $plugins The plugin object for update information.
	 * @return StdClass
	 */
	public function orderPlugins( $plugins ) {
		$plugins = (array) $plugins;
		$allPlugins = array_merge( $this->configs['plugins'], $this->configs['wporgPlugins'] );

		// Get priority default to 99.
		$getPriority = function( $plugin ) use ( $allPlugins ) {
			return ! empty( $allPlugins[ $plugin->slug ]['priority'] ) ?
				$allPlugins[ $plugin->slug ]['priority'] : 99;
		};

		$success = uasort( $plugins, function( $a, $b ) use ( $allPlugins, $getPriority ) {
			$priorityA = $getPriority( $a );
			$priorityB = $getPriority( $b );

			if ( $priorityA === $priorityB ) {
				return 0;
			}
			return ( $priorityA < $priorityB ) ? -1 : 1;
		} );

		return (object) $plugins;
	}

	/**
	 * Modify the Library's wp.org saved plugin data.
	 *
	 * @since 1.0.0
	 *
	 * @hook: Boldgrid\Library\Plugin\Installer\init
	 *
	 * @param  StdClass $plugins The plugin object for update information.
	 * @return StdClass
	 */
	public function wporgData( $plugins ) {
		$plugins = (array) $plugins;

		// Add wporg recommended plugins to the Plugins > Add New page.
		if ( $wporgPlugins = get_site_transient( 'boldgrid_wporg_plugins', false ) ) {
			$plugins = array_merge( $plugins, (array) $wporgPlugins );
		}

		return (object) $plugins;
	}
}
