<?php
date_default_timezone_set('America/Sao_Paulo');
require ("../metrics/gapi-1.3/gapi.class.php");

echo "[Debug] - ######################################################################################<BR>\n";
echo "[Debug] - # Starting ETL to populate Google Analytics Information at " . date("Y-m-d H:i:s")."<BR>\n";
echo "[Debug] - ######################################################################################<BR>\n";
echo "[Info] - This Script does not used any user informed date... <BR>\n";
echo "[Info] - Reference Date is: " . date('Y-m-d', mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")))  . "<BR>\n";

// Populate Consolidated Metrics for Shares information
echo "[Debug] - Preparing to Fetch Info throught Google Analytics API... <BR>\n";

// Configuring basic GA API parameters
$gaUsername = 'team@teckler.com';
$gaPassword = 'T3ckl347';
$profileId = '71713009';
$dimensions = array('date');
$metrics = array('pageviews','uniquePageviews','bounces', 'visits', 'visitors', 'newVisits', 'percentNewVisits','avgTimeOnSite','avgTimeOnPage','entranceBounceRate');
$sort = '-date';
$timeInterval = '120';
$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
$toDate = date('Y-m-d', mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));

// Evoking GA API
$ga = new gapi($gaUsername, $gaPassword);
$mostPopular = $ga->requestReportData($profileId, $dimensions, $metrics, $sort, null, $fromDate, $toDate, 1, $timeInterval);

// Iterating over GA Result Set
foreach($ga->getResults() as $key => $result){
	
	$repDate[$key] 		= $result->getDate();
	$repPageview[$key] 	= $result->getPageviews();	
	$repVisits[$key]	= $result->getVisits();
	$repBounce[$key] 	= $result->getBounces();
	($repVisits[$key]!=0) ? $repPercBounce[$key] = ($repBounce[$key] / $repVisits[$key]): $repPercBounce[$key] = 0;
	($repVisits[$key]!=0) ? $repPagesPerVisit[$key] = $repPageview[$key] / $repVisits[$key] : $repPagesPerVisit[$key] = 0;
	$repVisitors[$key] = $result->getVisitors();
	$repNewVisits[$key] = $result->getNewVisits();
	$repPercentNewVisits[$key] = $result->getPercentNewVisits();
	$repPercentReturningVisits[$key] = (100 - $repPercentNewVisits[$key]);
	$repAvgTimeOnSite[$key] = $result->getAvgTimeOnSite();
	$repAvgTimeOnPage[$key] = $result->getAvgTimeOnPage();
	$repEntranceBounceRate[$key] = $result->getEntranceBounceRate();

	echo "[DEBUG] -- Loop Key is $key.... <BR>\n";

	echo "[Info] -- GA Metric Date is " . date("Y-m-d",strtotime($repDate[$key])) 	. "<BR>\n";
	$insert_value = "('" . date("Y-m-d",strtotime($repDate[$key])) . "'," ;

	echo "[Info] -- GA Metric Unique Pageviews is: " . $repPageview[$key] 	. "<BR>\n";
	$insert_value .= $repPageview[$key] . "," ; 

	echo "[Info] -- GA Metric Unique Visits is: "	. $repVisits[$key]	 	. "<BR>\n";
	$insert_value .= $repVisits[$key] . "," ; 

	echo "[Info] -- GA Metric Bounce Count is: " . $repBounce[$key] 	 	. "<BR>\n";
	$insert_value .=  $repBounce[$key] . ",";

	echo "[Info] -- GA Metric Bounce Percentage is: " . round($repPercBounce[$key],4)*100 . "%<BR>\n";
	$insert_value .=  round($repPercBounce[$key],4)*100 . ",";

	echo "[Info] -- GA Metric Visitor is: " . $repVisitors[$key]			. "<BR>\n";
	$insert_value .= $repVisitors[$key] . ",";

	echo "[Info] -- GA Metric New Visits is: " . $repNewVisits[$key]			. "<BR>\n";
	$insert_value .= $repNewVisits[$key] . ",";

	echo "[Info] -- GA Metric Percentage of New Visits is: " . round($repPercentNewVisits[$key],2)		. "%<BR>\n";
	$insert_value .=  round($repPercentNewVisits[$key],2) . ",";

	echo "[Info] -- GA Metric Percentage of Returning Visits is: " . round($repPercentReturningVisits[$key],2)	. "%<BR>\n";
	$insert_value .=  round($repPercentReturningVisits[$key],2). ",";

	echo "[Info] -- GA Metric Average Time on Site is: " . $repAvgTimeOnSite[$key]	. "<BR>\n";
	$insert_value .=  $repAvgTimeOnSite[$key]. ",";

	echo "[Info] -- GA Metric Average Time On Page is: " . $repAvgTimeOnPage[$key]	. "<BR>\n";
	$insert_value .=  $repAvgTimeOnPage[$key] . ",";

	echo "[Info] -- GA Metric Pages Per Visit is: " . round($repPagesPerVisit[$key],2) 	. "<BR>\n";
	$insert_value .=  round($repPagesPerVisit[$key],2) . ",";

	echo "[Info] -- GA Metric Entrance Bounce Rate is: " . round($repEntranceBounceRate[$key],2) 	. "<BR>\n";
	$insert_value .=  round($repEntranceBounceRate[$key],2) . ") ";

	echo "<BR>\n";

	if ($key <= 0 ) {

		$insert_sql_statement = 
			"insert ignore into DELTA_METRICS_GOOGLE_ANALYTICS_DAY 
				(DATE, TOTAL_PAGEVIEWS_DAY, TOTAL_PAGES_VISITS_DAY, TOTAL_BOUNCE_DAY, TOTAL_PERC_BOUNCE_DAY,
			 	TOTAL_VISITORS_DAY, TOTAL_NEW_VISITORS_DAY, TOTAL_PERC_NEW_VISITORS_DAY,TOTAL_PERC_RETURNING_VISITORS_DAY,
			 	AVG_TIME_ON_SITE, AVG_TIME_ON_PAGE, TOTAL_NUM_PAGES_PER_VISIT_DAY, TOTAL_ENTRANCE_BOUNCE_RATE_DAY) 
			values " . $insert_value;

	} else {

		$update_sql_statement[] = 
		//"insert ignore into DELTA_METRICS_GOOGLE_ANALYTICS_DAY 
		//		(DATE, TOTAL_PAGEVIEWS_DAY, TOTAL_PAGES_VISITS_DAY, TOTAL_BOUNCE_DAY, TOTAL_PERC_BOUNCE_DAY,
		//		 TOTAL_VISITORS_DAY, TOTAL_NEW_VISITORS_DAY, TOTAL_PERC_NEW_VISITORS_DAY,TOTAL_PERC_RETURNING_VISITORS_DAY,
		//		 AVG_TIME_ON_SITE, AVG_TIME_ON_PAGE, TOTAL_NUM_PAGES_PER_VISIT_DAY, TOTAL_ENTRANCE_BOUNCE_RATE_DAY) 
		//	values " . $insert_value;

		"update ignore DELTA_METRICS_GOOGLE_ANALYTICS_DAY
			set TOTAL_PAGEVIEWS_DAY = " . $repPageview[$key] . ", 
				TOTAL_PAGES_VISITS_DAY = " . $repVisits[$key] . ", 
				TOTAL_BOUNCE_DAY = " . $repBounce[$key] . ", 
				TOTAL_PERC_BOUNCE_DAY = " .  round($repPercBounce[$key],4)*100 . ",
				TOTAL_VISITORS_DAY = " . $repVisitors[$key] . ", 
				TOTAL_NEW_VISITORS_DAY = " . $repNewVisits[$key] . ", 
				TOTAL_PERC_NEW_VISITORS_DAY = " . round($repPercentNewVisits[$key],2)  . ",
				TOTAL_PERC_RETURNING_VISITORS_DAY = " . round($repPercentReturningVisits[$key],2). ",
				AVG_TIME_ON_SITE = " . $repAvgTimeOnSite[$key] . ", 
				AVG_TIME_ON_PAGE = " . $repAvgTimeOnPage[$key]. ", 
				TOTAL_NUM_PAGES_PER_VISIT_DAY = " . round($repPagesPerVisit[$key],2) . ", 
				TOTAL_ENTRANCE_BOUNCE_RATE_DAY = " . round($repEntranceBounceRate[$key],2) . "  
			where date(DATE) = '" . date("Y-m-d",strtotime($repDate[$key])).  "'";	
		

	}

}

