<?php
include "../includes/header.php";
?>


<div id="wrap">
    
    
    	<!--BEGIN SIDEBAR-->
        <div id="menu" role="navigation">
          	<?php include "../includes/main_menu.php"; ?>
          	<?php include "../includes/submenu_charts.php"; ?> 
          	<div class="clearfix"></div> 
        </div>
        <!--SIDEBAR END-->
        <div id="main" role="main">
          	<div class="block">
   		  		<div class="clearfix"></div>
            	<!--page title-->
             	<div class="pagetitle">
                	<h1>Teckler Basic Google Analytics Comparion Reporting</h1> 
                	<div class="clearfix"></div>
             	</div>
             	<!--page title end-->
             	<P>&nbsp;</P>




             	<div class="container_config_param radius">
	            <!-- Configuration Parameters -->
					<table class="select_period">
						<form action="detailed_ga_comparison_report.php" method="post">
						<tr>
							<td>
								<div class="select_box_space">
									<p>Metric</p>
									<select  class="chzn-select" id="selected_metric" name="selected_metric">
										<option value="pageviews" selected> Pageviews In Tecks (Overall vs English vs Portuguese)	</option>
										<option value="visitors"> Visitors in Tecks (Overall vs English vs Portuguese)				</option>
										<option value="visits"> Visits in Tecks (Overall vs English vs Portuguese)					</option>
										<option value="newVisits"> New Visits in Tecks (Overall vs English vs Portuguese)		</option>
										<option value="retVisits"> Returning Visits in Tecks (Overall vs English vs Portuguese)	</option>
										<option value="bounces"> Bounce Rate in Tecks (Overall vs English vs Portuguese)				</option>
										<option value="avgTimeOnPage"> Average Time On Page in Tecks (Overall vs English vs Portuguese)			</option>
										<option value="avgTimeOnSite"> Average Time On Site in Tecks (Overall vs English vs Portuguese)			</option>
										<option value="pageviewssPerVisit"> Pageviews Per Visit in Tecks (Overall vs English vs Portuguese)	</option>
									</select>
								</div>
							</td>
							<td>
								<div class="select_box_space">
									<p>Period</p>
									<select id="selected_period"  class="chzn-select" name="selected_period">
										<option value="last07Days" selected >The last 7 Days 	</option>
										<option value="last15Days"			>The last 15 Days 	</option>
										<option value="last30Days"			>The last 30 Days 	</option>
										<option value="last60Days"			>The last 60 Days 	</option>
										<option value="last90Days"			>The last 90 Days	</option>
										<option value="last120Days"			>The last 120 Days 	</option>
										<option value="thisWeek"			>This Week 			</option>
										<option value="thisMonth"			>This Month			</option>
										<option value="thisQuarter"			>This Quarter 		</option>
										<option value="thisSemester"		>This Semester		</option>
									</select>
								</div>
							</td>
							<td valign="bottom">
								<div class="buttons-box-table">
									<input type="submit" class="button_submit_form green" name="cancel" value="Cancel">
									<input type="submit" class="button_submit_form blue" name="compare" value="Compare">
								</div>
							</td>
						</tr>
						</form>
					</table>	


				</div>




             	
             	
					
				


				<?php
					// Setting up basic page environment
					date_default_timezone_set('America/Sao_Paulo');
					require ("gapi-1.3/gapi.class.php");


					// Setting up $_POST environment
					$whatMetricToCompare = (isset($_POST['selected_metric']) ? $_POST['selected_metric'] : "");
					$whatPeriodToCompare = (isset($_POST['selected_period']) ? $_POST['selected_period'] : "");

					// Setting up basic parameters from Google Analytics API
					$gaUsername = 'team@teckler.com';
					$gaPassword = 'T3ckl347';
					$profileId = '71713009';
					$dimensions = array('date');
					$sort = 'date';
					$metrics = array(
							'pageviews',
							'uniquePageviews',
							'bounces', 
							'visits', 
							'visitors', 
							'newVisits', 
							'percentNewVisits',
							'avgTimeOnSite', 
							'avgTimeOnPage', 
							'pageviewsPerVisit');
					$timeInterval = '90';
					$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
					$toDate = date('Y-m-d');

					// Configure GAPI from User Input -- Metric
					if ($whatMetricToCompare != "" && $whatPeriodToCompare!= ""){
						switch ($whatMetricToCompare) {
							case 'pageviews':
							case 'pageviewsPerVisit':
								$metrics = array('pageviews','uniquePageviews', 'pageviewsPerVisit'); 
								break;
							case 'visits':
							case 'newVisitors':
							case 'retVisitors':
							case 'bounces':
							case 'visitors':
								$metrics = array('visits', 'newVisits', 'percentNewVisits', 'bounces', 'visitors'); 
								break;
							case 'avgTimeOnSite':
								$metrics = array('avgTimeOnSite'); 
								break;
							case 'avgTimeOnPage':
								$metrics = array('avgTimeOnPage'); 
								break;					
							default:
								$metrics = array('pageviews','uniquePageviews','bounces', 'visits', 'visitors', 'newVisits', 'percentNewVisits','avgTimeOnSite', 'avgTimeOnPage', 'pageviewsPerVisit');
								break;
						}
						// Configure GAPI from User Input -- Metric
						switch ($whatPeriodToCompare) {
							// LAST DAYS
							case 'last07Days':
								$timeInterval = '7';
								$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
								$toDate = date('Y-m-d');
								break;
							case 'last15Days':
								$timeInterval = '15';
								$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
								$toDate = date('Y-m-d');
								break;
							case 'last30Days':
								$timeInterval = '30';
								$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
								$toDate = date('Y-m-d');
								break;
							case 'last60Days':
								$timeInterval = '60';
								$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
								$toDate = date('Y-m-d');
								break;
							case 'last90Days':
								$timeInterval = '60';
								$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
								$toDate = date('Y-m-d');
								break;
							case 'last120Days':
								$timeInterval = '120';
								$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
								$toDate = date('Y-m-d');
								break;
							// THIS PERIOD
							case 'thisWeek':
								$timeInterval = date('N');
								$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
								$toDate = date('Y-m-d');
								break;
							case 'thisMonth':
								$timeInterval = date('d');
								$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
								$toDate = date('Y-m-d');
								break;
							case 'thisQuarter':
								$thisMonth = date('n');
								if ($thisMonth==1 || $thisMonth==4 || $thisMonth == 7 || $thisMonth==10 ) {
									// Every Start of the Quarter
									$timeInterval = date('d');
									$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
									$toDate = date('Y-m-d');
								} elseif ($thisMonth==2 || $thisMonth==8 || $thisMonth==11) {
									// February and August
									$timeInterval = date('d') + 31;
									$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
									$toDate = date('Y-m-d');
								} elseif ($thisMonth==6 || $thisMonth==12){
									// June and December
									$timeInterval = date('d') + 31 + 30;
									$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
									$toDate = date('Y-m-d');
								} elseif ($thisMonth==3){
									// March
									$timeInterval = date('d') + ( date('L') ? 29 : 28) + 31;
									$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
									$toDate = date('Y-m-d');
								} elseif ($thisMonth==5){
									// May
									$timeInterval = date('d') + 30;
									$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
									$toDate = date('Y-m-d');
								} elseif($thisMonth==9){
									$timeInterval = date('d') + 31 + 31;
									$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
									$toDate = date('Y-m-d');
								} 
								break;
							case 'ThisSemester':
								$dayOfTheYear = date('z');
								$midYear = ( date('L') ? (31+29+31+30+31+31) : (31+28+31+30+31+31) );
								if ($dayOfTheYear <= $midYear ) {
									$timeInterval = $dayOfTheYear;	
								} else {
									$timeInterval = $dayOfTheYear - $midYear;
								}
								$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
								$toDate = date('Y-m-d');
								break;
							// Default	
							default:
								$timeInterval = '90';
								$fromDate = date('Y-m-d', strtotime('-'. $timeInterval .' days'));
								$toDate = date('Y-m-d');
								break;
						} // End Switch
					}  // end If 

					// Setting Up Filters
					$filterGl	 	= "PagePath =~ ^(\/[^\/]+){3,} && PagePath =~ (-)[0-9]\d{1,}$ || PagePath =~ (=)$";
					$filterUS 		= "PagePath =~ ^\/en(\/[^\/]+){2,}$ && PagePath =~ (-)[0-9]\d{1,}$ || PagePath =~ (=)$";
					$filterBR		= "PagePath =~ ^\/pt(\/[^\/]+){2,}$ && PagePath =~ (-)[0-9]\d{1,}$ || PagePath =~ (=)$";

					// Call Google API to Request Data

					$gaGL = new gapi($gaUsername, $gaPassword);
					$gaGL->requestReportData($profileId, $dimensions, $metrics, $sort, $filterGl, $fromDate, $toDate, 1, $timeInterval);

					$gaUS = new gapi($gaUsername, $gaPassword);
					$gaUS->requestReportData($profileId, $dimensions, $metrics, $sort, $filterUS, $fromDate, $toDate, 1, $timeInterval);

					$gaBR = new gapi($gaUsername, $gaPassword);
					$gaBR->requestReportData($profileId, $dimensions, $metrics, $sort, $filterBR, $fromDate, $toDate, 1, $timeInterval);

					// Setting Up HTML Heading Titles
					$titleH2 = "Global Tracking Info";
					$titleH3 = "Info....";
					$titleH4GL = "Global";
					$titleH4US = "United States";
					$titleH4BR = "Brazil";

					//Getting Metric Info Back
					switch ($whatMetricToCompare) {
						// Page Tracking
						case 'pageviews':
							$titleH2 = "Page Tracking Info";
							$titleH3 = "Pageviews";
							foreach ($gaGL->getResults() as $keyGL => $resultGL) {
								$repDateGL[$keyGL] 				= $resultGL->getDate();
								$repPageviewGL[$keyGL] 			= $resultGL->getPageviews();	
								$repUniquePageviewGL[$keyGL]	= $resultGL->getUniquePageviews();
							}
							$metricArrayGL = array(	'Dates' 			=> $repDateGL,
													'Pageviews' 		=> $repPageviewGL,
													'UniquePageviews' 	=> $repUniquePageviewGL);
							foreach ($gaUS->getResults() as $keyUS => $resultUS) {
								$repDateUS[$keyUS] 				= $resultUS->getDate();
								$repPageviewUS[$keyUS] 			= $resultUS->getPageviews();	
								$repUniquePageviewUS[$keyUS]	= $resultUS->getUniquePageviews();
							}
							$metricArrayUS = array(	'Dates' 			=> $repDateUS,
													'Pageviews' 		=> $repPageviewUS,
													'UniquePageviews' 	=> $repUniquePageviewUS);
							foreach ($gaBR->getResults() as $keyBR => $resultBR) {
								$repDateBR[$keyBR] 				= $resultBR->getDate();
								$repPageviewBR[$keyBR] 			= $resultBR->getPageviews();	
								$repUniquePageviewBR[$keyBR]	= $resultBR->getUniquePageviews();
							}
							$metricArrayBR = array(	'Dates' 			=> $repDateBR,
													'Pageviews' 		=> $repPageviewBR,
													'UniquePageviews' 	=> $repUniquePageviewBR);
							$metricCount = 3;
							break;
						case 'pageviewsPerVisit':
							$titleH2 = "Page Tracking Info";
							$titleH3 = "Pageviews Per Visit";
							foreach ($gaGL->getResults() as $keyGL => $resultGL) {
								$repDateGL[$keyGL] 				= $resultGL->getDate();
								$repPageviewsPerVisitGL[$keyGL] = $resultGL->getPageviewsPerVisit() ;
							}
							$metricArrayGL = array(	'Dates'				=> $repDateGL,
													'PageviewsPerVisit' => $repPageviewsPerVisitGL);
							foreach ($gaUS->getResults() as $keyUS => $resultUS) {
								$repDateUS[$keyUS] 				= $resultUS->getDate();
								$repPageviewsPerVisitUS[$keyUS] = $resultUS->getPageviewsPerVisit() ;
							}
							$metricArrayUS = array(	'Dates'				=> $repDateUS,
													'PageviewsPerVisit' => $repPageviewsPerVisitUS);
							foreach ($gaBR->getResults() as $keyBR => $resultBR) {
								$repDateBR[$keyBR] 				= $resultBR->getDate();
								$repPageviewsPerVisitBR[$keyBR] = $resultBR->getPageviewsPerVisit() ;
							}
							$metricArrayBR = array(	'Dates'				=> $repDateBR,
													'PageviewsPerVisit' => $repPageviewsPerVisitBR);
							$metricCount = 2;
							break;
						case 'avgTimeOnPage':
							$titleH2 = "Page Tracking Info";
							$titleH3 = "Average Time On Page";
							foreach ($gaGL->getResults() as $keyGL => $resultGL) {
								$repDateGL[$keyGL] 				= $resultGL->getDate();
								$repAvgTimeOnPageGL[$keyGL] 	= $resultGL->getAvgTimeOnPage() ;
							}
							$metricArrayGL = array(	'Dates' 			=> $repDateGL,
													'AverageTimeOnPage' => $repAvgTimeOnPageGL);
							foreach ($gaUS->getResults() as $keyUS => $resultUS) {
								$repDateUS[$keyUS] 				= $resultUS->getDate();
								$repAvgTimeOnPageUS[$keyUS] 	= $resultUS->getAvgTimeOnPage() ;
							}
							$metricArrayUS = array(	'Dates' 			=> $repDateUS,
													'AverageTimeOnPage' => $repAvgTimeOnPageUS);

							foreach ($gaGL->getResults() as $keyBR => $resultBR) {
								$repDateBR[$keyBR] 				= $resultBR->getDate();
								$repAvgTimeOnPageBR[$keyBR] 	= $resultBR->getAvgTimeOnPage() ;
							}
							$metricArrayBR = array(	'Dates' 			=> $repDateBR,
													'AverageTimeOnPage' => $repAvgTimeOnPageBR);

							$metricCount = 2;
							break;
						// Session Tracking	
						case 'visits':
						case 'bounces':
							$titleH2 = "Session Tracking Info";
							($whatMetricToCompare=='bounces' ? $titleH3 = "Bounces": $titleH3 = "Visits" );
							foreach ($gaGL->getResults() as $keyGL => $resultGL) {
								$repDateGL[$keyGL] 				= $resultGL->getDate();
								$repVisitsGL[$keyGL]			= $resultGL->getVisits();
								$repBounceGL[$keyGL] 			= $resultGL->getBounces();
								$repPercBounceGL[$keyGL] 		= ($repVisitsGL[$keyGL] != 0 ? ($repBounceGL[$keyGL] / $repVisitsGL[$keyGL]) : 0 );
							}
							$metricArrayGL = array( 'Dates' => $repDateGL,
													'Visits' => $repVisitsGL,
													'Bounces' => $repBounceGL,
													'PercBounces' => $repPercBounceGL);
							foreach ($gaUS->getResults() as $keyUS => $resultUS) {
								$repDateUS[$keyUS] 				= $resultUS->getDate();
								$repVisitsUS[$keyUS]			= $resultUS->getVisits();
								$repBounceUS[$keyUS] 			= $resultUS->getBounces();
								$repPercBounceUS[$keyUS] 		= ($repVisitsUS[$keyUS] != 0 ? ($repBounceUS[$keyUS] / $repVisitsUS[$keyUS]) : 0 );
							}
							$metricArrayUS = array( 'Dates' => $repDateUS,
													'Visits' => $repVisitsUS,
													'Bounces' => $repBounceUS,
													'PercBounces' => $repPercBounceUS);
							foreach ($gaBR->getResults() as $keyBR => $resultBR) {
								$repDateBR[$keyBR] 				= $resultBR->getDate();
								$repVisitsBR[$keyBR]			= $resultBR->getVisits();
								$repBounceBR[$keyBR] 			= $resultBR->getBounces();
								$repPercBounceBR[$keyBR] 		= ($repVisitsBR[$keyBR] != 0 ? ($repBounceBR[$keyBR] / $repVisitsBR[$keyBR]) : 0 );
							}
							$metricArrayBR = array( 'Dates' => $repDateBR,
													'Visits' => $repVisitsBR,
													'Bounces' => $repBounceBR,
													'PercBounces' => $repPercBounceBR);
							$metricCount = 4;
							break;
						case 'avgTimeOnSite':
							$titleH2 = "Session Tracking Info";
							$titleH3 = "Average Time On Site";
							foreach ($gaGL->getResults() as $keyGL => $resultGL) {
								$repDateGL[$keyGL] 				= $resultGL->getDate();
								$repAvgTimeOnSiteGL[$keyGL]		= $resultGL->getAvgTimeOnSite();
							}
							$metricArrayGL = array(	'Dates' 			=> $repDateGL, 
													'AverageTimeOnSite' => $repAvgTimeOnSiteGL);
							foreach ($gaUS->getResults() as $keyUS => $resultUS) {
								$repDateUS[$keyUS] 				= $resultUS->getDate();
								$repAvgTimeOnSiteUS[$keyUS] 	= $resultUS->getAvgTimeOnSite();
							}
							$metricArrayUS = array(	'Dates' 			=> $repDateUS, 
													'AverageTimeOnSite' => $repAvgTimeOnSiteUS);
							foreach ($gaBR->getResults() as $keyBR => $resultBR) {
								$repDateBR[$keyBR] 				= $resultBR->getDate();
								$repAvgTimeOnSiteBR[$keyBR] 	= $resultBR->getAvgTimeOnSite();
							}
							$metricArrayBR = array(	'Dates' 			=> $repDateBR, 
													'AverageTimeOnSite' => $repAvgTimeOnSiteBR);
							$metricCount = 2;
							break;

						// Visitor Tracking	
						case 'visitors':
						case 'newVisits':
						case 'retVisits':
							$titleH2 = "Session Tracking Info";
							($whatMetricToCompare=='visitors' ? $titleH3 = "Visitors" : ($whatMetricToCompare == "newVisits" ? $titleH3 = "New Visitors" : $titleH3 = "Returning Visitors"));
							foreach ($gaGL->getResults() as $keyGL => $resultGL) {
								$repDateGL[$keyGL] 					= $resultGL->getDate();
								$repVisitsGL[$keyGL]				= $resultGL->getVisits();
								$repVisitorsGL[$keyGL] 				= $resultGL->getVisitors();
								$repNewVisitsGL[$keyGL] 			= $resultGL->getNewVisits();
								$repRetVisitsGL[$keyGL]				= $repVisitsGL[$keyGL] - $repNewVisitsGL[$keyGL];
								$repPercentNewVisitsGL[$keyGL] 		= $resultGL->getPercentNewVisits();
								$repPercentReturningVisitsGL[$keyGL] = (100 - $repPercentNewVisitsGL[$keyGL]);
							}
							$metricArrayGL = array(	'Dates' 		=> $repDateGL, 
													'Visits'		=> $repVisitsGL,
													'Visitors'		=> $repVisitorsGL, 
													'NewVisits'		=> $repNewVisitsGL, 
													'RetVisits'		=> $repRetVisitsGL, 
													'PercNewVisits'	=> $repPercentNewVisitsGL, 
													'PercRetVisits'	=> $repPercentReturningVisitsGL);		
							foreach ($gaUS->getResults() as $keyUS => $resultUS) {
								$repDateUS[$keyUS] 					= $resultUS->getDate();
								$repVisitsUS[$keyUS]				= $resultUS->getVisits();
								$repVisitorsUS[$keyUS] 				= $resultUS->getVisitors();
								$repNewVisitsUS[$keyUS] 			= $resultUS->getNewVisits();
								$repRetVisitsUS[$keyUS]				= $repVisitsUS[$keyUS] - $repNewVisitsUS[$keyUS];
								$repPercentNewVisitsUS[$keyUS] 		= $resultUS->getPercentNewVisits();
								$repPercentReturningVisitsUS[$keyUS] = (100 - $repPercentNewVisitsUS[$keyUS]);
							}
							$metricArrayUS = array(	'Dates' 		=> $repDateUS, 
													'Visits'		=> $repVisitsUS,
													'Visitors'		=> $repVisitorsUS, 
													'NewVisits'		=> $repNewVisitsUS, 
													'RetVisits'		=> $repRetVisitsUS, 
													'PercNewVisits'	=> $repPercentNewVisitsUS, 
													'PercRetVisits'	=> $repPercentReturningVisitsUS);		
							foreach ($gaBR->getResults() as $keyBR => $resultBR) {
								$repDateBR[$keyBR] 					= $resultBR->getDate();
								$repVisitsBR[$keyBR]				= $resultBR->getVisits();
								$repVisitorsBR[$keyBR] 				= $resultBR->getVisitors();
								$repNewVisitsBR[$keyBR] 			= $resultBR->getNewVisits();
								$repRetVisitsBR[$keyBR]				= $repVisitsBR[$keyBR] - $repNewVisitsBR[$keyBR];
								$repPercentNewVisitsBR[$keyBR] 		= $resultBR->getPercentNewVisits();
								$repPercentReturningVisitsBR[$keyBR] = (100 - $repPercentNewVisitsBR[$keyBR]);
							}
							$metricArrayBR = array(	'Dates' 		=> $repDateBR, 
													'Visits'		=> $repVisitsBR,			
													'Visits'		=> $repVisitorsBR, 
													'NewVisits'		=> $repNewVisitsBR, 
													'RetVisits'		=> $repRetVisitsBR, 
													'PercNewVisits'	=> $repPercentNewVisitsBR, 
													'PercRetVisits'	=> $repPercentReturningVisitsBR);		
							$metricCount = 6;
							break;
						default:
							foreach ($gaGL->getResults() as $keyGL => $resultGL) {
								// Date
								$repDateGL[$keyGL] 						= $resultGL->getDate();
								// Page Tracking
								$repPageviewGL[$keyGL] 					= $resultGL->getPageviews();	
								$repUniquePageviewGL[$keyGL]			= $resultGL->getUniquePageviews();
								$repPageviewsPerVisitGL[$keyGL] 		= $resultGL->getPageviewsPerVisit();
								$repAvgTimeOnPageGL[$keyGL] 			= $resultGL->getAvgTimeOnPage();
								// Session Tracking
								$repVisitsGL[$keyGL]					= $resultGL->getVisits();
								$repBounceGL[$keyGL] 					= $resultGL->getBounces();
								$repPercBounceGL[$keyGL] 				= ($repVisitsGL[$keyGL] != 0 ? ($repBounceGL[$keyGL] / $repVisitsGL[$keyGL]) : 0 );
								$repAvgTimeOnSiteGL[$keyGL] 			= $resultGL->getAvgTimeOnSite();
								// Visitor Tracking
								$repVisitorsGL[$keyGL] 					= $resultGL->getVisitors();
								$repNewVisitsGL[$keyGL] 				= $resultGL->getNewVisits();
								$repRetVisitsGL[$keyGL]					= $repVisitsGL[$keyGL] - $repNewVisitsGL[$keyGL];
								$repPercentNewVisitsGL[$keyGL] 			= $resultGL->getPercentNewVisits();
								$repPercentReturningVisitsGL[$keyGL] 	= (100 - $repPercentNewVisitsGL[$keyGL]);
							}
							$metricArrayGL = array(	'Dates' 			=> $repDateGL, 
													'PageViews' 		=> $repPageviewGL, 
													'UniquePageviews' 	=> $repUniquePageviewGL, 
													'PageviewsPerVisit'	=> $repPageviewsPerVisitGL, 
													'AverageTimeOnPage'	=> $repAvgTimeOnPageGL,
													'Visits'			=> $repVisitsGL, 
													'Bounces'			=> $repBounceGL, 
													'PercBounces'		=> $repPercBounceGL, 
													'AverageTimeOnSite'	=> $repAvgTimeOnSiteGL,
													'Visitors'			=> $repVisitorsGL, 
													'NewVisits'			=> $repNewVisitsGL, 
													'RetVisits'			=> $repRetVisitsGL, 
													'PercNewVisits'		=> $repPercentNewVisitsGL, 
													'PercRetVisits'		=> $repPercentReturningVisitsGL);
							foreach ($gaUS->getResults() as $keyUS => $resultUS) {
								// Page Tracking
								$repDateUS[$keyUS] 						= $resultUS->getDate();
								$repPageviewUS[$keyUS] 					= $resultUS->getPageviews();	
								$repUniquePageviewUS[$keyUS]			= $resultUS->getUniquePageviews();
								$repPageviewsPerVisitUS[$keyUS] 		= $resultUS->getPageviewsPerVisit();
								$repAvgTimeOnPageUS[$keyUS] 			= $resultUS->getAvgTimeOnPage();
								// Session Tracking
								$repVisitsUS[$keyUS]					= $resultUS->getVisits();
								$repBounceUS[$keyUS] 					= $resultUS->getBounces();
								$repPercBounceUS[$keyUS] 				= ($repVisitsUS[$keyUS] != 0 ? ($repBounceUS[$keyUS] / $repVisitsUS[$keyUS]) : 0 );
								$repAvgTimeOnSiteUS[$keyUS] 			= $resultUS->getAvgTimeOnSite();
								// Visitor Tracking
								$repVisitorsUS[$keyUS] 					= $resultUS->getVisitors();
								$repNewVisitsUS[$keyUS] 				= $resultUS->getNewVisits();
								$repRetVisitsUS[$keyUS]					= $repVisitsUS[$keyUS] - $repNewVisitsUS[$keyUS];
								$repPercentNewVisitsUS[$keyUS] 			= $resultUS->getPercentNewVisits();
								$repPercentReturningVisitsUS[$keyUS] 	= (100 - $repPercentNewVisitsUS[$keyUS]);
							}
							$metricArrayUS = array(	'Dates' 			=> $repDateUS, 
													'PageViews' 		=> $repPageviewUS, 
													'UniquePageviews' 	=> $repUniquePageviewUS, 
													'PageviewsPerVisit'	=> $repPageviewsPerVisitUS, 
													'AverageTimeOnPage'	=> $repAvgTimeOnPageUS,
													'Visits'			=> $repVisitsUS, 
													'Bounces'			=> $repBounceUS, 
													'PercBounces'		=> $repPercBounceUS, 
													'AverageTimeOnSite'	=> $repAvgTimeOnSiteUS,
													'Visitors'			=> $repVisitorsUS, 
													'NewVisits'			=> $repNewVisitsUS, 
													'RetVisits'			=> $repRetVisitsUS, 
													'PercNewVisits'		=> $repPercentNewVisitsUS, 
													'PercRetVisits'		=> $repPercentReturningVisitsUS);
							foreach ($gaBR->getResults() as $keyBR => $resultBR) {
								// Page Tracking
								$repDateBR[$keyBR] 						= $resultBR->getDate();
								$repPageviewBR[$keyBR] 					= $resultBR->getPageviews();	
								$repUniquePageviewBR[$keyBR]			= $resultBR->getUniquePageviews();
								$repPageviewsPerVisitBR[$keyBR] 		= $resultBR->getPageviewsPerVisit();
								$repAvgTimeOnPageBR[$keyBR] 			= $resultBR->getAvgTimeOnPage();
								// Session Tracking
								$repVisitsBR[$keyBR]					= $resultBR->getVisits();
								$repBounceBR[$keyBR] 					= $resultBR->getBounces();
								$repPercBounceBR[$keyBR] 				= ($repVisitsBR[$keyBR] != 0 ? ($repBounceBR[$keyBR] / $repVisitsBR[$keyBR]) : 0 );
								$repAvgTimeOnSiteBR[$keyBR] 			= $resultBR->getAvgTimeOnSite();
								// Visitor Tracking
								$repVisitorsBR[$keyBR] 					= $resultBR->getVisitors();
								$repNewVisitsBR[$keyBR] 				= $resultBR->getNewVisits();
								$repRetVisitsBR[$keyBR]					= $repVisitsBR[$keyBR] - $repNewVisitsBR[$keyBR];
								$repPercentNewVisitsBR[$keyBR] 			= $resultBR->getPercentNewVisits();
								$repPercentReturningVisitsBR[$keyBR] 	= (100 - $repPercentNewVisitsBR[$keyBR]);
							}
							$metricArrayBR = array(	'Dates' 			=> $repDateBR, 
													'PageViews' 		=> $repPageviewBR, 
													'UniquePageviews' 	=> $repUniquePageviewBR, 
													'PageviewsPerVisit'	=> $repPageviewsPerVisitBR, 
													'AverageTimeOnPage'	=> $repAvgTimeOnPageBR,
													'Visits'			=> $repVisitsBR, 
													'Bounces'			=> $repBounceBR, 
													'PercBounces'		=> $repPercBounceBR, 
													'AverageTimeOnSite'	=> $repAvgTimeOnSiteBR,
													'Visitors'			=> $repVisitorsBR, 
													'NewVisits'			=> $repNewVisitsBR, 
													'RetVisits'			=> $repRetVisitsBR, 
													'PercNewVisits'		=> $repPercentNewVisitsBR, 
													'PercRetVisits'		=> $repPercentReturningVisitsBR);		
							$metricCount = 14;
							break;
					}

					$upatedAtTString = "Results updated at: " . date('Y-m-d H:i:s');
					?>	
						<div class="clearfix">
						<p><?php echo $upatedAtTString; ?></p>
						<H2><?php echo $titleH2;?></h2>
						<H3><?php echo $titleH3;?></H3>
						</div>

						<div class="row-fluid">
							
							<!--Striped table-->
			                  <div class="grid span4 grid_table">
			                  <div class="grid-title">
			                   <div class="pull-left">
			                      <div class="icon-title"><i class="icon-eye-open"></i></div>
			                      <span><?php echo $titleH4GL;?></span> 
			                      <div class="clearfix"></div>
			                   </div>
			                  <div class="clearfix"></div>   
			                  </div>
			                  <div class="grid-content tabela_scroll">
			                    <table class="table table-striped" id="exemplo">
				                    <TR>
										<?php 
											$keys = "";
											$keys = array_keys($metricArrayGL);
												foreach ($keys as $key => $value) {
													echo "<th align=center>\n";
													echo $value;
													echo "</th>\n";
												}
											echo "<TR>\n";
											for ($i=0 ; $i<$timeInterval; $i++){
											echo "<TR>\n";
												for ($j=0; $j<$metricCount; $j++){
													if ($j==0) {
														echo "<TD>" . date('Y-m-d', strtotime($metricArrayGL[$keys[$j]][$i])) . "</TD>\n";
													} else {
														echo "<TD>" . $metricArrayGL[$keys[$j]][$i] . "</TD>\n" ;
													}
												}
											echo "</TR>\n";
											}
										?>
									</TR>
			                  </table>			                    
			                  <div class="clearfix"></div>
			                  </div>
			                  </div>
			                  <!--Striped table END-->

						
						

			                  <!--Striped table-->
			                  <div class="grid span4 grid_table">
			                  <div class="grid-title">
			                   <div class="pull-left">
			                      <div class="icon-title"><i class="icon-eye-open"></i></div>
			                      <span><?php echo $titleH4US ;?></span> 
			                      <div class="clearfix"></div>
			                   </div>
			                  <div class="clearfix"></div>   
			                  </div>
			                  <div class="grid-content tabela_scroll">
			                    <table class="table table-striped" id="exemplo">
				                    <TR>
										<?php 
											$keys = "";
											$keys = array_keys($metricArrayUS);
												foreach ($keys as $key => $value) {
													echo "<th align=center>\n";
													echo $value;
													echo "</th>\n";
												}
											echo "<TR>\n";
											for ($i=0 ; $i<$timeInterval; $i++){
											echo "<TR>\n";
												for ($j=0; $j<$metricCount; $j++){
													if ($j==0) {
														echo "<TD>" . date('Y-m-d', strtotime($metricArrayUS[$keys[$j]][$i])) . "</TD>\n";
													} else {
														echo "<TD>" . $metricArrayUS[$keys[$j]][$i] . "</TD>\n" ;
													}
												}
											echo "</TR>\n";
											}
										?>
									</TR>
			                  </table>			                    
			                  <div class="clearfix"></div>
			                  </div>
			                  </div>
			                  <!--Striped table END-->




			                  <!--Striped table-->
			                  <div class="grid span4 grid_table">
			                  <div class="grid-title">
			                   <div class="pull-left">
			                      <div class="icon-title"><i class="icon-eye-open"></i></div>
			                      <span><?php echo $titleH4BR; ?></span> 
			                      <div class="clearfix"></div>
			                   </div>
			                  <div class="clearfix"></div>   
			                  </div>
			                  <div class="grid-content tabela_scroll">
			                    <table class="table table-striped" id="exemplo">
				                    <TR>
										<?php 
											$keys = "";
											$keys = array_keys($metricArrayBR);
												foreach ($keys as $key => $value) {
													echo "<th align=center>\n";
													echo $value;
													echo "</th>\n";
												}
											echo "<TR>\n";
											for ($i=0 ; $i<$timeInterval; $i++){
											echo "<TR>\n";
												for ($j=0; $j<$metricCount; $j++){
													if ($j==0) {
														echo "<TD>" . date('Y-m-d', strtotime($metricArrayBR[$keys[$j]][$i])) . "</TD>\n";
													} else {
														echo "<TD>" . $metricArrayBR[$keys[$j]][$i] . "</TD>\n" ;
													}
												}
											echo "</TR>\n";
											}
										?>
									</TR>
			                  </table>			                    
			                  <div class="clearfix"></div>
			                  </div>
			                  </div>
			                  <!--Striped table END-->
							
							


						</div>




         	</div>
         </div>


</div>
<?php

// GET Number of Users With Keep Me Logged On
//$result = mysql_query($sql_numusers_with_keepmelogged, $conn);
//while ($row = mysql_fetch_array($result)) {
//	$OnlineUsersToday     = $row['NumUsers'];/
//	
//}

?>