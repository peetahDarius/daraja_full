<?php
if(isset($_POST['submit'])){
  session_start();

  $userid = $_SESSION["userid"];

  date_default_timezone_set('Africa/Nairobi');

  # access token
  $consumerKey = '1IQ5YCIk1EKUwHHSdt1kGGQ1kAG7j8Av'; //Fill with your app Consumer Key
  $consumerSecret = '2f4uptrxdyG0Lpmm'; // Fill with your app Secret

  # define the variales
  # provide the following details, this part is found on your test credentials on the developer account
  $BusinessShortCode = '174379';
  $Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';  
  
  /*
    This are your info, for
    $PartyA should be the ACTUAL clients phone number or your phone number, format 2547********
    $AccountRefference, it maybe invoice number, account number etc on production systems, but for test just put anything
    TransactionDesc can be anything, probably a better description of or the transaction
    $Amount this is the total invoiced amount, Any amount here will be 
    actually deducted from a clients side/your test phone number once the PIN has been entered to authorize the transaction. 
    for developer/test accounts, this money will be reversed automatically by midnight.
  */
  
  $PartyA = $_POST['phone']; // This is your phone number, 
  $AccountReference = 'Brooksys Tech';
  $TransactionDesc = 'Test Payment';
  $Amount = $_POST['amount'];
 
  include('./includes/conn.inc.php');
  $sql = "INSERT INTO tempdata(uid)VALUE('$userid')";
  $result = $conn->query($sql);
  # Get the timestamp, format YYYYmmddhms -> 20181004151020
  $Timestamp = date('YmdHis');    
  
  # Get the base64 encoded string -> $password. The passkey is the M-PESA Public Key
  $Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp);

  # header for access token
  $headers = ['Content-Type:application/json; charset=utf8'];

    # M-PESA endpoint urls
  $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
  $initiate_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

  # callback url
  $CallBackURL = 'https://desolate-lowlands-54682.herokuapp.com/callback_url.php';  

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

  # header for stk push
  $stkheader = ['Content-Type:application/json','Authorization:Bearer '.$access_token];

  # initiating the transaction
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $initiate_url);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader); //setting custom header

  $curl_post_data = array(
    //Fill in the request parameters with valid values
    'BusinessShortCode' => $BusinessShortCode,
    'Password' => $Password,
    'Timestamp' => $Timestamp,
    'TransactionType' => 'CustomerPayBillOnline',
    'Amount' => $Amount,
    'PartyA' => $PartyA,
    'PartyB' => $BusinessShortCode,
    'PhoneNumber' => $PartyA,
    'CallBackURL' => $CallBackURL,
    'AccountReference' => $AccountReference,
    'TransactionDesc' => $TransactionDesc
  );

  $data_string = json_encode($curl_post_data);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
  $curl_response = curl_exec($curl);
  //echo $curl_response;




};
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transaction status</title>
</head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.28/dist/sweetalert2.all.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<style>
  body{

background-color: #eee;

}

.card{


background-color:#fff;
border:none;
}

.card-1{

width: 400px;
}
.info{

font-size:15px;
color:#000;
}
.one{

font-size:12px;
color: #EDE7F6;
}

.card-2{
border-radius: 10px;
}
</style>
<body>
<div class="container d-flex justify-content-center mt-5">

        <div class="card p-3 card-1">


            <div class="info d-flex justify-content-between align-items-center">

                <div class="group d-flex flex-column">

                    <span class="font-weight-bold">Transaction  Information</span>
                    
                </div>

                <i class="fa fa-bell-o"></i>
                
            </div>


            <div class="card bg-success p-2 mt-3 card-2 px-4 text-white py-4">

                <div class="info d-flex justify-content-between align-items-center">

                <div class="group d-flex flex-column text-white">

                    <span class="font-weight-bold">Trans Status</span>
                    <small id="mydata"> </small>
                    
                </div>

                <i class="fa fa-angle-right text-white"></i>
                
            </div>
                
            </div>


             <div class="info d-flex justify-content-between mt-3">

                <div class="group d-flex flex-column">

                    <span class="font-weight-bold">Transaction ID</span>
                    <small id="trans" style="color:green!important;"></small>
                    
                </div>

                <small>12:00 PM</small>
                
               </div>
        </div>
        
    </div>
    <script>
    
       function poll(){
        $.ajax({
           type: "GET",
           url:"data_fetch.php",
           dataType:"html",
           success: function(data){
             //$("#mydata").html(data);
             if(data = 0 ){
              document.querySelector(`#mydata`).textContent = `Transaction successful`;

              Swal.fire({
                  icon: 'success',
                  title: 'Success.',
                  text: response
                });

             }else{
              Swal.fire({
                  icon: 'success',
                  title: 'Success.',
                  text: response
                });
              document.querySelector(`#mydata`).textContent = `Transaction Processing`;
             }
           }
        });
      };

      var intervalId = window.setInterval(function(){
 
     poll(); //call the function
   }, 2000);

   function transactionID(){
        $.ajax({
           type: "GET",
           url:"trans_fetch.php",
           dataType:"html",
           success: function(data){
             $("#trans").html(data);
           }
        });
      };

      var intervalId = window.setInterval(function(){
 
     transactionID(); //call the function
   }, 2000);
     


    </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  
</body>
</html>