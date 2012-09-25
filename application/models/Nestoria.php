<?php

class Application_Model_Nestoria
{
	function calculateAveragePrice($location)
	{
		//http://api.nestoria.co.uk/api?country=uk&pretty=1&action=metadata&place_name=brighton&encoding=json	  
		$rootUrl = 'http://api.nestoria.co.uk/api?action=metadata&encoding=json&place_name=';
		
		$client = new Zend_Http_Client();
		if(strlen($location))	
		{
			try {
				$client->setUri($rootUrl .$location);

				$response = $client->request();
				sleep(1);
			} catch (Exception $e) {
				print "Nestoria: could not do anything with this location: $location \n\n";
				print $e->getMessage();
			}

			//print_r($response);
			if($results = json_decode($response->getBody(), true)) {

				/*print_r($results);
				exit;*/
				if(is_array($results['response']['metadata']))
				{
					foreach( $results['response']['metadata'] as $metadata) {
						//print_r($metadata['metadata_name']);
						if( $metadata['metadata_name'] == 'avg_2bed_property_buy_monthly')
						{
							$sumRequestPrices = 0;

							for ($m = 1; $m <= 12; $m++)
							{
								//echo $metadata['data']['2010_m'.$m];
								$sumRequestPrices += $metadata['data']['2010_m'.$m]['avg_price'];
							}
						}
					}	
				}

				//print 'sum of prices = '.$sumRequestPrices.'<br/>';

				$averageRequestPrice = ($sumRequestPrices / 12 );
				return $averageRequestPrice;


			}else{
				//$failure[] =  array("id" => $id, "response" => $response);
				return false;
				//return '<h2>We could not find data for the location you have entered. Try with a different one.</h2>';
			}
		}
	}

}

