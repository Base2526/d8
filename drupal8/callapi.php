<?php

    // $curl = curl_init('http://localhost/rest/api/post');
    // curl_setopt($curl, CURLOPT_POST, true);
    // curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array('1'=>'A')));
    // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // $response = curl_exec($curl);
    // curl_close($curl);
    // var_dump($response);

    $url = 'http://localhost/rest/api/post';
    // $data = array('key1' => 'value1', 'key2' => 'value2');

   # Our new data
    $data = array(
        'election' => 1,
        'name' => 'Test'
    );

    $headers = array(
        "Content-type: application/json",
        // "Content-length: " . strlen($xml),
        // "X-CSRF-Token: NE_oyxy4iqJQ7oIPKWlR_VRdA9TDv3uL4Yhpq7MuO_w",
    );

    # Create a connection
    // $url = '/api/update';
    $ch = curl_init($url);
    # Form data string
    $postString = http_build_query($data, '', '&');
    # Setting our options
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    # Get the response
    $response = curl_exec($ch);
    curl_close($ch);

    var_dump($response);
?>