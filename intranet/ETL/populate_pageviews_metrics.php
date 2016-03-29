<?php

date_default_timezone_set("America/Sao_Paulo");
echo "Populating Online Pageviews Metrics at Metrics Database\n";

if (!isset($_GET['day'])) {
	$ReferenceDayIs = date("Y-m-d");	
} else {
	$ReferenceDayIs = date("Y-m-d", strtotime($_GET['day']));
}

echo "[Debug] - ######################################################################################\n";
echo "[Debug] - # Starting ETL to populate Pageviews Information at " . date("Y-m-d H:i:s")."\n";
echo "[Debug] - ######################################################################################\n";
echo "[Info] - Reference Date is: " . $ReferenceDayIs . "\n";


// Connecting to DB Server
require "conn.php";

// ///////////////////////////////////////////////////////////////////////
//
// Reading and Generating Pageviews Metrics from DB TECKLER
//
// //////////////////////////////////////////////////////////////////////

// Initializing Variables
$NumTotalExpectedPageviews				= 0;
$AvgExpectedPageviewTeck				= 0;
$AvgExpectedPageviewProfile				= 0; 
$TotalNumTecksWOPageview				= 0; 
$TotalPercTecksWOPageview				= 0;
$TotalNumProfilesWOPageview				= 0; 
$TotalPercProfilesWOPageview			= 0; 
$WAvgExpectedPageviewsViewedTecks		= 0; 
$WAvgExpectedPageviewsViewedProfiles 	= 0;
$TotalNumPageviewsPerAudioTeck			= 0;
$TotalNumPageviewsPerDocumentTeck		= 0;
$TotalNumPageviewsPerImageTeck			= 0;
$TotalNumPageviewsPerTextTeck			= 0;
$TotalNumPageviewsPerVideoTeck			= 0;

echo "[Debug] - Selecting DB PAY for Consumption...\n";
mysql_select_db(R_DB_P, $conn_r) or die('[Error] - Could not select database; ' . mysql_error());

// Populate Consolidated Metrics for Shares information
echo "[Debug] - Preparing SQL Statements to Populate Consolidated Metrics for Pageviews ... \n";

// PAGEVIEWS
// Metric - TOTAL EXPECTED PAGEVIEWS
	$sql_total_expected_pageviews = 
		 "select  sum(EXPECTED_VIEWS) as ExpectedPageViews from STATEMENT where date(FROM_UNIXTIME(DAY_SEQ*86400)) <= date('" . $ReferenceDayIs . "')";
	$result = mysql_query($sql_total_expected_pageviews, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Total Number of Expected Pageview: " . $row['ExpectedPageViews']. "\n";
		$NumTotalExpectedPageviews = ( ( (!empty($row['ExpectedPageViews'])) )  ? $row['ExpectedPageViews'] : 0 );			
	}

// Metric - Average Pageviews per Teck
	$sql_avg_pageviews_per_teck = 
		"select b.NumExpectedPageViews / a.NumTecks as AvgExpectedPVTecks
		from 
			(select count(POST_ID) as NumTecks from TECKLER.POST where date(CREATION_DATE) <= date('". $ReferenceDayIs . "') ) a,
			(select sum(EXPECTED_VIEWS) as NumExpectedPageViews, sum(VIEWS) as NumActualPageViews from STATEMENT where date(FROM_UNIXTIME(DAY_SEQ*86400)) <= date('" . $ReferenceDayIs . "') ) b";
	$result = mysql_query($sql_avg_pageviews_per_teck, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Average Number of Pageviews per Teck: " . round($row['AvgExpectedPVTecks'],2). "\n";
		$AvgExpectedPageviewTeck = ( !empty($row['AvgExpectedPVTecks']) ? round($row['AvgExpectedPVTecks'],2) : 0);	
	}

