<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

class ActionsController extends Zend_Controller_Action {

    /**
     * Establishes if the user is logged in or not
     * Then checks if the form has been submitted and
     * according to the paramters passed, it establishes
     * if we're requesting a call or a text
     */
    public function processAction()
    {
        /// cache miss; connect to the fb api*/
        require('models/Facebook.php');
        $facebook = new Application_Model_Facebook();
        $userid = $facebook->getUser();

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
                $user['url'] = "/index/logout/?url=".$url;
            }

            $cache->save($user, 'user'.$userid);


        } else {

            // cache hit! shout so that we know
            echo "This one is from cache!\n\n";
            $user = $cache->load('user'.$userid);

        }

        $this->view->user = $user;



        if ($this->getRequest()->isPost()) {

            $params = $this->getRequest()->getParams();

            if(isset($params['msg']) && (isset($params['text']) || isset($params['call']) ))
            {
                require_once('models/Twilio.php');
                $twilio = new Application_Model_Twilio();

                if(isset($params['msg']) && isset($params['text']))
                {
                    if(isset($params['yourname']))
                        $sender = $params['yourname'];
                    else
                        $sender = null;

                    if(isset($params['name']))
                    {
                        $recipientName = $params['name'];
                        $params['msg'] = str_replace('Mum', $recipientName, $params['msg']);
                    }else
                        $recipientName = null;

                    if(isset($params['number']))
                        $to = $params['number'];
                    else
                        $to = null;

                    $smss = $twilio->sendText($params['msg'], $to, $recipientName, $sender);

                    if(is_array($smss))
                    {
                        $this->view->heading = 'Your SMS was sent';

                        foreach ($smss as $id => $sms)
                        {
                            $this->view->text = $sms['msg'];
                            $status = $twilio->checkSmsStatus($id);
                            if($status['status'] == 'sent')
                                $this->view->text .= ' and was '.$status['status'].' on '.$status['date'];
                        }
                    }

                }else if(isset($params['msg']) && isset($params['call'])){

                    if(isset($params['yourname']))
                        $sender = $params['yourname'];
                    else
                        $sender = null;

                    if(isset($params['name']))
                    {
                        $recipientName = $params['name'];
                        $params['msg'] = str_replae('Mum', $recipientName, $params['msg']);
                    }else
                        $recipientName = null;

                    if(isset($params['number']))
                        $to = $params['number'];
                    else
                        $to = null;

                    $calls = $twilio->makeCall($params['msg'], $to, $recipientName, $sender);

                    if(is_array($calls))
                    {
                        $this->view->heading = 'Your Call was sent';

                        foreach ($calls as $id => $call)
                        {
                            $this->view->text = $call['msg'];
                        }
                    }
                }
            }
        }
    }



    public function responsesAction()
    {
        if ($this->getRequest()->isGet()) {

            $params = $this->getRequest()->getParams();

            if(isset($params['msg']) )
            {

                require APPLICATION_PATH."/../library/twilio/Services/Twilio/Twiml.php";

                $response = new Services_Twilio_Twiml;

                $response->say($params['msg']);

                $this->_helper->layout()->disableLayout();
                $this->_helper->viewRenderer->setNoRender(true);

                header('Content-Type: text/xml');

                echo $response;
            }
        }

    }



}

