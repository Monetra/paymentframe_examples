<header>
	<div id="header-filter"></div>
	<h1>Complete Your Order</h1>
</header>
<main>
	<div id="payment-info">
		<form id="customer-data-form" data-action="transaction">
			<label>
				<span>Name</span>
				<input type="text" name="cardholdername" />
			</label>
			<label>
				<span>Email Address</span>
				<input type="email" placeholder="" name="email" />
			</label>
			<label>
			<label id="signup-label">
				<input type="checkbox" />
				<span>Sign up for our rewards program</span>
			</label>
			<label id="phone-label">
				<span>Phone Number (optional)</span>
				<input type="text" />
			</label>
			<input type="hidden" name="tax" value="1.01" />
			<input type="hidden" name="amount" value="15.45" />
		</form>
		<div id="iframe-container">
			<iframe name="<?php echo $iframe_name; ?>" id="iframe" <?php echo $iframe_attribute_string; ?>></iframe>
		</div>
		<div id="error-message" class="hidden"></div>
	</div>
	<div id="order-info">
		<h2>Your Pickup Order</h2>
		<ul id="order-item-list">
			<li>
				<img src="./images/food/avocado-toast.jpg" width="250" />
				<div>
					<h3>Avocado Toast</h3>
					<h4>+ Eggs</h4>
					<span class="total-price">$9.49</span>
				</div>
			</li>
			<li>
				<img src="./images/food/coffee.jpg" width="250" />
				<div>
					<h3>Coffee</h3>
					<h4>+ Cream</h4>
					<h4>+ Sugar</h4>
					<span class="total-price">$4.95</span>
				</div>
			</li>
		</ul>
		<div class="order-price">
			<span>Subtotal: $14.44</span>
			<span>Tax: $1.01</span>
			<span id="order-total-price">
				Total: $15.45
			</span>
		</div>
	</div>
</main>
