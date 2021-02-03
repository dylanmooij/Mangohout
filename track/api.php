<?php


$retailer_id = '5fc93fbf86fd700b13a50ed5';
$token = login();

function login() {
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.damiso.nl/auth/login",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => "email=test@bramboos.nl&password=hoi123456",
	  CURLOPT_HTTPHEADER => array(
	    "Accept: application/json",
	    "Content-Type: application/x-www-form-urlencoded"
	  ),
	));

	$response = curl_exec($curl);
	curl_close($curl);

	return json_decode($response)->access_token;

}

function getOrderItems($order_id) {
	global $token;
	global $retailer_id;
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://api.damiso.nl/order-items?order_id='.$order_id,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_HTTPHEADER => array(
	    "Accept: application/json, text/plain, */*",
	    "Content-Type: application/json;charset=UTF-8",
	    "Authorization: Bearer ".$token,
	    "Cookie: auth.strategy=local; auth.redirect=%2Forders%2Fupload"
	  ),
	));
	$response = curl_exec($curl);
	$response = json_decode($response);

	curl_close($curl);
	
	return $response;
}

function getOrder($ordernumber, $email) {
	global $token;
	global $retailer_id;
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://api.damiso.nl/orders?retailer.id='.$retailer_id.'&customer_email='.$email.'&reference=MHO'.intval($ordernumber),
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_HTTPHEADER => array(
	    "Accept: application/json, text/plain, */*",
	    "Content-Type: application/json;charset=UTF-8",
	    "Authorization: Bearer ".$token,
	    "Cookie: auth.strategy=local; auth.redirect=%2Forders%2Fupload"
	  ),
	));

	$response = json_decode(curl_exec($curl));

	curl_close($curl);
	
	return $response[0];
}

function getPurchase($purchase_id) {
	global $token;
	global $retailer_id;
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://api.damiso.nl/purchases/'.$purchase_id,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_HTTPHEADER => array(
	    "Accept: application/json, text/plain, */*",
	    "Content-Type: application/json;charset=UTF-8",
	    "Authorization: Bearer ".$token,
	    "Cookie: auth.strategy=local; auth.redirect=%2Forders%2Fupload"
	  ),
	));

	$response = json_decode(curl_exec($curl));

	curl_close($curl);
	
	return $response;
}

function createDelivery($order, $items) {
	global $token;
	$curl = curl_init();

	$postdata = (object) array(
		'order_id' => $order->id,
		'payment_amount' => $order->outstanding_cod,
		'last_contact' => 'dmi_partial'
	);

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://api.damiso.nl/deliveries',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => json_encode($postdata),
	  CURLOPT_HTTPHEADER => array(
	    "Accept: application/json, text/plain, */*",
	    "Content-Type: application/json;charset=UTF-8",
	    "Authorization: Bearer ".$token,
	    "Cookie: auth.strategy=local; auth.redirect=%2Forders%2Fupload"
	  ),
	));

	$response = json_decode(curl_exec($curl));

	curl_close($curl);
	deleteItemsExcept($response->id, $items);
	return $response;
}

function deleteItemsExcept($delivery_id, $items) {
	global $token;
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://api.damiso.nl/order-items?delivery_id='.$delivery_id,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_HTTPHEADER => array(
	    "Accept: application/json, text/plain, */*",
	    "Content-Type: application/json;charset=UTF-8",
	    "Authorization: Bearer ".$token,
	    "Cookie: auth.strategy=local; auth.redirect=%2Forders%2Fupload"
	  ),
	));

	$response = json_decode(curl_exec($curl));

	curl_close($curl);
	foreach ($response as $item) {
		if(!in_array($item->id, $items)) {
			detachDeliveryFromItem($item->id);
		}
	}
	return $response;
}

function detachDeliveryFromItem($item_id) {
	global $token;
	$curl = curl_init();

	$postdata = (object) array(
		'delivery_id' => null,
		'id' => $item_id
	);

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://api.damiso.nl/order-items/'.$item_id,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "PATCH",
	  CURLOPT_POSTFIELDS => json_encode($postdata),
	  CURLOPT_HTTPHEADER => array(
	    "Accept: application/json, text/plain, */*",
	    "Content-Type: application/json;charset=UTF-8",
	    "Authorization: Bearer ".$token,
	    "Cookie: auth.strategy=local; auth.redirect=%2Forders%2Fupload"
	  ),
	));

	$response = json_decode(curl_exec($curl));

	curl_close($curl);
	return $response;
}