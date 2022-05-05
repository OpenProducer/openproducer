<?php
/**
 * BoldGrid Source Code
 *
 * @package BoldGrid_Inspirations_Admin_Notices
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

/**
 * The BoldGrid Inspirations Admin Notices class .
 */
class Boldgrid_Inspirations_Admin_Notices {
	/**
	 * Add hooks.
	 */
	public function add_hooks() {
		if ( is_admin() ) {
			// Allow BoldGrid Admin Notices to be dismissed and remembered.
			add_action( 'wp_ajax_dismiss_boldgrid_admin_notice',
				array(
					$this,
					'dismiss_boldgrid_admin_notice_callback',
				)
			);

			// Add the javascript that dismissed admin notices via ajax.
			add_action( 'admin_enqueue_scripts',
				array(
					$this,
					'admin_enqueue_scripts',
				)
			);
		}
	}

	/**
	 * Enqueue scripts.
	 */
	public function admin_enqueue_scripts() {
		// Add the javascript that dismissed admin notices via ajax.
		wp_enqueue_script(
			'boldgrid-admin-notices',
			plugins_url(
				'assets/js/boldgrid-admin-notices.js',
				BOLDGRID_BASE_DIR . '/boldgrid-inspirations.php'
			),
			array(),
			BOLDGRID_INSPIRATIONS_VERSION,
			true
		);
	}

	/**
	 * Clear a dismissal.
	 *
	 * @since 1.2.5
	 *
	 * @param string $id The notice id to dismiss.
	 * @return bool
	 */
	public function clear( $id ) {
		$id = sanitize_key( $id );

		$dismissal = $this->get( $id );

		// If the dismissal doesn't exist, then technically it's been cleared. Return success.
		if ( false === $dismissal ) {
			return true;
		}

		return delete_user_meta( get_current_user_id(), 'boldgrid_dismissed_admin_notices', $dismissal );
	}

	/**
	 * Dismiss a notice.
	 *
	 * @since 1.2.5
	 *
	 * @param string $id The notice id to dismiss.
	 * @return int|bool Meta ID on success, false on failure.
	 */
	public function dismiss( $id ) {
		$id = sanitize_key( $id );

		$dismissal = array(
			'id' => $id,
			'timestamp' => time(),
		);

		return add_user_meta( get_current_user_id(), 'boldgrid_dismissed_admin_notices', $dismissal );
	}

	/**
	 * Allow BoldGrid Admin Notices to be dismissed and remembered.
	 */
	public function dismiss_boldgrid_admin_notice_callback() {
		global $wpdb;

		// Abort if we did not pass in an admin notice id.
		if ( ! isset( $_POST['id'] ) ) {
			wp_die( 'false' );
		}

		// Sanitize the data key.
		$id = sanitize_key( $_POST['id'] );

		// Attempt to dismiss the notice. If it fails, die 'false' otherwise die 'true'.
		if ( false === $this->dismiss( $id ) ) {
			wp_die( 'false' );
		} else {
			wp_die( 'true' );
		}
	}

	/**
	 * Return whether or not an admin notice has been dismissed.
	 *
	 * This method checks for dismissed notices in the initial way we stored the data, in an option
	 * named 'boldgrid_dismissed_admin_notices'. We now store dismissed notice data in user meta.
	 *
	 * @since 1.2.5
	 *
	 * @param string $id An admin notice id.
	 * @return bool
	 */
	public function dismissed_in_deprecated( $id ) {
		$boldgrid_dismissed_admin_notices = get_option( 'boldgrid_dismissed_admin_notices' );

		// If nothing has ever been dismissed, then obviously the user has not dismissed this notice.
		if ( false === $boldgrid_dismissed_admin_notices || ! is_array( $boldgrid_dismissed_admin_notices ) ) {
			return false;
		}

		$id = sanitize_key( $id );

		/*
		 * Dismissed notices can be stored in two ways:
		 * # $dismissed[timestamp] = notice_id;
		 * # $dismissed[notice_id] = timestamp;
		 *
		 * If either of the above is set, the user has dimissed the notice, so return true. Otherwise,
		 * return false.
		 */
		$format_1_dismissed = in_array( $id, $boldgrid_dismissed_admin_notices, true );
		$format_2_dismissed = array_key_exists( $id, $boldgrid_dismissed_admin_notices );

		if ( $format_1_dismissed || $format_2_dismissed ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get a user's dismissal record for a particular id.
	 *
	 * @since 1.2.5
	 *
	 * @param string $id An admin notice id.
	 * @return array|bool If false, the $id was never dismissed.
	 */
	public function get( $id ) {
		$id = sanitize_key( $id );

		// Get all of the notices this user has dismissed.
		$dismissed_notices = get_user_meta( get_current_user_id(), 'boldgrid_dismissed_admin_notices' );

		// Loop through all of the dismissed notices. If we find our $id, return it.
		foreach ( $dismissed_notices as $dismissed_notice ) {
			if ( $id === $dismissed_notice['id'] ) {
				return $dismissed_notice;
			}
		}

		// We did not find our notice dismissed above, so return false.
		return false;
	}

	/**
	 * Return whether or not an admin notice has been dismissed.
	 *
	 * The initial version of this class was setup in a way that only envisioned one user, and they
	 * were an admin. That causes problems. If user 1 dismissed "notice #5", then user 2 and so on
	 * would never see that notice.
	 *
	 * We are changing things so that each user dismisses their own notices. They are no longer
	 * global notices, they are a per user notice.
	 *
	 * @param string $id An admin notice id.
	 * @return bool
	 */
	public function has_been_dismissed( $id ) {
		$id = sanitize_key( $id );

		/*
		 * Backwards compatibility.
		 *
		 * Currently there are 4 notices:
		 * # BoldGrid image search.
		 * # Feedback x weeks after inspirations.
		 * # Notice Inspirations > pages has moved.
		 * # Dependency plugin installation.
		 *
		 * If a notice is dismissed in the old version, then it stays dismissed fooooreeeeer.
		 * @link https://www.youtube.com/watch?v=H-Q7b-vHY3Q
		 * Only new notices will be dismissable per user.
		 */
		if ( $this->dismissed_in_deprecated( $id ) ) {
			return true;
		}

		$dismissal = $this->get( $id );

		/*
		 * If we failed to get the dismissal data, the user never dismissed it, so return false.
		 * Otherwise, we found their dismissal data, meaning they've dismissed it, so return true.
		 */
		if ( false === $dismissal ) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Return boolean for BoldGrid connection issue.
	 *
	 * @return bool
	 */
	public function boldgrid_connection_issue_exists() {
		return 0 === get_site_transient( 'boldgrid_available' );
	}

	/**
	 * Display connection notice callback for "admin_notices" actions.
	 *
	 * @since 1.2.5
	 */
	public static function display_connection_notice() {
		$notice_template_file = BOLDGRID_BASE_DIR .
		'/pages/templates/boldgrid-connection-issue.php';

		// Only show this notice once. If the file has already been included, don't show.
		if ( ! in_array( $notice_template_file, get_included_files(), true ) ) {
			include $notice_template_file;
		}
	}
}
