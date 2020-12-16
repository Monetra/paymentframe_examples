<?php

require('./backend_path.php');
require($backend_path . 'config.php');

/* Values that will be needed for generating the HMAC */
$host_domain = 'https://' . $_SERVER['HTTP_HOST'];
$monetra_username = $config['monetra_user'];
$monetra_password = $config['monetra_password'];

if (!empty($_GET['style'])) {
	$style_suffix = '-' . $_GET['style'];
} else {
	$style_suffix = '';
}

$hmac_fields = [];

/* "timestamp", "domain", "sequence", and "username" are the required HMAC fields. */

/* Current Unix timestamp */
$hmac_fields["timestamp"] = time();

/* Domain of the website that will host the iframe */
$hmac_fields["domain"] = $host_domain;

/* Merchant-specified alphanumeric value for tracking/verification purposes.
 * In production this should be dynamically generated.
 */
$hmac_fields["sequence"] = uniqid();

/* Username of the Monetra merchant user that will be used to request the iframe
 * and generate the ticket
 */
$hmac_fields["username"] = $monetra_username;

/* Optional field. This is the URL of the CSS file that will be used to style the iframe's contents. */
$hmac_fields["css-url"] = $host_domain . "/css/iframe$style_suffix.css";

if ($_GET['style'] == '') {

	$hmac_fields["include-cardholdername"] = "no";
	$hmac_fields["include-street"] = "no";
	$hmac_fields["include-zip"] = "no";
	$hmac_fields["expdate-format"] = "single-text";
	$font = "Roboto+Slab";

} elseif ($_GET['style'] === '2') {

	$hmac_fields["include-cardholdername"] = "no";
	$hmac_fields["include-street"] = "no";
	$hmac_fields["include-zip"] = "yes";
	$hmac_fields["expdate-format"] = "separate-selects";
	$font = "PT+Sans+Narrow:400,700";

} elseif ($_GET['style'] === '3') {

	$hmac_fields["include-cardholdername"] = "yes";
	$hmac_fields["include-street"] = "no";
	$hmac_fields["include-zip"] = "no";
	$hmac_fields["expdate-format"] = "single-text";
	$font = "Roboto:400,700";

}

$hmac_fields["auto-reload"] = "yes";
$hmac_fields["autocomplete"] = "no";

/* Concatenate all of the defined HMAC fields into a string with no delimiters */
$data_to_hash = implode("", $hmac_fields);

/* Generate the HMAC, using the Monetra merchant user's password as the key */
$hmac = hash_hmac('sha256', $data_to_hash, $monetra_password);

/* Assemble a string containing the "data-" attributes for the iframe element.
 * This will consist of the HMAC itself and all of the fields included in the HMAC.
 */
$iframe_attributes = [
	'data-hmac-hmacsha256="' . $hmac . '"'
];
foreach ($hmac_fields as $key => $value) {
	$iframe_attributes[] = 'data-hmac-' . $key . '="' . $value . '"';
}
$iframe_attribute_string = implode(" ", $iframe_attributes);

$iframe_name = 'iframe-' . uniqid();

header('Cache-Control: private, no-store, max-age=0, no-cache, must-revalidate, post-check=0, pre-check=0');

/* Render the payment page HTML. */
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>TranSafe PaymentFrame Demo</title>
		<link href="https://fonts.googleapis.com/css?family=<?php echo $font; ?>&display=swap" rel="stylesheet"> 
		<link rel="stylesheet" type="text/css" href="./css/standard.css" />
		<link rel="stylesheet" type="text/css" href="./css/host<?php echo $style_suffix; ?>.css" />
		<link rel="stylesheet" type="text/css" href="./css/receipt<?php echo $style_suffix; ?>.css" />
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
		<?php include $backend_path . 'include/layout' . $style_suffix . '.php'; ?>
		<div id="receipt-modal-container" class="modal-container hidden">
			<div class="modal-overlay"></div>
			<div id="receipt-modal" class="modal">
				<h2 id="order-completion-status">Order Complete</h2>
				<p>Your receipt is below.</p>
				<div id="receipt-container"></div>
			</div>
		</div>
		<div id="info-modal-container" class="modal-container hidden">
			<div class="modal-overlay"></div>
			<div id="info-modal" class="modal">
				<h3>Test Card Information</h3>
				<p>
					You can use the credit card details below to complete this test transaction.
				</p>
				<ul class="test-card-list">
					<li>
						<img alt="Visa" src="./images/card-logos/visa.svg" />
						<div>
							<span class="test-card-number">4111 1111 1111 1111</span>
							<span class="test-card-details">
								<span><b>CV:</b> 999</span>
								<span><b>ZIP:</b> 32606</span>
							</span>
						</div>
					</li>
					<li>
						<img alt="Mastercard" src="./images/card-logos/mc.svg" />
						<div>
							<span class="test-card-number">5454 5454 5454 5454</span>
							<span class="test-card-details">
								<span><b>CV:</b> 999</span>
								<span><b>ZIP:</b> 32606</span>
							</span>
						</div>
					</li>
					<li>
						<img alt="Discover" src="./images/card-logos/disc.svg" />
						<div>
							<span class="test-card-number">6011 0009 9550 0000</span>
							<span class="test-card-details">
								<span><b>CV:</b> 999</span>
								<span><b>ZIP:</b> 32606</span>
							</span>
						</div>
					</li>
					<li>
						<img alt="American Express" src="./images/card-logos/amex.svg" />
						<div>
							<span class="test-card-number">3714 496353 98431</span>
							<span class="test-card-details">
								<span><b>CV:</b> 1234</span>
								<span><b>ZIP:</b> 32606</span>
							</span>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<script src="https://<?php echo $config['monetra_host'] . ':' . $config['monetra_port']; ?>/PaymentFrame/PaymentFrame.js"></script>
		<script src="./js/host.js"></script>
	</body>
</html>
