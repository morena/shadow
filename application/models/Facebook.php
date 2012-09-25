<?php
require APPLICATION_PATH . '/../fbsdk/src/facebook.php';

class Application_Model_Facebook
{
    /**
     * private string
     */
    private $_appId = '517986281561274';

    /**
     * private string
     */
    private $_secret = 'be0e27d516de783fc8dd6cd5b213f3b2';

    private $_user = null;

    private $_logUrl = null;

    private $_logText = null;

    private $_userProfile = null;

    private $_text = null;

    public function setUser($input)
    {
        $this->_user = $input;
    }
    public function getUser()
    {
        return $this->_user;
    }

    public function setLogUrl($input)
    {
        $this->_logUrl = $input;
    }
    public function getLogUrl()
    {
        return $this->_logUrl;
    }
    public function setLogText($input)
    {
        $this->_logText = $input;
    }
    public function getLogText()
    {
        return $this->_logText;
    }

    public function setUserProfile($input)
    {
        $this->_userProfile = $input;
    }
    public function getUserProfile()
    {
        return $this->_userProfile;
    }

    public function setText($input)
    {
        $this->_text = $input;
    }
    public function getText()
    {
        return $this->_text;
    }



    public function __construct()
    {
        // Create our Application instance (replace this with your appId and secret).
        $facebook = new Facebook(array(
                    'appId' => $this->_appId,
                    'secret' => $this->_secret,
                ));

        // Get User ID
        $user = $facebook->getUser();
        $this->setUser($user);

        // Login or logout url will be needed depending on current user state.
        if ($user) {
            $this->setLogUrl($facebook->getLogoutUrl());
            $this->setLogText('Logout');

            // We may or may not have this data based on whether the user is logged in.
            //
            // If we have a $user id here, it means we know the user is logged into
            // Facebook, but we don't know if the access token is valid. An access
            // token is invalid if the user logged out of Facebook.
            try
            {
                // Proceed knowing you have a logged in user who's authenticated.
                $user_profile = $facebook->api('me?fields=first_name,last_name,location,statuses.limit(10),photos.limit(10),checkins.limit(10)');
                if($user_profile)
                    $this->setUserProfile($this->aggregateLatestFbActivity($user_profile));

            } catch (FacebookApiException $e)
            {
                error_log($e);
                $user = null;
            }

        } else {
            $params = array('scope' => array('user_activities','user_location', 'user_photos', 'user_status', 'publish_actions'));
            $this->setLogUrl($facebook->getLoginUrl($params));
            $this->setLogText('Login');
            $this->setText('To send a Text, a Call or an Email <br />with your latest Facebook whereabouts');
        }

        // This call will always work since we are fetching public data.
        //$naitik = $facebook->api('/naitik');

    }

    protected function aggregateLatestFbActivity($user_profile)
    {
        if(!is_array($user_profile))
            return false;

        $user_details = array();
        $user_details['id'] = $user_profile['id'];
        $user_details['first_name'] = $user_profile['first_name'];
        $user_details['last_name'] = $user_profile['last_name'];

        if(isset($user_profile['location']))
            $user_details['location'] = $user_profile['location'];

        if(isset($user_profile['statuses']['data'][0]['message']))
            $user_details['status']['message'] = $user_profile['statuses']['data'][0]['message'];

        if(isset($user_profile['statuses']['data'][0]['updated_time']))
        $user_details['status']['time'] = $this->formatDate($user_profile['statuses']['data'][0]['updated_time']);



        //Checkins
        if(isset($user_profile['checkins']['data'][0]['place']))
        $user_details['checkins']['place'] = $user_profile['checkins']['data'][0]['place'];

        if(isset($user_profile['checkins']['data'][0]['created_time']))
            $user_details['checkins']['created_time'] = $this->formatDate($user_profile['checkins']['data'][0]['created_time']);

        if(isset($user_profile['checkins']['data'][0]['tags']))
        {
            foreach($user_profile['checkins']['data'][0]['tags'] as $key =>$tag)
            {
                foreach( $tag as $key => $value )

                $user_details['checkins']['tags'][]= $value['name'];
            }

        }

        if(isset($user_profile['checkins']['data'][0]['message']))
            $user_details['checkins']['message'] = $user_profile['checkins']['data'][0]['message'];

        //Photo
        if(isset($user_profile['photos']['data'][0]['picure'][5]))
            $user_details['photos'] = $user_profile['photos']['data'][0]['picure'][5];

        if(isset($user_profile['photos']['data'][0]['created_time']))
            $user_details['photos']['created_time'] = $user_profile['photos']['data'][0]['created_time'];


        $msg ='Mum, I am alive! ';
        //if the person has checked in
        if( is_array($user_details['checkins']) && !empty($user_details['checkins']) )
        {
            $msg .= 'I was last seen at ';
            $msg .= $user_details['checkins']['place']['name'];
            $msg .= ' on '.$user_details['checkins']['created_time'].'.';

            if(isset($user_details['checkins']['message']))
            $msg .= ' I said: ';
            $msg .= '&quot;'.$user_details['checkins']['message'];

            if(isset($user_details['checkins']['tags']))
            {
                $msg .= ' '.implode(',', $user_details['checkins']['tags']);
            }
            $msg .= '&quot;';


        }elseif( is_array($user_details['status']) && !empty($user_details['status']))
        {
            $msg .= ' I said: ';
            $msg .= '&quot;'.$user_details['status']['message'].'&quot;';
            $msg .= ' on '.$user_details['status']['time'];
        }

        $salutation = ' Ciao, '.$user_details['first_name'];
        $salutationLength = strlen($salutation);
        $correctLength = 160-$salutationLength-9;

        //make sure the msg is not longer than 160 chars
        if(strlen($msg.$salutation) >= 160)
            $shorterMsg = substr($msg, 0, $correctLength ).'..."';

        $shorterMsg .= $salutation;

        $user_details['msg'] = str_replace('"', '&quot;',$shorterMsg);

        return $user_details;
    }

    protected function formatDate($date)
    {
        $timestamp = strtotime($date);

        return date('dS F Y',$timestamp).' at '.date('H:i',$timestamp);
    }


    protected function establishLatestActivity()
    {

    }




}