// Metric - Average Pageviews per Profile
	$sql_avg_pageviews_per_profile = 
		"select  b.NumExpectedPV / a.NumActiveProfiles as AvgExpectedPVProfile 
		from 
			(select count(distinct PROFILE_ID) as NumActiveProfiles from TECKLER.POST where date(CREATION_DATE) <= date('". $ReferenceDayIs . "') ) a, 
			(select sum(EXPECTED_VIEWS) as NumExpectedPV from STATEMENT where date(FROM_UNIXTIME(DAY_SEQ*86400)) <= date('" . $ReferenceDayIs . "') ) b";
	$result = mysql_query($sql_avg_pageviews_per_profile, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Average Number of Pageviews per Profile: " . round($row['AvgExpectedPVProfile'],2). "\n";
		$AvgExpectedPageviewProfile = (!empty($row['AvgExpectedPVProfile']) ? round($row['AvgExpectedPVProfile'],2) : 0 );	
	}

// Metric - Total and Percentage of Profiles Without Pageviews
	$sql_profiles_without_pageviews = 
		"select (a.NumTotalProfiles - b.NumProfilesWithPV) as NumProfilesWOPV, (1 - (b.NumProfilesWithPV / a.NumTotalProfiles)) as PercProfilesWOPV 
		from 
			(select (count(PROFILE_ID)) as NumTotalProfiles from TECKLER.PROFILE where date(PROFILE_CREATION_DATE) <= date('" . $ReferenceDayIs . "')) a, 
			(select (count(distinct AD_UNIT_NAME)) as NumProfilesWithPV from STATEMENT where (date(FROM_UNIXTIME(DAY_SEQ*86400)) <= date('" . $ReferenceDayIs . "'))) b";
	$result = mysql_query($sql_profiles_without_pageviews, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Total Number of Profiles Without Pageviews: " 		. $row['NumProfilesWOPV'] 				. "\n";
        echo "[Info] - Total Percentage of Profiles Without Pageviews: " 	. round($row['PercProfilesWOPV'],4)*100 . "%\n";
		$TotalNumProfilesWOPageview 	= (!empty($row['NumProfilesWOPV']) 	? $row['NumProfilesWOPV'] 			: 0);	
		$TotalPercProfilesWOPageview	= (!empty($row['PercProfilesWOPV']) ? round($row['PercProfilesWOPV'],4) : 0); 
	}

// Metric - Total and Percentage of Tecks Without Pageviews
	$sql_tecks_without_pageviews = 
		"select a.NumTecksWOPV as NumTecksWOPV, (a.NumTecksWOPV / b.NumTotalTecks) as PercTecksWOPV 
		from 
			(select count(POST_ID) as NumTecksWOPV from TECKLER.POST where PAGE_VIEWS <= 1 and date(CREATION_DATE) <= date('". $ReferenceDayIs . "') ) a, 
			(select count(POST_ID) as NumTotalTecks from TECKLER.POST where date(CREATION_DATE) <= date('". $ReferenceDayIs . "')) b";
	$result = mysql_query($sql_tecks_without_pageviews, $conn_r);
    while ($row = mysql_fetch_assoc($result)) {
        echo "[Info] - Total Number of Tecks Without Pageviews: " 		. $row['NumTecksWOPV'] . "\n";
        echo "[Info] - Total Percentage of Tecks Without Pageviews: " 	. round($row['PercTecksWOPV'],4)*100 . "%\n";
		$TotalNumTecksWOPageview 	= ( !empty($row['NumTecksWOPV']) 	? $row['NumTecksWOPV'] 				: 0 );	
		$TotalPercTecksWOPageview	= ( !empty($row['PercTecksWOPV']) 	? round($row['PercTecksWOPV'],4) 	: 0 ) ; 
	}

