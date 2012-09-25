<?php

class LoginController extends Zend_Controller_Action {

    public function indexAction()
    {
        ini_set('display_errors',1);
        error_reporting(E_ALL);

        require_once('models/Facebook.php');
        $facebook_m = new Application_Model_Facebook();

        $this->view->loginUrl = $facebook_m->loginUrl;

    }

    public function loginAction()
    {

    }


}

