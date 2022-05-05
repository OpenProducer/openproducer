<?php //phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * BoldGrid Library Update Data.
 *
 * Library package uses different naming convention.
 * phpcs:disable WordPress.NamingConventions.ValidVariableName
 * phpcs:disable WordPress.NamingConventions.ValidFunctionName
 *
 * @package Boldgrid\Plugin
 *
 * @since 2.12.2
 *
 * @author BoldGrid <wpb@boldgrid.com>
 */
namespace Boldgrid\Library\Library\Plugin;

use Boldgrid\Library\Library\Plugin\Plugin;

/**
 * Update Data Class.
 *
 * This class stores the update data for a given plugin.
 * Boldgrid\Library\Library\Plugin\Plugin class.
 *
 * @since 2.12.0
 */
class UpdateData {
	/**
	 * Plugin.
	 *
	 * @since 2.12.2
	 * @var Plugin
	 */
	public $plugin;

	/**
	 * Active Installs.
	 *
	 * @since 2.12.2
	 * @var string
	 */
	public $activeInstalls;

	/**
	 * Minor Version Installs.
	 *
	 * @since 2.12.2
	 * @var string
	 */
	public $minorVersionInstalls;

	/**
	 * Release Date.
	 *
	 * @since 2.12.2
	 * @var string
	 */
	public $releaseDate;

	/**
	 * Version.
	 *
	 * @since 2.12.2
	 * @var string
	 */
	public $version;

	/**
	 * Minor Version.
	 *
	 * @since 2.12.2
	 * @var string
	 */
	public $minorVersion;

	/**
	 * Stats.
	 *
	 * @since 2.12.2
	 * @var string
	 */
	public $stats;

	/**
	 * Days Since Release.
	 *
	 * @since 2.12.2
	 * @var int
	 */
	public $days;

	/**
	 * Response Data.
	 *
	 * @since 2.12.2
	 * @var Response
	 * @access private
	 */
	private $responseData;

	/**
	 * Current Transient.
	 *
	 * @since 2.12.2
	 * @var array
	 * @access private
	 */
	private $currentTransient;

	/**
	 * Timeout Setting.
	 *
	 * @since 2.12.2
	 * @var int
	 * @access private
	 */
	private $timeoutSetting = 3600;

	/**
	 * Constructor.
	 *
	 * @since 2.12.2
	 *
	 * @param Plugin $plugin The plugin we are getting data for.
	 * @param string $slug Optional slug of plugin if plugin object not given.
	 * @param bool   $force Whether or not to force fetching data from API.
	 */
	public function __construct( $plugin = null, $slug = null, $force = false ) {
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		// If a plugin object is passed in constructer, use that, or else create a new one from slug.
		$this->plugin = ( null !== $plugin ) ? $plugin : Factory::create( $slug );

		$responseTransient = $this->getInformationTransient();

		if ( false !== $responseTransient ) {
			$this->responseData   = (object) $responseTransient;
			$this->activeInstalls = $responseTransient['active_installs'];
			$this->version        = $responseTransient['version'];
			$this->downloaded     = $responseTransient['downloaded'];
			$this->releaseDate    = $responseTransient['last_updated'];
			$this->thirdParty     = $responseTransient['third_party'];
			$this->apiFetchTime   = isset( $this->responseTransient['api_fetch_time'] ) ? $this->responseTransient['api_fetch_time'] : current_time( 'timestamp' );
		} else {
			$this->responseData   = $this->fetchResponseData( $force );
			$this->activeInstalls = isset( $this->responseData->active_installs ) ? $this->responseData->active_installs : '0';
			$this->version        = isset( $this->responseData->version ) ? $this->responseData->version : null;
			$this->downloaded     = isset( $this->responseData->downloaded ) ? $this->responseData->downloaded : '0';
			$this->releaseDate    = isset( $this->responseData->last_updated ) ? new \DateTime( $this->responseData->last_updated ) : new \DateTime( gmdate( 'Y-m-d H:i:s', 1 ) );
			$this->thirdParty     = isset( $this->responseData->third_party ) ? $this->responseData->third_party : false;
			$this->apiFetchTime   = isset( $this->responseData->api_fetch_time ) ? $this->responseData->api_fetch_time : false;

			$this->setInformationTransient();
		}
		$now        = new \DateTime();
		$this->days = date_diff( $now, $this->releaseDate )->format( '%a' );
	}

	/**
	 * Get Minor Version Installs.
	 *
	 * @since SINCEVERSON
	 * @return int
	 */
	public function getMinorVersionInstalls() {
		foreach ( $this->stats as $minorVersion => $percentInstalls ) {
			if ( $minorVersion === $this->minorVersion ) {
				$x = $percentInstalls / 100;
				return $this->activeInstalls * $x;
			}
		}
		$now        = new \DateTime();
		$this->days = date_diff( $now, $this->releaseDate )->format( '%a' );
	}

	/**
	 * Get Response Data.
	 *
	 * @since 2.12.2
	 *
	 * @return Response
	 */
	public function getResponseData() {
		return $this->responseData;
	}

