<h3><?php esc_html_e( 'Start Over', 'boldgrid-inspirations' ); ?></h3>

<?php
// Allow the user to add &show_start_over=1 to the url to force showing the start over
// feature.
$show_start_over = ( isset( $_GET['show_start_over'] ) && 1 == $_GET['show_start_over'] );

if ( true == $this->user_has_built_a_boldgrid_site() || true == $show_start_over ) {
	?>

<p><?php echo esc_html__( 'Starting over will let you start over with a fresh site to run the BoldGrid Inspirations installer again.', 'boldgrid-inspirations' ); ?></p>

<form method="post">

<?php wp_nonce_field( 'start_over' ); ?>

<div class='plugin-card col-xs-12 col-sm-10 col-md-10 col-lg-6'>
		<div class='plugin-card-top'>

			<strong><?php echo esc_html__( 'Pages, Posts, and Menus:', 'boldgrid-inspirations' ); ?></strong>

			<p>

				<?php printf(
					wp_kses(
						// translators: 1 opening strong tag, 2 closing strong tag.
						__( 'This %1$sWILL%2$s unpublish all of your pages and posts, and all of your menus %1$sWILL%2$s be deleted!', 'boldgrid-inspirations' ),
						array( 'strong' => array() )
					),
					'<strong>',
					'</strong>'
				); ?>

			</p>

			<input type="checkbox" id="start_over" name="start_over" value="Y" />

			<span><?php echo esc_html__( 'Yes, let me start fresh!', 'boldgrid-inspirations' ); ?></span>
			<span id="boldgrid-alert-remove" style="display: none;">
				<?php printf(
					wp_kses(
						// translators: 1
						__( '%1$sWARNING:%2$s Pressing the "Start Over" button below will move your pages and posts to your trash!', 'boldgrid-inspirations' ),
						array( 'strong' => array() )
					),
					'<strong>',
					'</strong>'
				); ?>
			</span>
			<br /><br />

	<?php

	/**
	 * Allow an action after the "Start Over" option is printed.
	 *
	 * @since 1.2.12
	 */
	do_action( 'boldgrid_settings_after_start_fresh' );

	/**
	 * Give the user the option to start over with either / both their active / staging
	 * site.
	 *
	 * If the BoldGrid Staging plugin is installed, give the user the option to select which site to
	 * start over with. Otherwise, they will start over with their active site.
	 */
	// If the staging l
	if ( true == $this->staging_installed ) {
		?>
 		<?php echo esc_html__( 'Which sites would you like to perform the above actions with?', 'boldgrid-inspirations' ); ?><br />
 		<input type="checkbox" name="start_over_active" value="start_over_active" checked> <?php echo esc_html__( 'Active', 'boldgrid-inspirations' ); ?><br />
 		<input type="checkbox" name="start_over_staging" value="start_over_staging" checked> <?php echo esc_html__( 'Staging', 'boldgrid-inspirations' ); ?><br /> <br />
 		<?php
	} else {
		?>
		<input type="hidden" name="start_over_active"
				value="start_over_active" class="hidden">
		<?php
	}
	?>

			<hr />
			<br /> <strong><?php echo esc_html__( 'BoldGrid Themes:', 'boldgrid-inspirations' ); ?></strong><br /> <br />

			<input type="checkbox" id="boldgrid_delete_themes" name="boldgrid_delete_themes" value="1" /> <span><?php echo esc_html__( 'Remove all BoldGrid Themes.', 'boldgrid-inspirations' ); ?></span>
		</div>
	</div>
	<div style='clear: both;'></div>

<?php
	// Print the "Start Over" button.
	submit_button( __( 'Start Over', 'boldgrid-inspirations' ), 'secondary', 'submit', false,
		array (
			'id' => 'start_over_button'
		) );
	?>
</form>

<?php }else { ?>

<p>
	<?php
	$link = sprintf( '<span class="dashicons-before dashicons-lightbulb"><a href="%s">BoldGrid Inspirations</a>', esc_url( add_query_arg( 'page', 'boldgrid-inspirations', admin_url( 'admin.php' ) ) ) );
	// translators: 1 The url to access BoldGrid Inspirations in your WordPress dashboard.
	printf( __( 'You do not have a BoldGrid site to delete! You can build a new website using %1$s.', 'boldgrid-inspirations' ), $link );
	?>
</p>
<?php
}
?>

<hr />
