<?php

date_default_timezone_set("America/Sao_Paulo");
echo "Populating Following Metrics at Metrics Database\n";

if (!isset($_GET['day'])) {
	$InformedDateIs = date("Y-m-d");	
} else {
	$InformedDateIs = date("Y-m-d", strtotime($_GET['day']));
}
$ReferenceDayIs = date("Y-m-d", (time()-86400));

echo "[Debug] - ######################################################################################\n";
echo "[Debug] - # Starting ETL to populate Following Information at " . date("Y-m-d H:i:s")."\n";
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
echo "[Debug] - Preparing SQL Statements to Populate Consolidated Metrics for Following/Followed... \n";

	// FOLLOWING METRICS
	// Metric - TOTAL FOLLOWING
	$sql_total_num_following = 
		"select count(i.NumFollowing) as NumTotalFollowing 
		from 
			(select 
				count(F.PROFILE_ID) as NumFollowing, 
				F.FOLLOWER_PROFILE_ID as FollowerProfileID, 
				P.SIGNATURE as FollowerSignature 
			from FOLLOWER F, PROFILE P where F.FOLLOWER_PROFILE_ID = P.PROFILE_ID 
			group by F.FOLLOWER_PROFILE_ID order by count(F.PROFILE_ID) DESC
			) i ";
	$result = mysql_query($sql_total_num_following, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Total Number of People Following someone: " . $row['NumTotalFollowing']. "\n";
		$NumTotalFollowing = $row['NumTotalFollowing'];	
	}

	// FOLLOWED METRICS
	// Metric - TOTAL FOLLOWING
	$sql_total_num_followed = 
		"select count(i.NumFollowers) as NumTotalFollowed 
		from 
			(select 
				count(F.FOLLOWER_PROFILE_ID) as NumFollowers, 
				F.PROFILE_ID as ProfileBeingFollowedID, 
				P.SIGNATURE SignatureBeingFollowed 
			from  FOLLOWER F, PROFILE P  where F.PROFILE_ID = P.PROFILE_ID 
			group by F.PROFILE_ID order by count(F.FOLLOWER_PROFILE_ID) DESC
			) i ";
	$result = mysql_query($sql_total_num_followed, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Total Number of People being Followed by someone: " . $row['NumTotalFollowed']. "\n";
		$NumTotalFollowed = $row['NumTotalFollowed'];	
	}

	// Average for Followers per Profile
	$sql_avg_followed_profile = 
		"select count(a.NumFollowers)/(b.NumProfiles) as AVGFollowersPerProfile from 
			(select count(F.FOLLOWER_PROFILE_ID) as NumFollowers, F.PROFILE_ID as ProfileID 
			from FOLLOWER F group by F.PROFILE_ID order by count(F.FOLLOWER_PROFILE_ID) DESC) a,
			(select count(PROFILE_ID) as NumProfiles from PROFILE) b "; 
	$result = mysql_query($sql_avg_followed_profile, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Average of Followers per Profile: " . $row['AVGFollowersPerProfile']. "\n";
		$AVGFollowedPerProfile = $row['AVGFollowersPerProfile'];	
	}

	// Average for Following per Profile
	$sql_avg_following_profile = 
		"select count(a.NumFollowing)/(b.NumProfiles) as AVGFollowingPerProfile from 
			(select count(F.PROFILE_ID) as NumFollowing, F.FOLLOWER_PROFILE_ID as ProfileID 
			from FOLLOWER F group by F.FOLLOWER_PROFILE_ID order by count(F.PROFILE_ID) DESC) a,
			(select count(PROFILE_ID) as NumProfiles from PROFILE) b ";
	$result = mysql_query($sql_avg_following_profile, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Average of Following per Profile: " . $row['AVGFollowingPerProfile']. "\n";
		$AVGFollowingPerProfile = $row['AVGFollowingPerProfile'];	
	}

	// Weighted Average for Followers per Profile
	$sql_wavg_followed_profile = 
		"select sum(a.NumFollowers)/count(a.ProfileID) as WAVGFollowersPerProfile from 
			(select count(F.FOLLOWER_PROFILE_ID) as NumFollowers, F.PROFILE_ID as ProfileID 
			from FOLLOWER F group by F.PROFILE_ID order by count(F.FOLLOWER_PROFILE_ID) DESC) a"; 
	$result = mysql_query($sql_wavg_followed_profile, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Weighted Average of Followers per Profile: " . $row['WAVGFollowersPerProfile']. "\n";
		$WAVGFollowedPerProfile = $row['WAVGFollowersPerProfile'];	
	}

	// Weighted Average for Following per Profile
	$sql_wavg_following_profile = 
		"select sum(a.NumFollowing)/count(a.ProfileID) as WAVGFollowingPerProfile from 
			(select count(F.PROFILE_ID) as NumFollowing, F.FOLLOWER_PROFILE_ID as ProfileID 
			from FOLLOWER F group by F.FOLLOWER_PROFILE_ID order by count(F.PROFILE_ID) DESC) a";
	$result = mysql_query($sql_wavg_following_profile, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Weighted Average of Following per Profile: " . $row['WAVGFollowingPerProfile']. "\n";
		$WAVGFollowingPerProfile = $row['WAVGFollowingPerProfile'];	
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
$sql_insert_cons_metrics_follow = 
	"insert ignore into CONS_METRICS_FOLLOW 
		(DATE, TOTAL_FOLLOWING, TOTAL_FOLLOWED, AVG_FOLLOWING_PROFILE, AVG_FOLLOWED_PROFILE, WAVG_FOLLOWING_PROFILE, WAVG_FOLLOWED_PROFILE)  
	values 
		('". $ReferenceDayIs . "', $NumTotalFollowing, $NumTotalFollowed, $AVGFollowingPerProfile, $AVGFollowedPerProfile, $WAVGFollowingPerProfile, $WAVGFollowedPerProfile)";

echo "[SQL] - Insert Statement is: " . $sql_insert_cons_metrics_follow . "\n";
$insertion_success = mysql_query($sql_insert_cons_metrics_follow, $conn_w);
if ($insertion_success) {
	echo "[Success] - Total Consolidated Metrics of Likes - Insertion Succeed!.\n";;
} else {
	echo "[Failed] - Total Consolidated Metrics of Likes - Insertion Failed.\n";
	die("[Error] - Failure on inserting data. Please contact your Administrator\n");
}

echo "[Debug] - ######################################################################################\n";
echo "[Debug] - # Finishing ETL to populate Following Information at " . date("Y-m-d H:i:s")."\n";
echo "[Debug] - ######################################################################################\n";


?>