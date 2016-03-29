<?php
date_default_timezone_set('America/Sao_Paulo');
require ("../metrics/gapi-1.3/gapi.class.php");

echo "[Debug] - ######################################################################################\n";
echo "[Debug] - # Starting ETL to populate Google Analytics Information at " . date("Y-m-d H:i:s")."\n";
echo "[Debug] - ######################################################################################\n";
echo "[Info] - This Script does not used any user informed date... \n";
echo "[Info] - Reference Date is: " . date('Y-m-d', mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")))  . "\n";

// Populate Consolidated Metrics for Shares information
echo "[Debug] - Preparing to Fetch Info throught Google Analytics API... \n";

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

// ////////////////////////////
// GETTING GENERAL INFO
// 

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

		echo "[DEBUG] -- Loop Key is $key.... \n";

		echo "[Info] -- GA Metric Date is " . date("Y-m-d",strtotime($repDate[$key])) 	. "\n";
		$insert_value = "('" . date("Y-m-d",strtotime($repDate[$key])) . "'," ;

		echo "[Info] -- GA Metric Unique Pageviews is: " . $repPageview[$key] 	. "\n";
		$insert_value .= $repPageview[$key] . "," ; 

		echo "[Info] -- GA Metric Unique Visits is: "	. $repVisits[$key]	 	. "\n";
		$insert_value .= $repVisits[$key] . "," ; 

		echo "[Info] -- GA Metric Bounce Count is: " . $repBounce[$key] 	 	. "\n";
		$insert_value .=  $repBounce[$key] . ",";

		echo "[Info] -- GA Metric Bounce Percentage is: " . round($repPercBounce[$key],4)*100 . "%\n";
		$insert_value .=  round($repPercBounce[$key],4)*100 . ",";

		echo "[Info] -- GA Metric Visitor is: " . $repVisitors[$key]			. "\n";
		$insert_value .= $repVisitors[$key] . ",";

		echo "[Info] -- GA Metric New Visits is: " . $repNewVisits[$key]			. "\n";
		$insert_value .= $repNewVisits[$key] . ",";

		echo "[Info] -- GA Metric Percentage of New Visits is: " . round($repPercentNewVisits[$key],2)		. "%\n";
		$insert_value .=  round($repPercentNewVisits[$key],2) . ",";

		echo "[Info] -- GA Metric Percentage of Returning Visits is: " . round($repPercentReturningVisits[$key],2)	. "%\n";
		$insert_value .=  round($repPercentReturningVisits[$key],2). ",";

		echo "[Info] -- GA Metric Average Time on Site is: " . $repAvgTimeOnSite[$key]	. "\n";
		$insert_value .=  $repAvgTimeOnSite[$key]. ",";

		echo "[Info] -- GA Metric Average Time On Page is: " . $repAvgTimeOnPage[$key]	. "\n";
		$insert_value .=  $repAvgTimeOnPage[$key] . ",";

		echo "[Info] -- GA Metric Pages Per Visit is: " . round($repPagesPerVisit[$key],2) 	. "\n";
		$insert_value .=  round($repPagesPerVisit[$key],2) . ",";

		echo "[Info] -- GA Metric Entrance Bounce Rate is: " . round($repEntranceBounceRate[$key],2) 	. "\n";
		$insert_value .=  round($repEntranceBounceRate[$key],2) . ") ";

		echo "\n";

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

// ///////////////////////////////////////////////
// GETTING INFO FOR FREQUENCY
// 

	$dimensions = array('visitCount');
	$sort = 'visitCount';
	$toDate = date("Y-m-d", strtotime('- 1 days'));
	$fromDate = date("Y-m-d", strtotime('- 31 days'));
	$metrics = array('visitors', 'pageviews');
	$maxResults = 1000;
	$mostPopular = $ga->requestReportData($profileId, $dimensions, $metrics, $sort, null, $fromDate, $toDate, 1, $maxResults);

	foreach($ga->getResults() as $key => $result){		 
		$repVisitsCount[$key] 		= $result->getVisitCount();
		$repPageviews[$key]			= $result->getPageviews();
		$repVisitors[$key]			= $result->getVisitors();
		// Assembling returning data structure	
		$gaResults2 = array(
			"Visit Count" 	=> (int)$repVisitsCount[$key],
			"Visitors"		=> (int)$repVisitors[$key],
			"Pageviews"		=> (int)$repPageviews[$key]
			);
		
		echo "[Info] - GA Metrics - Date: " 			. date("Y-m-d", strtotime('- 1 days')) . "\n";
		echo "[Info] - GA Metrics - Visits Count is: " 	. $gaResults2['Visit Count'] . "\n";
		echo "[Info] - GA Metrics - Visitors: " 		. $gaResults2['Visitors'] . "\n";
		echo "[Info] - GA Metrics - Pageviews: " 		. $gaResults2['Pageviews'] . "\n";

		$insert_values = 
			"('" . date("Y-m-d", strtotime('- 1 days'))	. "', " .
				$gaResults2['Visit Count'] 					. ", "	. 
				$gaResults2['Visitors']						. "," . 
				$gaResults2['Pageviews'] 					. ")";

		$insert_sql_statement_frequency[] = 
				"insert ignore into CONS_METRICS_FREQUENCY 
					(DATE,  INFO_NUMBER_OF_VISITS,  INFO_NUMBER_OF_VISITORS,  INFO_NUMBER_OF_PAGEVIEWS) 
				values " . $insert_values;

		echo "[Debug] - GA Metric for Visits Count is: " . "insert ignore into CONS_METRICS_FREQUENCY 
					(DATE,  INFO_NUMBER_OF_VISITS,  INFO_NUMBER_OF_VISITORS,  INFO_NUMBER_OF_PAGEVIEWS) 
				values " . $insert_values . "\n";

	} 

// ///////////////////////////////////////////////
// GETTING INFO FOR RECENCY
//
	$dimensions = array('daysSinceLastVisit');
	$sort = 'daysSinceLastVisit';
	$toDate = date("Y-m-d", strtotime('- 1 days'));
	$fromDate = date("Y-m-d", strtotime('- 90 days'));
	$metrics = array( 'pageviews','visitors');
	$mostPopular = $ga->requestReportData($profileId, $dimensions, $metrics, $sort, null, $fromDate, $toDate, 1, null);

	foreach($ga->getResults() as $key => $result){		 
		$repDaysSinceLastVisit[$key] 	= $result->getDaysSinceLastVisit();
		$repPageviews[$key]				= $result->getPageviews();
		$repVisitors[$key]				= $result->getVisitors();

		// Assembling returning data structure	
		$gaResults3 = array(
			"Days Since Last Visit" 	=> (int)$repDaysSinceLastVisit[$key],
			"Pageviews"					=> (int)$repPageviews[$key],
			"Visitors"					=> (int)$repVisitors[$key]
			);

		echo "[Info] - GA Metrics - Date: " 					. date("Y-m-d", strtotime('- 1 days')) . "\n";
		echo "[Info] - GA Metrics - Days Since Last Visit: " 	. $gaResults3['Days Since Last Visit'] . "\n";
		echo "[Info] - GA Metrics - Visitors: " 				. $gaResults3['Visitors'] . "\n";
		echo "[Info] - GA Metrics - Pageviews: " 				. $gaResults3['Pageviews'] . "\n";

		$insert_values = 
			"('" . date("Y-m-d", strtotime('- 1 days'))	. "', " .
				$gaResults3['Days Since Last Visit'] 	. ", "	. 
				$gaResults3['Visitors']					. "," . 
				$gaResults3['Pageviews'] 				. ")";

		$insert_sql_statement_recency[] = 
				"insert ignore into CONS_METRICS_RECENCY 
					(DATE,  INFO_NUMBER_OF_DAYS_SINCE_LAST_VISIT,  INFO_NUMBER_OF_VISITORS,  INFO_NUMBER_OF_PAGEVIEWS) 
				values " . $insert_values;

		echo "[Debug] - GA Metric for Visits Count is: " . "insert ignore into CONS_METRICS_RECENCY  
					(DATE,  INFO_NUMBER_OF_DAYS_SINCE_LAST_VISIT,  INFO_NUMBER_OF_VISITORS,  INFO_NUMBER_OF_PAGEVIEWS) 
				values " . $insert_values . "\n";
	}

// ///////////////////////////////////////////////
// GETTING INFO FOR NEW USER PAGE
// 
	$dimensions = array('date');
	$metrics = array('pageviews','uniquePageviews','bounces', 'visits', 'visitors', 'newVisits', 'percentNewVisits','avgTimeOnSite','avgTimeOnPage','entranceBounceRate');
	$sort = '-date';
	$filterNewUserPage = 'pagePath=~ (\/user\/new_user)$';
	$timeInterval = '120';
	$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
	$toDate = date('Y-m-d', mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));

	$mostPopular = $ga->requestReportData($profileId, $dimensions, $metrics, $sort, $filterNewUserPage, $fromDate, $toDate, 1, $timeInterval);

	// Iterating over GA Result Set
	foreach($ga->getResults() as $keyNU => $resultNU){
		
		$repDateNewUser[$keyNU] 					= $resultNU->getDate();
		$repPageviewNewUser[$keyNU] 				= $resultNU->getPageviews();	
		$repVisitsNewUser[$keyNU]					= $resultNU->getVisits();
		$repBounceNewUser[$keyNU] 					= $resultNU->getBounces();
		($repVisitsNewUser[$keyNU]!= 0 ) ? $repPercBounceNewUser[$keyNU] = ($repBounceNewUser[$keyNU] / $repVisitsNewUser[$keyNU]): $repPercBounceNewUser[$keyNU] = 0;
		($repVisitsNewUser[$keyNU]!= 0 ) ? $repPagesPerVisitNewUser[$keyNU] = $repPageviewNewUser[$keyNU] / $repVisitsNewUser[$keyNU] : $repPagesPerVisitNewUser[$keyNU] = 0;
		$repVisitorsNewUser[$keyNU] 				= $resultNU->getVisitors();
		$repNewVisitsNewUser[$keyNU] 				= $resultNU->getNewVisits();
		$repPercentNewVisitsNewUser[$keyNU] 		= $resultNU->getPercentNewVisits();
		$repPercentReturningVisitsNewUser[$keyNU] 	= (100 - $repPercentNewVisits[$keyNU]);
		$repAvgTimeOnSiteNewUser[$keyNU]	 		= $resultNU->getAvgTimeOnSite();
		$repAvgTimeOnPageNewUser[$keyNU] 			= $resultNU->getAvgTimeOnPage();
		$repEntranceBounceRateNewUser[$keyNU] 		= $resultNU->getEntranceBounceRate();

		echo "[DEBUG] -- Loop Key is $key.... \n";
		
		echo "[Info] -- GA Metric Date for New User Page is " . date("Y-m-d",strtotime($repDateNewUser[$keyNU])) 	. "\n";
		$insert_value_newuser = "('" . date("Y-m-d",strtotime($repDateNewUser[$keyNU])) . "'," ;
		
		echo "[Info] -- GA Metric Unique Pageviews for New User Page is: " . $repPageviewNewUser[$keyNU] 	. "\n";
		$insert_value_newuser .= $repPageviewNewUser[$keyNU] . "," ; 

		echo "[Info] -- GA Metric Unique Visits for New User Page is: "	. $repVisitsNewUser[$keyNU]	 	. "\n";
		$insert_value_newuser .= $repVisitsNewUser[$keyNU] . "," ; 

		echo "[Info] -- GA Metric Bounce Count for New User Page is: " . $repBounceNewUser[$keyNU] 	 	. "\n";
		$insert_value_newuser .=  $repBounceNewUser[$keyNU] . ",";

		echo "[Info] -- GA Metric Bounce Percentage for New User Page is: " . round($repPercBounceNewUser[$keyNU],4)*100 . "%\n";
		$insert_value_newuser .=  round($repPercBounceNewUser[$keyNU],4)*100 . ",";

		echo "[Info] -- GA Metric Visitor for New User Page is: " . $repVisitorsNewUser[$keyNU]			. "\n";
		$insert_value_newuser .= $repVisitorsNewUser[$keyNU] . ",";

		echo "[Info] -- GA Metric New Visits for New User Page is: " . $repNewVisitsNewUser[$keyNU]			. "\n";
		$insert_value_newuser .= $repNewVisitsNewUser[$keyNU] . ",";

		echo "[Info] -- GA Metric Percentage of New Visits for New User Page is: " . round($repPercentNewVisitsNewUser[$keyNU],2)		. "%\n";
		$insert_value_newuser .=  round($repPercentNewVisitsNewUser[$keyNU],2) . ",";

		echo "[Info] -- GA Metric Percentage of Returning Visits for New User Page is: " . round($repPercentReturningVisitsNewUser[$keyNU],2)	. "%\n";
		$insert_value_newuser .=  round($repPercentReturningVisitsNewUser[$keyNU],2). ",";

		echo "[Info] -- GA Metric Average Time on Site for New User Page is: " . $repAvgTimeOnSiteNewUser[$keyNU]	. "\n";
		$insert_value_newuser .=  $repAvgTimeOnSiteNewUser[$keyNU]. ",";

		echo "[Info] -- GA Metric Average Time On Page for New User Page is: " . $repAvgTimeOnPageNewUser[$keyNU]	. "\n";
		$insert_value_newuser .=  $repAvgTimeOnPageNewUser[$keyNU] . ",";

		echo "[Info] -- GA Metric Pages Per Visit for New User Page is: " . round($repPagesPerVisitNewUser[$keyNU],2) 	. "\n";
		$insert_value_newuser .=  round($repPagesPerVisitNewUser[$keyNU],2) . ",";

		echo "[Info] -- GA Metric Entrance Bounce Rate for New User Page is: " . round($repEntranceBounceRateNewUser[$keyNU],2) 	. "\n";
		$insert_value_newuser .=  round($repEntranceBounceRateNewUser[$keyNU],2) . ") ";

		echo "\n";

		if ($keyNU <= 0 ) {

			$insert_sql_statement_newuser = 
				"insert ignore into DELTA_METRICS_GA_NEW_USER_DAY 
					(DATE, TOTAL_PAGEVIEWS_DAY, TOTAL_PAGES_VISITS_DAY, TOTAL_BOUNCE_DAY, TOTAL_PERC_BOUNCE_DAY,
				 	TOTAL_VISITORS_DAY, TOTAL_NEW_VISITORS_DAY, TOTAL_PERC_NEW_VISITORS_DAY,TOTAL_PERC_RETURNING_VISITORS_DAY,
				 	AVG_TIME_ON_SITE, AVG_TIME_ON_PAGE, TOTAL_NUM_PAGES_PER_VISIT_DAY, TOTAL_ENTRANCE_BOUNCE_RATE_DAY) 
				values " . $insert_value;

		} else {

			$update_sql_statement_newuser[] =
			"update ignore DELTA_METRICS_GA_NEW_USER_DAY
				set TOTAL_PAGEVIEWS_DAY = " . $repPageviewNewUser[$keyNU] . ", 
					TOTAL_PAGES_VISITS_DAY = " . $repVisitsNewUser[$keyNU] . ", 
					TOTAL_BOUNCE_DAY = " . $repBounceNewUser[$keyNU] . ", 
					TOTAL_PERC_BOUNCE_DAY = " .  round($repPercBounceNewUser[$keyNU],4)*100 . ",
					TOTAL_VISITORS_DAY = " . $repVisitorsNewUser[$keyNU] . ", 
					TOTAL_NEW_VISITORS_DAY = " . $repNewVisitsNewUser[$keyNU] . ", 
					TOTAL_PERC_NEW_VISITORS_DAY = " . round($repPercentNewVisitsNewUser[$keyNU],2)  . ",
					TOTAL_PERC_RETURNING_VISITORS_DAY = " . round($repPercentReturningVisitsNewUser[$keyNU],2). ",
					AVG_TIME_ON_SITE = " . $repAvgTimeOnSiteNewUser[$keyNU] . ", 
					AVG_TIME_ON_PAGE = " . $repAvgTimeOnPageNewUser[$keyNU]. ", 
					TOTAL_NUM_PAGES_PER_VISIT_DAY = " . round($repPagesPerVisitNewUser[$keyNU],2) . ", 
					TOTAL_ENTRANCE_BOUNCE_RATE_DAY = " . round($repEntranceBounceRateNewUser[$keyNU],2) . "  
				where date(DATE) = '" . date("Y-m-d",strtotime($repDateNewUser[$keyNU])).  "'";	
		}

	}

