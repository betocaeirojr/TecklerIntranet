<?php

// Global Variable -- SQL Metrics Statements 
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
					"ConsUsersRawInfo"					=> "select * from CONS_METRICS_PROFILES " 													. $prWhereClause,
					"DeltaUsersRawInfo"					=> "select * from DELTA_METRICS_PROFILES_DAY " 												. $prWhereClause
					);


	



?>