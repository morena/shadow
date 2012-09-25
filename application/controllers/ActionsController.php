<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

class ActionsController extends Zend_Controller_Action {

    public function processAction() {
        require_once('models/Facebook.php');
        $facebook = new Application_Model_Facebook();

        $this->view->url = $facebook->getLogUrl();
        $this->view->logText = $facebook->getLogText();
        $this->view->text = $facebook->getText();

        $user = $facebook->getUser();
        if($user)
        {
            $this->view->user = $user;
            $userProfile = $facebook->getUserProfile();
            if($userProfile)
            {
                $this->view->user_details = $userProfile;

            }
        }

        if ($this->getRequest()->isPost()) {

            $params = $this->getRequest()->getParams();

            if(isset($params['msg']) && (isset($params['text']) || isset($params['call']) ))
            {
                require_once('models/Twilio.php');
                $twilio = new Application_Model_Twilio();

                if(isset($params['msg']) && isset($params['text']))
                {
                    if(isset($params['name']))
                        $from = $params['name'];
                    else
                        $from = null;

                    if(isset($params['number']))
                        $to = $params['number'];
                    else
                        $to = null;

                    $smss = $twilio->sendText($params['msg'], $to, $from);

                    if(is_array($smss))
                    {
                        $this->view->heading = 'Your SMS was sent';

                        foreach ($smss as $id => $sms)
                        {
                            //$twilio->checkSmsStatus($id);

                            $this->view->text = $sms['msg'];
                        }
                    }

                }else if(isset($params['msg']) && isset($params['text'])){
                    $twilio->makeCall($params['msg']);
                }
            }
        }
    }



}

