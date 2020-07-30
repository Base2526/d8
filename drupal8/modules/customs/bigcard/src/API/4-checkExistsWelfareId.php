<?php

// ------------ is exists welfare id  ---------------------
$token = 'dDJwOnNlY3JldA==';
$welfare_id = '1234567890123';


$ch = curl_init();
curl_setopt_array($ch, array(
  CURLOPT_URL => "https://bgcdlpsapi.bigc.co.th/api/loyalty/checkExistsWelfareId?welfareId=" . $welfare_id,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HEADER => true,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
	"Authorization:" . $token,
    "Content-Type: application/json",
  ),
));

// send the request and store the response to $data
$response = curl_exec($ch);

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
    $isExists = $data->isExists;
    dpm('isExistsWelfareId:' . $isExists);
}
// end session
curl_close($ch);
?>
