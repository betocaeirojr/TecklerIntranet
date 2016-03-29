<?php

date_default_timezone_set("America/Sao_Paulo");
echo "Populating Revenue Metrics at Metrics Database\n";

if (!isset($_GET['day'])) {
	$ReferenceDayIs = date("Y-m-d");	
} else {
	$ReferenceDayIs = date("Y-m-d", strtotime($_GET['day']));
}

echo "[Debug] - ######################################################################################\n";
echo "[Debug] - # Starting ETL to populate Revenue Information at " . date("Y-m-d H:i:s")."\n";
echo "[Debug] - ######################################################################################\n";
echo "[Info] - Reference Date is: " . $ReferenceDayIs . "\n";

// Connecting to DB Server
require "conn.php";

// ///////////////////////////////////////////////////////////////////////
//
// Reading and Generating Shares Metrics from DB TECKLER
//
// //////////////////////////////////////////////////////////////////////

// Initializing Variables
echo "[Debug] - Starting to Initialize metrics variables ...\n";
$TotalExpectedRevenueToDate 		= 0;
$TotalActualRevenueToDate 			= 0;
$TotalExpectedRevenuePendingToDate 	= 0;
$TotalActualRevenueVerifiedToDate 	= 0;
$TotalActualRevenueRequestedToDate 	= 0;
$TotalActualRevenueWithdrawnToDate 	= 0;
$TotalActualRevenueErrorToDate 		= 0;
$AvgExpectedUserRevenueProfile		= 0;
$AvgExpectedUserRevenueTeck			= 0;
$WAvgExpectedUserRevenueViewedProfile = 0;
$WAvgExpectedUserRevenueViewedTeck	= 0;

$TotalExpectedRevenueDay 			= 0;
$TotalActualRevenueToDay 			= 0;
$TotalExpectedRevenuePendingDay 	= 0;
$TotalActualRevenueVerifiedDay 		= 0;
$TotalActualRevenueRequestedDay 	= 0;
$TotalActualRevenueWithdrawnDay 	= 0;
$TotalActualRevenueErrorDay 		= 0;

$TotalNumberOfFundTransferRequestedDay = 0;

echo "[Debug] - Selecting DB PAY for Consumption...\n";
mysql_select_db(R_DB_P, $conn_r) or die('[Error] - Could not select database; ' . mysql_error());

// Populate Consolidated Metrics for Shares information
echo "[Debug] - Preparing SQL Statements to Populate Consolidated Metrics for Revenue ... \n";

// REVENUE
	// Metric - TOTAL EXPECTED REVENUE TO DATE
	$sql_total_expected_revenue_todate = 
		 "select sum(EXPECTED_VALUE)*0.000001*0.98 as TotalExpectedAmmountToDate_USD from STATEMENT
		 	where date(FROM_UNIXTIME(DAY_SEQ*86400)) <= date('" . $ReferenceDayIs . "')";
	$result = mysql_query($sql_total_expected_revenue_todate, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Total Expected Revenue to date: " . $row['TotalExpectedAmmountToDate_USD']. "\n";
		$TotalExpectedRevenueToDate = ( !empty($row['TotalExpectedAmmountToDate_USD']) ? $row['TotalExpectedAmmountToDate_USD'] : 0) ;	
	}


	// Metric - TOTAL ACTUAL REVENUE TO DATE
	$sql_total_actual_revenue_todate = 
		 "select sum(VALUE)*0.000001*0.98 as TotalActualAmmountToDate_USD from STATEMENT
		 where date(FROM_UNIXTIME(DAY_SEQ*86400)) <= date('" . $ReferenceDayIs . "') - INTERVAL 31 DAY";
	$result = mysql_query($sql_total_actual_revenue_todate, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Total Actual Revenue to date (-31 days): " . $row['TotalActualAmmountToDate_USD']. "\n";
		$TotalActualRevenueToDate = (!empty($row['TotalActualAmmountToDate_USD']) ? $row['TotalActualAmmountToDate_USD'] : 0);	
	}

