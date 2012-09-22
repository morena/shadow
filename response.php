<?php

if(isset($_REQUEST['call']) && isset($_REQUEST['msg']))
{

$msg = $_REQUEST['msg'];


}else{
    $msg = "I'm still working on it";
}


require "twilio/Services/Twilio/Twiml.php";

$response = new Services_Twilio_Twiml;
$response->say($msg);
print $response;