// ///////////////////////////////////////////////
// GETTING INFO FOR TRAFFIC SOURCE
//
	$dimensions = array('date', 'medium');
	$sort = '-date';
	$toDate = date("Y-m-d", strtotime('- 1 days'));
	$fromDate = date("Y-m-d", strtotime('- 90 days'));
	$metrics = array('pageviews', 'visits', 'visitors');;
	//$ga_TrafficSource = new gapi($this->gaUsername, $this->gaPassword);
	$mostPopular = $ga->requestReportData($profileId, $dimensions, $metrics, $sort, null, $fromDate, $toDate, 1, null);

	foreach($ga->getResults() as $key => $result){
		$repDate[$key] 			= $result->getDate();
		$repPageview_TS[$key] 		= $result->getPageviews();	
		$repVisits_TS[$key]			= $result->getVisits();
		$repVisitors_TS[$key] 		= $result->getVisitors();
		$repMedium[$key]			= $result->getMedium();
		$numResults 				= $key;
	}

	// Starting to assemble final structures for returning
	$i=0;
	$j=-1;
	$date = "2013-01-01";

	while ($i<=$numResults){ 
		if ($date != $repDate[$i]) { 
			// New Date 
				if ($j>0){
					// Visitors
					if (empty($visitors[$j-1]['Not Set'])) $visitors[$j-1]['Not Set'] = 0;
					if (empty($visitors[$j-1]['Direct'])) 	$visitors[$j-1]['Direct'] = 0;
					if (empty($visitors[$j-1]['Organic'])) $visitors[$j-1]['Organic'] = 0;
					if (empty($visitors[$j-1]['Referral'])) $visitors[$j-1]['Referral'] = 0;
					$visitors[$j-1]['Total'] = 
								$visitors[$j-1]['Not Set'] + $visitors[$j-1]['Direct'] + 
								$visitors[$j-1]['Organic'] + $visitors[$j-1]['Referral'];

					// Visits
					if (empty($visits[$j-1]['Not Set'])) 	$visits[$j-1]['Not Set'] = 0;
					if (empty($visits[$j-1]['Direct'])) 	$visits[$j-1]['Direct'] = 0;
					if (empty($visits[$j-1]['Organic'])) 	$visits[$j-1]['Organic'] = 0;
					if (empty($visits[$j-1]['Referral'])) 	$visits[$j-1]['Referral'] = 0;
					$visits[$j-1]['Total'] = 
								$visits[$j-1]['Not Set'] + $visits[$j-1]['Direct'] + 
								$visits[$j-1]['Organic'] + $visits[$j-1]['Referral'];

					// Pageviews
					if (empty($pageviews[$j-1]['Not Set'])) 	$pageviews[$j-1]['Not Set'] = 0;
					if (empty($pageviews[$j-1]['Direct'])) 		$pageviews[$j-1]['Direct'] = 0;
					if (empty($pageviews[$j-1]['Organic'])) 	$pageviews[$j-1]['Organic'] = 0;
					if (empty($pageviews[$j-1]['Referral'])) 	$pageviews[$j-1]['Referral'] = 0;
					$pageviews[$j-1]['Total'] = 
								$pageviews[$j-1]['Not Set'] + $pageviews[$j-1]['Direct'] + 
								$pageviews[$j-1]['Organic'] + $pageviews[$j-1]['Referral'];

				}
			$date = $repDate[$i];
			$j++;	
			$visitors[] = array(
					"Date" 		=> "",
					"Direct"	=> "",
					"Referral"	=> "",
					"Organic"	=> "",
					"Not Set"	=> "",
					"Total"		=> ""
								);
			$visits[] = array(
					"Date" 		=> "",
					"Direct"	=> "",
					"Referral"	=> "",
					"Organic"	=> "",
					"Not Set"	=> "",
					"Total"		=> ""
								);
			$pageviews[] = array(
					"Date" 		=> "",
					"Direct"	=> "",
					"Referral"	=> "",
					"Organic"	=> "",
					"Not Set"	=> "",
					"Total"		=> ""
								);

			$visitors[$j]['Date'] 	= $repDate[$i]; 
			$visits[$j]['Date'] 	= $repDate[$i]; 
			$pageviews[$j]['Date'] 	= $repDate[$i]; 
		}
		switch ($repMedium[$i]) {
			case '(not set)':
				$visitors[$j]['Not Set'] 	= $repVisitors_TS[$i];
				$visits[$j]['Not Set'] 		= $repVisits_TS[$i];
				$pageviews[$j]['Not Set'] 	= $repPageview_TS[$i];
				break;		
			case '(none)':
				$visitors[$j]['Direct'] 	= $repVisitors_TS[$i];
				$visits[$j]['Direct'] 		= $repVisits_TS[$i];
				$pageviews[$j]['Direct'] 	= $repPageview_TS[$i];
				break;
			case 'referral':
				$visitors[$j]['Referral'] 	= $repVisitors_TS[$i]; 
				$visits[$j]['Referral']   	= $repVisits_TS[$i]; 
				$pageviews[$j]['Referral'] 	= $repPageview_TS[$i];
				break;				
			case 'organic':
				$visitors[$j]['Organic'] 	= $repVisitors_TS[$i]; 
				$visits[$j]['Organic'] 		= $repVisits_TS[$i]; 
				$pageviews[$j]['Organic'] 	= $repPageview_TS[$i];
				break;
		}
		$i++;
	}

	// Totaling the last item
	$visitors[$j]['Total'] = 
			$visitors[$j]['Not Set'] + $visitors[$j]['Direct'] + 
			$visitors[$j]['Organic'] + $visitors[$j]['Referral'];

	$visits[$j]['Total'] = 
			$visits[$j]['Not Set'] + $visits[$j]['Direct'] + 
			$visits[$j]['Organic'] + $visits[$j]['Referral'];

	$pageviews[$j]['Total'] = 
			$pageviews[$j]['Not Set'] + $pageviews[$j]['Direct'] + 
			$pageviews[$j]['Organic'] + $pageviews[$j]['Referral'];


	//$ResultingArray = array(
	//			"Visitors" 	=> $visitors,
	//			"Visits"	=> $visits,
	//			"Pageviews"	=> $pageviews);


	// Preparing SQL Statements for Metrics per Traffic Source
	foreach ($visitors as $key => $value) {
		$insert_visitors_per_traffic_source[] = 
			"insert into 
				CONS_METRICS_TRAFFIC_ORIGIN_VISITORS(DATE, TOTAL_TRAFFIC_DIRECT, TOTAL_TRAFFIC_ORGANIC, TOTAL_TRAFFIC_REFERAL, TOTAL_TRAFFIC_NOTSET) 
				values ('" . date("Y-m-d", strtotime($value['Date'])) . "', " . $value['Direct'] . "," . $value['Organic'] . ", " . $value['Referral'] . ", " . $value['Not Set'] .")  
			ON DUPLICATE KEY UPDATE 
				TOTAL_TRAFFIC_DIRECT = "  . $value['Direct'] 	. ", 
				TOTAL_TRAFFIC_ORGANIC = " . $value['Organic'] 	. ",
				TOTAL_TRAFFIC_REFERAL = " . $value['Referral']	. ",
				TOTAL_TRAFFIC_NOTSET = " . $value['Not Set']	." ;";
		echo "[SQL] - SQL statement for insertion is " . $insert_visitors_per_traffic_source[$key] . "\n";
	}

	foreach ($pageviews as $key => $value) {
		$insert_pageviews_per_traffic_source[] = 
			"insert into 
				CONS_METRICS_TRAFFIC_ORIGIN_PAGEVIEWS(DATE, TOTAL_TRAFFIC_DIRECT, TOTAL_TRAFFIC_ORGANIC, TOTAL_TRAFFIC_REFERAL, TOTAL_TRAFFIC_NOTSET) 
				values ('" . date("Y-m-d", strtotime($value['Date'])) . "', " . $value['Direct'] . "," . $value['Organic'] . ", " . $value['Referral'] . ", " . $value['Not Set'] .")  
			ON DUPLICATE KEY UPDATE 
				TOTAL_TRAFFIC_DIRECT = "  . $value['Direct'] 	. ", 
				TOTAL_TRAFFIC_ORGANIC = " . $value['Organic'] 	. ",
				TOTAL_TRAFFIC_REFERAL = " . $value['Referral']	. ",
				TOTAL_TRAFFIC_NOTSET = " . $value['Not Set']	." ;";
		echo "[SQL] - SQL statement for insertion is " . $insert_pageviews_per_traffic_source[$key] . "\n";
	}

