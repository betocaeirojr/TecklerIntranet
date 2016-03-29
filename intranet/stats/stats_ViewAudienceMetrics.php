<?php
	include "../includes/header.php";

	require_once "Connection.php";
	require_once "Audience.php";
	require_once "Users.php";
	require_once "Tecks.php";

	/* echo "<PRE>"; print_r($_POST); echo "</PRE>"; */

	// Initialize Key Variables, checking FORM POST
	if (isset($_POST['AggregationPeriod'])) {
		if ( 	($_POST['AggregationPeriod']== 'day') OR 
				($_POST['AggregationPeriod']== 'week' ) OR  
				($_POST['AggregationPeriod']== 'all')
			) {
			$GroupingPeriod = 'day';
		} else {
			$GroupingPeriod = 'month';
		} 	
		$AggregationPeriod = $_POST['AggregationPeriod'];
	} else {
		$AggregationPeriod = 'all'; 
		$GroupingPeriod = 'day';
	}

	if (isset($_POST['MetricType'])){ 
		$MetricType = $_POST['MetricType'];
	} else { 
		$MetricType = 'cons'; 
	}

	if (isset($_POST['MetricReferenceDate'])){
		$MetricReferenceDate = $_POST['MetricReferenceDate'];
		
		// Check to see if $MetricReferenceDate is in the Future
		$givenDate = strtotime($_POST['MetricReferenceDate']);
		$todayDate = strtotime(date('Y-m-d'));

		if ($todayDate <= ($givenDate - 86400)) {
			$yesterday = mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"));
			$MetricReferenceDate = date('Y-m-d',$yesterday);
		}
	} else {
		$yesterday = mktime(0, 0, 0, date("m")  , date("d")-2, date("Y"));
		$MetricReferenceDate = date('Y-m-d',$yesterday);
	}

	$ResultReturnType = 'array';
		

	$Connection = new Connection();

	// Fetch Metrics Data
	$AudienceMetric = new Audience($Connection);

	//Raw Information
	$PageviewsRawData 					= $AudienceMetric->getPageviewsRawData($ResultReturnType, $MetricReferenceDate, $GroupingPeriod, 'desc');
	$PageviewsPerTeckTypeRawData 		= $AudienceMetric->getPageviewsPerTeckTypeRawData($ResultReturnType, $MetricReferenceDate, $GroupingPeriod, 'desc');
	$AlexaGlobalRankingRawData 			= $AudienceMetric->getAlexaRawData($ResultReturnType, $MetricReferenceDate, $GroupingPeriod, 'desc');

	$PageviewsCount 					= $AudienceMetric->getPageviewsCount($ResultReturnType, $MetricReferenceDate);
	$AveragePageviewPerTeck 			= $AudienceMetric->getAveragePageviewsPerTeckCount($ResultReturnType, $MetricReferenceDate);
	$AveragePageviewPerProfile 			= $AudienceMetric->getAveragePageviewsPerProfileCount($ResultReturnType, $MetricReferenceDate);
	$WeightedAveragePageviewPerTeck 	= $AudienceMetric->getWeightedAveragePageviewsPerTeckCount($ResultReturnType, $MetricReferenceDate);
	$WeightedAveragePageviewPerProfile 	= $AudienceMetric->getWeightedAveragePageviewsPerProfileCount($ResultReturnType, $MetricReferenceDate);
	$TeckWithoutPageviewsCount 			= $AudienceMetric->getTecksWithoutPageviews($ResultReturnType, $MetricReferenceDate, 'abs');
	$ProfilesWithoutPageviewsCount 		= $AudienceMetric->getProfilesWithoutPageviews($ResultReturnType, $MetricReferenceDate, 'abs');

	$AveragePageviewsInTecks_PerDay_RawData 	= $AudienceMetric->getAveragePageviewsInTecks($ResultReturnType, 'day', '', 'desc');
	$AveragePageviewsInTecks_PerMonth_RawData 	= $AudienceMetric->getAveragePageviewsInTecks($ResultReturnType, 'month', '', 'desc');

	$AveragePageviewsInTecks_Accumuldated = $AudienceMetric->getAveragePageviewsInTecksAccumulated($ResultReturnType, '');
	//$AveragePageviewsInTecks_Accumuldated = "Not Relevant Anymore";

	// Info from Google Analytics
	$GARawDataDay 						= $AudienceMetric->getGoogleAnalyticsRawData($ResultReturnType, $MetricReferenceDate, 'desc');
	$GAPageviewsDayCount 				= $AudienceMetric->getUniquePageviewsGA($ResultReturnType, $MetricReferenceDate);
	$GAPageVisitsDayCount 				= $AudienceMetric->getUniquePageVisitsGA($ResultReturnType, $MetricReferenceDate);
	$GABounceDayCount 					= $AudienceMetric->getUniqueBounceGA($ResultReturnType, $MetricReferenceDate,'abs');
	$GABounceDayPerc 					= $AudienceMetric->getUniqueBounceGA($ResultReturnType, $MetricReferenceDate,'perc');

	$GAUniqueVisitorsDayCount 			= $AudienceMetric->getUniqueVistorsGA($ResultReturnType, $MetricReferenceDate);
	$GANewVisitorsDayCount				= $AudienceMetric->getUniqueNewVisitsGA($ResultReturnType, $MetricReferenceDate,'abs');
	$GANewVisitorsDayPerc 				= $AudienceMetric->getUniqueNewVisitsGA($ResultReturnType, $MetricReferenceDate,'perc');
	$GAReturningVisitorsDayPerc 		= $AudienceMetric->getPercUniqueReturningVisitsGA($ResultReturnType, $MetricReferenceDate);

	$GAAverageTimeOnSiteCount 			= $AudienceMetric->getAverageTimeOnSiteGA($ResultReturnType, $MetricReferenceDate);
	$GAAverageTimeOnPageCount			= $AudienceMetric->getAverageTimeOnPageGA($ResultReturnType, $MetricReferenceDate);
	$GAPagesPerVisitCount 				= $AudienceMetric->getNumberPagesPerVisitGA($ResultReturnType, $MetricReferenceDate);
	$GAEntranceBounceRatePerc 			= $AudienceMetric->getEntranceBounceRateGA($ResultReturnType, $MetricReferenceDate);

	// User and Profile Metrics
	$UserMetric = new Users($Connection);
	$UsersCount 						= $UserMetric->getUsersCount($ResultReturnType, $MetricType, $MetricReferenceDate);
	$UsersActiveProfileCount 			= $UserMetric->getActiveProfilesCount($ResultReturnType, $MetricType, $MetricReferenceDate);
	$UsersRawData 						= $UserMetric->getUsersRawData($ResultReturnType, $MetricType, $MetricReferenceDate, 'day' ,  'desc');

	// Teck Metrics
	$TeckMetric = new Tecks($Connection);
	$TecksCount 						= $TeckMetric->getTecksCount($ResultReturnType, $MetricType, $MetricReferenceDate);
	$TeckRawData 						= $TeckMetric->getTecksRawData($ResultReturnType, $MetricType, $MetricReferenceDate, 'day' ,  'desc');

	// Combining the Arrays for derived metrics
	$ResultingArray = array();
	foreach ($PageviewsRawData as $PVkey => $PVvalue) {
		//$ResultingArray[$PVkey] = array_merge($PVvalue);
		$ResultingArray[$PVkey] = array_merge($PVvalue, $UsersRawData[$PVkey], $TeckRawData[$PVkey] );

	}

	// Daily Views Information
	$DailyViews_RawInformation = $AudienceMetric->getDeltaDailyViewsRawInformation($ResultReturnType, $MetricReferenceDate);