// Metric - Weighted Average Pageviews per Teck
	$sql_wavg_pageviews_per_viewed_teck = 
		"select  b.NumExpectedPageViews / a.NumTecks as WAvgExpectedPVTecks
		from 
			(select count(POST_ID) as NumTecks from TECKLER.POST where PAGE_VIEWS > 1 and date(CREATION_DATE) <= date('". $ReferenceDayIs . "') ) a,
			(select sum(EXPECTED_VIEWS) as NumExpectedPageViews, sum(VIEWS) as NumActualPageViews from STATEMENT where date(FROM_UNIXTIME(DAY_SEQ*86400)) <= date('" . $ReferenceDayIs . "') ) b";
	$result = mysql_query($sql_wavg_pageviews_per_viewed_teck, $conn_r);
    while ($row = mysql_fetch_assoc($result)) {
        echo "[Info] - Weighted Average Number of Pageviews per Viewed Teck 
        (considering only Tecks with at least 2 pageviews) :" . round($row['WAvgExpectedPVTecks'],4). "\n";
		$WAvgExpectedPageviewsViewedTecks = ( !empty($row['WAvgExpectedPVTecks']) ? round($row['WAvgExpectedPVTecks'], 4) : 0 );	
	}

// Metric - Weighted Average Pageviews per Profile
	$sql_wavg_pageviews_per_viewed_profile = 
		"select  (a.NumExpectedPV / b.NumProfilesWithPV) as WAvgExpectedPVProfile 
		from 
			(select sum(EXPECTED_VIEWS) as NumExpectedPV  from STATEMENT  where date(FROM_UNIXTIME(DAY_SEQ*86400)) <= date('". $ReferenceDayIs . "')) a, 
			(select (count(distinct AD_UNIT_NAME)) as NumProfilesWithPV from STATEMENT  where (date(FROM_UNIXTIME(DAY_SEQ*86400)) <= date('". $ReferenceDayIs . "'))) b";
	$result = mysql_query($sql_wavg_pageviews_per_viewed_profile, $conn_r);
    while ($row = mysql_fetch_assoc($result)){
        echo "[Info] - Weighted Average Number of Pageviews per Viewed Profile: " . round($row['WAvgExpectedPVProfile'],2). "\n";
		$WAvgExpectedPageviewsViewedProfiles = ( !empty($row['WAvgExpectedPVProfile']) ? round($row['WAvgExpectedPVProfile'],4) : 0 );	
	}

// Metric - Pageviews per Teck Type
	$sql_pageviews_per_tecktype = 
		"select count(POST_ID) as NumTecks, sum(PAGE_VIEWS) as NumPageviews, TYPE as TeckType from TECKLER.POST group by TYPE order by count(POST_ID) DESC";
	$result = mysql_query($sql_pageviews_per_tecktype, $conn_r);
	while ($row = mysql_fetch_assoc($result)){
		switch (strtolower($row['TeckType'])) {
			case 'a':
				$TotalNumPageviewsPerAudioTeck = $row['NumPageviews'];
				echo "[Info] - Pageviews for Audio Tecks is: " . $TotalNumPageviewsPerAudioTeck . "\n";
				break;
			case 'd':
				$TotalNumPageviewsPerDocumentTeck = $row['NumPageviews'];
				echo "[Info] - Pageviews for Document Tecks is: " . $TotalNumPageviewsPerDocumentTeck . "\n";
				break;
			case 'i':
				$TotalNumPageviewsPerImageTeck = $row['NumPageviews'];
				echo "[Info] - Pageviews for Image Tecks is: " . $TotalNumPageviewsPerImageTeck . "\n";
				break;
			case 't':
				$TotalNumPageviewsPerTextTeck = $row['NumPageviews'];
				echo "[Info] - Pageviews for Text Tecks is: " . $TotalNumPageviewsPerTextTeck . "\n";
				break;
			case 'v':
				$TotalNumPageviewsPerVideoTeck = $row['NumPageviews'];
				echo "[Info] - Pageviews for Video Tecks is: " . $TotalNumPageviewsPerVideoTeck . "\n";
				break;
			default:
				break;
		}
	}

