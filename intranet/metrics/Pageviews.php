<?php

date_default_timezone_set('America/Sao_Paulo');


/* ******************************
* Audience Class is responsible for all metrics related to Audience.
* Pageviews, Unique Visitors, etc.
* It includes both info of Teckler and of Google Analytics.
*/
class Audience{

	public $conn;
	public $activeDebug=FALSE;

	private $sql_statements_array  = array(
				"PageviewsPerTeckPerDay" 		=> "select 
														date(a.RefDate) as ReferenceDate,
														(sum(a.SumPageviewsTeckler) / count(a.TeckID)) as AvgPageviewsTecks,
														(sum(a.SumPageviewsDFP) / count(a.TeckID)) as AvgPageviewsDFP,
														((sum(a.SumPageviewsDFP) + sum(a.SumPageviewsTeckler)) / (count(a.TeckID) *2)) as AvgPageviewsGlobal 
													from 
														(select   
															sum(VIEWS) as SumPageviewsTeckler,  
															sum(DFP_VIEWS) as SumPageviewsDFP,  
															DAY as RefDate,  
															POST_ID as TeckID 
														from  
															TECKLER.DAILY_VIEWS  
														where  
															date(DAY) <> date('0000-00-00')  
														group by 
															POST_ID, date(DAY)  
														order by  
															date(DAY)) a
													group by
														date(a.RefDate)",
				"PageviewsPerTeckPerMonth" 		=> "select 
														date(a.RefDate) as ReferenceDate, 
														(sum(a.SumPageviewsTeckler) / count(a.TeckID)) as AvgPageviewsTecks,
														(sum(a.SumPageviewsDFP) / count(a.TeckID)) as AvgPageviewsDFP,
														((sum(a.SumPageviewsDFP) + sum(a.SumPageviewsTeckler)) / (count(a.TeckID) *2)) as AvgPageviewsGlobal 
													from 
														(select  
															sum(VIEWS) as SumPageviewsTeckler,  
															sum(DFP_VIEWS) as SumPageviewsDFP,  
															DAY as RefDate,  
															POST_ID as TeckID 
														from  
															TECKLER.DAILY_VIEWS  
														where  
															month(DAY) <> '00' 
														group by 
															POST_ID, month(DAY)  
														order by  
															month(DAY)) a 
													group by 
														month(a.RefDate)", 
				"PageviewsPerTeckAccumulatedGlobal"		=> "select 
																sum(PAGE_VIEWS) / count(POST_ID) as AvgAccumPageviews 
															from TECKLER.POST", 
				"PageviewsAging"				=> "select  
														date(dv.DAY) as ReferenceDate, 
														sum(dv.VIEWS) as Pageviews 
													from 
														TECKLER.DAILY_VIEWS dv  
													where 
														date(dv.DAY) > date('0000-00-00') and  
														dv.POST_ID in 
															(select 
																POST_ID 
															from 
																POST 
															where 
																date(PUBLISH_DATE)>date('2013-09-15') 
															) 
													group by 
														date(dv.DAY)  
													order 
														by date(dv.DAY)", 
				"TotalVisitorsPerMonth"		=> "select 1", 
				"TotalVisitorsPerDay"		=> "select 
													TOTAL_VISITORS_DAY as NumVisitors, 
													date(DATE) as ReferenceDate 
												from 
													DELTA_METRICS_GOOGLE_ANALYTICS_DAY
												order by 
													date(DATE) DESC", 
				"PercNewVisitorPerMonth"	=> "select 1", 
				"PercNewVisitorPerDay"		=> "select 
													TOTAL_PERC_NEW_VISITORS_DAY as PercentNewVisitors, 
													date(DATE) as ReferenceDate 
												from 
													DELTA_METRICS_GOOGLE_ANALYTICS_DAY
												order by 
													date(DATE) DESC", 
				"PagesPerVisitPerMonth"		=> "select 1", 
				"PagesPerVisitPerDay"		=> "select 
													TOTAL_PAGES_VISITS_DAY as NumPagesVisits, 
													date(DATE) as ReferenceDate 
												from 
													DELTA_METRICS_GOOGLE_ANALYTICS_DAY
												order by 
													date(DATE) DESC"
			);

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
		if ($this->activeDebug) {
			echo "[DEBUG] -- Metric is: " . $metric . "<BR>\n"; 
			echo "[DEBUG] -- SQL Statement is: " . $sql . "<BR>\n"; 
		}
		$result = $this->conn->getConnection()->query($sql);
		if (!$result){
			// DEBUG
			if ($this->activeDebug) {
				echo "[DEBUG] -- SQL Result is empty!\n";
			}
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
	public function getPageviewsPerTeckPerDay(){
		$metricData = $this->getMetricData('PageviewsPerTeckPerDay');
		if ($this->activeDebug) {
			echo "<pre>\n";
			print_r($metricData);
			echo "</pre>\n";

		}
		return $metricData;
	}

	public function getPageviewsPerTeckPerMonth(){
		$metricData = $this->getMetricData('PageviewsPerTeckPerMonth');
		return $metricData;
	}

	public function getPageviewsPerTeckAccumulatedGlobal(){
		$metricData = $this->getMetricData('PageviewsPerTeckAccumulatedGlobal');
		return $metricData;
	}	

	public function getPageviewsAging(){

		$teckCreationDate = date("Y-m-d", strtotime('2013-10-08'));
		$todayis = date("Y-m-d");
		for ($i = strtotime('2013-09-19') ; $i <= strtotime($todayis) ; $i = $i + (60 * 60 * 24) ){
			$teckCreationDate = date('Y-m-d', $i);
			$sql = 
				"select  
					date(dv.DAY) as ReferenceDate, sum(dv.VIEWS) as Pageviews 
				from TECKLER.DAILY_VIEWS dv  
				where 
					date(dv.DAY) > date('0000-00-00') and  
					dv.POST_ID in 
						(select POST_ID from TECKLER.POST 
						where date(PUBLISH_DATE)=date('". date('Y-m-d', $i) . "') ) 
				group by date(dv.DAY) order by date(dv.DAY)";

			$ReferenceDate = $teckCreationDate;
			// Setting up initial values for Period Variables
			$Pageviews_P1 = 0; $Pageviews_P2 = 0; $Pageviews_P3 = 0; 
			$Pageviews_P4 = 0; $Pageviews_P5 = 0; $Pageviews_P6 = 0;
			
			// Accumulating Pageviews into specific period slots.
			$result = $this->conn->getConnection()->query($sql);

			// DEBUG
			//echo "<PRE>";
			//print_r($result);
			//echo "</PRE>";

			
			while ($row = $result->fetch_assoc() ){	
				// First Period
				$ReferenceDay = strtotime($ReferenceDate);
				if (strtotime($row['ReferenceDate']) == $ReferenceDay) {
					$Pageviews_P1 = $row['Pageviews'];
				}
				// Second Period (D2 to D7)
				$ReferenceDay_Plus1 	= $ReferenceDay + (60*60*24*1);
				$ReferenceDay_Plus7 	= $ReferenceDay + (60*60*24*6);
				if 	(	(strtotime($row['ReferenceDate']) >= $ReferenceDay_Plus1) AND 
						(strtotime($row['ReferenceDate']) <= $ReferenceDay_Plus7) ) {
					$Pageviews_P2 = $Pageviews_P2 +  $row['Pageviews']; 
				}
				// Third Period (D8 to D14)
				$ReferenceDay_Plus8 	= $ReferenceDay + (60*60*24*7);
				$ReferenceDay_Plus14 	= $ReferenceDay + (60*60*24*13);
				if 	(	(strtotime($row['ReferenceDate']) >= $ReferenceDay_Plus8) AND 
						(strtotime($row['ReferenceDate']) <= $ReferenceDay_Plus14) ) {
					$Pageviews_P3 = $Pageviews_P3 + $row['Pageviews']; 
				}
				// Fourth Period (D15 to D30)
				$ReferenceDay_Plus15	= $ReferenceDay + (60*60*24*14);
				$ReferenceDay_Plus30	= $ReferenceDay + (60*60*24*30);
				if 	(	(strtotime($row['ReferenceDate']) >= $ReferenceDay_Plus15) AND 
						(strtotime($row['ReferenceDate']) <= $ReferenceDay_Plus30) ) {
					$Pageviews_P4 = $Pageviews_P4 + $row['Pageviews']; 
				}
				// Fifth Period (D31 to D60)
				$ReferenceDay_Plus31 	= $ReferenceDay + (60*60*24*31);
				$ReferenceDay_Plus60	= $ReferenceDay + (60*60*24*60);
				if 	(	(strtotime($row['ReferenceDate']) >= $ReferenceDay_Plus31) AND 
						(strtotime($row['ReferenceDate']) <= $ReferenceDay_Plus60) ) {
					$Pageviews_P5 = $Pageviews_P5 + $row['Pageviews']; 
				}
				// Sixth Period (D61 to D90)
				$ReferenceDay_Plus61 	= $ReferenceDay + (60*60*24*61);
				$ReferenceDay_Plus90	= $ReferenceDay + (60*60*24*90);
				if 	(	(strtotime($row['ReferenceDate']) >= $ReferenceDay_Plus61) AND 
						(strtotime($row['ReferenceDate']) <= $ReferenceDay_Plus90) ) {
					$Pageviews_P6 = $Pageviews_P6 + $row['Pageviews']; 
				}
			}
			// Accumulating Results for returning
			$AgingPVMatrix[] = 
			array(
				"Date"	=> $ReferenceDate,
				"PV-P1 (Creation Day)"	=> $Pageviews_P1,
				"PV-P2 (D2 to D6)"		=> $Pageviews_P2,
				"PV-P3 (D7 to D14)"		=> $Pageviews_P3,
				"PV-P4 (D15 to D30)"	=> $Pageviews_P4,
				"PV-P5 (D31 to D60)"	=> $Pageviews_P5,
				"PV-P6 (D61 to D90)"	=> $Pageviews_P6,
				);

		}
		
		return $AgingPVMatrix;
	}	

	public function getTotalVisitorsPerDay(){
		$metricData = $this->getMetricData('TotalVisitorsPerDay');
		return $metricData;
	}

	public function getPercNewVisitorPerDay(){
		$metricData = $this->getMetricData('PercNewVisitorPerDay');
		return $metricData;
	}

	public function getPagesPerVisitPerDay(){
		$metricData = $this->getMetricData('PagesPerVisitPerDay');
		return $metricData;
	}

	public function getGAMetrics(){

		require_once ("gapi-1.3/gapi.class.php");

		$dimensions = array('date');
		$sort = 'date';
		$timeInterval = 90;	
		$toDate = date("Y-m-d"/*, strtotime('- 2 days')*/);
		$fromDate = date("Y-m-d", strtotime('- 90 days'));
		$metrics = array(
			'pageviews',
			'uniquePageviews',
			'visits',
			'newVisits', 
			'percentNewVisits',
			'pageviewsPerVisit');
		$ga1 = new gapi($this->gaUsername, $this->gaPassword);
		$mostPopular = $ga1->requestReportData($this->profileId, $dimensions, $metrics, $sort, null, $fromDate, $toDate, 1, $timeInterval);

		foreach($ga1->getResults() as $key => $result){
			$repDate[$key] 					= $result->getDate();
			$repPageview[$key] 				= $result->getPageviews();	
			$repUniquePageview[$key] 		= $result->getUniquePageviews();	
			$repVisits[$key]				= $result->getVisits();
			$repNewVisits[$key] 			= $result->getNewVisits();
			$repPercentNewVisits[$key] 		= $result->getPercentNewVisits();
			$repPercentReturningVisits[$key] = (100 - $repPercentNewVisits[$key]);
			$repPagesPerVisit[$key] 		= ($repVisits[$key] != 0 ? $repPageview[$key] / $repVisits[$key] : 0);


			// Assembling returning data structure	
			$gaResults1[] = array(
				"Date" 						=> date("Y-m-d", strtotime($repDate[$key])),
				"UniquePageviews" 			=> $repUniquePageview[$key], 
				"Total Visits"	 			=> $repVisits[$key],
				"New Visits"				=> $repNewVisits[$key], 
				"Percent New Visits"		=> round($repPercentNewVisits[$key],2), 
				"Percent Returning Visits"	=> round($repPercentReturningVisits[$key],2), 
				"Pages Per Visit"			=> round($repPagesPerVisit[$key],2)
				);
		}
		return $gaResults1;
	}

	public function getGAMetrics_VisitsCount(){

		require_once ("gapi-1.3/gapi.class.php");

		$dimensions = array('visitCount');
		$sort = 'visitCount';
		$toDate = date("Y-m-d", strtotime('- 1 days'));
		$fromDate = date("Y-m-d", strtotime('- 31 days'));
		$metrics = array('visitors', 'pageviews', 'visits');
		$maxResults = 1000;
		$ga2 = new gapi($this->gaUsername, $this->gaPassword);
		$mostPopular = $ga2->requestReportData($this->profileId, $dimensions, $metrics, $sort, null, $fromDate, $toDate, 1, $maxResults);

		foreach($ga2->getResults() as $key => $result){		 
			$repVisitsCount2[$key] 			= $result->getVisitCount();
			$repVisits2[$key]				= $result->getVisits();
			$repPageviews2[$key]			= $result->getPageviews();
			$repVisitors2[$key]				= $result->getVisitors();
			// Assembling returning data structure	
			$gaResults2[] = array(
				"Visit Count" 	=> (int)$repVisitsCount2[$key],
				"Visitors"		=> (int)$repVisitors2[$key],
				"Pageviews"		=> (int)$repPageviews2[$key],
				"Visits"		=> (int)$repVisits2[$key]
				);
		}
		return $gaResults2;
	}

	public function getGAMetrics_DaysSinceLastVisit(){

		require_once ("gapi-1.3/gapi.class.php");

		$dimensions = array('daysSinceLastVisit');
		$sort = 'daysSinceLastVisit';
		$toDate = date("Y-m-d"/*, strtotime('- 2 days')*/);
		$fromDate = date("Y-m-d", strtotime('- 90 days'));
		$metrics = array(
			'visits',
			'pageviews',
			'visitors'
			);
		$ga3 = new gapi($this->gaUsername, $this->gaPassword);
		$mostPopular = $ga3->requestReportData($this->profileId, $dimensions, $metrics, $sort, null, $fromDate, $toDate, 1, null);

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

	public function getTrafficSourceInfoPerDay(){
		require_once ("gapi-1.3/gapi.class.php");
		$dimensions = array('date', 'medium');
		$sort = '-date';
		$toDate = date("Y-m-d"/*, strtotime('- 2 days')*/);
		$fromDate = date("Y-m-d", strtotime('- 90 days'));
		$metrics = array('pageviews', 'visits', 'visitors');;
		$ga_TrafficSource = new gapi($this->gaUsername, $this->gaPassword);
		$mostPopular = $ga_TrafficSource->requestReportData($this->profileId, $dimensions, $metrics, $sort, null, $fromDate, $toDate, 1, null);

		foreach($ga_TrafficSource->getResults() as $key => $result){
			echo "<tr>\n";
			$repDate[$key] 			= $result->getDate();
			$repPageview_TS[$key] 		= $result->getPageviews();	
			$repVisits_TS[$key]			= $result->getVisits();
			$repVisitors_TS[$key] 		= $result->getVisitors();
			$repMedium[$key]			= $result->getMedium();
			$numResults 				= $key;
		}

		// Starting to assemble final structures for returning
		$i=0;
		$j=-1;
		$date = "2013-01-01";

		while ($i<=$numResults){ 
			if ($date != $repDate[$i]) { 
				// New Date 
					if ($j>0){
						// Visitors
						if (empty($visitors[$j-1]['Not Set'])) $visitors[$j-1]['Not Set'] = 0;
						if (empty($visitors[$j-1]['Direct'])) 	$visitors[$j-1]['Direct'] = 0;
						if (empty($visitors[$j-1]['Organic'])) $visitors[$j-1]['Organic'] = 0;
						if (empty($visitors[$j-1]['Referral'])) $visitors[$j-1]['Referral'] = 0;
						$visitors[$j-1]['Total'] = 
									$visitors[$j-1]['Not Set'] + $visitors[$j-1]['Direct'] + 
									$visitors[$j-1]['Organic'] + $visitors[$j-1]['Referral'];

						// Visits
						if (empty($visits[$j-1]['Not Set'])) 	$visits[$j-1]['Not Set'] = 0;
						if (empty($visits[$j-1]['Direct'])) 	$visits[$j-1]['Direct'] = 0;
						if (empty($visits[$j-1]['Organic'])) 	$visits[$j-1]['Organic'] = 0;
						if (empty($visits[$j-1]['Referral'])) 	$visits[$j-1]['Referral'] = 0;
						$visits[$j-1]['Total'] = 
									$visits[$j-1]['Not Set'] + $visits[$j-1]['Direct'] + 
									$visits[$j-1]['Organic'] + $visits[$j-1]['Referral'];

						// Pageviews
						if (empty($pageviews[$j-1]['Not Set'])) 	$pageviews[$j-1]['Not Set'] = 0;
						if (empty($pageviews[$j-1]['Direct'])) 		$pageviews[$j-1]['Direct'] = 0;
						if (empty($pageviews[$j-1]['Organic'])) 	$pageviews[$j-1]['Organic'] = 0;
						if (empty($pageviews[$j-1]['Referral'])) 	$pageviews[$j-1]['Referral'] = 0;
						$pageviews[$j-1]['Total'] = 
									$pageviews[$j-1]['Not Set'] + $pageviews[$j-1]['Direct'] + 
									$pageviews[$j-1]['Organic'] + $pageviews[$j-1]['Referral'];

					}
				$date = $repDate[$i];
				$j++;	
				$visitors[] = array(
						"Date" 		=> "",
						"Direct"	=> "",
						"Referral"	=> "",
						"Organic"	=> "",
						"Not Set"	=> "",
						"Total"		=> ""
									);
				$visits[] = array(
						"Date" 		=> "",
						"Direct"	=> "",
						"Referral"	=> "",
						"Organic"	=> "",
						"Not Set"	=> "",
						"Total"		=> ""
									);
				$pageviews[] = array(
						"Date" 		=> "",
						"Direct"	=> "",
						"Referral"	=> "",
						"Organic"	=> "",
						"Not Set"	=> "",
						"Total"		=> ""
									);

				$visitors[$j]['Date'] 	= $repDate[$i]; 
				$visits[$j]['Date'] 	= $repDate[$i]; 
				$pageviews[$j]['Date'] 	= $repDate[$i]; 
			}
			switch ($repMedium[$i]) {
				case '(not set)':
					$visitors[$j]['Not Set'] 	= $repVisitors_TS[$i];
					$visits[$j]['Not Set'] 		= $repVisits_TS[$i];
					$pageviews[$j]['Not Set'] 	= $repPageview_TS[$i];
					break;		
				case '(none)':
					$visitors[$j]['Direct'] 	= $repVisitors_TS[$i];
					$visits[$j]['Direct'] 		= $repVisits_TS[$i];
					$pageviews[$j]['Direct'] 	= $repPageview_TS[$i];
					break;
				case 'referral':
					$visitors[$j]['Referral'] 	= $repVisitors_TS[$i]; 
					$visits[$j]['Referral']   	= $repVisits_TS[$i]; 
					$pageviews[$j]['Referral'] 	= $repPageview_TS[$i];
					break;				
				case 'organic':
					$visitors[$j]['Organic'] 	= $repVisitors_TS[$i]; 
					$visits[$j]['Organic'] 		= $repVisits_TS[$i]; 
					$pageviews[$j]['Organic'] 	= $repPageview_TS[$i];
					break;
			}
			$i++;
		}

		// Totaling the last item
		$visitors[$j]['Total'] = 
				$visitors[$j]['Not Set'] + $visitors[$j]['Direct'] + 
				$visitors[$j]['Organic'] + $visitors[$j]['Referral'];

		$visits[$j]['Total'] = 
				$visits[$j]['Not Set'] + $visits[$j]['Direct'] + 
				$visits[$j]['Organic'] + $visits[$j]['Referral'];

		$pageviews[$j]['Total'] = 
				$pageviews[$j]['Not Set'] + $pageviews[$j]['Direct'] + 
				$pageviews[$j]['Organic'] + $pageviews[$j]['Referral'];

	
		$returningArray = array(
					"Visitors" 	=> $visitors,
					"Visits"	=> $visits,
					"Pageviews"	=> $pageviews);

		return $returningArray;
	}
}



?>