<?php
// ------------ get my refer code---------------------
$token = 'dDJwOnNlY3JldA==';
$auth_token = 'eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJCQjE0MjEwODMwMTgiLCJtZW0iOiIxMTAxMjE0MjEwODMwMTg3IiwiYmdjIjoiNzcxMzExNDIwMDAxNzY5OSJ9.VhTSw_lFyH2B38LnEilkCEax78oAW9ysWSt9b6ko9bw';

$ch = curl_init();
curl_setopt_array($ch, array(
  CURLOPT_URL => "https://bgcdlpsapi.bigc.co.th/api/memberGetMember/myReferCode",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HEADER => true,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "Authorization:" . $token,
    "X-Auth-Token:" . $auth_token,
    "Accept: application/json",
    "Content-Type: application/json",
  ),
));

$response = curl_exec($ch);
//dpm($response);

// get httpcode 
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if($httpcode == 200){ // if response ok
	// separate header and body
	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	$header = substr($response, 0, $header_size);
	$body = substr($response, $header_size);
	
	// convert json to array or object
	$result = json_decode($body);
	dpm($result);
    // $data = $result->data;
	
	// access to data
    // $isExists = $data->isExists;
    // dpm('isExistsWelfareId:' . $isExists);
}
// end session
curl_close($ch);
?>
