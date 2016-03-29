<?php

date_default_timezone_set("America/Sao_Paulo");
echo "Populating Online Visitors/Users Metrics at Metrics Database\n";

if (!isset($_GET['day'])) {
	$InformedDateIs = date("Y-m-d");	
} else {
	$InformedDateIs = date("Y-m-d", strtotime($_GET['day']));
}
$ReferenceDayIs = date("Y-m-d", (time()-86400));

echo "[Debug] - ######################################################################################\n";
echo "[Debug] - # Starting ETL to populate Online Visitors/Users Information at " . date("Y-m-d H:i:s")."\n";
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

// Initializing Variables
$NumTotalUniqueVisitorsDay	=0; 
$NumTotalUsersKeepMeLogged	=0; 
$NumTotalUsersLastLogin1W	=0; 
$NumTotalUsersLastLogin1M	=0;


echo "[Debug] - Selecting DB Teckler for Consumption...\n";
mysql_select_db(R_DB_T, $conn_r) or die('[Error] - Could not select database; ' . mysql_error());

// Populate Consolidated Metrics for Shares information
echo "[Debug] - Preparing SQL Statements to Populate Consolidated Metrics for Online Users... \n";

// ONLINE USERS/VISITORS
	// Metric - TOTAL UNIQUE VISITORS TODAY
	$sql_total_num_unique_visitors_day = 
		 "select count(USER_ID) as NumUsers from USER_LOGIN_INFO where date(LAST_LOGIN_DATE)='" . $ReferenceDayIs . "'";
	$result = mysql_query($sql_total_num_unique_visitors_day, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Total Number of Unique Visitors at this Day: " . $row['NumUsers']. "\n";
		$NumTotalUniqueVisitorsDay = (!empty($row['NumUsers']) ? $row['NumUsers'] : 0 );	
	}

	// Metric - TOTAL USERS WITH KEEP ME LOGGED On
	$sql_total_num_keepmelogged = 
		"select count(TOKEN) as NumUsers
			from USER_LOGIN_INFO 
			where TOKEN<>'' and date(LAST_LOGIN_DATE)='" . $ReferenceDayIs . "'";
	$result = mysql_query($sql_total_num_keepmelogged, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Total Number of Users with Keep Me Logged Active: " . $row['NumUsers']. "\n";
		$NumTotalUsersKeepMeLogged = (!empty($row['NumUsers']) ? $row['NumUsers'] : 0 );	
	}

	// Metric - NUM USERS LAST LOGIN 1 Week
	$sql_total_num_user_lastlogin_1w = 
		"select count(USER_ID) as NumUsers from USER_LOGIN_INFO 
		where DATE(LAST_LOGIN_DATE) < ('". $ReferenceDayIs . "') - INTERVAL 7 DAY";
	$result = mysql_query($sql_total_num_user_lastlogin_1w, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Total Number of Users with last login prior 1 Week: " . $row['NumUsers']. "\n";
		$NumTotalUsersLastLogin1W = (!empty($row['NumUsers']) ? $row['NumUsers'] : 0 );	
	}

	// Metric - NUM USERS LAST LOGIN 1 Month
	$sql_total_num_user_lastlogin_1m = 
		"select count(USER_ID) as NumUsers from USER_LOGIN_INFO 
		where DATE(LAST_LOGIN_DATE) < date('". $ReferenceDayIs . "') - INTERVAL 30 DAY";
	$result = mysql_query($sql_total_num_user_lastlogin_1m, $conn_r);
    if ($result != FALSE) {
	    while ($row = mysql_fetch_assoc($result))
	    {
	        echo "[Info] - Total Number of Users with last login prior 1 Month: " . $row['NumUsers']. "\n";
			$NumTotalUsersLastLogin1M = $row['NumUsers'];	
		}
	} else {
			echo "[Info] - Total Number of Users with last login prior 1 Month: " .  "0" . "\n";
			$NumTotalUsersLastLogin1M = 0;	
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
$sql_insert_cons_metrics_online = 
	"insert ignore into CONS_METRICS_ONLINE_USERS 
		(DATE,TOTAL_UNIQUE_VISITOR_DAY, TOTAL_USERS_KEEPMELOGGED, TOTAL_LAST_LOGIN_1WEEK, TOTAL_LAST_LOGIN_1MONTH)  
	values 
		('". $ReferenceDayIs . "', $NumTotalUniqueVisitorsDay, $NumTotalUsersKeepMeLogged, $NumTotalUsersLastLogin1W, $NumTotalUsersLastLogin1M)";

echo "[SQL] - Insert Statement is: " . $sql_insert_cons_metrics_online . "\n";
$insertion_success = mysql_query($sql_insert_cons_metrics_online, $conn_w);
if ($insertion_success) {
	echo "[Success] - Total Consolidated Metrics of Online Users - Insertion Succeed!.\n";;
} else {
	echo "[Failed] - Total Consolidated Metrics of Online Users - Insertion Failed.\n";
	die("[Error] - Failure on inserting data. Please contact your Administrator\n");
}

echo "[Debug] - ######################################################################################\n";
echo "[Debug] - # Finishing ETL to populate Online Visitors/Users Information at " . date("Y-m-d H:i:s")."\n";
echo "[Debug] - ######################################################################################\n";


?>