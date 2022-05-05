<script id="transactions-template" type="text/x-handlebars-template">
<table class="widefat" id='receipts'>
	<thead>
		<tr>
			<th><?php echo esc_html__( 'Transaction ID', 'boldgrid-inspirations' ); ?></th>
			<th class='sort-date sorted asc'>
				<a href=''>
					<span><?php echo esc_html__( 'Date', 'boldgrid-inspirations' ); ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th><?php echo esc_html__( 'Items', 'boldgrid-inspirations' ); ?></th>
			<th><?php echo esc_html__( 'Coins', 'boldgrid-inspirations' ); ?></th>
			<th><?php echo esc_html__( 'Invoice', 'boldgrid-inspirations' ); ?></th>
		</tr>
	</htead>
	<tbody>
		{{#each transactions}}
		<tr>
			<td>{{transaction_id}}</td>
			<td>{{transaction_date}}</td>
			<td>{{objCount transaction_item}}</td>
			<td>
				<span class='coin-bg-s'>
					{{#ifCond transaction_total '<' 0}}{{multiply transaction_total "-1"}}{{else}}{{transaction_total}}{{/ifCond}}
					{{#ifCond transaction_total '>' 0}}(<?php echo esc_html__( 'Credit', 'boldgrid-inspirations' ); ?>){{/ifCond}}
				</span>
			</td>
			<td><a class='view' data-transaction-id="{{transaction_id}}" href='#'><?php echo esc_html__( 'View', 'boldgrid-inspirations' ); ?></a></td>
		</tr>
		{{/each}}
</table>
</script>

<script id="no-transactions-template" type="text/x-handlebars-template">
	<p><?php echo esc_html__( 'There are no transactions to display at this time.', 'boldgrid-inspirations' ); ?></p>
</script>

<?php // Example object being passed in: http://pastebin.com/sgQL6Bb1 ?>
<script id="transaction-template" type="text/x-handlebars-template">
<h1><?php echo esc_html__( 'Invoice for Transaction ID:', 'boldgrid-inspirations' ); ?> {{transaction_id}}</h1>
<table class="widefat receipt">
	<thead>
		<tr>
			<th><?php echo esc_html__( 'Description', 'boldgrid-inspirations' ); ?></th>
			<th></th>
			<th><?php echo esc_html__( 'Coins', 'boldgrid-inspirations' ); ?></th>
			<th></th>
		</tr>
	</htead>
	<tbody>
		{{#each transaction_item}}
		<tr data-user-transaction-item-id='{{user_transaction_item_id}}'>
			<td class='thumbnail'>
				<span class="spinner inline"></span>
			</td>
			<td>
				{{description}}
				{{#ifCond coins '>' 0}}
					{{#isSetAndNotNull ../../transaction_reseller_title}}
						(<strong><?php echo esc_html__( 'Processed by', 'boldgrid-inspirations' ); ?></strong>: <em>{{../../../transaction_reseller_title}})</em>
					{{/isSetAndNotNull}}
				{{/ifCond}}
			</td>
			<td>
				<span class='coin-bg-s'>
					{{#ifCond coins '<' 0}}{{multiply coins "-1"}}{{else}}{{coins}}{{/ifCond}}
				</span>
			</td>
			<td class='redownload'></td>
		</tr>
		{{/each}}
</table>
</script>

<script id="tablenav-top-template" type="text/x-handlebars-template">
	<div class='tablenav-pages'>
		{{this}} <?php echo esc_html__( 'Invoices', 'boldgrid-inspirations' ); ?>
	</div>
</script>
