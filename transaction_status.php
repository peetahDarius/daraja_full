<?php
session_start();
    //  //Processing the Mpesa json response Data
    //  $mpesaResponse = file_get_contents('M_PESAConfirmationResponse.json');
    //  $callbackContent = json_decode($mpesaResponse);

    //  $Resultcode = $callbackContent->Body->stkCallback->ResultCode;
  /* access token */
    $consumerKey = 'nk16Y74eSbTaGQgc9WF8j6FigApqOMWr'; //Fill with your app Consumer Key
    $consumerSecret = '40fD1vRXCq90XFaU'; // Fill with your app Secret
    $headers = ['Content-Type:application/json; charset=utf8'];
    $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $curl = curl_init($access_token_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_HEADER, FALSE);
    curl_setopt($curl, CURLOPT_USERPWD, $consumerKey.':'.$consumerSecret);
    $result = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $result = json_decode($result);
    $access_token = $result->access_token;
    curl_close($curl);


  /* making the request */
    $tstatus_url = 'https://sandbox.safaricom.co.ke/mpesa/transactionstatus/v1/query';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $tstatus_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$access_token)); //setting custom header

    $curl_post_data = array(
    //Fill in the request parameters with valid values
    'Initiator' => 'testapi',
    'SecurityCredential' => 'a0mE7rTSguAAj+7qZ9bKiNqhgYFk3YLZKv/DRmq4S0vPV6ycJpK1kOZ5J6G6K2ESMiHF7i7EFj5gRIDooyFnNpGwJzMvP3ioNh0o635ctAn9JD3Szx6BjCkrOX3mkeJyi6JjlOIla/ObAOBCPbs5Rc+YCvpOGJy3X4QkMd7nncKOI7nNsinRy+sxyXOFusDKVaIM4eLNDhnY1tWRwjQ5vNRBwP9NhDA3G4Dy7yF/rR19EvaVPp60neFKbd1FqsY1pT/3aJi81JpmXjjN2qP90cHjtHW+KgKeJgE6pt583/IDETB+XNaSEVc2YyVte/nx2ToasDHxcwRWfd0fqQt1EA==',
    'CommandID' => 'TransactionStatusQuery',
    'TransactionID' => 'OEI2AK4Q16',
    'PartyA' => '600978', // shortcode 1
    'IdentifierType' => '2',
    'ResultURL' => 'https://ccf2-197-232-122-6.eu.ngrok.io/pesabox/status.php',
    'QueueTimeOutURL' => 'https://ccf2-197-232-122-6.eu.ngrok.io/pesabox/status.php',
    'Remarks' => 'test',
    'Occasion' => 'test'
    );

    $data_string = json_encode($curl_post_data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    $curl_response = curl_exec($curl);
    echo $curl_response;
?>