// REVENUE
	// Metric - TOTAL EXPECTED REVENUE OF THE DAY
	$sql_total_expected_revenue_day = 
		 "select sum(EXPECTED_VALUE)*0.000001*0.98 as TotalExpectedAmmountDay_USD from STATEMENT
		 where date(FROM_UNIXTIME(DAY_SEQ*86400)) = date('" . $ReferenceDayIs . "')";
	$result = mysql_query($sql_total_expected_revenue_day, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Total Expected Revenue of this Day: " . $row['TotalExpectedAmmountDay_USD']. "\n";
		$TotalExpectedRevenueDay = ( !empty($row['TotalExpectedAmmountDay_USD']) ? $row['TotalExpectedAmmountDay_USD'] : 0 );	
	}

	// Metric - TOTAL ACTUAL REVENUE OF THE DAY
	$sql_total_actual_revenue_day = 
		 "select sum(VALUE)*0.000001*0.98 as TotalActualAmmountDay_USD from STATEMENT
		 where date(FROM_UNIXTIME(DAY_SEQ*86400)) = date('" . $ReferenceDayIs . "') - INTERVAL 31 DAY";
	$result = mysql_query($sql_total_actual_revenue_day, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Total Actual Revenue of this Day (-31 days): " . $row['TotalActualAmmountDay_USD']. "\n";
		$TotalActualRevenueDay = ( !empty($row['TotalActualAmmountDay_USD']) ? $row['TotalActualAmmountDay_USD'] : 0 );	
	}

