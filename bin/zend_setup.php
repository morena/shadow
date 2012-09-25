<?php

error_reporting(E_ALL | E_STRICT);
ini_set("max_execution_time", 0);

define('BASE_PATH', realpath(dirname(__FILE__) ));
define('APPLICATION_PATH', BASE_PATH . '/../application/' );

// Define application environment
define('APPLICATION_ENV', 'development');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
	APPLICATION_PATH,
    get_include_path(),
)));


/** Zend_Application */
require_once 'Zend/Application.php';
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap();

$db = Zend_Db_Table::getDefaultAdapter();