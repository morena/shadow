<?php

require_once 'models/Generic.php';

class Application_Model_Plaques extends Application_Model_Generic
{
	public function __construct()
	{
		$this->_db = Zend_Db_Table::getDefaultAdapter();
	}
	
	function ratePlaques($geocoordinates = '')
	{
		$results = array();
		$totalScore = 0;

		//find the relevant attacks using geolocation
		//HOW??
		$minLat = $geocoordinates['viewport']['southwest']['lat'];
		$maxLat = $geocoordinates['viewport']['northeast']['lat'];
		$minLng = $geocoordinates['viewport']['southwest']['lng'];
		$maxLng = $geocoordinates['viewport']['northeast']['lng'];
		
		
		
		$select = $this->_db->select()
		->from('plaques')
		->where('plaques_lat BETWEEN '.$minLat.' AND '.$maxLat)
		->where('plaques_lng BETWEEN '.$minLng.' AND '.$minLng)
		->where('plaques_lng IS NOT NULL');
		
		/*SELECT `plaques`.* FROM `plaques` 
		WHERE (plaques_lat BETWEEN 53.7577207 AND 53.7641246) 
		AND (plaques_lng BETWEEN -9.248634 AND -9.248634) 
		AND (plaques_lng IS NOT NULL)*/
		
		//switch to random if we get no results
		if(!$this->_db->fetchAll($select->__toString()))
		{
			$results['message'] = 'There were no plaques in your area, so we have picked some random ones';
			$select = $this->_db->select()
			->from('plaques')
			->limit(5)
			->order(new Zend_Db_Expr("RAND()"));
		}
		//query
		//var_dump($select->__toString());exit;
		
		
		
		foreach ($this->_db->fetchAll($select->__toString()) as $plaque)
		{
			$results['plaques'][] = $plaque;
			$totalScore += 5;	
		}

		//print the final score
		//print 'final score = '.$totalScore;

		$results['totalScore'] = $totalScore;

		return $results;
	}

}

