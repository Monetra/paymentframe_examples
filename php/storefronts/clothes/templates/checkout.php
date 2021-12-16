<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>TranSafe PaymentFrame Demo</title>
		<link href="https://fonts.googleapis.com/css?family=Roboto+Slab&display=swap" rel="stylesheet"> 
		<link rel="stylesheet" type="text/css" href="./storefronts/shared/css/host.css" />
		<link rel="stylesheet" type="text/css" href="./storefronts/clothes/css/host.css" />
		<link rel="stylesheet" type="text/css" href="./storefronts/clothes/css/receipt.css" />
	</head>
	<body>
		<?php include './storefronts/shared/templates/header.php' ?>
		<div id="content-container">
			<header>
				<h1>Complete Your Order</h1>
			</header>
			<main>
				<div id="order-info">
					<h2>Items in your Order</h2>
					<ul id="order-item-list">
						<li>
							<div>
								<img src="./storefronts/clothes/images/gloves.jpg" width="150" />
								<div>
									<h3>Gloves</h3>
									<h4>Medium, brown</h4>
									<span class="unit-price">1 x $40.00</span>
								</div>
							</div>
							<span class="total-price">$40.00</span>
						</li>
						<li>
							<div>
								<img src="./storefronts/clothes/images/tie.jpg" width="150" />
								<div>
									<h3>Tie</h3>
									<h4>Blue</h4>
									<span class="unit-price">2 x $25.00</span>
								</div>
							</div>
							<span class="total-price">$50.00</span>
						</li>
						<li>
							<div>
								<img src="./storefronts/clothes/images/shoes.jpg" width="150" />
								<div>
									<h3>Shoes</h3>
									<h4>Size 11, dark brown</h4>
									<span class="unit-price">1 x $120.00</span>
								</div>
							</div>
							<span class="total-price">$120.00</span>
						</li>
					</ul>
					<div class="order-amounts">
						<span>Subtotal: $210.00</span>
						<span>Tax: $14.70</span>
						<span>Shipping: <b class="free">$0.00</b></span>
						<span id="order-total-price">
							Order Total: $224.70
						</span>
					</div>
				</div>
				<div id="payment-info">
					<h2>Customer Details</h2>
					<form id="customer-data-form" data-action="transaction">
						<label>
							<span>Name</span>
							<input type="text" name="cardholdername" />
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
						<input type="hidden" name="amount" value="224.70" />
						<input type="hidden" name="tax" value="14.70" />
					</form>
					<h2>Payment Information</h2>
					<div id="iframe-container">
						<iframe id="iframe" <?= implode(" ", $iframe_attributes) ?>></iframe>
					</div>
					<div id="error-message" class="hidden"></div>
				</div>
			</main>
		</div>
		<?php include './storefronts/shared/templates/receipt-modal.php' ?>
		<?php include './storefronts/shared/templates/test-card-modal.php' ?>
		<script src="https://<?= MONETRA_HOST ?>:<?= MONETRA_PORT ?>/PaymentFrame/PaymentFrame.js"></script>
		<script src="./storefronts/shared/js/host.js"></script>
		<script src="./storefronts/clothes/js/checkout.js"></script>
	</body>
</html>