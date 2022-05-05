<?php
/**
 * BoldGrid Usage Notice.
 *
 * @package Boldgrid\Library
 *
 * @version 2.11.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library\Usage;

use Boldgrid\Library\Library;
use Boldgrid\Library\Library\Filter;

/**
 * BoldGrid Library Usage Notice Class.
 *
 * @since 2.11.0
 */
class Notice {
	/**
	 * Whether or not we've already added the filters in the constructor.
	 *
	 * Filters only need to be added once.
	 *
	 * @since 2.11.0
	 * @access private
	 * @var bool
	 */
	private static $filtersAdded = false;

	/**
	 * Construct.
	 *
	 * Do not instantiate this class unless you're sure you want to collect usage data.
	 *
	 * @since 2.11.0
	 */
	public function __construct() {
		// Only add the filters once.
		if ( ! self::$filtersAdded ) {
			Filter::add( $this );
			self::$filtersAdded = true;
		}
	}

	/**
	 * Admin enqueue scripts.
	 *
	 * @since 2.11.0
	 */
	public static function admin_enqueue_scripts() {
		$handle = 'bglib-usage-notice';

		wp_register_script(
			$handle,
			Library\Configs::get( 'libraryUrl' ) . 'src/assets/js/usage-notice.js',
			'jquery',
			date( 'Ymd' )
		);

		$translation = [
			'nonce'  => wp_create_nonce( 'bglib_usage_signup' ),
			'error' => esc_html__( 'There was a problem dismissing this notice. Please refresh the page and try again.', 'boldgrid-library' ),
		];

		wp_localize_script( $handle, 'BglibUsageNotice', $translation );

		wp_enqueue_script( $handle );
	}

	/**
	 * Determine whether or not to show the usage notice.
	 *
	 * @since 2.11.0
	 *
	 * return bool
	 */
	public function maybeShow() {
		if ( apply_filters( 'Boldgrid\Library\Notice\KeyPrompt\maybePromptKey', false ) ) {
			// If we're currently prompting the user to enter their key, don't show this notice.
			$maybeShow = false;
		} elseif ( apply_filters( 'Boldgrid\Library\Key\hasKey', false ) ) {
			// If the user has a key entered, no need to show the notice.
			$maybeShow = false;
		} elseif( ! Settings::hasAgreeDecision() && Helper::hasScreenPrefix() ) {
			// If the user has not made a decision and their on an applicable page, show the notice.
			$maybeShow = true;
		} else {
			$maybeShow = false;
		}

		/**
		 * Filter whether or not we should show the notice.
		 *
		 * The first usage of this filter is for Total Upkeep to say "Don't show the notice if the user
		 * has not made their first backup yet.". The reason for this is that if the user hasn't made
		 * their first backup yet, they're going to see notices teaching them how, and now is not a
		 * good time to ask to track anonymous usage data.
		 *
		 * @since 2.11.0
		 *
		 * @param bool $maybeShow Whether or not we should show the notice.
		 */
		$maybeShow = apply_filters( 'Boldgrid\Library\Usage\Notice\maybeShow', $maybeShow );

		return $maybeShow;
	}

	/**
	 * Show the notice.
	 *
	 * @since 2.11.0
	 */
	public function admin_notices() {
		// Abort if we shouldn't be showing the notice.
		if ( ! $this->maybeShow() ) {
			return;
		}

		$params = [
			'message' => '<p>' .
				__(
					'Thank you for using BoldGrid! Would you be ok with helping us improve our products by sending anonymous usage data? Information collected will not be personal and is not used to identify or contact you.',
					'boldgrid-library'
				) . '</p>',
			'yes' => __( 'Sure, I\'ll help!', 'boldgrid-library' ),
			'no'  => __( 'Sorry, not now', 'boldgrid-library' ),
		];

		/**
		 * Filter the params for the notice.
		 *
		 * @since 2.11.0
		 *
		 * @param array $params {
		 * 		An array of params for the notice.
		 *
		 * 		@type string $message The markup for the message, generally within a p tag.
		 * 		@type string $yes     The verbiage for the yes button.
		 * 		@type string $no      The verbiage for the no button.
		 * }
		 */
		$params = apply_filters( 'Boldgrid\Library\Usage\Notice\admin_notices', $params );

		echo '<div class="notice notice-info" id="bglib_usage_notice">';

		echo wp_kses(
			$params['message'],
			[
				'p' => [],
			]
		);

		echo '<p>
			<a class="button button-primary" data-choice="1">' . esc_html( $params['yes'] ) . '</a>
			<a href="" data-choice="0">' . esc_html( $params['no'] ) . '</a>
		</p>';

		echo '</div>';
	}

	/**
	 * Handle the ajax call to accept / decline the notice.
	 *
	 * @since 2.11.0
	 */
	public static function wp_ajax_bglib_usage_signup() {
		if( ! check_ajax_referer( 'bglib_usage_signup', 'nonce', false ) ) {
			wp_send_json_error( esc_html__( 'Invalid nonce.', 'boldgrid-library' ) );
		}

		$share_data = ! empty( $_POST['choice'] );

		$updated = Settings::setAgree( $share_data );

		$updated ? wp_send_json_success() : wp_send_json_error();
	}
}
