<?php

date_default_timezone_set("America/Sao_Paulo");
echo "Populating Average Pageviews Metrics at Metrics Database\n";

if (!isset($_GET['day'])) {
	$ReferenceDayIs = date("Y-m-d");	
} else {
	$ReferenceDayIs = date("Y-m-d", strtotime($_GET['day']));
}

echo "[Debug] - ######################################################################################\n";
echo "[Debug] - # Starting ETL to populate Average Pageviews Information at " . date("Y-m-d H:i:s")."\n";
echo "[Debug] - ######################################################################################\n";
echo "[Info] - Reference Date is: " . $ReferenceDayIs . "\n";

$RealReferenceDayIs = date('Y-m-d' , strtotime($ReferenceDayIs) - (60 * 60 * 24 * 3));
echo "[Debug] - Real Reference Date is: " . $RealReferenceDayIs . "\n";


// Connecting to DB Server
require "conn.php";

// ///////////////////////////////////////////////////////////////////////
//
// Reading and Generating Pageviews Metrics from DB TECKLER
//
// //////////////////////////////////////////////////////////////////////

// Initializing Variables
$AvgPageviews_Teckler_Day			= 0;
$AvgPageviews_DFP_Day				= 0; 
$AvgPageviews_Global_Day			= 0;

$AvgPageviews_Teckler_Month			= 0;
$AvgPageviews_DFP_Month				= 0; 
$AvgPageviews_Global_Month			= 0;


$sql_avg_pageviews_per_teck_per_week = 
	"select 
		(sum(a.SumPageviewsTeckler) / count(a.TeckID)) as AvgPageviewsTecks,
		(sum(a.SumPageviewsDFP) / count(a.TeckID)) as AvgPageviewsDFP,
		((sum(a.SumPageviewsDFP) + sum(a.SumPageviewsTeckler)) / (count(a.TeckID) *2)) as AvgPageviewsGlobal, 
		week(a.RefDate, 7) as ReferenceWeek, 
		date(a.RefDate) as ReferenceDate  
	from
		(select  
			sum(VIEWS) as SumPageviewsTeckler,  
			sum(DFP_VIEWS) as SumPageviewsDFP,  
			DAY as RefDate,  
			POST_ID as TeckID 
		from  
			DAILY_VIEWS  
		where  
			date(DAY) <> date('0000-00-00')  
		group by 
			POST_ID, date(DAY)  
		order by  
			date(DAY)) a
	group by
		week(a.RefDate, 7) 
	order by 
		week(a.RefDate, 7) DESC";

echo "[Debug] - Selecting DB TECKLER for Consumption...\n";
mysql_select_db(R_DB_T, $conn_r) or die('[Error] - Could not select database; ' . mysql_error());