// ////////////////////////////////////////////////////////////////////////////
// REVENUE BY STATUS 
	// PENDING
		// TOTAL PENDING TO DATE
		// Metric - TOTAL ACTUAL REVENUE OF THE DAY
		$sql_total_expected_revenue_pending_todate = 
			 "select sum(EXPECTED_VALUE)*0.000001*0.98*0.7 as TotalUserAmmountPending
				from STATEMENT where status='pending' and date(FROM_UNIXTIME(DAY_SEQ*86400)) <= date('" . $ReferenceDayIs . "')";
		$result = mysql_query($sql_total_expected_revenue_pending_todate, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        echo "[Info] - Total Expected Ammount (User Share) Pending to date: " . $row['TotalUserAmmountPending']. "\n";
			$TotalExpectedRevenuePendingToDate = (!empty($row['TotalUserAmmountPending']) ? $row['TotalUserAmmountPending'] : 0) ;
		}
		
		// DELTA PENDING DAY
		$sql_total_expected_revenue_pending_day = 
			 "select 
			 	sum(EXPECTED_VALUE)*0.000001*0.98*0.7 as TotalUserAmmountPending from STATEMENT 
				where status='pending' and date(FROM_UNIXTIME(DAY_SEQ*86400)) = date('" . $ReferenceDayIs . "')";
		$result = mysql_query($sql_total_expected_revenue_pending_day, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        echo "[Info] - Total Expected Ammount (User Share) Pending of this Day: " . $row['TotalUserAmmountPending']. "\n";
			$TotalExpectedRevenuePendingDay = (!empty($row['TotalUserAmmountPending']) ? $row['TotalUserAmmountPending'] : 0);	
		}
		
	// VERIFIED
		// TOTAL VERIFIED TO DATE
		$sql_total_actual_revenue_verified_todate = 
			 "select sum(VALUE)*0.000001*0.98*0.7 as TotalUserAmmountVerified 
				from STATEMENT where status='ok' and date(FROM_UNIXTIME(DAY_SEQ*86400)) <= date('" . $ReferenceDayIs . "') - INTERVAL 31 DAY";
		$result = mysql_query($sql_total_actual_revenue_verified_todate, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        echo "[Info] - Total Expected Ammount (User Share) Verified to date (Ref: D-31 Days): " . $row['TotalUserAmmountVerified']. "\n";
			$TotalActualRevenueVerifiedToDate = ( !empty($row['TotalUserAmmountVerified']) ? $row['TotalUserAmmountVerified'] : 0 );	
		}
		// DELTA VERIFIED DAY
		$sql_total_actual_revenue_verified_day = 
			 "select sum(VALUE)*0.000001*0.98*0.7 as TotalUserAmmountVerified from STATEMENT 
				where status='ok' and date(FROM_UNIXTIME(DAY_SEQ*86400)) = date('" . $ReferenceDayIs . "') - INTERVAL 31 DAY";
		$result = mysql_query($sql_total_actual_revenue_verified_day, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        echo "[Info] - Total Expected Ammount (User Share) Verified today (Ref: D-31 days): " . $row['TotalUserAmmountVerified']. "\n";
			$TotalActualRevenueVerifiedDay = ( !empty($row['TotalUserAmmountVerified']) ? $row['TotalUserAmmountVerified'] : 0 );	
		}

	// REQUESTED
		// TOTAL REQUESTED TO DATE
		$sql_total_actual_revenue_requested_todate = 
			 "select sum(VALUE)*0.000001*0.98*0.7 as TotalUserAmmountRequested 
				from STATEMENT where status='requested' and date(REQUESTED_DATE) <= date('" . $ReferenceDayIs . "')";
		$result = mysql_query($sql_total_actual_revenue_requested_todate, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        echo "[Info] - Total Expected Ammount (User Share) Requested to date: " . $row['TotalUserAmmountRequested']. "\n";
			$TotalActualRevenueRequestedToDate = ( !empty($row['TotalUserAmmountRequested']) ? $row['TotalUserAmmountRequested'] : 0 );	
		}

		// DELTA REQUESTED DAY
		$sql_total_actual_revenue_requested_day = 
			 "select sum(VALUE)*0.000001*0.98*0.7 as TotalUserAmmountRequested 
				from STATEMENT where status='requested' and date(REQUESTED_DATE) = date('" . $ReferenceDayIs . "')";
		$result = mysql_query($sql_total_actual_revenue_requested_day, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        echo "[Info] - Total Expected Ammount (User Share) Requested of this Day: " . $row['TotalUserAmmountRequested']. "\n";
			$TotalActualRevenueRequestedDay = ( !empty($row['TotalUserAmmountRequested']) ? $row['TotalUserAmmountRequested'] : 0 ) ;	
		}

	// WITHDRAWN
		// TOTAL WITHDRAWN TO DATE
		$sql_total_actual_revenue_withdrawn_todate = 
			 "select sum(VALUE)*0.000001*0.98*0.7 as TotalUserAmmountWithdrawn 
				from STATEMENT where status='withdraw' and date(WITHDRAW_DATE) <= date('" . $ReferenceDayIs . "')";
		$result = mysql_query($sql_total_actual_revenue_withdrawn_todate, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        echo "[Info] - Total Expected Ammount (User Share) Withdrawn to date: " . $row['TotalUserAmmountWithdrawn']. "\n";
			$TotalActualRevenueWithdrawnToDate = (!empty($row['TotalUserAmmountWithdrawn']) ? $row['TotalUserAmmountWithdrawn'] :0);	
		}

		// DELTA WITHDRAW DAY
		$sql_total_actual_revenue_withdrawn_day = 
			 "select 
			 	sum(VALUE)*0.000001*0.98*0.7 as TotalUserAmmountWithdrawn from STATEMENT 
				where status='withdraw' and date(WITHDRAW_DATE) = date('" . $ReferenceDayIs . "')";
		$result = mysql_query($sql_total_actual_revenue_withdrawn_day, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        echo "[Info] - Total Expected Ammount (User Share) Withdrawn of this Day: " . $row['TotalUserAmmountWithdrawn']. "\n";
			$TotalActualRevenueWithdrawnDay = (!empty($row['TotalUserAmmountWithdrawn']) ? $row['TotalUserAmmountWithdrawn'] : 0) ;	
		}

	// ERROR
		// TOTAL ERROR TO DATE
		$sql_total_actual_revenue_error_todate = 
			 "select sum(VALUE)*0.000001*0.98*0.7 as TotalUserAmmountError 
				from STATEMENT  where status='error' and date(REQUESTED_DATE) <= date('" . $ReferenceDayIs . "')";
		$result = mysql_query($sql_total_actual_revenue_error_todate, $conn_r);
	    while ($row = mysql_fetch_assoc($result)) {
	        echo "[Info] - Total Expected Ammount (User Share) with Error to date: " . $row['TotalUserAmmountError'] . "\n";
			$TotalActualRevenueErrorToDate = (!empty($row['TotalUserAmmountError']) ? $row['TotalUserAmmountError'] : 0) ;	
		}

		// DELTA ERROR DAY
		$sql_total_actual_revenue_error_day = 
			 "select sum(VALUE)*0.000001*0.98*0.7 as TotalUserAmmountError 
				from STATEMENT where status='error' and date(REQUESTED_DATE) = date('" . $ReferenceDayIs . "')";
		$result = mysql_query($sql_total_actual_revenue_error_day, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        echo "[Info] - Total Expected Ammount (User Share) with Error of this Day: " . $row['TotalUserAmmountError']. "\n";
			$TotalActualRevenueErrorDay = ( !empty($row['TotalUserAmmountError']) ? $row['TotalUserAmmountError'] : 0);	
		}

