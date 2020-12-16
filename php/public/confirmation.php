<?php

require('./backend_path.php');
require($backend_path . 'libmonetra.php');
require($backend_path . 'config.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	header("Location: https://" . $_SERVER['HTTP_HOST']);
	exit();
}

M_InitEngine(NULL);
$conn = M_InitConn();

M_SetSSL($conn, $config['monetra_host'], $config['monetra_port']);

M_SetBlocking($conn, 1);
M_SetTimeout($conn, 30);

M_Connect($conn);

$identifier = M_TransNew($conn);

M_TransKeyVal($conn, $identifier, "username", $config['monetra_user']);
M_TransKeyVal($conn, $identifier, "password", $config['monetra_password']);
M_TransKeyVal($conn, $identifier, "action", "cardtype");
M_TransKeyVal($conn, $identifier, "cardshieldticket", $_POST['ticket']);

M_TransSend($conn, $identifier);

$response = [];
$response_keys = M_ResponseKeys($conn, $identifier);
foreach ($response_keys as $key) {
	$response[$key] = M_ResponseParam($conn, $identifier, $key);
}

M_DeleteTrans($conn, $identifier);
M_DestroyConn($conn);
M_DestroyEngine();

?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>TranSafe PaymentFrame Demo</title>
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet"> 
		<link rel="stylesheet" type="text/css" href="./css/standard.css" />
		<link rel="stylesheet" type="text/css" href="./css/host-3.css" />
		<link rel="stylesheet" type="text/css" href="./css/receipt-3.css" />
		<link rel="stylesheet" type="text/css" href="./css/confirmation.css" />
	</head>
	<body>
		<div id="style-switch">
			<span>TranSafe PaymentFrame Demo</span>
			<button id="info-modal-open">Test Card Information</button>
			<div id="style-links">
				<a href="./">Example 1</a>
				<a href="./?style=2">Example 2</a>
				<a href="./?style=3">Example 3</a>
			</div>
		</div>
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
				<div id="confirmation-info">
					<h2>Payment Summary</h2>
					<p>
						<?php echo $_POST['name']; ?>
						<br />
						<?php echo $_POST['email']; ?>
						<br />
						<?php echo $_POST['street']; ?>
						<br />
						<?php echo $_POST['city'] . ', ' . $_POST['state'] . ' ' . $_POST['zip']; ?>
						<br />
						<div class="card-confirmation">
							<?php 
								if (in_array($response['cardtype'], ['MC', 'VISA', 'AMEX', 'DISC'])) {
									echo '<img class="confirmation-card-icon" alt="' . $response['cardtype'] . '" src="./images/card-logos/' . strtolower($response['cardtype']) . '.svg" />';
								}
								echo 'ending in ' . str_replace('X', '', $response['account']); 
							?>
						</div>
					</p>
				</div>
			</div>
			<form id="complete-order-form">
				<?php foreach ($_POST as $key => $value) {?>
					<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>" />
				<?php } ?>
				<button type="button" id="complete-order-button">
					Complete Order
				</button>
			</form>
		</main>
		<div id="receipt-modal-container" class="modal-container hidden">
			<div class="modal-overlay"></div>
			<div id="receipt-modal" class="modal">
				<h2 id="order-completion-status">Order Complete</h2>
				<p>Your receipt is below.</p>
				<div id="receipt-container"></div>
			</div>
		</div>
		<script src="./js/host.js"></script>
	</body>
</html>
