<?php

require_once "Connection.php";
require_once "Audience.php";
require_once "Users.php";
require_once "Tecks.php";

$Conn = new Connection();

$AudienceMetric = new Audience($Conn);
$UserMetric 	= new Users($Conn);
$TeckMetric		= new Tecks($Conn);


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
//Raw Information
$PageviewsRawData 				= $AudienceMetric->getPageviewsRawData($ResultReturnType, $MetricReferenceDate, $GroupingPeriod, 'desc');
$PageviewsPerTeckTypeRawData 	= $AudienceMetric->getPageviewsPerTeckTypeRawData($ResultReturnType, $MetricReferenceDate, $GroupingPeriod, 'desc');
$AlexaGlobalRankingRawData 		= $AudienceMetric->getAlexaRawData($ResultReturnType, $MetricReferenceDate, $GroupingPeriod, 'desc');

$PageviewsCount 					= $AudienceMetric->getPageviewsCount($ResultReturnType, $MetricReferenceDate);
$AveragePageviewPerTeck 			= $AudienceMetric->getAveragePageviewsPerTeckCount($ResultReturnType, $MetricReferenceDate);
$AveragePageviewPerProfile 			= $AudienceMetric->getAveragePageviewsPerProfileCount($ResultReturnType, $MetricReferenceDate);
$WeightedAveragePageviewPerTeck 	= $AudienceMetric->getWeightedAveragePageviewsPerTeckCount($ResultReturnType, $MetricReferenceDate);
$WeightedAveragePageviewPerProfile 	= $AudienceMetric->getWeightedAveragePageviewsPerProfileCount($ResultReturnType, $MetricReferenceDate);
$TeckWithoutPageviewsCount 			= $AudienceMetric->getTecksWithoutPageviews($ResultReturnType, $MetricReferenceDate, 'abs');
$ProfilesWithoutPageviewsCount 		= $AudienceMetric->getProfilesWithoutPageviews($ResultReturnType, $MetricReferenceDate, 'abs');


// Info from Google Analytics
$GARawDataDay 				= $AudienceMetric->getGoogleAnalyticsRawData($ResultReturnType, $MetricReferenceDate, 'desc');
$GAPageviewsDayCount 		= $AudienceMetric->getUniquePageviewsGA($ResultReturnType, $MetricReferenceDate);
$GAPageVisitsDayCount 		= $AudienceMetric->getUniquePageVisitsGA($ResultReturnType, $MetricReferenceDate);
$GABounceDayCount 			= $AudienceMetric->getUniqueBounceGA($ResultReturnType, $MetricReferenceDate,'abs');
$GABounceDayPerc 			= $AudienceMetric->getUniqueBounceGA($ResultReturnType, $MetricReferenceDate,'perc');

$GAUniqueVisitorsDayCount 	= $AudienceMetric->getUniqueVistorsGA($ResultReturnType, $MetricReferenceDate);
$GANewVisitorsDayCount		= $AudienceMetric->getUniqueNewVisitsGA($ResultReturnType, $MetricReferenceDate,'abs');
$GANewVisitorsDayPerc 		= $AudienceMetric->getUniqueNewVisitsGA($ResultReturnType, $MetricReferenceDate,'perc');
$GAReturningVisitorsDayPerc = $AudienceMetric->getPercUniqueReturningVisitsGA($ResultReturnType, $MetricReferenceDate);

$GAAverageTimeOnSiteCount 	= $AudienceMetric->getAverageTimeOnSiteGA($ResultReturnType, $MetricReferenceDate);
$GAAverageTimeOnPageCount	= $AudienceMetric->getAverageTimeOnPageGA($ResultReturnType, $MetricReferenceDate);
$GAPagesPerVisitCount 		= $AudienceMetric->getNumberPagesPerVisitGA($ResultReturnType, $MetricReferenceDate);
$GAEntranceBounceRatePerc 	= $AudienceMetric->getEntranceBounceRateGA($ResultReturnType, $MetricReferenceDate);

// User and Profile Metrics
$UsersCount 						= $UserMetric->getUsersCount($ResultReturnType, $MetricType, $MetricReferenceDate);
$UsersActiveProfileCount 			= $UserMetric->getActiveProfilesCount($ResultReturnType, $MetricType, $MetricReferenceDate);
$UsersRawData 						= $UserMetric->getUsersRawData($ResultReturnType, $MetricType, $MetricReferenceDate, 'day' ,  'desc');



// Combining the Arrays for derived metrics
$ResultingArray = array();
foreach ($PageviewsRawData as $PVkey => $PVvalue) {
	//$ResultingArray[$PVkey] = array_merge($PVvalue);
	$ResultingArray[$PVkey] = array_merge($PVvalue, $UsersRawData[$PVkey]);
	//$ResultingArray[$PVkey] = array_merge($PVvalue, $UsersRawData[$PVkey], $TeckRawData[$PVkey] );

}


?>


