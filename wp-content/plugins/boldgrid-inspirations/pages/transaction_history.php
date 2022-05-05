<?php
// Prevent direct calls.
require BOLDGRID_BASE_DIR . '/pages/templates/restrict-direct-access.php';

add_thickbox();

include BOLDGRID_BASE_DIR . '/pages/templates/transaction_history.php';

?>
<div class='wrap'>
<?php
	include BOLDGRID_BASE_DIR . '/pages/includes/cart_header.php';
?>
	<h1><?php echo esc_html__( 'Transaction History', 'boldgrid-inspirations' ); ?></h1>
	<div class='tablenav top'></div>
	<div id='transactions'><?php echo esc_html__( 'Loading transaction history...', 'boldgrid-inspirations' ); ?></div>
	<div class='tablenav bottom'></div>
	<div id='transaction' class='hidden'></div>
</div>
