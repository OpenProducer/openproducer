<?php
/**
 * Form Builder abort message screen template.
 *
 * @since 1.7.3
 *
 * @var string $message An abort message to display.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div id="wpforms-builder-abort-message" class="wpforms-fullscreen-notice wpforms-notice-white wpforms-admin-page">

	<h3 class="waving-hand-emoji"><?php esc_html_e( 'Hi there!', 'wpforms-lite' ); ?></h3>

	<p><?php echo esc_html( $message ); ?></p>

	<img src="<?php echo esc_url( WPFORMS_PLUGIN_URL . 'assets/images/empty-states/no-forms.svg' ); ?>" alt="">

	<?php if ( wpforms_current_user_can( 'view_forms' ) ) { ?>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=wpforms-overview' ) ); ?>" class="wpforms-btn wpforms-btn-lg wpforms-btn-orange">
			<?php esc_html_e( 'Back to All Forms', 'wpforms-lite' ); ?>
		</a>
	<?php } ?>

</div>