// //////////////////////////////////////////
	// AVERAGE EXPECTED REVENUE PER PROFILE
	$sql_avg_revenue_profile = 
		"select 
			a.NumExpectedUserRevenue / b.NumProfiles as AvgExpectedUserRevenueProfile
		from 
			(select (sum(EXPECTED_VALUE)*0.000001*0.98*0.7) as NumExpectedUserRevenue from STATEMENT) a, 
			(select count(distinct PROFILE_ID) as NumProfiles from TECKLER.PROFILE) b";
	$result = mysql_query($sql_avg_revenue_profile, $conn_r);
	while ($row = mysql_fetch_assoc($result)){
	    echo "[Info] - Average User Revenue per Profile: " . $row['AvgExpectedUserRevenueProfile']. "\n";
		$AvgExpectedUserRevenueProfile = $row['AvgExpectedUserRevenueProfile'];	
	}

	// WEIGHTED EXPECTED REVENUE PER VIEWED PROFILE
	$sql_wavg_revenue_viewed_profile = 
		"select 
			a.NumExpectedUserRevenue / b.NumViewedProfiles as WAvgExpectedUserRevenueViewedProfile
		from 
			(select (sum(EXPECTED_VALUE)*0.000001*0.98*0.7) as NumExpectedUserRevenue from STATEMENT) a, 
			(select count(distinct PROFILE_ID) as NumViewedProfiles from TECKLER.POST where PAGE_VIEWS>1) b";
	$result = mysql_query($sql_wavg_revenue_viewed_profile, $conn_r);
	while ($row = mysql_fetch_assoc($result)){
	    echo "[Info] - Weighted Average User Revenue per Viewed Profile: " . $row['WAvgExpectedUserRevenueViewedProfile']. "\n";
		$WAvgExpectedUserRevenueViewedProfile = ( !empty($row['WAvgExpectedUserRevenueViewedProfile']) ? $row['WAvgExpectedUserRevenueViewedProfile'] : 0 );	
	}

	// AVERAGE EXPECTED REVENUE PER TECK
	$sql_avg_revenue_teck = 
		"select 
			a.NumExpectedUserRevenue / b.NumTecks as AvgExpectedUserRevenueTeck
		from 
			(select (sum(EXPECTED_VALUE)*0.000001*0.98*0.7) as NumExpectedUserRevenue from STATEMENT) a, 
			(select count(POST_ID) as NumTecks from TECKLER.POST) b";
	$result = mysql_query($sql_avg_revenue_teck, $conn_r);
	while ($row = mysql_fetch_assoc($result)){
	    echo "[Info] - Average User Revenue per Teck: " . $row['AvgExpectedUserRevenueTeck']. "\n";
		$AvgExpectedUserRevenueTeck = (!empty($row['AvgExpectedUserRevenueTeck']) ? $row['AvgExpectedUserRevenueTeck'] : 0 );	
	}

	// WEIGHTED EXPECTED REVENUE PER VIEWED TECK
	$sql_wavg_revenue_viewed_teck = 
		"select 
			a.NumExpectedUserRevenue / b.NumViewedTecks as WAvgExpectedUserRevenueViewedTeck
		from 
			(select (sum(EXPECTED_VALUE)*0.000001*0.98*0.7) as NumExpectedUserRevenue from STATEMENT) a, 
			(select count(POST_ID) as NumViewedTecks from TECKLER.POST where PAGE_VIEWS>1) b";
	$result = mysql_query($sql_wavg_revenue_viewed_teck, $conn_r);
	while ($row = mysql_fetch_assoc($result)){
	    echo "[Info] - Weighted Average User Revenue per Viewed Teck: " . $row['WAvgExpectedUserRevenueViewedTeck']. "\n";
		$WAvgExpectedUserRevenueViewedTeck = ( !empty($row['WAvgExpectedUserRevenueViewedTeck']) ? $row['WAvgExpectedUserRevenueViewedTeck'] : 0 );	
	}


