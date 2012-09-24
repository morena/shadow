<?php
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */
require 'fbsdk/src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '517986281561274',
  'secret' => 'be0e27d516de783fc8dd6cd5b213f3b2',
));



// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('me?fields=first_name,last_name,location,statuses.limit(10),photos.limit(10),checkins.limit(10)');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
    $params = array('scope' => array('user_activities','user_location', 'user_photos', 'user_status', 'publish_actions'));
    $this->view->url = $facebook->getLoginUrl($params);

}

// This call will always work since we are fetching public data.
$naitik = $facebook->api('/naitik');


//die(var_dump($_REQUEST));
//Twilio
if(isset($_REQUEST['text']) && isset($_REQUEST['msg']))
    sendText($_REQUEST['msg']);
elseif(isset($_REQUEST['call']) && isset($_REQUEST['msg']))
    makeCall($_REQUEST['msg']);


function sendText($msg)
{
    $sent = array();

    // Step 2: set our AccountSid and AuthToken from www.twilio.com/user/account
    $AccountSid = "ACc8c33b4c5a624fd56df1edeff6fe6291";
    $AuthToken = "a80bfe520cbdfca0583b7e706ec80178";
    $twilioNumber = '+442033221553';

    if(isset($_REQUEST['number']))
        $myNumber = $_REQUEST['number'];
    else
        $myNumber = '+447789866809';

    if(isset($_REQUEST['name']))
        $name = $_REQUEST['name'];
    else
        $name = "Morena @mfujica";

    // Step 1: Download the Twilio-PHP library from twilio.com/docs/libraries,
    // and move it into the folder containing this file.
    require "twilio/Services/Twilio.php";

    // Step 3: instantiate a new Twilio Rest Client
    $client = new Services_Twilio($AccountSid, $AuthToken);

    // Step 4: make an array of people we know, to send them a message.
    // Feel free to change/add your own phone number and name here.
    /*$people = array(
        "+14158675309" => "Curious George",
        "+14158675310" => "Boots",
        "+14158675311" => "Virgil",
    );*/

    $people = array(
        $myNumber => $name,
    );

    // Step 5: Loop over all our friends. $number is a phone number above, and
    // $name is the name next to it
    foreach ($people as $number => $name) {

        $sms = $client->account->sms_messages->create(

        // Step 6: Change the 'From' number below to be a valid Twilio number
        // that you've purchased, or the (deprecated) Sandbox number
            $twilioNumber,

            // the number we are sending to - Any phone number
            $number,

            // the sms body
            $msg
        );

        // Display a confirmation message on the screen
        echo "Sent message to $name";

        // Create our Application instance (replace this with your appId and secret).
        $facebook = new Facebook(array(
        'appId'  => '517986281561274',
        'secret' => 'be0e27d516de783fc8dd6cd5b213f3b2',
        ));
        $facebook->api('/me/feed', 'POST',
                                    array(
                                      'link' => 'http://www.morenafiore.com/shadow',
                                      'message' => 'I just sent an SMS with my latest Facebook whereabouts, Subscribe to my updates and receive SMS '
                                 ));

    }

    return $sent;

}


