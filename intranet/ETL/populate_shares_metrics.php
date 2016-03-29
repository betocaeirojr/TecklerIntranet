<?php

date_default_timezone_set("America/Sao_Paulo");
echo "Populating Shares Metrics at Metrics Database\n";

if (!isset($_GET['day'])) {
	$InformedDateIs = date("Y-m-d");	
} else {
	$InformedDateIs = date("Y-m-d", strtotime($_GET['day']));
}
$ReferenceDayIs = date("Y-m-d", (time()-86400));

echo "[Debug] - ######################################################################################\n";
echo "[Debug] - # Starting ETL to populate Shares Information at " . date("Y-m-d H:i:s")."\n";
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
echo "[Debug] - Preparing SQL Statements to Populate Consolidated Metrics for Shares... \n";

	// SHARES METRICS
	// Metric - TOTAL SHARES
	$sql_total_num_shares = 
		"select sum(FACEBOOK+GOOGLE_PLUS+TWITTER+LINKEDIN) as NumTotalShares from POST_SHARE";
	$result = mysql_query($sql_total_num_shares, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Total Number of Shares: " . $row['NumTotalShares']. "\n";
		$NumTotalShares = $row['NumTotalShares'];	
	}

	// Metric - AVERAGE SHARES PER TECK
	$sql_avg_shares_teck = 
		"select sum(c.NumShares)/(d.NumTecks) as AvgSharesPerTeck from (select sum(ps.FACEBOOK+ps.GOOGLE_PLUS+ps.TWITTER+ps.LINKEDIN) as NumShares from POST_SHARE ps) c, (select count(POST_ID) as NumTecks from POST) d ";
	$result = mysql_query($sql_avg_shares_teck, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Average Number of Shares per Teck: " . $row['AvgSharesPerTeck']. "\n";
		$AvgSharesTeck = $row['AvgSharesPerTeck'];	
	}

	// Metric - Total Tecks Wihtout Shares
	$sql_tecks_wo_share = 
		"select (a.NumPosts - b.NumPostWShare) as NumTecksWOShare from 
			(select count(POST_ID) as NumPosts from POST) a, 
			(select count(POST_ID) as NumPostWShare from POST_SHARE) b ";
	$result = mysql_query($sql_tecks_wo_share, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Number of Tecks wihtout Share: " . $row['NumTecksWOShare']. "\n";
		$NumTecksWOShare = $row['NumTecksWOShare'];	
	}

	// Metric - Weighted Average of Shares per Teck
	$sql_wavg_shares_teck = 
		"select (a.NumShares/b.NumTecksWShare) as WAVGSharesPerTeck from 
			(select sum(ps.FACEBOOK+ps.GOOGLE_PLUS+ps.TWITTER+ps.LINKEDIN) as NumShares from POST_SHARE ps) a, 
			(select count(POST_ID) as NumTecksWShare from POST_SHARE) b ";
	$result = mysql_query($sql_wavg_shares_teck, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Weighted Average of Shares per Teck: " . $row['WAVGSharesPerTeck']. "\n";
		$WAvgSharesTeck = $row['WAVGSharesPerTeck'];	
	}


// Metric - Shares per Social Network
	// Metric - Shares at Facebook
	$sql_total_shares_fb = 
		"select sum(FACEBOOK) as NumSharesFB from POST_SHARE";
	$result = mysql_query($sql_total_shares_fb, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Total Numbers of Shares at Facebook: " . $row['NumSharesFB']. "\n";
		$NumShares_FB = $row['NumSharesFB'];	
	}


	// Metric - Shares at Google Plus
	$sql_total_shares_gp = 
		"select sum(GOOGLE_PLUS) as NumSharesGP from POST_SHARE";

	$result = mysql_query($sql_total_shares_gp, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Total Numbers of Shares at Google Plus: " . $row['NumSharesGP']. "\n";
		$NumShares_GP = $row['NumSharesGP'];	
	}

	// Metric - Shares at LinkedIn
	$sql_total_shares_ld = 
		"select sum(LINKEDIN) as NumSharesLD from POST_SHARE";
	$result = mysql_query($sql_total_shares_ld, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Total Numbers of Shares at LinkedIn: " . $row['NumSharesLD']. "\n";
		$NumShares_LD = $row['NumSharesLD'];	
	}

	// Metric - Shares at LinkedIn
	$sql_total_shares_tw = 
		"select sum(TWITTER) as NumSharesTW from POST_SHARE";
	$result = mysql_query($sql_total_shares_tw, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Total Numbers of Shares at Twitter: " . $row['NumSharesTW']. "\n";
		$NumShares_TW = $row['NumSharesTW'];	
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
$sql_insert_cons_metrics_shares = 
	"insert ignore into CONS_METRICS_SHARES 
		(DATE, TOTAL_SHARES, AVG_SHARES_TECK, TOTAL_TECKS_WO_SHARES, WAVG_SHARES_TECK)  
	values 
		('". $ReferenceDayIs . "', $NumTotalShares, $AvgSharesTeck, $NumTecksWOShare, $WAvgSharesTeck)";

echo "[SQL] - Insert Statement is: " . $sql_insert_cons_metrics_shares . "\n";
$insertion_success = mysql_query($sql_insert_cons_metrics_shares, $conn_w);
if ($insertion_success) {
	echo "[Success] - Total Consolidated Metrics of Shares - Insertion Succeed!.\n";;
} else {
	echo "[Failed] - Total Consolidated Metrics of Shares - Insertion Failed.\n";
	die("[Error] - Failure on inserting data. Please contact your Administrator\n");
}

// Metrics - Shares per Social Network
echo "[Debug] - Preparing SQL statement for insertion of consolidated metrics of Shares per Social Network...\n";
$sql_insert_metrics_shares_sn = 
	"insert ignore into CONS_METRICS_SHARES_SN 
		(DATE, TOTAL_SHARES_FB, TOTAL_SHARES_GP, TOTAL_SHARES_TW, TOTAL_SHARES_LI) 
	values 
		('". $ReferenceDayIs . "', $NumShares_FB, $NumShares_GP, $NumShares_TW, $NumShares_LD)";

echo "[SQL] - Insert Statement is: " . $sql_insert_metrics_shares_sn . "\n";
$insertion_success = mysql_query($sql_insert_metrics_shares_sn, $conn_w);
if ($insertion_success) {
	echo "[Success] - Metrics of Shares per Social Network  - Insertion Succeed!.\n";;
} else {
	echo "[Failed] - Metrics of Shares per Social Network - Insertion Failed.\n";
	die("[Error] - Failure on inserting data. Please contact your Administrator\n");
}

echo "[Debug] - ######################################################################################\n";
echo "[Debug] - # Finishing ETL to populate Shares Information at " . date("Y-m-d H:i:s")."\n";
echo "[Debug] - ######################################################################################\n";


?>