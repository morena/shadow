<?php

class Application_Model_DbTable_Blitz extends Zend_Db_Table_Abstract
{

	protected $_name = "blitz";
	protected $_primary = "blitz_id";

	// Table is auto inc
	protected $_sequence = true;
}