// Metric - Aging Pageviews
	//$todayis = date("Y-m-d");
	for ($i = strtotime('2013-09-19') ; $i <= strtotime($ReferenceDayIs) ; $i = $i + (60 * 60 * 24) ){
		$teckCreationDate = date('Y-m-d', $i);
		$sql_calculate_aging_pageviews = 
			"select  
				date(dv.DAY) as ReferenceDate, 
				sum(dv.VIEWS) as Pageviews 
			from 
				TECKLER.DAILY_VIEWS dv  
			where 
				date(dv.DAY) > date('0000-00-00') and  
				dv.POST_ID in 
					(select POST_ID from TECKLER.POST 
					where date(PUBLISH_DATE)=date('". date('Y-m-d', $i) . "') ) 
			group by date(dv.DAY) 
			order by date(dv.DAY) DESC";

		$ReferenceDate = $teckCreationDate;
		// Setting up initial values for Period Variables
		$Pageviews_P1 = 0; $Pageviews_P2 = 0; $Pageviews_P3 = 0; 
		$Pageviews_P4 = 0; $Pageviews_P5 = 0; $Pageviews_P6 = 0;
		
		// Accumulating Pageviews into specific period slots.
		$result = mysql_query($sql_calculate_aging_pageviews, $conn_r);

		// DEBUG
		//echo "<PRE>";
		//print_r($result);
		//echo "</PRE>";

		
		while ($row = mysql_fetch_assoc($result) ){	
			// First Period
			$ReferenceDay = strtotime($ReferenceDate);
			if (strtotime($row['ReferenceDate']) == $ReferenceDay) {
				$Pageviews_P1 = $row['Pageviews'];
			}
			// Second Period (D2 to D7)
			$ReferenceDay_Plus1 	= $ReferenceDay + (60*60*24*1);
			$ReferenceDay_Plus7 	= $ReferenceDay + (60*60*24*6);
			if 	(	(strtotime($row['ReferenceDate']) >= $ReferenceDay_Plus1) AND 
					(strtotime($row['ReferenceDate']) <= $ReferenceDay_Plus7) ) {
				$Pageviews_P2 = $Pageviews_P2 +  $row['Pageviews']; 
			}
			// Third Period (D8 to D14)
			$ReferenceDay_Plus8 	= $ReferenceDay + (60*60*24*7);
			$ReferenceDay_Plus14 	= $ReferenceDay + (60*60*24*13);
			if 	(	(strtotime($row['ReferenceDate']) >= $ReferenceDay_Plus8) AND 
					(strtotime($row['ReferenceDate']) <= $ReferenceDay_Plus14) ) {
				$Pageviews_P3 = $Pageviews_P3 + $row['Pageviews']; 
			}
			// Fourth Period (D15 to D30)
			$ReferenceDay_Plus15	= $ReferenceDay + (60*60*24*14);
			$ReferenceDay_Plus30	= $ReferenceDay + (60*60*24*30);
			if 	(	(strtotime($row['ReferenceDate']) >= $ReferenceDay_Plus15) AND 
					(strtotime($row['ReferenceDate']) <= $ReferenceDay_Plus30) ) {
				$Pageviews_P4 = $Pageviews_P4 + $row['Pageviews']; 
			}
			// Fifth Period (D31 to D60)
			$ReferenceDay_Plus31 	= $ReferenceDay + (60*60*24*31);
			$ReferenceDay_Plus60	= $ReferenceDay + (60*60*24*60);
			if 	(	(strtotime($row['ReferenceDate']) >= $ReferenceDay_Plus31) AND 
					(strtotime($row['ReferenceDate']) <= $ReferenceDay_Plus60) ) {
				$Pageviews_P5 = $Pageviews_P5 + $row['Pageviews']; 
			}
			// Sixth Period (D61 to D90)
			$ReferenceDay_Plus61 	= $ReferenceDay + (60*60*24*61);
			$ReferenceDay_Plus90	= $ReferenceDay + (60*60*24*90);
			if 	(	(strtotime($row['ReferenceDate']) >= $ReferenceDay_Plus61) AND 
					(strtotime($row['ReferenceDate']) <= $ReferenceDay_Plus90) ) {
				$Pageviews_P6 = $Pageviews_P6 + $row['Pageviews']; 
			}
		}
		// Accumulating Results for returning
		$AgingPVMatrix[] = 
		array(
			"Date"	=> $ReferenceDate,
			"PV-P1 (Creation Day)"	=> $Pageviews_P1,
			"PV-P2 (D2 to D6)"		=> $Pageviews_P2,
			"PV-P3 (D7 to D14)"		=> $Pageviews_P3,
			"PV-P4 (D15 to D30)"	=> $Pageviews_P4,
			"PV-P5 (D31 to D60)"	=> $Pageviews_P5,
			"PV-P6 (D61 to D90)"	=> $Pageviews_P6,
			);

		$PVDecaiRateMatrix[] = 
		array(
			"Date" 	=> $ReferenceDate,
			"P1" 	=> $Pageviews_P1,
			"P2P1" 	=> round($Pageviews_P2/$Pageviews_P1, 4), 
			"P3P2"	=> round($Pageviews_P3/$Pageviews_P2, 4),
			"P4P3"	=> round($Pageviews_P4/$Pageviews_P3, 4),
			"P5P4"	=> round($Pageviews_P5/$Pageviews_P4, 4),
			"P6P5"	=> round($Pageviews_P6/$Pageviews_P5, 4)
			);

	}

