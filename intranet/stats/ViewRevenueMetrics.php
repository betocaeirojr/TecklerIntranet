<?php

require "Revenue.php";

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
	$yesterday = mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"));
	$MetricReferenceDate = date('Y-m-d',$yesterday);
}

$MetricReferenceDate = date('2013-08-05');

$ResultReturnType = 'array';
	
// Fetch Metrics Data
$RevenueMetric = new Revenue();
// Raw Data
$aRevenueRawDataInfo 					= $RevenueMetric->getRevenueRawData($ResultReturnType, $MetricType, $MetricReferenceDate,'desc');

// Revenue Breakdown ( Expected / Actual)
$aExpectedRevenueAmmount 			= $RevenueMetric->getExpectedRevenueCount($ResultReturnType, $MetricType, $MetricReferenceDate);
$aActualRevenueAmmount 				= $RevenueMetric->getActualRevenueCount($ResultReturnType, $MetricType, $MetricReferenceDate);

// Revenue Breakdown By Type - Payment Lifecycle
$aPendingRevenueAmmount 			= $RevenueMetric->getPendingRevenueCount($ResultReturnType, $MetricType, $MetricReferenceDate);
$aVerifiedRevenueAmmount 			= $RevenueMetric->getVerifiedRevenueCount($ResultReturnType, $MetricType, $MetricReferenceDate);
$aRequestedRevenueAmmount 			= $RevenueMetric->getRequestedRevenueCount($ResultReturnType, $MetricType, $MetricReferenceDate);
$aWithdrawnRevenueAmmount 			= $RevenueMetric->getWithdrawnRevenueCount($ResultReturnType, $MetricType, $MetricReferenceDate);
$aErrorRevenueAmmount 				= $RevenueMetric->getErrorRevenueCount($ResultReturnType, $MetricType, $MetricReferenceDate);

// Average Revenue
$aAverageRevenuePerProfile 			= $RevenueMetric->getAverageRevenue($ResultReturnType, $MetricReferenceDate, 'profile');
$aAverageRevenuePerTeck 			= $RevenueMetric->getAverageRevenue($ResultReturnType, $MetricReferenceDate, 'teck');
$aWeightedAverageRevenuePerProfile 	= $RevenueMetric->getWeightedAverageRevenue($ResultReturnType, $MetricReferenceDate, 'profile');
$aWeightedAverageRevenuePerTeck 	= $RevenueMetric->getWeightedAverageRevenue($ResultReturnType, $MetricReferenceDate, 'teck');

$aTransferRequestedDayCount			= $RevenueMetric->getNumberOfTransferRequested($ResultReturnType, $MetricReferenceDate);

?>