/* 
echo "<pre>";
echo "[DEBUG] -- Insert Statement is: \n";
print_r($insert_sql_statement);
print "\n";
echo "[DEBUG] -- Update Statement is: \n";
print_r($update_sql_statement);
echo "</pre>";
*/

// ///////////////////////////////////////////////////////////////////////
//
// Saving Consolidated Metrics into INTRANET DB
//
// //////////////////////////////////////////////////////////////////////

// Connecting to DB Server
require "conn.php";

// Preparing for Insertion
echo "[Debug] - Selecting DB Intranet for Insertion ...\n";
mysql_select_db(W_DB, $conn_w) or die('[Error] - Could not select database; ' . mysql_error());

/* 
// Consolidated Metrics - DELTA GOOGLE ANALYTICS METRICS DAY
	// For development velocity reasons, I'll insert one row at a time, not performing Array Insert.
	// Inserting the last day (key 0)
	echo "[Debug] - Preparing SQL statement for insertion of last day metrics...\n";	
	echo "[SQL] - Insert Statement is: " . $insert_sql_statement . "\n";

	$insertion_success = mysql_query($insert_sql_statement, $conn_w);
	if ($insertion_success) {
		echo "[Success] - Delta Metrics for Google Analytics - Insertion Succeed!\n";;
	} else {
		echo "[Failed] - Delta Metrics for Google Analytics - Insertion Failed!\n";
		die("[Error] - Failure on inserting data. Please contact your Administrator!\n");
	}

	// Updating the remaining days
	foreach ($update_sql_statement as $key => $value) {
		echo "[Debug] - Preparing SQL statement for updating of consolidated metrics...\n";	
		echo "[SQL] - The Update Statement is: " . $value . "\n";
		
		$insertion_success = mysql_query($value, $conn_w);
		if ($insertion_success) {
			echo "[Success] - Delta Metrics for Google Analytics - Updating Succeed!\n";;
		} else {
			echo "[Failed] - Delta Metrics for Google Analytics - Updating Failed!\n";
			die("[Error] - Failure on updating data. Please contact your Administrator!\n");
		}
	}

// Consolidated Metric - Frequency - Number of Visitors per Visit Count
	echo "[Debug] - Preparing SQL statement for insertion day metrics...\n";	
	$max_limit = count($insert_sql_statement_frequency);
	for ($i = 0; $i < $max_limit ; $i++){
		echo "[Debug] - Preparing SQL statement for updating of consolidated metrics...\n";	
		echo "[SQL] - The Insert Statement is: " . $insert_sql_statement_frequency[$i] . "\n";
		$insertion_success = mysql_query($insert_sql_statement_frequency[$i], $conn_w);
		if ($insertion_success) {
			echo "[Success] - Delta Metrics for GA Frequency - Updating Succeed!\n";;
		} else {
			echo "[Failed] - Delta Metrics for GA Frequency - Updating Failed!\n";
		}
	}

// Consolidated Metric - Recency - Number of Days Since Last Visit
	echo "[Debug] - Preparing SQL statement for insertion day metrics...\n";	
	$max_limit = count($insert_sql_statement_recency);
	for ($i = 0; $i < $max_limit ; $i++){
		echo "[Debug] - Preparing SQL statement for updating of consolidated metrics...\n";	
		echo "[SQL] - The Insert Statement is: " . $insert_sql_statement_recency[$i] . "\n";
		$insertion_success = mysql_query($insert_sql_statement_recency[$i], $conn_w);
		if ($insertion_success) {
			echo "[Success] - Delta Metrics for GA Recency - Updating Succeed!\n";;
		} else {
			echo "[Failed] - Delta Metrics for GA Recency - Updating Failed!\n";
		}
	}

// Consolidated Metrics - DELTA GOOGLE ANALYTICS METRICS FOR NEW USER PAGE
	// For development velocity reasons, I'll insert one row at a time, not performing Array Insert.
	// Inserting the last day (key 0)
	echo "[Debug] - Preparing SQL statement for insertion of last day metrics...\n";	
	echo "[SQL] - Insert Statement is: " . $insert_sql_statement_newuser . "\n";

	$insertion_success = mysql_query($insert_sql_statement_newuser, $conn_w);
	if ($insertion_success) {
		echo "[Success] - Delta Metrics for Google Analytics for New User Page - Insertion Succeed!\n";;
	} else {
		echo "[Failed] - Delta Metrics for Google Analytics for New User Page - Insertion Failed!\n";
		die("[Error] - Failure on inserting data. Please contact your Administrator!\n");
	}

	// Updating the remaining days
	foreach ($update_sql_statement_newuser as $key => $value) {
		echo "[Debug] - Preparing SQL statement for updating of consolidated metrics...\n";	
		echo "[SQL] - The Update Statement is: " . $value . "\n";
		
		$insertion_success = mysql_query($value, $conn_w);
		if ($insertion_success) {
			echo "[Success] - Delta Metrics for Google Analytics for New User Page - Updating Succeed!\n";;
		} else {
			echo "[Failed] - Delta Metrics for Google Analytics for New User Page - Updating Failed!\n";
			die("[Error] - Failure on updating data. Please contact your Administrator!\n");
		}
	}

*/
	
