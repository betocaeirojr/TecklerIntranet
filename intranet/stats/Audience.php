<?php

//require_once "Connection.php";
//include "Utils.php";


date_default_timezone_set('America/Sao_Paulo');

class Audience{

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
			"DeltaExpectedPageviewsCount" 				=> "select TOTAL_EXPECTED_PAGEVIEWS as MetricValue from CONS_METRICS_PAGEVIEWS_EXPECTED " 			. $prWhereClause,
			"DeltaAveragePageviewsPerTeckCount" 		=> "select AVG_PAGEVIEWS_TECK as MetricValue from CONS_METRICS_PAGEVIEWS_EXPECTED " 				. $prWhereClause,
			"DeltaAveragePageviewsPerProfileCount" 		=> "select AVG_PAGEVIEWS_PROFILE as MetricValue from CONS_METRICS_PAGEVIEWS_EXPECTED " 				. $prWhereClause,
			"DeltaProfilesWOPageviewsCount" 			=> "select TOTAL_PROFILES_WO_PAGEVIEWS as MetricValue from CONS_METRICS_PAGEVIEWS_EXPECTED " 		.  $prWhereClause,
			"DeltaPercProfilesWOPageviewsCount" 		=> "select PERC_PROFILES_WO_PAGEVIEWS as MetricValue from CONS_METRICS_PAGEVIEWS_EXPECTED " 		. $prWhereClause,
			"DeltaTecksWOPageviewsCount"  				=> "select TOTAL_TECKS_WO_PAGEVIEWS as MetricValue from CONS_METRICS_PAGEVIEWS_EXPECTED " 			. $prWhereClause,
			"DeltaPercTecksWOPageviewsCount" 			=> "select PERC_TECKS_WO_PAGEVIEWS	as MetricValue from CONS_METRICS_PAGEVIEWS_EXPECTED " 			. $prWhereClause,
			"DeltaWeightedAveragePageviewsPerTeckCount" => "select WAVG_PAGEVIEWS_VIEWED_TECKS as MetricValue from CONS_METRICS_PAGEVIEWS_EXPECTED " 		. $prWhereClause,
			"DeltaWeightedAveragePageviewsPerProfileCount" => "select WAVG_PAGEVIEWS_VIEWED_PROFILES as MetricValue from CONS_METRICS_PAGEVIEWS_EXPECTED " . $prWhereClause,
			"DeltaPageviewsRawInfo" 					=> "select * from CONS_METRICS_PAGEVIEWS_EXPECTED " 												. $prWhereClause,
			"ConsPageviewsPerTypeRawInfo" 				=> "select * from CONS_METRICS_PAGEVIEWS_TYPE " 													. $prWhereClause,
			"ConsAlexaGlobalRankingInfo"				=> "select * from CONS_METRICS_ALEXA_GLOBAL_DAY "													. $prWhereClause,
			"DeltaPageviewsGADayCount" 					=> "select TOTAL_PAGEVIEWS_DAY as MetricValue from DELTA_METRICS_GOOGLE_ANALYTICS_DAY " 			. $prWhereClause,
			"DeltaPageVisitsGADayCount" 				=> "select TOTAL_PAGES_VISITS_DAY as MetricValue from DELTA_METRICS_GOOGLE_ANALYTICS_DAY " 			. $prWhereClause, 
			"DeltaBounceGADayCount" 					=> "select TOTAL_BOUNCE_DAY as MetricValue from DELTA_METRICS_GOOGLE_ANALYTICS_DAY " 				. $prWhereClause, 
			"DeltaPercBounceGADayCount" 				=> "select TOTAL_PERC_BOUNCE_DAY as MetricValue from DELTA_METRICS_GOOGLE_ANALYTICS_DAY " 			. $prWhereClause, 
			"DeltaVisitorsGADayCount" 					=> "select TOTAL_VISITORS_DAY as MetricValue from DELTA_METRICS_GOOGLE_ANALYTICS_DAY " 				. $prWhereClause,
			"DeltaNewVisitsGADayCount" 					=> "select TOTAL_NEW_VISITORS_DAY as MetricValue from DELTA_METRICS_GOOGLE_ANALYTICS_DAY " 			. $prWhereClause,
			"DeltaPercNewVisitsGADayCount"  			=> "select TOTAL_PERC_NEW_VISITORS_DAY as MetricValue from DELTA_METRICS_GOOGLE_ANALYTICS_DAY " 	. $prWhereClause,
			"DeltaPercReturningVisitsGADayCount"  		=> "select TOTAL_PERC_RETURNING_VISITORS_DAY as MetricValue from DELTA_METRICS_GOOGLE_ANALYTICS_DAY " . $prWhereClause,
			"DeltaAverageTimeOnSiteGADayCount"  		=> "select AVG_TIME_ON_SITE as MetricValue from DELTA_METRICS_GOOGLE_ANALYTICS_DAY " 				. $prWhereClause,
			"DeltaAverageTimeOnPageGADayCount"  		=> "select AVG_TIME_ON_PAGE as MetricValue from DELTA_METRICS_GOOGLE_ANALYTICS_DAY " 				. $prWhereClause,
			"DeltaNumPagesPerVisitGADayCount"  			=> "select TOTAL_NUM_PAGES_PER_VISIT_DAY as MetricValue from DELTA_METRICS_GOOGLE_ANALYTICS_DAY " 	. $prWhereClause,
			"DeltaEntranceBounceRateGADayCount"  		=> "select TOTAL_ENTRANCE_BOUNCE_RATE_DAY as MetricValue from DELTA_METRICS_GOOGLE_ANALYTICS_DAY " 	. $prWhereClause,
			"DeltaGoogleAnalyticsDayRawInfoData"  		=> "select * from DELTA_METRICS_GOOGLE_ANALYTICS_DAY " 												. $prWhereClause, 
			"DeltaAveragePageviewsPerTeckPerDay"		=> "select * from DELTA_METRICS_AVERAGE_PAGEVIEWS_DAY "												. $prWhereClause,
			"DeltaAveragePageviewsPerTeckPerMonth"		=> "select * from DELTA_METRICS_AVERAGE_PAGEVIEWS_MONTH "											. $prWhereClause,
			"ConsAveragePageviewPerTeckAccumulated"		=> "select ((	a.TOTAL_PAGEVIEWS_AUDIO + 
																		a.TOTAL_PAGEVIEWS_VIDEO + 
																		a.TOTAL_PAGEVIEWS_TEXT + 
																		a.TOTAL_PAGEVIEWS_IMAGE) / 
																	(b.TOTAL_PUBLISHED_TECKS)) as MetricValue,
															date(a.DATE) as ReferenceDate  
															from CONS_METRICS_PAGEVIEWS_TYPE a, CONS_METRICS_TECKS b ",
			"DeltaDailyViewsRawInformation" 			=> "select * from DELTA_METRICS_DAILY_VIEWS " 														. $prWhereClause,
			"PageviewsAging"							=> "select * from CONS_AGING_PAGEVIEWS "															. $prWhereClause,
			"AgingDecaiRate"							=> "select * from CONS_METRICS_PAGEVIEWS_DECAI_RATE " 												. $prWhereClause,
		);
		

		// Check Which Metric Should be Fetched
		switch ($prMetric) {
			
			// Expected Pageviews
			case 'DeltaExpectedPageviewsCount':
				$sql = $sql_metrics_array['DeltaExpectedPageviewsCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			
			// Average Pageviews per Teck and Profile
			case 'DeltaAveragePageviewsPerTeckCount':
				$sql = $sql_metrics_array['DeltaAveragePageviewsPerTeckCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;			
			case 'DeltaAveragePageviewsPerProfileCount':
				$sql = $sql_metrics_array['DeltaAveragePageviewsPerProfileCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			
			// Profiles Without Pageviews
			case 'DeltaProfilesWOPageviewsCount':
				$sql = $sql_metrics_array['DeltaProfilesWOPageviewsCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;	
			case 'DeltaPercProfilesWOPageviewsCount':
				$sql = $sql_metrics_array['DeltaPercProfilesWOPageviewsCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			
			// Tecks Without Pageviews
			case 'DeltaTecksWOPageviewsCount':
				$sql = $sql_metrics_array['DeltaTecksWOPageviewsCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;	
			case 'DeltaPercTecksWOPageviewsCount':
				$sql = $sql_metrics_array['DeltaPercTecksWOPageviewsCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			
			// Weighted Average Pageviews per Teck and Profile
			case 'DeltaWeightedAveragePageviewsPerTeckCount':
				$sql = $sql_metrics_array['DeltaWeightedAveragePageviewsPerTeckCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;	
			case 'DeltaWeightedAveragePageviewsPerProfileCount':
				$sql = $sql_metrics_array['DeltaWeightedAveragePageviewsPerProfileCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	

			// Pageviews Raw Information
			case 'DeltaPageviewsRawInfo':
				$sql = $sql_metrics_array['DeltaPageviewsRawInfo'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'ConsPageviewsPerTypeRawInfo':
				$sql = $sql_metrics_array['ConsPageviewsPerTypeRawInfo'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;	
			
			// Alexa Global Information
			case 'ConsAlexaGlobalRankingInfo':
				$sql = $sql_metrics_array['ConsAlexaGlobalRankingInfo'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;
			
			// Google Analytics Numbers
			case 'DeltaGoogleAnalyticsDayRawInfoData':
				$sql = $sql_metrics_array['DeltaGoogleAnalyticsDayRawInfoData'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;
			case 'DeltaPageviewsGADayCount':
				$sql = $sql_metrics_array['DeltaPageviewsGADayCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;
			case 'DeltaPageVisitsGADayCount':
				$sql = $sql_metrics_array['DeltaPageVisitsGADayCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;
			case 'DeltaBounceGADayCount':
				$sql = $sql_metrics_array['DeltaBounceGADayCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;
			case 'DeltaPercBounceGADayCount':
				$sql = $sql_metrics_array['DeltaPercBounceGADayCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;
			case 'DeltaVisitorsGADayCount':
				$sql = $sql_metrics_array['DeltaVisitorsGADayCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;
			case 'DeltaNewVisitsGADayCount':
				$sql = $sql_metrics_array['DeltaNewVisitsGADayCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;
			case 'DeltaPercNewVisitsGADayCount':
				$sql = $sql_metrics_array['DeltaPercNewVisitsGADayCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;
			case 'DeltaPercReturningVisitsGADayCount':
				$sql = $sql_metrics_array['DeltaPercReturningVisitsGADayCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;
			case 'DeltaAverageTimeOnSiteGADayCount':
				$sql = $sql_metrics_array['DeltaAverageTimeOnSiteGADayCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;
			case 'DeltaAverageTimeOnPageGADayCount':
				$sql = $sql_metrics_array['DeltaAverageTimeOnPageGADayCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;
			case 'DeltaNumPagesPerVisitGADayCount':
				$sql = $sql_metrics_array['DeltaNumPagesPerVisitGADayCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;
			case 'DeltaEntranceBounceRateGADayCount':
				$sql = $sql_metrics_array['DeltaEntranceBounceRateGADayCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;

			// Average DailyViews per Tecks & DailyViews
			case 'DeltaAveragePageviewsPerTeckPerDay':
				$sql = $sql_metrics_array['DeltaAveragePageviewsPerTeckPerDay'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;
			case 'DeltaAveragePageviewsPerTeckPerMonth':
				$sql = $sql_metrics_array['DeltaAveragePageviewsPerTeckPerMonth'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;
			case 'ConsAveragePageviewPerTeckAccumulated':
				$sql = $sql_metrics_array['ConsAveragePageviewPerTeckAccumulated'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;				
			case 'DeltaDailyViewsRawInformation':
				$sql = $sql_metrics_array['DeltaDailyViewsRawInformation'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;

			case 'PageviewsAging':
				$sql = $sql_metrics_array['PageviewsAging'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;

			case 'AgingDecaiRate':
				$sql = $sql_metrics_array['AgingDecaiRate'];
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
	        		if (	$prMetric == "DeltaPageviewsRawInfo" 				|| 
	        				$prMetric == "ConsPageviewsPerTypeRawInfo" 			||
	        				$prMetric == "ConsAlexaGlobalRankingInfo"			||
	        				$prMetric == "DeltaGoogleAnalyticsDayRawInfoData" 	|| 
	        				$prMetric == "DeltaAveragePageviewsPerTeckPerDay" 	||
	        				$prMetric == "DeltaAveragePageviewsPerTeckPerMonth" || 
	        				$prMetric == "DeltaDailyViewsRawInformation"		||
	        				$prMetric == "PageviewsAging"						||
	        				$prMetric == "AgingDecaiRate"							) {
	        			$MetricValue[] = $row;

	        		} // Else, the metrics return a single value metric 
	        		else {
	        			//echo "[DEBUG] -- KPI is: " .  $prMetric . "<BR>"; 
	        			$MetricValue = $row['MetricValue'];
	        		}
	        		//echo "------------ DEBUG -----------<br>\n"; echo "<pre>" ; print_r($MetricValue); echo "</pre>" ;
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
	public function getPageviewsRawData($prRetType='array', $prDate='', $prGroup='day', $prOrder='desc'){

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
		$UsersData = $this->getMetricInfo('DeltaPageviewsRawInfo', $whereStatement);
	

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
	public function getPageviewsPerTeckTypeRawData($prRetType='array', $prDate='', $prGroup='day', $prOrder='desc'){

		// Preparing SQL Statement
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
		$UsersData = $this->getMetricInfo('ConsPageviewsPerTypeRawInfo', $whereStatement);
	

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
	public function getAlexaRawData($prRetType='array', $prDate='', $prGroup='day', $prOrder='desc'){

		// Preparing SQL Statement
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
		$UsersData = $this->getMetricInfo('ConsAlexaGlobalRankingInfo', $whereStatement);
	

		// Preparing the Returning ResultSet
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	public function getAveragePageviewsInTecksAccumulated($prRetType='array', $prDate=''){
		// Get Metric
		
		if ($prDate == '') {
			// Preparing SQL Statement acording Class Parameters
			$prDate = date("Y-m-d");
		}

		$whereStatement = "where date(a.DATE) = date(b.PUBLISHED_DATE) and date(a.DATE) = date('". $prDate . "' order by date(a.DATE) DESC";
		$UsersData = $this->getMetricInfo('ConsAveragePageviewPerTeckAccumulated', $whereStatement);	
		
		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType); 

	}

	public function getAveragePageviewsInTecks($prRetType='array', $prGroupingBy='day', $prDate='', $prOrder='desc'){
		// Get Metric
		if ($prGroupingBy == 'day') {
			if ($prDate == '') {
				// Preparing SQL Statement acording Class Parameters
				$whereStatement = $this->setWhereClauseUntilDate($prDate);
			} else {
				$whereStatement = $this->setWhereClauseAtDate($prDate);
			}
			$OrderByStatement = " order by date(DATE) " . $prOrder;
			$whereStatement 	= $whereStatement  . $OrderByStatement;
			$UsersData = $this->getMetricInfo('DeltaAveragePageviewsPerTeckPerDay', $whereStatement);	
		} else {
			if ($prDate == '') {
				// Preparing SQL Statement acording Class Parameters
				$whereStatement = $this->setWhereClauseUntilDate($prDate);
			} else {
				$whereStatement = $this->setWhereClauseAtDate($prDate);
			}
			$OrderByStatement = " order by month(DATE) " . $prOrder;
			$whereStatement 	= $whereStatement  . $OrderByStatement;
			$UsersData = $this->getMetricInfo('DeltaAveragePageviewsPerTeckPerMonth', $whereStatement);	
		}
		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType); 

	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getPageviewsCount($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('DeltaExpectedPageviewsCount', $whereStatement);

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType); 

	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getAveragePageviewsPerTeckCount($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('DeltaAveragePageviewsPerTeckCount', $whereStatement);

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getAveragePageviewsPerProfileCount($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('DeltaAveragePageviewsPerProfileCount', $whereStatement);

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getWeightedAveragePageviewsPerTeckCount($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('DeltaWeightedAveragePageviewsPerTeckCount', $whereStatement);

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getWeightedAveragePageviewsPerProfileCount($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('DeltaWeightedAveragePageviewsPerProfileCount', $whereStatement);

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}
	
	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getTecksWithoutPageviews($prRetType='array', $prDate='', $prType='abs'){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ($prType=='abs') {
			$UsersData = $this->getMetricInfo('DeltaTecksWOPageviewsCount', $whereStatement);
		} else {
			$UsersData = $this->getMetricInfo('DeltaPercTecksWOPageviewsCount', $whereStatement);
		}
	
		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getProfilesWithoutPageviews($prRetType='array', $prDate='', $prType='abs'){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ($prType=='abs') {
			$UsersData = $this->getMetricInfo('DeltaProfilesWOPageviewsCount', $whereStatement);
		} else {
			$UsersData = $this->getMetricInfo('DeltaPercProfilesWOPageviewsCount', $whereStatement);
		}
	
		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	/* ***************************************************    
	* 
	* 	Methods for Fetching Google Analytics Metrics
	* 
	*/
	public function getGoogleAnalyticsRawData($prRetType='array', $prDate='', $prOrder='desc'){

		// Preparing SQL Statement
		$whereStatement 	= $this->setWhereClauseUntilDate($prDate);
		$OrderByStatement = " order by date(DATE) " . $prOrder;
		
		$whereStatement 	= $whereStatement . $OrderByStatement;
		
		// Get Metric
		$UsersData = $this->getMetricInfo('DeltaGoogleAnalyticsDayRawInfoData', $whereStatement);

		// Preparing the Returning ResultSet
		return $this->setReturnResultSet($UsersData, $prRetType);
	}


	public function getUniquePageviewsGA($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('DeltaPageviewsGADayCount', $whereStatement);

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	public function getUniquePageVisitsGA($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('DeltaPageVisitsGADayCount', $whereStatement);

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	public function getUniqueBounceGA($prRetType='array', $prDate='', $prType='abs'){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ($prType=='abs') {
			$UsersData = $this->getMetricInfo('DeltaBounceGADayCount', $whereStatement);
		} else {
			$UsersData = $this->getMetricInfo('DeltaPercBounceGADayCount', $whereStatement);
		} 
		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	public function getUniqueVistorsGA($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('DeltaVisitorsGADayCount', $whereStatement);

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	public function getUniqueNewVisitsGA($prRetType='array', $prDate='', $prType='abs'){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ($prType=='abs') {
			$UsersData = $this->getMetricInfo('DeltaNewVisitsGADayCount', $whereStatement);
		} else {
			$UsersData = $this->getMetricInfo('DeltaPercNewVisitsGADayCount', $whereStatement);
		}

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	public function getPercUniqueReturningVisitsGA($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('DeltaPercReturningVisitsGADayCount', $whereStatement);

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	public function getAverageTimeOnSiteGA($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('DeltaAverageTimeOnSiteGADayCount', $whereStatement);

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	public function getAverageTimeOnPageGA($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('DeltaAverageTimeOnPageGADayCount', $whereStatement);

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	public function getNumberPagesPerVisitGA($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('DeltaNumPagesPerVisitGADayCount', $whereStatement);

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	public function getEntranceBounceRateGA($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('DeltaEntranceBounceRateGADayCount', $whereStatement);

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	public function getDeltaDailyViewsRawInformation($prRetType='array', $prDate=''){
		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseUntilDate($prDate);
		$whereStatement = $whereStatement . " order by date(DATE) DESC ";

		// Get Metric
		$UsersData = $this->getMetricInfo('DeltaDailyViewsRawInformation', $whereStatement);

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);	
	}

	public function getPageviewsAging($prRetType='array', $prDate=''){
		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseUntilDate($prDate);
		$whereStatement = $whereStatement . " order by date(DATE) ASC ";

		// Get Metric
		$AgingData = $this->getMetricInfo('PageviewsAging', $whereStatement);

		// Preparing The Returning Info
		return $this->setReturnResultSet($AgingData, $prRetType);	

	}

	public function getAgingDecaiRate($prRetType='array', $prDate=''){
		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseUntilDate($prDate);
		$whereStatement = $whereStatement . " order by date(DATE) ASC ";

		// Get Metric
		$AgingData = $this->getMetricInfo('AgingDecaiRate', $whereStatement);

		// Preparing The Returning Info
		return $this->setReturnResultSet($AgingData, $prRetType);	

	}

	// /////////////////////////////////////////
	// 
	// Methods for Metrics Dashboard Only
	//
	public function getDashboardMetric_AvgPageviewsTecks_Day(){
		$sql = 
			"select 
				date(DATE) as ReferenceDate,
				AVERAGE_PV_TECKLER as AveragePageviews
			from 
				DELTA_METRICS_AVERAGE_PAGEVIEWS_DAY
			order by date(DATE) DESC
			limit 1";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dataAveragePageviews_Day = $row['AveragePageviews'];
		}
		return ($dataAveragePageviews_Day != '0' ? $dataAveragePageviews_Day : 1);
	}
	
	public function getDashboardMetric_AvgPageviewsTecks_Month(){
		$sql = 
			"select 
				date(DATE) as ReferenceDate,
				AVERAGE_PV_TECKLER as AveragePageviews
			from 
				DELTA_METRICS_AVERAGE_PAGEVIEWS_MONTH
			order by date(DATE) DESC
			limit 1";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dataAveragePageviews_Month = $row['AveragePageviews'];
		}
		return ($dataAveragePageviews_Month != '0' ? $dataAveragePageviews_Month : 1);
	}

	public function getDashboardMetric_AvgPageviewsTecks_Accumulated(){
		$sql = 
			"select
				a.RefDate as ReferenceDate, 
				(a.TotalPageviews / b.TotalPublishedTecks) as AvgPageviewsPerTeck_Accumulated 
			from 
			(select 
				(TOTAL_PAGEVIEWS_AUDIO + 
					TOTAL_PAGEVIEWS_IMAGE + 
					TOTAL_PAGEVIEWS_TEXT + 
					TOTAL_PAGEVIEWS_VIDEO) AS TotalPageviews, 
				date(DATE) as RefDate 
				from CONS_METRICS_PAGEVIEWS_TYPE 
				group by date(DATE) 
				order by date(DATE) DESC limit 1) a, 
			(select  
				TOTAL_PUBLISHED_TECKS as TotalPublishedTecks, 
				date(DATE) as RefDate 
				from CONS_METRICS_TECKS 
				group by date(DATE) 
				order by date(DATE) DESC limit 1) b 
			where 
				a.RefDate = b.RefDate;";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dataAveragePageviews_Accumulated = $row['AvgPageviewsPerTeck_Accumulated'];
		}
		return ($dataAveragePageviews_Accumulated != '0' ? $dataAveragePageviews_Accumulated : 1);
	}

	public function getDashboardMetric_AgingPageviews(){
		$sql = 
			"select 
				(sum(P1)) as P1,
				(sum(P2) / sum(P1)) as Decai_P2_P1,
				(sum(P3) / sum(P2)) as Decai_P3_P2,
				(sum(P4) / sum(P3)) as Decai_P4_P3,
				(sum(P5) / sum(P4)) as Decai_P5_P4,
				(sum(P6) / sum(P5)) as Decai_P6_P5 
			from 
				CONS_AGING_PAGEVIEWS";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dataAgingPageviews = $row;
		}
		return $dataAgingPageviews;
	}

	public function getDashboardMetric_NewVsRet_PPVisit_Bounce_Day(){
		$sql = 
			"select 
				date(DATE) as ReferenceDate,
				TOTAL_VISITORS_DAY as TotalVisitorsAtDay,
				TOTAL_PAGES_VISITS_DAY as TotalPagesVisitsAtDay,
				TOTAL_PERC_BOUNCE_DAY as PercentBounceAtDay, 
				TOTAL_PERC_NEW_VISITORS_DAY as PercentNewVisitorsAtDay, 
				TOTAL_PERC_RETURNING_VISITORS_DAY as PercentRetVisitorsAtDay, 
				TOTAL_NUM_PAGES_PER_VISIT_DAY as NumPagesPerVisitAtDay 
			from 
				DELTA_METRICS_GOOGLE_ANALYTICS_DAY 
			where TOTAL_VISITORS_DAY > 0 
			order by 
				date(DATE) DESC 
			limit 1";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dataFromGA = $row;
		}
		return $dataFromGA;
	}

	public function getDashboardMetric_Frequency_Last31Days(){
		$referenceDate = date('Y-m-d', strtotime('-3 days'));
		$sql = 
			"select 
				INFO_NUMBER_OF_VISITS as VisitsCount, 
				INFO_NUMBER_OF_VISITORS as Visitors, 
				INFO_NUMBER_OF_PAGEVIEWS as Pageviews 
			from CONS_METRICS_FREQUENCY 
			where date(DATE) = date('" . $referenceDate . "') 
			order by INFO_NUMBER_OF_VISITS ASC 
			limit 60";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dataFromGA[] = $row;
		}
		return $dataFromGA;
	}

	public function getDashboardMetric_Recency(){
		$referenceDate = date('Y-m-d', strtotime('-3 days'));
		$sql = 
			"select 
				INFO_NUMBER_OF_DAYS_SINCE_LAST_VISIT as DaysSinceLastVisit, 
				INFO_NUMBER_OF_VISITORS as Visitors, 
				INFO_NUMBER_OF_PAGEVIEWS as Pageviews 
			from CONS_METRICS_RECENCY 
			where date(DATE) = date('" . $referenceDate . "') 
			order by INFO_NUMBER_OF_DAYS_SINCE_LAST_VISIT ASC 
			limit 60";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dataFromGA[] = $row;
		}
		return $dataFromGA;
	}

	public function getDashboardMetric_VisitorsPerTrafficSource(){
		$referenceDate = date('Y-m-d', strtotime('-1 days'));
		$sql = 
			"select 
				date(DATE) as RefDate,
				TOTAL_TRAFFIC_DIRECT as VisitorsDirect, 
				TOTAL_TRAFFIC_ORGANIC as VisitorsOrganic, 
				TOTAL_TRAFFIC_REFERAL as VisitorsReferral, 
				TOTAL_TRAFFIC_NOTSET as VisitorsNotSet  
			from CONS_METRICS_TRAFFIC_ORIGIN_VISITORS 
			order by date(DATE) DESC  
			limit 7";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dataFromGA[] = $row;
		}
		return $dataFromGA;
	}

	public function getDashboardMetric_PageviewsPerTrafficSource(){
		$referenceDate = date('Y-m-d', strtotime('-1 days'));
		$sql = 
			"select 
				date(DATE) as RefDate,
				TOTAL_TRAFFIC_DIRECT as PageviewsDirect, 
				TOTAL_TRAFFIC_ORGANIC as PageviewsOrganic, 
				TOTAL_TRAFFIC_REFERAL as PageviewsReferral, 
				TOTAL_TRAFFIC_NOTSET as PageviewsNotSet  
			from CONS_METRICS_TRAFFIC_ORIGIN_PAGEVIEWS 
			order by date(DATE) DESC  
			limit 7";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$dataFromGA[] = $row;
		}
		return $dataFromGA;
	}	

	public function getDashboardMetric_AvgPageviewsTecks_CG_Day(){
		$referenceDate = date('Y-m-d', strtotime('-1 days'));
		$sql =
			"select 
				a.RefDate as ReferenceDate, 
				a.PublishedTecks as TotalPublishedTecks,  
				b.SumTotalsViews as TotalViewsDay, 
				b.SumTotalsViews / a.PublishedTecks as AverageViewsPerTeck
			from  
				(select date(DATE) as RefDate, TOTAL_PUBLISHED_TECKS as PublishedTecks 
					from CONS_METRICS_TECKS  
					group by date(DATE)  
					order by date(DATE) desc) a, 
				(select date(DATE) as RefDate, sum(TOTAL_SUM_VIEWS) as SumTotalsViews 
					from DELTA_METRICS_DAILY_VIEWS 
					group by date(DATE)  
					order by date(DATE) DESC) b 
				where 
					a.RefDate = b.RefDate 
				order by  
					date(b.RefDate) DESC 
				limit 1";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		return $data;
	}

	public function getDashboardMetric_AvgPageviewsTecks_CG_Month(){
		$referenceDate = date('Y-m-d', strtotime('-1 days'));
		$sql =
			"select 
				a.RefDate as ReferenceDate, 
				a.PublishedTecks as TotalPublishedTecks,  
				b.SumTotalsViews as TotalViewsDay, 
				b.SumTotalsViews / a.PublishedTecks as AverageViewsPerTeck 
			from  
				(select date(DATE) as RefDate, TOTAL_PUBLISHED_TECKS as PublishedTecks 
					from CONS_METRICS_TECKS  
					group by YEAR(DATE),MONTH(DATE)  
					order by YEAR(DATE) DESC, MONTH(DATE) desc) a, 
				(select date(DATE) as RefDate, sum(TOTAL_SUM_VIEWS) as SumTotalsViews 
					from DELTA_METRICS_DAILY_VIEWS 
					group by YEAR(DATE),MONTH(DATE)  
			order by YEAR(DATE) DESC ,MONTH(DATE) DESC) b 
			where 
				year(a.RefDate) = year(b.RefDate) and
				month(a.RefDate) = month(b.RefDate) 
			order by  
				date(b.RefDate) DESC";
		$result = $this->conn->getConnection()->query($sql);
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		return $data;
	}





}
?>