<html>
	<head> 
		<title>Audience Key Performance Indicators</title>
	</head>

	<body>
		<h1> <center>Audience Key Performance Indicators </center></h1>
		<hr>
		<!-- Configuration Parameters -->
		<table border=1>
			<form method="post" action="ViewAudienceMetrics.php">
				<tr valign=center>
					<!--td> Metric Type : <BR>
						<select name="MetricType">
							<option value="cons" <?php if ($MetricType=='cons') { echo "selected"; } else { echo "";} ?> >Consolidated</option>
							<option value="day"  <?php if ($MetricType=='day') 	{ echo "selected"; } else { echo "";} ?> >Daily</option>
						</select>
					</td-->
					<td> Aggregation Period: <BR>
						<select name="AggregationPeriod">
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
						&nbsp;<sup>In format 'YYYY-mm-dd'</sup>
					</td>
					<td><input type='submit' name="go" value="go"></td>
				</tr>
			</form>
		</table>


		<h2> Aggregated Statistics </h2>

		<!-- General Information -->
		<h3> General Statistics from Teckler DB</h3>
		<table border=1>
			<tr valign=center>
				<th colspan=4 align=center> Number of Pageviews </th> 
				<th colspan=4 align=center> Number of Tecks Without Pageviews </th> 
				<th colspan=4 align=center> Number of Profiles Without Pageviews</th>
			</tr>
			<tr>
				<td colspan=4 align=center> <?php echo $PageviewsCount; 				?> </td> 
				<td colspan=4 align=center> <?php echo $TeckWithoutPageviewsCount;		?></td> 
				<td colspan=4 align=center> <?php echo $ProfilesWithoutPageviewsCount;	?> </td>
			</tr>

			<tr>
				<th colspan=3 align=center> Average Pageviews Per Teck 			</th>
				<th colspan=3 align=center> Weighted Average Pageviews Per Teck </th>
				<th colspan=3 align=center> Average Pageviews Per Profile </th>
				<th colspan=3 align=center> Weighted Average Pageviews Per Profile </th>
			</tr>
			<tr>
				<td colspan=3 align=center> <?php echo $AveragePageviewPerTeck ;?> </td>
				<td colspan=3 align=center> <?php echo $WeightedAveragePageviewPerTeck ;?> </td>
				<td colspan=3 align=center> <?php echo $AveragePageviewPerProfile ;?> </td>
				<td colspan=3 align=center> <?php echo $WeightedAveragePageviewPerProfile ;?> </td>
			</tr>
		</table>
	
		<hr>

		<h3> General Statistics from Google Analystics</h3>
		<table border=1>
			<tr valign=center>
				<th colspan=3 align=center> Pageviews of the Day</th> 
				<th colspan=3 align=center> Page Visitis of the Day </th> 
				<th colspan=3 align=center> Bounce Counter</th>
				<th colspan=3 align=center> Bounce Percentage</th>
			</tr>
			<tr>
				<td colspan=3 align=center> <?php echo $GAPageviewsDayCount; 	?> </td> 
				<td colspan=3 align=center> <?php echo $GAPageVisitsDayCount;	?></td> 
				<td colspan=3 align=center> <?php echo $GABounceDayCount;		?> </td>
				<td colspan=3 align=center> <?php echo $GABounceDayPerc;		?> </td>
			</tr>

			<tr>
				<th colspan=3 align=center> Unique Visitors of the Day 			</th>
				<th colspan=3 align=center> New Visitors (%) </th>
				<th colspan=3 align=center> Returning Visitors (%) </th>
				<th colspan=3 align=center> Pages per Visit </th>

			</tr>
			<tr>
				<td colspan=3 align=center> <?php echo $GAUniqueVisitorsDayCount 	;?> </td>
				<td colspan=3 align=center> <?php echo $GANewVisitorsDayPerc 		;?> </td>
				<td colspan=3 align=center> <?php echo $GAReturningVisitorsDayPerc 	;?> </td>
				<td colspan=3 align=center> <?php echo $GAPagesPerVisitCount 		;?> </td>
			</tr>
			<tr>
				<th colspan=6 align=center> Average Time Spent on Site	</th>
				<th colspan=6 align=center> Average Time Spent on Pages </th>
			</tr>
			<tr>
				<td colspan=6 align=center> <?php echo $GAAverageTimeOnSiteCount 		;?> </td>
				<td colspan=6 align=center> <?php echo $GAAverageTimeOnPageCount 		;?> </td>
			</tr>
		</table>
	
		<hr>

		<!-- Pageviews Raw Data -->
		<h3> Pageviews Raw Information</h3>
		<table border=1>
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
			<?php 
				foreach ($ResultingArray as $ekey => $evalue) {
					echo "<tr>";		
					foreach ($evalue as $ikey => $ivalue) {
						echo "<TD align=center>";
						echo $ivalue; 
						echo "</TD>";
					}
					echo "</tr>";
				}
			?>
		</table>
		<hr>

		<!-- Pageviews Per Teck Type -->
		<h3> Pageviews Per Type</h3>
		<table border=1>
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
			<?php 
				foreach ($PageviewsPerTeckTypeRawData as $ekey => $evalue) {
					echo "<tr>";		
					foreach ($evalue as $ikey => $ivalue) {
						echo "<TD align=center>";
						echo $ivalue; 
						echo "</TD>";
					}
					echo "</tr>";
			}
			?>
		</table>
		<hr>

		<!-- Alexa Raw Data -->
		<h3> Alexa Raw Information </h3>
		<table border=1>
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
			<?php 
				foreach ($AlexaGlobalRankingRawData as $ekey => $evalue) {
					echo "<tr>";		
					foreach ($evalue as $ikey => $ivalue) {
						echo "<TD align=center>";
						echo $ivalue; 
						echo "</TD>";
					}
					echo "</tr>";
			}
			?>
		</table>
		<HR>

		<!-- Google Analytics Raw Data -->
		<h3> Google Analytics Raw Information </h3>
		<table border=1>
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
			<?php 
				foreach ($GARawDataDay as $ekey => $evalue) {
					echo "<tr>";		
					foreach ($evalue as $ikey => $ivalue) {
						echo "<TD align=center>";
						echo $ivalue; 
						echo "</TD>";
					}
					echo "</tr>";
			}
			?>
		</table>

	</body>

</html>
