<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>TranSafe PaymentFrame Demo</title>
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet"> 
		<link rel="stylesheet" type="text/css" href="./storefronts/shared/css/host.css" />
		<link rel="stylesheet" type="text/css" href="./storefronts/books/css/host.css" />
	</head>
	<body>
		<?php include './storefronts/shared/templates/header.php' ?>
		<div id="content-container">
			<header>
				<h1>Checkout</h1>
			</header>
			<main>
				<div id="payment-info">
					<h2>Payment Information</h2>
					<h3>Order Total: $63.87</h3>
					<div>
						<form id="customer-data-form" action="./books-confirm-order" method="POST">
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
							<input type="hidden" name="amount" value="63.87" />
							<input type="hidden" name="tax" value="3.90" />
						</form>
						<div id="iframe-container">
							<iframe id="iframe" <?= implode(" ", $iframe_attributes) ?>></iframe>
						</div>
					</div>
				</div>
				<button type="button" id="external-submit-button">Continue</button>
				<div id="error-message" class="hidden"></div>
			</main>
		</div>
		<?php include './storefronts/shared/templates/receipt-modal.php' ?>
		<?php include './storefronts/shared/templates/test-card-modal.php' ?>
		<script src="https://<?= MONETRA_HOST ?>:<?= MONETRA_PORT ?>/PaymentFrame/PaymentFrame.js"></script>
		<script src="./storefronts/shared/js/host.js"></script>
		<script src="./storefronts/books/js/checkout.js"></script>
	</body>
</html>