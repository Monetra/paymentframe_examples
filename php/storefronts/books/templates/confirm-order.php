<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>TranSafe PaymentFrame Demo</title>
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet"> 
		<link rel="stylesheet" type="text/css" href="./storefronts/shared/css/host.css" />
		<link rel="stylesheet" type="text/css" href="./storefronts/books/css/host.css" />
		<link rel="stylesheet" type="text/css" href="./storefronts/books/css/receipt.css" />
	</head>
	<body>
		<?php include './storefronts/shared/templates/header.php' ?>
		<div id="content-container">
			<header>
				<h1>Confirm and Complete Order</h1>
			</header>
			<main>
				<div id="summary">
					<div id="order-info">
						<h2>Order Summary</h2>
						<table id="order-item-table">
							<thead>
								<tr>
									<th>Title</th>
									<th>Author</th>
									<th class="price-column">Price</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>The War of the Worlds</td>
									<td>H.G. Wells</td>
									<td class="price-column">$29.99</td>
								</tr>
								<tr>
									<td>Frankenstein</td>
									<td>Mary Shelley</td>
									<td class="price-column">$19.99</td>
								</tr>
								<tr>
									<td>The Count of Monte Cristo</td>
									<td>Alexandre Dumas</td>
									<td class="price-column"><span>Special!</span>$9.99</td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="3">
										<span>Subtotal: 59.97</span>
										<span>Tax: 3.90</span>
										<span id="order-total-amount">Total: $63.87</span>
									</th>
								</tr>
							</tfoot>
						</table>
					</div>
					<div id="confirmation-info">
						<h2>Payment Summary</h2>
						<p class="payment-summary">
							<span class="summary-label">Name</span>
							<span class="summary-value"><?= $_POST['cardholdername'] ?></span>

							<span class="summary-label">Email</span>
							<span class="summary-value"><?= $_POST['email'] ?></span>

							<span class="summary-label">Address</span>
							<span class="summary-value">
								<?= $_POST['street'] ?>
								<br />
								<?= $_POST['city'] . ', ' . $_POST['state'] . ' ' . $_POST['zip'] ?>
							</span>

							<span class="summary-label">Card Information</span>
							<div class="card-confirmation">
								<?php 
									if (in_array($cardtype_response['cardtype'], ['MC', 'VISA', 'AMEX', 'DISC'])) {
										echo '<img class="confirmation-card-icon" alt="' . $cardtype_response['cardtype'] . '" src="./storefronts/shared/images/' . strtolower($cardtype_response['cardtype']) . '.svg" />';
									}
									echo 'ending in ' . str_replace('X', '', $cardtype_response['account']); 
								?>
							</div>
						</p>
					</div>
				</div>
				<form id="complete-order-form">
					<?php foreach ($_POST as $key => $value) {?>
						<input type="hidden" name="<?= $key ?>" value="<?= $value ?>" />
					<?php } ?>
					<button type="button" id="complete-order-button">
						Complete Order
					</button>
				</form>
			</main>
		</div>
		<?php include './storefronts/shared/templates/receipt-modal.php' ?>
		<?php include './storefronts/shared/templates/test-card-modal.php' ?>
		<script src="https://<?= MONETRA_HOST ?>:<?= MONETRA_PORT ?>/PaymentFrame/PaymentFrame.js"></script>
		<script src="./storefronts/shared/js/host.js"></script>
		<script src="./storefronts/books/js/confirm-order.js"></script>
	</body>
</html>