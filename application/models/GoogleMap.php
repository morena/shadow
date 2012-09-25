<?php
require_once 'models/Generic.php';

class Application_Model_GoogleMap extends Application_Model_Generic
{
	 /** 
	 * @var $_attacksGeoloc holds the attacks geo location info
	 */
	 private $attacksGeoloc = array();
	 
	  /** 
	 * @var $_plaquesGeoloc holds the attacks geo location info
	 */
	 private $plaquesGeoloc = array();
	  
	  /**
	  * sets the attacks geo location data
	  * 
	  * @param array
	  * @return null
	  */
	  public function setAttacksGeoloc($input= '')
	  {
	  	$this->attacksGeoloc =  $input;
	  }
	  /**
	  * returns the attacks geolocation
	  * 
	  * @return array
	  */
	  public function getAttacksGeoloc()
	  {
	  	return $this->attacksGeoloc;
	  }
	  
	  
	  /**
	  * sets the plaques geo location data
	  * 
	  * @param array
	  * @return null
	  */
	  public function setPlaquesGeoloc($input = '')
	  {
	  	$this->plaquesGeoloc = $input;
	  }
	  /**
	  * returns the plaques geolocation
	  * 
	  * @return array
	  */
	  public function getPlaquesGeoloc()
	  {
	  	return $this->plaquesGeoloc;
	  }
}
?>