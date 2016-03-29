<?php

//require_once "Connection.php";
//include "Utils.php";

date_default_timezone_set('America/Sao_Paulo');

class Revenue{

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
			"ConsRevenueRawInfo"			=> "select * from CONS_METRICS_REVENUE "												. $prWhereClause,
			"DeltaRevenueRawInfo"			=> "select * from DELTA_METRICS_REVENUE_DAY "											. $prWhereClause,
			"ConsExpectedRevenueAmmount" 	=> "select TOTAL_EXPECTED_REVENUE_TODATE as MetricValue from CONS_METRICS_REVENUE " 	. $prWhereClause,
			"DeltaExpectedRevenueAmmount" 	=> "select TOTAL_EXPECTED_REVENUE_DAY as MetricValue from DELTA_METRICS_REVENUE_DAY " 	. $prWhereClause,
			"ConsActualRevenueAmmount" 		=> "select TOTAL_ACTUAL_REVENUE_TODATE as MetricValue from CONS_METRICS_REVENUE " 		. $prWhereClause,
			"DeltaActualRevenueAmmount" 	=> "select TOTAL_ACTUAL_REVENUE_DAY as MetricValue from DELTA_METRICS_REVENUE_DAY " 	. $prWhereClause,
			"ConsPendingAmmout" 			=> "select TOTAL_PENDING_TODATE as MetricValue from CONS_METRICS_REVENUE " 				. $prWhereClause,
			"DeltaPendingAmmout"  			=> "select TOTAL_PENDING_DAY as MetricValue from DELTA_METRICS_REVENUE_DAY " 			. $prWhereClause,
			"ConsVerifiedAmmount" 			=> "select TOTAL_VERIFIED_TODATE as MetricValue from CONS_METRICS_REVENUE " 			. $prWhereClause,
			"DeltaVerifiedAmmount" 			=> "select TOTAL_VERIFIED_DAY as MetricValue from DELTA_METRICS_REVENUE_DAY " 			. $prWhereClause,
			"ConsRequestedAmmount" 			=> "select TOTAL_REQUESTED_TODATE as MetricValue from CONS_METRICS_REVENUE " 			. $prWhereClause,
			"DeltaRequestedAmmount" 		=> "select TOTAL_REQUESTED_DAY as MetricValue from DELTA_METRICS_REVENUE_DAY " 			. $prWhereClause,
			"ConsWithdrawnAmmount" 			=> "select TOTAL_WITHDRAWN_TODATE as MetricValue from CONS_METRICS_REVENUE " 			. $prWhereClause,
			"DeltaWithdrawnAmmount"			=> "select TOTAL_WITHDRAWN_DAY as MetricValue from DELTA_METRICS_REVENUE_DAY "			. $prWhereClause,	
			"ConsErrorAmmount" 				=> "select TOTAL_ERROR_TODATE	as MetricValue from CONS_METRICS_REVENUE " 				. $prWhereClause,
			"DeltaErrorAmmount" 			=> "select TOTAL_ERROR_DAY	as MetricValue from DELTA_METRICS_REVENUE_DAY " 			. $prWhereClause,
			"ConsAverageRevenuePerProfile" 	=> "select AVG_REVENUE_PROFILE as MetricValue from CONS_METRICS_REVENUE " 				. $prWhereClause,
			"ConsAverageRevenuePerTeck"		=> "select AVG_REVENUE_TECK	as MetricValue from CONS_METRICS_REVENUE "					. $prWhereClause,
			"ConsWeightedAverageRevenuePerProfile" 	=> "select WAVG_REVENUE_VIEWED_PROFILES as MetricValue from CONS_METRICS_REVENUE " 	. $prWhereClause,
			"ConsWeightedAverageRevenuePerTeck"	=> "select WAVG_REVENUE_VIEWED_TECKS as MetricValue from CONS_METRICS_REVENUE " 	. $prWhereClause,
			"DeltaNumberOfTransferRequestedAtDay" => "select TOTAL_TRANSFERS_REQUESTS_DAY as MetricValue from DELTA_METRICS_REVENUE_DAY " . $prWhereClause
								);
		

		// Check Which Metric Should be Fetched
		switch ($prMetric) {

			// Revenue Raw Information
			case 'ConsRevenueRawInfo':
				$sql = $sql_metrics_array['ConsRevenueRawInfo'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'DeltaRevenueRawInfo':
				$sql = $sql_metrics_array['DeltaRevenueRawInfo'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;

			// Expected Revenue Metrics
			case 'ConsExpectedRevenueAmmount':
				$sql = $sql_metrics_array['ConsExpectedRevenueAmmount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'DeltaExpectedRevenueAmmount':
				$sql = $sql_metrics_array['DeltaExpectedRevenueAmmount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;
			
			// Actual Revenue Metrics
			case 'ConsActualRevenueAmmount':
				$sql = $sql_metrics_array['ConsActualRevenueAmmount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'DeltaActualRevenueAmmount':
				$sql = $sql_metrics_array['DeltaActualRevenueAmmount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;	
			
			// Pending Revenue
			case 'ConsPendingAmmout':
				$sql = $sql_metrics_array['ConsPendingAmmout'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'DeltaPendingAmmout':
				$sql = $sql_metrics_array['DeltaPendingAmmout'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;	
			
			// Verified Revenue
			case 'ConsVerifiedAmmount':
				$sql = $sql_metrics_array['ConsVerifiedAmmount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'DeltaVerifiedAmmount':
				$sql = $sql_metrics_array['DeltaVerifiedAmmount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;	
			
			// Requested Revenue
			case 'ConsRequestedAmmount':
				$sql = $sql_metrics_array['ConsRequestedAmmount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'DeltaRequestedAmmount':
				$sql = $sql_metrics_array['DeltaRequestedAmmount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;	

			// Withdrawn Revenue
			case 'ConsWithdrawnAmmount':
				$sql = $sql_metrics_array['ConsWithdrawnAmmount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'DeltaWithdrawnAmmount':
				$sql = $sql_metrics_array['DeltaWithdrawnAmmount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;	

			// Error Revenue
			case 'ConsErrorAmmount':
				$sql = $sql_metrics_array['ConsErrorAmmount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'DeltaErrorAmmount':
				$sql = $sql_metrics_array['DeltaErrorAmmount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;
			
			// Average Revenue - Per Teck and Per Profile
			case 'ConsAverageRevenuePerProfile':
				$sql = $sql_metrics_array['ConsAverageRevenuePerProfile'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'ConsAverageRevenuePerTeck':
				$sql = $sql_metrics_array['ConsAverageRevenuePerTeck'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;

			// Weighted Average Revenue - Per Teck and Per Profile
			case 'ConsWeightedAverageRevenuePerProfile':
				$sql = $sql_metrics_array['ConsWeightedAverageRevenuePerProfile'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'ConsWeightedAverageRevenuePerTeck':
				$sql = $sql_metrics_array['ConsWeightedAverageRevenuePerTeck'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;
			// Number of Transfers Requested
			case 'DeltaNumberOfTransferRequestedAtDay':
				$sql = $sql_metrics_array['DeltaNumberOfTransferRequestedAtDay'];
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
			// Fetching Metric Value
			$result = $this->conn->getConnection()->query($sql);		
			if (!$result) return FALSE;
			try {
				while ($row = $result->fetch_assoc()) {
	        		//echo "[DEBUG] -- VERBOSITY LEVEL : SUPER::: <br>\n"; echo "<PRE>"; print_r($row); echo "</PRE>";
	        		// If the metrics (below) return an multivalue array
	        		if (	$prMetric == "ConsRevenueRawInfo" 	|| 
	        				$prMetric == "DeltaRevenueRawInfo"		) {
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
	public function getRevenueRawData($prRetType='array', $prCount='cons', $prDate='', $prOrder='desc'){

		// Preparing SQL Statement
		$whereStatement 	= $this->setWhereClauseUntilDate($prDate);
		$whereStatement 	= $whereStatement . "order by date(DATE) $prOrder";

		// Get Metric
		if ( strtolower($prCount) == 'cons') {
			$UsersData = $this->getMetricInfo('ConsRevenueRawInfo', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaRevenueRawInfo', $whereStatement);
		}

		// Preparing the Returning ResultSet
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getExpectedRevenueCount($prRetType='array', $prCount='cons', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ( strtolower($prCount) == 'cons') {
			$UsersData = $this->getMetricInfo('ConsExpectedRevenueAmmount', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaExpectedRevenueAmmount', $whereStatement);
		}

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType); 
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getActualRevenueCount($prRetType='array', $prCount='cons', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ( strtolower($prCount) == 'cons') {
			$UsersData = $this->getMetricInfo('ConsActualRevenueAmmount', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaActualRevenueAmmount', $whereStatement);
		}

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}
	 
	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getPendingRevenueCount($prRetType='array', $prCount='cons', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ( strtolower($prCount) == 'cons') {
			$UsersData = $this->getMetricInfo('ConsPendingAmmout', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaPendingAmmout', $whereStatement);
		}

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}
	
	
	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getVerifiedRevenueCount($prRetType='array', $prCount='cons', $prDate=''){
		
		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ( strtolower($prCount) == 'cons') {
			$UsersData = $this->getMetricInfo('ConsVerifiedAmmount', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaVerifiedAmmount', $whereStatement);
		}	

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getRequestedRevenueCount($prRetType='array', $prCount='cons', $prDate=''){
		
		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ( strtolower($prCount) == 'cons') {
			$UsersData = $this->getMetricInfo('ConsRequestedAmmount', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaRequestedAmmount', $whereStatement);
		}	

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getWithdrawnRevenueCount($prRetType='array', $prCount='cons', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ( strtolower($prCount) == 'cons') {
			$UsersData = $this->getMetricInfo('ConsWithdrawnAmmount', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaWithdrawnAmmount', $whereStatement);
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
	public function getErrorRevenueCount($prRetType='array', $prCount='cons', $prDate=''){

		// Preparing SQL Statement
		$whereStatement 	= $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ( strtolower($prCount) == 'cons') {
			$UsersData = $this->getMetricInfo('ConsErrorAmmount', $whereStatement);
		}
		else {
			$UsersData = $this->getMetricInfo('DeltaErrorAmmount', $whereStatement);
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
	public function getAverageRevenue($prRetType='array', $prDate='', $prIndic='profile'){

		// Preparing SQL Statement
		$whereStatement 	= $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ( strtolower($prIndic) == 'profile') {
			$UsersData = $this->getMetricInfo('ConsAverageRevenuePerProfile', $whereStatement);
		}
		elseif ($prIndic=='teck') {
				$UsersData = $this->getMetricInfo('ConsAverageRevenuePerTeck', $whereStatement);
			} else {
				$UsersData = 0;
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
	public function getWeightedAverageRevenue($prRetType='array', $prDate='', $prIndic='profile'){

		// Preparing SQL Statement
		$whereStatement 	= $this->setWhereClauseAtDate($prDate);

		// Get Metric
		if ( strtolower($prIndic) == 'profile') {
			$UsersData = $this->getMetricInfo('ConsWeightedAverageRevenuePerProfile', $whereStatement);
		}
		elseif ($prIndic=='teck') {
				$UsersData = $this->getMetricInfo('ConsWeightedAverageRevenuePerTeck', $whereStatement);
			} else {
				$UsersData = 0;
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
	public function getNumberOfTransferRequested($prRetType='array', $prDate=''){

		// Preparing SQL Statement
		$whereStatement 	= $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('DeltaNumberOfTransferRequestedAtDay', $whereStatement);
		
		// Preparing the Returning ResultSet
		return $this->setReturnResultSet($UsersData, $prRetType);
	}



}
?>