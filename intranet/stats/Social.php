<?php

//require_once "Connection.php";
//include "Utils.php";

date_default_timezone_set('America/Sao_Paulo');

class Social{

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
		$whereClause = "where date(DATE) <= date('" . $sql_date ."') order by date(DATE) DESC";

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
								"ConsFollowingCount" 				=> "select TOTAL_FOLLOWING as MetricValue from CONS_METRICS_FOLLOW " 		. $prWhereClause,
								"ConsFollowedCount" 				=> "select TOTAL_FOLLOWED as MetricValue from CONS_METRICS_FOLLOW " 		. $prWhereClause,
								"ConsAverageFollowingCount"			=> "select AVG_FOLLOWING_PROFILE as MetricValue from CONS_METRICS_FOLLOW " 	. $prWhereClause,
								"ConsAverageFollowedCount"			=> "select AVG_FOLLOWED_PROFILE as MetricValue from CONS_METRICS_FOLLOW " 	. $prWhereClause,
								"ConsWeightedAverageFollowingCount" => "select WAVG_FOLLOWING_PROFILE as MetricValue from CONS_METRICS_FOLLOW "	. $prWhereClause,
								"ConsWeightedAverageFollowedCount"	=> "select WAVG_FOLLOWED_PROFILE as MetricValue from CONS_METRICS_FOLLOW " 	. $prWhereClause,
								"ConsVotedTecksCount"				=> "select TOTAL_LIKES_TECKS as MetricValue from CONS_METRICS_LIKES " 		. $prWhereClause,
								"ConsVotedProfilesCount"			=> "select TOTAL_LIKES_PROFILES as MetricValue from CONS_METRICS_LIKES " 	. $prWhereClause,
								"ConsAverageVotedTecksCount"		=> "select AVG_LIKES_TECK as MetricValue from CONS_METRICS_LIKES " 			. $prWhereClause, 
								"ConsAverageVotedProfilesCount"		=> "select AVG_LIKES_PROFILE as MetricValue from CONS_METRICS_LIKES " 		. $prWhereClause,
								"ConsWeightedAverageVotedTecksCount"=> "select WAVG_LIKES_TECK as MetricValue from CONS_METRICS_LIKES " 		. $prWhereClause,
								"ConsWeightedAverageVotedProfilesCount"	=> "select WAVG_LIKES_PROFILE as MetricValue from CONS_METRICS_LIKES " 	. $prWhereClause,
								"ConsSharesCount"					=> "select TOTAL_SHARES as MetricValue from CONS_METRICS_SHARES " 			. $prWhereClause,
								"ConsAverageSharePerTeckCount"		=> "select AVG_SHARES_TECK as MetricValue from CONS_METRICS_SHARES  " 		. $prWhereClause,
								"ConsWeightedAverageSharePerTeckCount"	=> "select WAVG_SHARES_TECK as MetricValue from CONS_METRICS_SHARES  " 	. $prWhereClause,
								"ConsTecksWithoutSahresCount"		=> "select TOTAL_TECKS_WO_SHARES as MetricValue from CONS_METRICS_SHARES   ". $prWhereClause,
								"ConsSharesPerSocialNetworkCount"	=> "select * from CONS_METRICS_SHARES_SN " 									. $prWhereClause,
								"ConsFollowRawInfo"					=> "select * from CONS_METRICS_FOLLOW " 									. $prWhereClause,
								"ConsVotesRawInfo"					=> "select * from CONS_METRICS_LIKES " 										. $prWhereClause,
								"ConsSharesRawInfo"					=> "select * from CONS_METRICS_SHARES " 									. $prWhereClause,
								"ConsSharesPerSocialNetworkRawInfo"	=> "select * from CONS_METRICS_SHARES_SN " 									. $prWhereClause
							);
		
		// Check Which Metric Should be Fetched
		switch ($prMetric) {
			// Raw Info
			case 'ConsFollowRawInfo':
				$sql = $sql_metrics_array['ConsFollowRawInfo'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'ConsVotesRawInfo':
				$sql = $sql_metrics_array['ConsVotesRawInfo'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'ConsSharesRawInfo':
				$sql = $sql_metrics_array['ConsSharesRawInfo'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'ConsSharesPerSocialNetworkRawInfo':
				$sql = $sql_metrics_array['ConsSharesPerSocialNetworkRawInfo'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	

			// Following and Followed Total Numbers
			case 'ConsFollowingCount':
				$sql = $sql_metrics_array['ConsFollowingCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users Count...<br>\n";
				}
				break;	
			case 'ConsFollowedCount':
				$sql = $sql_metrics_array['ConsFollowedCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Count...<br>\n";
				}
				break;
			
			// Following and Followed Average Numbers
			case 'ConsAverageFollowingCount':
				$sql = $sql_metrics_array['ConsAverageFollowingCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Profiles Count...<br>\n";
				}
				break;	
			case 'ConsAverageFollowedCount':
				$sql = $sql_metrics_array['ConsAverageFollowedCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Profiles Count...<br>\n";
				}
				break;	
			
			// Following and Followed Weighted Average Numbers
			case 'ConsWeightedAverageFollowingCount':
				$sql = $sql_metrics_array['ConsWeightedAverageFollowingCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Active Profiles Count...<br>\n";
				}
				break;	
			case 'ConsWeightedAverageFollowedCount':
				$sql = $sql_metrics_array['ConsWeightedAverageFollowedCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Active Profiles Count...<br>\n";
				}
				break;	
			
			//Number of Votes (both Like and Dislike)
			case 'ConsVotedTecksCount':
				$sql = $sql_metrics_array['ConsVotedTecksCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Average Profiles Per User Count...<br>\n";
				}
				break;	
			case 'ConsVotedProfilesCount':
				$sql = $sql_metrics_array['ConsVotedProfilesCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Average Profiles Per User Count...<br>\n";
				}
				break;	
			
			// Average Number of Votes (both Like and Dislike)
			case 'ConsAverageVotedTecksCount':
				$sql = $sql_metrics_array['ConsAverageVotedTecksCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Average Active Profiles Per User Count...<br>\n";
				}
				break;	
			case 'ConsAverageVotedProfilesCount':
				$sql = $sql_metrics_array['ConsAverageVotedProfilesCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Active Profiles Per User Count...<br>\n";
				}
				break;	
			
			// WeightedAverage Number of Votes (both Like and Dislike)
			case 'ConsWeightedAverageVotedTecksCount':
				$sql = $sql_metrics_array['ConsAverageVotedTecksCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users With only 1 Profile Count...<br>\n";
				}
				break;	
			case 'ConsWeightedAverageVotedProfilesCount':
				$sql = $sql_metrics_array['ConsWeightedAverageVotedProfilesCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Weighted Profiles Per User Count...<br>\n";
				}
				break;	
			
			// Shares Count
			case 'ConsSharesCount':
				$sql = $sql_metrics_array['ConsSharesCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Daily Logged Users Count...<br>\n";
				}
				break;	
			
			// Average and Weighted AverageShare per Teck
			case 'ConsAverageSharePerTeckCount':
				$sql = $sql_metrics_array['ConsAverageSharePerTeckCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users With Keep Me Logged On Count...<br>\n";
				}
				break;	
			case 'ConsWeightedAverageSharePerTeckCount':
				$sql = $sql_metrics_array['ConsWeightedAverageSharePerTeckCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Consolidated Users per Lang Count...<br>\n";
				}
				break;	

			// Tecks Without Shares
			case 'ConsTecksWithoutSahresCount':
				$sql = $sql_metrics_array['ConsTecksWithoutSahresCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Per Lang Count...<br>\n";
				}
				break;
			
			// Shares Per Social Network
			case 'ConsSharesPerSocialNetworkCount':
				$sql = $sql_metrics_array['ConsSharesPerSocialNetworkCount'];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Consolidated Users Raw Information...<br>\n";
				}
				break;	
			/*
			case '':
				$sql = $sql_metrics_array[''];
				if ($this->activeDebug) {
					echo "[DEBUG] - Using Method getMetricInfo for Metric Delta Users Raw Information ...<br>\n";
				}
				break;
			*/
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
	        		if (	$prMetric == "ConsSharesPerSocialNetworkCount" 	||
	        				$prMetric == "ConsFollowRawInfo"				||
	        				$prMetric == "ConsVotesRawInfo"					||
	        				$prMetric == "ConsSharesRawInfo"				||
	        				$prMetric == "ConsSharesPerSocialNetworkRawInfo"
	        			){
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


	// Get Raw Info
	// Follow / Following
	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'. Will get info from the until this date (including the informed day)
	* @param prCount: Type of counting (Consolidated or Delta Metric)
	* @param prPeriod: Consolidated info (day, week, month)
	* @param prRetType: Returning type (array or json)
	* @param prOrder: Ordering of the information (desc or asc)
	*/
	public function getFollowRawData($prRetType='array', $prDate='', $prOrder='desc'){

		// Preparing SQL Statement
		$whereStatement 	= $this->setWhereClauseUntilDate($prDate);
		$whereStatement 	= $whereStatement . "order by date(DATE) $prOrder";

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsFollowRawInfo', $whereStatement);

		// Preparing the Returning ResultSet
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	// Votes
	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'. Will get info from the until this date (including the informed day)
	* @param prCount: Type of counting (Consolidated or Delta Metric)
	* @param prPeriod: Consolidated info (day, week, month)
	* @param prRetType: Returning type (array or json)
	* @param prOrder: Ordering of the information (desc or asc)
	*/
	public function getVotesRawData($prRetType='array', $prDate='', $prOrder='desc'){

		// Preparing SQL Statement
		$whereStatement 	= $this->setWhereClauseUntilDate($prDate);
		$whereStatement 	= $whereStatement . "order by date(DATE) $prOrder";

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsVotesRawInfo', $whereStatement);

		// Preparing the Returning ResultSet
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	// Shares 
	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'. Will get info from the until this date (including the informed day)
	* @param prCount: Type of counting (Consolidated or Delta Metric)
	* @param prPeriod: Consolidated info (day, week, month)
	* @param prRetType: Returning type (array or json)
	* @param prOrder: Ordering of the information (desc or asc)
	*/
	public function getSharesRawData($prRetType='array', $prDate='', $prOrder='desc'){

		// Preparing SQL Statement
		$whereStatement 	= $this->setWhereClauseUntilDate($prDate);
		$whereStatement 	= $whereStatement . "order by date(DATE) $prOrder";

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsSharesRawInfo', $whereStatement);

		// Preparing the Returning ResultSet
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

	// Shares per Social Network
	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'. Will get info from the until this date (including the informed day)
	* @param prCount: Type of counting (Consolidated or Delta Metric)
	* @param prPeriod: Consolidated info (day, week, month)
	* @param prRetType: Returning type (array or json)
	* @param prOrder: Ordering of the information (desc or asc)
	*/
	public function getSharesPerSocialNetworkRawData($prRetType='array', $prDate='', $prOrder='desc'){

		// Preparing SQL Statement
		$whereStatement 	= $this->setWhereClauseUntilDate($prDate);
		$whereStatement 	= $whereStatement . "order by date(DATE) $prOrder";

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsSharesPerSocialNetworkRawInfo', $whereStatement);

		// Preparing the Returning ResultSet
		return $this->setReturnResultSet($UsersData, $prRetType);
	}

// Get Total Numbers
	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getFollowingCount($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsFollowingCount', $whereStatement);
	

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType); 
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getFollowedCount($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsFollowedCount', $whereStatement);
	

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType); 
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getVotedTecksCount($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsVotedTecksCount', $whereStatement);
	

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType); 
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getVotedProfilesCount($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsVotedProfilesCount', $whereStatement);
	

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType); 
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getSharesCount($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsSharesCount', $whereStatement);
	

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType); 
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getTecksWithoutSharesCount($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsTecksWithoutSahresCount', $whereStatement);
	

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType); 
	}


// Get Average and Weighted Average Numbers

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getAverageFollowingCount($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsAverageFollowingCount', $whereStatement);
	

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType); 
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getAverageFollowedCount($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsAverageFollowedCount', $whereStatement);
	

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType); 
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getWeightedAverageFollowingCount($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsWeightedAverageFollowingCount', $whereStatement);
	

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType); 
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getWeightedAverageFollowedCount($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsWeightedAverageFollowedCount', $whereStatement);
	

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType); 
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getAverageVotesPerTeckCount($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsAverageVotedTecksCount', $whereStatement);
	

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType); 
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getAverageVotesPerProfileCount($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsAverageVotedProfilesCount', $whereStatement);
	

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType); 
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getWeightedAverageVotesPerTeckCount($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsWeightedAverageVotedTecksCount', $whereStatement);
	

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType); 
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getWeightedAverageVotesPerProfileCount($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsWeightedAverageVotedProfilesCount', $whereStatement);
	

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType); 
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getAverageSharesPerTeckCount($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsAverageSharePerTeckCount', $whereStatement);
	

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType); 
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getWeightedAverageSharesPerTeckCount($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsWeightedAverageSharePerTeckCount', $whereStatement);
	

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType); 
	}

	/* ************************************
	* @param prDate: date in format of 'YYYY-mm-dd'
	* @param prCount: Consolidated and Delta Metric
	* @param prPeriod: Consolidated info (day, week, month)
	*/
	public function getSharesPerSocialNetworkCount($prRetType='array', $prDate=''){

		// Preparing SQL Statement acording Class Parameters
		$whereStatement = $this->setWhereClauseAtDate($prDate);

		// Get Metric
		$UsersData = $this->getMetricInfo('ConsSharesPerSocialNetworkCount', $whereStatement);

		// Preparing The Returning Info
		return $this->setReturnResultSet($UsersData, $prRetType);
	}
}
?>