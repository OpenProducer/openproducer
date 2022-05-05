<?php

// Prevent direct calls
if ( ! defined( 'WPINC' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

?>
<div class="misc-pub-section boldgrid-auto-add-to-menu">
	<?php esc_html_e( 'In menu', 'boldgrid-inspirations' ); ?>: <span id='selected-menu-names'></span>
	<a href="#edit-boldgrid-auto-add-to-menu" class="edit-boldgrid-auto-add-to-menu hide-if-no-js">
		<span aria-hidden="true"><?php esc_html_e( 'Edit', 'boldgrid-inspirations' ); ?></span>
	</a>
	<div id="boldgrid-auto-add-to-menu-menu-listing" class="hide-if-js">
		<?php echo $nav_menus_html; ?>
		<p>
			<a class="hide-boldgrid-auto-add-to-menu button"><?php esc_html_e( 'OK', 'boldgrid-inspirations' ); ?></a>
			<a class="hide-boldgrid-auto-add-to-menu button-cancel" id="cancel-add-to-menu" href="#edit-boldgrid-auto-add-to-menu"><?php esc_html_e( 'Cancel', 'boldgrid-inspirations' ); ?></a>
		</p>
	</div>
	<input type='hidden' name='boldgrid_auto_add_to_menu_page_id' id='boldgrid-auto-add-to-menu-page-id' value='<?php echo $post->ID; ?>' data-is-new-page='<?php echo $is_new_page; ?>' />
</div>