// TRANSFER REQUESTS
	// Number of Transfers request by day
	$sql_num_transfer_requests_day = 
		"select count(a.PaypalEmail) as NumTransferRequestes, date(a.Data) as DateOfRequest, sum(a.value) as TotalAmmountRequested 
		from 
			(SELECT sum(VALUE)*0.000001*0.7 as VALUE, date(REQUESTED_DATE) as Data, PAYPAL_EMAIL as PaypalEmail 
			FROM STATEMENT  
			where date(FROM_UNIXTIME(DAY_SEQ * 86400)) <= date('".$ReferenceDayIs."') 
			group by date(REQUESTED_DATE), PAYPAL_EMAIL 
			order by date(REQUESTED_DATE) desc) a 
		group by date(Data) 
		order by date(Data) DESC
		limit 1";
	$result = mysql_query($sql_num_transfer_requests_day, $conn_r);
	while ($row = mysql_fetch_assoc($result)){
	    echo "[Info] - Number of Fund Transfer Requested at the day: " . $row['NumTransferRequestes']. "\n";
		$TotalNumberOfFundTransferRequestedDay = ( !empty($row['NumTransferRequestes']) ? $row['WAvgExpectedUserRevenueViewedTeck'] : 0 );	
	}

	// Number of Transfers request by month



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
$sql_insert_cons_metrics_revenue = 
	"insert ignore into CONS_METRICS_REVENUE 
		(DATE,TOTAL_EXPECTED_REVENUE_TODATE, TOTAL_ACTUAL_REVENUE_TODATE, TOTAL_PENDING_TODATE, TOTAL_VERIFIED_TODATE, TOTAL_REQUESTED_TODATE, TOTAL_WITHDRAWN_TODATE, TOTAL_ERROR_TODATE, AVG_REVENUE_PROFILE, AVG_REVENUE_TECK, WAVG_REVENUE_VIEWED_PROFILES, WAVG_REVENUE_VIEWED_TECKS)  
	values 
		('". $ReferenceDayIs . "', $TotalExpectedRevenueToDate,$TotalActualRevenueToDate, $TotalExpectedRevenuePendingToDate, $TotalActualRevenueVerifiedToDate, $TotalActualRevenueRequestedToDate, $TotalActualRevenueWithdrawnToDate, $TotalActualRevenueErrorToDate, $AvgExpectedUserRevenueProfile, $AvgExpectedUserRevenueTeck, $WAvgExpectedUserRevenueViewedProfile, $WAvgExpectedUserRevenueViewedTeck, TotalNumberOfFundTransferRequestedDay)";

echo "[SQL] - Insert Statement is: " . $sql_insert_cons_metrics_revenue . "\n";
$insertion_success = mysql_query($sql_insert_cons_metrics_revenue, $conn_w);
if ($insertion_success) {
	echo "[Success] - Total Consolidated Metrics of Revenue - Insertion Succeed!.\n";;
} else {
	echo "[Failed] - Total Consolidated Metrics of Revenue - Insertion Failed.\n";
	die("[Error] - Failure on inserting data. Please contact your Administrator\n");
}

// DELTA Metrics - DAY
echo "[Debug] - Preparing SQL statement for insertion of consolidated metrics...\n";
$sql_insert_delta_metrics_revenue_day = 
	"insert ignore into DELTA_METRICS_REVENUE_DAY 
		(DATE,TOTAL_EXPECTED_REVENUE_DAY, TOTAL_PENDING_DAY, TOTAL_VERIFIED_DAY, TOTAL_REQUESTED_DAY, TOTAL_WITHDRAWN_DAY, TOTAL_ERROR_DAY, TOTAL_TRANSFERS_REQUESTS_DAY)  
	values 
		('". $ReferenceDayIs . "', $TotalExpectedRevenueDay, $TotalExpectedRevenuePendingDay, $TotalActualRevenueVerifiedDay, $TotalActualRevenueRequestedDay, $TotalActualRevenueWithdrawnDay, $TotalActualRevenueErrorDay)";



echo "[SQL] - Insert Statement is: " . $sql_insert_delta_metrics_revenue_day . "\n";
$insertion_success = mysql_query($sql_insert_delta_metrics_revenue_day, $conn_w);
if ($insertion_success) {
	echo "[Success] - Total Delta Metrics of Revenue of this Day - Insertion Succeed!.\n";;
} else {
	echo "[Failed] - Total Delta Metrics of Revenue of this Day - Insertion Failed.\n";
	die("[Error] - Failure on inserting data. Please contact your Administrator\n");
}

echo "[Debug] - ######################################################################################\n";
echo "[Debug] - # Finishing ETL to populate Revenue Information at " . date("Y-m-d H:i:s")."\n";
echo "[Debug] - ######################################################################################\n";

?>