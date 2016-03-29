<?php

require "Tecks.php";

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

if (isset($_POST['MetricType'])){ $MetricType = $_POST['MetricType']; } else { $MetricType = 'cons'; }

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

$ResultReturnType = 'array';
	
// Fetch Metrics Data
$TeckMetric = new Tecks();
// Raw Data
$aTecksRawData 					= $TeckMetric->getTecksRawData($ResultReturnType, $MetricType, $MetricReferenceDate, $GroupingPeriod ,  'desc');
$aTecksPerLanguageData 			= $TeckMetric->getTecksPerLanguage($ResultReturnType, $MetricType, $MetricReferenceDate, $GroupingPeriod, 'desc');
$aTecksPerTypeData 				= $TeckMetric->getTecksPerType($ResultReturnType, $MetricType, $MetricReferenceDate, $GroupingPeriod,'desc');

// General Information
$aTecksCount	 				= $TeckMetric->getTecksCount($ResultReturnType, $MetricType, $MetricReferenceDate);
$aPublishedTecksCount			= $TeckMetric->getPublishedTecksCount($ResultReturnType, $MetricType, $MetricReferenceDate);
$aRatioTecksPubTecks			= $TeckMetric->getRatioTecksByPublishedTecks($ResultReturnType, $MetricType, $MetricReferenceDate);
$aAverageTecksPerProfile		= $TeckMetric->getAverageTecksPerProfile($ResultReturnType, $MetricType, $MetricReferenceDate);
$aAverageTecksPerActiveProfile	= $TeckMetric->getAverageTecksPerActiveProfile($ResultReturnType, $MetricType, $MetricReferenceDate);
$aProfilesWOTecksCount			= $TeckMetric->getProfilesWithoutTeck($ResultReturnType, $MetricType, $MetricReferenceDate);


?>
<html>
	<head> 
		<title>Tecks Key Performance Indicators</title>
	</head>

	<body>
		<h1> <center>Tecks Key Performance Indicators </center></h1>
		<hr>
		<!-- Configuration Parameters -->
		<table border=1>
			<form method="post" action="ViewTecksMetrics.php">
				<tr valign=center>
					<td> Metric Type : <BR>
						<select name="MetricType">
							<option value="cons" <?php if ($MetricType=='cons') { echo "selected"; } else { echo "";} ?> >Consolidated</option>
							<option value="day"  <?php if ($MetricType=='day') 	{ echo "selected"; } else { echo "";} ?> >Daily</option>
						</select>
					</td>
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
			<tr valign=center>
				<th colspan=4 align=center> Number of Tecks </th> 
				<th colspan=4 align=center> Number of Published Tecks <sup>2</sup></th> 
				<th colspan=4 align=center> Number of Profiles Without Tecks</th>
			</tr>
			<tr>
				<td colspan=4 align=center> <?php echo $aTecksCount; ?> </td> 
				<td colspan=4 align=center> <?php echo $aPublishedTecksCount;?></td> 
				<td colspan=4 align=center> <?php echo $aProfilesWOTecksCount;?> </td>
			</tr>

			<tr>
				<th colspan=4 align=center> Average Number of Tecks Per Profile</th>
				<th colspan=4 align=center> Average Number of Tecks Per Active Profile<sup>1</sup></th>
				<th colspan=4 align=center> Ratio - Published Tecks / Tecks </th>
			</tr>
			<tr>
				<td colspan=4 align=center> <?php echo $aAverageTecksPerProfile ;?> </td>
				<td colspan=4 align=center> <?php echo $aAverageTecksPerActiveProfile ;?> </td>
				<td colspan=4 align=center> <?php echo $aRatioTecksPubTecks ;?> </td>
			</tr>
		</table>
		<p>1. Active Profiles = Profiles With at Least 1 published teck.<br>
		   2. Published Tecks = Tecks actually published (disconsidering Drafts or Fraud Tecks).<br>

		<hr>

		<!-- Tecks Per Language -->
		<h3> Tecks Per Language</h3>
		<table border=1>
			<tr>
				<th>Date </th>
				<th>ar</th>
				<th>de</th>
				<th>en</th>
				<th>es</th>
				<th>fr</th>
				<th>it</th>
				<th>he</th>
				<th>hi</th>
				<th>jp</th>
				<th>ko</th>
				<th>pt</th>
				<th>ru</th>
				<th>zh</th>
			</tr>
			<?php 
				foreach ($aTecksPerLanguageData as $ekey => $evalue) {
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





		<!-- Tecks Per Type -->
		<h3> Tecks Per Type</h3>
		<table border=1>
			<tr>
			<?php
				$keys = array_keys($aTecksPerTypeData[0]);
				foreach ($keys as $key => $value) {
					echo "<th align=center>";
					echo $value;
					echo "</th>";
				}
				reset($aTecksPerTypeData);
			?>
			</tr>
			<?php 
				foreach ($aTecksPerTypeData as $ekey => $evalue) {
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

		
		<!-- Raw Data -->
		<h3> Tecks Raw Data - Breakdown by Day</h3>
		<table border=1>
			<tr>
				<?php
					$keys = array_keys($aTecksRawData[0]);
					foreach ($keys as $key => $value) {
						echo "<th align=center>";
						echo $value;
						echo "</th>";
					}
				reset($aTecksRawData);
				?>

			</tr>	
			<?php 
				foreach ($aTecksRawData as $ekey => $evalue) {
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
