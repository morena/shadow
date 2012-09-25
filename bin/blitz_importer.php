<?php

require_once 'zend_setup.php';

// importCsv();
updateLatLng();

function importCsv()
{
	$table = new Zend_Db_Table('blitz');
	$file = BASE_PATH . '/../docs/data/Blitz.csv';
	$fh = fopen($file, 'r');
	while (($data = fgetcsv($fh, 4096, ",")) !== FALSE)
	{
		$data = array(
			'blitz_time' => $data[2],
			'blitz_location' => $data[3],
			'blitz_bomb_type' => $data[4],
			'blitz_damage' => $data[5]
		);
		$table->insert($data);
	}
	fclose($fh);
}

function updateLatLng()
{
	$record = 0;
	$client = new Zend_Http_Client();
	$rootUrl = 'http://maps.googleapis.com/maps/api/geocode/xml?address=';

	$db = Zend_Db_Table::getDefaultAdapter();
	$select = $db->select()
		->from('blitz', array('blitz_location', 'blitz_id'))
		->where('blitz_lat IS NULL')
		->orwhere('blitz_lng IS NULL');
		
	$failure = array();
	foreach ($db->fetchAll($select->__toString()) as $attack)
	{
		$record++;
		if(($record % 100) == 0)
			print 'Finished '.$record . " records\n";

		if(isset($attack['blitz_location']))
			$attack['blitz_location'] = str_replace(' ' , '+', $attack['blitz_location']);

		$id = $attack['blitz_id'];
	
		try {
			$client->setUri($rootUrl .$attack['blitz_location'].'&sensor=false');
	
			$response = $client->request();
		} catch (Exception $e) {
			print "could not do anything with this id's location: $id \n\n";
		}
	
		if($xml_string = $response->getBody()) {
			$xml = new SimpleXMLElement($xml_string);
			
			if (isset($xml->result[0]))
			{
				$location = $xml->result[0]->geometry->location;
				
				$values = array(
					"blitz_lat" => (float) $location->lat,
					"blitz_lng" => (float) $location->lng
				);
				
				$db->update('blitz', $values, "blitz_id = $id");
			}else{
				$failure[] =  array("id" => $id, "response" => $response);
			}
		}
	}

	print_r($failure);
}