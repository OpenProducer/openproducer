<?php
$classes = apply_filters( 'Boldgrid\Library\Views\KeyPrompt\classes', array() );

$newKeyUrl = \Boldgrid\Library\Library\Key\PostNewKey::getCentralUrl();
?>
<div id="container_boldgrid_api_key_notice"
	class="boldgrid-notice library error notice is-dismissible <?php echo ! empty( $classes ) ? implode( ' ', $classes ) : ''; ?>"
	data-notice-id="bg-key-prompt"
	data-notice-state="<?php echo \Boldgrid\Library\Library\Notice\KeyPrompt::getState() ?>"
	>
	<div class="premium-key-active key-entry-message">
		<h2>
			<span class="dashicons dashicons-admin-network"></span>
			<?php esc_html_e( 'Premium BoldGrid Connect Key', 'boldgrid-library' )?>
		</h2>
		<p>
			<?php esc_html_e( 'Awesome! You have a Premium Connect Key saved on this site.', 'boldgrid-library' ) ?>
		</p>
		<p>
			<?php printf(
				// translators: 1 The opening anchor tag linking to the the "WordPress Plugins" page on Boldgrid.com, 2 its closing anchor tag, 3 the opening anchor tag to BoldGrid Central, 4 its closing anchor tag.
				esc_html__( 'Make sure you\'re getting the most out of your premium subscription by installing our other %1$sBoldGrid plugins%2$s. As a Premium user, you also have access to %3$sCloud WordPress%4$s where you can create new WordPress sites for free. If you need any help, our support team is eager to serve!', 'boldgrid-library' ),
				'<a href="https://www.boldgrid.com/wordpress-plugins/" target="_blank">', '</a>',
				'<a href="https://www.boldgrid.com/central/" target="_blank">', '</a>'
			); ?>
		</p>
		<p class='change-key'><a href="#" data-action="change-connect-key"><?php _e( 'Click here to change your Connect Key', 'boldgrid-library' ) ?></a></p>
	</div>
	<div class="basic-key-active key-entry-message">
		<h2>
			<span class="dashicons dashicons-admin-network"></span>
			<?php esc_html_e( 'Free BoldGrid Connect Key', 'boldgrid-library' )?>
		</h2>

		<?php if ( ! $enableClaimMessage ) { ?>
		<p>
			<?php esc_html_e( 'Thank you for adding your Connect Key. Try upgrading to a Premium subscription for full access to BoldGrid!', 'boldgrid-library' ); ?>
		</p>
		<p><a target="_blank" href="https://www.boldgrid.com/connect-keys?source=library-prompt"
				class="button button-primary"><?php esc_html_e( 'Upgrade', 'boldgrid-library' ) ?></a>
		</p>
		<?php } else {
			include __DIR__ . '/EnvatoFreeKey.php';
		} ?>
		<p class='change-key'><a href="#" data-action="change-connect-key"><?php esc_html_e( 'Click here to change your Connect Key', 'boldgrid-library' ) ?></a></p>
	</div>
	<div class="api-notice">
		<?php
		/**
		 * Allow plugins to add a heading to the key prompt.
		 *
		 * @since 2.8.0
		 *
		 * @param Ambiguous $heading
		 */
		$heading = apply_filters( 'Boldgrid\Library\Views\KeyPrompt\header', false );
		echo ! empty( $heading ) ? '<h1>' . esc_html( $heading ) . '</h1>' : '';
		?>
		<h2>
			<span class="dashicons dashicons-admin-network"></span>
			<?php esc_html_e( 'Enter your BoldGrid Connect Key to use BoldGrid', 'boldgrid-library' ); ?>
		</h2>
		<p id="boldgrid_api_key_notice_message" style="margin-top:2em">
			<?php esc_html_e( 'Already have your Key? Enter it below and click Submit:', 'boldgrid-library' ); ?>
		</p>
		<form id="boldgrid-api-form" autocomplete="off">
			<?php wp_nonce_field( 'boldgrid_set_key', 'set_key_auth' ); ?>
			<p class="tos-box">
				<label>
					<input id="tos-box" type="checkbox" value="0">
					<?php printf(
						// translators: 1 The opening anchor tag to BoldGrid's TOS page, 2 its closing anchor tag.
						esc_html__( 'I agree to the %1$sTerms of Use and Privacy Policy%2$s.', 'boldgrid-library' ),
						'<a href="https://www.boldgrid.com/software-privacy-policy/" target="_blank">',
						'</a>'
					); ?>
				</label>
			</p>
			<p>
				<input type="text" id="boldgrid_api_key" maxlength="37" placeholder="XXXXXXXX - XXXXXXXX - XXXXXXXX - XXXXXXXX" autocomplete="off" />
				<button id="submit_api_key" class="button button-primary">
					<?php esc_html_e( 'Submit', 'boldgrid-library' ); ?>
				</button>
				<span class="spinner spinner-left"></span>
				<span>
					<div id="boldgrid-api-loading" class="boldgrid-wp-spin"></div>
				</span>
			</p>
		</form>
		<?php
		if ( $enableClaimMessage ) {
			include __DIR__ . '/Envato.php';
		} else {
			// Display either the Envato message or the default signup message.
			?>
				<p>
					<?php esc_html_e( 'Don\'t have a Key or lost your Key?', 'boldgrid-library' ); ?><br />
					<a href="#" class="boldgridApiKeyLink button button-primary">
						<?php esc_html_e( 'Get a BoldGrid Connect Key', 'boldgrid-library' ); ?>
					</a>
				</p>
		<?php } ?>
	</div>
	<?php
	// Display either the Envato message or the default signup message.

	?>
	<div class="new-api-key hidden">
		<h2>
			<span class="dashicons dashicons-admin-network"></span>
			<?php esc_html_e( 'Request or Reset a BoldGrid Connect Key', 'boldgrid-library' ); ?>
		</h2>
		<div class="key-request-content">
			<p id="requestKeyMessage">
				<?php printf(
					// translators: 1 An opening strong tag, 2 its closing strong tag, 3 the opening anchor tag linking to BoldGrid's "Connect Key" pages, 4 its closing anchor tag.
					esc_html__(
						'You may obtain two different types of Connect Keys: A Free Key or a Premium Connect Key (%3$sclick here%4$s for the benefits of a Premium Key).
						%1$sA Premium Connect Key is highly recommended and may already come with your hosting account.%2$s',
						'boldgrid-library'
					),
					'<strong>',
					'</strong>',
					'<a href="https://www.boldgrid.com/connect-keys?source=library-prompt" target="_blank">',
					'</a>' );
				?>
				<br />
			</p>
			<p class="error-alerts"></p>
			<p><?php esc_html_e( 'Check with your host or visit BoldGrid Central to sign up and get your Connect Key.', 'boldgrid-library' ); ?></p>
			<p>
				<a class="button-primary" href="<?php echo $newKeyUrl; ?>">BoldGrid Central<a>
			</p>

			<p style="margin-top:2em;">
				<a href="#" class="enterKeyLink">
					<?php esc_html_e( 'Have a Connect Key to enter?', 'boldgrid-library' ); ?>
				</a>
			</p>
		</div>
	</div>
</div>
