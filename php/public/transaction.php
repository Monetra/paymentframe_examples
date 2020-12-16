<?php

require('./backend_path.php');
require($backend_path . 'libmonetra.php');
require($backend_path . 'config.php');

M_InitEngine(NULL);
$conn = M_InitConn();

M_SetSSL($conn, $config['monetra_host'], $config['monetra_port']);

M_SetBlocking($conn, 1);
M_SetTimeout($conn, 30);

M_Connect($conn);

$identifier = M_TransNew($conn);

M_TransKeyVal($conn, $identifier, "username", $config['monetra_user']);
M_TransKeyVal($conn, $identifier, "password", $config['monetra_password']);
M_TransKeyVal($conn, $identifier, "action", "sale");
M_TransKeyVal($conn, $identifier, "amount", $_POST['amount']);
if (isset($_POST['tax'])) {
	M_TransKeyVal($conn, $identifier, "tax", $_POST['tax']);
}
M_TransKeyVal($conn, $identifier, "cardshieldticket", $_POST['ticket']);
if (isset($_POST['street'])) {
	M_TransKeyVal($conn, $identifier, "street", $_POST['street']);
}
if (isset($_POST['zip'])) {
	M_TransKeyVal($conn, $identifier, "zip", $_POST['zip']);
}
M_TransKeyVal($conn, $identifier, "cardholdername", $_POST['cardholdername']);
M_TransKeyVal($conn, $identifier, "ordernum", time());
M_TransKeyVal($conn, $identifier, "rcpt", "type=html");

M_TransSend($conn, $identifier);

$response = [];
$response_keys = M_ResponseKeys($conn, $identifier);
foreach ($response_keys as $key) {
	$response[$key] = M_ResponseParam($conn, $identifier, $key);
}

M_DeleteTrans($conn, $identifier);
M_DestroyConn($conn);
M_DestroyEngine();

echo json_encode($response);
