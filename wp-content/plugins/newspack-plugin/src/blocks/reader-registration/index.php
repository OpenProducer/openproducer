<?php
/**
 * Newspack Blocks.
 *
 * @package Newspack
 */

namespace Newspack\Blocks\ReaderRegistration;

use Newspack;
use Newspack\Reader_Activation;
use Newspack\Recaptcha;

defined( 'ABSPATH' ) || exit;

const FORM_ACTION = 'newspack_reader_registration';

/**
 * Register block from metadata.
 */
function register_block() {
	// Allow render_block callback to run so we can ensure it renders nothing.
	\register_block_type_from_metadata(
		__DIR__ . '/block.json',
		array(
			'render_callback' => __NAMESPACE__ . '\\render_block',
		)
	);

	// No need to register block styles if Reader Activation is disabled.
	if ( ! Reader_Activation::is_enabled() ) {
		return;
	}
}
add_action( 'init', __NAMESPACE__ . '\\register_block' );

/**
 * Enqueue front-end scripts.
 */
function enqueue_scripts() {
	$should_enqueue_scripts = Reader_Activation::allow_reg_block_render();
	/**
	 * Filters whether to enqueue the reader registration block scripts.
	 *
	 * @param bool $should_enqueue_scripts Whether to enqueue the reader registration block scripts.
	 */
	if ( ! apply_filters( 'newspack_enqueue_reader_activation_block', $should_enqueue_scripts ) ) {
		return;
	}

	$handle = 'newspack-reader-registration-block';
	\wp_enqueue_style(
		$handle,
		\Newspack\Newspack::plugin_url() . '/dist/reader-registration-block.css',
		[],
		NEWSPACK_PLUGIN_VERSION
	);
	\wp_enqueue_script(
		$handle,
		\Newspack\Newspack::plugin_url() . '/dist/reader-registration-block.js',
		[ 'wp-polyfill', 'newspack-reader-activation' ],
		NEWSPACK_PLUGIN_VERSION,
		true
	);
	\wp_script_add_data( $handle, 'async', true );
	\wp_script_add_data( $handle, 'amp-plus', true );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts' );

/**
 * Generate a unique ID for each registration form.
 *
 * The ID for each form instance is unique only for each page render.
 * The main intent is to be able to pass this ID to analytics so we
 * can identify what type of form it is, so the ID doesn't need to be
 * predictable nor consistent across page renders.
 *
 * @return string A unique ID string to identify the form.
 */
function get_form_id() {
	return \wp_unique_id( 'newspack-register-' );
}

/**
 * Render Registration Block.
 *
 * @param array[] $attrs Block attributes.
 * @param string  $content Block content (inner blocks) – success state in this case.
 */
function render_block( $attrs, $content ) {
	// Render nothing if Reader Activation is disabled and not a preview request.
	if ( ! Reader_Activation::allow_reg_block_render() ) {
		return '';
	}

	$registered      = false;
	$my_account_url  = function_exists( 'wc_get_account_endpoint_url' ) ? \wc_get_account_endpoint_url( 'dashboard' ) : false;
	$message         = '';
	$success_message = __( 'Success! Your account was created and you’re signed in.', 'newspack-plugin' ) . '<br />';

	if ( $my_account_url ) {
		$success_message .= sprintf(
			// Translators: %s is a link to My Account.
			__( 'Please visit %s to verify and manage your account.', 'newspack-plugin' ),
			'<a href="' . esc_url( $my_account_url ) . '">' . __( 'My Account', 'newspack-plugin' ) . '</a>'
		);
	}

	/** Handle default attributes. */
	$default_attrs = [
		'label'           => __( 'Sign up', 'newspack-plugin' ),
		'newsletterLabel' => __( 'Subscribe to our newsletter', 'newspack-plugin' ),
		'signInLabel'     => __( 'Sign in to an existing account', 'newspack-plugin' ),
		'signedInLabel'   => __( 'An account was already registered with this email. Please check your inbox for an authentication link.', 'newspack-plugin' ),
	];
	$attrs         = \wp_parse_args( $attrs, $default_attrs );
	foreach ( $default_attrs as $key => $value ) {
		if ( empty( $attrs[ $key ] ) ) {
			$attrs[ $key ] = $value;
		}
	}

	$sign_in_url = \wp_login_url();
	if ( function_exists( 'wc_get_account_endpoint_url' ) ) {
		$sign_in_url = $my_account_url;
	}

	/** Setup list subscription */
	$lists = [];
	if ( $attrs['newsletterSubscription'] && method_exists( 'Newspack_Newsletters_Subscription', 'get_lists_config' ) ) {
		$list_config = \Newspack_Newsletters_Subscription::get_lists_config();
		if ( ! \is_wp_error( $list_config ) ) {
			// get existing lists preserving the order.
			foreach ( $attrs['lists'] as $list_id ) {
				if ( isset( $list_config[ $list_id ] ) ) {
					$lists[ $list_id ] = $list_config[ $list_id ];
				}
			}
		}
	}

	$is_admin_preview = method_exists( 'Newspack_Popups', 'is_user_admin' ) && \Newspack_Popups::is_user_admin();

	// phpcs:disable WordPress.Security.NonceVerification.Recommended
	if (
		! \is_preview() &&
		! $is_admin_preview &&
		( ! method_exists( '\Newspack_Popups', 'is_preview_request' ) || ! \Newspack_Popups::is_preview_request() ) &&
		(
			\is_user_logged_in() ||
			( isset( $_GET['newspack_reader'] ) && absint( $_GET['newspack_reader'] ) )
		)
	) {
		$registered = true;
		$message    = $success_message;
	}
	if ( isset( $_GET['newspack_reader'] ) && isset( $_GET['message'] ) ) {
		$message = \sanitize_text_field( $_GET['message'] );
	}
	// phpcs:enable

	$success_registration_markup = $content;
	if ( empty( \wp_strip_all_tags( $content ) ) ) {
		$success_registration_markup = '<p>' . $success_message . '</p>';
	}

	$success_login_markup = $attrs['signedInLabel'];
	if ( ! empty( \wp_strip_all_tags( $attrs['signedInLabel'] ) ) ) {
		$success_login_markup = '<p>' . $attrs['signedInLabel'] . '</p>';
	}

	$checked = [];
	if ( ! empty( $attrs['listsCheckboxes'] ) ) {
		foreach ( $lists as $list_id => $list_name ) {
			if ( isset( $attrs['listsCheckboxes'][ $list_id ] ) && true === $attrs['listsCheckboxes'][ $list_id ] ) {
				$checked[] = $list_id;
			}
		}
	}

	ob_start();
	?>
	<div class="newspack-registration newspack-ui <?php echo esc_attr( get_block_classes( $attrs ) ); ?>">
		<?php if ( $registered ) : ?>
			<div class="newspack-ui__box newspack-ui__box--success newspack-ui__box--text-center">
				<span class="newspack-ui__icon newspack-ui__icon--success">
					<svg aria-hidden="true" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
						<path d="M16.7 7.1l-6.3 8.5-3.3-2.5-.9 1.2 4.5 3.4L17.9 8z" />
					</svg>
				</span>
				<?php echo $success_registration_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php else : ?>
			<form id="<?php echo esc_attr( get_form_id() ); ?>" data-newspack-recaptcha="newspack_register">
				<?php if ( ! empty( $attrs['title'] ) ) : ?>
					<div class="newspack-registration__header">
						<h3 class="newspack-registration__title"><?php echo \wp_kses_post( $attrs['title'] ); ?></h3>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $attrs['description'] ) ) : ?>
					<p class="newspack-registration__description"><?php echo \wp_kses_post( $attrs['description'] ); ?></p>
				<?php endif; ?>
				<input type="hidden" name="<?php echo esc_attr( FORM_ACTION ); ?>" value="<?php echo esc_attr( FORM_ACTION ); ?>" />
				<div class="newspack-registration__form-content">
				<?php
				/**
				 * Action to add custom fields before the form fields of the registration block.
				 *
				 * @param array $attrs Block attributes.
				 */
				do_action( 'newspack_registration_before_form_fields', $attrs );
				?>
					<?php
					if ( ! empty( $lists ) ) {
						if ( 1 === count( $lists ) && $attrs['hideSubscriptionInput'] ) {
							?>
							<input
							<?php
							if ( $is_admin_preview ) :
								?>
								disabled
								<?php endif; ?>
								type="hidden"
								name="lists[]"
								value="<?php echo \esc_attr( key( $lists ) ); ?>"
							/>
							<?php
						} else {
							Reader_Activation::render_subscription_lists_inputs(
								$lists,
								$checked,
								[
									'single_label'     => $attrs['newsletterLabel'],
									'show_description' => $attrs['displayListDescription'],
								]
							);
						}
					}
					?>
					<div class="newspack-registration__main">
						<div>
							<?php Reader_Activation::render_third_party_auth(); ?>
							<div class="newspack-registration__inputs">
								<input
								<?php
								if ( $is_admin_preview ) :
									?>
									disabled
									<?php endif; ?>
									type="email" name="npe" autocomplete="email"
									placeholder="<?php echo \esc_attr( $attrs['placeholder'] ); ?>"
								/>
								<?php Reader_Activation::render_honeypot_field( $attrs['placeholder'] ); ?>
								<button
								<?php
								if ( $is_admin_preview ) :
									?>
									disabled
									<?php endif; ?>
									type="submit"
									class="newspack-ui__button newspack-ui__button--primary"
								>
									<span class="submit"><?php echo \esc_html( $attrs['label'] ); ?></span>
								</button>
							</div>
							<div class="newspack-registration__response <?php echo ( empty( $message ) ) ? 'newspack-registration--hidden' : null; ?>">
								<?php if ( ! empty( $message ) ) : ?>
									<p><?php echo \esc_html( $message ); ?></p>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<div class="newspack-registration__have-account">
					<a href="<?php echo \esc_url( $sign_in_url ); ?>" data-newspack-reader-account-link class="newspack-ui__button newspack-ui__button--ghost">
						<?php echo \wp_kses_post( $attrs['signInLabel'] ); ?>
					</a>
				</div>
				<div class="newspack-registration__help-text">
					<p>
						<?php
						$terms_url = wp_http_validate_url( Reader_Activation::get_setting( 'terms_url' ) );
						if ( $terms_url ) :
							?>
							<a href="<?php echo esc_url( $terms_url ); ?>">
							<?php
						endif;
						$terms_text = empty( $attrs['privacyLabel'] ) ? Reader_Activation::get_setting( 'terms_text' ) : $attrs['privacyLabel'];
						echo \wp_kses_post( $terms_text );
						?>
						<?php if ( $terms_url ) : ?>
						</a>
						<?php endif; ?>
					</p>
				</div>
			</form>
			<div class="newspack-registration__registration-success newspack-registration--hidden newspack-ui__box newspack-ui__box--success newspack-ui__box--text-center">
				<span class="newspack-ui__icon newspack-ui__icon--success">
					<svg aria-hidden="true" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
						<path d="M16.7 7.1l-6.3 8.5-3.3-2.5-.9 1.2 4.5 3.4L17.9 8z" />
					</svg>
				</span>
				<?php echo $success_registration_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<div class="newspack-registration__login-success newspack-registration--hidden newspack-ui__box newspack-ui__box--success newspack-ui__box--text-center">
				<span class="newspack-ui__icon newspack-ui__icon--success">
					<svg aria-hidden="true" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M19.5854 12.6708C19.8395 12.5438 20 12.2841 20 12C20 11.7159 19.8395 11.4562 19.5854 11.3292L5.08543 4.0792C4.79841 3.93569 4.45187 3.99069 4.22339 4.21602C3.9949 4.44135 3.93509 4.78709 4.07461 5.07608L7.4172 12L4.07461 18.924C3.93509 19.213 3.9949 19.5587 4.22339 19.784C4.45187 20.0094 4.79841 20.0644 5.08543 19.9208L19.5854 12.6708ZM8.72077 11.25L6.38144 6.40425L17.573 12L6.38144 17.5958L8.72079 12.75H12V11.25H8.72077Z" />
					</svg>
				</span>
				<?php echo \wp_kses_post( $success_login_markup ); ?>
			</div>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Utility to assemble the class for a server-side rendered block.
 *
 * @param array $attrs Block attributes.
 *
 * @return string Class list separated by spaces.
 */
