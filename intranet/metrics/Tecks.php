<?php

date_default_timezone_set('America/Sao_Paulo');


/* ******************************
* Audience Class is responsible for all metrics related to Audience.
* Pageviews, Unique Visitors, etc.
* It includes both info of Teckler and of Google Analytics.
*/
class Tecks{

	public $conn;
	public $activeDebug=FALSE;

	private $sql_statements_array  = array(
							"NewTecksPerDay" 	=> "select date(PUBLISH_DATE) as PublishedDate, count(POST_ID) as NumTecks  
													from TECKLER.POST 
													group by date(PUBLISH_DATE) 
													order by date(PUBLISH_DATE) DESC",
							"NewTecksPerMonth" 	=> "select date(PUBLISH_DATE) as PublishedDate, count(POST_ID) as NumTecks 
													from TECKLER.POST 
													group by month(PUBLISH_DATE) 
													order by month(PUBLISH_DATE) DESC" );

	// Setting up basic parameters from Google Analytics API
	private $gaUsername 	= 'team@teckler.com';
	private $gaPassword 	= 'T3ckl347';
	private $profileId 		= '71713009';
	
	/* ********************************** 
	* 
	*/
	public function __construct($prConn) {
		$this->conn = $prConn;

		// DEBUG
		if ($this->activeDebug){
			echo "<pre>\n";
			print_r($this->sql_statements_array);
			echo "</pre>\n";				
		}
		
		return $this->conn;
	}

	/* *************************************************
	* Set up the return data on the specif format type
	*/
	private function setReturnResultSet($prData, $prRetType='array'){
		if ($prRetType=='array') {
			return $prData;
		} else {
			return json_encode($prData);
		} 
	}


	/* ************************************************* 
	*  Retrieves the Specific Metric
	*/
	private function getMetricData($prMetric){
		$metric = "'" . $prMetric . "'";

		$sql = $this->sql_statements_array[$prMetric];
		$result = $this->conn->getConnection()->query($sql);
		if (!$result){
			$data = FALSE;
		} else {
			try {
				while ($rawdata = $result->fetch_assoc()) {
	        		$data[] = $rawdata;
	        		// DEBUG
	        		if ($this->activeDebug){
	        			echo "<pre>\n";
	        			print_r($data);
	        			echo "</pre>\n";
	        		}
	        		
	    		}
			} catch(Exception $e) {
				die("Something went wrong. Check the exception " . $e->getMessage() . "\n" );
			} 
		}
		return $data;	
	}

	/* ******************************************
	* Get Specific Metrics Info
	*/
	public function getNewTecksPerDay(){
		$metricData = $this->getMetricData('NewTecksPerDay');
		return $metricData;
	}

	public function getNewTecksPerMonth(){
		$metricData = $this->getMetricData('NewTecksPerMonth');
		return $metricData;
	}

	public function getGAMetricsBounceRateTecks(){

		require_once ("gapi-1.3/gapi.class.php");

		$dimensions = array('date','pagePath');
		$sort = '';
		$toDate = date("Y-m-d"/*, strtotime('- 2 days')*/);
		$fromDate = date("Y-m-d", strtotime('- 90 days'));
		$metrics = array(
			'visits',
			'uniquePageviews',
			'visitors'
			);
		$filter = '';
		$ga1 = new gapi($this->gaUsername, $this->gaPassword);
		$mostPopular = $ga1->requestReportData($this->profileId, $dimensions, $metrics, null, null, $fromDate, $toDate, 1, null);

		foreach($ga1->getResults() as $key => $result){		 
			$repDate1[$key] 				= $result->getDate();
			$repVisits1[$key]				= $result->getVisits();
			$repUniquePageviews1[$key]		= $result->getUniquePageviews();
			$repVisitors1[$key]				= $result->getVisitors();
			// Assembling returning data structure	
			$gaResults2[] = array(
				"Reference Date" 	=> (int)$repDate1[$key],
				"Visits"			=> (int)$repVisits1[$key],
				"Unique Pageviews"	=> (int)$repPageviews1[$key],
				"Visitors"			=> (int)$repVisitors1[$key]
				);
		}
		return $gaResults2;
	}

	public function getGAMetricsTecksPageConvertion(){

		require_once ("gapi-1.3/gapi.class.php");

		$dimensions = array('daysSinceLastVisit');
		$sort = '';
		$toDate = date("Y-m-d"/*, strtotime('- 2 days')*/);
		$fromDate = date("Y-m-d", strtotime('- 90 days'));
		$metrics = array(
			'visits',
			'pageviews',
			'visitors'
			);
		$ga3 = new gapi($this->gaUsername, $this->gaPassword);
		$mostPopular = $ga3->requestReportData($this->profileId, $dimensions, $metrics, null, null, $fromDate, $toDate, 1, null);

		foreach($ga3->getResults() as $key => $result){		 
			$repDaysSinceLastVisit3[$key] 	= $result->getDaysSinceLastVisit();
			$repVisits3[$key]				= $result->getVisits();
			$repPageviews3[$key]			= $result->getPageviews();
			$repVisitors3[$key]				= $result->getVisitors();
			// Assembling returning data structure	
			$gaResults3[] = array(
				"Days Since Last Visit" 	=> (int)$repDaysSinceLastVisit3[$key],
				"Visits"					=> (int)$repVisits3[$key],
				"Pageviews"					=> (int)$repPageviews3[$key],
				"Visitors"					=> (int)$repVisitors3[$key]
				);
		}
		return $gaResults3;
	}
}


?>