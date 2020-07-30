<?php

// ------------ find phone number to send OTP ---------------------
$token = 'dDJwOnNlY3JldA==';
$id_card = '1234567890123';

$ch = curl_init();
curl_setopt_array($ch, array(
  CURLOPT_URL => "https://bgcdlpsapi.bigc.co.th/api/loyalty/findMember?idCard=" . $id_card,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HEADER => true,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
	"Authorization:" . $token,
    "Content-Type: application/json",
  ),
));

$response = curl_exec($ch);
// dpm($response);

// get httpcode 
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if($httpcode == 200){ // if response ok
	// separate header and body
	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	$header = substr($response, 0, $header_size);
	$body = substr($response, $header_size);
	
	// convert json to array or object
    $result = json_decode($body);
    $data = $result->data;
	
	// access to data
    $mobilePhone = $data->mobilePhone;
    dpm('mobilePhone:' . $mobilePhone);
}
// end session
curl_close($ch);
?>