function get_block_classes( $attrs = [] ) {
	$classes = [];
	if ( isset( $attrs['align'] ) && ! empty( $attrs['align'] ) ) {
		$classes[] = 'align' . $attrs['align'];
	}
	if ( isset( $attrs['className'] ) ) {
		array_push( $classes, $attrs['className'] );
	}
	return implode( ' ', $classes );
}

/**
 * Send the form response to the client, whether it's a JSON or GET request.
 *
 * @param mixed  $data    The response to send to the client.
 * @param string $message Optional custom message.
 */
function send_form_response( $data, $message = '' ) {
	$is_error = \is_wp_error( $data );
	if ( empty( $message ) ) {
		$message = $is_error ? $data->get_error_message() : __( 'Thank you for registering!', 'newspack-plugin' );
	}
	if ( \wp_is_json_request() ) {
		\wp_send_json( compact( 'message', 'data' ), \is_wp_error( $data ) ? 400 : 200 );
		exit;
	} elseif ( isset( $_SERVER['REQUEST_METHOD'] ) && 'GET' === $_SERVER['REQUEST_METHOD'] ) {
		$args_to_remove   = [
			'_wp_http_referer',
			FORM_ACTION,
		];
		$is_existing_user = 0;
		if ( ! $is_error ) {
			$args_to_remove   = array_merge( $args_to_remove, [ 'email', 'lists' ] );
			$is_existing_user = isset( $data['existing_user'] ) && boolval( $data['existing_user'] ) ? 1 : 0;
		}
		\wp_safe_redirect(
			\add_query_arg(
				[
					'newspack_reader' => $is_error ? '0' : '1',
					'message'         => $message,
					'existing_user'   => $is_existing_user,
				],
				\remove_query_arg( $args_to_remove )
			)
		);
		exit;
	}
}

