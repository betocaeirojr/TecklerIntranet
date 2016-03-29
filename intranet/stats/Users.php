<?php

//require_once "Connection.php";
//include "Utils.php";

date_default_timezone_set('America/Sao_Paulo');

class Users{

	public $conn;
	public $activeDebug=FALSE;

	/* ********************************
	*  @param prDate: date in "YYY-mm-dd" format
	*/
	private function setWhereClauseUntilDate($prWhereDate=''){

		if ( $prWhereDate == "" ) {
			$sql_date = date('Y-m-d');
		} else {
			$sql_date = date('Y-m-d',strtotime($prWhereDate));
		}
		$whereClause = "where date(DATE) <= date('" . $sql_date ."') ";

		return $whereClause;
	}

	private function setWhereClauseAtDate($prDate=''){

		if ( $prDate == "" ) {
			$sql_date = date('Y-m-d');
		} else {
			$sql_date = date('Y-m-d',strtotime($prDate));
		}
		$whereClause = "where date(DATE) = date('" . $sql_date ."') ";

		return $whereClause;
	}

	private function setReturnResultSet($prData, $prRetType){
		if ($prRetType=='array') {
			return $prData;
		} else {
			return json_encode($prData);
		} 
	}

	private function getMetricInfo($prMetric, $prWhereClause=''){
		
		// Initializing SQL Variable;
		$sql='';

		// Create a list of all Metrics and theirs respective SQL...
		
		$sql_metrics_array = array(
								"ConsUsersCount" 					=> "select TOTAL_USERS as MetricValue from CONS_METRICS_PROFILES " 							. $prWhereClause,
								"DeltaUsersCount" 					=> "select DELTA_USERS_DAY as MetricValue from DELTA_METRICS_PROFILES_DAY " 				. $prWhereClause,
								"ConsProfilesCount"					=> "select TOTAL_PROFILES as MetricValue from CONS_METRICS_PROFILES " 						. $prWhereClause,
								"DeltaProfilesCount"				=> "select DELTA_PROFILES_DAY as MetricValue from DELTA_METRICS_PROFILES_DAY " 				. $prWhereClause,
								"ConsActiveProfilesCount" 			=> "select TOTAL_ACTIVE_PROFILES as MetricValue from CONS_METRICS_PROFILES "				. $prWhereClause,
								"DeltaActiveProfilesCount"			=> "select DELTA_ACTIVE_PROFILES_DAY as MetricValue from DELTA_METRICS_PROFILES_DAY " 		. $prWhereClause,
								"ConsAvgProfilesPerUserCount"		=> "select AVG_PROFILES_USER as MetricValue from CONS_METRICS_PROFILES " 					. $prWhereClause,
								"DeltaAvgProfilesPerUserCount"		=> "select AVG_PROFILES_USER_DAY as MetricValue from DELTA_METRICS_PROFILES_DAY " 			. $prWhereClause,
								"ConsAvgActiveProfilesPerUserCount" => "select AVG_ACTIVE_PROFILES_USER as MetricValue from CONS_METRICS_PROFILES " 			. $prWhereClause, 
								"DeltaAvgActiveProfilesPerUserCount" => "select AVG_ACTIVE_PROFILES_USER_DAY as MetricValue from DELTA_METRICS_PROFILES_DAY " 	. $prWhereClause,
								"ConsUsersWith1ProfileCount"		=> "select TOTAL_USERS_W1_PROFILES as MetricValue from CONS_METRICS_PROFILES " 				. $prWhereClause,
								"ConsWavgProfilesPerUserCount"		=> "select WAVG_PROFILES_USERS as MetricValue from CONS_METRICS_PROFILES " 					. $prWhereClause,
								"DeltaLoggedUsersCount"				=> "select TOTAL_UNIQUE_VISITOR_DAY as MetricValue from CONS_METRICS_ONLINE_USERS " 		. $prWhereClause,
								"ConsUsersWithKeepMeLogged"		 	=> "select TOTAL_USERS_KEEPMELOGGED as MetricValue from CONS_METRICS_ONLINE_USERS " 		. $prWhereClause,
								"ConsUsersPerLangCount"				=> "select * from CONS_METRICS_USERS_LANG " 												. $prWhereClause,
								"DeltaUsersPerLangCount"			=> "select * from DELTA_METRICS_USERS_LANG_DAY  " 											. $prWhereClause,
								"ConsEngagedProfiles"				=> "select TOTAL_ENGAGED_PROFILES as MetricValue from CONS_METRICS_PROFILES " 				. $prWhereClause,
								"ConsUsersRawInfo"					=> "select * from CONS_METRICS_PROFILES " 													. $prWhereClause,
								"DeltaUsersRawInfo"					=> "select * from DELTA_METRICS_PROFILES_DAY " 												. $prWhereClause
							);
		

		// Check Which Metric Should be Fetched
		switch ($prMetric) {
			
			// Users Metrics
			case 'ConsUsersCount':
				$sql = $sql_metrics_array['ConsUsersCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'DeltaUsersCount':
				$sql = $sql_metrics_array['DeltaUsersCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;
			
			// Profiles Metrics
			case 'ConsProfilesCount':
				$sql = $sql_metrics_array['ConsProfilesCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Profiles Count...<br>\n";
				}
				break;	
			case 'DeltaProfilesCount':
				$sql = $sql_metrics_array['DeltaProfilesCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Profiles Count...<br>\n";
				}
				break;	
			
			// Active Profile Metrics
			case 'ConsActiveProfilesCount':
				$sql = $sql_metrics_array['ConsActiveProfilesCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Active Profiles Count...<br>\n";
				}
				break;	
			case 'DeltaActiveProfilesCount':
				$sql = $sql_metrics_array['DeltaActiveProfilesCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Active Profiles Count...<br>\n";
				}
				break;	
			
			//Average Number of Profiles per User
			case 'ConsAvgProfilesPerUserCount':
				$sql = $sql_metrics_array['ConsAvgProfilesPerUserCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Average Profiles Per User Count...<br>\n";
				}
				break;	
			case 'DeltaAvgProfilesPerUserCount':
				$sql = $sql_metrics_array['DeltaAvgProfilesPerUserCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Average Profiles Per User Count...<br>\n";
				}
				break;	
			
			// Average Number of Active Profiles Per User
			case 'ConsAvgActiveProfilesPerUserCount':
				$sql = $sql_metrics_array['ConsAvgActiveProfilesPerUserCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Average Active Profiles Per User Count...<br>\n";
				}
				break;	
			case 'DeltaAvgActiveProfilesPerUserCount':
				$sql = $sql_metrics_array['DeltaAvgActiveProfilesPerUserCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Active Profiles Per User Count...<br>\n";
				}
				break;	
			
			// Number of Users with only 1 profile
			case 'ConsUsersWith1ProfileCount':
				$sql = $sql_metrics_array['ConsUsersWith1ProfileCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users With only 1 Profile Count...<br>\n";
				}
				break;	
			
			// Weighted Average of Profiles Per User
			case 'ConsWavgProfilesPerUserCount':
				$sql = $sql_metrics_array['ConsWavgProfilesPerUserCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Weighted Profiles Per User Count...<br>\n";
				}
				break;	
			
			// Logged Users in a Given Day
			case 'DeltaLoggedUsersCount':
				$sql = $sql_metrics_array['DeltaLoggedUsersCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Daily Logged Users Count...<br>\n";
				}
				break;	
			
			// Number of Users with Keep Me Looged On
			case 'ConsUsersWithKeepMeLogged':
				$sql = $sql_metrics_array['ConsUsersWithKeepMeLogged'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users With Keep Me Logged On Count...<br>\n";
				}
				break;	
			
			// Users Per Lang
			case 'ConsUsersPerLangCount':
				$sql = $sql_metrics_array['ConsUsersPerLangCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users per Lang Count...<br>\n";
				}
				break;	
			case 'DeltaUsersPerLangCount':
				$sql = $sql_metrics_array['DeltaUsersPerLangCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Per Lang Count...<br>\n";
				}
				break;

			// Engaged Profiles
			case 'ConsEngagedProfiles':
				$sql = $sql_metrics_array['ConsEngagedProfiles'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Per Lang Count...<br>\n";
				}
				break;
			
			// Users Raw Information
			case 'ConsUsersRawInfo':
				$sql = $sql_metrics_array['ConsUsersRawInfo'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Consolidated Users Raw Information...<br>\n";
				}
				break;	
			case 'DeltaUsersRawInfo':
				$sql = $sql_metrics_array['DeltaUsersRawInfo'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Raw Information ...<br>\n";
				}
				break;
			
			default:
				break;
		}

		// Prepare to Execute Metric SQL
		if (!empty($sql)) {
			if ($this->activeDebug) {
				echo "[DEBUG] -- The SQL is: " . $sql . "<BR>\n";
			}
			$result = $this->conn->getConnection()->query($sql);		
			if (!$result) return FALSE;
			try {
				while ($row = $result->fetch_assoc()) {
	        		//echo "[DEBUG] -- VERBOSITY LEVEL : SUPER::: <br>\n"; echo "<PRE>"; print_r($row); echo "</PRE>";
	        		// If the metrics (below) return an multivalue array
	        		if (	$prMetric == "ConsUsersPerLangCount" 	|| 
	        				$prMetric == "DeltaUsersPerLangCount" 	|| 
	        				$prMetric == "ConsUsersRawInfo" 		|| 
	        				$prMetric == "DeltaUsersRawInfo"		) {
	        			$MetricValue[] = $row;

	        		} // Else, the metrics return a single value metric 
	        		else {
	        			$MetricValue = $row['MetricValue'];
	        		}
	    		}
			} catch(Exception $e) {
				die("Something went wrong. Check the exception " . $e->getMessage() . "\n" );
			} 	
			return $MetricValue;
		} else {
			return 0;
		}
	}

	public function __construct($prConn) {

		$this->conn = $prConn;
		return $this->conn;
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'. Will get info from the until this date (including the informed day)
	* @param prCount: Type of counting (Consolidated or Delta Metric)
	* @param prPeriod: Consolidated info (day, week, month)
	* @param prRetType: Returning type (array or json)
	* @param prOrder: Ordering of the information (desc or asc)
	*/
	public function getUsersRawData($prRetType='array', $prCount='cons', $prDate='', $prGroup='day', $prOrder='desc'){

		// Preparing SQL Statement
		if ($prGroup=='') {
			$whereStatement 	= $this->setWhereClauseAtDate($prDate);
			$GroupByStatement = "";
			$OrderByStatement = "";
		} else {
			$whereStatement 	= $this->setWhereClauseUntilDate($prDate);
			if ($prGroup =='day') {
				$GroupByStatement = " group by date(DATE) ";
				$OrderByStatement = " order by date(DATE) " . $prOrder;
			} elseif ($prGroup == 'month'){
				$GroupByStatement = " group by month(DATE) ";
				$OrderByStatement = " order by month(DATE) " . $prOrder;
			}
		}
		$whereStatement 	= $whereStatement . $GroupByStatement . $OrderByStatement;
		
		// Get Metric
		if ( strtolower($prCount) == 'cons') {
			$UsersData = $this->getMetricInfo('ConsUsersRawInfo', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaUsersRawInfo', $whereStatement);
		}

		// Preparing the Returning ResultSet
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getUsersCount($prRetType='array', $prCount='cons', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ( strtolower($prCount) == 'cons') {
			$UsersData = $this->getMetricInfo('ConsUsersCount', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaUsersCount', $whereStatement);
		}

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType); 

	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getProfilesCount($prRetType='array', $prCount='cons', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ( strtolower($prCount) == 'cons') {
			$UsersData = $this->getMetricInfo('ConsProfilesCount', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaProfilesCount', $whereStatement);
		}

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}
	 
	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getActiveProfilesCount($prRetType='array', $prCount='cons', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ( strtolower($prCount) == 'cons') {
			$UsersData = $this->getMetricInfo('ConsActiveProfilesCount', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaActiveProfilesCount', $whereStatement);
		}

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getAverageProfilesPerUser($prRetType='array', $prCount='cons', $prDate=''){
		
		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ( strtolower($prCount) == 'cons') {
			$UsersData = $this->getMetricInfo('ConsAvgProfilesPerUserCount', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaAvgProfilesPerUserCount', $whereStatement);
		}	

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getAverageActiveProfilesPerUser($prRetType='array', $prCount='cons', $prDate=''){
		
		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ( strtolower($prCount) == 'cons') {
			$UsersData = $this->getMetricInfo('ConsAvgActiveProfilesPerUserCount', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaAvgActiveProfilesPerUserCount', $whereStatement);
		}	

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getUsersWithOnly1Profile($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsUsersWith1ProfileCount', $whereStatement);
	
		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getWeightedAverageProfilesPerUser($prRetType='array', $prDate=''){
		
		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsWavgProfilesPerUserCount', $whereStatement);
			
		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}
 
	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'. Will get info from the until this date (including the informed day)
	* @param prCount: Type of counting (Consolidated or Delta Metric)
	* @param prPeriod: Consolidated info (day, week, month)
	* @param prRetType: Returning type (array or json)
	* @param prOrder: Ordering of the information (desc or asc)
	*/
	public function getUsersPerLanguage($prRetType='array', $prCount='cons', $prDate=''){

		// Preparing SQL Statement
		$whereStatement 	= $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ( strtolower($prCount) == 'cons') {
			$UsersData = $this->getMetricInfo('ConsUsersPerLangCount', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaUsersPerLangCount', $whereStatement);
		}
		// Preparing the Returning ResultSet
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	/* **************
	* @param prDate: date in format of 'YYYY-mm-dd'. Will get info from the until this date (including the informed day)
	* @param prCount: Type of counting (Consolidated or Delta Metric)
	* @param prPeriod: Consolidated info (day, week, month)
	* @param prRetType: Returning type (array or json)
	* @param prOrder: Ordering of the information (desc or asc)
	*/
	public function getEngagedProfiles($prRetType='array', $prDate=''){

		// Preparing SQL Statement
		$whereStatement 	= $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsEngagedProfiles', $whereStatement);
		
		// Preparing the Returning ResultSet
		return $this->setReturnResultSet($UsersData, $prRetType);
	}


	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'. Will get info from the until this date (including the informed day)
	* @param prCount: Type of counting (Consolidated or Delta Metric)
	* @param prRetType: Returning type (array or json)
	*/
	public function getLoggedUsers($prRetType='array', $prDate=''){
		
		// Preparing SQL Statement
		$whereStatement 	= $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('DeltaLoggedUsersCount', $whereStatement);		

		// Preparing the Returning ResultSet
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'. Will get info from the until this date (including the informed day)
	* @param prCount: Type of counting (Consolidated or Delta Metric)
	* @param prRetType: Returning type (array or json)
	*/
	public function getUsersWithAutoLogin($prRetType='array', $prDate=''){
		
		// Preparing SQL Statement
		$whereStatement 	= $this->setWhereClauseAtDate($prDate);
		
		// Get Metric
		$UsersData = $this->getMetricInfo('ConsUsersWithKeepMeLogged', $whereStatement);		

		// Preparing the Returning ResultSet
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	// ////////////////////////////////////////////////////////////
	//
	// Methods for Metrics Dashboards
	//
	// ////////////////////////////////////////////////////////////

	public function getDashboardMetric_NewProfilesDay(){
		$sql = 
			"select 
				date(DATE) as ReferenceDay, 
				DELTA_PROFILES_DAY as NewProfiles 
			from 
				DELTA_METRICS_PROFILES_DAY 
			order by 
				date(DATE) DESC 
			limit 1";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dataNewProfilesDay = $row['NewProfiles'];
		}
		return $dataNewProfilesDay;
	}

	public function getDashboardMetric_NewUsersDay(){
		$sql = 
			"select 
				date(DATE) as ReferenceDay, 
				DELTA_USERS_DAY as NewUsers 
			from 
				DELTA_METRICS_PROFILES_DAY 
			order by 
				date(DATE) DESC 
			limit 1";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dataNewProfilesDay = $row['NewUsers'];
		}
		return $dataNewProfilesDay;
	}

	public function getDashboardMetric_NewProfilesMonth(){
		$sql = 
			"select 
				date(DATE) as ReferenceDay, 
				sum(DELTA_PROFILES_DAY) as NewProfiles 
			from 
				DELTA_METRICS_PROFILES_DAY 
			group by 
				year(DATE), month(DATE) 
			order by 
				year(DATE) DESC, month(DATE) DESC 
			limit 1";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dataNewProfilesMonth = $row['NewProfiles'];
		}
		return $dataNewProfilesMonth;	
	}

	public function getDashboardMetric_ActiveProfiles(){
		$sql = 
			"select 
				date(DATE) as ReferenceDay, 
				TOTAL_ENGAGED_PROFILES as ActiveProfiles 
			from 
				CONS_METRICS_PROFILES 
			order by date(DATE) DESC
			limit 1";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dataActiveProfiles = $row['ActiveProfiles'];
		}
		return $dataActiveProfiles;
	}

	public function getDashboardMetric_TotalVisitorsNewUsersDay(){
		$sql = 
			"select 
				date(DATE) as ReferenceDate,
				TOTAL_VISITORS_DAY as NumTotalVisitorsNewUsers 
			from 
				DELTA_METRICS_GA_NEW_USER_DAY
			order by date(DATE) DESC
			limit 1";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dataNumTotalVisitorsNewUsers = $row['NumTotalVisitorsNewUsers'];
		}
		return ($dataNumTotalVisitorsNewUser != '0' ? $dataNumTotalVisitorsNewUsers : 1);		
	}

	public function getDashboardMetric_TotalVisitorsDay(){
		$sql = 
			"select 
				date(DATE) as ReferenceDate,
				TOTAL_VISITORS_DAY as NumTotalVisitors 
			from 
				DELTA_METRICS_GOOGLE_ANALYTICS_DAY
			where TOTAL_VISITORS_DAY > 0
			order by date(DATE) DESC
			limit 1";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dataNumTotalVisitors = $row['NumTotalVisitors'];
		}
		return ($dataNumTotalVisitors != '0' ? $dataNumTotalVisitors : 1);		
	}

	public function getDashboardMetric_LoggedUsersDay(){
		$sql = 
			"select 
				date(DATE) as ReferenceDay, 
				TOTAL_UNIQUE_VISITOR_DAY as NumLoggedUsers 
			from 
				CONS_METRICS_ONLINE_USERS 
			where TOTAL_UNIQUE_VISITOR_DAY > 0 
			order by date(DATE) DESC
			limit 1";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dataNumLoggedUsers = $row['NumLoggedUsers'];
		}
		return $dataNumLoggedUsers;
	}
		
	public function getDashboardMetric_NewUserBounceRateDay(){
		$sql = 
			"select 
				date(DATE) as ReferenceDate,
				TOTAL_PERC_BOUNCE_DAY as PercentBounceNewUser  
			from 
				DELTA_METRICS_GA_NEW_USER_DAY 
			where TOTAL_PERC_BOUNCE_DAY > 0 
			order by date(DATE) DESC
			limit 1";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dataPercBounceNewUser = $row['PercentBounceNewUser'];
		}
		return ($dataPercBounceNewUser != '0' ? $dataPercBounceNewUser : 1);	
	}

	public function getDashboardMetric_ContactsImportedDay(){
		$sql = 
			"select 
				date(DATE) as ReferenceDate,
				DELTA_IMPORTED_CONTACTS_DAY as ContactsImportedDay 
			from 
				DELTA_METRICS_CONTACTS_INVITATIONS_DAY 
			order by 
				date(DATE) DESC 
			limit 1";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dataContactsImported = $row['ContactsImportedDay'];
		}
		return $dataContactsImported;		
	}

	public function getDashboardMetric_InvitationsSentDay(){
		$sql = 
			"select 
				date(DATE) as ReferenceDate,
				DELTA_INVITATIONS_SENT_DAY as InvitationsSentDay,
				DELTA_INVITATIONS_READ_DAY as InvitationsReadDay,
				DELTA_INVITATIONS_CLICK_DAY as InvitationsClickedDay 
			from 
				DELTA_METRICS_CONTACTS_INVITATIONS_DAY 
			order by 
				date(DATE) DESC 
			limit 1";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dataInvitations = $row;
		}
		return $dataInvitations;		
	}

	public function getDashboardMetric_PercLoggedPerTotalVisitorsDay(){

		$sql = 
			"select 
				date(a.DATE) as ReferenceDay, 
				a.TOTAL_UNIQUE_VISITOR_DAY as NumLoggedUsers, 
				b.TOTAL_VISITORS_DAY as NumTotalVisitors,
				(a.TOTAL_UNIQUE_VISITOR_DAY / b.TOTAL_VISITORS_DAY) as PercLoggedTotalVisitors 
			from 
				CONS_METRICS_ONLINE_USERS a, 
				DELTA_METRICS_GOOGLE_ANALYTICS_DAY b 
			where 
				a.DATE = b.DATE and 
				b.TOTAL_VISITORS_DAY > 0 and 
				a.TOTAL_UNIQUE_VISITOR_DAY > 0 
			order by  
				date(a.DATE) DESC  
			limit 1";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dataPercLoggedVisitors = $row;
		}
		return $dataPercLoggedVisitors;	
	}

}
?>