?>

    <div id="wrap">
    
    
    	<!--BEGIN SIDEBAR-->
        <div id="menu" role="navigation">
        	<?php include "../includes/main_menu.php"; ?>
          
          <?php include "../includes/submenu_stats.php"; ?>
          
          
          <div class="clearfix"></div>
          
          
        </div>
        <!--SIDEBAR END-->
    
    	
        <!--BEGIN MAIN CONTENT-->
        <div id="main" role="main">
          <div class="block">
   		  <div class="clearfix"></div>
            
             <!--page title-->
             <div class="pagetitle">
                <h1>Audience Key Performance Indicators </h1> 
                <div class="clearfix"></div>
             </div>
             <!--page title end-->
         

             <div class="container_config_param radius">
	             <!-- Configuration Parameters -->
					<table class="select_period">
						<form method="post" action="stats_ViewAudienceMetrics.php">
							<tr valign=center>
								<!--td> Metric Type : <BR>
									<select name="MetricType">
										<option value="cons" <?php if ($MetricType=='cons') { echo "selected"; } else { echo "";} ?> >Consolidated</option>
										<option value="day"  <?php if ($MetricType=='day') 	{ echo "selected"; } else { echo "";} ?> >Daily</option>
									</select>
								</td-->
								<td> Aggregation Period: <BR>
									<select class="chzn-select chosen_select" name="AggregationPeriod">
										<option value="all" <?php if ($AggregationPeriod=='all') { echo "selected"; } else { echo "";}  ?>>Overall</option>
										<!--option value="year">Breakdown by Year</option-->
										<!--option value="semester">Breakdown By Semester</option-->
										<!--option value="quarter">Breakdown By Quarter</option-->
										<option value="month" <?php if ($AggregationPeriod=='month') { echo "selected"; } else { echo ""; }  ?>>Breakdown By Month</option>
										<!--option value="week">Breakdown By Week</option-->
										<option value="day" <?php if ($AggregationPeriod=='day') { echo "selected"; } else { echo ""; }  ?>>Breakdown By Day</option>
									</select>
								</td>
								<td>
									&nbsp; Reference Date for Reporting <br>
									&nbsp; <input type="text" name="MetricReferenceDate" value="<?php if (!empty($MetricReferenceDate)) {echo $MetricReferenceDate; }?> "></input><br>
									&nbsp;<span class="format">In format 'YYYY-mm-dd'</span>
								</td>
								<td>
									<div class="container_button">
										<input class="button_submit_form green" type='submit' name="go" value="go">
									</div>
								</td>
							</tr>
						</form>
					</table>
				</div>


				<div class="clearfix"></div>
				<h2 class="clearfix"> Aggregated Statistics </h2>

				<!-- General Information -->
				<h3 class="clearfix"> General Statistics from Teckler DB</h3>
				<!--Bordered Table-->
	              <div class="grid grid_table">
	              <div class="grid-content overflow">
	                <table align="center" class="table table-bordered table-mod-2 table_centered">
	                <thead>
	                  <tr>
	                    <th class="head_table_gray" colspan=2 align=center> Number of Pageviews </th> 
						<th class="head_table_gray" colspan=2 align=center> Number of Tecks Without Pageviews </th> 
						<th class="head_table_gray" colspan=2 align=center> Number of Tecks</th>
						<th class="head_table_gray" colspan=2 align=center> Number of Active Profiles </th>
						<th class="head_table_gray" colspan=2 align=center> Number of Profiles Without Pageviews</th>
						<th class="head_table_gray" colspan=2 align=center> Number of Users</th>
	                  </tr>
	                </thead>
	                <tbody>
	                  <tr>
	                    <td colspan=2 align=center> <?php echo number_format($PageviewsCount, 0, ".", ","); 				?></td> 
						<td colspan=2 align=center> <?php echo number_format($TeckWithoutPageviewsCount, 0, ".", ",");		?></td> 
						<td colspan=2 align=center> <?php echo number_format($TecksCount, 0, ".", ",");						?></td>
						<td colspan=2 align=center> <?php echo number_format($UsersActiveProfileCount, 0, ".", ",");		?></td>
						<td colspan=2 align=center> <?php echo number_format($ProfilesWithoutPageviewsCount, 0, ".", ",");	?></td>
						<td colspan=2 align=center> <?php echo number_format($UsersCount, 0, ".", ",");						?></td>
	                  </tr>
	                  <tr>
						<th class="head_table_gray" colspan=3 align=center> Average Pageviews Per Teck 	(DFP)		</th>
						<th class="head_table_gray" colspan=3 align=center> Weighted Average Pageviews Per Teck (DFP) </th>
						<th class="head_table_gray" colspan=3 align=center> Average Pageviews Per Profile (DFP)</th>
						<th class="head_table_gray" colspan=3 align=center> Weighted Average Pageviews Per Profile (DFP) </th>
						</tr>
					<tr>
						<td colspan=3 align=center> <?php echo number_format($AveragePageviewPerTeck, 4, ".", ",") ;?> 				</td>
						<td colspan=3 align=center> <?php echo number_format($WeightedAveragePageviewPerTeck, 4, ".", ",") ;?> 		</td>
						<td colspan=3 align=center> <?php echo number_format($AveragePageviewPerProfile, 4, ".", ",") ;?> 			</td>
						<td colspan=3 align=center> <?php echo number_format($WeightedAveragePageviewPerProfile, 4, ".", ",") ;?> 	</td>
					</tr>
	                </tbody>
	              </table>	                
	              <div class="clearfix"></div>
	              </div>
	              </div>
	              <!--Bordered Table END-->


				<h3> General Statistics from Google Analystics</h3>
				<!--Bordered Table-->
	              <div class="grid grid_table">
	              <div class="grid-content">
	                <table align="center" class="table table-bordered table-mod-2 table_centered">
	                <thead>
	                  <tr>
	                    <th class="head_table_gray" colspan=3 align=center> Pageviews of the Day</th> 
						<th class="head_table_gray" colspan=3 align=center> Page Visitis of the Day </th> 
						<th class="head_table_gray" colspan=3 align=center> Bounce Counter</th>
						<th class="head_table_gray" colspan=3 align=center> Bounce Percentage</th>
	                  </tr>
	                </thead>
	                <tbody>
	                  <tr>
	                    <td colspan=3 align=center> <?php echo number_format($GAPageviewsDayCount, 0, ".",","); 	?></td> 
						<td colspan=3 align=center> <?php echo number_format($GAPageVisitsDayCount, 0, ".",",");	?></td> 
						<td colspan=3 align=center> <?php echo number_format($GABounceDayCount, 0, ".",",");		?></td>
						<td colspan=3 align=center> <?php echo number_format($GABounceDayPerc, 2, ".",",");			?></td>
	                  </tr>
	                  <tr>
						<th class="head_table_gray" colspan=3 align=center> Unique Visitors of the Day 	</th>
						<th class="head_table_gray" colspan=3 align=center> New Visitors (%) 			</th>
						<th class="head_table_gray" colspan=3 align=center> Returning Visitors (%) 		</th>
						<th class="head_table_gray" colspan=3 align=center> Pages per Visit 			</th>
						</tr>
					<tr>
						<td colspan=3 align=center> <?php echo number_format($GAUniqueVisitorsDayCount, 0, ".",",") 	;?> 	</td>
						<td colspan=3 align=center> <?php echo number_format($GANewVisitorsDayPerc, 2, ".",",") 		;?> 	</td>
						<td colspan=3 align=center> <?php echo number_format($GAReturningVisitorsDayPerc, 2, ".",",") 	;?> 	</td>
						<td colspan=3 align=center> <?php echo number_format($GAPagesPerVisitCount, 2, ".",",") 		;?> 	</td>
					</tr>
					<tr>
						<th class="head_table_gray" colspan=6 align=center> Average Time Spent on Site (sec)	</th>
						<th class="head_table_gray" colspan=6 align=center> Average Time Spent on Pages (sec)</th>
					</tr>
					<tr>
						<td colspan=6 align=center> <?php echo number_format($GAAverageTimeOnSiteCount, 2, ".",",") 		;?> </td>
						<td colspan=6 align=center> <?php echo number_format($GAAverageTimeOnPageCount, 2, ".",",") 		;?> </td>
					</tr>
	                </tbody>
	              </table>	                
	              <div class="clearfix"></div>
	              </div>
	              </div>
	              <!--Bordered Table END-->			


			<h3> Accumulated Information - Average Pageviews in Tecks</h3>
				<!--Bordered Table-->
	              <div class="grid grid_table">
	              <div class="grid-content">
	                <table align="center" class="table table-bordered table-mod-2 table_centered">
	                <thead>
	                  <tr>
	                    <th class="head_table_gray" colspan=12 align=center> Average Accumulated Pageviews</th> 
	                  </tr>
	                </thead>
	                <tbody>
	                  <tr>
	                    <td colspan=12 align=center> <?php print_r(number_format($AveragePageviewsInTecks_Accumuldated, 2, ".", ",")); 	?> </td> 
	                  </tr>
	                </tbody>
	              </table>	                
	              <div class="clearfix"></div>
	              </div>
	              </div>
	              <!--Bordered Table END-->			

			<h3> Daily Views Information</h3>
				<!--Bordered Table-->
	              <div class="grid grid_table">
	              <div class="grid-content">
		                <table class="table table-striped table_centered">
		                <thead>
		                 <tr>
							<?php
								$keys = array_keys($DailyViews_RawInformation[0]);
								foreach ($keys as $key => $value) {
									echo "<th align=center>";
									echo $value;
									echo "</th>";
								}
								reset($DailyViews_RawInformation);
							?>
							</tr>
		                </thead>
		                <tbody>
		                  <?php 
							foreach ($DailyViews_RawInformation as $ekey => $evalue) {
								echo "<tr>";		
								foreach ($evalue as $ikey => $ivalue) {
									echo "<TD align=center>";
									echo ( is_numeric($ivalue) ? number_format($ivalue,0, ".",","): $ivalue); 
									echo "</TD>";
								}
								echo "</tr>";
								}
							?>	
		                </tbody>
		              </table>	                
	              <div class="clearfix"></div>
	              </div>
	              </div>
	              <!--Bordered Table END-->	






				  <!-- Average Page Views In Tecks  (Per Day, Per Month)-->
				  <h3> Average Pageviews Raw Information - Daily </h3>
				  <p> IMPORTANT : There is a delay of 4 days due to DFP consolidation rules..</p>
					<!--Striped table-->
		              <div class="grid grid_table">
		              <div class="grid-content">
		                <table class="table table-striped table_centered">
		                <thead>
		                 <tr>
							<?php
								$keys = array_keys($AveragePageviewsInTecks_PerDay_RawData[0]);
								foreach ($keys as $key => $value) {
									echo "<th align=center>";
									echo $value;
									echo "</th>";
								}
								reset($AveragePageviewsInTecks_PerDay_RawData);
							?>
							</tr>
		                </thead>
		                <tbody>
		                  <?php 
							foreach ($AveragePageviewsInTecks_PerDay_RawData as $ekey => $evalue) {
								echo "<tr>";		
								foreach ($evalue as $ikey => $ivalue) {
									echo "<TD align=center>";
									echo (is_numeric($ivalue) ? number_format($ivalue, 4, ".", ",") : $ivalue ); 
									echo "</TD>";
								}
								echo "</tr>";
								}
							?>	
		                </tbody>
		              </table>	                
		              <div class="clearfix"></div>
		              </div>
		              </div>
		              <!--Striped table END-->				  

				  <h3> Average Pageviews Raw Information - Monthly </h3>
				  <p> IMPORTANT : There is a delay of 4 days due to DFP consolidation rules..</p>
					<!--Striped table-->
		              <div class="grid grid_table">
		              <div class="grid-content">
		                <table class="table table-striped table_centered">
		                <thead>
		                 <tr>
							<?php
								$keys = array_keys($AveragePageviewsInTecks_PerMonth_RawData[0]);
								foreach ($keys as $key => $value) {
									echo "<th align=center>";
									echo $value;
									echo "</th>";
								}
								reset($AveragePageviewsInTecks_PerMonth_RawData);
							?>
							</tr>
		                </thead>
		                <tbody>
		                  <?php 
							foreach ($AveragePageviewsInTecks_PerMonth_RawData as $ekey => $evalue) {
								echo "<tr>";		
								foreach ($evalue as $ikey => $ivalue) {
									echo "<TD align=center>";
									echo (is_numeric($ivalue) ? number_format($ivalue, 6, ".", ",") : $ivalue ); 
									echo "</TD>";
								}
								echo "</tr>";
								}
							?>	
		                </tbody>
		              </table>	                
		              <div class="clearfix"></div>
		              </div>
		              </div>
		              <!--Striped table END-->	



				<!-- Pageviews Raw Data -->
				<h3> Pageviews Raw Information</h3>
				<!--Striped table-->
	              <div class="grid grid_table">
	              <div class="grid-content">
	                <table class="table table-striped table_centered">
	                <thead>
	                 <tr>
						<?php
							$keys = array_keys($ResultingArray[0]);
							foreach ($keys as $key => $value) {
								echo "<th align=center>";
								echo $value;
								echo "</th>";
							}
							reset($ResultingArray);
						?>
						</tr>
	                </thead>
	                <tbody>
	                  <?php 
						foreach ($ResultingArray as $ekey => $evalue) {
							echo "<tr>";		
							foreach ($evalue as $ikey => $ivalue) {
								echo "<TD align=center>";
								echo (is_numeric($ivalue) ?  number_format($ivalue, 2, ".", ",") : $ivalue ); 
								echo "</TD>";
							}
							echo "</tr>";
							}
						?>	
	                </tbody>
	              </table>	                
	              <div class="clearfix"></div>
	              </div>
	              </div>
	              <!--Striped table END-->







				<!-- Pageviews Per Teck Type -->
				<h3> Pageviews Per Type</h3>
				<!--Striped table-->
	              <div class="grid grid_table">
	              <div class="grid-content">
	                <table class="table table-striped table_centered">
	                <thead>
	                 <tr>
						<?php
							$keys = array_keys($PageviewsPerTeckTypeRawData[0]);
							foreach ($keys as $key => $value) {
								echo "<th align=center>";
								echo $value;
								echo "</th>";
							}
							reset($PageviewsPerTeckTypeRawData);
						?>
						</tr>
	                </thead>
	                <tbody>
	                  <?php 
						foreach ($PageviewsPerTeckTypeRawData as $ekey => $evalue) {
							echo "<tr>";		
							foreach ($evalue as $ikey => $ivalue) {
								echo "<TD align=center>";
								echo (is_numeric($ivalue) ?  number_format($ivalue, 2, ".", ",") : $ivalue ); 
								echo "</TD>";
							}
							echo "</tr>";
						}
						?>
	                </tbody>
	              </table>	                
	              <div class="clearfix"></div>
	              </div>
	              </div>
	              <!--Striped table END-->

				<!-- Alexa Raw Data -->
				<h3> Alexa Raw Information </h3>
				<!--Striped table-->
	              <div class="grid grid_table">
	              <div class="grid-content">
	                <table class="table table-striped table_centered">
	                <thead>
		                 <tr>
							<?php
								$keys = array_keys($AlexaGlobalRankingRawData[0]);
								foreach ($keys as $key => $value) {
									echo "<th align=center>";
									echo $value;
									echo "</th>";
								}
							reset($AlexaGlobalRankingRawData);
							?>
						</tr>	
	                </thead>
	                <tbody>
	                  <?php 
						foreach ($AlexaGlobalRankingRawData as $ekey => $evalue) {
							echo "<tr>";		
							foreach ($evalue as $ikey => $ivalue) {
								echo "<TD align=center>";
								echo (is_numeric($ivalue) ? number_format($ivalue, 2, ".", ",") : $ivalue ); 
								echo "</TD>";
							}
							echo "</tr>";
						}
						?>
	                </tbody>
	              </table>	                
	              <div class="clearfix"></div>
	              </div>
	              </div>
	              <!--Striped table END-->
				


				



				<!-- Google Analytics Raw Data -->
				<h3> Google Analytics Raw Information </h3>
				<!--Striped table-->
	              <div class="grid grid_table">
	              <div class="grid-content">
	                <table class="table table-striped table_centered">
	                <thead>
		                <tr>
						<?php
							$keys = array_keys($GARawDataDay[0]);
							foreach ($keys as $key => $value) {
								echo "<th align=center>";
								echo $value;
								echo "</th>";
							}
						reset($GARawDataDay);
						?>
					</tr>	
	                </thead>
	                <tbody>
	                  <?php 
						foreach ($GARawDataDay as $ekey => $evalue) {
							echo "<tr>";		
							foreach ($evalue as $ikey => $ivalue) {
								echo "<TD align=center>";
								echo (is_numeric($ivalue) ? number_format($ivalue, 4, ".", ",") :  $ivalue); 
								echo "</TD>";
							}
							echo "</tr>";
						}
						?>
	                </tbody>
	              </table>	                
	              <div class="clearfix"></div>
	              </div>
	              </div>
	              <!--Striped table END-->
				<div class="clearfix"></div>

              </div>
              
              </div>
              <!--Sample Table END-->
             
             
               
               <?php include "../includes/footer.php"; ?>
              
          <div class="clearfix"></div> 
          </div><!--end .block-->
        </div>
        <!--MAIN CONTENT END-->
    
    </div>
    <!--/#wrapper-->

    <?php include "../includes/java_scripts.php"; ?>


  </body>
</html>


