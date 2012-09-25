<?php

require_once 'models/Generic.php';

class Application_Model_Blitz extends Application_Model_Generic
{
	public function __construct()
	{
		$this->_db = Zend_Db_Table::getDefaultAdapter();
	}
	
	public function rateAttacks($geocoordinates = '')
	{
		
		$totalScore = 0;

		//this array will hold details of the plaques found and a total score
		$results = array();

		$counter = 0;
		
		$results['totalScore'] = 0;
		
		//find the relevant attacks using geolocation
		//HOW??
		$minLat = $geocoordinates['viewport']['southwest']['lat'];
		$maxLat = $geocoordinates['viewport']['northeast']['lat'];
		$minLng = $geocoordinates['viewport']['southwest']['lng'];
		$maxLng = $geocoordinates['viewport']['northeast']['lng'];
		
		//print_r('$minLat = '.$minLat .'and $maxLat = '.$maxLat);exit;
		
		$select = $this->_db->select()
		->from('blitz')
		->where('blitz_lat BETWEEN '.$minLat.' AND '.$maxLat)
		->where('blitz_lng BETWEEN '.$minLng.' AND '.$minLng)
		->where('blitz_lng IS NOT NULL');
		
		/*
		SELECT `blitz`.* FROM `blitz` 
		WHERE (blitz_lat BETWEEN 53.7577207 AND 53.7641246) 
		AND (blitz_lng BETWEEN -9.248634 AND -9.248634) 
		AND (blitz_lng IS NOT NULL)		
		*/
		
		//switch to random if we get no results
		if(!$this->_db->fetchAll($select->__toString()))
		{
			$results['message'] = 'There were no Blitz attacks in your area, so we have picked some random ones';
			$select = $this->_db->select()
			->from('blitz')
			->limit(5)
			->order(new Zend_Db_Expr("RAND()"));
		}	
		
		
		//query
		//var_dump($select->__toString());exit;
		
		foreach ($this->_db->fetchAll($select->__toString()) as $attack) {
			//add plaque details to the results
			$results['attacks'][$counter] = $attack;

			$attackID = $attack['blitz_id'];

			//we want to match damage against each of the scores
			//WHERE MATCH(copy) AGAINST('LOVE')
			//so first we select all the scores
			foreach ($this->_db->fetchAll('SELECT * from scores') as $score) 
			{

				//add plaque details to the results
				$scoreName = $score['scores_name'];
				$scoreScore = $score['scores_score'];

				$results['attacks'][$counter]['score'] = $scoreScore;

				//if in the query SELECT $attack WHERE MATCH(copy) AGAINST('LOVE') we have a result, we add to the score
				foreach($this->_db->fetchAll("SELECT `blitz_damage` FROM blitz WHERE MATCH(blitz_damage) AGAINST('$scoreName') AND blitz_id = $attackID ") as $match)
				{
					if(is_array($match))
					{

						//add scores details to the results array			
						$results['scores'][] = $score;
						$totalScore += $scoreScore;
						//print 'score = '.$scoreScore.'<br />';
						//print 'running total = '.$totalScore.'<br />';
					}
				}
			}
		$counter ++;	
		$results['totalScore'] = $totalScore;
		}
		return $results;
	}
	
}

