<?php

namespace WPForms\Lite\Integrations\LiteConnect;

use WPForms\Helpers\Crypto;

/**
 * Integration between Lite Connect API and WPForms Lite.
 *
 * @since 1.7.4
 */
class Integration extends \WPForms\Integrations\LiteConnect\Integration {

	/**
	 * Encrypt the form entry and submit it to the Lite Connect API.
	 *
	 * If the regular wp_remote_post() request fail for any reasons, then an
	 * Action Scheduler task will be created to retry a couple of minutes later.
	 *
	 * @since 1.7.4
	 *
	 * @param array $entry_args The entry data.
	 * @param array $form_data  The form data.
	 *
	 * @return false|string
	 */
	public function submit( $entry_args, $form_data ) {

		if ( ! is_array( $entry_args ) ) {
			return false;
		}

		$entry_args['form_data'] = $form_data;

		// Encrypt entry using the WPForms Crypto class.
		$entry_data = Crypto::encrypt( wp_json_encode( $entry_args ) );

		// We have to start requesting site keys in ajax, turning on the LC functionality.
		// First, the request to the API server will be sent.
		// Second, the server will respond to our callback URL /wpforms/auth/key/nonce, and the site key will be stored in the DB.
		// Third, we have to get access via a separate HTTP request.
		$this->update_keys(); // Third request here.

		// Submit entry to the Lite Connect API.
		$response = $this->add_form_entry( $this->auth['access_token'], $entry_args['form_id'], $entry_data );

		// Confirm if entry has been added successfully to the Lite Connect API.
		if ( $response ) {
			$response = json_decode( $response, true );
		}

		if ( isset( $response['error'] ) && $response['error'] === 'Access token is invalid or expired.' ) {
			// Force to re-generate access token in case it is invalid.
			$this->get_access_token( $this->get_site_key(), true );
		}

		if ( ! isset( $response['status'] ) || $response['status'] !== 'success' ) {
			/**
			 * If Lite Connect API is not available in the add_form_entry()
			 * request above, then a task is created to run it later via Action
			 * Scheduler.
			 */
			( new SendEntryTask() )->create( $entry_args['form_id'], $entry_data );
		}

		// Increase the entries count if the entry has been added successfully.
		if ( isset( $response['status'] ) && $response['status'] === 'success' ) {
			$this->increase_entries_count();
		}

		if ( ! empty( $response['error'] ) ) {
			wpforms_log(
				'Lite Connect: error submitting form entry',
				[
					'response'   => $response,
					'entry_args' => $entry_args,
				],
				[
					'type'    => [ 'error' ],
					'form_id' => $entry_args['form_id'],
				]
			);
		}

		return $response;
	}

	/**
	 * Increases the Lite Connect entries count.
	 *
	 * @since 1.7.4
	 */
	public function increase_entries_count() {

		self::maybe_set_entries_count();

		update_option( self::LITE_CONNECT_ENTRIES_COUNT_OPTION, self::get_entries_count() + 1 );
	}
}
