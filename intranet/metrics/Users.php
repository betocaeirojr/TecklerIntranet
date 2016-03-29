<?php

date_default_timezone_set('America/Sao_Paulo');


/* ******************************
* Audience Class is responsible for all metrics related to Audience.
* Pageviews, Unique Visitors, etc.
* It includes both info of Teckler and of Google Analytics.
*/
class Users{

	public $conn;
	public $activeDebug=FALSE;
	public $newUsersPerDay;
	public $newProfilesPerDay;
	public $loggedUsersPerDay; 
	public $totalVisitorsPerDay_Total;
	public $totalVisitorsPerDay_NewUser;
	public $bounceRateNewUserPage;

	private $sql_statements_array  = array(
							"NewUsersPerDay" 		=> "select date(USER_CREATION_DATE) as CreationDate, count(USER_ID) as NumUsers  
													from TECKLER.USER 
													group by date(USER_CREATION_DATE) 
													order by date(USER_CREATION_DATE) DESC",
							"NewUsersPerMonth" 		=> "select date(USER_CREATION_DATE) as CreationDate, count(USER_ID) as NumUser 
													from TECKLER.USER 
													group by month(USER_CREATION_DATE) 
													order by month(USER_CREATION_DATE) DESC limit 12", 
							"NewProfilesPerDay"		=> "select date(PROFILE_CREATION_DATE) as ProfileCreationDate, count(PROFILE_ID) as NumProfiles 
													from TECKLER.PROFILE 
													group by date(PROFILE_CREATION_DATE) order by date(PROFILE_CREATION_DATE) DESC", 
							"NewProfilesPerMonth"	=> "select date(PROFILE_CREATION_DATE) as ProfileCreationDate, count(PROFILE_ID) as NumProfiles 
													from TECKLER.PROFILE 
													group by month(PROFILE_CREATION_DATE) order by month(PROFILE_CREATION_DATE) DESC limit 12", 
							"ActiveProfiles"		=> "select count(distinct PROFILE_ID) as NumActiveProfiles 
													from TECKLER.POST 
													where date(CREATION_DATE) > CURDATE() - INTERVAL 90 DAY",
							"LoggedUsersPerDay"		=> "select date(LAST_LOGIN_DATE) as LastLoginDate, count(distinct USER_ID) as NumUsersLogged
													from TECKLER.USER_LOGIN_INFO 
													group by date(LAST_LOGIN_DATE) order by date(LAST_LOGIN_DATE) DESC",
							"LoggedUsersPerMonth"	=> "",
							"ContactImportsPerDay"	=> "select date(CREATION_DATE) as ImportingDate, count(CONTACT_ID) as NumContacts
													from TECKLER.USER_EXTERNAL_CONTACT group by date(CREATION_DATE) order by date(CREATION_DATE) DESC", 
							"ContentCreatorsPerDay"	=> "select date(a.DATE) as ReferenceDate, (a.NUM_CONTENT_CREATORS/b.TOTAL_VISITORS_DAY) as ContentCreatorsRatio, 
															a.NUM_CONTENT_CREATORS as ContentCreators, b.TOTAL_VISITORS_DAY as TotalVisitors  
														from DELTA_METRICS_CONTENT_CREATORS_DAY a, DELTA_METRICS_GOOGLE_ANALYTICS_DAY b 
														where date(a.DATE) = date(b.DATE) order by date(a.DATE) DESC"
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
	public function getNewProfilesPerDay(){
		$metricData = $this->getMetricData('NewProfilesPerDay');
		$this->newProfilesPerDay = $metricData;
		return $metricData;
	}

	public function getNewProfilesPerMonth(){
		$metricData = $this->getMetricData('NewProfilesPerMonth');
		return $metricData;
	}

	public function getNewUsersPerDay(){
		$metricData = $this->getMetricData('NewUsersPerDay');
		$this->newUsersPerDay = $metricData;
		return $metricData;
	}

	public function getNewUsersPerMonth(){
		$metricData = $this->getMetricData('NewUsersPerMonth');
		return $metricData;
	}

	public function getLoggedUsersPerDay(){
		$metricData = $this->getMetricData('LoggedUsersPerDay');
		$this->loggedUsersPerDay = $metricData;
		return $metricData;
	}

	public function getContactImportingPerDay(){
		$metricData = $this->getMetricData('ContactImportsPerDay');
		return $metricData;
	}

	public function getActiveProfiles(){
		$todayis = date("Y-m-d");
		for ($i = strtotime('2013-08-09') ; $i <= strtotime($todayis) ; $i = $i + (60 * 60 * 24) ){
			$referenceDate = date('Y-m-d', $i);
			$sql = "select '".$referenceDate ."' as ReferenceDate, count(distinct PROFILE_ID) as NumActiveProfiles 
					from TECKLER.POST 
					where 
						date(PUBLISH_DATE) < '" . $referenceDate . "' and 
						date(PUBLISH_DATE) > '" . $referenceDate . "' - INTERVAL 90 DAY";
		    
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
			
		}

		return $data;
	}

	public function getTotalVisitors($prPage=''){
		
		require_once ("gapi-1.3/gapi.class.php");

		$dimensions = array('date');
		$sort = '-date';
		$toDate = date("Y-m-d"/*, strtotime('- 2 days')*/);
		$fromDate = date("Y-m-d", strtotime('- 90 days'));
		$metrics = array(
			'visits',
			'visitors'
			);
		$filter = ($prPage != 'NewUser' ? null : 'pagePath=~ (\/user\/new_user)$');
		$gaVisitors = new gapi($this->gaUsername, $this->gaPassword);
		$mostPopular = $gaVisitors->requestReportData($this->profileId, $dimensions, $metrics, $sort, $filter, $fromDate, $toDate, 1, null);

		foreach($gaVisitors->getResults() as $key => $result){		 
			$repDate_Visitors[$key] 				= $result->getDate();
			$repVisits_Visitors[$key]				= $result->getVisits();
			$repVisitors_Visitors[$key]				= $result->getVisitors();
			// Assembling returning data structure	
			$gaResults_Visitors[] = array(
				"Reference Date" 	=> date('Y-m-d', strtotime($repDate_Visitors[$key])),
				"Visits"			=> (int)$repVisits_Visitors[$key],
				"Visitors"			=> (int)$repVisitors_Visitors[$key]
				);
		}

		if ($prPage!='NewUser') {
			$this->totalVisitorsPerDay_Total = $gaResults_Visitors;
		} else {
			$this->totalVisitorsPerDay_NewUser = $gaResults_Visitors;
		}
		return $gaResults_Visitors;
	}

	public function getPercLoggedPerTotalVisitors(){
		arsort($this->loggedUsersPerDay);
		arsort($this->totalVisitorsPerDay_Total);
		//arsort($this->totalVisitorsPerDay_NewUser);
		for ($j=0; $j<80; $j++ ){
			if ($this->loggedUsersPerDay[$j]['LastLoginDate'] == 
				$this->totalVisitorsPerDay_Total[$j]['Reference Date'] )  {
				$resultingMetric[$j]['date'] 						= $this->loggedUsersPerDay[$j]['LastLoginDate'];
				$resultingMetric[$j]['NumLoggedUsers'] 				= $this->loggedUsersPerDay[$j]['NumUsersLogged'];
				$resultingMetric[$j]['NumVisitorsTotal'] 			= $this->totalVisitorsPerDay_Total[$j]['Visitors'];
				$resultingMetric[$j]['PercLoggedPerTotalVisitors'] 		= ( $resultingMetric[$j]['NumVisitorsTotal']   != 0 ? round($resultingMetric[$j]['NumLoggedUsers'] / $resultingMetric[$j]['NumVisitorsTotal'], 6) 	: 0 );
				$resultingMetric[$j]['NumVisitsTotal'] 				= $this->totalVisitorsPerDay_Total[$j]['Visits'];
				$resultingMetric[$j]['PercLoggedPerTotalVisits'] 		= ( $resultingMetric[$j]['NumVisitsTotal']   != 0 ? round($resultingMetric[$j]['NumLoggedUsers'] / $resultingMetric[$j]['NumVisitsTotal'],6)   : 0) ;
				$resultingMetric[$j]['NumVisitorsNewUser'] 			= $this->totalVisitorsPerDay_NewUser[$j]['Visitors'];
				$resultingMetric[$j]['PercLoggedPerNewUsersVisitors'] 	= ( $resultingMetric[$j]['NumVisitorsNewUser'] != 0 ? round($resultingMetric[$j]['NumLoggedUsers'] / $resultingMetric[$j]['NumVisitorsNewUser'], 6) : 0 );
				$resultingMetric[$j]['NumVisitsNewUser'] 			= $this->totalVisitorsPerDay_NewUser[$j]['Visits'];
				$resultingMetric[$j]['PercLoggedPerNewUserVisits'] 		= ( $resultingMetric[$j]['NumVisitsNewUser'] != 0 ? round($resultingMetric[$j]['NumLoggedUsers'] / $resultingMetric[$j]['NumVisitsNewUser'],6) : 0 );
			} else {
				echo "[DEBUG] - Teckler Reference date is 		: " . $this->loggedUsersPerDay[$j]['LastLoginDate']. "<BR>\n";
				echo "<pre>"; print_r($this->loggedUsersPerDay[$j]) ; echo "</pre>";
				echo "[DEBUG] - GA Reference date is 	: " . $this->totalVisitorsPerDay_Total[$j]['Reference Date'] . "<BR>\n";
				echo "<pre>"; print_r($this->totalVisitorsPerDay_Total[$j]) ; echo "</pre>";
			}
		}
		return $resultingMetric;

	}

	public function getGAMetricsNewUserPageBounceRate(){

		require_once ("gapi-1.3/gapi.class.php");

		$dimensions = array('date');
		$sort = '-date';
		$toDate = date("Y-m-d"/*, strtotime('- 2 days')*/);
		$fromDate = date("Y-m-d", strtotime('- 90 days'));
		$metrics = array(
			'visitBounceRate',
			'pageviews',
			'visits'
			);
		$filter = 'pagePath=~ (\/user\/new_user)$';
		$ga_Bounce = new gapi($this->gaUsername, $this->gaPassword);
		$mostPopular = $ga_Bounce->requestReportData($this->profileId, $dimensions, $metrics, $sort, $filter, $fromDate, $toDate, 1, 90);

		foreach($ga_Bounce->getResults() as $key => $result){
			$repDate_Bounce[$key] 		= $result->getDate();
			$repBounces_Bounce[$key]	= $result->getVisitBounceRate();
			$repPageviews_Bounce[$key]	= $result->getPageviews();
			$repVisits_Bounce[$key]		= $result->getVisits();
			// Assembling returning data structure	
			$gaResults_Bounce[] = array(
				"Reference Date" 		=> date('Y-m-d', strtotime($repDate_Bounce[$key])),
				"Visit Bounces Rate (%)"=> (int)$repBounces_Bounce[$key],
				"Pageviews"				=> (int)$repPageviews_Bounce[$key],
				"Visits"				=> (int)$repVisits_Bounce[$key]
				);
		}
		$this->bounceRateNewUserPage = $gaResults_Bounce;
		return $gaResults_Bounce;
	}

	public function getNewUserPageConversionRate(){
		if (isset($this->newUsersPerDay)) {
			arsort($this->newUsersPerDay);	
		} else {
			$this->newUsersPerDay = $this->getNewUsersPerDay();
			arsort($this->newUsersPerDay);	
		}
		arsort($this->bounceRateNewUserPage);
		for ($j=0; $j<80; $j++ ){
			if ($this->newUsersPerDay[$j]['CreationDate'] == 
				$this->bounceRateNewUserPage[$j]['Reference Date'] )  {
				$resultingMetric[$j]['Reference Date'] 						= $this->newUsersPerDay[$j]['CreationDate'];
				$resultingMetric[$j]['Num New Users Day'] 				= $this->newUsersPerDay[$j]['NumUsers'];
				$resultingMetric[$j]['Num Visits Day'] 					= $this->bounceRateNewUserPage[$j]['Visits'];
				$resultingMetric[$j]['Percent New Profiles Per Visits'] 	= ( $this->bounceRateNewUserPage[$j]['Visits'] != 0 ? round($this->newUsersPerDay[$j]['NumUsers'] / $this->bounceRateNewUserPage[$j]['Visits'], 6) : 0 );
			} else {
				echo "[DEBUG] - Teckler Reference date is 		: " . $this->newUsersPerDay[$j]['CreationDate'] . "<BR>\n";
				echo "<pre>"; print_r($this->newUsersPerDay[$j]) ; echo "</pre>";
				echo "[DEBUG] - GA Reference date is 	: " . $this->bounceRateNewUserPage[$j]['Reference Date'] . "<BR>\n";
				echo "<pre>"; print_r($this->bounceRateNewUserPage[$j]) ; echo "</pre>";
			}
		}
		return $resultingMetric;
	}

	public function getContentCreators_TotalVisitorsRatio(){
		$metricData  = $this->getMetricData('ContentCreatorsPerDay');
		return $metricData;
	}

}


?>