<?php

define("MONETRA_HOST",          "test.transafe.com");
define("MONETRA_PORT",          "8665");
define('MONETRA_APIKEY_ID',     "P004EB1C1832603A3");
define('MONETRA_APIKEY_SECRET', "NrdJ0flyV89pcoSueUt/QGsoAo1hY1xE6xDO6/h7aWY=");
define('HOST_DOMAIN',           "https://" . $_SERVER['HTTP_HOST']);
define('HOST_BASE_PATH',        "/");

handleRequest("GET", "clothes", function() {

	$hmac_fields = [
		"hmac-timestamp"              => time(),
		"hmac-domain"                 => HOST_DOMAIN,
		"hmac-sequence"               => uniqid(),
		"hmac-auth_apikey_id"         => MONETRA_APIKEY_ID,
		"hmac-css-url"                => HOST_DOMAIN . HOST_BASE_PATH . 'storefronts/clothes/css/iframe.css',
		"hmac-include-cardholdername" => "no",
		"hmac-include-street"         => "no",
		"hmac-include-zip"            => "no",
		"hmac-expdate-format"         => "single-text",
		"hmac-auto-reload"            => "yes",
		"hmac-autocomplete"           => "no"
	];
	$hmac = generateHmac($hmac_fields);
	$iframe_attributes = assembleIframeAttributes($hmac, $hmac_fields);

	include 'storefronts/clothes/templates/checkout.php';

});

handleRequest("GET", "breakfast", function() {

	$hmac_fields = [
		"hmac-timestamp"              => time(),
		"hmac-domain"                 => HOST_DOMAIN,
		"hmac-sequence"               => uniqid(),
		"hmac-auth_apikey_id"         => MONETRA_APIKEY_ID,
		"hmac-css-url"                => HOST_DOMAIN . HOST_BASE_PATH . 'storefronts/breakfast/css/iframe.css',
		"hmac-include-cardholdername" => "no",
		"hmac-include-street"         => "no",
		"hmac-include-zip"            => "yes",
		"hmac-expdate-format"         => "separate-selects",
		"hmac-auto-reload"            => "yes",
		"hmac-autocomplete"           => "no",
		"hmac-include-submit-button"  => "no"
	];
	$hmac = generateHmac($hmac_fields);
	$iframe_attributes = assembleIframeAttributes($hmac, $hmac_fields);

	include 'storefronts/breakfast/templates/checkout.php';

});

handleRequest("GET", "books", function() {

	$hmac_fields = [
		"hmac-timestamp"              => time(),
		"hmac-domain"                 => HOST_DOMAIN,
		"hmac-sequence"               => uniqid(),
		"hmac-auth_apikey_id"         => MONETRA_APIKEY_ID,
		"hmac-css-url"                => HOST_DOMAIN . HOST_BASE_PATH . 'storefronts/books/css/iframe.css',
		"hmac-include-cardholdername" => "no",
		"hmac-include-street"         => "no",
		"hmac-include-zip"            => "no",
		"hmac-expdate-format"         => "single-text",
		"hmac-include-submit-button"  => "no"
	];

	$hmac = generateHmac($hmac_fields);
	$iframe_attributes = assembleIframeAttributes($hmac, $hmac_fields);

	include 'storefronts/books/templates/checkout.php';

});

handleRequest("POST", "books-confirm-order", function() {

	$url = 'https://' . MONETRA_HOST . ':' . MONETRA_PORT . '/api/v1/transaction/cardtype';

	$cardtype_request_data = [
		'account_data' => [
			'cardshieldticket' => $_POST['ticket']
		]
	];

	$cardtype_response_data = sendRequestToPaymentServer($url, $cardtype_request_data);
	$cardtype_response = json_decode($cardtype_response_data, true);

	include 'storefronts/books/templates/confirm-order.php';

});

handleRequest("POST", "pay", function() {

	$url = 'https://' . MONETRA_HOST . ':' . MONETRA_PORT . '/api/v1/transaction/purchase';
	
	$transaction_data = [
		'account_data' => [
			'cardshieldticket' => $_POST['ticket'],
			'cardholdername'   => $_POST['cardholdername']
		],
		'money' => [
			'amount' => $_POST['amount'],
			'tax'    => $_POST['tax']
		],
		'order' => [
			'ordernum' => strval(time())
		],
		'rcpt' => 'type=html'
	];

	if (isset($_POST['street'])) {
		$transaction_data['verification']['street'] = $_POST['street'];
	}
	if (isset($_POST['zip'])) {
		$transaction_data['verification']['zip'] = $_POST['zip'];
	}

	$response_data = sendRequestToPaymentServer($url, $transaction_data);

	echo $response_data;

});

handleRequest("GET", "", function() {
	header("Location: " . HOST_DOMAIN . HOST_BASE_PATH . "clothes");
});

function handleRequest($request_method, $request_uri, $callback) {
	if ($request_method === $_SERVER['REQUEST_METHOD']
	&& HOST_BASE_PATH . $request_uri === $_SERVER['REQUEST_URI']) {
		$callback();
	}
}

function generateHmac($hmac_fields) {

	$data_to_hash = implode("", $hmac_fields);

	$hmac = hash_hmac('sha256', $data_to_hash, MONETRA_APIKEY_SECRET);

	return $hmac;
}

function assembleIframeAttributes($hmac, $hmac_fields) {
	$iframe_attributes = [
		'name="iframe-' . uniqid() . '"',
		'data-hmac-hmacsha256="' . $hmac . '"'
	];
	foreach ($hmac_fields as $key => $value) {
		$iframe_attributes[] = 'data-' . $key . '="' . $value . '"';
	}
	return $iframe_attributes;
}

function sendRequestToPaymentServer($url, $request_data) {

	$request_body = json_encode($request_data);

	$headers = [
		"X-API-KEY-ID: " . MONETRA_APIKEY_ID,
		"X-API-KEY: " . MONETRA_APIKEY_SECRET,
		"Content-Type: application/json",
		"Content-Length: " . strlen($request_body)
	];

	$curl = curl_init();

	curl_setopt($curl, \CURLOPT_URL, $url);
	curl_setopt($curl, \CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($curl, \CURLOPT_HTTPAUTH, \CURLAUTH_BASIC);
	curl_setopt($curl, \CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, \CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, \CURLOPT_POSTFIELDS, $request_body);

	$response = curl_exec($curl);

	curl_close($curl);

	return $response;

}
