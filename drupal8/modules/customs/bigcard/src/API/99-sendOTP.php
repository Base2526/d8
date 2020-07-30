<?php

// ------------ send OTP message ---------------------
$password = 'H5ywPz12'; // Thailand(192)
// $password = 'FH3mzpao'; // all countries(190)

$transaction_id = DATE('YmdHis') . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
$project_id = '192'; // Thailand(192) / all countries(190)
$sender = 'jieb'; // sender name
$phone_number = '66954861000'; // phone number
$message = 'hello world'; // text SMS

$encode_message = urlencode($message);
$session = MD5('transaction_id=' . $transaction_id . '&project_id=' . $project_id . '&sender=' . $sender . '&msisdn=' . $phone_number . '&msg=' . $encode_message . '&pwd=' . $password);

$data_obj = [
  "transaction_id" => $transaction_id,
  "project_id" => $project_id, 
  "sender" => $sender, 
  "msisdn" => $phone_number, 
  "msg" => $message,
  "session" => $session
];

$ch = curl_init();
curl_setopt_array($ch, array(
  CURLOPT_URL => "https://ppro-smsapi.eggdigital.com/sms-api/sms_single", // test(not working !!!)
  // CURLOPT_URL => "https://smsapi.eggdigital.com/sms-api/sms_single", // production(work!!!)
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HEADER => true,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode($data_obj),
  CURLOPT_HTTPHEADER => array(
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