// Metric - Daily Pageviews Info
	$sql_daily_views_info_day = 
		"select 
			date(DAY) as ReferenceDate,
			sum(VIEWS) as SumViewsTeckler, 
			sum(DFP_VIEWS) as SumViewsDPF, 
			COUNT(DISTINCT POST_ID) as TotalTecks, 
			COUNT(DISTINCT PROFILE_ID) as TotalProfiles 
		from 
			TECKLER.DAILY_VIEWS 
		where 
			date(DAY) = '" . $ReferenceDayIs . "'  
		group by 
			date(DAY) 
		order by 
			date(DAY) DESC";
	echo "[Debug] - SQL Stament for Daily Views is: " . $sql_daily_views_info_day . "\n"; 
	$result = mysql_query($sql_daily_views_info_day, $conn_r);
	    while ($row = mysql_fetch_assoc($result)){
			$DailyViewsInfo[] = array(
						"Reference Date" 	=> $row['ReferenceDate'],
						"SumViewsTeckler" 	=> (!empty($row['SumViewsTeckler']) ? $row['SumViewsTeckler'] 	: 1), 
						//"SumViewsDFP"		=> (!empty($row['SumViewsDFP']) 	? $row['SumViewsDFP'] 		: 1),
						"TotalTecks" 		=> (!empty($row['TotalTecks']) 		? $row['TotalTecks'] 		: 1),
						"TotalProfiles"		=> (!empty($row['TotalProfiles']) 	? $row['TotalProfiles'] 	: 1) 
										);

			echo "[Info] - Daily Views info at "  . date("Y-m-d", strtotime($ReferenceDayIs)) . " are: \n"; 
			echo "[Info] -- Views Teckler: "	 	. $row['SumViewsTeckler'] 	. "\n";
			//echo "[Info] -- Views DFP: " 			. $row['SumViewsTeckler']	. "\n";
			echo "[Info] -- Total Tecks: " 			. $row['TotalTecks'] 		. "\n";
			echo "[Info] -- Total Profiles: " 		. $row['TotalProfiles'] 	. "\n";
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
	$sql_insert_cons_metrics_pageviews = 
		"insert ignore into CONS_METRICS_PAGEVIEWS_EXPECTED 
			(DATE, TOTAL_EXPECTED_PAGEVIEWS, AVG_PAGEVIEWS_TECK,  AVG_PAGEVIEWS_PROFILE, TOTAL_TECKS_WO_PAGEVIEWS, PERC_TECKS_WO_PAGEVIEWS,TOTAL_PROFILES_WO_PAGEVIEWS, PERC_PROFILES_WO_PAGEVIEWS, WAVG_PAGEVIEWS_VIEWED_TECKS, WAVG_PAGEVIEWS_VIEWED_PROFILES)  
		values 
			('". $ReferenceDayIs . "',$NumTotalExpectedPageviews, $AvgExpectedPageviewTeck, $AvgExpectedPageviewProfile, $TotalNumTecksWOPageview, $TotalPercTecksWOPageview,$TotalNumProfilesWOPageview, $TotalPercProfilesWOPageview, $WAvgExpectedPageviewsViewedTecks, $WAvgExpectedPageviewsViewedProfiles)";

	echo "[SQL] - Insert Statement is: " . $sql_insert_cons_metrics_pageviews . "\n";
	$insertion_success = mysql_query($sql_insert_cons_metrics_pageviews, $conn_w);
	if ($insertion_success) {
		echo "[Success] - Total Consolidated Metrics of Pageviews - Insertion Succeed!.\n";;
	} else {
		echo "[Failed] - Total Consolidated Metrics of Pageviews - Insertion Failed.\n";
		die("[Error] - Failure on inserting data. Please contact your Administrator\n");
	}


// Consolidated Metrics - Pageviews Per Teck Type
	echo "[Debug] - Preparing SQL statement for insertion of consolidated metrics...\n";
	$sql_insert_cons_metrics_pageviews_type = 
		"insert ignore into CONS_METRICS_PAGEVIEWS_TYPE 
			(DATE, TOTAL_PAGEVIEWS_AUDIO, TOTAL_PAGEVIEWS_DOCUMENT, TOTAL_PAGEVIEWS_IMAGE, TOTAL_PAGEVIEWS_TEXT, TOTAL_PAGEVIEWS_VIDEO)  
		values 
			('". $ReferenceDayIs . "',$TotalNumPageviewsPerAudioTeck, $TotalNumPageviewsPerDocumentTeck, $TotalNumPageviewsPerImageTeck, $TotalNumPageviewsPerTextTeck, $TotalNumPageviewsPerVideoTeck)";

	echo "[SQL] - Insert Statement is: " . $sql_insert_cons_metrics_pageviews_type . "\n";
	$insertion_success = mysql_query($sql_insert_cons_metrics_pageviews_type, $conn_w);
	if ($insertion_success) {
		echo "[Success] - Total Consolidated Metrics of Pageviews - Insertion Succeed!.\n";;
	} else {
		echo "[Failed] - Total Consolidated Metrics of Pageviews - Insertion Failed.\n";
		die("[Error] - Failure on inserting data. Please contact your Administrator\n");
	}

// Consolidated Metrics - Aging Pageviews
	echo "[Debug] - Preparing SQL statement for insertion of consolidated metrics...\n";
	echo "[Debug] - Aging Matrix";
	print_r($AgingPVMatrix);
	echo "\n";
	foreach ($AgingPVMatrix as $key => $value) {
		$sql_insert_aging_info = 
			"insert into 
				CONS_AGING_PAGEVIEWS (DATE, P1, P2, P3, P4, P5, P6) values ('".
				$value['Date'] 				. "'," 	. $value['PV-P1 (Creation Day)'] 	.", " . $value['PV-P2 (D2 to D6)'] . "," . 
				$value['PV-P3 (D7 to D14)']	. ", "	. $value['PV-P4 (D15 to D30)'] 		.", " . $value['PV-P5 (D31 to D60)'] . ", " . 
				$value['PV-P6 (D61 to D90)'] . ") 
			ON DUPLICATE KEY UPDATE 
				P1 = " .$value['PV-P1 (Creation Day)']	. ", P2 = " .$value['PV-P2 (D2 to D6)'] 		. ",
				P3 = " .$value['PV-P3 (D7 to D14)'] 	. ", P4 = " .$value['PV-P4 (D15 to D30)'] 		. ",
				P5 = " .$value['PV-P5 (D31 to D60)'] 	. ", P6 = " .$value['PV-P6 (D61 to D90)'];

		echo "[SQL] - Insert Statement is: " . $sql_insert_aging_info . "\n";
		$insertion_success = mysql_query($sql_insert_aging_info, $conn_w);
		if ($insertion_success) {
			echo "[Success] - Total Consolidated Metrics of Aging Pageviews - Insertion Succeed!.\n";;
		} else {
			echo "[Failed] - Total Consolidated Metrics of Aging Pageviews - Insertion Failed.\n";
			die("[Error] - Failure on inserting data. Please contact your Administrator\n");
		}
	}

// Consolidated Metrics - Pageviews Decai Rate
	echo "[Debug] - Preparing SQL statement for insertion of consolidated metrics...\n";
	echo "[Debug] - Pageviews Decai Rate Matrix";
	print_r($PVDecaiRateMatrix);
	echo "\n";
	foreach ($PVDecaiRateMatrix as $key => $value) {
		$sql_insert_pv_decai_rate = 
			"insert into CONS_METRICS_PAGEVIEWS_DECAI_RATE 
			(DATE, PAGEVIEWS_P1, PAGEVIEWS_P2P1, PAGEVIEWS_P3P2, PAGEVIEWS_P4P3, PAGEVIEWS_P5P4, PAGEVIEWS_P6P5) values ('".
				$value['Date'] 				. "'," 	. $value['P1'] 	.", " . $value['P2P1'] . "," . 
				$value['P3P2']	. ", "	. $value['P4P3'] 		.", " . $value['P5P4'] . ", " . 
				$value['P6P5'] . ") 
			ON DUPLICATE KEY UPDATE 
				PAGEVIEWS_P1 = " .$value['P1']	. ", PAGEVIEWS_P2P1 = " .$value['P2P1'] 		. ",
				PAGEVIEWS_P3P2 = " .$value['P3P2'] 	. ", PAGEVIEWS_P4P3 = " .$value['P4P3'] 		. ",
				PAGEVIEWS_P5P4 = " .$value['P5P4'] 	. ", PAGEVIEWS_P6P5 = " .$value['P6P5'];

		echo "[SQL] - Insert Statement is: " . $sql_insert_pv_decai_rate . "\n";
		$insertion_success = mysql_query($sql_insert_pv_decai_rate, $conn_w);
		if ($insertion_success) {
			echo "[Success] - Total Consolidated Metrics of Pageviews Decai Rate - Insertion Succeed!.\n";;
		} else {
			echo "[Failed] - Total Consolidated Metrics of Pageviews Decai Rate - Insertion Failed.\n";
			die("[Error] - Failure on inserting data. Please contact your Administrator\n");
		}
	}

// Delta Metrics - Daily Views
	echo "[Debug] - Preparing SQL statement for insertion of daily views delta metrics... \n";
	$sql_insert_daily_views = "insert ignore 
			DELTA_METRICS_DAILY_VIEWS(DATE, TOTAL_SUM_VIEWS, TOTAL_PROFILES, TOTAL_TECKS) values ('" . 
				$DailyViewsInfo[0]['Reference Date'] 	. "', " . 
				$DailyViewsInfo[0]['SumViewsTeckler'] 	. ", " 	.
				$DailyViewsInfo[0]['TotalProfiles'] 	. ", " 	.
				$DailyViewsInfo[0]['TotalTecks'] 		. ")";

	echo "[SQL] - Insert Statement is: " . $sql_insert_daily_views . "\n";
	
	$insertion_success = mysql_query($sql_insert_daily_views, $conn_w);
		if ($insertion_success) {
			echo "[Success] - Total Delta Metrics of Daily Views - Insertion Succeed!.\n";;
		} else {
			echo "[Failed] - Total Delta Metrics of Daily Views - Insertion Failed.\n";
			die("[Error] - Failure on inserting data. Please contact your Administrator\n");
		}


echo "[Debug] - ######################################################################################\n";
echo "[Debug] - # Finishing ETL to populate Pageviews Information at " . date("Y-m-d H:i:s")."\n";
echo "[Debug] - ######################################################################################\n";


?>