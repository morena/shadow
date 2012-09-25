<?php

class Application_Model_DbTable_Plaques extends Zend_Db_Table_Abstract
{

	protected $_name = "plaques";
	protected $_primary = "plaques_id";

	// Table is auto inc
	protected $_sequence = true;
}
