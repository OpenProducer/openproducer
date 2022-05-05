<?php
/**
 * BoldGrid Library Key PostNewKey
 *
 * @package Boldgrid\Library
 * @subpackage \Key
 *
 * @version 2.8.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library\Key;

use Boldgrid\Library\Library\Filter;
use Boldgrid\Library\Library\ReleaseChannel;
use Boldgrid\Library\Library\Key;
use Boldgrid\Library\Library\Configs;

/**
 * Boldgrid Library Key PostNewKey Class.
 *
 * This class is responsible for handling the posting of a new key from Central back to the user's
 * WordPress dashboard.
 *
 * @since 2.8.0
 */
class PostNewKey {
	/**
	 * The option that stores whether or not a new key has been added.
	 *
	 * @since 2.8.0
	 * @access private
	 * @var string
	 */
	private $option = 'bglib_after_key';

	/**
	 * Constructor.
	 *
	 * @since 1.8.0
	 */
	public function __construct() {
		Filter::add( $this );
	}

	/**
	 * Admin notices.
	 *
	 * If the user's key was successfully added, we need to show an admin notice.
	 *
	 * @since 1.8.0
	 */
	public function admin_notices() {
		$afterKeyAdded = get_option( $this->option );

		if ( empty( $afterKeyAdded ) ) {
			return;
		}

		switch( $afterKeyAdded ) {
			case 'success':
				echo '<div class="notice notice-success is-dismissible bglib-key-added"><p>' .
					esc_html( 'Your new BoldGrid Connect Key has been successfully added!', 'boldgrid-library' ) .
					'</p></div>';
				break;
			case 'fail':
				echo '<div class="notice notice-error is-dismissible bglib-key-added"><p>' .
					esc_html( 'An unknown error occurred adding your new BoldGrid Connect Key.', 'boldgrid-library' ) .
					'</p></div>';
				break;
		}

		delete_option( $this->option );
	}

	/**
	 * Get the url to BoldGrid Central for the user to get a new Connect Key.
	 *
	 * @since 2.8.0
	 *
	 * @return string
	 */
	public static function getCentralUrl( $returnUrl = '' ) {
		/*
		 * Configure our return url.
		 *
		 * The return url is the url that BoldGrid Central will link the user to after they received
		 * their new BoldGrid Connect Key.
		 *
		 * In the initial version of this method, the return url linked to
		 * Dashboard > Settings > BoldGrid Connect
		 * and we allowed the filter below to be used to change the url. As plugins began to use
		 * this method more, it's became easier to simply allow a return url to be passed in, rather
		 * than require the filter to be used.
		 */
		$returnUrl = ! empty( $returnUrl ) ? $returnUrl : admin_url( 'options-general.php?page=boldgrid-connect.php' );

		/**
		 * Allow the return url to be filtered.
		 *
		 * @since 2.8.0
		 *
		 * @param string URL to the BoldGrid Connect settings page.
		 */
		$returnUrl = apply_filters( 'Boldgrid\Library\Key\returnUrl', $returnUrl );

		$returnUrl = add_query_arg( 'nonce', wp_create_nonce( 'bglib-key-prompt' ), $returnUrl );

		// Create the final url and return it.
		return add_query_arg(
			array(
				'wp-url' => urlencode( $returnUrl ),
			),
			Configs::get( 'getNewKey' )
		);
	}

	/**
	 * Handle the submission of an api key via a post call.
	 *
	 * @since 2.8.0
	 *
	 * @hook admin_init
	 */
	public function processPost() {
		if ( $this->isPosting() ) {
			$releaseChannel = new ReleaseChannel;

			$key = new Key( $releaseChannel );

			$hashed_key = md5( $_POST['activateKey'] );

			$success = $key->addKey( $hashed_key );

			/*
			 * This option is used to setup an event similiar to WordPress' after_switch_theme hook.
			 * It allows us to take action on the page load following a new Connect Key being added.
			 */
			update_option( $this->option, $success ? 'success' : 'fail' );

			/*
			 * Refersh the current page so that when it reloads the new page begins with the user
			 * having a Connect Key installed. Otherwise, the current page will load and propmpt the
			 * user for a key even though we've just added one.
			 */
			header( 'Refresh:0' );
			exit;
		}
	}

	/**
	 * Determine whether or not we are posting a new Connect Key to the dashboard.
	 *
	 * @since 2.8.0
	 *
	 * @return bool
	 */
	private function isPosting() {
		if ( empty( $_POST['activateKey'] ) ) {
			return false;
		}

		if ( empty( $_GET['nonce'] ) || ! wp_verify_nonce( $_GET['nonce'], 'bglib-key-prompt') ) {
			return false;
		}

		if ( ! current_user_can( 'update_plugins' ) ) {
			return false;
		}

		return true;
	}
}