/**
 * Process registration form.
 */
function process_form() {
	// No need to process form values if Reader Activation is disabled.
	if ( ! Reader_Activation::is_enabled() ) {
		return;
	}

	$action = filter_input( INPUT_POST, FORM_ACTION, FILTER_SANITIZE_SPECIAL_CHARS );

	// No need to proceed if we don't have the required params.
	if ( empty( $action ) || $action !== FORM_ACTION ) {
		return;
	}

	$honeypot_trap = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL );

	// Honeypot trap.
	if ( ! empty( $honeypot_trap ) ) {
		return send_form_response(
			[
				'email'         => \sanitize_email( $honeypot_trap ),
				'authenticated' => true,
				'existing_user' => false,
			]
		);
	}

	// reCAPTCHA test.
	$current_page_url = \wp_parse_url( \wp_get_raw_referer() );
	if ( ! empty( $current_page_url['path'] ) ) {
		$current_page_url = \esc_url( \home_url( $current_page_url['path'] ) );
	}
	if ( apply_filters( 'newspack_recaptcha_verify_captcha', Recaptcha::can_use_captcha(), $current_page_url, 'registration_block' ) ) {
		$captcha_result = Recaptcha::verify_captcha();
		if ( \is_wp_error( $captcha_result ) ) {
			return send_form_response( $captcha_result );
		}
	}

	// Note that that the "true" email address field is called `npe` due to the honeypot strategy.
	// The honeypot field is called `email` to hopefully capture bots that might be looking for such a field.
	$email = filter_input( INPUT_POST, 'npe', FILTER_SANITIZE_EMAIL );
	if ( empty( $email ) ) {
		return send_form_response( new \WP_Error( 'invalid_email', __( 'You must enter a valid email address.', 'newspack-plugin' ) ) );
	}

	$lists = filter_input( INPUT_POST, 'lists', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY );
	$metadata = [];
	if ( ! empty( $lists ) ) {
		$metadata['lists'] = $lists;
	}
	$metadata['referer']             = \wp_get_raw_referer(); // wp_get_referer() will return false because it's a POST request to the same page.
	$metadata['current_page_url']    = home_url( add_query_arg( array(), $metadata['referer'] ) );
	$metadata['registration_method'] = 'registration-block';

	/**
	 * Filters the metadata to be saved for a reader registered through the Reader Registration Block.
	 *
	 * @param array  $metadata Metadata.
	 * @param string $email    Email address of the reader.
	 */
	$metadata = apply_filters( 'newspack_register_reader_form_metadata', $metadata, $email );

	$user_id = Reader_Activation::register_reader( $email, '', true, $metadata );

	/**
	 * Fires after a reader is registered through the Reader Registration Block.
	 *
	 * @param string              $email   Email address of the reader.
	 * @param int|false|\WP_Error $user_id The created user ID in case of registration, false if not created or a WP_Error object.
	 * @param array               $metadata Array with metadata about the user being registered.
	 */
	\do_action( 'newspack_reader_registration_form_processed', $email, $user_id, $metadata );

	if ( \is_wp_error( $user_id ) ) {
		return send_form_response( $user_id );
	}

	$user_logged_in = false !== $user_id;

	return send_form_response(
		[
			'email'         => $email,
			'authenticated' => $user_logged_in,
			'existing_user' => ! $user_logged_in,
			'metadata'      => $metadata,
		]
	);
}
add_action( 'template_redirect', __NAMESPACE__ . '\\process_form' );
