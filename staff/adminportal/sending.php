<?php
// Be sure to include the file you've just downloaded
require_once('AfricasTalkingGateway.php');
$sms = $message = "";
$contacts = array();
// Specify your authentication credentials
if(isset($_POST['send']))
  {
  $to = $_POST['to'];
   $sms = $_POST['sms'];

   $message    = $to."\n".$sms;
   $username   = "alvo";
   $apikey     = "bd0f1bc44d323903e5e67dc5e50e580509fe45c8b28622d477c102773955d1b6";

  include 'functions/actions.php';
  $obj = new DataOperations();
  $sql = "SELECT * FROM student";
  $res = mysqli_query($obj->con,$sql);
  while ($fetch_contacts = mysqli_fetch_array($res)) {
        $contacts[] = $fetch_contacts['fathersmobile'];
      //$recipients = $contacts.", ";

       
  }
  
  $recipients = implode(", ", $contacts);
// Specify the numbers that you want to send to in a comma-separated list
// Please ensure you include the country code (+254 for Kenya in this case)

 
// And of course we want our recipients to know what we really do


// Create a new instance of our awesome gateway class
$gateway    = new AfricasTalkingGateway($username, $apikey);

/*************************************************************************************
  NOTE: If connecting to the sandbox:

  1. Use "sandbox" as the username
  2. Use the apiKey generated from your sandbox application
     https://account.africastalking.com/apps/sandbox/settings/key
  3. Add the "sandbox" flag to the constructor

  $gateway  = new AfricasTalkingGateway($username, $apiKey, "sandbox");
**************************************************************************************/

// Any gateway error will be captured by our custom Exception class below, 
// so wrap the call in a try-catch block

try 
{ 
  // Thats it, hit send and we'll take care of the rest. 
  $results = $gateway->sendMessage($recipients, $message);
			
  foreach($results as $result) {
    // status is either "Success" or "error message"
    echo " Number: " .$result->number;
    echo " Status: " .$result->status;
    echo " StatusCode: " .$result->statusCode;
    echo " MessageId: " .$result->messageId;
    echo " Cost: "   .$result->cost."\n";
  }
}
catch ( AfricasTalkingGatewayException $e )
{
  echo "Encountered an error while sending: ".$e->getMessage();
}
}

// DONE!!! 