<html>
	<head> 
		<title>Revenue Key Performance Indicators</title>
	</head>

	<body>
		<h1> <center>Revenue Key Performance Indicators </center></h1>
		<hr>
		<!-- Configuration Parameters -->
		<table border=1>
			<form method="post" action="ViewRevenueMetrics.php">
				<tr valign=center>
					<td> Metric Type : <BR>
						<select name="MetricType">
							<option value="cons" <?php if ($MetricType=='cons') { echo "selected"; } else { echo "";} ?> >Consolidated</option>
							<option value="day"  <?php if ($MetricType=='day') 	{ echo "selected"; } else { echo "";} ?> >Daily</option>
						</select>
					</td>
					
					<!--td> Aggregation Period: <BR>
						<select name="AggregationPeriod">
							<option value="all" <?php if ($AggregationPeriod=='all') { echo "selected"; } else { echo "";}  ?>>Overall</option>
							<option value="year">Breakdown by Year</option>
							<option value="semester">Breakdown By Semester</option>
							<option value="quarter">Breakdown By Quarter</option>
							<option value="month" <?php if ($AggregationPeriod=='month') { echo "selected"; } else { echo ""; }  ?>>Breakdown By Month</option>
							<option value="week">Breakdown By Week</option>
							<option value="day" <?php if ($AggregationPeriod=='day') { echo "selected"; } else { echo ""; }  ?>>Breakdown By Day</option>
						</select>
					</td-->
					<td>
						&nbsp; Reference Date for Reporting <br>
						&nbsp; <input type="text" name="MetricReferenceDate" value="<?php if (!empty($MetricReferenceDate)) {echo $MetricReferenceDate; }?> "></input><br>
						&nbsp;<sup>In format 'YYYY-mm-dd'</sup>
					</td>
					<td><input type='submit' name="go" value="go"></td>
				</tr>
			</form>
		</table>


		<h2> <?php 
			if ($MetricType=='cons') { 
				echo "Aggregate Statistics";
			} elseif ($MetricType=='day') {  
				echo "Daily Statistics";
			} 
			?>
		</h2>

		<!-- General Information -->
		<h3> General Statistics </h3>
		<table border=1>
			<!-- Actual vs Expected Revenue-->
			<tr valign=center>
				<th colspan=10 align=center> Expected Revenue </th> 
				<th colspan=10 align=center> Actual Revenue <sup>1</sup></th> 
			</tr>
			<tr>
				<td colspan=10 align=center> <?php echo $aExpectedRevenueAmmount; ?> </td> 
				<td colspan=10 align=center> <?php echo $aActualRevenueAmmount;?></td> 
			</tr>

			<!-- Revenue by Payment LifeCycle-->
			<tr>
				<th colspan=4 align=center> Pending Revenue (US$)				</th>
				<th colspan=4 align=center> Verified Revenue (US$)	<sup>2</sup></th>
				<th colspan=4 align=center> Requested Revenue (US$)	<sup>2</sup></th>
				<th colspan=4 align=center> Withdrawn Revenue (US$)	<sup>2</sup></th>
				<th colspan=4 align=center> Error Revenue (US$)		<sup>2</sup></th>
			</tr>
			<tr>
				<td colspan=4 align=center> <?php echo $aPendingRevenueAmmount ;?> 		</td>
				<td colspan=4 align=center> <?php echo $aVerifiedRevenueAmmount ;?>  	</td>
				<td colspan=4 align=center> <?php echo $aRequestedRevenueAmmount ;?> 	</td>
				<td colspan=4 align=center> <?php echo $aWithdrawnRevenueAmmount ;?> 	</td>
				<td colspan=4 align=center> <?php echo $aErrorRevenueAmmount ;?>  		</td>
			</tr>
			<!-- Average and Weighted Average Per Teck and Per Profile-->
			<tr>
				<th colspan=10 align=center> Tecks</th>
				<th colspan=10 align=center> Profiles</th>
			</tr>
			<tr>
				<th colspan=5 align=center> Average Revenue Per Teck</th>
				<th colspan=5 align=center> Weighted Average Revenue Per Viewed Teck</th>
				<th colspan=5 align=center> Average Revenue per Profile</th>
				<th colspan=5 align=center> Weighted Average Revenue Per Viewed Profile</th>
			</tr>
			<tr>
				<td colspan=5 align=center> <?php echo $aAverageRevenuePerTeck ;?> 			</td>
				<td colspan=5 align=center> <?php echo $aWeightedAverageRevenuePerTeck ;?>  </td>
				<td colspan=5 align=center> <?php echo $aAverageRevenuePerProfile ;?> 		</td>
				<td colspan=5 align=center> <?php echo $aWeightedAverageRevenuePerProfile ;?> 	</td>

			</tr>
		</table>
		<p>1. Actual Revenue = Revenue already verified by Google, after a 32 days period.<br>
		   2. Actual Revenue = Verified + Requested + Withdrawn + Error<br>

		<hr>
		<table border=1>
			<tr>
				<th colspan=20 align=center> Total Number of Fund Transfers Requested </th>
			</tr>
			<tr>
				<td colspan=20 align=center> <?php echo $aTransferRequestedDayCount ;?> </td>
			</tr>
		</table>

		<!-- Raw Data -->
		<h3> Tecks Raw Data - Breakdown by Day</h3>
		<table border=1>
			<tr>
				<?php
					$keys = array_keys($aRevenueRawDataInfo[0]);
					foreach ($keys as $key => $value) {
						echo "<th align=center>";
						echo $value;
						echo "</th>";
					}
				reset($aRevenueRawDataInfo);
				?>

			</tr>	
			<?php 
				foreach ($aRevenueRawDataInfo as $ekey => $evalue) {
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
