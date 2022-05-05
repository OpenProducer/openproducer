<?php
// Prevent direct calls.
require BOLDGRID_BASE_DIR . '/pages/templates/restrict-direct-access.php';

$reseller = new Boldgrid\Library\Library\Reseller();

// Configure the "purchase coins" link.
$reseller_link = sprintf(
	wp_kses(
		// translators: 1 URL to BoldGrid Central.
		__( 'You can purchase additional coins through <a href="%1$s" target="_blank">BoldGrid Central</a>.', 'boldgrid-inspirations' ),
		array( 'a' => array( 'href' => array(), 'target' => array() ) )
	),
	esc_url( $reseller->centralUrl )
);
if ( $reseller->hasCoinUrl() && isset( $reseller->data['reseller_title'] ) ) {
	$reseller_link = sprintf(
		wp_kses(
			// translators: 1 URL to reseller's page where user can purchase additional BoldGrid Connect Coins, 2 the name of the reseller.
			__( 'You can purchase additional coins through your official BoldGrid reseller, <a href="%1$s" target="_blank">%2$s</a>.', 'boldgrid-inspirations' ),
			array( 'a' => array( 'href' => array(), 'target' => array() ) )
		),
		$reseller->data['reseller_coin_url'],
		$reseller->data['reseller_title']
	);
}

?>

<div class='wrap'>

<?php
	// include the navigation
	include BOLDGRID_BASE_DIR . '/pages/includes/cart_header.php';
?>

	<div class='plugin-card'>

		<div class='plugin-card-top'>
			<p><?php
				echo $reseller_link . ' ' .
				esc_html__( 'After you have purchased additional coins, your new coin balance will update on the transaction pages.', 'boldgrid-inspirations' );
			?></p>
		</div>

	</div>

</div>