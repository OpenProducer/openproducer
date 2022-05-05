<?php
/**
 * BoldGrid Source Code
 *
 * @package Boldgrid_Inspirations_Coins
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

/**
 * The BoldGrid Coins class.
 */
class Boldgrid_Inspirations_Coins extends Boldgrid_Inspirations {
	/**
	 * Get the user's coin balance.
	 *
	 * First, try getting it from the transient.
	 * If it doesn't exist there, reach out to the asset server to get it.
	 *
	 * @see Boldgrid_Inspirations_Api::boldgrid_api_call().
	 *
	 * @return string
	 */
	public function get_coin_balance() {
		// Check for the coin balance in a transient.
		$user_coin_balance = get_transient( 'boldgrid_coin_balance' );

		// If we have an invalid balance, get the latest balance from the asset server.
		if ( ! $user_coin_balance && Boldgrid_Inspirations_Api::get_is_asset_server_available() ) {
			// Configure our API call.
			$boldgrid_configs = $this->get_configs();

			$url_to_get_balance = $boldgrid_configs['ajax_calls']['get_coin_balance'];

			// Make API Call.
			$response = Boldgrid_Inspirations_Api::boldgrid_api_call(
				$url_to_get_balance, false, array(), 'POST'
			);

			set_transient( 'boldgrid_coin_balance', $user_coin_balance, 10 * MINUTE_IN_SECONDS );
		}

		$balance = (
			isset( $response->result->data->balance ) ? $response->result->data->balance : '?'
		);

		return $balance;
	}
}