echo "<pre>";
echo "[DEBUG] -- Insert Statement is: <BR>\n";
print_r($insert_sql_statement);
print "<BR>\n";
echo "[DEBUG] -- Update Statement is: <BR>\n";
print_r($update_sql_statement);
echo "</pre>";

// ///////////////////////////////////////////////////////////////////////
//
// Saving Consolidated Metrics into INTRANET DB
//
// //////////////////////////////////////////////////////////////////////

// Connecting to DB Server
require "conn.php";

// Preparing for Insertion
echo "[Debug] - Selecting DB Intranet for Insertion ...<BR>\n";
mysql_select_db(W_DB, $conn_w) or die('[Error] - Could not select database; ' . mysql_error());


// For development velocity reasons, I'll insert one row at a time, not performing Array Insert.
// Inserting the last day (key 0)
echo "[Debug] - Preparing SQL statement for insertion of last day metrics...<BR>\n";	
echo "[SQL] - Insert Statement is: " . $insert_sql_statement . "<br>\n";

$insertion_success = mysql_query($insert_sql_statement, $conn_w);
if ($insertion_success) {
	echo "[Success] - Delta Metrics for Google Analytics - Insertion Succeed!<BR>\n";;
} else {
	echo "[Failed] - Delta Metrics for Google Analytics - Insertion Failed!<BR>\n";
	die("[Error] - Failure on inserting data. Please contact your Administrator!<BR>\n");
}


// Consolidated Metrics - DELTA GOOGLE ANALYTICS METRICS DAY
foreach ($update_sql_statement as $key => $value) {
	echo "[Debug] - Preparing SQL statement for updating of consolidated metrics...<BR>\n";	
	echo "[SQL] - The Update Statement is: " . $value . "<br>\n";
	
	$insertion_success = mysql_query($value, $conn_w);
	if ($insertion_success) {
		echo "[Success] - Delta Metrics for Google Analytics - Updating Succeed!<BR>\n";;
	} else {
		echo "[Failed] - Delta Metrics for Google Analytics - Updating Failed!<BR>\n";
		die("[Error] - Failure on updating data. Please contact your Administrator!<BR>\n");
	}
}
?>

