<?php

// ------------ member get member info ---------------------
$token = 'dDJwOnNlY3JldA==';
// $auth_token = 'eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJCQjE0MjEwODMwMTgiLCJtZW0iOiIxMTAxMjE0MjEwODMwMTg3IiwiYmdjIjoiNzcxMzExNDIwMDAxNzY5OSJ9.VhTSw_lFyH2B38LnEilkCEax78oAW9ysWSt9b6ko9bw';
$rule_id = 'OR00000001298';
$effective_date = '';

$params = array();
if($rule_id != ''){
  array_push($params, 'ruleId=' . $rule_id);
}
if($effective_date != ''){ 
  array_push($params, 'effectiveDate=' . $effective_date);
}
$query_string = '?' . join('&', $params);
// dpm($query_string);

$ch = curl_init();
curl_setopt_array($ch, array(
  CURLOPT_URL => "https://bgcdlpsapi.bigc.co.th/api/loyalty/getSpecialOnlineRule" . $query_string,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HEADER => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "Authorization:" . $token,
    // "X-Auth-Token:" . $auth_token,
    "Accept: application/json",
    "Content-Type: application/json",
  ),
));

$response = curl_exec($ch);
// dpm($response);
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
