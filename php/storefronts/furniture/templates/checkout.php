<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>TranSafe PaymentFrame Demo</title>
		<link href="https://fonts.googleapis.com/css?family=Lora&display=swap" rel="stylesheet"> 
		<link rel="stylesheet" type="text/css" href="./storefronts/shared/css/host.css" />
		<link rel="stylesheet" type="text/css" href="./storefronts/furniture/css/host.css" />
		<link rel="stylesheet" type="text/css" href="./storefronts/furniture/css/receipt.css" />
	</head>
	<body>
		<?php include './storefronts/shared/templates/header.php' ?>
		<div id="content-container">
			<header>
				<h1>Complete Your Order</h1>
			</header>
			<main>
				<div id="order-info">
					<h2>Items in your Cart</h2>
					<ul id="order-item-list">
						<li>
							<div>
								<img src="./storefronts/furniture/images/couch.jpeg" width="250" />
								<div>
									<h3>Sofa</h3>
									<h4>Brown</h4>
									<span class="unit-price">1 &times; $1049.99</span>
								</div>
							</div>
							<span class="total-price">$1049.99</span>
						</li>
						<li>
							<div>
								<img src="./storefronts/furniture/images/chair.jpeg" width="250" />
								<div>
									<h3>Chair</h3>
									<h4>Yellow</h4>
									<span class="unit-price">2 &times; $249.99</span>
								</div>
							</div>
							<span class="total-price">$499.98</span>
						</li>
					</ul>
					<div class="order-amounts">
						<span>Subtotal: $1549.97</span>
						<span>Tax: $108.50</span>
						<span>Shipping: $35.00</span>
						<span id="order-total-price">
							Order Total: $1693.47
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
						<label id="country-label">
							<span>Country</span>
							<input required type="text" name="country" />
						</label>
						<input type="hidden" name="amount" value="1693.47" />
						<input type="hidden" name="tax" value="108.50" />
					</form>
					<h2>Payment Information</h2>
					<div id="iframe-container">
						<iframe id="iframe" <?= implode(" ", $iframe_attributes) ?>></iframe>
					</div>
					<div id="error-message" class="hidden"></div>
					<div id="submit-button-container">
						<button id="submit-button">Complete Order</button>
					</div>
				</div>
			</main>
		</div>
		<?php include './storefronts/shared/templates/receipt-modal.php' ?>
		<?php include './storefronts/shared/templates/test-card-modal.php' ?>
		<script src="https://<?= MONETRA_HOST ?>:<?= MONETRA_PORT ?>/PaymentFrame/PaymentFrame.js"></script>
		<script src="./storefronts/shared/js/host.js"></script>
		<script src="./storefronts/furniture/js/checkout.js"></script>
	</body>
</html>