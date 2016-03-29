<?php

date_default_timezone_set("America/Sao_Paulo");
echo "Populating Likes and Dislikes Metrics at Metrics Database\n";

if (!isset($_GET['day'])) {
	$InformedDateIs = date("Y-m-d");	
} else {
	$InformedDateIs = date("Y-m-d", strtotime($_GET['day']));
}
$ReferenceDayIs = date("Y-m-d", (time()-86400));

echo "[Debug] - ######################################################################################\n";
echo "[Debug] - # Starting ETL to populate Likes/Dislikes Information at " . date("Y-m-d H:i:s")."\n";
echo "[Debug] - ######################################################################################\n";
echo "[Info] - Informed Date is: " . $InformedDateIs . "\n";
echo "[Info] - This Script does not used any user informed date... \n";
echo "[Info] - Reference Date is: " . $ReferenceDayIs . "\n";

// Connecting to DB Server
require "conn.php";

// ///////////////////////////////////////////////////////////////////////
//
// Reading and Generating Shares Metrics from DB TECKLER
//
// //////////////////////////////////////////////////////////////////////

echo "[Debug] - Selecting DB Teckler for Consumption...\n";
mysql_select_db(R_DB_T, $conn_r) or die('[Error] - Could not select database; ' . mysql_error());

// Populate Consolidated Metrics for Shares information
echo "[Debug] - Preparing SQL Statements to Populate Consolidated Metrics for Likes and Dislikes... \n";

	// LIKES METRICS
	// Metric - TOTAL LIKES TECKS
	$sql_total_num_likes_tecks = 
		"select count(distinct POST_ID) as NumTecksRated from RATING";
	$result = mysql_query($sql_total_num_likes_tecks, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Total Number of Tecks Liked/Disliked: " . $row['NumTecksRated']. "\n";
		$NumTotalRatesTecks = $row['NumTecksRated'];	
	}

	// Metric - TOTAL LIKES_PROFILES
	$sql_total_num_likes_profiles = 
		"select count(distinct PROFILE_ID) as NumProfilesRated from RATING";
	$result = mysql_query($sql_total_num_likes_profiles, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Total Number of Profiles Liked/Disliked: " . $row['NumProfilesRated']. "\n";
		$NumTotalRatesProfiles = $row['NumProfilesRated'];	
	}


	// Metric - Average Number of Likes/Dislikes per Teck
	$sql_avg_likes_per_teck = 
		"select ((a.NumRates) / (b.NumTecks)) as AvgRatesPerTeck from 
		(select count(RATING_ID) as NumRates from RATING) a, 
		(select count(POST_ID) as NumTecks from POST) b";
	$result = mysql_query($sql_avg_likes_per_teck, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Average number of Likes/Dislikes per Teck: " . $row['AvgRatesPerTeck']. "\n";
		$AvgRatesPerTeck = $row['AvgRatesPerTeck'];	
	}

	// Metric - Average Number of Likes/Dislikes per Profile
	$sql_avg_likes_per_profile = 
		"select ((a.NumRates) / (b.NumProfiles)) as AvgRatesPerProfile from 
			(select count(RATING_ID) as NumRates from RATING) a,
			(select count(PROFILE_ID) as NumProfiles from PROFILE) b";
	$result = mysql_query($sql_avg_likes_per_profile, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Average number of Likes/Dislikes per Profile: " . $row['AvgRatesPerProfile']. "\n";
		$AvgRatesPerProfile = $row['AvgRatesPerProfile'];	
	}

	// Metric - Weight average Number of Likes/Dislikes per Teck
	$sql_wavg_likes_per_teck =
		"select sum(a.NumRates)/count(a.TeckID) as WAvgLikesPerTeck from 
		(select count(RATING_ID) as NumRates, POST_ID as TeckID from RATING group by POST_ID order by count(RATING_ID)) a";
	$result = mysql_query($sql_wavg_likes_per_teck, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Weighted Average of Liked/Disliked Tecks: " . $row['WAvgLikesPerTeck']. "\n";
		$WAVGRatesPerTeck = $row['WAvgLikesPerTeck'];	
	}

	// Metric - Weight average Number of Likes/Dislikes per Profile
	$sql_wavg_likes_per_profile =
		"select sum(a.NumRates)/count(a.ProfileID) as WAvgLikesPerProfile from 
		(select count(RATING_ID) as NumRates, PROFILE_ID as ProfileID from RATING group by PROFILE_ID order by count(RATING_ID)) a";
	$result = mysql_query($sql_wavg_likes_per_profile, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Weighted Average of Liked/Disliked Tecks: " . $row['WAvgLikesPerProfile']. "\n";
		$WAVGRatesPerProfile = $row['WAvgLikesPerProfile'];	
	}

// ///////////////////////////////////////////////////////////////////////
//
// Saving Consolidated Metrics into INTRANET DB
//
// //////////////////////////////////////////////////////////////////////

// Preparing for Insertion
echo "[Debug] - Selecting DB Intranet for Insertion ...\n";
mysql_select_db(W_DB, $conn_w) or die('[Error] - Could not select database; ' . mysql_error());

// Consolidated Metrics - TOTAL
echo "[Debug] - Preparing SQL statement for insertion of consolidated metrics...\n";
$sql_insert_cons_metrics_likes = 
	"insert ignore into CONS_METRICS_LIKES 
		(DATE,
		TOTAL_LIKES_TECKS, TOTAL_LIKES_PROFILES, AVG_LIKES_TECK, AVG_LIKES_PROFILE, WAVG_LIKES_TECK, WAVG_LIKES_PROFILE)  
	values 
		('". $ReferenceDayIs . "', $NumTotalRatesTecks, $NumTotalRatesProfiles, $AvgRatesPerTeck, $AvgRatesPerProfile, $WAVGRatesPerTeck, $WAVGRatesPerProfile)";

echo "[SQL] - Insert Statement is: " . $sql_insert_cons_metrics_likes . "\n";
$insertion_success = mysql_query($sql_insert_cons_metrics_likes, $conn_w);
if ($insertion_success) {
	echo "[Success] - Total Consolidated Metrics of Likes - Insertion Succeed!.\n";;
} else {
	echo "[Failed] - Total Consolidated Metrics of Likes - Insertion Failed.\n";
	die("[Error] - Failure on inserting data. Please contact your Administrator\n");
}

echo "[Debug] - ######################################################################################\n";
echo "[Debug] - # Finishing ETL to populate Likes/Dislikes Information at " . date("Y-m-d H:i:s")."\n";
echo "[Debug] - ######################################################################################\n";


?>