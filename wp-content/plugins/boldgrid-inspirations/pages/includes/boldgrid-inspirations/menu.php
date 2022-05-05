<?php
/**
 * Top menu.
 *
 * This file renders the top menu in the Inspirations process.
 *
 * @since 1.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$active_menu_item = empty( $active_menu_item ) ? 'welcome' : $active_menu_item;

// The $section & $show_content_warning vars are set in pages/boldgrid-inspirations.php
if ( ! empty( $section ) && 'design' === $section && empty( $show_content_warning ) ) {
	$active_menu_item = 'design';
}

$steps = array(
	array(
		'class'     => 'welcome' === $active_menu_item ? 'active' : 'disabled',
		'data-step' => 'welcome',
		'disabled'  => false,
		'title'     => esc_html__( 'Welcome', 'boldgrid-inspirations' ),
	),
	array(
		'class'     => 'design' === $active_menu_item ? 'active' : 'disabled',
		'data-step' => 'design',
		'disabled'  => 'design' !== $active_menu_item,
		'title'     => esc_html__( 'Design', 'boldgrid-inspirations' ),
	),
	array(
		'class'     => 'disabled',
		'data-step' => 'content',
		'disabled'  => true,
		'title'     => esc_html__( 'Content', 'boldgrid-inspirations' ),
	),
	array(
		'class'     => 'disabled',
		'data-step' => 'contact',
		'disabled'  => true,
		'title'     => esc_html__( 'Essentials', 'boldgrid-inspirations' ),
	),
	array(
		'class'     => 'install' === $active_menu_item ? 'active' : 'disabled',
		'data-step' => 'install',
		'disabled'  => 'install' === $active_menu_item ? false : true,
		'title'     => esc_html__( 'Finish', 'boldgrid-inspirations' ),
	),
);

?>

<div class="top-menu welcome">
	<button type="button" class="notice-dismiss" title="<?php esc_attr_e( 'Toggle full screen', 'boldgrid-inspirations' ); ?>">
		<span class="screen-reader-text"><?php echo esc_html__( 'Dismiss this notice.', 'boldgrid-inspirations' ); ?></span>
	</button>

	<div>
		<?php
		$last_class = '';

		foreach( $steps as $step ) {
			$class = $step['class'];
			$class .= 'active' === $step['class'] ? ' boldgrid-orange-important' : '';
			$class .= 'active' === $last_class ? ' next' : '';

			$attributes = array(
				'style="postion:relative;"',
				'class="' . esc_attr( $class ) . '"',
				'data-step="' . esc_attr( $step['data-step'] ) . '"',
				! empty( $step['disabled'] ) ? 'data-disabled' : '',
			);

			echo '<a ' . implode( ' ', $attributes ) . '>' . esc_html( $step['title'] ) . '</a>';

			$last_class = $step['class'];
		}
		?>
	</div>
</div>