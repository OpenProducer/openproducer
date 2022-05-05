<?php

// Prevent direct calls
if ( ! defined( 'WPINC' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

// Get the user's current coin balance.
include_once BOLDGRID_BASE_DIR . '/includes/class-boldgrid-inspirations-coins.php';

$boldgrid_coins = new Boldgrid_Inspirations_Coins();

$current_coin_balance = $boldgrid_coins->get_coin_balance();

// configure the active tab
$checkout_active = ( 'boldgrid-cart' == $_GET['page'] ? 'nav-tab-active' : '' );

$transactions_active = ( 'boldgrid-transactions' == $_GET['page'] ? 'nav-tab-active' : '' );

$purchase_coins_active = ( 'boldgrid-purchase-coins' == $_GET['page'] ? 'nav-tab-active' : '' );
?>

<!-- display available balance -->
<div id='coin_balance_container'>
	<strong><?php echo esc_html__( 'Available Balance', 'boldgrid-inspirations' ); ?></strong>: <span id='coin_balance'><?php echo $current_coin_balance; ?></span>
</div>

<!-- tab navigation for transaction page -->
<div id='boldgrid-transaction-tabs'>
	<h2 class="nav-tab-wrapper">
		<span class="boldgrid-transaction-tab">
			<?php printf( '<a href="%s" class="nav-tab ' . esc_attr__( $transactions_active ) . '">' . esc_html__( 'Receipts / Transaction History', 'boldgrid-inspirations' ) . '</a>', esc_url( add_query_arg('page', 'boldgrid-transactions', admin_url( 'admin.php' ) ) ) ); ?>
		</span> <span class="boldgrid-transaction-tab">
			<?php printf( '<a href="%s" class="nav-tab ' . esc_attr__( $checkout_active ) . '">' . esc_html__( 'Cart / Checkout', 'boldgrid-inspirations' ) . '</a>', esc_url( add_query_arg('page', 'boldgrid-cart', admin_url( 'admin.php' ) ) ) ); ?>
		</span> <span class="boldgrid-transaction-tab">
			<?php printf( '<a href="%s" class="nav-tab ' . esc_attr__( $purchase_coins_active ) . '">' . esc_html__( 'Purchase Coins', 'boldgrid-inspirations' ) . '</a>', esc_url( add_query_arg('page', 'boldgrid-purchase-coins', admin_url( 'admin.php' ) ) ) ); ?>
		</span>
	</h2>
</div>
