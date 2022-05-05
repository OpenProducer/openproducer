<?php
/**
 * BoldGrid Source Code
 *
 * @package BoldGrid_Inspirations_Install_Backup
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

/**
 * The BoldGrid Inspirations Install Backup class.
 *
 * @since 2.2.0
 */
class Boldgrid_Inspirations_Install_Backup {
	/**
	 * The name of the option tracking the progress of this process.
	 *
	 * @since 2.2.0
	 * @var string
	 * @access private
	 */
	private $option_name = 'boldgrid-inspirations-backup-redirect';

	/**
	 * Add hooks.
	 *
	 * @since 2.2.0
	 */
	public function add_admin_hooks() {
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		add_action( 'activated_plugin', array( $this, 'on_activation' ) );

		add_filter( 'boldgrid_backup_post_activate_notice', array( $this, 'filter_notice' ) );
	}

	/**
	 * Filter the "Total Upkeep installed" notice.
	 *
	 * If Inspirations is installing the plugin for the user, we'll be redirecting them to the Transfers
	 * page. We don't want an admin notice of, "Here's how to create your first backup" to get the user
	 * off of the path we want them on.
	 *
	 * @since 2.2.0
	 *
	 * @return string
	 */
	public function filter_notice( $notice ) {
		if ( 'filter_notice' === get_option( $this->option_name ) ) {
			/*
			 * We're at the last stage of this process. Delete the option so no more redirects /
			 * filters occur.
			 */
			delete_option( $this->option_name );

			$notice = '';
		}

		return $notice;
	}

	/**
	 * Display admin notices.
	 *
	 * If we are installing Total Upkeep for the user, give them a notice during the installation
	 * process to help guide them through to the next step. That notice right now is simply saying,
	 * "Click activate and we'll redirect you".
	 *
	 * @since 2.2.0
	 */
	public function admin_notices() {
		$action = ! empty( $_GET['action'] ) ? $_GET['action'] : '';
		$plugin = ! empty( $_GET['plugin'] ) ? $_GET['plugin'] : '';
		$src    = ! empty( $_GET['src'] ) ? $_GET['src'] : '';

		// Whether or not we are installing Total Upkeep and coming from BoldGrid Inspirations.
		if ( 'install-plugin' !== $action || 'boldgrid-backup' !== $plugin || 'boldgrid-inspirations' !== $src ) {
			return;
		}

		// Flag that we're adding the notice, so we know to redirect after activation.
		update_option( $this->option_name, 'redirect' );

		?><div class="notice notice-info" style="margin-left:0;">
			<h1><?php esc_html_e( 'Thank you for installing Total Upkeep!', 'boldgrid-inspirations' ); ?></h1>
			<p><?php echo wp_kses(
				sprintf(
					__( 'When the installation completes below, click the %1$sActivate Plugin%2$s button. We\'ll then take you to the %1$sTotal Upkeep Site Transfer%2$s wizard.', 'boldgrid-inspirations' ),
					'<strong>',
					'</strong>'
				),
				[ 'strong' => [] ]
			); ?></p>
		</div><?php

		// Clean things up a bit. Remove the h1 on the page because we're supplying our own.
		$script = 'jQuery( function() {
			jQuery( ".wrap h1" ).first().remove();
		});';

		Boldgrid_Inspirations_Utility::inline_js_oneliner( $script );
	}

	/**
	 * Steps to take when any plugin is activated.
	 *
	 * @since 2.2.0
	 *
	 * @param string $plugin Path to the plugin file relative to the plugins directory.
	 */
	public function on_activation( $plugin ) {
		$src = ! empty( $_GET['src'] ) ? $_GET['src'] : '';

		// Whether or not it's Inspirations activating the Total Upkeep plugin.
		$is_inspirations_activation = 'boldgrid-backup/boldgrid-backup.php' === $plugin &&
			'boldgrid-inspirations' === $src;

		/*
		 * If we need to, redirect the user to the Total Upkeep transfers wizard.
		 *
		 * The conditionals look similar, however different logic is needed based on whether we've
		 * had to install the Total Upkeep plugin, or simply activate it.
		 */
		if ( $is_inspirations_activation || 'redirect' === get_option( $this->option_name ) ) {
			// Now that we're redirecting, remind us in a moment to adjust the activation notice.
			update_option( $this->option_name, 'filter_notice' );

			wp_redirect( admin_url( 'admin.php?page=boldgrid-backup-transfers' ), 301 );
			exit;
		}
	}
}
