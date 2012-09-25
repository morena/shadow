<?php

require_once 'zend_setup.php';

$file = BASE_PATH . '/../docs/data/plaques.json';
$plaquesJson = file_get_contents($file);
	
$plaquesArray = json_decode($plaquesJson, true);
	 
//exit(print_r($plaquesArray));
foreach($plaquesArray as $plaques) 
{
	foreach($plaques as $plaque) 
	{

		//print_r($plaque);
		if (isset($plaque['latitude'])) 
		{
			//break inscription down in individual parts grrrrr
			//no chance
			//print_r( $inscription = explode(' ', $plaque['inscription']));
			
			$data = array(
				'plaques_id' => $plaque['id'],
				'plaques_inscription' => $plaque['inscription'],
				'plaques_lat' => $plaque['latitude'],
				'plaques_lng' => $plaque['longitude'],
				'plaques_erected_at' => $plaque['erected_at'],
				'plaques_photos' => json_encode($plaque['photos'])
			);
			
			$table = new Zend_Db_Table('plaques');
			$table->insert($data);
		}	
	}
}
print'done';