// Consolidated Metric - Traffic Origin - Pageviews and Visitors
	echo "[Debug] - Preparing SQL statement for insertion day metrics...\n";	
	$max_limit = count($insert_visitors_per_traffic_source);
	for ($i = 0; $i < $max_limit ; $i++){
		echo "[Debug] - Preparing SQL statement for updating of consolidated metrics...\n";	
		echo "[SQL] - The Insert Statement is: " . $insert_visitors_per_traffic_source[$i] . "\n";
		$insertion_success = mysql_query($insert_visitors_per_traffic_source[$i], $conn_w);
		if ($insertion_success) {
			echo "[Success] - Consolidated Metrics for GA Traffic Origin - Visitors - Updating Succeed!\n";;
		} else {
			echo "[Failed] - Consolidated Metrics for GA Traffic Origin - Visitors - Updating Failed!\n";
		}
	}

	echo "[Debug] - Preparing SQL statement for insertion day metrics...\n";	
	$max_limit = count($insert_pageviews_per_traffic_source);
	for ($i = 0; $i < $max_limit ; $i++){
		echo "[Debug] - Preparing SQL statement for updating of consolidated metrics...\n";	
		echo "[SQL] - The Insert Statement is: " . $insert_pageviews_per_traffic_source[$i] . "\n";
		$insertion_success = mysql_query($insert_pageviews_per_traffic_source[$i], $conn_w);
		if ($insertion_success) {
			echo "[Success] - Consolidated Metrics for GA Traffic Origin - Pageviews - Updating Succeed!\n";;
		} else {
			echo "[Failed] - Consolidated Metrics for GA Traffic Origin - Pageviews - Updating Failed!\n";
		}
	}



?>