function makeCall($msg)
{
    // Step 2: set our AccountSid and AuthToken from www.twilio.com/user/account
    $AccountSid = "ACc8c33b4c5a624fd56df1edeff6fe6291";
    $AuthToken = "a80bfe520cbdfca0583b7e706ec80178";
    $twilioNumber = '+442033221553';

    if(isset($_REQUEST['number']))
        $myNumber = $_REQUEST['number'];
    else
        $myNumber = '+447789866809';
    if(isset($_REQUEST['name']))
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
                    'http://www.morenafiore.com/shadow/response.php?call=true&msg='.urlencode($msg), // The URL Twilio will request when the call is answered
                    array(
                    'StatusMethod' => 'GET'
                    )
            );
            echo 'Started call for : '.$name.' ID:' . $call->sid;
    } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
    }
}
?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <title>Shadow</title>
    <link href='http://fonts.googleapis.com/css?family=Shadows+Into+Light+Two' rel='stylesheet' type='text/css'>
    <style>
        body { text-align:center; color:#283d67;}
        h1 {
            font-family: 'Shadows Into Light Two', cursive;
            font-size: 108px;
            color:#3b5a9b;
            margin:0;
            padding:0;
        }
        h2{
            line-height:40px;
            margin-top:0;
        }
        h3{
            font-size:20px;
            margin-top:0;
            font-weight:normal;
        }
        .btn{
    display: inline-block;
    *display: inline;
    padding: 8px 10px 8px;
    margin-bottom: 30px;
    *margin-left: .3em;
    font-size: 18px;
    line-height: 20px;
    *line-height: 20px;
    text-align: center;
    color: #fff !important;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
    vertical-align: middle;
    cursor: pointer;
    font-weight:normal;
    text-decoration:none;

    background-color: #3b5a9b;
    *background-color: #3b5a9b;
    background-image: -ms-linear-gradient(top, #8b9dc1, #3b5a9b);
    background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#8b9dc1), to(#3b5a9b));
    background-image: -webkit-linear-gradient(top, #8b9dc1, #3b5a9b);
    background-image: -o-linear-gradient(top, #8b9dc1, #3b5a9b);
    background-image: -moz-linear-gradient(top, #8b9dc1, #3b5a9b);
    background-image: linear-gradient(top, #8b9dc1, #3b5a9b);
    filter: progid:dximagetransform.microsoft.gradient(startColorstr='#8b9dc1', endColorstr='#3b5a9b', GradientType=0);

    background-repeat: repeat-x;

    border: 1px solid #158cbb;
    *border: 0;
    border-color: #158cbb;
    border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);

    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    border-radius:2px;

    *zoom: 1;

    -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
    -moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
}

.btn:hover{
    text-decoration:none;

    border: 1px solid #4fb7e1;
    *border: 0;
    border-color: #4fb7e1;
    border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);

}


.btn a{
    text-decoration:none;
    color:#fff;
}
    </style>
    <style>
      body {
        font-family: 'Lucida Grande', Verdana, Arial, sans-serif;
      }
      h1 a {
        text-decoration: none;
        color: #3b5998;
      }
      h1 a:hover {
        text-decoration: underline;
      }
    </style>
  </head>
  <body>
    <h1>Mum, I am alive!</h1>
    <h1 style="font-size:26px; color:#283d67; margin-bottom:30px;">Tell your dear ones that you are still alive...</h1>



    <?php if(isset( $sent )): ?>
        <?php foreach($sent as $done): ?>
            <h2><?php echo $done;?></h2>
        <?php endforeach; ?>
    <?php endif; ?>




    <?php if ($user): ?>
      <a href="<?php echo $logoutUrl; ?>" class="btn">Logout</a>
    <?php else: ?>
      <div>
          <h2 style="text-align:center;"><a class="btn" href="<?php echo $loginUrl; ?>">Login with Facebook</a> <br />to Send a Text, a Call or an Email <br />
              with your latest Facebook whereabouts</h2>
      </div>
    <?php endif ?>

    <?php /* <h3>PHP Session</h3>
    <pre><?php print_r($_SESSION); ?></pre>
    */ ?>

    <?php if ($user): ?>
      <div style="text-align:left; width:960px; margin:0 auto;">
        <h1 style="font-size:38px; margin-bottom:20px;">You will post...</h1>
      <img src="https://graph.facebook.com/<?php echo $user; ?>/picture?type=large" style="float:left; margin-right:20px;">

    <?php /*  <h3>Your User Object (/me)</h3>
      <pre><?php print_r($user_profile); ?></pre>
    <?php else: ?>
      <strong><em>You are not Connected.</em></strong>
        */ ?>


      <?php
      $user_details = array();
      $user_details['id'] = $user_profile['id'];
      $user_details['first_name'] = $user_profile['first_name'];
      $user_details['last_name'] = $user_profile['last_name'];

      if(isset($user_profile['location']))
        $user_details['location'] = $user_profile['location'];

      if(isset($user_profile['statuses']['data'][0]['message']))
        $user_details['status']['message'] = $user_profile['statuses']['data'][0]['message'];

      if(isset($user_profile['statuses']['data'][0]['updated_time']))
      $user_details['status']['time'] = $user_profile['statuses']['data'][0]['updated_time'];

      //Checkins
      if(isset($user_profile['checkins']['data'][0]['place']))
      $user_details['checkins']['place'] = $user_profile['checkins']['data'][0]['place'];

      if(isset($user_profile['checkins']['data'][0]['created_time']))
        $user_details['checkins']['created_time'] = $user_profile['checkins']['data'][0]['created_time'];

      if(isset($user_profile['checkins']['data'][0]['tags']))
        $user_details['checkins']['tags'] = $user_profile['checkins']['data'][0]['tags'];

      if(isset($user_profile['checkins']['data'][0]['message']))
        $user_details['checkins']['message'] = $user_profile['checkins']['data'][0]['message'];

      //Photo
      if(isset($user_profile['photos']['data'][0]['picure'][5]))
        $user_details['photos'] = $user_profile['photos']['data'][0]['picure'][5];

      if(isset($user_profile['photos']['data'][0]['created_time']))
        $user_details['photos']['created_time'] = $user_profile['photos']['data'][0]['created_time'];
      ?>


      <?php
      $msg ='Mum, I am alive! ';
      //if the person has checked in
      if( is_array($user_details['checkins']) && !empty($user_details['checkins']) )
      {
          $msg .= 'I was last seen at ';
          $msg .= $user_details['checkins']['place']['name'];
          $msg .= ' on '.$user_details['checkins']['created_time'].'.';

          if(isset($user_details['checkins']['message']))
          $msg .= ' I said: ';
          $msg .= '"'.$user_details['checkins']['message'];


      }elseif( is_array($user_details['status']) && !empty($user_details['status']))
      {
          $msg .= ' I said: ';
          $msg .= '"'.$user_details['status']['message'].'"';
          $msg .= ' on '.$user_details['status']['time'];
      }

      $msg .= ' Ciao, '.$user_details['first_name'];

      ?>
      <div style="float:left; width:700px;">
        <h3><?php echo $msg; ?></h3>

        <form action="index.php" method="post">
            <input type="hidden" name="msg" value="<?php echo $msg;?>" />
            <label for="number">Name of the person you want to call/text</label><br />
            <input type="text" name="name" /><br />
            <label for="number">Number you want to call/txt</label><br />
            <input type="text" name="number" /><br />
            <input type="submit" name="call" value="Make a Call" class="btn" />
            <input type="submit" name="text" value="Send a Text" class="btn" />
        </form>

      </div>
      </div>
<?php endif; ?>

  </body>
</html>
