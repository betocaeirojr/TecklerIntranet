<?php

//require_once "Connection.php";
//include "Utils.php";

date_default_timezone_set('America/Sao_Paulo');

class Tecks{

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
								"ConsTecksCount" 					=> "select TOTAL_TECKS as MetricValue from CONS_METRICS_TECKS " 						. $prWhereClause,
								"DeltaTecksCount" 					=> "select DELTA_TECKS_DAY as MetricValue from DELTA_METRICS_TECKS_DAY " 				. $prWhereClause,
								"ConsPublishedTecksCount"			=> "select TOTAL_PUBLISHED_TECKS as MetricValue from CONS_METRICS_TECKS " 				. $prWhereClause,
								"DeltaPublishedTecksCount" 			=> "select DELTA_PUBLISHED_TECKS_DAY as MetricValue from DELTA_METRICS_TECKS_DAY " 		. $prWhereClause,
								"ConsAvgTecksPerProfileCount"		=> "select AVG_TECKS_PROFILE as MetricValue from CONS_METRICS_TECKS " 					. $prWhereClause,
								"DeltaAvgTecksPerProfileCount"		=> "select AVG_TECKS_PROFILE_DAY as MetricValue from DELTA_METRICS_TECKS_DAY " 			. $prWhereClause,
								"ConsAvgTecksPerActiveProfileCount"	=> "select AVG_TECKS_ACTIVE_PROFILE as MetricValue from CONS_METRICS_TECKS " 			. $prWhereClause,
								"DeltaAvgTecksPerActiveProfileCount"=> "select AVG_TECKS_ACTIVE_PROFILES_DAY as MetricValue from DELTA_METRICS_TECKS_DAY " 	. $prWhereClause,
								"ConsRatioTecksByPublishTecksCount"	=> "select RT_TECKS_PUBTECKS as MetricValue from CONS_METRICS_TECKS " 					. $prWhereClause,
								"DeltaRatioTecksByPublishTecksCount"=> "select RT_TECKS_PUBTECKS_DAY as MetricValue from DELTA_METRICS_TECKS_DAY " 			. $prWhereClause,
								"ConsProfilesWOTecksCount"			=> "select TOTAL_PROFILES_WO_TECKS as MetricValue from CONS_METRICS_TECKS " 			. $prWhereClause,
								"DeltaProfilesWOTecksCount"			=> "select DELTA_PROFILE_WO_TECKS_DAY as MetricValue from DELTA_METRICS_TECKS_DAY " 	. $prWhereClause,
								"ConsTecksPerLangCount"				=> "select * from CONS_METRICS_TECKS_LANG " 											. $prWhereClause,
								"DeltaTecksPerLangCount"			=> "select * from DELTA_METRICS_TECKS_LANG_DAY  " 										. $prWhereClause,
								"ConsTecksPerTypeCount"				=> "select DATE, TOTAL_TECKS_TEXT, TOTAL_TECKS_IMAGE, TOTAL_TECKS_AUDIO, TOTAL_TECKS_VIDEO, TOTAL_TECKS_DOCUMENT from CONS_METRICS_TECKS_TYPE " 								. $prWhereClause,
								"DeltaTecksPerTypeCount"			=> "select DATE, DELTA_TECKS_TEXT_DAY, DELTA_TECKS_IMAGE_DAY, DELTA_TECKS_AUDIO_DAY, DELTA_TECKS_VIDEO_DAY, DELTA_TECKS_DOCUMENT_DAY from DELTA_METRICS_TECKS_TYPE_DAY  " 	. $prWhereClause,
								"ConsTecksRawInfo"					=> "select * from CONS_METRICS_TECKS " 													. $prWhereClause,
								"DeltaTecksRawInfo"					=> "select * from DELTA_METRICS_TECKS_DAY " 											. $prWhereClause
							);
		

		// Check Which Metric Should be Fetched
		switch ($prMetric) {
			
			// Tecks Metrics
			case 'ConsTecksCount':
				$sql = $sql_metrics_array['ConsTecksCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'DeltaTecksCount':
				$sql = $sql_metrics_array['DeltaTecksCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;
			
			// Published Tecks Metrics
			case 'ConsPublishedTecksCount':
				$sql = $sql_metrics_array['ConsPublishedTecksCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'DeltaPublishedTecksCount':
				$sql = $sql_metrics_array['DeltaPublishedTecksCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;	
			
			// Average Tecks Per Profile
			case 'ConsAvgTecksPerProfileCount':
				$sql = $sql_metrics_array['ConsAvgTecksPerProfileCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'DeltaAvgTecksPerProfileCount':
				$sql = $sql_metrics_array['DeltaAvgTecksPerProfileCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;	
			
			// Average Tecks Per Active Profile
			case 'ConsAvgTecksPerActiveProfileCount':
				$sql = $sql_metrics_array['ConsAvgTecksPerActiveProfileCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'DeltaAvgTecksPerActiveProfileCount':
				$sql = $sql_metrics_array['DeltaAvgTecksPerActiveProfileCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;	
			
			// Ratio Tecks vs Published Tecks
			case 'ConsRatioTecksByPublishTecksCount':
				$sql = $sql_metrics_array['ConsRatioTecksByPublishTecksCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'DeltaRatioTecksByPublishTecksCount':
				$sql = $sql_metrics_array['DeltaRatioTecksByPublishTecksCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;	

			// Profiles Without Tecks
			case 'ConsProfilesWOTecksCount':
				$sql = $sql_metrics_array['ConsProfilesWOTecksCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'DeltaProfilesWOTecksCount':
				$sql = $sql_metrics_array['DeltaProfilesWOTecksCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;	

			// Tecks Per Lang
			case 'ConsTecksPerLangCount':
				$sql = $sql_metrics_array['ConsTecksPerLangCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'DeltaTecksPerLangCount':
				$sql = $sql_metrics_array['DeltaTecksPerLangCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;
			
			// Tecks per Teck Type
			case 'ConsTecksPerTypeCount':
				$sql = $sql_metrics_array['ConsTecksPerTypeCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'DeltaTecksPerTypeCount':
				$sql = $sql_metrics_array['DeltaTecksPerTypeCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;

			// Tecks Raw Information
			case 'ConsTecksRawInfo':
				$sql = $sql_metrics_array['ConsTecksRawInfo'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'DeltaTecksRawInfo':
				$sql = $sql_metrics_array['DeltaTecksRawInfo'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
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
	        		if (	$prMetric == "ConsTecksPerLangCount" 	|| 
	        				$prMetric == "DeltaTecksPerLangCount" 	|| 
	        				$prMetric == "ConsTecksRawInfo" 		|| 
	        				$prMetric == "DeltaTecksRawInfo"		|| 
	        				$prMetric == "ConsTecksPerTypeCount"	|| 
	        				$prMetric == "DeltaTecksPerTypeCount") {
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
	public function getTecksRawData($prRetType='array', $prCount='cons', $prDate='', $prGroup='day', $prOrder='desc'){

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
			$UsersData = $this->getMetricInfo('ConsTecksRawInfo', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaTecksRawInfo', $whereStatement);
		}

		// Preparing the Returning ResultSet
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getTecksCount($prRetType='array', $prCount='cons', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ( strtolower($prCount) == 'cons') {
			$UsersData = $this->getMetricInfo('ConsTecksCount', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaTecksCount', $whereStatement);
		}

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType); 
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getPublishedTecksCount($prRetType='array', $prCount='cons', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ( strtolower($prCount) == 'cons') {
			$UsersData = $this->getMetricInfo('ConsPublishedTecksCount', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaPublishedTecksCount', $whereStatement);
		}

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}
	 
	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getRatioTecksByPublishedTecks($prRetType='array', $prCount='cons', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ( strtolower($prCount) == 'cons') {
			$UsersData = $this->getMetricInfo('ConsRatioTecksByPublishTecksCount', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaRatioTecksByPublishTecksCount', $whereStatement);
		}

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}
	
	
	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getAverageTecksPerProfile($prRetType='array', $prCount='cons', $prDate=''){
		
		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ( strtolower($prCount) == 'cons') {
			$UsersData = $this->getMetricInfo('ConsAvgTecksPerProfileCount', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaAvgTecksPerProfileCount', $whereStatement);
		}	

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getAverageTecksPerActiveProfile($prRetType='array', $prCount='cons', $prDate=''){
		
		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ( strtolower($prCount) == 'cons') {
			$UsersData = $this->getMetricInfo('ConsAvgTecksPerActiveProfileCount', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaAvgTecksPerActiveProfileCount', $whereStatement);
		}	

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getProfilesWithoutTeck($prRetType='array', $prCount='cons', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ( strtolower($prCount) == 'cons') {
			$UsersData = $this->getMetricInfo('ConsProfilesWOTecksCount', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaProfilesWOTecksCount', $whereStatement);
		}
	
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
	public function getTecksPerLanguage($prRetType='array', $prCount='cons', $prDate='', $prGroup='', $prOrder='desc'){

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
			$UsersData = $this->getMetricInfo('ConsTecksPerLangCount', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaTecksPerLangCount', $whereStatement);
		}
		// Preparing the Returning ResultSet
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'. Will get info from the until this date (including the informed day)
	* @param prCount: Type of counting (Consolidated or Delta Metric)
	* @param prPeriod: Consolidated info (day, week, month)
	* @param prRetType: Returning type (array or json)
	* @param prOrder: Ordering of the information (desc or asc)
	*/
	public function getTecksPerType($prRetType='array', $prCount='cons', $prDate='', $prGroup='', $prOrder='desc'){

		// Preparing SQL Statement
		if ($prGroup=='') {
			$whereStatement 	= $this->setWhereClauseAtDate($prDate);
			$GroupByStatement = "";
			$OrderByStatement = "";
		} else {
			$whereStatement 	= $this->setWhereClauseUntilDate($prDate);
			if ($prGroup =='day') {
				$GroupByStatement = " group by date(DATE) ";
				$OrderByStatement = " order by date(DATE) " .   $prOrder;
			} elseif ($prGroup == 'month'){
				$GroupByStatement = " group by month(DATE) ";
				$OrderByStatement = " order by month(DATE) " . $prOrder ;
			}
		}

		$whereStatement 	= $whereStatement . $GroupByStatement . $OrderByStatement ;

		// Get Metric
		if ( strtolower($prCount) == 'cons') {
			$UsersData = $this->getMetricInfo('ConsTecksPerTypeCount', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaTecksPerTypeCount', $whereStatement);
		}
		// Preparing the Returning ResultSet
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	public function getDashboardMetric_NewTecksDay(){
		$sql = 
			"select 
				date(DATE) as ReferenceDay, 
				DELTA_PUBLISHED_TECKS_DAY as PublishedTecks 
			from 
				DELTA_METRICS_TECKS_DAY 
			order by date(DATE) DESC 
			limit 1";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dataNewTecksDay = $row['PublishedTecks'];
		}
		return $dataNewTecksDay;

	}


	public function getDashboardMetric_NewTecksMonth(){
		$sql = 
			"select 
				date(DATE) as ReferenceDay, 
				sum(DELTA_PUBLISHED_TECKS_DAY) as PublishedTecks 
			from 
				DELTA_METRICS_TECKS_DAY 
			group by year(DATE), month(DATE) 
			order by year(DATE) DESC,month(DATE) DESC 
			limit 1";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dataNewTecksMonth = $row['PublishedTecks'];
		}
		return $dataNewTecksMonth;

	}



}
?>