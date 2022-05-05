<?php
/**
 * BoldGrid Library Notice
 *
 * @package Boldgrid\Library
 * @subpackage \Library
 *
 * @version 1.0.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library;

/**
 * BoldGrid Library Notice Class.
 *
 * This class is responsible for adding any admin notices that are
 * displayed in the WordPress dashboard.
 *
 * @since 1.0.0
 */
class Notice {

	/**
	 * @access private
	 *
	 * @var string $name  Name of notice to create.
	 * @var array  $args  Optional arguments if required by class.
	 * @var mixed  $class Instantiated class object or add filters.
	 */
	private
		$name,
		$args,
		$class;

	/**
	 * Initialize class and set class properties.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name  Name of notice to create.
	 * @param array  $args  Optional arguments if required by class being instantiated.
	 */
	public function __construct( $name, $args = null ) {
		$this->name = $name;
		$this->args = $args;
		$this->setClass( $name, $args );

		Filter::add( $this );
	}

	/**
	 * Sets the class class property.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $name Name of the notice class to initialize.
	 * @param  array  $args Optional arguments to pass to the class initialization.
	 *
	 * @return string $class The class class property.
	 */
	private function setClass( $name, $args ) {

		// Build the class string dynamically.
		$class = __NAMESPACE__ . '\\Notice\\' . ucfirst( $name );

		// Create a new instance or add our filters.
		return $this->class = class_exists( $class ) ? new $class( $args ) : Filter::add( $this );
	}

	/**
	 * Show an admin notice.
	 *
	 * At one point we had this method running on the following hook:
	 * Boldgrid\Libary\Notice\show. Because several different notices are
	 * instantiating this class (such as the keyPrompt and ClaimPremiumKey notices),
	 * the hook was being added more than once, causing duplicate admin notices
	 * to show.
	 *
	 * @since 2.2.0
	 *
	 * @param string $message
	 * @param string $id
	 * @param string $class
	 */
	public static function show( $message, $id, $class = "notice notice-warning" ) {
		$nonce = wp_nonce_field( 'boldgrid_set_key', 'set_key_auth', true, false );

		if( self::isDismissed( $id ) ) {
			return;
		}

		printf( '<div class="%1$s boldgrid-notice is-dismissible" data-notice-id="%2$s">%3$s%4$s</div>',
			$class,
			$id,
			$message,
			$nonce
		);

		/*
		 * Enqueue js required to allow for notices to be dismissed permanently.
		 *
		 * When notices (such as the "keyPrompt" notice) are shown by creating a new instance of
		 * this class, the js is enqueued by the Filter::add call in the constructor. We do however
		 * allow notices to be shown via this static method, and so we need to enqueue the js now.
		 */
		self::enqueue();
	}

	/**
	 * Displays the license notice in the WordPress admin.
	 *
	 * @since 1.0.0
	 *
	 * @nohook: admin_notices
	 */
	public function add( $name = null ) {
		$name = $name ? $name : $this->getName();
		$path = __DIR__;
		$name = ucfirst( $name );
		include  "{$path}/Views/{$name}.php";
	}

	/**
	 * Get the name class property.
	 *
	 * @since  1.0.0
	 *
	 * @return string $name The name class property.
	 */
	protected function getName() {
		return $this->name;
	}

	/**
	 * Get the args class property.
	 *
	 * @since  1.0.0
	 *
	 * @return string $name The args class property.
	 */
	protected function getArgs() {
		return $this->args;
	}

	/**
	 * Handle dismissal of the key prompt notice.
	 *
	 * @since 2.1.0
	 *
	 * @static
	 *
	 * @see \Boldgrid\Library\Library\Notice::isDismissed()
	 *
	 * @uses $_POST['notice'] Notice id.
	 *
	 * @hook: wp_ajax_dismissBoldgridNotice
	 */
	public static function dismiss() {
		// Validate nonce.
		if ( isset( $_POST['set_key_auth'] ) && check_ajax_referer( 'boldgrid_set_key', 'set_key_auth', false ) ) {
			$id = sanitize_key( $_POST['notice'] );

			// Mark the notice as dismissed, if not already done so.
			$dismissal = array(
				'id' => $id,
				'timestamp' => time(),
			);

			if ( ! Notice::isDismissed( $id ) ) {
				add_user_meta( get_current_user_id(), 'boldgrid_dismissed_admin_notices', $dismissal );
			}
		}
	}

	/**
	 * Handle undismissal of the key prompt notice.
	 *
	 * @since 2.1.0
	 *
	 * @uses $_POST['notice'] Notice id.
	 *
	 * @hook: wp_ajax_undismissBoldgridNotice
	 */
	public function undismiss() {
		// Validate nonce.
		if ( isset( $_POST['set_key_auth'] ) && ! empty( $_POST['notice'] ) && check_ajax_referer( 'boldgrid_set_key', 'set_key_auth', false ) ) {
			$id = sanitize_key( $_POST['notice'] );

			// Get all of the notices this user has dismissed.
			$dismissals = get_user_meta( get_current_user_id(), 'boldgrid_dismissed_admin_notices' );

			// Loop through all of the dismissed notices. If we find the dismissal, then remove it.
			foreach ( $dismissals as $dismissal ) {
				if ( $id === $dismissal['id'] ) {
					delete_user_meta( get_current_user_id(), 'boldgrid_dismissed_admin_notices', $dismissal );
					break;
				}
			}
		}
	}

	/**
	 * Is there a user dismissal record for a particular admin notice id?
	 *
	 * @since 2.1.0
	 *
	 * @static
	 *
	 * @param  string $id An admin notice id.
	 * @return bool
	 */
	public static function isDismissed( $id ) {
		$dismissed = false;
		$id = sanitize_key( $id );

		// Get all of the notices this user has dismissed.
		$dismissals = get_user_meta( get_current_user_id(), 'boldgrid_dismissed_admin_notices' );

		// Loop through all of the dismissed notices. If we find our $id, then mark bool and break.
		foreach ( $dismissals as $dismissal ) {
			if ( $id === $dismissal['id'] ) {
				$dismissed = true;
				break;
			}
		}

		return $dismissed;
	}

	/**
	 * Enqueues the required files.
	 *
	 * @since 2.1.0
	 *
	 * @see \Boldgrid\Library\Library\Configs::get()
	 *
	 * @hook: admin_enqueue_scripts
	 */
	public static function enqueue() {
		wp_enqueue_script(
			'bglib-notice-js',
			Configs::get( 'libraryUrl' ) . 'src/assets/js/notice.js'
		);
	}
}
