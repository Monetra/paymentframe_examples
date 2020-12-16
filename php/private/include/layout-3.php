<header>
	<h1>Checkout</h1>
</header>
<main>
	<div id="order-info">
		<h2>Your new domain names</h2>
		<table id="order-item-table">
			<thead>
				<tr>
					<th>Domain Name</th>
					<th>Registration Period</th>
					<th class="price-column">Price</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>businessname.com</td>
					<td>1 year</td>
					<td class="price-column">$59.99</td>
				</tr>
				<tr>
					<td>businessname.net</td>
					<td>1 year</td>
					<td class="price-column">$19.99</td>
				</tr>
				<tr>
					<td>businessname.biz</td>
					<td>1 year</td>
					<td class="price-column"><span>Special!</span>$9.99</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="3">
						<span>Subtotal: 89.97</span>
						<span>Tax: 6.30</span>
						<span id="order-total-amount">Total: $96.27</span>
					</th>
				</tr>
			</tfoot>
		</table>
	</div>
	<div id="payment-info">
		<form id="customer-data-form" data-action="confirmation" method="POST">
			<label>
				<span>Name</span>
				<input type="text" name="name" />
			</label>
			<label>
				<span>Email</span>
				<input type="email" name="email" />
			</label>
			<label>
				<span>Address</span>
				<input type="text" name="street" />
			</label>
			<label id="city-label">
				<span>City</span>
				<input type="text" name="city" />
			</label>
			<label id="state-label">
				<span>State</span>
				<input type="text" name="state" />
			</label>
			<label id="zip-label">
				<span>Zip</span>
				<input required type="text" name="zip" />
			</label>
			<input type="hidden" name="amount" value="96.27" />
			<input type="hidden" name="tax" value="6.30" />
		</form>
		<div id="iframe-container">
			<iframe name="<?php echo $iframe_name; ?>" id="iframe" <?php echo $iframe_attribute_string; ?>></iframe>
		</div>
	</div>
	<div id="error-message" class="hidden"></div>
</main>