// Populate Consolidated Metrics for Average information
	echo "[Debug] - Preparing SQL Statements to Populate Metrics for Average Pageviews in Tecks at Day ... \n";

	// Average PAGEVIEWS
	// Metric - AVERAGE PAGEVIEWS PER DAY
	$sql_avg_pageviews_per_teck_per_day = 
		"select 
			(sum(a.SumPageviewsTeckler) / count(a.TeckID)) as AvgPageviewsTecks,
			(sum(a.SumPageviewsDFP) / count(a.TeckID)) as AvgPageviewsDFP,
			((sum(a.SumPageviewsDFP) + sum(a.SumPageviewsTeckler)) / (count(a.TeckID) *2)) as AvgPageviewsGlobal, 
			date(a.RefDate) as ReferenceDate
		from
			(select  
				sum(VIEWS) as SumPageviewsTeckler,  
				sum(DFP_VIEWS) as SumPageviewsDFP,  
				DAY as RefDate,  
				POST_ID as TeckID 
			from  
				DAILY_VIEWS  
			where  
				date(DAY) <> date('0000-00-00') and 
				date(DAY) = date('". $RealReferenceDayIs . "')  
			group by 
				POST_ID, date(DAY)  
			order by  
				date(DAY)) a
		group by
			date(a.RefDate) 
		order by 
			date(a.RefDate) DESC";
	//echo "[SQL] - Select statement is " . $sql_avg_pageviews_per_teck_per_day . "\n";
	$result = mysql_query($sql_avg_pageviews_per_teck_per_day, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Total Number of Average Pageview Day - Teckler : " . $row['AvgPageviewsTecks']. "\n";
        echo "[Info] - Total Number of Average Pageview Day - DFP : " . $row['AvgPageviewsDFP']. "\n";
        echo "[Info] - Total Number of Average Pageview Day - Global : " . $row['AvgPageviewsGlobal']. "\n";

		$AvgPageviews_Teckler_Day 	= ( ( (!empty($row['AvgPageviewsTecks'])) )  ? $row['AvgPageviewsTecks'] : 0 );
		$AvgPageviews_DFP_Day 		= ( ( (!empty($row['AvgPageviewsDFP'])) )  ? $row['AvgPageviewsDFP'] : 0 );
		$AvgPageviews_Global_Day 	= ( ( (!empty($row['AvgPageviewsGlobal'])) )  ? $row['AvgPageviewsGlobal'] : 0 );

	}

	// Metric - AVERAGE PAGEVIEWS PER MONTH
	$date_parts = date_parse($ReferenceDayIs);
	
	// START DEBUGING
	// print_r($date_parts);
	// END DEBUGING

	if ($date_parts['day'] == 03 && $date_parts['month'] == 01) {
		$year = $date_parts['year'] - 1;
		$month = 12;
		$ConsolidateMonthly = TRUE;
	} elseif ($date_parts['day'] == 03 && $date_parts['month'] > 01){
		$year = $date_parts['year'];
		$month = (int)$date_parts['month'] - 1;
		$ConsolidateMonthly = TRUE;
	} else {
		$ConsolidateMonthly = FALSE;
	}

	// Metric - AVERAGE PAGEVIEWS PER MONTH 
	echo "[Debug] - Preparing SQL Statements to Populate Metrics for Average Pageviews in Tecks at Month ... \n";
	if ($ConsolidateMonthly) {
		$sql_avg_pageviews_per_teck_per_month = 
			"select 
				(sum(a.SumPageviewsTeckler) / count(a.TeckID)) as AvgPageviewsTecks,
				(sum(a.SumPageviewsDFP) / count(a.TeckID)) as AvgPageviewsDFP,
				((sum(a.SumPageviewsDFP) + sum(a.SumPageviewsTeckler)) / (count(a.TeckID) *2)) as AvgPageviewsGlobal, 
				date(a.RefDate) as ReferenceDate
			from
				(select  
					sum(VIEWS) as SumPageviewsTeckler,  
					sum(DFP_VIEWS) as SumPageviewsDFP,  
					DAY as RefDate,  
					POST_ID as TeckID 
				from  
					DAILY_VIEWS  
				where  
					month(DAY) <> '00' and 
					month(DAY) = '" . $month . "' and 
					year(DAY) = '". $year . "' 
				group by 
					POST_ID, month(DAY)  
				order by  
					month(DAY)) a 
			group by 
				month(a.RefDate) 
			order by 
				month(a.RefDate) DESC";
		//echo "[SQL] - Select statement is " . $sql_avg_pageviews_per_teck_per_month . "\n";
		$result = mysql_query($sql_avg_pageviews_per_teck_per_month, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
	        echo "[Info] - Total Number of Average Pageview Month - Teckler : " . $row['AvgPageviewsTecks']. "\n";
	        echo "[Info] - Total Number of Average Pageview Month - DFP : " 	. $row['AvgPageviewsDFP']. "\n";
	        echo "[Info] - Total Number of Average Pageview Month - Global : " 	. $row['AvgPageviewsGlobal']. "\n";

			$AvgPageviews_Teckler_Month 	= ( ( (!empty($row['AvgPageviewsTecks'])) )  ? $row['AvgPageviewsTecks'] : 0 );
			$AvgPageviews_DFP_Month 		= ( ( (!empty($row['AvgPageviewsDFP'])) )  ? $row['AvgPageviewsDFP'] : 0 );
			$AvgPageviews_Global_Month 		= ( ( (!empty($row['AvgPageviewsGlobal'])) )  ? $row['AvgPageviewsGlobal'] : 0 );

		}	
	}
	 
	// Updating DFP and Global Views
	$sql_get_real_dfp_global_views = 
		"select DATE, AVERAGE_PV_TECKLER, AVERAGE_PV_DFP, AVERAGE_PV_GLOBAL";


