<?php

class Application_Model_Geocoding
{
	function getGeoLocation($location)
	{
		
		$results = array();	
		
		$rootUrl = 'http://maps.googleapis.com/maps/api/geocode/xml?address=';
		
		
		$client = new Zend_Http_Client();
		if(strlen($location))	
		{
			try {
				$client->setUri($rootUrl .$location.'&sensor=false');
				$response = $client->request();
			} catch (Exception $e) {
				print "Geocoding: could not do anything with this location: $location \n\n";
				print $e->getMessage();
			}

			if($xml_string = $response->getBody()) {
				$xml = new SimpleXMLElement($xml_string);
				if (isset($xml->result[0]))
				{
					$results['requestLocation']['lat'] = (float) $xml->result[0]->geometry->location->lat;
					$results['requestLocation']['lng'] = (float)$xml->result[0]->geometry->location->lng;
					
					$results['viewport']['southwest']['lat'] = (float) $xml->result[0]->geometry->viewport->southwest->lat;
					$results['viewport']['southwest']['lng'] = (float) $xml->result[0]->geometry->viewport->southwest->lng;
					$results['viewport']['northeast']['lat'] = (float) $xml->result[0]->geometry->viewport->northeast->lat;
					$results['viewport']['northeast']['lng'] = (float) $xml->result[0]->geometry->viewport->northeast->lng;
					
					
				}else{
					$results[] =  array("id" => $id, "response" => $response);
				}
			}
		}
	
	return $results;
	
	}

}