	/**
	 * Set Response Data.
	 *
	 * @since 2.12.2
	 *
	 * @param bool $force Whether or not to force fethcing from API.
	 *
	 * @return Response
	 */
	public function fetchResponseData( $force = false ) {
		global $wp_filter;
		$is_timely_updates  = apply_filters( 'boldgrid_backup_is_timely_updates', false );
		$plugin_information = array();
		$delay_time         = $force ? 0 : 3;
		$age_of_transient   = $this->getAgeOfTransient();

		/*
		 * If the age of transient is less than 0, that transient is expired but hasn't been invalidated.
		 * In that case, you must go ahead and fetch the data. if the age is greater than or equal to zero
		 * and less than the delay time, then delay fetching data.
		 */
		$delayFetchingData = ( 0 <= $age_of_transient && $age_of_transient < $delay_time );

		if ( $is_timely_updates && ! $delayFetchingData ) {
			/*
			 * Sometimes, other plugins will add filters to the 'plugins_api' hook.
			 * Doing this can sometimes 'short-circuit' other requests to this API.
			 * See: https://developer.wordpress.org/reference/hooks/plugins_api/
			 * This will remove those filters so we can be sure that this API request
			 * returns valid data. This should not affect other plugins from using those filters
			 * since their 'add_filters()' calls will re-register their filters.
			*/
			\remove_all_filters( 'plugins_api' );
			$plugin_information = plugins_api(
				'plugin_information',
				(object) array(
					'slug'   => $this->plugin->getSlug(),
					'fields' => array(
						'downloaded',
						'last_updated',
						'active_installs',
					),
				)
			);
			/*
			 * A successful call to the WordPress.org Plugins API will NOT have a 'no_update' property.
			 * This conditional will ensure that such calls are marked as WP_Error objects and then the
			 * $plugin_information will be assigned the return value from $this->getGenericInfo() below.
			 */
			if ( isset( $plugin_information->no_update ) ) {
				$plugin_information = new \WP_Error( 'Plugin API returns unusable data' );
			} else {
				$plugin_information->api_fetch_time = current_time( 'timestamp' );
			}

		} else {
			$plugin_information = array(
				'active_installs' => '0',
				'version'         => '0',
				'downloaded'      => '000000',
				'last_updated'    => gmdate( 'Y-m-d H:i:s', 1 ),
				'api_fetch_time'  => false,
			);
		}

		if ( is_a( $plugin_information, 'WP_Error' ) ) {
			$plugin_information = $this->getGenericInfo( $plugin_information );
			return (object) $plugin_information;
		}
		return (object) $plugin_information;
	}

	/**
	 * Get Plugin Information from Transient.
	 *
	 * @since 2.12.2
	 *
	 * @return array
	 */
	public function getInformationTransient() {
		$transient = get_transient( 'boldgrid_plugin_information' );
		if ( false === $transient ) {
			$this->currentTransient = array();
			return false;
		} else {
			$this->currentTransient = $transient;
		}

		if ( array_key_exists( $this->plugin->getSlug(), $transient ) && false === $transient[ $this->plugin->getSlug() ]['api_fetch_time'] ) {
			return false;
		}

		if ( array_key_exists( $this->plugin->getSlug(), $transient ) ) {
			return $transient[ $this->plugin->getSlug() ];
		}
		return false;
	}

	/** Get Age of Transient.
	 *
	 * @since 2.12.2
	 *
	 * @return string
	 */
	public function getAgeOfTransient() {
		if ( $this->currentTransient ) {
			$timeout           = get_option( '_transient_timeout_boldgrid_plugin_information' );
			$current_timestamp = current_time( 'timestamp' );
			return $this->timeoutSetting - ( $timeout - $current_timestamp );
		}
	}

	/**
	 * Set Plugin Information Transient.
	 *
	 * @since 2.12.2
	 */
	public function setInformationTransient() {
		$transient = get_transient( 'boldgrid_plugin_information' );
		if ( false === $transient ) {
			$transient = array();
		}

		$transient[ $this->plugin->getSlug() ] = array(
			'active_installs' => $this->activeInstalls,
			'version'         => $this->version,
			'downloaded'      => $this->downloaded,
			'last_updated'    => $this->releaseDate,
			'third_party'     => $this->thirdParty,
			'api_fetch_time'  => $this->apiFetchTime,
		);

		$is_timely_updates = apply_filters( 'boldgrid_backup_is_timely_updates', false );
		if ( $is_timely_updates ) {
			set_transient( 'boldgrid_plugin_information', $transient, $this->timeoutSetting );
		}

	}

	/**
	 * Plugins Api Failed.
	 *
	 * @since 2.12.2
	 *
	 * @param \WP_Error $errors WordPress error returned by plugins_api().
	 */
	public function getGenericInfo( \WP_Error $errors ) {
		$current     = get_site_transient( 'update_plugins' );
		$new_version = isset( $current->response[ $this->plugin->getFile() ] ) ? $current->response[ $this->plugin->getFile() ]->new_version : '';

		$plugin_information = array(
			'active_installs' => '0',
			'version'         => $new_version,
			'downloaded'      => '000000',
			'last_updated'    => gmdate( 'Y-m-d H:i:s', 1 ),
			'third_party'     => true,
			'api_fetch_time'  => current_time( 'timestamp' ),
		);

		return $plugin_information;
	}

	/**
	 * Get Time Till Update.
	 *
	 * This is used in a filter for WP5.5+ installations.
	 * It returns a unix timestamp of when a given plugin can update.
	 *
	 * @return int Unix Timestamp.
	 */
	public function timeTillUpdate() {
		$default_days         = 0;
		$core                 = apply_filters( 'boldgrid_backup_get_core', null );
		$auto_update_settings = $core->settings->get_setting( 'auto_update' );
		$days_setting         = ! empty( $auto_update_settings['days'] ) ? $auto_update_settings['days'] : $default_days;
		$time_till_update     = $days_setting - $this->days; //phpcs:ignore WordPress.NamingConventions.ValidVariableName

		if ( ! empty( $this->thirdParty ) || 0 >= $time_till_update ) { //phpcs:ignore WordPress.NamingConventions.ValidVariableName
			return false;
		}

		return time() + ( ( (int) $time_till_update ) * 24 * 60 * 60 );
	}
}
