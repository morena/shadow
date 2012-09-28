<?php
class Application_Model_User
{
    /**
     *
     * $_user obj
     */
    private $_user = null;

    /**
     * Allows for setting the user obj from outside
     * @param obj $input
     */
    public function setUser($input)
    {
        $this->_user = $input;
    }

    /**
     * Instantiates the Zend Cache objects and stores it
     */
    public function getUser()
    {
        //connect to the FB API to get the User ID
        require_once('models/Facebook.php');
        $facebook = new Application_Model_Facebook();
        $userid = $facebook->getUser();

        //we then call the cache
        require_once('models/Memcache.php');
        $cache_m = new Application_Model_Memcache();
        $cache = $cache_m->getCache();

        // see if a cache already exists:
        if( ($user = $cache->load('user'.$userid)) === false ) {

            $user = array();

            $url = $facebook->getLogUrl();
            $user['url'] = $url;
            $user['logText'] = $facebook->getLogText();
            $user['text'] = $facebook->getText();

            if($userid)
            {
                $userProfile = $facebook->getUserProfile();
                $user['userProfile'] = $userProfile;
                $user['url'] = "/index/logout/?url=".$url;
            }

            $cache->save($user, 'user'.$userid);
            $this->saveUser($user);


        } else {

            // cache hit! shout so that we know
            echo "This one is from cache!\n\n";
            $user = $cache->load('user'.$userid);

        }

        return $user;

    }


}

