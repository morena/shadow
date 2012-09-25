<?php
require APPLICATION_PATH . '/../library/twilio/Services/Twilio.php';

class Application_Model_Twilio
{
    /**
     * private string
     */
    private $_accountSid = 'ACc8c33b4c5a624fd56df1edeff6fe6291';

    /**
     * private string
     */
    private $_authToken = 'a80bfe520cbdfca0583b7e706ec80178';

    /**
     * private string
     */
    private $_twilioNumber = '+442033221553';

    /**
     * private string
     */
    private $_myNumber = '+447789866809';


    public function sendText($msg, $to = null, $from = null) {
        $sent = array();

        if (isset($to))
            $myNumber = $_REQUEST['number'];
        else
            $myNumber = $this->_myNumber;

        if (isset($from))
            $name = $from;
        else
            $name = "Morena @mfujica";

        // Step 3: instantiate a new Twilio Rest Client
        $client = new Services_Twilio($this->_accountSid, $this->_authToken);

        // Step 4: make an array of people we know, to send them a message.
        // Feel free to change/add your own phone number and name here.
        /* $people = array(
          "+14158675309" => "Curious George",
          "+14158675310" => "Boots",
          "+14158675311" => "Virgil",
          ); */

        $people = array(
            $myNumber => $name,
        );

        $msg = html_entity_decode($msg);

        // Step 5: Loop over all our friends. $number is a phone number above, and
        // $name is the name next to it
        foreach ($people as $number => $name) {

            $sms = $client->account->sms_messages->create(
                    // Step 6: Change the 'From' number below to be a valid Twilio number
                    // that you've purchased, or the (deprecated) Sandbox number
                    $this->_twilioNumber,
                    // the number we are sending to - Any phone number
                    $number,
                    // the sms body
                    $msg
            );

            // Display a confirmation message on the screen
            // ID i.e. SM33bd3680b76d881f2e01f0b8f412ef2e
            $return[$sms->sid]['msg'] = "SMS ID '.$sms->sid.' sent to $name";
            $return[$sms->sid]['status'] = $sms->status;


            /*// Create our Application instance (replace this with your appId and secret).
            $facebook = new Facebook(array(
                        'appId' => '517986281561274',
                        'secret' => 'be0e27d516de783fc8dd6cd5b213f3b2',
                    ));
            $facebook->api('/me/feed', 'POST', array(
                'link' => 'http://www.morenafiore.com/shadow',
                'message' => 'I just sent an SMS with my latest Facebook whereabouts, Subscribe to my updates and receive SMS '
            ));*/
        }

        return $return;
    }

    public function checkSmsStatus($smsId)
    {
        $client = new Services_Twilio($this->_accountSid, $this->_authToken);
        /*$message = $client->listResource->sms_messages->get($smsId);

        echo'<pre>';
        var_dump($message);
        echo '</pre>';*/
    }

    public function makeCall($msg) {
        // Step 2: set our AccountSid and AuthToken from www.twilio.com/user/account
        $AccountSid = "ACc8c33b4c5a624fd56df1edeff6fe6291";
        $AuthToken = "a80bfe520cbdfca0583b7e706ec80178";
        $twilioNumber = '+442033221553';

        if (isset($_REQUEST['number']))
            $myNumber = $_REQUEST['number'];
        else
            $myNumber = '+447789866809';
        if (isset($_REQUEST['name']))
            $name = $_REQUEST['name'];
        else
            $name = "Morena @mfujica";

        // Include the Twilio PHP library
        require 'twilio/Services/Twilio.php';

        // Twilio REST API version
        $version = "2010-04-01";

        // Instantiate a new Twilio Rest Client
        $client = new Services_Twilio($AccountSid, $AuthToken, $version);

        try {
            // Initiate a new outbound call
            $call = $client->account->calls->create(
                    $twilioNumber, // The number of the phone initiating the call
                    $myNumber, // The number of the phone receiving call
                    'http://www.morenafiore.com/shadow/response.php?call=true&msg=' . urlencode($msg), // The URL Twilio will request when the call is answered
                    array(
                'StatusMethod' => 'GET'
                    )
            );
            echo 'Started call for : ' . $name . ' ID:' . $call->sid;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }



}

