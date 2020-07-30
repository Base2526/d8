<?php

// ------------ register ---------------------
$token = 'dDJwOnNlY3JldA==';
$data_obj = [
  "bigcard" => "",
  "mobilePhone" => "0891234567",
  "idCard" => "1234567890123",
  "idCardRef" => "JC2-01234567-89",
  "idCardType" => 1,
  "email" => "rakdee@gmail.com",
  "title" => "9",
  "tName" => "รักดี",
  "tLastName" => "ซื่อสัตย์",
  "eName" => "Rakdee",
  "eLastName" => "Suesud",
  "gender" => "M",
  "birthDate" => "1986-07-12",
  "nationality" => 1,
  "addType" => 1,
  "address1" => "140/7",
  "address2" => "",
  "subDistrict" => "240101",
  "district" => "2401",
  "province" => "CCO",
  "postalCode" => "137",
  "country" => "TH",
  "contactPermission" => 1,
  "contactPreference" => 7,
  "occupation" => "IT",
  "password" => "thisissecret",
  "consentStatus" => "C",
  "isVerifyDopa" => "N",
  "language" => "TH" // not in manual doc
];

$ch = curl_init();
curl_setopt_array($ch, array(
  CURLOPT_URL => "https://bgcdlpsapi.bigc.co.th/api/loyalty/register",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HEADER => true,
  //CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode($data_obj),
  CURLOPT_HTTPHEADER => array(
    "Authorization:" . $token,
    "Accept: application/json",
    "Content-Type: application/json",
  ),
));

$response = curl_exec($ch);
// dpm($response);

$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
// dpm($httpcode);

// get httpcode 
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
