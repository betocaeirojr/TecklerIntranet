<?php

require "Users.php";

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

$ResultReturnType = 'array';
	
// Fetch Metrics Data
$UserMetric = new Users();
$aUsersRawData 					= $UserMetric->getUsersRawData($ResultReturnType, $MetricType, $MetricReferenceDate, $GroupingPeriod ,  'desc');
$aUsersCount 					= $UserMetric->getUsersCount($ResultReturnType, $MetricType, $MetricReferenceDate);
$aProfilesCount 				= $UserMetric->getProfilesCount($ResultReturnType, $MetricType, $MetricReferenceDate);
$aActiveProfilesCount 			= $UserMetric->getActiveProfilesCount($ResultReturnType, $MetricType, $MetricReferenceDate);
$aAvgProfilesPerUser 			= $UserMetric->getAverageProfilesPerUser($ResultReturnType, $MetricType, $MetricReferenceDate);
$aAvgActiveProfilesPerUser 		= $UserMetric->getAverageActiveProfilesPerUser($ResultReturnType, $MetricType, $MetricReferenceDate);
$aUsersWithOnly1ProfileCount 	= $UserMetric->getUsersWithOnly1Profile($ResultReturnType, $MetricReferenceDate);
$aWavgProfilesPerUser 			= $UserMetric->getWeightedAverageProfilesPerUser($ResultReturnType, $MetricReferenceDate);
$aUsersPerLanguageData 			= $UserMetric->getUsersPerLanguage($ResultReturnType, $MetricType, $MetricReferenceDate);
$aEngagedProfilesCount	 		= $UserMetric->getEngagedProfiles($ResultReturnType, $MetricReferenceDate);
$aLoggedUsersCount 				= $UserMetric->getLoggedUsers($ResultReturnType, $MetricReferenceDate);
$aUsersWithKeepMeLoggedOnCount = $UserMetric->getUsersWithAutoLogin($ResultReturnType, $MetricReferenceDate);

// Preparing the info about Users Per Language
foreach ($aUsersPerLanguageData  as $key => $value) {
	foreach ($value as $ikey => $ivalue) {
		if ($ikey == 'LANG') {
			$LangCode[] = $ivalue;
		} 
		if ( $ikey == 'TOTAL_USERS_LANG' || $ikey == 'DELTA_USERS_LANG_DAY' ) {
			$NumUsersLang[] = $ivalue;
		}
	}
}

?>
<html>
	<head> 
		<title>User and Profiles Key Performance Indicators</title>
	</head>

	<body>
		<h1> <center>Users and Profiles Key Performance Indicators </center></h1>
		<hr>
		<!-- Configuration Parameters -->
		<table border=1>
			<form method="post" action="ViewUsersMetrics.php">
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
				<th colspan=6 align=center> Number of Users </th> <th colspan=6 align=center> Number of Profiles </th>
			</tr>
			<tr>
				<td colspan=6 align=center> <?php echo $aUsersCount; ?> </td> <td colspan=6 align=center> <?php echo $aProfilesCount;?></td>
			</tr>
			<tr>
				<th colspan=3 align=center> Number of Active Profiles<sup>1</sup></th> <th colspan=3 align=center> Number of Engaged Users <sup>2</sup></th>
				<th colspan=3 align=center> Number of Unique Logged Users<sup>3</sup></th> <th colspan=3 align=center> Number of Users with only 1 Profile</th>
			</tr>
			<tr>
				<td colspan=3 align=center> <?php echo $aActiveProfilesCount;?> </td> <td colspan=3 align=center> <?php echo $aEngagedProfilesCount;?> </td>
				<td colspan=3 align=center> <?php echo $aLoggedUsersCount;?> </td> <td colspan=3 align=center> <?php echo $aUsersWithOnly1ProfileCount;?> </td>
			</tr>
			<tr>
				<th colspan=4 align=center> Average Number of Profiles Per User</th>
				<th colspan=4 align=center> Average Number of Active Profiles Per User</th>
				<th colspan=4 align=center> Weighted Average Number of Profiles Per User</th>
			</tr>
			<tr>
				<td colspan=4 align=center> <?php echo $aAvgProfilesPerUser ;?> </td>
				<td colspan=4 align=center> <?php echo $aAvgActiveProfilesPerUser ;?> </td>
				<td colspan=4 align=center> <?php echo $aWavgProfilesPerUser ;?> </td>
			</tr>
		</table>
		<p>1. Active Profiles = Profiles With at Least 1 published teck.<br>
		   2. Engaged Profiles = Profiles with published tecks int the previous 90 days.<br>
		   3. Logged Users = Number of Users that logged YESTERDAY</p>

		<hr>

		<!-- User Per Language -->
		<h3> Users Per Language</h3>
		<table border=1>
			<tr>
			<?php foreach ($LangCode as $value) { ?>	
				<th width=3 align=center> <?php echo $value; ?>	 </th>		
			<?php } ?>
			</tr>
			<tr>
				<?php foreach ($NumUsersLang as $value) { ?>	
				<td align=center> <?php echo $value; ?>	 </td>		
			<?php } ?>
			</tr>
		</table>
		<hr>
		
		<!-- Raw Data -->
		<h3> User Raw Data - Breakdown by Day</h3>
		<table border=1>
			<tr>
				<?php
					$keys = array_keys($aUsersRawData[0]);
					foreach ($keys as $key => $value) {
						echo "<th align=center>";
						echo $value;
						echo "</th>";
					}
				reset($aUsersRawData);
				?>

			</tr>	
			<?php 
				foreach ($aUsersRawData as $ekey => $evalue) {
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
		<p> 1. Weighted Average of Profiles Per Users: Considering ONLY users with more than 1 profile. </p>

	</body>

</html>
