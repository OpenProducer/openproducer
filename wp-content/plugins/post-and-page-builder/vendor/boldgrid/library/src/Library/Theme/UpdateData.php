<?php //phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * BoldGrid Library Update Data.
 *
 * Library package uses different naming convention.
 * phpcs:disable WordPress.NamingConventions.ValidVariableName
 * phpcs:disable WordPress.NamingConventions.ValidFunctionName
 *
 * @package Boldgrid\Theme
 *
 * @since 2.12.2
 *
 * @author BoldGrid <wpb@boldgrid.com>
 */
namespace Boldgrid\Library\Library\Theme;

use Boldgrid\Library\Library\Theme\Theme;

/**
 * Update Data Class.
 *
 * This class stores the update data for a given theme.
 * Boldgrid\Library\Library\Theme\Theme class.
 *
 * @since 2.12.0
 */
class UpdateData {
	/**
	 * Theme.
	 *
	 * @since 2.12.2
	 * @var Theme
	 */
	public $theme;

	/**
	 * Themes.
	 *
	 * @since 2.12.2
	 * @var string
	 */
	public $themes;

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
	 * Is Timely Updates Enabled.
	 *
	 * @since 2.12.3
	 * @var bool
	 * @access private
	 */
	private $is_timely_updates;

	/**
	 * Constructor.
	 *
	 * @since 2.12.2
	 *
	 * @param Theme  $theme The Theme Object.
	 * @param string $stylesheet Theme's Stylesheet.
	 */
	public function __construct( $theme = null, $stylesheet = null ) {
		// If a theme object is passed in constructer, use that, or else create a new one from stylesheet.
		$this->theme = ( null !== $theme ) ? $theme : new Theme( $stylesheet );

		$settings                = get_option( 'boldgrid_backup_settings' );
		$this->is_timely_updates = ! empty( $settings['auto_update']['timely-updates-enabled'] );

		$responseTransient = $this->getInformationTransient();

		if ( false !== $responseTransient ) {
			$this->responseData = (object) $responseTransient;
			$this->version      = $responseTransient['version'];
			$this->downloaded   = $responseTransient['downloaded'];
			$this->releaseDate  = $responseTransient['last_updated'];
			$this->apiFetchTime = isset( $this->responseTransient['api_fetch_time'] ) ? $this->responseTransient['api_fetch_time'] : current_time( 'timestamp' );
		} else {
			$this->responseData = $this->fetchResponseData();
			$this->version      = $this->responseData->version;
			$this->downloaded   = $this->responseData->downloaded;
			$this->releaseDate  = new \DateTime( $this->responseData->last_updated );
			$this->apiFetchTime = isset( $this->responseData->api_fetch_time ) ? $this->responseData->api_fetch_time : false;

			$this->setInformationTransient();
		}

		$now = new \DateTime();

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
	 * @return Response
	 */
	public function fetchResponseData() {
		include_once ABSPATH . 'wp-admin/includes/theme.php';

		$theme_information = array();

		if ( $this->is_timely_updates ) {
			$theme_information = themes_api(
				'theme_information',
				array(
					'slug'   => $this->theme->stylesheet,
					'fields' => array(
						'downloaded',
						'last_updated',
						'active_installs',
					),
				)
			);

			$theme_information->api_fetch_time = current_time( 'timestamp' );
		} else {
			$theme_information = array(
				'active_installs' => '0',
				'version'         => '0',
				'downloaded'      => '000000',
				'last_updated'    => gmdate( 'Y-m-d H:i:s', 1 ),
				'api_fetch_time'  => false,
			);
		}

		if ( is_a( $theme_information, 'WP_Error' ) ) {
			$theme_information = $this->getGenericInfo( $theme_information );
			return (object) $theme_information;
		}

		return (object) $theme_information;
	}

	/**
	 * Get Theme Information from Transient.
	 *
	 * @since 2.12.2
	 *
	 * @return array
	 */
	public function getInformationTransient() {
		$transient = get_transient( 'boldgrid_theme_information' );

		if ( false === $transient ) {
			$this->currentTransient = array();
			return false;
		} else {
			$this->currentTransient = $transient;
		}

		if ( array_key_exists( $this->theme->stylesheet, $transient ) &&
			false === $transient[ $this->theme->stylesheet ]['api_fetch_time'] ) {
			return false;
		}

		if ( array_key_exists( $this->theme->stylesheet, $transient ) ) {
			return $transient[ $this->theme->stylesheet ];
		}
		return false;
	}

	/**
	 * Set Theme Information Transient.
	 *
	 * @since 2.12.2
	 */
	public function setInformationTransient() {
		$transient = get_transient( 'boldgrid_theme_information' );
		if ( false === $transient ) {
			$transient = array();
		}

		$transient[ $this->theme->stylesheet ] = array(
			'version'         => $this->version,
			'downloaded'      => $this->downloaded,
			'last_updated'    => $this->releaseDate,
			'api_fetch_time'  => $this->apiFetchTime,
		);

		if ( $this->is_timely_updates ) {
			set_transient( 'boldgrid_theme_information', $transient, $this->timeoutSetting );
		}
	}

	/**
	 * Plugins Api Failed.
	 *
	 * @since 2.12.2
	 *
	 * @param \WP_Error $errors WordPress error returned by themes_api().
	 */
	public function getGenericInfo( \WP_Error $errors ) {
		$current     = get_site_transient( 'update_themes' );
		$new_version = isset( $current->checked[ $this->theme->stylesheet ] ) ? $current->checked[ $this->theme->stylesheet ] : '';

		$theme_information = array(
			'active_installs' => '0',
			'version'         => $new_version,
			'downloaded'      => '000000',
			'last_updated'    => gmdate( 'Y-m-d H:i:s', 1 ),
			'third_party'     => true,
			'api_fetch_time'  => current_time( 'timestamp' ),
		);

		return $theme_information;
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
