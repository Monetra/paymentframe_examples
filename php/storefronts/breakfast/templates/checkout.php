<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>TranSafe PaymentFrame Demo</title>
		<link href="https://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700&display=swap" rel="stylesheet"> 
		<link rel="stylesheet" type="text/css" href="./storefronts/shared/css/host.css" />
		<link rel="stylesheet" type="text/css" href="./storefronts/breakfast/css/host.css" />
		<link rel="stylesheet" type="text/css" href="./storefronts/breakfast/css/receipt.css" />
	</head>
	<body>
		<?php include './storefronts/shared/templates/header.php' ?>
		<div id="content-container">
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
						<iframe id="iframe" <?= implode(" ", $iframe_attributes) ?>></iframe>
					</div>
					<button id="external-submit-button" type="button">Pay $15.25</button>
					<div id="error-message" class="hidden"></div>
				</div>
				<div id="order-info">
					<h2>Your Pickup Order</h2>
					<ul id="order-item-list">
						<li>
							<img src="./storefronts/breakfast/images/avocado-toast.jpg" width="250" />
							<div>
								<h3>Avocado Toast</h3>
								<h4>+ Eggs</h4>
								<span class="total-price">$9.49</span>
							</div>
						</li>
						<li>
							<img src="./storefronts/breakfast/images/coffee.jpg" width="250" />
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
		</div>
		<?php include './storefronts/shared/templates/receipt-modal.php' ?>
		<?php include './storefronts/shared/templates/test-card-modal.php' ?>
		<script src="https://<?= MONETRA_HOST ?>:<?= MONETRA_PORT ?>/PaymentFrame/PaymentFrame.js"></script>
		<script src="./storefronts/shared/js/host.js"></script>
		<script src="./storefronts/breakfast/js/checkout.js"></script>
	</body>
</html>