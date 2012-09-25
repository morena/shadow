<?php

class Application_Model_Generic
{
	private $_db = null;
	
	public function __construct()
	{
		$this->_db = Zend_Db_Table::getDefaultAdapter();
	}
	
}