// Populate TOP 100 Tecks/Profiles Per Pageview Per Day
	$sql_top_100_tecks_per_pv = 
		"select 
			sum(VIEWS) as CountViews, 
			POST_ID as TeckID  
		from 
			DAILY_VIEWS 
		where 
			date(DAY) = date('" . $ReferenceDayIs . "') 
		group by 
			POST_ID   
		order by 
			sum(VIEWS) DESC 
		limit 100";

	$ranking_tecks = 1;
	$result = mysql_query($sql_top_100_tecks_per_pv, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
    	$top100_tecks_per_pv[] = "('" . $ReferenceDayIs . "'," . $ranking_tecks . "," . $row['CountViews'] . "," . $row['TeckID']. ")";
    	$ranking_tecks++;
    }

	$sql_top_100_profiles_per_pv = 
		"select 
			sum(VIEWS) as CountViews, 
			PROFILE_ID as ProfileID  
		from 
			DAILY_VIEWS 
		where 
			date(DAY) = date('" . $ReferenceDayIs . "') 
		group by 
			PROFILE_ID   
		order by 
			sum(VIEWS) DESC 
		limit 100";

	$ranking_profiles = 1;
	$result = mysql_query($sql_top_100_profiles_per_pv, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
    	$top100_profiles_per_pv[] = "('" . $ReferenceDayIs . "'," . $ranking_profiles . "," . $row['CountViews'] . "," . $row['ProfileID']. ")";
    	$ranking_profiles++;
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
	$sql_insert_cons_metrics_pageviews_day = 
		"insert ignore into DELTA_METRICS_AVERAGE_PAGEVIEWS_DAY 
			(DATE, AVERAGE_PV_TECKLER, AVERAGE_PV_DFP, AVERAGE_PV_GLOBAL)  
		values 
			('". $RealReferenceDayIs . "',$AvgPageviews_Teckler_Day, $AvgPageviews_DFP_Day, $AvgPageviews_Global_Day)";

	echo "[SQL] - Insert Statement is: " . $sql_insert_cons_metrics_pageviews_day . "\n";
	$insertion_success = mysql_query($sql_insert_cons_metrics_pageviews_day, $conn_w);
	if ($insertion_success) {
		echo "[Success] - Total Consolidated Metrics of Average Pageviews per Day - Insertion Succeed!.\n";;
	} else {
		echo "[Failed] - Total Consolidated Metrics of Average Pageviews per Day - Insertion Failed.\n";
		die("[Error] - Failure on inserting data. Please contact your Administrator\n");
	}

// Consolidated Metrics - Pageviews Per Teck Type
	if ($ConsolidateMonthly) {
		echo "[Debug] - Preparing SQL statement for insertion of consolidated metrics...\n";
		$sql_insert_cons_metrics_pageviews_month = 
			"insert ignore into DELTA_METRICS_AVERAGE_PAGEVIEWS_MONTH 
				(DATE, AVERAGE_PV_TECKLER, AVERAGE_PV_DFP, AVERAGE_PV_GLOBAL)  
			values 
				('". $year . "-" . $month  . "-28',$AvgPageviews_Teckler_Month, $AvgPageviews_DFP_Month, $AvgPageviews_Global_Month)";

		echo "[SQL] - Insert Statement is: " . $sql_insert_cons_metrics_pageviews_month . "\n";
		
		$insertion_success = mysql_query($sql_insert_cons_metrics_pageviews_month, $conn_w);
		if ($insertion_success) {
			echo "[Success] - Total Consolidated Metrics of Average Pageviews per Month - Insertion Succeed!.\n";;
		} else {
			echo "[Failed] - Total Consolidated Metrics of Average Pageviews per Month - Insertion Failed.\n";
			die("[Error] - Failure on inserting data. Please contact your Administrator\n");
		}

	}


// TOP 100 Tecks Per Pageview
	echo "[Debug] - Preparing SQL statement for insertaion of TOP 100 Tecks Per Pageview at Day...\n";
	echo "[Debug] - Preparing Insert Values\n";
	$insert_values_top_100_tecks = implode(',', $top100_tecks_per_pv);
	$sql_insert_top100_tecks_per_pv = 
		"insert ignore into 
			TOP_100_TECKS_PER_PAGEVIEW (DATE, RANKING, TOTAL_PAGEVIEWS, TECK_ID) 
			values " . $insert_values_top_100_tecks;			
	$insertion_success = mysql_query($sql_insert_top100_tecks_per_pv, $conn_w);
	if ($insertion_success) {
		echo "[Success] - Total Consolidated Metrics of TOP 100 Tecks per Day - Insertion Succeed!.\n";;
	} else {
		echo "[Failed] - Total Consolidated Metrics of TOP 100 Tecks per Day - Insertion Failed.\n";
		die("[Error] - Failure on inserting data. Please contact your Administrator\n");
	}

// TOP 100 PROFILES Per Pageview
	echo "[Debug] - Preparing SQL statement for insertaion of TOP 100 PROFILES Per Pageview at Day...\n";
	echo "[Debug] - Preparing Insert Values\n";
	$insert_values_top_100_profiles = implode(',', $top100_profiles_per_pv);
	$sql_insert_top100_profiles_per_pv = 
		"insert ignore into 
			TOP_100_PROFILES_PER_PAGEVIEW (DATE, RANKING, TOTAL_PAGEVIEWS, PROFILE_ID) 
			values " . $insert_values_top_100_profiles;			
	$insertion_success = mysql_query($sql_insert_top100_profiles_per_pv, $conn_w);
	if ($insertion_success) {
		echo "[Success] - Total Consolidated Metrics of TOP 100 Profiles per Day - Insertion Succeed!.\n";;
	} else {
		echo "[Failed] - Total Consolidated Metrics of TOP 100 Profiles per Day - Insertion Failed.\n";
		die("[Error] - Failure on inserting data. Please contact your Administrator\n");
	}


echo "[Debug] - ######################################################################################\n";
echo "[Debug] - # Finishing ETL to populate Pageviews Information at " . date("Y-m-d H:i:s")."\n";
echo "[Debug] - ######################################################################################\n";


?>