<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


class IndexController extends Zend_Controller_Action {

    public function indexAction()
    {

        require_once('models/User.php');
        $user_m = new Application_Model_User();
        $user = $user_m->getUser();

        if(isset($user['userProfile']))
        {
            $this->view->form = $this->actionForm($user);
            $this->view->fullMsg = $user['userProfile']['fullMsg'];
        }


        $this->view->user = $user;

    }


    protected function actionForm($user)
    {
        require_once 'forms/Actions.php';

        $form = new Application_Form_Actions();
        $mgElement = $form->getElement('msg');
        $mgElement->setValue($user['userProfile']['msg']);
        $sender = $form->getElement('yourname');
        $sender->setValue($user['userProfile']['first_name']);

        return $form;
    }

    public function logoutAction()
    {
        if ($this->getRequest()->isGet())
        {
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
            $params = $this->getRequest()->getParams();
            Zend_Session::destroy();
            //$this->_redirect($params['url']);
            $this->_helper->redirector->gotoUrlAndExit($params['url']);
        }

    }

    /* public function indexAction()
      {
      require_once 'forms/Search.php';

      $this->_form = new Application_Form_Search();
      $this->view->form = $this->_form;

      if ($this->getRequest()->isPost()) {

      // now check to see if the form submitted exists, and
      // if the values passed in are valid for this form
      if ($this->_form->isValid($this->_request->getPost())) {
      // Get the values from the DB
      $data = $this->_form->getValues();

      //replace spaces inside location with +
      $data['location'] = str_replace(' ', '+',$data['location']);

      //get the average price for a house
      require_once('models/Nestoria.php');
      $n = new Application_Model_Nestoria();
      $averageRequestPrice = $n->calculateAveragePrice($data['location']);
      $this->view->averageRequestPrice = number_format($averageRequestPrice, 0, '.', ', ');

      //get geolocation for the request from google
      require_once('models/Geocoding.php');
      $g = new Application_Model_Geocoding();
      $viewport = $g->getGeoLocation($data['location']);

      //print_r($viewport);

      if($viewport)
      {
      //pass the bounding biox geo coordinates to the view
      $this->view->viewportGeoloc = $viewport;
      require_once('models/GoogleMap.php');
      $coordinates = new Application_Model_GoogleMap();

      //get a rate for attacks
      require_once('models/Blitz.php');
      $b = new Application_Model_Blitz();
      $attacksResults = $b->rateAttacks($viewport);


      for($i = 0; $i<= count($attacksResults['attacks']); $i++)
      {
      $attacksCoordinates[$i]['blitz_lat'] = $attacksResults['attacks'][$i]['blitz_lat'];
      $attacksCoordinates[$i]['blitz_lng'] = $attacksResults['attacks'][$i]['blitz_lng'];
      }
      //var_dump($attacksCoordinates);
      $coordinates->setAttacksGeoloc($attacksCoordinates);
      $this->view->attacksCoordinates = $coordinates->getAttacksGeoloc();

      $this->view->attacksResults = $attacksResults;

      //get a rate for plaques
      require_once('models/Plaques.php');
      $p = new Application_Model_Plaques();
      $plaquesResults = $p->ratePlaques($viewport);

      for($i = 0; $i<= count($plaquesResults["plaques"]); $i++)
      {
      $plaquesCoordinates[$i]['plaques_lat'] = $plaquesResults["plaques"][$i]['plaques_lat'];
      $plaquesCoordinates[$i]['plaques_lng'] = $plaquesResults["plaques"][$i]['plaques_lng'];
      }
      $coordinates->setPlaquesGeoloc($plaquesCoordinates);
      $this->view->plaquesGeoloc = $coordinates->getPlaquesGeoloc();

      $this->view->plaquesResults = $plaquesResults;
      }

      $totalScore = $attacksResults['totalScore'] +  $plaquesResults['totalScore'];

      $this->view->historicPrice = number_format($averageRequestPrice + ( ($totalScore / 100 )* $averageRequestPrice ), 0, '.', ', ');

      $this->view->totalScore = $totalScore;
      }
      } */
}

